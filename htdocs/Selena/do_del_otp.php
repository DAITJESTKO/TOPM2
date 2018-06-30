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

$Bill_Dog  	= $_REQUEST ["Bill_Dog"];
$Date_start = $_REQUEST ["Date_start"];
$Date_end 	= $_REQUEST ["Date_end"];
$TN_canc	= $_REQUEST ["TN_canc"];

//*********************	
$s_quer = "update `actions` set `TN_canc`=$TN_canc, canc=1, Date_ed='".date("Y-m-d H:i:s")."' where `Bill_Dog`=$Bill_Dog and id_ActionType=4 and `Date_start`= '$Date_start' and `Date_end`='$Date_end' and not canc";
echo "Отпуск ... ";//.$s_quer
$res = mysql_query($s_quer) or die(mysql_error());
echo "отменен.</br>";

//*********************	
//$s_quer ="delete from `notify_repair` where Bill_Dog=$Bill_Dog and ((conn=1 and Date_Plan='".$Date_end."') or (conn=-1 and Date_Plan='".$Date_start."'))";
$s_quer = "update `notify_repair` set `TN_canc`=$TN_canc, Date_ed='".date("Y-m-d H:i:s")."' where Bill_Dog=$Bill_Dog and ((conn=1 and Date_Plan='$Date_end') or (conn=-1 and Date_Plan='$Date_start'))";
echo "Заявки монтажнику ... ";//.$s_quer
$res = mysql_query($s_quer) or die(mysql_error());
echo "отменены.</br>";

//*********************	
$q_quer = mysql_query("select `Date_pay` from customers where `Bill_Dog`=$Bill_Dog") or die(mysql_error());
$r_quer = mysql_fetch_array($q_quer, MYSQL_ASSOC);
$Date_pay = date("Y-m-d", strtotime(date($r_quer["Date_pay"])) - strtotime($Date_end) + strtotime($Date_start));

$s_quer ="update `customers` set `Date_pay`='$Date_pay' where `Bill_Dog`=$Bill_Dog";
echo "Оплаченная дата - $Date_pay ... ";//.$s_quer
$res = mysql_query($s_quer) or die(mysql_error());
echo "Установлена.</br>";

//*********************	
	echo "Оплаченный срок в заявке на отключение ... ";//$Bill_Dog
	$q_DP = mysql_query("select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` where Bill_Dog=$Bill_Dog and conn=-1") or die(mysql_error());
	if(mysql_num_rows($q_DP)>0) {
		$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
		$s_del ="update `notify_repair` set Date_Plan='$Date_pay' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Max_Date_Plan"]."'";
		$q_del = mysql_query($s_del) or die(mysql_error());
		echo "Перенесён на $Date_pay.</br>";//
	} else {
		$dp = strtotime($Date_pay);		$Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
		$q_ins_noti = "insert into `notify_repair` (id_p,fl,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn) ".
						"values ($id_p,$fl,$TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_pay',-1)";
		$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		echo "установлен в $Date_pay.</br>";
	}
	
	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value=document.forms.ulaForm.h_Cod_flat.value; '.
			'document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');" value="Обновить"/>';
//	echo 'Обновить экран. <button name="B_chk_adress" type=button onClick="f=document.forms[&quot;ulaForm&quot;];f.sCod_flat.value = '.$_REQUEST["Cod_flat"].'; setTimeout(&quot;f.sCod_flat.onchange();&quot;, 1000);"><img src="reload.png" align=middle alt="Обнови"></button>';

?>