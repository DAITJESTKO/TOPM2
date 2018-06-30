<link href="selena.css" rel="stylesheet" type="text/css" />
<?
require_once("for_form.php"); 
  $conn = db_connect();
  if (!$conn) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");

	$new = (isset($_REQUEST ["new"]))? $_REQUEST ["new"]:"";
	$new_p = (isset($_REQUEST ["new"]))? "new=new&":"";
	if (!isset($GLOBALS['menu'])) $GLOBALS['menu'] = "";
	$menu_p = (isset($GLOBALS['menu']))? "menu=".$GLOBALS['menu']."&":"";
	$trg = (isset($GLOBALS["trg"]))? "trg=".$GLOBALS["trg"]."&":"";//"trg=".$GLOBALS["trg_div"]
	$tp = (isset($GLOBALS['tp']))? "tp=".$GLOBALS['tp']."&":"";
	$tn = (isset($GLOBALS['tn']))? "tn=".$GLOBALS['tn']."&":"";
	$Nic = (isset($_REQUEST['Nic']))? "Nic=".$GLOBALS['Nic']."&":"";
	$par = $tn.$tp.$trg.$new_p.$menu_p.$Nic;
	if ($new == "new") { ?>
		<table width=800 border=0 bgcolor="#66FF66" ><!--FFFF99-->
			<tr><td><p class="quote">Выбор нового адреса<? if(isset($_REQUEST['Nic'])){echo " для ".$_REQUEST['Nic'];}?>
            </p></td></tr>
		</table>
 <?	} ?>

<div id="adress">
	<table width=800 border=0 bgcolor="#66FF66" >
	<tr height="25">
	    <td width="71" align="right"><div id="id_town<? echo $new; ?>">
        <select name='id_town<? echo $new; //?>' class='font8pt'
				onchange="<? echo ($new!="new"?'clr_adress();':'')."ch('ch_town','{$par}tw',this,'id_street{$new}');"?>">
	      <option value="0">город</option>
	  		<?php
			$tw = 1; // По умолчанию город №1 (Талнах)
			$q_Town = "SELECT * FROM spr_town";
			$Town = mysql_query($q_Town) or die(mysql_error());
			$row_Town = mysql_fetch_assoc($Town);
			$totalRows_Town = mysql_num_rows($Town);
			do {  ?>
				<option value="<?php echo $row_Town['id_Town'] ?>" <? if ($row_Town['id_Town']==1) {?>selected<? }?>> 
				<?php echo $row_Town['Town'] ?></option>
				<?php
			} while ($row_Town = mysql_fetch_assoc($Town));
 ?>
		</select>
      </div></td>
		<td width="14" align="right">ул.</td>
		<td width="145"><div width=100% id="id_street<? echo $new; ?>">
<?	if (isset($_REQUEST ["nx"]))
		{ $nx = $_REQUEST ["nx"]; } else { $nx = "num_build".$new; }
	if (isset($GLOBALS['tp']) && ($GLOBALS['tp']==4)) {
		$q_Streets = "SELECT distinct id_street, name_street FROM v_podjezd where id_town=$tw and id_Region=".getRgn($GLOBALS['tn'])." order by name_street";
	} else {
		$q_Streets = "SELECT * FROM spr_street where id_town=$tw order by name_street";
	}
	$Streets = mysql_query($q_Streets) or die(mysql_error());
	$row_Streets = mysql_fetch_assoc($Streets);
	$totalRows_Streets = mysql_num_rows($Streets);
?>
<select name="id_street<? echo $new; ?>" class='font8pt' lang="ru" onchange='ch("ch_street","<?php
		echo $par; ?>st",this,"<?php echo $nx; ?>"); <? echo ($new!="new")?"clr_adress();":""; ?>' >
  		<option value="0"><? echo "Выбор улицы"?> </option>
<?php 		do {  ?>
  		<option value="<?php echo $row_Streets['id_street'] ?>" <? //if($ind ==1) { echo "selected"; }; ?> > 
<?php  		 echo $row_Streets['name_street'] ?></option>
<?php  		 } while ($row_Streets = mysql_fetch_assoc($Streets));	?>
</select>

<?
function getRgn($TabNum) {
	$result = mysql_query("select id_Region from personal where TabNum=$TabNum") or die(mysql_error());
	$row_res = mysql_fetch_assoc($result);
	return $row_res["id_Region"];
}
?>        
	    </div></td>
    	<td width="<? echo (isset($GLOBALS['menu'])&& ($GLOBALS['menu'] == 'edt_bld'))?530:67 ?>">
			<div width=100% align="left" id="num_build<? echo $new; ?>"></div>
	  	</td>
		<?	if (!isset($GLOBALS['menu']) || (($GLOBALS['menu'] != 'edt_bld') && ($GLOBALS['menu'] != 'dolgn') && ($GLOBALS['menu'] != 'otp') && ($GLOBALS['menu'] != 'activ') && ($GLOBALS['menu'] != 'mont'))) {?>
		<td width="55"><div align="left" id="flat<? echo $new; ?>"></div></td>
		<? } ?>
	<td width="10" colspan="1"><div id="B_adress<? echo $new; ?>" align="right"></div></td>
	<? if (/*!isset($_REQUEST ["new"]) and */$GLOBALS['menu'] != 'edt_bld' && ($GLOBALS['menu'] != 'dolgn') && ($GLOBALS['menu'] != 'otp') && ($GLOBALS['menu'] != 'activ') && ($GLOBALS['menu'] != 'mont')) { ?>
  		<td width="88" align="right">ник: 
        	<? if ($GLOBALS['menu'] == 'show_err') { ?>
				<input name="hNic" value="<? echo $_REQUEST['Nic']?>" type="hidden" />
            <? } //else echo $GLOBALS['menu']; ?>
			<? //if(isset($_REQUEST['Nic'])){echo 'value="'.$_REQUEST['Nic'].'"';}?> 
	    <input id="sNic" name="sNic" type="text" size="5" onchange="f=document.forms.ulaForm;f.sBill_Dog.value = '';
			f.sCod_flat.value = '';srch()" /></td><td><div id="dNic"></div></td>
  		<td width="132" align="right">код адреса: 
	    <input id="sCod_flat" name="sCod_flat" type="text" size="3"<?php /*?> onClick="srch('Cod_flat')"<?php */?> onchange="f=document.forms.ulaForm;f.sBill_Dog.value = '';f.sNic.value = '';srch()" /></td><td><div id="dCod_flat"></div></td>
		<td width="130" align="right">договор №: 
	    <input id="sBill_Dog" name="sBill_Dog" type="text" size="4" onchange="f=document.forms.ulaForm;f.sCod_flat.value = '';
			f.sNic.value = '';srch()" <? if(isset($_REQUEST['Bill_Dog'])){echo 'value="'.$_REQUEST['Bill_Dog'].'"';}?> />
        </td><td><div id="dBill_Dog"></div></td>
<? } ?>
   	  <td width="2"><div align="left" id="podjezd"></div></td>
		<td></td>
	  </tr>
  </table>
</div>
<table width=800 border=0>
	<tr>
		<td><?	if ($GLOBALS['menu'] != 'tab_Cust') {?><div id="tab_Cust"></div><? }?></td>
		  <td> <div id="tab_Cust<? echo $new; ?>" align="centr"><!--<input name="hid_id_Podjezd" type="hidden"/>(корп._, под._эт.<input name="floor" type="text" id="floor" size="1" />, район _)--></div> </td>
		  <td> <div id="d_Bill_Dog<? echo $new; ?>" valign="bottom" align="left"></div> </td>
  </tr>
</table>
<?	if ($new == "new") { ?></tr></table><? } ?>
