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
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?

if (!isset($_REQUEST ["s1"]))exit;
$s1 = $_REQUEST ["s1"];
$s2 = $_REQUEST ["s2"];

	$q_Streets = "update spr_street set id_street=".$s2." where id_street=".$s1;
	$Streets = mysql_query($q_Streets) or die(mysql_error());
echo "spr_street исправлен строкой: $q_Streets<br>";

	$q_Streets = "update spr_build set id_street=".$s2." where id_street=".$s1;
	$Streets = mysql_query($q_Streets) or die(mysql_error());
echo "spr_build исправлен строкой: $q_Streets<br>";

	$q_Streets = "update spr_podjezd set id_street=".$s2." where id_street=".$s1;
	$Streets = mysql_query($q_Streets) or die(mysql_error());
echo "spr_podjezd исправлен строкой: $q_Streets<br>";
?>
