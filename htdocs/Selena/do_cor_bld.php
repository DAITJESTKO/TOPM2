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

	$id_p  			= " id_Podjezd=".$_REQUEST ["id_p"];
//	$K  			= " Korpus='".$_REQUEST ["K"]."'";
	$P  			= " Podjezd=".$_REQUEST ["P"];
	$F  			= " FirstFlat=".$_REQUEST ["F"];
	$L  			= " LastFlat=".$_REQUEST ["L"];
	$V  			= " VLan=".$_REQUEST ["V"];
	$S  			= " switch=".$_REQUEST ["S"];
	$A  			= " auto=".$_REQUEST ["A"];

	$cor_qry = "update `spr_podjezd` set $P, $F, $L, $V, $S, $A where $id_p";// $K,
//echo $cor_qry.'</br>';
	$s_cor_qry =  mysql_query($cor_qry) or die(mysql_error());
	echo '<img src="BD21301_.GIF" />';
?>