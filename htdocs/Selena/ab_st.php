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
<?  $cmd = $_REQUEST ["cmd"];
	$id_tarifab = $_REQUEST ["id_tarifab"];
	$name_ab = $_REQUEST ["name_ab"];
	$name_abon = $_REQUEST ["name_abon"];
	$ab_sum = $_REQUEST ["ab_sum"];
	$k_tar = $_REQUEST ["k_tar"];
	$perstypes = $_REQUEST ["perstypes"];

//	if (!isset($_REQUEST ["ab_st"])) { return; }
//	$ab_st = $_REQUEST ["ab_st"];
	if ($cmd=='cor') {
	echo	$s_qer = "update `spr_tarifab` set `name_ab`='$name_ab',`name_abon`='$name_abon',`ab_sum`=$ab_sum,`k_tar`=$k_tar,`perstypes`=$perstypes where `id_tarifab`=$id_tarifab";
	} else {
	echo	$s_qer = "insert into `spr_tarifab` (id_tarifab, name_ab, name_abon, ab_sum, k_tar, perstypes) values ".
											  "($id_tarifab,'$name_ab','$name_abon',$ab_sum,$k_tar,$perstypes)";
	}
//	$rslt = mysql_query($s_qer) or die(mysql_error());
	echo "&radic;";
?>