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

$now	 = date("Y-m-d H:i:s");	
$today	 = "'".date("Y-m-d")."'";
$deb	 = 0;
$account = $_REQUEST ["account"];
$TabNum	 = $_REQUEST ["tn"];
$B_inf	 = get_inf_acc($account);
$log = isset($_REQUEST["log"])?$_REQUEST["log"]:"";
//*********************	

if (strlen($log)>0)	{ //isset($_REQUEST ["log"]) Изменить логин ".$_REQUEST ["log"]."
	$is_log =  mysql_query("select Bill_Dog, account from logins where Login='$log'") or die(mysql_error());
	if( mysql_num_rows($is_log)==0 ) {
		$upd_log =  mysql_query("update logins set Login='$log' where account=$account") or die(mysql_error());
		$q_ins_inet = "insert into `actions` (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,id_ActionType,Comment) ".
		"values ($TabNum,".$B_inf['Bill_Dog'].",'".$B_inf['Nic']."','$log','$now',$today,$today,6,".
						"'".$B_inf['Login']."-> $log')";
		$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
		echo "готово";
	} else {
		$res = mysql_fetch_assoc($is_log);
		echo "такой логин у счёта <a href=\"javascript:{ch_param('sh_form','menu=pay&tn=$tn&tp=$tp&Bill_Dog=".$res["Bill_Dog"].
				"','Mform');s_Bill_Dog();}\">".$res["Bill_Dog"]."</a>, интернет счёт ".$res["account"]."!";
	}
return;
}
//*********************	//print "php_sockets.dll - "; 
if(!extension_loaded('sockets')) 
	{ print "<b>Не загружена библиотека для работы с сокетами!!!</b><br>";		return; }
error_reporting(E_ALL); 
set_time_limit(30); 
ob_implicit_flush(); 
if (($fp = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === False) {
    echo "Ошибка соединения с сервером биллинга: " . socket_strerror($fp) . "\n";		return;	}
if (!$fp) {	echo "$errstr ($errno)<br>\n"; return; }

//if ($result = @socket_connect($fp, $IPb, 49160) === False) {
//    echo "Ошибка соединения с сервером биллинга";		return;	}
	
if (@socket_connect($fp, $IPb, 49160) === False) {
    die("Ошибка соединения с сервером биллинга: ".socket_last_error().", ".socket_strerror(socket_last_error()));
}

if (!get_wellcome($fp)) { echo date("Y-m-d H:i:s")," Ошибка при прочитении данных приветствия<br> ";	return;	}

$dfrz = isset($_REQUEST["dfrz"])?$_REQUEST["dfrz"]:"dfrz";
$doff = isset($_REQUEST["doff"])?$_REQUEST["doff"]:"doff";
//*********************	

$s_b = "<button type=button onClick=\"ch_param('refr_3w','tn=$TabNum&cmd=";
if (isset($_REQUEST ["cmd"]))	{ // Подана комманда
	//	echo $_REQUEST ["cmd"],strpos($_REQUEST ["cmd"],"freeze")=== false?"false":"freeze";
	if (strpos($_REQUEST ["cmd"],"freeze")=== false) { // Включение или отключение абонента
		$res = get_000($fp, $_REQUEST ["cmd"]." $account");
		$acc = send_command($fp, "acc $account");
		$off = isOFF($acc);
		$s_off = ($off)?"on":"off";
		$id_ac = $_REQUEST ["cmd"] == "off"?"9":"10";
		$comm = ($off?"OFF":"ON");
		$b_off = $s_b.$s_off."&account=$account', 'doff');\"><b>".($off?"В":"ОТ")."КЛ</b>ючить"."</button>";
		echo ($s_off==$_REQUEST ["cmd"]?"НЕ успешно, ЕЩЁ РАЗ!":"")."$b_off";//"<b>",($off?"ОТ":"В")."КЛ</b>ючен ",
	} else {
		$res = get_000($fp, $_REQUEST ["cmd"]." $account");
		$frz = isFrosen(send_command($fp, "acc $account"));
		fdebug("Абонент: ". ($frz?"раз":"за"). "морожен<br>");
		$s_frz = ($frz)?"unfreeze":"freeze";
		$id_ac = $_REQUEST ["cmd"] == "freeze"?"7":"8";
		$comm = ($frz?"":"UN")."FRZ";
		$b_ufrz = $s_b.$s_frz."&account=$account','dfrz');\"><b>".($frz?"РАЗ":"ЗА")."</b>морозь"."</button>";
		echo "<b>",($frz?"":"НЕ </b>")."заморожен","</b> $b_ufrz";
	}
	$q_ins_inet = "insert into `actions` (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,id_ActionType,Comment) ".			//,Summa
					"values ($TabNum,".$B_inf['Bill_Dog'].",'".$B_inf['Nic']."','".$B_inf['Login']."','$now',$today,$today,".	
					"$id_ac,'".$B_inf['Login']." $comm')";
	$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
//*********************	
	if ($fp) { socket_close($fp); }
	return;	
}
$acc = send_command($fp, "acc $account");
?><table width='800'><tr><td><?
echo "Информация об интернет счёте: <b>".$account. "</b>, баланс: <b>", set_color($acc_1 = getSum($acc)),"</b>руб.";
$n = getNic(get_000($fp, "look $account"));		$wNic = trim( ($n==""?getNic(get_000($fp, "look $account")):$n));
echo ", логин в биллинге: <b>$wNic</b>";
if (strcasecmp($wNic,$B_inf['Login']) != 0) {	?>
</td><td><div align="left" id='dlog' style="background-color:#F99">Изменить 
	 <? echo $B_inf['Login']?> <button name="cor_3w" type=button onClick="ch_param('refr_3w','<? echo "tn=$TabNum&account=$account&log=$wNic" ?>', 'dlog');">
		<b>-></b></button>	<? echo $wNic?>
<?php /* <? // '+val_nm(Bill_Dog, 'account'+(1*f.Login.selectedIndex+1))+' ?>*/ ?>
   </div>
   </td><td><?
}
$acc_2 = $acc_1;
//*********************	
$s_c = "update `logins` set saldo=$acc_2 where Login='".$B_inf['Login']."'";//echo 	,'</br>'
$s_cor_inet =  mysql_query($s_c) or die(mysql_error());
?>... счёт в базе обновлен.</td></tr></table><?
//*********************	
?><table width='400'><tr><?
$s_frz = ($frz = isFrosen($acc))?"unfreeze":"freeze";	//f = document.forms.ulaForm; Bill_Dog = f_Bill_Dog(); +val_nm(Bill_Dog, 'account'+(1*f.Login.selectedIndex+1))
$b_ufrz = "<button type=button onClick=\"ch_param('refr_3w','tn=$TabNum&cmd=$s_frz&account=$account', 'dfrz');\"><b>".($frz?"РАЗ":"ЗА")."</b>морозь"."</button>";
?><td><div align="center" id="dfrz" style="background-color:#6FF"><b><? echo ($frz?"заморожен ":"</b>"),$b_ufrz?> </b></div></td><?
// ---------------
$s_off = ($off = isOFF($acc))?"on":"off"; //	$s_off = ( !($off = isOFF($acc))? /*!= 'none'*/ ) && 'off' || 'on';($off?"ОТ":"В")."КЛ</b>ючен ",
$b_off = "<button type=button onClick=\"ch_param('refr_3w','tn=$TabNum&cmd=$s_off&account=$account', 'doff');\"><b>".($off?"В":"ОТ")."КЛ</b>ючить"."</button>";
?><td><div align="center" id="doff" ><b><? echo $b_off ?></div></td><? //style=\"background-color:",($off?"#F60":"#6F6")."
?></tr></table><?
/*********************	
$q_inet = "select `saldo` from `logins` where account=$account";//." and Flat=".$fl
$s_inet =  mysql_query($q_inet) or die(mysql_error());
$row_inet = mysql_fetch_array($s_inet, MYSQL_ASSOC);
echo '<FONT size=+1>Баланс интернет-счёта №'.$account.': '.set_color($row_inet["saldo"]).' руб.</FONT></br>';
//*********************/
if ($fp) { socket_close($fp); }

//*********************	
/*	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value=document.forms.ulaForm.h_Cod_flat.value; '.
			'document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');'.
			'" value="Обновить"/>';	//		//document.getElementById(\'B_chk_adress\').onClick();	ch(\'ch_flt\',\'menu=pay&\',2,\'tab_Cust\');
*///*********************		//	*********************	//	*********************

function set_color ($sum) {
	return '<font color="'.(($sum<0)?'FF0000':'0033FF').'"><b>'.$sum.'</b></font>';
}
function fdebug($sdeb) {
	if ($GLOBALS['deb']==1) { echo date("Y-m-d H:i:s")." ".$sdeb; }
}
?>