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
	$new = (isset($_REQUEST ["new"]))? $_REQUEST ["new"]:"";
	$new_p = (isset($_REQUEST ["new"]))? "new=new&":"";
	$menu_p = (isset($GLOBALS['menu']))? "menu=".$GLOBALS['menu']."&":"";
	$trg = (isset($GLOBALS["trg"]))? "trg=".$GLOBALS["trg"]."&":"";
//echo $GLOBALS['tn'], " ", $GLOBALS['tp'];
//	$tp = (isset($GLOBALS['tp']))? "tp=".$GLOBALS['tp']."&":"";
//	$tn = (isset($GLOBALS['tn']))? "tn=".$GLOBALS['tn']."&":"";
//	$par = $tn.$tp.$trg.$new_p.$menu_p;
	$tp = (isset($GLOBALS['tp']))? "tp=".$GLOBALS['tp']."&":"";
	$tn = (isset($GLOBALS['tn']))? "tn=".$GLOBALS['tn']."&":"";
	$Nic = (isset($_REQUEST['Nic']))? "Nic=".$GLOBALS['Nic']."&":"";
	$par = $tn.$tp.$trg.$new_p.$menu_p.$Nic;
//	$par = ((isset($GLOBALS['tn']))? "tn=".$GLOBALS['tn']."&":"").((isset($GLOBALS['tp']))? "tp=".$GLOBALS['tp']."&":"").$trg.$new_p.$menu_p;
	$tw = $_REQUEST ["tw"];
	if (isset($_REQUEST ["nx"]))
		{ $nx = $_REQUEST ["nx"]; } else { $nx = "num_build".$new; }
///	$ind = 1; 
	if (isset($GLOBALS['tp']) && ($GLOBALS['tp']==4)) {
		$q_Streets = "SELECT distinct id_street, name_street FROM v_podjezd where id_town=".$tw." and id_Region=".getRgn($GLOBALS['tn'])." order by name_street";
	} else {
		$q_Streets = "SELECT * FROM spr_street where id_town=".$tw." order by name_street";
	}
	$Streets = mysql_query($q_Streets) or die(mysql_error());
	$row_Streets = mysql_fetch_assoc($Streets);
	$totalRows_Streets = mysql_num_rows($Streets);
?>
<select name="id_street<? echo $new; ?>" class='font8pt' lang="ru" onchange='ch("ch_street","<?php
		echo $par; ?>st",this,"<?php echo $nx; ?>"); <? echo ($new!="new")?"clr_adress();":""; ?>'>
  		<option value="0"><? echo "Выбор улицы"?> </option>
<?php 		do {  ?>
  		<option value="<?php echo $row_Streets['id_street'] ?>" <? //if($ind ==1) { echo "selected"; }; ?> > 
  		<?php echo $row_Streets['name_street'] ?></option>
  		<?php //$ind++;
} while ($row_Streets = mysql_fetch_assoc($Streets));
  $rows = mysql_num_rows($Streets);
/*  if($rows > 0) {
      mysql_data_seek($Streets, 0);
	  $row_Streets = mysql_fetch_assoc($Streets);
  }	*/
?>
</select>
<?
function getRgn($TabNum) {
	$result = mysql_query("select id_Region from personal where TabNum=$TabNum") or die(mysql_error());
	$row_res = mysql_fetch_assoc($result);
	return $row_res["id_Region"];
}
?>