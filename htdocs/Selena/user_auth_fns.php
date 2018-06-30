<?
require_once("db_fns.php");

$Menu_Item = "not select";
$id_Podjezd = 0;
$Bill_Dog = 0;
$Cod_flat = 0;
global $new_Cod; // = 0;
$new_Cod = 0;
global $row_customer;
global $arr_customers;

global $Nics;
$totalRows_customer = 0;

$IPb = $_SERVER["REMOTE_ADDR"]=="127.0.0.1"?'10.1.5.115':'10.1.5.15';

function register($username, $email, $password)
// register new person with db
// return true or error message
{
 // connect to db
  $conn = db_connect();
  if (!$conn)
    return "Невозможно соединиться с базой - пожалуйста, попробуйте позже.";

  // check if username is unique 
  $result = mysql_query("select * from v_personal where login='$username'"); 
  if (!$result)
     return "Невозможно выполнить запрос";
  if (mysql_num_rows($result)>0) 
     return "Такое имя уже существует - вернитесь и попробуйте другое.";

  // if ok, put in db
  $result = mysql_query("insert into personal (login, passwd, Name, id_TypePers ) values 
                         ('$username', password('$password'), '$email', 1)"); //
  if (!$result)
    return "Невозможно зарегистрировать - пожалуйста, попробуйте позже.";

  return true;
}
 
function login($username, $password)
// check username and password with db
// if yes, return true
// else return false
{
  // connect to db
  $conn = db_connect();
  if (!$conn)
    return 0;
  // check if username is unique
  $result = mysql_query("select * from v_personal
                         where login='$username'
                         and passwd = password('$password')");
  $res = mysql_query("insert into logs (DT,login,state,ip) values ('".date("Y-m-d H:i:s").
						"','$username',".(mysql_num_rows($result)>0?1:0).",'".$_SERVER["REMOTE_ADDR"]."')");

  if (!$result)
     return 0;
  
  if (mysql_num_rows($result)>0)
     return 1;
  else 
     return 0;
}

function f_get_Pers($username)
{
  	$res = mysql_query("SELECT * FROM v_personal where login='$username'") or die(mysql_error());
	$row_Pers = mysql_fetch_assoc($res);
	return $row_Pers;
}
function f_get_TypePers($username)
{
	$q_TabNum = "SELECT * FROM personal where login='$username'";
  	$TabNum = mysql_query($q_TabNum) or die(mysql_error());

	$row_TabNum = mysql_fetch_assoc($TabNum);
	return $row_TabNum["id_TypePers"];
}

function f_get_TabNum($username)
{
	$q_TabNum = "SELECT * FROM personal where login='$username'";
  	$TabNum = mysql_query($q_TabNum) or die(mysql_error());

	$row_TabNum = mysql_fetch_assoc($TabNum);
	return $row_TabNum["TabNum"];
}

function check_valid_user()
// see if somebody is logged in and notify them if not
{
  global $valid_user;
  if (session_is_registered("valid_user"))
  {
//      echo "Зарегистрирован как $valid_user.";
//      echo "<br>";
  }
  else
  {
     // they are not logged in 
     do_html_heading("Проблема:");
     echo "Вы незарегистрированны.<br>";
     do_html_url("login.php", "Login");
     do_html_footer();
     exit;
  }  
}

function change_password($username, $old_password, $new_password)
// изменить пароль для username/old_password на new_password
// возвращает true или false
{
  // if the old password is right 
  // change their password to new_password and return true
  // else return false
  if (login($username, $old_password))
  {
    if (!($conn = db_connect()))
      return false;
    $result = mysql_query( "update personal
                            set passwd = password('$new_password')
                            where login = '$username'");
    if (!$result)
      return false;  // not changed
    else
      return true;  // changed successfully
  }
  else
    return false; // old password was wrong
}

function get_random_word($min_length, $max_length)
// grab a random word from dictionary between the two lengths
// and return it
{
   // generate a random word
  $word = "";
  $dictionary = "/usr/dict/words";  // the ispell dictionary
  $fp = fopen($dictionary, "r");
  $size = filesize($dictionary);

  // go to a random location in dictionary
  srand ((double) microtime() * 1000000);
  $rand_location = rand(0, $size);
  fseek($fp, $rand_location);

  // get the next whole word of the right length in the file
  while (strlen($word)< $min_length || strlen($word)>$max_length) 
  {  
     if (feof($fp))   
        fseek($fp, 0);        // if at end, go to start
     $word = fgets($fp, 80);  // skip first word as it could be partial
     $word = fgets($fp, 80);  // the potential password
  };
  $word=trim($word); // trim the trailing \n from fgets
  return $word;  
}

function reset_password($username)
// set password for username to a random value
// return the new password or false on failure
{ 
  // get a random dictionary word b/w 6 and 13 chars in length
  $new_password = get_random_word(6, 13);
 
  // add a number  between 0 and 999 to it
  // to make it a slightly better password
  srand ((double) microtime() * 1000000);
  $rand_number = rand(0, 999); 
  $new_password .= $rand_number;
 
  // set user's password to this in database or return false
  if (!($conn = db_connect()))
      return false;
  $result = mysql_query( "update user
                          set passwd = password('$new_password')
                          where username = '$username'");
  if (!$result)
    return false;  // not changed
  else
    return $new_password;  // changed successfully  
}

function notify_password($username, $password)
// notify the user that their password has been changed
{
    if (!($conn = db_connect()))
      return false;
    $result = mysql_query("select email from user
                            where username='$username'");
    if (!$result)
      return false;  // not changed
    else if (mysql_num_rows($result)==0)
      return false; // username not in db
    else
    {
      $email = mysql_result($result, 0, "email");
      $from = "From: support@phpbookmark \r\n";
      $mesg = "Ваш пароль был изменён на $password \r\n"
              ."Пожалуйста измените его при следующем входе. \r\n";
      if (mail($email, " login information", $mesg, $from))
        return true;      
      else
        return false;     
    }
} 

function get_num_noti()
{
	$q_N_noti = "SELECT max(`Num_Notify`) AS `MAX_noti` FROM notify_repair";
  	$rq_noti = mysql_query($q_N_noti) or die(mysql_error());
	$r_N_noti = mysql_fetch_assoc($rq_noti);
//	$Num_Notify = $r_N_noti["MAX_noti"] + 1;
	return $r_N_noti["MAX_noti"]+1;
}

function check_Cod_flat($Cod_flat)
{
	$q_Cod_flat = "SELECT 1 FROM `cod_flat` where Cod_flat=$Cod_flat order by Cod_flat";//customers
  	$Cod_flat = mysql_query($q_Cod_flat) or die(mysql_error());
	return mysql_num_rows($Cod_flat)==0;
}

function check_flat($id_p, $fl)
{
	$q_Cod_flat = "SELECT 1 FROM `cod_flat` where id_Podjezd=$id_p and flat=$fl order by id_Podjezd, flat";//customers
  	$v_Cod_flat = mysql_query($q_Cod_flat) or die($q_Cod_flat." ".mysql_error());
	return mysql_num_rows($v_Cod_flat)==0;
}

function get_adr($Bill_Dog) {
	$res =  mysql_query("select Cod_flat, id_Podjezd, flat from `customers` where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	return $row;//['Cod_flat']
}

function get_Cod_flat($id_p, $fl)
{
	$q_Cod_flat = "SELECT Cod_flat FROM `cod_flat` where id_Podjezd=$id_p and flat=$fl";//customers
  	$v_Cod_flat = mysql_query($q_Cod_flat) or die(mysql_error());
	if(mysql_num_rows($v_Cod_flat)==0) {
		put_Cod_flat("id_Podjezd,flat", "$id_p,$fl");
		return get_Cod_flat($id_p, $fl);
	}
	$row_Cod_flat = mysql_fetch_assoc($v_Cod_flat);
	return $row_Cod_flat["Cod_flat"];
}

function new_Cod_flat()
{
	$q_Cod_flat = "SELECT max(`Cod_flat`) AS `MAX_Cod_flat` FROM `cod_flat` ";// customers MAX(Cod_flat)
  	$v_Cod_flat = mysql_query($q_Cod_flat) or die(mysql_error());
	$row_Cod_flat = mysql_fetch_assoc($v_Cod_flat);
	return $row_Cod_flat["MAX_Cod_flat"]+1;
}

function put_Cod_flat($s, $v)
{
	$q_Cod_flat = "insert into `cod_flat` ($s) values ($v)";
  	$v_Cod_flat = mysql_query($q_Cod_flat) or die(mysql_error());
	echo "Адресу присвоен код</br>";
}

function get_Bill_Dog()
{
	$q_Bill_Dog = "SELECT * FROM `v_bill_dog` ";// customers MAX(Bill_Dog)
  	$v_Bill_Dog = mysql_query($q_Bill_Dog) or die(mysql_error());
	$row_Bill_Dog = mysql_fetch_assoc($v_Bill_Dog);
	return $row_Bill_Dog["MAX_Bill_Dog"]+1;
}

function get_inf_acc($account) {
	$res =  mysql_query("select Bill_Dog, Nic, Login from `logins` where `account`=$account") or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	return $row; //["Bill_Dog"]
}

function get_Bill_acc($account) {
	$res =  mysql_query("select Bill_Dog from `logins` where `account`=$account") or die(mysql_error());
	$row_B = mysql_fetch_assoc($res);
	return $row_B["Bill_Dog"];
}

function get_Bill_Log($Login) {
	$res =  mysql_query("select Bill_Dog from `logins` where `Login`=$Login") or die(mysql_error());
	$row_B = mysql_fetch_assoc($res);
	return $row_B["Bill_Dog"];
}

function ch_cod($Bill_Dog) {
	$res =  mysql_query("select 1 from `v_hist_cod` where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
	return mysql_num_rows($res)>0;
}

function get_cod($Bill_Dog) {
	$res =  mysql_query("select `Cod_flat` from `customers` where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	return $row["Cod_flat"];
}

function put_old_cod($Bill_Dog) {
	$res =  mysql_query("select * from `customers` where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	$q_ins_cod = "insert into `hist_cod` (Bill_Dog,ch_date,TabNum,new_cod) values ($Bill_Dog,".
				($row["tarifab_date"]==""?"NULL,NULL":"'".$row["tarifab_date"]."',".$row["TabNum"]).",".$row["Cod_flat"].")";
	$s_ins_cod =  mysql_query($q_ins_cod) or die(mysql_error());
}

function put_new_cod($Bill_Dog, $Cod_flat, $TabNum) {
//	$res =  mysql_query("select * from `customers` where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
//	$row = mysql_fetch_assoc($res);
	$q_ins_cod = "insert into `hist_cod` (Bill_Dog,ch_date,new_cod,TabNum) values ($Bill_Dog,'".date("Y-m-d H:i:s")."',$Cod_flat, $TabNum)";
	$s_ins_cod =  mysql_query($q_ins_cod) or die(mysql_error());
}

function ins_Login($account, $Bill_Dog, $Nic, $Login, $id_tarif3w, $tarif3w_date)
{
	$sL_col = "account, Bill_Dog, Nic, Login, id_tarif3w, tarif3w_date";
	$vL_col = "$account, $Bill_Dog,'$Nic','$Login', $id_tarif3w, $tarif3w_date";
/* 	echo "</br>".*/	$qL_ins_cust = "insert into `logins` (".$sL_col.") values (".$vL_col.")";
	$sL_ins_nics =  mysql_query($qL_ins_cust) or die(mysql_error());	
}

function put_noti2conn ($conn, $Date_Plan, $Bill_Dog, $Cod_flat, $id_p, $fl, $TabNum) {	//
//		$Date_Plan=date("Y-m-d",mktime(0,0,0,date("m"),date("d")/*+3*/,date("Y")));
//		$dp = strtotime($Date_pay);		$Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
		$Date_in = date("Y-m-d H:i:s");
		echo "Заявка на подключение абонента с плановой датой:$Date_Plan. ";
		$s_col =  "Cod_flat, Bill_Dog, conn,  Notify,      Date_Plan,   Date_in,  TabNum, id_p, fl";					
		$v_col = "$Cod_flat,$Bill_Dog,$conn,'подключение','$Date_Plan','$Date_in',$TabNum,$id_p,$fl";
	
		$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
		$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		echo "Внесена</br>";
}

function put_noti2off ($Date_pay, $Bill_Dog, $Cod_flat, $id_p, $fl, $TabNum) {	//
		$dp = strtotime($Date_pay);		$Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
		$Date_in = date("Y-m-d H:i:s");
		echo "Заявка на отключение абонента, плановая дата:$Date_Plan. ";
		$s_col = "Cod_flat, Bill_Dog, conn,  Notify,      Date_Plan,   Date_in,  TabNum, id_p, fl";					
		$v_col = "$Cod_flat,$Bill_Dog, -1,'откл.(долг)','$Date_Plan','$Date_in',$TabNum,$id_p,$fl";
	
		$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
		$s_ins_noti =  mysql_query($q_ins_noti) or die($q_ins_noti." ".mysql_error());
		echo "Внесена</br>";
}

function is_off_dolg($BD){ // отсутствует заявка на откл
	$s_not =  mysql_query("select 1 from `notify_repair` where Bill_dog=$BD and Notify='откл.(долг)' and conn=-1 and Date_Fact is null") or die(mysql_error());
//	$row_not = mysql_fetch_array($s_not, MYSQL_ASSOC);
	return mysql_num_rows($s_not) == 0;
}

function check_off($Bill_Dog) {	/* есть ли заявка на отключение за долг */
	$res =  mysql_query("select 1 from `notify_repair` where `Bill_Dog`=$Bill_Dog and `Notify` = 'откл.(долг)' and `Date_Fact` IS NULL") or die(mysql_error());
	return mysql_num_rows($res) > 0;
}

function check_st($Bill_Dog, $conn) {
	if ($conn==6) {	/* Переоформление	*/
		$res =  mysql_query("select `state` from `customers` where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
		if (mysql_num_rows($res)>0) {
			$row = mysql_fetch_assoc($res);
			return $row["state"] >0?"state=1, inet=null, ":"";
		}
	}
	return "";
}

function get_acc($account) {
	return $account;
	$r_acc =  mysql_query("select account from acc_bill where Bill_Dog=$account") or die(mysql_error());
	$acc = mysql_fetch_array($r_acc, MYSQL_ASSOC);
	return /*mysql_num_rows($acc)>0?*/$acc["account"];
}

function fcmd($fp, $cmd) {
//return send_command($fp, $cmd);

//echo date("Y-m-d H:i:s")." ";
	fputs ($fp, "$cmd\n");	$s = '';
//echo date("Y-m-d H:i:s")." ";
	while (false !== ($char = fgets($fp))) { $s .= "$char"; }
//	echo date("Y-m-d H:i:s")."<br>";
	return $s;
}

function send_command($socket, $command) 
{ 
   socket_write($socket, $command . "\n", strlen($command) + 1); 
   while ($out = socket_read($socket, 256)) // 
   { 
      if(preg_match('/#/i',$out)) ///#/i
		return $out;//(true); 
   } 
}
function get_000($socket, $command) 
{ //echo $command,"<br>";
   socket_write($socket, $command . "\n", strlen($command) + 1); 
   while ($out = socket_read($socket, 512)) // 
   { 
   //   if(preg_match('/401/i',$out)) 
        if(strpos($out,'401')>0) 
         return $out; //(true); 
      if(preg_match('/000/i',$out)) 
         return $out; //(true); 
/*      if(preg_match('/406/i',$out)) 
         return (false); // $out; //(true); */
   } 
}

function get_wellcome($socket) 
{ 
   while ($out = socket_read($socket, 1024)) // 
   { 
      if(preg_match('/Ready/i',$out)) 
         return(true);
   } 
}

function getNic($s) {
	if ($s=="000") return "";
	$n_ = explode(chr(9), strstr($s, "adder"));
	$nic = isset($n_[2])?explode(chr(10), $n_[2]):array(0=>"");
	return $nic[0];
}

function getSum($s) {
	$s2 = ltrim(strstr(strstr($s, ")"), " "));
	$s3 = explode(" ", $s2);
	return $s3[0];
}

function isFrosen($s) {
	$s2 = ltrim(strstr($s, "-"), " ");
	return substr($s2, 5, 1)=="F"; //
}

function isOFF($s) {
	$s2 = ltrim(strstr($s, "-"), " ");
	return substr($s2, 4, 1)=="O"; //
}
/**/
function HACK($varforsql){ // ФУНКЦИЯ ДЛЯ ФИЛЬТРОВКИ ТОГО, ЧТО ПИШУТ ЛЮДИ В ТЕРМИНАЛЕ, В ОСНОВНОМ ЗАЩИТА ОТ ИНЬЕКЦИЙ SQL
$varforsql=str_replace('`',"&#96;",$varforsql);
$varforsql=str_replace("'","&#39;",$varforsql);
$varforsql=str_replace('"',"&#34;",$varforsql);
$varforsql=str_replace('\\',"&#92;",$varforsql);
$varforsql=str_replace('/',"&#47;",$varforsql);
$varforsql=str_replace("<","&#60;",$varforsql);
$varforsql=str_replace(">","&#62;",$varforsql);
$varforsql=str_replace("*","&#42;",$varforsql);
$varforsql=str_replace('­',"&#45;",$varforsql);
return $varforsql;
}

function isRadreply($mac) {
	$link = mysql_connect("localhost", "radik", "1597") or die("Невозможно соединиться: " . mysql_error());
	$s_ins = "select 1 from radius.radreply where username='$mac';";
	$res = mysql_query($s_ins) or die(mysql_error());
//	mysql_close($link);
	return mysql_num_rows($res)>0;
}

function do_on_cust($BD) {
	$today = date("Y-m-d");
	$now = date("Y-m-d H:i:s");
	$Date_Fact = $today;
	$TabNum = 17;	// авто
$q_ = mysql_query("SELECT
notify_repair.`Cod_flat`,
notify_repair.Bill_Dog,
notify_repair.Notify,
notify_repair.Num_Notify,
notify_repair.conn,
notify_repair.Date_Plan,
notify_repair.Date_Fact,
v_customer.mac,
v_customer.Nic,
v_customer.Date_pay
FROM
	notify_repair
	Inner Join v_customer ON notify_repair.Bill_Dog = v_customer.Bill_Dog
WHERE
	v_customer.Bill_Dog = $BD and
	notify_repair.conn =  '1' AND
	notify_repair.Date_Plan <=  curdate() AND
	notify_repair.Date_Fact IS NULL  AND
	v_customer.auto =  '1' AND
	length(v_customer.mac) >  0 AND
	v_customer.state =  '2' and
	v_customer.Date_pay > curdate()
") or die(mysql_error());
if (($num_r = mysql_num_rows($q_)) == 0) {
//	fdebug("<br>&nbsp;&nbsp;Подключать некого<br>");
	return;
}
//echo "Подключаемых абонентов - ",$num_r, "<br>";
/*while (*/$row = mysql_fetch_array($q_, MYSQL_ASSOC);	//)  { //mysql_fetch_assoc($q_, MYSQL_ASSOC)
	// Закрытие заявки	'$today'
//	echo "Договор ", $row['Bill_Dog']," заявка на ",$row['Date_Plan']," оплачено по ",$row['Date_pay']," - ";
 	$q_noti = "update notify_repair set Date_Fact=Date_Plan,mont=$TabNum,Date_ed='$now' where Num_Notify = {$row['Num_Notify']}";
	$s_noti =  mysql_query($q_noti) or die(mysql_error());
	
//		$Date_end_st = (empty($Date_end_st)?"null":"'$Date_end_st'");	$today
 	$q_cust = "update customers set state=1, Date_start_st='{$row['Date_Plan']}', Date_end_st = '{$row['Date_pay']}', DateKor='$now', mont=$TabNum where `Bill_Dog`={$row['Bill_Dog']}";
	$s_cust =  mysql_query($q_cust) or die(mysql_error());
//	fdebug("<img src='create_check.gif'/><br>");
//	}

$link = mysql_connect("localhost", "radik", "1597") or die("Невозможно соединиться: " . mysql_error());

$s_ins = "insert into radius.radcheck (username, nasipaddress) select username, nasipaddress from Selena.v_radcheck as c where c.Bill_Dog=$BD;";
$r_ins = mysql_query($s_ins) or die(mysql_error());
//fdebug("radius.radcheck готов!<br>");

$s_ins = "insert into radius.radreply (username, value, nasipaddress) select username, value, nasipaddress from Selena.v_radreply as c where c.Bill_Dog=$BD;";
$r_ins = mysql_query($s_ins) or die(mysql_error());
//fdebug("radius.radreply готов!<br>");

mysql_close($link);
$conn = db_connect();

}
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
?>