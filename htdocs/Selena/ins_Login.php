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
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

	$Bill_Dog = $_REQUEST ["Bill_Dog"];
	$Nic = $_REQUEST ["Nic"];
	$Login = $_REQUEST ["Login"];
	$id_tarif3w = $_REQUEST ["id_tarif3w"];
	$tarif3w_date = $_REQUEST ["tarif3w_date"];
		$q_Login = "select * FROM logins where account=$Bill_Dog";// $Nic
		$s_Login =  mysql_query($q_Login) or die(mysql_error());
		$totalRows_Login = mysql_num_rows($s_Login);
		$account = ($totalRows_Login > 0)?get_Bill_Dog():$Bill_Dog; // пока не работает автодобавление из-за сдвоенных счетов
	$sL_col = "account, Bill_Dog, Nic, Login, id_tarif3w, tarif3w_date";
	$vL_col = "$account, $Bill_Dog,'$Nic','$Login', $id_tarif3w, $tarif3w_date";
	$qL_ins_login = "insert into `logins` (".$sL_col.") values (".$vL_col.")";
// 	echo "</br>".$qL_ins_login;
	$sL_ins_login =  mysql_query($qL_ins_login) or die(mysql_error());	
?>