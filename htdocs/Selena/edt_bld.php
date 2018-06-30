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
$tn = (!$_REQUEST ["tp"]==4 && isset($_REQUEST ["tn"]))?" and (m_TabNum=".$_REQUEST ["tn"].")":"";
$tp = !(isset($_REQUEST ["tp"])?($_REQUEST ["tp"]==4):1);
$TypePers = isset($_REQUEST ["tp"])?$_REQUEST ["tp"]:4;
	$k = $_REQUEST ["k"];	
/*	$st = $_REQUEST ["st"];	
	$Num_build = $_REQUEST ["Num_build"];	*/
if ($GLOBALS['menu'] == 'edt_bld') {
//echo $GLOBALS['menu'];
//	$q_bld = "SELECT * from v_pd where id_street=$st and Num_build = $Num_build";
	$q_bld = "SELECT * FROM `spr_podjezd` where id_korp=$k";
	$result = mysql_query($q_bld) or die(mysql_error());		// Выполняем запрос

$i = 0;
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgcolor = $cfg['BgcolorOne'];

//	$q_num_build = "SELECT * FROM spr_build where id_street=".$st.
?>	<table width="800" border="0" cellpadding="2" cellspacing="1" >
	<tr>
	<td bgcolor="<?php echo $bgcolor; ?>"><input name="h_k" type="hidden" value="<?php echo $k ?>" /></td>
	<td bgcolor="<?php echo $bgcolor; ?>"><strong></strong></td>
	<td bgcolor="<?php echo $bgcolor; ?>"><strong>№ подъезда</strong></td>
	<td bgcolor="<?php echo $bgcolor; ?>"><strong>№ 1-й кв.</strong></td>
	<td bgcolor="<?php echo $bgcolor; ?>"><strong>№ последн.кв.</strong></td>
		<? if ($TypePers==1) { ?>
			<td bgcolor="<?php echo $bgcolor; ?>"><strong>IP Range</strong></td>
			<td bgcolor="<?php echo $bgcolor; ?>"><strong>VLan</strong></td>
			<td bgcolor="<?php echo $bgcolor; ?>"><strong>свич</strong></td>
			<td bgcolor="<?php echo $bgcolor; ?>"><strong>авто</strong></td>
		<? } ?>
	<td bgcolor="<?php echo $bgcolor; ?>"><strong></strong></td>
	<td bgcolor="<?php echo $bgcolor; ?>"><strong></strong></td>
	</tr>
<?
	while ($row = mysql_fetch_assoc($result))  {
		$bgcolor = ($i++ % 2) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];//
		$id_p = $row["id_Podjezd"];
?>	<tr>
		<td bgcolor="<?php echo $bgcolor; ?>"> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> </td>
<?php /*?>		<td bgcolor="<?php echo $bgcolor; ?>"> <input name="k<?php echo $row["id_Podjezd"]?>" type="text" value="<?php echo $row["Korpus"]; ?>" size="3" /> </td><?php */?>
		<td bgcolor="<? echo $bgcolor?>"> <input name="p<? echo $id_p?>" type="text" value="<? echo $row["Podjezd"]; ?>" size="3" /> </td>
		<td bgcolor="<? echo $bgcolor?>"> <input name="f<? echo $id_p?>" type="text" value="<? echo $row["FirstFlat"]; ?>" size="3" /> </td>
		<td bgcolor="<? echo $bgcolor?>"> <input name="l<? echo $id_p?>" type="text" value="<? echo $row["LastFlat"]; ?>" size="3" /> </td>
		<? if ($TypePers==1) { ?>
			<td bgcolor="<? echo $bgcolor?>"> <input name="ip<? echo $id_p?>" type="text" value="<? echo $row["IP_Range"]; ?>" size="8" /> </td>
			<td bgcolor="<? echo $bgcolor?>"> <input name="v<? echo $id_p?>" type="text" value="<? echo $row["VLan"]; ?>" size="5" /> </td>
			<td bgcolor="<? echo $bgcolor?>"> <input name="s<? echo $id_p?>" type="text" value="<? echo $row["switch"]; ?>" size="3" /> </td>
			<td bgcolor="<? echo $bgcolor?>"> <input name="a<? echo $id_p?>" type="checkbox" <? echo $row["auto"]==1?'checked=\"true\"':''; ?> /> </td>
		<? } ?>
		<td bgcolor="<? echo $bgcolor?>"><?php /*?> //  alert(&quot; &quot;) &K='+f.k<?php echo $row["id_Podjezd"]?>.value+' <?php */?>
		<button name="B_sv" type=button onClick="f=document.forms['ulaForm']; ch_param('do_cor_bld','<? echo "id_p=$id_p&P='+f.p{$id_p}.value+'&F='+f.f{$id_p}.value+'&L='+f.l{$id_p}.value+'&V='+f.v{$id_p}.value+'&S='+f.s{$id_p}.value+'&A='+(f.a{$id_p}.checked==true?'1':'0')"?>,'d<? echo $id_p ?>');"><img src="upload.gif"></button>
		</td>
		<td bgcolor="<? echo $bgcolor; ?>"> <div id="d<? echo $id_p ?>"></div></td>
	</tr>
<?	} ?>
<?
}
?>	