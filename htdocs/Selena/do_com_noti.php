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

	$comment  = $_REQUEST ["com"];
	$Notify = $_REQUEST ["Notify"];	//get_param("Notify");

// Изменение комментария в заявке
	$q_noti = "update `notify_repair` set comment='$comment', Date_ed='".date("Y-m-d H:i:s")."' where Num_Notify = $Notify";
	$s_noti =  mysql_query($q_noti) or die(mysql_error());
?>
<img src='create_check.gif'/>
