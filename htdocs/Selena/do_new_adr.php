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

	$id_p  			= $_REQUEST ["id_p"];
	$fl 			= $_REQUEST ["fl"];
	$floor  		= " `floor`='".$_REQUEST ["floor"]."'";
	$Bill_Dog  		= " `Bill_Dog`=".$_REQUEST ["Bill_Dog"];
	$BD_		  	= $_REQUEST ["Bill_Dog"];
	$cod 			= get_Cod_flat($id_p, $fl);
	$Cod_flat  		= " `Cod_flat`=$cod"/*.$_REQUEST ["Cod_flat"]*/;
	$conn 			= " `conn`=5";
	$DateKor		= "'".date("Y-m-d H:i:s")."'";
	$TabNum 		= $_REQUEST ["TabNum"];
	if (!ch_cod($_REQUEST ["Bill_Dog"])) { put_old_cod($_REQUEST["Bill_Dog"]); }

	$Date_Plan 		= date("Y-m-d"/*,mktime(0,0,0,date("m"),date("d")+3,date("Y"))*/);
	
	$old_adr = get_adr($_REQUEST["Bill_Dog"]);
	$Date_in = $DateKor;
	$s_col = "Cod_flat, Bill_Dog,";				$v_col = $old_adr["Cod_flat"].", ".$_REQUEST ["Bill_Dog"].",";
	$s_col = $s_col." Notify, conn,";			$v_col = $v_col." 'откл.(смена адр.)', -1,";
	$s_col = $s_col."Date_in,Date_Plan,TabNum,";$v_col = $v_col." $Date_in, '$Date_Plan', '".$_REQUEST["TabNum"]."',";
	$s_col = $s_col. " id_p, fl";				$v_col = $v_col. " ".$old_adr["id_Podjezd"].", ".$old_adr["flat"];
	$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
	echo "Заявка монтажнику на отключение абонента по старому адресу с плановой датой исполнения:$Date_Plan... ";
	$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
	echo "Внесена.</br>";
		
//	put_new_cod($_REQUEST ["Bill_Dog"]);
	put_new_cod($BD_, $cod, $_REQUEST["TabNum"]);
	$s_col = "Cod_flat, id_Podjezd, flat";		$v_col = /*$_REQUEST["Cod_flat"].*/"$cod, $id_p, $fl";
	if(check_flat($_REQUEST["id_p"], $_REQUEST["fl"])) {	echo "Присвоение кода адреса...";
		put_Cod_flat($s_col, $v_col);
		$q_cor_Cod_flat = "update `customers` set $Cod_flat where `id_Podjezd`=$id_p and `flat`=$fl";
		$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die(mysql_error());	// echo "Ошибка при установке кода адреса.\n";
	}
	echo "Изменения в основные данные... "; //
	$q_cor_Cod_flat = "update `customers` set $Cod_flat,id_Podjezd=$id_p,flat=$fl,$floor,$conn,`DateKor`=$DateKor,`TabNum`=$TabNum, state=0 
							where $Bill_Dog";
	$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die(mysql_error());	// echo "Ошибка при установке кода адреса.\n";
	echo "Внесены.</br>";
	
	$Notify = "подкл.(смена адр.)";	$Date_in = $DateKor;
	$s_col = "Cod_flat, Bill_Dog,";								$v_col = /*$_REQUEST ["Cod_flat"].*/"$cod, ".$_REQUEST ["Bill_Dog"].",";
	if (strlen($Notify)>0) {$s_col = $s_col." Notify,";			$v_col = $v_col." '$Notify',";}
	if (strlen($Date_Plan)>0) {$s_col = $s_col." Date_Plan,";	$v_col = $v_col." '$Date_Plan',";}
	if (strlen($Date_in)>0) {$s_col = $s_col." Date_in,"; 		$v_col = $v_col." $Date_in,";}
	if (strlen($TabNum)>0) {$s_col = $s_col." TabNum,"; 		$v_col = $v_col." '$TabNum',";}

	$s_col = $s_col. " conn, id_p, fl";							$v_col = $v_col. " 5, $id_p, $fl";

	echo "Заявка монтажнику на смену адреса абонента. Плановая дата исполнения:$Date_Plan. ";
	$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
	$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
	echo "Внесена.</br>";

	echo "Новый адрес в прочих заявках ... ";
	$s = "update notify_repair set $Cod_flat,id_p=$id_p,fl=$fl where Date_Fact IS NULL and $Bill_Dog and not (Notify='откл.(смена адр.)' and conn=-1)";
	$q_del = mysql_query($s) or die(mysql_error());
	echo "установлен.</br>";
		
	echo "Произведена смена адреса. Код нового адреса - $cod";	// Для обновления экрана нажмите кнопку

/*	echo 'Обновить экран. <button name="B_chk_adress" type=button onClick="document.forms.ulaForm.sCod_flat.value = '.
			$_REQUEST["Cod_flat"].'; setTimeout(&quot;srch(\'Cod_flat\')&quot;, 300);"><img src="reload.png" align=middle alt="Обнови"></button>';*/
//document.forms.ulaForm.h_Cod_flat.value
	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value='.
			$cod.'; document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');'.
			'" value="Обновить"/>';
return; //#####################################################################################################################################
	//#####################################################################################################################################

	$Cod_flat = get_param("Cod_flat");

if (isset($_REQUEST ["Cod_flat"])) {
	$q_cor_Cod_flat = "update `customers` set Cod_flat=".$_REQUEST ["Cod_flat"]." where $id_p and flat=$fl";
	$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die(mysql_error());	// echo "Ошибка при установке кода адреса.\n";
} else {
	$q_cor_Cod_flat = "select Cod_flat from `customers` where $id_p and flat=$fl";
	$s_cor_Cod_flat =  mysql_query($q_cor_Cod_flat) or die(mysql_error());
	$row_Cod_flat = mysql_fetch_array($s_cor_Cod_flat, MYSQL_ASSOC);
	$Cod_Cod_flat = " Cod_flat=".$row_Cod_flat["Cod_flat"].", ";
}
	$q_cor_cust = "update `customers` set ".
		"$floor $Fam $Name $Father $Birthday $pasp_Ser $pasp_Num $pasp_Date $pasp_Uvd $pasp_Adr $Comment ".
		"$phone_Home $phone_Cell $phone_Work $Jur $From_Net $conn $mont $TabNum $DateKor ".
		"Saldo=Saldo+(".(1*$conn_pay).")+(".(1*$abon_pay)."),$Nic".
		" where $id_p and flat=$fl and $Bill_Dog";
//echo $q_cor_cust.'</br>'; // $Cod_flat.
//return;
	$s_cor_cust =  mysql_query($q_cor_cust) or die(mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	
//	$q_cor_nics = "update `nics` set $id_tarifab $tarifab_date $Nic where $Nic and $Bill_Dog";
	$q_cor_nics = "update `customers` set $id_tarifab $tarifab_date $Nic where $Nic and $Bill_Dog";
//		echo $q_cor_nics.'</br>';
	$s_cor_nics =  mysql_query($q_cor_nics) or die(mysql_error());	// echo "Ошибка при обновлении данных подключения к сети.\n";
	
// Проверка есть ли логин
	$q_Login = "select Login FROM logins where $Nic";
	$s_Login =  mysql_query($q_Login) or die(mysql_error());
//	$row_Login = mysql_fetch_array($s_Login, MYSQL_ASSOC);
	$totalRows_Login = mysql_num_rows($s_Login);

	if ($totalRows_Login >0 ) {
		// Login FROM Logins where Login='".$Login."'"; 
		$q_cor_logins = "update `logins` set $tarif3w_date $id_tarif3w saldo=saldo+(".(1*$inet_pay).") where $Nic and $Login";
	} else {
		$q_cor_logins = "insert into `logins` (tarif3w_date,id_tarif3w,Nic,Login,saldo) values ($tarif3w_date_,$id_tarif3w_,$Nic_,$Login_,$inet_pay)";
	}
//			echo $q_cor_logins.'</br>';
	$s_cor_logins =  mysql_query($q_cor_logins) or die(mysql_error());	// echo "Ошибка при обновлении данных подключения к интернету.";

	//#####################################################################################################################################
	//#####################################################################################################################################
function get_param ($name) {
	$$name = $_REQUEST [$name];
	return (($$name=="")? "" : " `".$name."`='".$$name."',");
}
?>