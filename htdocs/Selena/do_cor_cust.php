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

	$id_p_ 			= $_REQUEST ["id_p"];
	$fl_ 			= $_REQUEST ["fl"];
	$Cod_flat_		= $_REQUEST ["Cod_flat"];
	$id_p  			= " id_Podjezd=".$_REQUEST ["id_p"];
	$cod 			= get_Cod_flat($id_p_, $fl);
	$Cod_flat  		= " `Cod_flat`=$cod"/*.$_REQUEST ["Cod_flat"]*/;
	$Nic  			= " Nic='".$_REQUEST ["Nic"]."'";
	$Login  		= " Login='".$_REQUEST ["Login"]."'";
	$Nic_  			= "'".$_REQUEST ["Nic"]."'";
	$Login_  		= "'".$_REQUEST ["Login"]."'";
	$tarif3w_date_	= isset($_REQUEST["tarif3w_date"])?"'".$_REQUEST["tarif3w_date"]."'":"";//(($tarifab=="")? " ":" tarifab=".$tarifab.",");
	$id_tarif3w_ 	= isset($_REQUEST["tarif3w"])?$_REQUEST ["id_tarif3w"]:"";
	$BD_		  	= $_REQUEST ["Bill_Dog"];
//	echo $Login,"</br>";
//	$Nic = get_param("Nic");					//(($Nic=="")? " " : " Nic='".$Nic."',");
	$Bill_Dog  		= " Bill_Dog=".$_REQUEST ["Bill_Dog"];
//	$Bill_Dog = get_param("Bill_Dog");							//(($Bill_Dog=="")? " " : " Bill_Dog=".$Bill_Dog.",");
	$mac = get_param("mac");							//(($Bill_Dog=="")? " " : " Bill_Dog=".$Bill_Dog.",");
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
	$id_tarifab = isset($_REQUEST ["id_tarifab"])?get_param("id_tarifab"):"";		//(($tarifab=="")? " " : " tarifab=".$tarifab.",");
	$n_st = isset($_REQUEST ["n_st"])?$_REQUEST["n_st"]:"";
	$tarifab_date = get_param("tarifab_date");	//(($tarifab_date=="")? " " : " tarifab_date=".$tarifab_date.",");
	$Date_start_st = get_param("Date_start_st");
	$Date_end_st = get_param("Date_end_st");
	$Date_pay = get_param("Date_pay");
	$TabNum = get_param("TabNum");				//(($TabNum=="")? " " : " TabNum='".$TabNum."',");
	$DateKor = "'".date("Y-m-d H:i:s")."'";		//get_param("DateKor");			//(($DateKor=="")? " " : " DateKor='".$DateKor."',");
	$id_tarif3w = get_param("id_tarif3w");		//(($tarifab=="")? " " : " tarifab=".$tarifab.",");
	$tarif3w_date = get_param("tarif3w_date");	//(($tarifab_date=="")? " " : " tarifab_date=".$tarifab_date.",");
	$conn_ = get_param("conn");
//	$mont = get_param("mont");
	$conn_pay =  isset($_REQUEST ["conn_pay"])?1*$_REQUEST ["conn_pay"]:0;
	$abon_p = isset($_REQUEST ["abon_p"])?1*$_REQUEST ["abon_p"]:0;
	$inet_Cpay = isset($_REQUEST ["inet_Cpay"])?1*$_REQUEST ["inet_Cpay"]:0;
//	$Cod_flat = get_param("Cod_flat");
		$s_tar = "select con_typ from spr_tar_con where id_tar_con=".$_REQUEST["conn"];
		$q_tar = mysql_query($s_tar) or die($s_tar.mysql_error());
		$_tar = mysql_fetch_array($q_tar, MYSQL_ASSOC);
		$con_typ = $_tar["con_typ"];
	$TConn = array(0 =>'Корректировка',1 =>'Новое подкл.',2 =>'Доп.компьютер',3 =>'Смена владельца',
				   4 =>'Переподключение',5 =>'Смена адреса', 6 =>'Переоформление', 7 =>'Смена тарифа');
	$Y = date("Y");
	if ($con_typ==6 and $_REQUEST["conn"]==24 and $n_st == 3) {
		$Date_end_st = "Date_end_st=null,";
		$Date_pay = "Date_pay='{$_REQUEST ['inet_Cpay']}',";
	}

	if ($con_typ==5) {//$_REQUEST["conn"] // Смена адреса
		if (!ch_cod($BD_)) { 
			echo "Первая смена адреса; сохраняем начальный адрес ...";
			put_old_cod($BD_);
			echo " готово<br>";
		}
		echo "Сохраняем новый адрес ...";
		put_new_cod($BD_, $cod, $_REQUEST["TabNum"]);
		echo " готово<br>";
	}
	$s_col = "Cod_flat, id_Podjezd, flat";			$v_col = "$Cod_flat_, $id_p_, $fl_";
	if(check_flat($id_p_, $fl_)) {	echo "Присвоение кода адреса... ";
		put_Cod_flat($s_col, $v_col);
		$q_cor_Cod_flat = "update `customers` set Cod_flat=$Cod_flat_ where $id_p and flat=$fl_";
		$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die($q_cor_Cod_flat.mysql_error());	// echo "Ошибка при установке кода адреса.\n";
	}
	
if (isset($_REQUEST ["Cod_flat"])) {
	$q_cor_Cod_flat = "update `customers` set Cod_flat=$Cod_flat_ where $id_p and flat=$fl_";
	$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die($q_cor_Cod_flat.mysql_error());	// echo "Ошибка при установке кода адреса.\n";
} else {
	$q_cor_Cod_flat = "select Cod_flat from `customers` where $id_p and flat=$fl_";
	$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die($q_cor_Cod_flat.mysql_error());
	$row_Cod_flat = mysql_fetch_array($s_cor_Cod_flat, MYSQL_ASSOC);
	if (!($row_Cod_flat["Cod_flat"]>0)) { echo "Ошибка установки кода адреса!"; return; }
	$Cod_flat_ = $row_Cod_flat["Cod_flat"];
	$Cod_Cod_flat = " Cod_flat=$Cod_flat_, ";
}

	echo "Изменения в основные данные и данные о подключении к сети... ";
	$q_cor_cust = "update `customers` set Cod_flat=$Cod_flat_,".
		"$mac $floor $Fam $Name $Father $Birthday $pasp_Ser $pasp_Num $pasp_Date $pasp_Uvd $pasp_Adr $Comment ".
		"$phone_Home $phone_Cell $phone_Work $Jur $From_Net $TabNum `DateKor`=$DateKor, $Nic, ".
		($_REQUEST["conn"]>0?"$conn_ ".(in_array(array('16','17','18','19','24'),$_REQUEST["conn"])?"":"$id_tarifab ")."$tarifab_date":"").
			($con_typ==6?"state=".($_REQUEST["conn"]==24?$n_st:"1").",":"").//$_REQUEST["conn"]		check_st($BD_, $_REQUEST["conn"]).
		"$Date_start_st $Date_end_st $Date_pay ".
		($conn_pay>0?"Saldo=Saldo+(".(1*$conn_pay).")+(".(1*$abon_p)."),":"").
		" inet=null".
		" where $id_p and flat=$fl_ and $Bill_Dog";// $mont	($_REQUEST ["Date_start_st"]==$_REQUEST ["Date_end_st"]?"":"$Date_start_st $Date_end_st $Date_pay ").
//echo $q_cor_cust.'</br>'; // $Cod_flat.
//return;
	$s_cor_cust =  mysql_query($q_cor_cust) or die("<b>Ошибка в стр.111!</b> $q_cor_cust ".mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	
	$q_cor_log = "update `logins` set $Nic where $Bill_Dog";
	$s_cor_log =  mysql_query($q_cor_log) or die($q_cor_log.mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	
	echo "Внесены.</br>";
	if ($_REQUEST["conn"]==0) {
		//*****************************
		echo "Обновить экран. <button name='B_chk_adress' type=button onClick='f=document.forms.ulaForm;".
			"f.sCod_flat.value=$Cod_flat_;setTimeout(&quot;f.sCod_flat.onchange();&quot;,300);'>",
			"<img src='reload.png' align=middle alt='Обнови'></button>";
		return;
	}
//*********************	
$ds = $Date_start_st;
$de = $Date_end_st;
if (($ds != "") || ($de != "")) {
	echo "Информация об операции ... ";
	$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,".
					($ds?"Date_start,":"").($de?"Date_end,":"")."Summa,id_ActionType,Comment) ".
					"values (".$_REQUEST ["TabNum"].",$BD_,$Nic,'".date("Y-m-d H:i:s")."',".
					($ds?"'".$_REQUEST['Date_start_st']."',":"").($de?"'".$_REQUEST ["Date_end_st"]."',":"").
						"$abon_p,1,'".$TConn[$con_typ]."')";//$_REQUEST ["conn"]		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die($q_ins_abon.mysql_error());
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,".
					($ds?"Date_start,":"").($de?"Date_end,":"")."Summa,id_ActionType,Comment) ".
					"values (".$_REQUEST ["TabNum"].",$BD_,$Nic,'".date("Y-m-d H:i:s")."',".
					($ds?"'".$_REQUEST['Date_start_st']."',":"").($de?"'".$_REQUEST ["Date_end_st"]."',":"").
						"$abon_p,1,'".$TConn[$con_typ]."')";//$_REQUEST ["conn"]		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die($q_ins_abon.mysql_error());
	echo "Добавлена.</br>";
}
	if ($con_typ==0/*$_REQUEST ["conn"]Не установлено*/ || 
		$con_typ==3/*$_REQUEST ["conn"]Смена владельца*/ || 
		(f_m_ab($_REQUEST ["id_tarifab"])==0 /*абон плата == 0*/ ? check_off($BD_)/*есть заявка на откл*/:0) )
		{	//echo "/* заявка монтажнику на ОТКЛ. не нужна */";
		if ($con_typ!=3/* НЕ Смена владельца*/) { 
	//		echo f_m_ab($_REQUEST ["id_tarifab"]),",",check_off($BD_),"/* $con_typ удаляется заявка монтажнику на отключ	*/</br>";
			$q = "delete from notify_repair where Bill_dog=$BD_ and Notify='откл.(долг)' and conn=-1 and Date_Fact is null";
			$s_not =  mysql_query($q) or die($q.mysql_error());
		
		}
	}	else { //   echo "/* монтажнику НУЖНА заявка*/";
		if ($con_typ!=6 && $Date_start_st != "") {	//* НЕ Переоформление и есть Дата_начала	* /
			put_noti2conn($con_typ,$_REQUEST["Date_start_st"],$BD_,$Cod_flat_,$id_p_, $fl_, $_REQUEST["TabNum"]);
		}
		if ($Date_pay != "") { 
			$d_off = ($con_typ==6 and $_REQUEST["conn"]==24 and $n_st == 3)?date("Y-m-d", strtotime($_REQUEST['Date_start_st']) - 1 ):$_REQUEST["Date_pay"];
			$q = "delete from notify_repair where Bill_dog=$BD_ and Notify='откл.(долг)' and conn=-1 and Date_Fact is null";
			$s_not =  mysql_query($q) or die($q.mysql_error());
			put_noti2off (/*$_REQUEST["Date_pay"]*/$d_off, $BD_, $Cod_flat_, $id_p_, $fl_, $_REQUEST ["TabNum"]);
		}
	}	
// Проверка есть ли логин
$q_Login = "select * FROM logins where $Bill_Dog";
$s_Login =  mysql_query($q_Login) or die("<br>Ошибка! ".$q_Login.mysql_error());
$totalRows_Login = mysql_num_rows($s_Login);
if ($totalRows_Login ==0 and $_REQUEST ["Login"]!='') {
	$q_Login = "select * FROM logins where $Login";
	$s_Login =  mysql_query($q_Login) or die("<br>Ошибка! ".$q_Login.mysql_error());
	$totalRows_Login = mysql_num_rows($s_Login);
	if ($totalRows_Login > 0) {
		echo "исправление в базе логинов ... ";
//	echo "logins=".$totalRows_Login;
		$q_Login = "update `logins` set $Bill_Dog where $Login";
	//	$q_Login = "select * FROM logins where Login=$Login";// $Nic
		$s_Login =  mysql_query($q_Login) or die("<br>Ошибка! ".$q_Login.mysql_error());

		echo "проверка ... ";
		$q_Login = "select * FROM logins where $Bill_Dog";// $Nic
		$s_Login = mysql_query($q_Login) or die("<br>Ошибка! ".$q_Login.mysql_error());
		$totalRows_Login = mysql_num_rows($s_Login);
		echo $totalRows_Login > 0?"выполнена<br>" : "<b>ОШИБКА, интернет логинов НЕТ!</b><br>";
	}
}
	
if (isset($_REQUEST ["tarif3w_date"])){
	if ($totalRows_Login >0 ) {
		// Login FROM Logins where Login='".$Login."'"; 
		$q_cor_logins = "update `logins` set $tarif3w_date $id_tarif3w saldo=saldo+(".(1*$inet_Cpay).")".
						" where $Bill_Dog and $Login";
		echo "Изменение данных о подключении к интернету... ";
	} else {
		$q_cor_logins = "insert into `logins` (tarif3w_date,id_tarif3w,Nic,Login,saldo,Bill_Dog) values ($tarif3w_date_,$id_tarif3w_,$Nic_,$Login_,$inet_Cpay,$BD_)";
		echo "Внесение данных о подключении к интернету... ";
	}
	//			echo $q_cor_logins.'</br>';
	$s_cor_logins =  mysql_query($q_cor_logins) or die("<br>Ошибка! ".$q_cor_logins.mysql_error());	// echo "Ошибка при обновлении данных подключения к интернету.";
	echo " Выполнено.</br>";
} else
	if ($totalRows_Login >0 ) {
		$row_Login = mysql_fetch_array($s_Login, MYSQL_ASSOC);
		if ($row_Login["Nic"]!=$_REQUEST["Nic"]) {
			$q_cor_logins = "update `logins` set $Nic where $Bill_Dog";
			echo "Изменение ника с ".$row_Login["Nic"]." на ".$_REQUEST["Nic"]."... ";
			$s_cor_logins =  mysql_query($q_cor_logins) or die("<br>Ошибка! ".$q_cor_logins.mysql_error());	// echo "Ошибка при обновлении данных подключения к интернету.";
			echo " Выполнено.</br>";
		}
}
//*****************************
echo 'Обновить экран. <button name="B_chk_adress" type=button onClick="f=document.forms.ulaForm;f.sCod_flat.value = '.$Cod_flat_.'; setTimeout(&quot;f.sCod_flat.onchange();&quot;, 300);"><img src="reload.png" align=middle alt="Обнови"></button>';

/*******        **********        *********        **********      **********         ***********************************************************************************/
function get_param ($name) {
	$$name = isset($_REQUEST [$name])?$_REQUEST [$name]:"";
	return ($$name==""? "" : " `{$name}`='{$$name}',");
}
function f_m_ab($id_tar) {
	$qs = "select ab_sum from spr_tarifab where id_tarifab=$id_tar";
	$q = mysql_query($qs) or die($qs.mysql_error());
	$r = mysql_fetch_array($q, MYSQL_ASSOC);
	return $r["ab_sum"];
}
?>