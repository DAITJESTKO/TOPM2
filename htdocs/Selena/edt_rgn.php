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
//	$k = $_REQUEST ["k"];	
	$st = $_REQUEST ["st"];	
/*	$Num_build = $_REQUEST ["Num_build"];	*/
//*/if ($GLOBALS['menu'] == 'edt_bld') {
//echo $GLOBALS['menu'];
//	$q_bld = "SELECT * from v_pd where id_street=$st and Num_build = $Num_build";
	//	$q_bld = "SELECT * FROM `spr_podjezd` where id_korp=$k";
	//	$result = mysql_query($q_bld) or die(mysql_error());		// Выполняем запрос

	$q_num_build = "SELECT distinct Num_build, Korpus, RegionName FROM v_bld_rgn where id_street=".$st." ORDER BY space(5-length(Num_build))+Num_build";
	$result =  mysql_query($q_num_build) or die(mysql_error());
//	$row_num_build = mysql_fetch_assoc($num_build);
	$totalRows_num_build = mysql_num_rows($result);

$i = 0;
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgc = $cfg['BgcolorOne'];

//	$q_num_build = "SELECT * FROM spr_build where id_street=".$st.
?>	<table width="800" border="0" cellpadding="2" cellspacing="1" >
	<tr>
<?php /*?>	<td bgcolor="<?php echo $bgcolor; ?>"><input name="h_k" type="hidden" value="<?php echo $k ?>" /></td><?php */?>
	<td bgcolor="<?php echo $bgc; ?>"><strong>дом</strong></td>
	<td bgcolor="<?php echo $bgc; ?>"><strong>район</strong></td>
	<td bgcolor="<?php echo $bgc; ?>"><strong></strong></td>
	</tr>
<?
	while ($row = mysql_fetch_assoc($result))  {
		$bgc = ($i++ % 2) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];//?>
	<tr>
		<td width="80" bgcolor="<?php echo $bgc; //$row['id_korp']?>">
			<input name="k<?php echo $row['Num_build']."_".$row['Korpus'] ?>" type="checkbox" onchange="set_selrgn(this.value)"/><?php echo $row['Num_build'],empty($row['Korpus'])?"":" ".$row['Korpus']; ?> </td>
		<td width="100" bgcolor="<?php echo $bgc; ?>"><?php echo $row["RegionName"] ?></td>
        <div align="center">
          <?php /*?>		<td bgcolor="<?php echo $bgcolor; ?>"><button name="B_sv" type=button onClick="f=document.forms['ulaForm']; ch_param('do_cor_bld','id_p=<?php echo $row["id_Podjezd"]?>&P='+f.p<?php echo $row["id_Podjezd"]?>.value+'&F='+f.f<?php echo $row["id_Podjezd"]?>.value+'&L='+f.l<?php echo $row["id_Podjezd"]?>.value,'d<?php echo $row["id_Podjezd"] ?>');"><img src="upload.gif"></button>
		</td><?php */?>
        </div>
        <td bgcolor="<?php echo $bgc; ?>"> <div id="d<?php echo $row['Num_build']."_".$row['Korpus'] ?>"></div></td>
	</tr>
<?	} ?>
</table>
<table>
<tr><td>
Для </td>
     <td><div id="d_sel_bld">всех</div><!-- <label>
        <input name="bld1" type="radio" value="1" checked onchange='set_rgn(this.value);' />
        всех</label>
      <br />
      <label>
        <input name="bld2" type="radio" value="2" onchange='set_rgn(this.value);' />
        выбранных</label>
      <br />-->
 </td> <td>
   домов установить район:
<select name="rgn" class='font8pt' id="rgn" lang="ru" onchange='adj_rgn();'>
<?php	$q_rgn = "SELECT * FROM `spr_Region`";// WHERE `id_TypePers`=4
		$rgn = mysql_query($q_rgn) or die(mysql_error());
		$row_rgn = mysql_fetch_assoc($rgn);
		$totalRows_rgn = mysql_num_rows($rgn);
		echo "<option value=0>выбрать</option>";
		do { echo "<option value=".$row_rgn['id_Region'].">".$row_rgn['RegionName']."</option>"; }
			while ($row_rgn = mysql_fetch_assoc($rgn));
		$rows = mysql_num_rows($rgn);
		if($rows > 0) { mysql_data_seek($rgn, 0); $row_rgn = mysqli_fetch_assoc($rgn);  } ?>
    </select>
</td></tr>
</table>
<?
//*/}
?>	