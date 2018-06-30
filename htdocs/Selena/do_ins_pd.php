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
	$k  = $_REQUEST ["k"];

	$result = mysql_query("SELECT * FROM `spr_podjezd` where id_korp=$k") or die(mysql_error());		// Выполняем запрос
	$num_rows = mysql_num_rows($result);
	$P = ($num_rows >0) ? 1*$num_rows + 1 : $P = 1;
	$F=0; $L=0;	$V = 0;	$s = 0; $a = 0;	$n = 1;
	while ($row = mysql_fetch_assoc($result)) { $L=$row['LastFlat']; $n = $L - $row['FirstFlat']; 	$V = $row['VLan']+1; $s = $row['switch']; $a = $row['auto']; }
	$F = $L + 1; $L = $F + $n;

	$ins_qry = "insert into `spr_podjezd` (id_korp,podjezd,FirstFlat,LastFlat,VLan,switch,auto) values ($k, $P, $F, $L, $V, $s, $a)";
//	echo $ins_qry;
		$ins_rslt =  mysql_query($ins_qry) or die(mysql_error());	?>
Добавлен подъезд. Обновить экран. <button name="B_chk_adress" type=button onClick="f=document.forms.ulaForm; setTimeout('f.num_build.onchange();', 500);"><img src="reload.png" align=middle alt="Обнови"></button>