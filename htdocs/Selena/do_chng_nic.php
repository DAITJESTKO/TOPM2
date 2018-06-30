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

	$Bill_Dog  		= " Bill_Dog=".$_REQUEST ["Bill_Dog"];
	$newNic  		= " Nic='".$_REQUEST ["newNic"]."'";
	$oldNic  		= " Nic='".$_REQUEST ["oldNic"]."'";
	$DateKor		= "'".date("Y-m-d H:i:s")."'";
	$TabNum			= " TabNum=".$_REQUEST ["TabNum"];//get_param("TabNum");
	$tn				= $_REQUEST ["TabNum"];
	$Y = date("Y");
//	echo "tn=".$_REQUEST ["tn"]." TabNum=$TabNum</br>";
//	$Login  		= " Login='".$_REQUEST ["Login"]."'";
//	$Nic_  			= "'".$_REQUEST ["Nic"]."'";
//	$Login_  		= "'".$_REQUEST ["Login"]."'";
//echo $Bill_Dog, $newNic, $oldNic, $TabNum;
	/*
	$s_col = "Cod_flat, id_Podjezd, flat";
	$v_col = $_REQUEST["Cod_flat"].", ".$_REQUEST["id_p"].", ".$_REQUEST["fl"];
	if(check_flat($_REQUEST["id_p"], $_REQUEST["fl"])) {
		//echo "Присвоение кода адреса";
		put_Cod_flat($s_col, $v_col);
	}	*/
	echo "Изменения в основные данные и данные о подключении к сети... ";
	$q_cor_cust = "update `customers` set $newNic, inet=null, $TabNum, `DateKor`=$DateKor where $Bill_Dog";
//!	echo $q_cor_cust.'</br>'; // $Cod_flat.
	$s_cor_cust =  mysql_query($q_cor_cust) or die(mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	echo "Внесены.</br>";
	
	echo "Номер договора ".$_REQUEST ["Bill_Dog"]." в платежи ника '".$_REQUEST ["newNic"]."'... ";
	$q_cor = "update `act2010` set $Bill_Dog where $newNic"; /* нужна ли замена в других файлах кроме текущего года? */
	$s_cor =  mysql_query($q_cor) or die(mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	$q_cor = "update `act2011` set $Bill_Dog where $newNic"; /* нужна ли замена в других файлах кроме текущего года? */
	$s_cor =  mysql_query($q_cor) or die(mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	$q_cor = "update `act2012` set $Bill_Dog where $newNic"; /* нужна ли замена в других файлах кроме текущего года? */
	$s_cor =  mysql_query($q_cor) or die(mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	$q_cor = "update `actions` set $Bill_Dog where $newNic"; /* нужна ли замена в других файлах кроме текущего года? */
//!	echo $q_cor_cust.'</br>'; // $Cod_flat.
	$s_cor =  mysql_query($q_cor) or die(mysql_error());	// echo "Ошибка при обновлении данных клиента.\n";
	echo "Внесён.</br>";

	echo "Состояние абонента ... ";//$Bill_Dog
	$res = mysql_query("select * from customers WHERE $Bill_Dog") or die(mysql_error());
	$row = mysql_fetch_assoc($res);
/*	echo mysql_num_rows($res);
	print_r($row);	*/
	$Y = date("Y");
	while ( ! ($resNic = mysql_query("select * from act{$Y} WHERE $newNic order by Date_start DESC") or die(mysql_error())) ) { $Y =-1; } 
	
	$rowNic = mysql_fetch_assoc($resNic);
	$state = ($rowNic["id_ActionType"]==1 or $rowNic["id_ActionType"]==3)?1:2;
//		echo "<b> c ".$rowNic["Date_start"]." состояние - ".($state==2?"не ":"")."активен</b>";
	$q_s = "update customers set `tarifab_date`=null,`DateKor`='".date("Y-m-d H:i:s")."',`Date_start_st`='".
		$rowNic["Date_start"]."',`Date_end_st`='".$rowNic["Date_end"]."',`Date_pay`='".$rowNic["Date_end"].
		"',id_tarifab=1,`state`=$state where $newNic";
//!	echo $q_s.'</br>'; // $Cod_flat.
	$q_upd = mysql_query($q_s) or die(mysql_error());
	echo "установлено.</br>";
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	echo "Оплаченный срок в заявке на отключение ... ";//$Bill_Dog
	$q_DP = mysql_query("select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` where `Date_Fact` IS NULL and $Bill_Dog and conn=-1 order by `Date_Plan` desc") or die(mysql_error());
	$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
	if(is_null($r_DP["Max_Date_Plan"])) { /* нет заявки на отключение, внести */
		$q_noti = "insert into `notify_repair` (id_p,fl,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn,Notify) ".
				"values (".$row['id_Podjezd'].",".$row['flat'].",$tn,".$rowNic['Bill_Dog'].",'".
				$row["Nic"]."','".date("Y-m-d H:i:s")."','".$rowNic["Date_end"]."',-1,'откл.(долг)')";
		echo "установлен в";
	} else {
		$q_noti ="update `notify_repair` set Date_Plan='".$rowNic["Date_end"]."' where $Bill_Dog and conn=-1 and Date_Plan='".
			$r_DP["Max_Date_Plan"]."'";
//		$s_noti = mysql_query("update `customers` set Date_Plan='$Date_end' where $Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Date_end_st"]."'") or die(mysql_error());
		echo "перенесён на";//$Date_end
	}
//!	echo ' '.$q_noti.'</br>';
	$s_noti = mysql_query($q_noti) or die(mysql_error());
	echo " ".$rowNic["Date_end"].".</br>";
//-------------------------------------------------------
	$q_Login = "select Login FROM logins where $oldNic";
		$s_Login =  mysql_query($q_Login) or die(mysql_error());
		$totalRows_Login = mysql_num_rows($s_Login);
	
		if ($totalRows_Login >0 ) {
			$q_cor_logins = "update `logins` set $newNic where $oldNic, $Bill_Dog";
			echo "Изменение";
		} else {
			$q_cor_logins = "insert into `logins` (id_tarif3w,Nic,Login,Bill_Dog) ".
					"values (0,'".$_REQUEST ["newNic"]."','".$_REQUEST ["oldNic"]."',".$_REQUEST ["Bill_Dog"].")";
			echo "Внесение";
		}
		echo " доменного Ника для интернет логина ... ";
//!		echo $q_cor_logins.'</br>';
		$s_cor_logins =  mysql_query($q_cor_logins) or die(mysql_error());	// echo "Ошибка при обновлении данных подключения к интернету.";
		echo " Выполнено.</br>";

//*********************	
		echo "Информация об операции ... ";
		$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
						"values ($tn,".$_REQUEST ["Bill_Dog"].",'".$_REQUEST ["newNic"]."','".
								date("Y-m-d H:i:s")."','".$rowNic ["Date_start"]."','".$rowNic ["Date_end"].
						"',0,6,'ник ".$_REQUEST ["oldNic"]." на ".$_REQUEST ["newNic"]."')";//		.'</br>'
//!		echo $q_ins_abon.'</br>';
		$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
		$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
						"values ($tn,".$_REQUEST ["Bill_Dog"].",'".$_REQUEST ["newNic"]."','".
								date("Y-m-d H:i:s")."','".$rowNic ["Date_start"]."','".$rowNic ["Date_end"].
						"',0,6,'ник ".$_REQUEST ["oldNic"]." на ".$_REQUEST ["newNic"]."')";//		.'</br>'
//!		echo $q_ins_abon.'</br>';
		$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
		echo "Добавлена.</br>";

	echo 'Обновить экран. <button type=button onClick="document.forms.ulaForm.B_chk_adress.click();"><img src="reload.png" align=middle alt="Обнови"></button>';

return; // #####################################################################################################



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
	$id_tarif3w = get_param("id_tarif3w");		//(($tarifab=="")? " " : " tarifab=".$tarifab.",");
	$tarif3w_date = get_param("tarif3w_date");	//(($tarifab_date=="")? " " : " tarifab_date=".$tarifab_date.",");
	$conn = get_param("conn");
//	$mont = get_param("mont");
	$conn_pay = 1*$_REQUEST ["conn_pay"];
	$abon_pay = 1*$_REQUEST ["abon_pay"];
	$inet_pay = 1*$_REQUEST ["inet_pay"];
	$Cod_flat = get_param("Cod_flat");
	$TConn = array(1 =>'Новое подкл.',2 =>'Доп.компьютер',3 =>'Смена владельца',4 =>'Переподключение',5 =>'Смена адреса', 6 =>'Переоформление');

	$s_col = "Cod_flat, id_Podjezd, flat";
	$v_col = $_REQUEST["Cod_flat"].", ".$_REQUEST["id_p"].", ".$_REQUEST["fl"];
	if(check_flat($_REQUEST["id_p"], $_REQUEST["fl"])) {
		//echo "Присвоение кода адреса";
		put_Cod_flat($s_col, $v_col);
	}
/*	
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
*/
	echo "Изменения в основные данные и данные о подключении к сети... ";
	$q_cor_cust = "update `customers` set Cod_flat=".$_REQUEST ["Cod_flat"].",".
		"$floor $Fam $Name $Father $Birthday $pasp_Ser $pasp_Num $pasp_Date $pasp_Uvd $pasp_Adr $Comment ".
		"$phone_Home $phone_Cell $phone_Work $Jur $From_Net $conn $TabNum `DateKor`=$DateKor, ".check_st($_REQUEST ["Bill_Dog"], $_REQUEST["conn"]).
		($_REQUEST ["Date_start_st"]==$_REQUEST ["Date_end_st"]?"":"$Date_start_st $Date_end_st $Date_pay ")."$id_tarifab $tarifab_date ".
		"Saldo=Saldo+(".(1*$conn_pay).")+(".(1*$abon_pay)."),$Nic, inet=null".
		" where $id_p and flat=$fl and $Bill_Dog";// $mont
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
if ($_REQUEST ["tarif3w_date"]){
		$q_Login = "select Login FROM logins where $Nic";
		$s_Login =  mysql_query($q_Login) or die(mysql_error());
	//	$row_Login = mysql_fetch_array($s_Login, MYSQL_ASSOC);
		$totalRows_Login = mysql_num_rows($s_Login);
	
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
}
	if (!($_REQUEST ["conn"]==3/*Смена владельца*/ || check_off($_REQUEST ["Bill_Dog"])/*есть заявка на откл*/)) {
		$s_col = "Cod_flat, Bill_Dog,";								$v_col = $_REQUEST ["Cod_flat"].", ".$_REQUEST ["Bill_Dog"].",";
		//----------------------------------------------------------------------------------	
		if ($_REQUEST ["conn"]==6) {
			$Notify = "откл.(долг)";  	$Date_in = $DateKor;		$Date_Plan = $_REQUEST["Date_pay"];
			$s_col = $s_col." conn,";								$v_col = $v_col." -1,";
			echo "Заявка монтажнику на отключение абонента. Плановая дата исполнения:$Date_Plan. ";
		} else {
			$Notify = "подключение";  $Date_in = $_REQUEST["DateKor"];	$Date_Plan = date("Y-m-d", mktime(0,0,0,date("m"),date("d")/*+3*/,date("Y")));
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
	echo 'Обновить экран. <button name="B_chk_adress" type=button onClick="f=document.forms[&quot;ulaForm&quot;];f.sCod_flat.value = '.$_REQUEST["Cod_flat"].'; setTimeout(&quot;f.sCod_flat.onchange();&quot;, 1000);"><img src="reload.png" align=middle alt="Обнови"></button>';

/*******        **********        *********        **********      **********         ***********************************************************************************/
function get_param ($name) {
	$$name = $_REQUEST [$name];
	return (($$name=="")? "" : " `".$name."`='".$$name."',");
}

?>