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
$InputDate = $_REQUEST ["di"];
$Date_start = $_REQUEST ["ds"];
$Date_end = $_REQUEST ["de"];
//$sq = ;
$ra = mysql_query("select * from act".date("Y",strtotime($InputDate))." where InputDate='$InputDate' and Date_start='$Date_start' and Date_end='$Date_end'") or die(mysql_error());
echo ($ra) ? "найден платёж":"возникла ошибка", "<br>";
$rc = mysql_query("select * from customers where Bill_Dog=".$ra["Bill_Dog"]) or die(mysql_error());
echo ($rc) ? "найден абонент":"возникла ошибка", "<br>";
?>