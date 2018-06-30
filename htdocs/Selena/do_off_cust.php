<? /*
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
	$Date_X = $today; //"2012-01-21";//
	$TabNum = 17;	// авто
	$Date_Fact = $Date_X;
	
echo "<br>Автоматическое отключение абонентов<br>";
	require_once("db_fns.php");
	$conn = db_connect();

/*	
$q_ = mysql_query("SELECT `Bill_Dog` , Nic, `Date_start_st` , `Date_end_st` , `Date_pay` 
FROM `v_customer` 
WHERE auto =1
AND inet IS NULL 
AND LENGTH( `mac` ) >0
AND `state` =1
AND id_tarifab NOT IN ( 2, 3 ) 
AND DATE_ADD(`Date_end_st`, INTERVAL 1 DAY) <= '$Date_X'
# AND `Date_pay` = `Date_pay` 
# AND `Date_pay` +30 > curdate( ) 
# AND `Bill_Dog` NOT IN ( SELECT `Bill_Dog` FROM v_radreply )") or die(mysql_error());//``
///////////////////////////////////
SELECT c.`Bill_Dog` , c.Nic, c.`Date_start_st` , c.`Date_end_st` , c.`Date_pay`,
notify_repair.Num_Notify, notify_repair.Date_Plan, notify_repair.Notify,
if(notify_repair.Notify='откл.(замороз)',
	(select Date_Plan from notify_repair as n 
	 	where n.conn=1 and n.`Date_Fact` is null and n.Date_Plan <= c.`Date_pay` and n.Date_Plan > c.`Date_end_st` and n.Bill_Dog = c.Bill_Dog), null ) as D_con
FROM
v_customer as c
Inner Join notify_repair ON c.Bill_Dog = notify_repair.Bill_Dog
WHERE auto =1
AND inet IS NULL 
AND LENGTH( `mac` ) >0
AND `state` =1
AND id_tarifab NOT IN ( 2, 3 ) 
AND `Date_end_st` < curdate()
and notify_repair.`Date_Fact` is null 
and notify_repair.conn=-1
and notify_repair.Date_Plan = (
	select Date_Plan from notify_repair 
		where (Date_Plan=DATE_ADD(c.`Date_end_st`, INTERVAL 1 DAY) or Date_Plan=c.`Date_end_st`)
			and `Date_Fact` is null and conn=-1 and `Bill_Dog`=c.`Bill_Dog`)
ORDER BY `c`.`Bill_Dog` ASC*/
/* !!!
$q_err = mysql_query("SELECT c.`Bill_Dog`, c.Nic, c.`Date_start_st`, c.`Date_end_st`, c.`Date_pay`,
notify_repair.Num_Notify, notify_repair.Date_Plan, notify_repair.Notify
	, if(notify_repair.Notify='откл.(замороз)',
	(select sum(1) from notify_repair as n 
	 	where n.conn=1 and n.Notify='подкл.(замороз)' and n.`Date_Fact` is null and n.Date_Plan <= c.`Date_pay` and n.Date_Plan > c.`Date_end_st` and n.Bill_Dog = c.Bill_Dog), null ) as D_con
FROM
v_customer as c
Inner Join notify_repair ON c.Bill_Dog = notify_repair.Bill_Dog
WHERE auto =1
AND inet IS NULL 
AND LENGTH( `mac` ) >0
AND `state` =1
and id_tarifab NOT IN ( 2, 3 )
AND DATE_ADD(`Date_end_st`, INTERVAL (if(id_tarifab IN ( 6 ),5,0)) DAY) < curdate()
AND `Date_end_st` < curdate()
and notify_repair.`Date_Fact` is null 
and notify_repair.conn=-1
and (notify_repair.Date_Plan=DATE_ADD(c.`Date_end_st`, INTERVAL 1 DAY) or notify_repair.Date_Plan=c.`Date_end_st`)
	ORDER BY `c`.`Bill_Dog` ASC") or die(mysql_error());

if (($num_r = mysql_num_rows($q_err)) > 0) {
	echo "</br>&nbsp;&nbsp;Ошибки в заявках абонентов:<br>";
	while ($row = mysql_fetch_array($q_err, MYSQL_ASSOC))   //mysql_fetch_assoc($q_, MYSQL_ASSOC)
		// Закрытие заявки	'$today'
		echo "&nbsp;&nbsp;<b>".$row['Bill_Dog'],"</b> - ",$row["Notify"]," с ",$row['Date_Plan'],$row['D_con']=="NULL"?"":($row['D_con']." по <i>ошибка</i>"),", оплачен по ",$row['Date_pay']."</br>";
	return;
} else { echo "Ошибок в базе нет, проверка списка отключаемых аббонентов</br>"; }
*/
$q_ = mysql_query("SELECT c.`Bill_Dog`, c.Nic, c.`Date_start_st`, c.`Date_end_st`, c.`Date_pay`,
notify_repair.Num_Notify, notify_repair.Date_Plan, notify_repair.Notify
, if(notify_repair.Notify='откл.(замороз)',
	(select Date_Plan from notify_repair as n 
	 	where n.conn=1 and n.Notify='подкл.(замороз)' and n.`Date_Fact` is null and n.Date_Plan <= c.`Date_pay` and n.Date_Plan > c.`Date_end_st` and n.Bill_Dog = c.Bill_Dog), null ) as D_con
FROM
v_customer as c
Inner Join notify_repair ON c.Bill_Dog = notify_repair.Bill_Dog
WHERE auto =1
AND inet IS NULL 
AND LENGTH( `mac` ) >0
AND `state` =1
AND id_tarifab NOT IN ( 2, 3 ) 
AND DATE_ADD(`Date_end_st`, INTERVAL (if(id_tarifab IN ( 6 ),5,0)) DAY) < curdate()
and notify_repair.`Date_Fact` is null 
and notify_repair.conn=-1
and (notify_repair.Date_Plan=DATE_ADD(c.`Date_end_st`, INTERVAL 1 DAY) or notify_repair.Date_Plan=c.`Date_end_st`)
	ORDER BY `c`.`Bill_Dog` ASC") or die(mysql_error());
//	$res = mysql_fetch_array($q_, MYSQL_ASSOC);
if (($num_r = mysql_num_rows($q_)) == 0) {
	echo "</br>&nbsp;&nbsp;Должников нет";
	return;
}
echo "Отключаемых абонентов - ",$num_r, "</br>";
while ($row = mysql_fetch_array($q_, MYSQL_ASSOC))  { //mysql_fetch_assoc($q_, MYSQL_ASSOC)
	// Закрытие заявки	'$today'
	echo $row['Bill_Dog']," - ",$row["Notify"]," с ",$row['Date_Plan'],$row['D_con']=="NULL"?"":(" по ".$row['D_con']),", оплачен по ",$row['Date_pay'];
/*	echo $s="SELECT Num_Notify from notify_repair where Num_Notify={$row['Num_Notify']}";
	$q_n = mysql_query($s) or die(mysql_error());
	$r_not = mysql_fetch_array($q_n, MYSQL_ASSOC);
*/ 											//	Date_Plan
	$q_noti = "update notify_repair set Date_Fact='$today' ,mont=$TabNum, Date_ed='$now' where Num_Notify={$row['Num_Notify']}";//`Date_Fact` is null and `Notify`='откл.(долг)' and `Bill_Dog`={$row['Bill_Dog']}
	$s_noti =  mysql_query($q_noti) or die(mysql_error());
	
	if ($row["Notify"]=='откл.(смена адр.)') {
		// При отключении для смены адреса ничего не делаем
	} else if ($row["Notify"]=='откл.(долг)') {
		// Отключаем за долг
	//		$Date_end_st = (empty($Date_end_st)?"null":"'$Date_end_st'");	$todayDate_pay+1
														//		{$row['Date_Plan']}
		$q_cust = "update customers set state=2, Date_start_st='$today', Date_end_st = null where `Bill_Dog`={$row['Bill_Dog']}";
		$s_cust =  mysql_query($q_cust) or die(mysql_error());
	} else {//	"подкл.(замороз)"	, Date_pay='$Date_pay''$Date_end_st'
		// Отключаем в отпуск, окончание отключенного состояния - 
/*		$q_end="select Date_Plan from notify_repair where conn = 1 and Date_Plan > '$Date_X' and `Date_Fact` is null and Bill_Dog={$row['Bill_Dog']}";
		$s_end =  mysql_query($q_end) or die(mysql_error());
		if (mysql_num_rows($s_end)>0) {
			$r_end = mysql_fetch_array($s_end, MYSQL_ASSOC);
			$D_end = $r_end["Date_Plan"];	*/
	if ($row["Notify"]=='откл.(замороз)') {
			$D_end = $row["D_con"];
		} else {
			$D_end = $row['Date_pay'];
		}
		echo " Плановое подключение из отпуска: $D_end<br>";
												//		{$row['Date_Plan']}
		$q_cust="update customers set state=2, Date_start_st='$today', Date_end_st = '$D_end' where Bill_Dog={$row['Bill_Dog']}";
		$s_cust =  mysql_query($q_cust) or die(mysql_error());
	}
	
	echo "<img src='create_check.gif'/></br>";
	}
echo "Готово</br>";
?>