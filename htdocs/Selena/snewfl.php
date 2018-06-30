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

	$Bill_Dog  = $_REQUEST ["B"];
	$Bill_Dog=(int)$Bill_Dog;
	$flat  = $_REQUEST ["f"];
	$flat=(int)$flat;
	if ($Bill_Dog == 0) return 0;
	$q_cor = "update `customers` set saldo=0, flat=$flat where Bill_Dog=$Bill_Dog";
	$s_cor =  mysql_query($q_cor) or die(mysql_error());
	echo "√";
	
/*	echo '    <input name="B_chk_adressi" type="button" onclick="'.
			$Bill_Dog.'; document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Bill_Dog\');'.
			'" value="Обновить"/>'; */
?>