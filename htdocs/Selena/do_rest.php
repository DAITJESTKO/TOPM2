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

	$id_p  			= " id_Podjezd=".$_REQUEST ["id_p"];
	$Nic  			= " Nic='".$_REQUEST ["Nic"]."'";
	$Bill_Dog  		= " Bill_Dog=".$_REQUEST ["Bill_Dog"];
	$Login  		= " Login='".$_REQUEST ["Login"]."'";
	$Nic_  			= "'".$_REQUEST ["Nic"]."'";
	$Login_  		= "'".$_REQUEST ["Login"]."'";
	$tarif3w_date_	= "'".$_REQUEST ["tarif3w_date"]."'";		//(($tarifab=="")? " " : " tarifab=".$tarifab.",");
	$id_tarif3w_ 	= $_REQUEST ["id_tarif3w"];
	$fl 			= $_REQUEST ["fl"];
//	echo $Login,"</br>";
//	$Nic = get_param("Nic");					//(($Nic=="")? " " : " Nic='".$Nic."',");
//	$ = get_param("");							//(($Bill_Dog=="")? " " : " Bill_Dog=".$Bill_Dog.",");
	$floor = get_param("floor");				//(($floor=="")? " " : " floor='".$floor."',");
	$Fam = get_param("Fam");					//(($Fam=="")? " " : " Fam='".$Fam."',");
	$Name = get_param("Name");					//(($Name=="")? " " : " Name='".$Name."',");
	$Father = get_param("Father");				//(($Father=="")? " " : " Father='".$Father."',");
	$pasp_Ser = get_param("pasp_Ser");			//(($pasp_Ser=="")? " " : " pasp_Ser='".$pasp_Ser."',");
	$pasp_Num = get_param("pasp_Num");			//(($pasp_Num=="")? " " : " pasp_Num='".$pasp_Num."',");
	$Birthday = get_param("Birthday");			//(($Birthday=="")? " " : " Birthday='".$Birthday."',");
	$pasp_Date = get_param("pasp_Date");		//(($pasp_Date=="")? " " : " pasp_Date='".$pasp_Date."',");
	$pasp_Uvd = get_param("pasp_Uvd");			//(($pasp_Uvd=="")? " " : " pasp_Uvd='".$pasp_Uvd."',");
	$pasp_Adr = get_param("pasp_Adr");			//(($pasp_Adr=="")? " " : " pasp_Adr='".$pasp_Adr."',");
	$Comment = get_param("Comment");
	$phone_Home = get_param("phone_Home");		//(($phone_Home=="")? " " : " phone_Home='".$phone_Home."',");
	$phone_Cell = get_param("phone_Cell");		//(($phone_Cell=="")? " " : " phone_Cell='".$phone_Cell."',");
	$phone_Work = get_param("phone_Work");		//(($phone_Work=="")? " " : " phone_Work='".$phone_Work."',");
	$Jur = get_param("Jur");					//(($Jur=="")? " " : " Jur=".$Jur.",");
	$From_Net = get_param("From_Net");
	$id_tarifab = get_param("id_tarifab");		//(($tarifab=="")? " " : " tarifab=".$tarifab.",");
	$tarifab_date = get_param("tarifab_date");	//(($tarifab_date=="")? " " : " tarifab_date=".$tarifab_date.",");
	$Date_start_st = get_param("Date_start_st");
	$Date_end_st = get_param("Date_end_st");
	$Date_pay = get_param("Date_pay");
	$TabNum = get_param("TabNum");				//(($TabNum=="")? " " : " TabNum='".$TabNum."',");
	$DateKor = "'".date("Y-m-d H:i:s")."'";		//get_param("DateKor");			//(($DateKor=="")? " " : " DateKor='".$DateKor."',");
	$id_tarif3w = get_param("id_tarif3w");		//(($tarifab=="")? " " : " tarifab=".$tarifab.",");
	$tarif3w_date = get_param("tarif3w_date");	//(($tarifab_date=="")? " " : " tarifab_date=".$tarifab_date.",");
	$conn = get_param("conn");
//	$mont = get_param("mont");
	$conn_pay = 1*$_REQUEST ["conn_pay"];
	$abon_pay = 1*$_REQUEST ["abon_pay"];
	$inet_pay = 1*$_REQUEST ["inet_pay"];
	$Cod_flat = get_param("Cod_flat");
$Y = date("Y");
	$TConn = array(0=>'Корректировка',1 =>'Новое подкл.',2 =>'Доп.компьютер',3 =>'Смена владельца',4 =>'Переподключение',5 =>'Смена адреса', 6 =>'Переоформление');

	if ($_REQUEST["conn"]==5) {
		if (!ch_cod($_REQUEST ["Bill_Dog"])) { put_old_cod($_REQUEST ["Bill_Dog"]); }
		put_new_cod($_REQUEST ["Bill_Dog"]);
	}
	$s_col = "Cod_flat, id_Podjezd, flat";						$v_col = $_REQUEST["Cod_flat"].", ".$_REQUEST["id_p"].", ".$_REQUEST["fl"];
	if(check_flat($_REQUEST["id_p"], $_REQUEST["fl"])) {	echo "Присвоение кода адреса... ";
		put_Cod_flat($s_col, $v_col);
		$q_cor_Cod_flat = "update `customers` set Cod_flat=".$_REQUEST ["Cod_flat"]." where $id_p and flat=$fl";
		$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die(mysql_error());	// echo "Ошибка при установке кода адреса.\n";
	}
	
if (isset($_REQUEST ["Cod_flat"])) {
	$q_cor_Cod_flat = "update `customers` set Cod_flat=".$_REQUEST ["Cod_flat"]." where $id_p and flat=$fl";
	$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die(mysql_error());	// echo "Ошибка при установке кода адреса.\n";
} else {
	$q_cor_Cod_flat = "select Cod_flat from `customers` where $id_p and flat=$fl";
	$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die(mysql_error());
	$row_Cod_flat = mysql_fetch_array($s_cor_Cod_flat, MYSQL_ASSOC);
	if (!($row_Cod_flat["Cod_flat"]>0)) { echo "Ошибка установки кода адреса!"; return; }
	$Cod_Cod_flat = " Cod_flat=".$row_Cod_flat["Cod_flat"].", ";
}

	echo "Изменения в основные данные и данные о подключении к сети... ";
	$q_cor_cust = "update `customers` set Cod_flat=".$_REQUEST ["Cod_flat"].",".
		"$floor $Fam $Name $Father $Birthday $pasp_Ser $pasp_Num $pasp_Date $pasp_Uvd $pasp_Adr $Comment ".
		"$phone_Home $phone_Cell $phone_Work $Jur $From_Net $conn $TabNum `DateKor`=$DateKor,".($_REQUEST["conn"]==6?"state=1,":"").//check_st($_REQUEST ["Bill_Dog"], $_REQUEST["conn"]).
		"$Date_start_st $Date_end_st $Date_pay $id_tarifab $tarifab_date ".
		"Saldo=Saldo+(".(1*$conn_pay).")+(".(1*$abon_pay)."),$Nic, inet=null".
		" where $id_p and flat=$fl and $Bill_Dog";// $mont	($_REQUEST ["Date_start_st"]==$_REQUEST ["Date_end_st"]?"":"$Date_start_st $Date_end_st $Date_pay ").
//echo $q_cor_cust.'</br>'; // $Cod_flat.
//return;
	$s_cor_cust =  mysql_query($q_cor_cust) or die(mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	echo "Внесены.</br>";
/*	
	echo "Изменения в данные подключения к сети... ";
//	$q_cor_nics = "update `nics` set $id_tarifab $tarifab_date $Date_start_st $Date_end_st $Date_pay $Nic where $Nic and $Bill_Dog";
	$q_cor_nics = "update `customers` set $id_tarifab $tarifab_date $Date_start_st $Date_end_st $Date_pay $Nic where $Nic and $Bill_Dog";
//		echo $q_cor_nics.'</br>';
	$s_cor_nics =  mysql_query($q_cor_nics) or die(mysql_error());	// echo "Ошибка при обновлении данных подключения к сети.\n";
	echo "Внесены.</br>";
*/	
//*********************	
		echo "Информация о платеже абонплаты ... ";
		$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
						"values (".$_REQUEST ["TabNum"].",".$_REQUEST ["Bill_Dog"].",$Nic,'".date("Y-m-d H:i:s")."','".$_REQUEST ["Date_start_st"]."','".$_REQUEST ["Date_end_st"].
						"',$abon_pay,1,'".$TConn[$_REQUEST ["conn"]]."')";//		.'</br>'
		$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
		$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
						"values (".$_REQUEST ["TabNum"].",".$_REQUEST ["Bill_Dog"].",$Nic,'".date("Y-m-d H:i:s")."','".$_REQUEST ["Date_start_st"]."','".$_REQUEST ["Date_end_st"].
						"',$abon_pay,1,'".$TConn[$_REQUEST ["conn"]]."')";//		.'</br>'
		$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
		echo "Добавлена.</br>";
		
// Проверка есть ли логин
$q_Login = "select * FROM logins where $Bill_Dog";// $Nic
$s_Login =  mysql_query($q_Login) or die(mysql_error());
$totalRows_Login = mysql_num_rows($s_Login);
if ($_REQUEST ["tarif3w_date"]){
	if ($totalRows_Login >0 ) {
		// Login FROM Logins where Login='".$Login."'"; 
		$q_cor_logins = "update `logins` set $tarif3w_date $id_tarif3w saldo=saldo+(".(1*$inet_pay).") where $Nic and $Login";
		echo "Изменение данных о подключении к интернету... ";
	} else {
		$q_cor_logins = "insert into `logins` (tarif3w_date,id_tarif3w,Nic,Login,saldo) values ($tarif3w_date_,$id_tarif3w_,$Nic_,$Login_,$inet_pay)";
		echo "Внесение данных о подключении к интернету... ";
	}
	//			echo $q_cor_logins.'</br>';
	$s_cor_logins =  mysql_query($q_cor_logins) or die(mysql_error());	// echo "Ошибка при обновлении данных подключения к интернету.";
	echo " Выполнено.</br>";
} else
	if ($totalRows_Login >0 ) {
		$row_Login = mysql_fetch_array($s_Login, MYSQL_ASSOC);
		if ($row_Login["Nic"]!=$_REQUEST["Nic"]) {
			$q_cor_logins = "update `logins` set $Nic where $Bill_Dog";
			echo "Изменение ника с ".$row_Login["Nic"]." на ".$_REQUEST["Nic"]."... ";
			$s_cor_logins =  mysql_query($q_cor_logins) or die(mysql_error());	// echo "Ошибка при обновлении данных подключения к интернету.";
			echo " Выполнено.</br>";
		}
}
	if (!($_REQUEST ["conn"]==0/*Не установлено*/ || $_REQUEST ["conn"]==3/*Смена владельца*/ || check_off($_REQUEST ["Bill_Dog"])/*есть заявка на откл*/)) {
		$s_col = "Cod_flat, Bill_Dog,";								$v_col = $_REQUEST ["Cod_flat"].", ".$_REQUEST ["Bill_Dog"].",";
		$Date_in = $DateKor;
		//----------------------------------------------------------------------------------	
		if ($_REQUEST ["conn"]==6) {	/* Переоформление	*/
			$Notify = "откл.(долг)";								$Date_Plan = $_REQUEST["Date_pay"];
			$s_col = $s_col." conn,";								$v_col = $v_col." -1,";
			echo "Заявка монтажнику на отключение абонента. Плановая дата исполнения:$Date_Plan. ";
		} else {
			$Notify = "подключение";								$Date_Plan = date("Y-m-d", mktime(0,0,0,date("m"),date("d")/*+3*/,date("Y")));
			echo "Заявка на смену подключения абонента. Плановая дата исполнения:$Date_Plan. ";
		}
		if (strlen($Notify)>0) {$s_col = $s_col." Notify,";			$v_col = $v_col." '$Notify',";}
		if (strlen($Date_Plan)>0) {$s_col = $s_col." Date_Plan,";	$v_col = $v_col." '$Date_Plan',";}
		if (strlen($Date_in)>0) {$s_col = $s_col." Date_in,"; 		$v_col = $v_col." $Date_in,";}
		if (strlen($TabNum)>0) {$s_col = $s_col." TabNum,"; 		$v_col = $v_col." '".$_REQUEST["TabNum"]."',";}
		$s_col = $s_col. " id_p, fl";								$v_col = $v_col. " ".$_REQUEST ["id_p"].", ".$_REQUEST ["fl"];
	//	if (strlen($)>0) {$s_col = $s_col." ,";						$v_col = $v_col." '$',";}
	
		$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
		$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		echo "Внесена</br>";
	}
	echo 'Обновить экран. <button name="B_chk_adress" type=button onClick="f=document.forms[&quot;ulaForm&quot;];f.sCod_flat.value = '.$_REQUEST["Cod_flat"].'; setTimeout(&quot;f.sCod_flat.onchange();&quot;, 300);"><img src="reload.png" align=middle alt="Обнови"></button>';

/*******        **********        *********        **********      **********         ***********************************************************************************/
function get_param ($name) {
	$$name = $_REQUEST [$name];
	return (($$name=="")? "" : " `".$name."`='".$$name."',");
}
?>