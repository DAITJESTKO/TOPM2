<? require_once("for_form.php"); 
  check_valid_user();
  $conn = db_connect();
  if (!$conn) return 0;
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");	
	
$B = intval($_REQUEST ["B"]);
$res = mysql_query("update customers set ".($_REQUEST ["d"]=="p"?"Date_pay=Date_end_st":"Date_end_st=Date_pay")." where Bill_Dog=".$B);
echo ($res) ? "исправлено":"возникла ошибка";
?>