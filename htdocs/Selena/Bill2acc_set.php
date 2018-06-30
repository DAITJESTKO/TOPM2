<?php
require_once("for_form.php"); 
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
$nb1 = $_REQUEST ["nb1"];
$nb2 = $_REQUEST ["nb2"];

$res1 = mysql_query("SELECT Nic FROM `customers` where `Bill_Dog`=$nb1") or die(mysql_error());
$res2 = mysql_query("SELECT Nic FROM `customers` where `Bill_Dog`=$nb2") or die(mysql_error());
if (!(mysql_num_rows($res1)>0 && mysql_num_rows($res2)>0)) {
  	echo "Возникли проблемы! Не наден договор $nb1 или $nb2";     return 0;
}
$r1 = mysql_fetch_assoc($res1);
$r2 = mysql_fetch_assoc($res2);

$res_nic = mysql_query("SELECT Nic FROM `logins` where `account`=$nb2") or die(mysql_error());	//	`Login`='".$r2["Nic"]."'
if (mysql_num_rows($res_nic)>0) { // Login имеется, ИСПРАВЛЯЕМ номер аб.дрговора
	$q_cor_log = "update logins set Nic='".$r1["Nic"]."', Bill_Dog=$nb1 where `account`=$nb2";
} else {
	$q_cor_log = "insert into `logins` (tarif3w_date,id_tarif3w,Nic,Login,Bill_Dog, account) ".
						   "values ('".date("Y-m-d")."',1,'".$r1["Nic"]."','".$r2["Nic"]."',$nb1, $nb2)";
}
//echo $q_cor_log;
$s_ins_log =  mysql_query($q_cor_log) or die(mysql_error());
//***********************************
echo '<button type=button onClick="f=document.forms.ulaForm;f.sBill_Dog.value = '.$nb1.'; setTimeout(&quot;f.sBill_Dog.onchange();&quot;, 300);">'.
		'<img src="reload.png" align=middle alt="Обнови"></button>';
?>