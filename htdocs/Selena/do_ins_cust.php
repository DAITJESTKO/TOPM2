<?
require_once("for_form.php"); 
check_valid_user();
  $conn = db_connect();
  if (!$conn) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
	$id_p  = $_REQUEST ["id_p"];
	$fl  = $_REQUEST ["fl"];
	$Cod_flat = $_REQUEST ["Cod_flat"];
	$Bill_Dog = $_REQUEST ["Bill_Dog"];
	$Nic = $_REQUEST ["Nic"];
	$conn = $_REQUEST ["conn"];
echo	$id_tarifab = $_REQUEST ["id_tarifab"];
	$tarifab_date = $_REQUEST ["tarifab_date"];
	$mac  = get_param (/*$_REQUEST [*/"mac");
	$floor = get_param (/*$_REQUEST [*/"floor");
	$Fam = get_param (/*$_REQUEST [*/"Fam");
	$Name = get_param (/*$_REQUEST [*/"Name");
	$Father = get_param (/*$_REQUEST [*/"Father");
	$pasp_Ser = get_param (/*$_REQUEST [*/"pasp_Ser");
	$pasp_Num = get_param (/*$_REQUEST [*/"pasp_Num");
	$pasp_Date = get_param (/*$_REQUEST [*/"pasp_Date");
	$pasp_Uvd = get_param (/*$_REQUEST [*/"pasp_Uvd");
	$pasp_Adr = get_param (/*$_REQUEST [*/"pasp_Adr");
	$phone_Home = get_param (/*$_REQUEST [*/"phone_Home");
	$phone_Cell = get_param (/*$_REQUEST [*/"phone_Cell");
	$phone_Work = get_param (/*$_REQUEST [*/"phone_Work");
	$Jur = get_param (/*$_REQUEST [*/"Jur");
	$Birthday = get_param (/*$_REQUEST [*/"Birthday");
	$Comment = get_param ("Comment");
	$DateKor = date("Y-m-d H:i:s");
	if ($_REQUEST ["tarif3w_date"]) {
		$Login = $_REQUEST ["Login"];
		$id_tarif3w = $_REQUEST ["id_tarif3w"];
		$tarif3w_date = $_REQUEST ["tarif3w_date"];
	}
//	$mont = $_REQUEST ["mont"];
//	$pay = $_REQUEST ["pay"];
	$conn_pay = $_REQUEST ["conn_pay"];
	$abon_p = ($_REQUEST ["abon_p"]=='')? 0:$_REQUEST ["abon_p"];
	$inet_pay = (isset($_REQUEST ["inet_Cpay"]))?$_REQUEST ["inet_Cpay"]: 0;
	$Date_start_st = $_REQUEST ["Date_start_st"];
	$Date_end_st = $_REQUEST ["Date_end_st"];
	$Date_pay = $_REQUEST ["Date_pay"];
	$Y = date("Y");
// 	echo $Nic.'</br>';
/*	
	if (isset($_REQUEST ["Cod_flat"])) {
		$s_cor_Cod_flat =  mysql_query("update customers set Cod_flat=".$_REQUEST["Cod_flat"]." where id_Podjezd=$id_p and flat=$fl") or die(mysql_error());
	}
*/
echo "<======";
return;
//----------------------------------------------------------------------------------	
if ($_REQUEST ["tarif3w_date"]) {
	echo "Создание интернет учётки ... ";
// Login FROM Logins where Login='".$Login."'"; 
$account = get_Bill_Dog(); // пока не работает автодобавление из-за сдвоенных счетов
	$sL_col = "account,  Nic, Login, id_tarif3w, tarif3w_date";	//Bill_Dog,, id_Login, Num_PC
	$vL_col = "$account, '$Nic','$Login', '$id_tarif3w', '$tarif3w_date'";	//$Bill_Dog,, 1, $Num_PC
	if (strlen($inet_pay)>0) {$sL_col = $sL_col.",saldo";		$vL_col = $vL_col.",'$inet_pay'";}
	$qL_ins_cust = "insert into `logins` (".$sL_col.") values (".$vL_col.")";
// 	echo "</br>".$qL_ins_cust;
	$sL_ins_login =  mysql_query($qL_ins_cust) or die(mysql_error());
	$get_acc = "select account from logins where Login='$Login'";
	$s_ins_login =  mysql_query($get_acc) or die(mysql_error());
	$a_acc = mysql_fetch_assoc($s_ins_login);
	echo "счёт биллинга - ", $account = $a_acc["account"], "<br>";
	/*if (!get_cod($Bill_Dog)) {	*/
		$Bill_Dog = $account;	/*	}	*/
	$s_ins_login =  mysql_query("update logins set Bill_Dog = $Bill_Dog where Login='$Login'") or die(mysql_error());
	echo "Прикоеплён к абон.договору № $Bill_Dog<br>";
}	else {
	echo "Не установлена дата подключения к интернету!";
	return;
}
//----------------------------------------------------------------------------------	  
	$s_col = "id_Podjezd, flat";						$v_col = "$id_p, $fl";
	if(check_flat($id_p, $fl)) { put_Cod_flat($s_col, $v_col); }
	$Cod_flat = get_Cod_flat($id_p, $fl);
	if (/*in_array(array(16,17,18,19,24),*/$_REQUEST["conn"]/*)*/== 16) {  // Дополнительный комп
		$s_tar_id = "select id_tarifab from customers where Cod_flat=$Cod_flat and inet is null";
	}
	
	$s_col = "Cod_flat,".$s_col;						$v_col = "$Cod_flat,".$v_col;
	
	$s_col = $s_col. ", mac, Bill_Dog, Nic, id_tarifab, tarifab_date, Date_start_st, Date_end_st, Date_pay";
	$v_col = $v_col. ",'$mac',$Bill_Dog,'$Nic','$id_tarifab','$tarifab_date','$Date_start_st','$Date_end_st','$Date_pay'";

	if (strlen($floor)>0) {$s_col = $s_col.", floor";			$v_col = $v_col.", '$floor'";}
	if (strlen($pasp_Ser)>0) {$s_col = $s_col. ", pasp_Ser";	$v_col = $v_col.", '$pasp_Ser'";}
	if (strlen($pasp_Num)>0) {$s_col = $s_col.", pasp_Num";		$v_col = $v_col.", '$pasp_Num'";}
	if (strlen($pasp_Date)>0) {$s_col = $s_col.", pasp_Date";	$v_col = $v_col.", '$pasp_Date'";}
	if (strlen($pasp_Uvd)>0) {$s_col = $s_col.", pasp_Uvd";		$v_col = $v_col.", '$pasp_Uvd'";}
	if (strlen($pasp_Adr)>0) {$s_col = $s_col.", pasp_Adr";		$v_col = $v_col.", '$pasp_Adr'";}
	if (strlen($Comment)>0) {$s_col = $s_col.", Comment";		$v_col = $v_col.", '$Comment'";}
	if (strlen($phone_Home)>0) {$s_col = $s_col.", phone_Home";	$v_col = $v_col.", '$phone_Home'";}
	if (strlen($phone_Cell)>0) {$s_col = $s_col.", phone_Cell";	$v_col = $v_col.", '$phone_Cell'";}
	if (strlen($phone_Work)>0) {$s_col = $s_col.", phone_Work";	$v_col = $v_col.", '$phone_Work'";}
	if (strlen($Jur)>0) {$s_col = $s_col.", Jur";				$v_col = $v_col.", $Jur";}
	if (strlen($Name)>0) {$s_col = $s_col.", Name";				$v_col = $v_col.", '$Name'";}
	if (strlen($Father)>0) {$s_col = $s_col.", Father";			$v_col = $v_col.", '$Father'";}
	if (strlen($Fam)>0) {$s_col = $s_col.", Fam";				$v_col = $v_col.", '$Fam'";}
	if (strlen($Birthday)>0) {$s_col = $s_col.", Birthday";		$v_col = $v_col.", '$Birthday'";}
//	if (strlen($mont)>0) {$s_col = $s_col." mont,";				$v_col = $v_col.", '$mont',";}
	if (strlen($conn)>0) {$s_col = $s_col.", conn";				$v_col = $v_col.", '$conn'";}
	if (strlen($DateKor)>0) {$s_col = $s_col.", DateKor";		$v_col = $v_col.", '$DateKor'";}
	if (strlen($TabNum)>0) {$s_col = $s_col.", TabNum";			$v_col = $v_col.", '$TabNum'";}
	if (strlen($abon_p)>0) {$s_col = $s_col.", Saldo";			$v_col = $v_col.", '$abon_p'";}

	$q_ins_cust = "insert into `customers` (".$s_col.") values (".$v_col.")";
	$s_ins_cust =  mysql_query($q_ins_cust) or die(mysql_error());	

//----------------------------------------------------------------------------------	
/*	$sN_col = "Bill_Dog, Nic, id_tarifab, tarifab_date, Date_start_st, Date_end_st, Date_pay";
	$vN_col = "$Bill_Dog, '$Nic', '$id_tarifab', '$tarifab_date', '$Date_start_st', '$Date_end_st', '$Date_pay'";
//	$qN_ins_cust = "insert into `nics` (".$sN_col.") values (".$vN_col.")";
//!!	$qN_ins_cust = "insert into `customers` (".$sN_col.") values (".$vN_col.")";
// 	echo "</br>".$qN_ins_cust."</br>";
//!!	$sN_ins_nics =  mysql_query($qN_ins_cust) or die(mysql_error());	
*/
//----------------------------------------------------------------------------------	
/*	$Notify = "подключение";	$Date_in = $DateKor;	$Date_Plan = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+3,date("Y")));
	$s_col = "Cod_flat, Bill_Dog,";								$v_col = $_REQUEST ["Cod_flat"].", ".$_REQUEST ["Bill_Dog"].",";
	if (strlen($Notify)>0) {$s_col = $s_col." Notify,";			$v_col = $v_col." '$Notify',";}
	if (strlen($Date_Plan)>0) {$s_col = $s_col." Date_Plan,";	$v_col = $v_col." '$Date_Plan',";}
	if (strlen($Date_in)>0) {$s_col = $s_col." Date_in,"; 		$v_col = $v_col." '$Date_in',";}
	if (strlen($TabNum)>0) {$s_col = $s_col." TabNum,"; 		$v_col = $v_col." '$TabNum',";}
//	if (strlen($mont)>0) {$s_col = $s_col." mont,"; 			$v_col = $v_col." ".$_REQUEST ["mont"].",";}
//	if (strlen($Date_Fact)>0) {$s_col = $s_col." Date_Fact,"; 	$v_col = $v_col." '$Date_Fact',";}
//	if (strlen($)>0) {$s_col = $s_col." ,";						$v_col = $v_col." '$',";}
	if (strlen($conn)>0) {$s_col = $s_col." conn,";				$v_col = $v_col." '$conn',";}
	$s_col = $s_col. " id_p, fl";								$v_col = $v_col. " ".$_REQUEST ["id_p"].", ".$_REQUEST ["fl"];

	$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
//	echo $q_ins_noti."</br>";
	$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
	echo "Внесена заявка на подключение абонента. Плановая дата исполнения:$Date_Plan.</br>";
*/	
//*********************	Заявки на откл/подкл
	$Date_in = date("Y-m-d H:i:s");	$dp = strtotime($Date_pay);//$DateKor;

	$Notify1 = "подкл.(новое)";	$Date_Plan_1 = date("Y-m-d", mktime(0,0,0,date("m"),date("d")/*+3*/,date("Y")));
	$Notify2 = "откл.(долг)";	$Date_Plan_2 = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
	//	$Date_Plan = strtotime($Date_pay);
	
	$s_col = "Cod_flat,Bill_Dog,";	$v_col = "$Cod_flat, $Bill_Dog,";
	$s_col = $s_col."Date_in,"; 	$v_col = $v_col." '$Date_in',";
	$s_col = $s_col."TabNum,"; 		$v_col = $v_col." '$TabNum',";
	$s_col = $s_col. "id_p,fl";		$v_col = $v_col." ".$_REQUEST ["id_p"].", ".$_REQUEST ["fl"];
	
	echo "Заявка на подключение абонента, с плановой датой исполнения:$Date_Plan_1... ";
	$q_ins_noti = "insert into `notify_repair` (Date_Plan,conn,Notify,$s_col) values ('$Date_Plan_1',1,'$Notify1',$v_col)";
	$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
	echo "Внесена.</br>";

	echo "Заявка на отключение абонента, с плановой датой исполнения:$Date_Plan_2... ";
	$q_ins_noti = "insert into `notify_repair` (Date_Plan,conn,Notify,$s_col) values ('$Date_Plan_2',-1,'$Notify2',$v_col)";
	$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
	echo "Внесена.</br>";
//*********************	
//*********************	
	echo "Информация о платеже за подключение ... ";
	$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_start_st','$Date_start_st',$conn_pay,1,'оплата за подкл.')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_start_st','$Date_start_st',$conn_pay,1,'оплата за подкл.')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	echo "Добавлена.</br>";
	

//*********************	
	echo "Информация о платеже абонплаты ... ";
	$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_start_st','$Date_end_st',$abon_p,1,'абон.пл. при подкл.')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_start_st','$Date_end_st',$abon_p,1,'абон.пл. при подкл.')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	echo "Добавлена.</br>";
	
//*********************	
if ($_REQUEST ["tarif3w_date"] && $inet_pay>0) {
	echo "Информация о платеже за интернет ... ";
	$q_ins_inet = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','$Login','".date("Y-m-d H:i:s")."','".date("Y-m-d")."','".date("Y-m-d")."',$inet_pay,2,'за интернет при подкл.')";//		.'</br>'
	$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
	$q_ins_inet = "insert into `actions` (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','$Login','".date("Y-m-d H:i:s")."','".date("Y-m-d")."','".date("Y-m-d")."',$inet_pay,2,'за интернет при подкл.')";//		.'</br>'
	$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
	echo "Добавлена.</br>";
}
//*********************	
	echo 'Обновить экран. <button name="B_chk_adress" type=button onClick="f=document.forms.ulaForm;f.sCod_flat.value = '.$Cod_flat.'; f.h_Cod_flat.value = '.$Cod_flat.';'.
	'setTimeout(&quot;f.sCod_flat.onchange();&quot;, 1000);"><img src="reload.png" align=middle alt="Обнови"></button>';
//return;
/*	$vN_col = (strlen($Bill_Dog)==0)? " " : " $Bill_Dog,";
	$vN_col = $vN_col. (strlen($tarifab)==0)? " " : " $tarifab,";
	$vN_col = $vN_col. (strlen($tarifab_date)==0)? " " : " $tarifab_date,";
	$vN_col = $vN_col. " $Num_PC";
*/
//*	$s_ins_cust =  mysql_query($q_cor_cust) or die(mysql_error());	
//	$row_cor_cust = mysql_fetch_assoc($s_cor_cust);
//	$totalRows_cor_cust = mysql_num_rows($s_cor_cust);
// 	echo $totalRows_cor_cust;
	// 	echo $row_test;
/***********************************************************************************/
function get_param ($name) {
	return $$name = isset($_REQUEST [$name])?$_REQUEST [$name]:"";
	//return ($$name==""? "" : " `{$name}`='{$$name}',");
}
function set_query($v,$nm)
{
//	$v = "$".$nm;
	if (strlen($v)>0) {$s_col = $s_col." '".$nm."',";}
	if (strlen($v)>0) {$v_col = $v_col." '".$nm."',";}
//	if (strlen($$v)>0) {$v_col = $v_col." '".$nm."',";}
}
//	$s_col = $s_col. (strlen($)==0)? " " : " ,";
?>