<?
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

echo	$mac  = $_REQUEST ["m"];
	$Bill_Dog = $_REQUEST ["bd"];	//get_param("Notify");
	$Y = date("Y");

	$get_old_m =  mysql_query("select mac, Nic from customers where Bill_Dog= $Bill_Dog") or die(mysql_error());
	$row_mac 	= mysql_fetch_array($get_old_m, MYSQL_ASSOC);
	$old_mac 	= $row_mac["mac"];
	$Nic		= $row_mac["Nic"];
	$TabNum		= $_REQUEST ["TabNum"];
	
	$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','".date("Y-m-d")."','".date("Y-m-d H:i:s")."',0,6,'old_mac=$old_mac')";//	.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','".date("Y-m-d")."','".date("Y-m-d H:i:s")."',0,6,'old_mac=$old_mac')";//	.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
// Изменение комментария в заявке
	$q_cor = "update `customers` set mac='$mac' where Bill_Dog= $Bill_Dog";
	$s_cor =  mysql_query($q_cor) or die(mysql_error());
?>
<img src='create_check.gif'/>
