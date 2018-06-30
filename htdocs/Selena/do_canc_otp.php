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

$d_canc  	= $_REQUEST ["d_canc"];
$Bill_Dog  	= $_REQUEST ["Bill_Dog"];
$Date_start = $_REQUEST ["Date_start"];
$Date_end 	= $_REQUEST ["Date_end"];
$TN_canc	= $_REQUEST ["TN_canc"];

$q_quer = mysql_query("select Cod_flat,Nic,Date_end_st,Date_pay from customers where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
$r_quer = mysql_fetch_array($q_quer, MYSQL_ASSOC);
if (mysql_num_rows($q_quer)>0) { // у абонента нет кода адреса && $r_res["Cod_flat"]==0
		$Cod_flat = $r_quer['Cod_flat']==0?get_Cod_flat($id_p=$r_quer['id_p'], $fl=$r_quer['fl']):$r_quer['Cod_flat'];
}
$Nic = $r_quer["Nic"];
$Date_pay = date("Y-m-d", strtotime(date($r_quer["Date_pay"])) - strtotime($r_quer["Date_end_st"]) + strtotime($d_canc));

//*********************	Измененние операции
$s_quer = "update `actions` set `Date_end`='$d_canc',Date_ed='".date("Y-m-d H:i:s")."',`TN_canc`=$TN_canc where `Bill_Dog`=$Bill_Dog and id_ActionType=4 and `Date_start`= '$Date_start' and `Date_end`='$Date_end' and not canc";
echo "Оконч.отпуска ";//.$s_quer
$res = mysql_query($s_quer);
echo "перенесёно на $d_canc.</br>";
//*********************	
$iY = date("Y", strtotime($Date_start));
$s_quer = "update `act{$iY}` set `Date_end`='$d_canc',Date_ed='".date("Y-m-d H:i:s")."',`TN_canc`=$TN_canc where `Bill_Dog`=$Bill_Dog and id_ActionType=4 and `Date_start`= '$Date_start' and `Date_end`='$Date_end' and not canc";
echo "$iY Оконч.отпуска ";//.$s_quer
$res = mysql_query($s_quer);
echo "перенесёно на $d_canc.</br>";

//*********************	Изменение заявки на подключение
	$de = strtotime($Date_end);
	$Date_on_1 = date("Y-m-d",mktime(0,0,0,date("m",$de),date("d",$de),date("Y",$de)));
$res=mysql_query("select * from notify_repair where Bill_Dog=$Bill_Dog and conn=1 and Date_Plan='$Date_on_1'") or die(mysql_error());
$r_res = mysql_fetch_array($res, MYSQL_ASSOC);
/*if (mysql_num_rows($res)>0) { // в заявке нет кода адреса && $r_res["Cod_flat"]==0
		$Cod_flat = $r_res['Cod_flat']==0?get_Cod_flat($id_p=$r_res['id_p'], $fl=$r_res['fl']):$r_res['Cod_flat'];
}	*/

echo "Подключение после отпуска ";//.$s_quer
$dc = strtotime($d_canc);
$Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dc),date("d",$dc),date("Y",$dc)));
if(mysql_num_rows($res)>0) { 
	$s_quer ="update notify_repair set Cod_flat=$Cod_flat,Date_Plan='$Date_Plan',Date_ed='".date("Y-m-d H:i:s")."',TN_canc=$TN_canc where Bill_Dog=$Bill_Dog and conn=1 and Date_Plan='$Date_on_1'";// and Date_Fact is null
echo "перенесёно";
} else {
	$s_quer= "insert into `notify_repair` (Cod_flat,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn) ".
						"values ($Cod_flat,$TN_canc,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_Plan',1)";
echo "добавлено";
}
$res = mysql_query($s_quer) or die(mysql_error());
echo " на $d_canc.</br>";

//*********************	
$s_quer ="update `customers` set Cod_flat=$Cod_flat,Date_end_st='$d_canc',`Date_pay`='$Date_pay' where `Bill_Dog`=$Bill_Dog";
echo "Отпуск по $d_canc, оплачено по $Date_pay - ";//.$s_quer
$res = mysql_query($s_quer) or die(mysql_error());
echo "установл.</br>";

//*********************	
	echo "Откл. за долг ";//$Bill_Dog
	$r_DP="select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` where Date_Fact is null and Bill_Dog=$Bill_Dog and conn=-1 order by `Date_Plan` desc";
	$q_DP = mysql_query($r_DP) or die(mysql_error());
	$dp = strtotime($Date_pay);
	$Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
	if(mysql_num_rows($q_DP)>0) {
		echo "перенесёно";//
		$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
		$q_noti2del ="update `notify_repair` set Cod_flat=$Cod_flat,Date_Plan='$Date_Plan' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Max_Date_Plan"]."'";
	} else {//id_p,fl	$id_p,$fl
		$q_noti2del = "insert into `notify_repair` (Cod_flat,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn) ".
						"values (".$r_quer["Cod_flat"].",$TN_canc,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_Plan',-1)";
		echo "установлено";
	}
//	echo	$q_noti2del;
	echo " на $Date_Plan.</br>";
	$r_noti2del =  mysql_query($q_noti2del) or die(mysql_error());
	
	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value=$Cod_flat; './/document.forms.ulaForm.h_Cod_flat.value
			'document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');" value="Обновить"/>';
//	echo 'Обновить экран. <button name="B_chk_adress" type=button onClick="f=document.forms[&quot;ulaForm&quot;];f.sCod_flat.value = '.$_REQUEST["Cod_flat"].'; setTimeout(&quot;f.sCod_flat.onchange();&quot;, 1000);"><img src="reload.png" align=middle alt="Обнови"></button>';

return; //#####################################################################################
///////////////////////////////////////////////////////////
$Nic  		= $_REQUEST ["Nic"];
$Login  	= $_REQUEST ["Login"];
$abon 		= $_REQUEST ["abon"];
$inet 		= $_REQUEST ["inet"];
$abon_Com 	= $_REQUEST ["abon_Com"];
$inet_Com 	= $_REQUEST ["inet_Com"];
//$new_Date_end = $_REQUEST ["new_Date_end"];
$action 	= $_REQUEST ["action"];
$nDateAct 	= $_REQUEST ["nDateAct"];
$id_p		= $_REQUEST ["id_p"];
$fl			= $_REQUEST ["fl"];
$TabNum  	= $_REQUEST ["TabNum"];

	
if ($_REQUEST ["abon"] !=0) {
//$to_Date_pay = ($action > 0?$nDateAct:$new_Date_pay);
//*********************	
/*	$q_abon = "select `Saldo` from `v_customer` where Bill_Dog=$Bill_Dog";//." and Flat=".$fl
	$s_abon =  mysql_query($q_abon) or die(mysql_error());
	$row_abon = mysql_fetch_array($s_abon, MYSQL_ASSOC);
*/		
//*********************	
	echo "Оплаченный срок в заявке на отключение ... ";//$Bill_Dog
	$q_DP = mysql_query("select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` where Bill_Dog=$Bill_Dog and conn=-1 order by `Date_Plan` desc") or die(mysql_error());
	if(mysql_num_rows($q_DP)>0) {
		$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
		$s_del ="update `notify_repair` set Date_Plan='$nDateAct' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Max_Date_Plan"]."'";
		$q_del = mysql_query($s_del) or die(mysql_error());
//		$q_del = mysql_query("update `customers` set Date_Plan='$Date_end' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Date_end_st"]."'") or die(mysql_error());
		echo "Перенесён на $nDateAct.</br>";//$Date_end
	} else {
		$q_ins_noti = "insert into `notify_repair` (id_p,fl,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn) ".
						"values ($id_p,$fl,$TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$nDateAct',-1)";
		$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		echo "установлен в $nDateAct.</br>";
	}
	
//*********************	
	echo "Изменения в данные о подключении к сети... ";//Saldo=".($_REQUEST ["abon"]+$row_abon["Saldo"])."
 	$q_cor_abon = "update `customers` set Date_pay='$nDateAct' where Bill_Dog=$Bill_Dog";//
	$s_cor_abon =  mysql_query($q_cor_abon) or die(mysql_error());// Date_start_st='$Date_start', Date_end_st='$to_Date_end',
	echo "Внесены.</br>";	//	 state=1,
	
//*********************	!!!!
/*	echo "Изменения в данные подключения к сети... ";
//	$q_cor_nics = "update `nics` set  state=1, Date_end_st='$new_Date_end' where Bill_Dog=$Bill_Dog";//		.'</br>'
	$q_cor_nics = "update `customers` set  state=1, Date_end_st='$new_Date_end' where Bill_Dog=$Bill_Dog";//		.'</br>'
	$s_cor_nics =  mysql_query($q_cor_nics) or die(mysql_error());	// echo "Ошибка при обновлении подключения к сети.\n";
	echo "Внесены.</br>";
*/	
//*********************	
	echo "Информация о платеже абонплаты ... ";
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d")."','$Date_start','$Date_end',$abon,1,'$abon_Com')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	echo "Добавлена.</br>";
	
//*********************	
		if ($action > 0) {
			echo "Информация о предоставлении $action мес. по акции ... ";
			$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
							"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_end','$nDateAct',0,3,'по акции $action мес.')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			echo "Добавлена.</br>";
		}
	}
//*********************	
	if ($inet !=0) {
		echo "Информация о платеже за интернет ... ";
	//*********************	
		$q_ins_inet = "insert into `actions` (TabNum,Bill_Dog,Nic,Login,InputDate,Summa,id_ActionType,Comment) ".
						"values ($TabNum,$Bill_Dog,'$Nic','$Login','".date("Y-m-d H:i:s")."',$inet,2,'$inet_Com')";//		.'</br>'
		$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
	//*********************	
		$s_inet =  mysql_query("select `saldo` from `logins` where Login='$Login'") or die(mysql_error());
		$row_inet = mysql_fetch_array($s_inet, MYSQL_ASSOC);
		$s_cor_inet =  mysql_query("update `logins` set saldo=".($inet+$row_inet["saldo"])." where Login='$Login'") or die(mysql_error());
		echo "Добавлена.</br>";
		
	//*********************	
		$q_inet = "select `saldo` from `logins` where Login='$Login'";//." and Flat=".$fl
		$s_inet =  mysql_query($q_inet) or die(mysql_error());
		$row_inet = mysql_fetch_array($s_inet, MYSQL_ASSOC);
		echo '<FONT size=+1>Баланс интернета после внесения: '.set_color($row_inet["saldo"]).' руб.</FONT></br>';
	//*********************	
	}
//*********************	
	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value=document.forms.ulaForm.h_Cod_flat.value; '.
			'document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');'.
			'" value="Обновить"/>';	//		//document.getElementById(\'B_chk_adress\').onClick();	ch(\'ch_flt\',\'menu=pay&\',2,\'tab_Cust\');
//*********************		//	*********************	//	*********************

function set_color ($sum) {
	return '<font color="'.(($sum<0)?'FF0000':'0033FF').'"><b>'.$sum.'</b></font>';
}
?>