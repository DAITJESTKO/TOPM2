<?
require_once("for_form.php"); 
check_valid_user();
  $conn = db_connect();
  if (!$conn) return 0;
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
	$st  = $_REQUEST ["st"];
	$nb  = "'".$_REQUEST ["nb"]."'";
	$k  = "'".$_REQUEST ["k"]."'";
	$r  = $_REQUEST ["r"];

	$rslt = mysql_query("SELECT * FROM spr_build where id_street=$st and Num_build=$nb and Korpus=$k") or die(mysql_error());
	if (mysql_num_rows($rslt)>0) {
		echo "X";
	} else {
		$ins_qry = "insert into `spr_build` (id_street,Num_build,Korpus,id_Region) values ($st, $nb, $k, $r)";
//		echo $ins_qry;
		$ins_rslt =  mysql_query($ins_qry) or die(mysql_error());		?>
		<button name="B_chk_adress" type=button onClick="f=document.forms.ulaForm; setTimeout('f.id_street.onchange();', 1000);"><img src="reload.png" alt="Обнови"></button>
<?	}	?>
