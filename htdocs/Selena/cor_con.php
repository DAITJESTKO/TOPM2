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
	$id_tar_con = $_REQUEST ["id_tar_con"];
	$name_c = $_REQUEST ["name_cn"];
	$name_con = $_REQUEST ["name_con"];
	$con_sum = $_REQUEST ["con_sum"]==""?"NULL":$_REQUEST ["con_sum"];
	$opl_period = $_REQUEST ["opl_period"]==""?"NULL":$_REQUEST ["opl_period"];
	$con_typ = $_REQUEST ["con_typ"];
	$id_tarifab = $_REQUEST ["id_tarifab"];

	if ($cmd=='cor') {
		$s_qer = "update `spr_tar_con` set name_cn='$name_cn',name_con='$name_con',con_sum=$con_sum,opl_period=$opl_period,con_typ=$con_typ,id_tarifab=$id_tarifab where id_tar_con=$id_tar_con";
	} else {
		$s_qer = "insert into `spr_tar_con` (id_tar_con, name_cn, name_con, con_sum, opl_period, con_typ, id_tarifab) values ".
											  "($id_tar_con,'$name_cn','$name_con','$con_sum',$opl_period,$con_typ,$id_tarifab)";
	}
	$rslt = mysql_query($s_qer) or die(mysql_error());?>
<b>&radic;</b>

