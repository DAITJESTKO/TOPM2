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

$Bill_Dog  	= $_REQUEST ["BD"];
$InputDate	= $_REQUEST ["ID"];
$Date_start = $_REQUEST ["Ds"];
$Date_end 	= $_REQUEST ["De"];

//*********************	 and id_ActionType=4
$dp = strtotime($Date_start);
$nDs = date("d",$dp)>27?date("Y-m-d",mktime(0,0,0,date("m",$dp)+1,1,date("Y",$dp))):$Date_start;

$dp = strtotime($Date_end);
$nDe = date("d",$dp)>27?date("Y-m-d",mktime(0,0,0,date("m",$dp),date("t",$dp),date("Y",$dp))):$Date_end;

$s_quer = "update `actions` set Date_start='$nDs',`Date_end`='$nDe' where `Bill_Dog`=$Bill_Dog and InputDate='$InputDate' and `Date_start`= '$Date_start' and `Date_end`='$Date_end'";
$res = mysql_query($s_quer) or die(mysql_error());
echo "√";
?>