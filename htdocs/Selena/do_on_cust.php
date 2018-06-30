<?	/*
require_once("for_form.php"); 
check_valid_user();
  $db_conn = db_connect();
  if (!$db_conn) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");
*/
	$today = date("Y-m-d");
	$now = date("Y-m-d H:i:s");
	$Date_Fact = $today;
	$TabNum = 17;	// авто
echo "Автоматическое подключение абонентов<br>";
	require_once("db_fns.php");
	$conn = db_connect();
$q_ = mysql_query("SELECT
notify_repair.`Cod_flat`,
notify_repair.Bill_Dog,
notify_repair.Notify,
notify_repair.Num_Notify,
notify_repair.conn,
notify_repair.Date_Plan,
notify_repair.Date_Fact,
v_customer.mac,
v_customer.Nic,
v_customer.Date_pay
FROM
	notify_repair
	Inner Join v_customer ON notify_repair.Bill_Dog = v_customer.Bill_Dog
WHERE
	notify_repair.conn =  '1' AND
	notify_repair.Date_Plan <=  curdate() AND
	notify_repair.Date_Fact IS NULL  AND
	v_customer.auto =  '1' AND
	length(v_customer.mac) >  0 AND
	v_customer.state =  '2' and
	v_customer.Date_pay > curdate()
") or die(mysql_error());//``
//	$res = mysql_fetch_array($q_, MYSQL_ASSOC);
if (($num_r = mysql_num_rows($q_)) == 0) {
	echo "<br>&nbsp;&nbsp;Подключать некого<br>";
	return;
}
echo "Подключаемых абонентов - ",$num_r, "<br>";
while ($row = mysql_fetch_array($q_, MYSQL_ASSOC))  { //mysql_fetch_assoc($q_, MYSQL_ASSOC)
	// Закрытие заявки	'$today'
	echo "Договор ", $row['Bill_Dog']," заявка на ",$row['Date_Plan']," оплачено по ",$row['Date_pay']," - ";
 	$q_noti = "update notify_repair set Date_Fact=Date_Plan,mont=$TabNum,Date_ed='$now' where Num_Notify = {$row['Num_Notify']}";
	$s_noti =  mysql_query($q_noti) or die(mysql_error());
	
//		$Date_end_st = (empty($Date_end_st)?"null":"'$Date_end_st'");	$today
 	$q_cust = "update customers set state=1, Date_start_st='{$row['Date_Plan']}', Date_end_st = '{$row['Date_pay']}', DateKor='$now', mont=$TabNum where `Bill_Dog`={$row['Bill_Dog']}";
	$s_cust =  mysql_query($q_cust) or die(mysql_error());
	echo "<img src='create_check.gif'/><br>";
	}
echo "Готово<br>";
?>