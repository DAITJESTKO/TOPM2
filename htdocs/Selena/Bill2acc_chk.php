<?php
require_once("for_form.php"); 
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
$nb1 = $_REQUEST ["nb1"];
$nb2 = $_REQUEST ["nb2"];
$result = mysql_query("SELECT Nic FROM `customers` where `Bill_Dog`=$nb1");
if (!$result) {
  	echo "Возникли проблемы! Договор №$nb1 не найден!";
     return 0; 
}
$log2 = mysql_query("SELECT Nic, Bill_Dog FROM `logins` where `account`=$nb2");
if (mysql_num_rows($log2)>0) {
	$r2 = mysql_fetch_assoc($log2);
	echo "<b>В таблице интернет логинов счёт №$nb2 уже привязан к договору №".$r2["Bill_Dog"]."!";
	if ($nb1 == $r2["Bill_Dog"]) { return; }
	echo "</br>Перепривязать к аб.договору №$nb1?</b>";
}

if (mysql_num_rows($result)>0) { ?>
    <button type=button onClick="NewBillAcc();">Да</button>
<? } else {
	echo "не найден абонент";
}?>