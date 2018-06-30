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
	$trg = (isset($GLOBALS["trg"]))? "trg=".$GLOBALS["trg_div"]."&":"";
	$tp = (isset($GLOBALS['tp']))? "tp=".$GLOBALS['tp']."&":"";
	$tn = (isset($GLOBALS['tn']))? "tn=".$GLOBALS['tn']."&":"";
	$Nic = (isset($_REQUEST['Nic']))? "Nic=".$GLOBALS['Nic']."&":"";
	$par = $tn.$tp.$trg.$new_p.$menu_p.$Nic;

	$nx_p = "ch_build";
    $st = $_REQUEST ["st"];
	$a_nx_p = array('edt_bld'=>'edt_bld', 'dolgn'=>'dolgn', 'activ'=>'activ');
	if (isset($GLOBALS['menu']) and ($GLOBALS['menu']=='edt_bld' or $GLOBALS['menu']=='dolgn' or $GLOBALS['menu']=='activ')) {
		$nx = 'tab_Cust';	$nx_p = $a_nx_p[$GLOBALS['menu']]; //"edt_bld";
	} else {
		$edt_bld = 0;
		if (isset($_REQUEST ["nx"])){ $nx = $_REQUEST ["nx"];} else { $nx = "flat".$new; }
	}
	$q_num_build = "SELECT * FROM spr_build where id_street=".$st." ORDER BY space(5-length(Num_build))+Num_build+Korpus"; // + Korpus	distinct Num_build,Korpus
	$num_build =  mysql_query($q_num_build) or die(mysql_error());
	$row_num_build = mysql_fetch_assoc($num_build);
	$totalRows_num_build = mysql_num_rows($num_build);
?>
<!--	<table <? //echo 'width="',(isset($GLOBALS['menu'])&& ($GLOBALS['menu'] == 'edt_bld'))?490:103,'"' ?> border="0"  >
	<tr>
		<td>--><select name="num_build<? echo $new; ?>" class='font8pt' onchange='if (this.value==0){this[1].selected=true;}ch("<? echo $nx_p ?>","<?php echo $par; ?>k",this,"<? echo $nx; ?>"); <? echo ($new!="new")?"clr_adress();":""; ?> <? if($GLOBALS['menu']=='edt_bld') { ?> btn_addPod(); <? } ?>'> <? /* st="+"<? echo $st ?>"+"&Num_build", this */?>
  		<option value="0"><? echo "дом"?> </option>
<? do {  ?>
		<option value="<?php echo $row_num_build['id_korp']?>">
		  <?php echo $row_num_build['Num_build'].(empty($row_num_build['Korpus']) ? "" : " ".$row_num_build['Korpus'].""); ?></option>
<? } while ($row_num_build = mysql_fetch_assoc($num_build)); ?>
		</select>
<? if (isset($GLOBALS['menu'])&& ($GLOBALS['menu'] == 'edt_bld')) {?>
			<input name="B_regions" type="button" onclick="ch_param('edt_rgn', 'st='+document.forms['ulaForm'].id_street.value, 'tab_Cust');" value="районы" />
<!--		</td>
		<td width="500" bgcolor="#E5E5E5">-->
		<div style="background-color:#33CC66; position:absolute; left: 435px; top: 60px;">
			&nbsp;Новые: Дом
			<input name="new_bld" type="text" size="3" /> Корп.<input name="new_krp" type="text" size="3" />
			Район<select name="new_rgn" type="text" />
	<?  	$qry =  mysql_query("select * from `spr_region`") or die(mysql_error());
			$result = mysql_fetch_assoc($qry);
	do { ?> <option value="<?php echo $result['id_Region']?>"><? echo $result['RegionName']; ?></option>
	<?  } while ($result = mysql_fetch_assoc($qry)); ?>
			</select>
			<input name="B_new_bld" type="button"	value="Внести"
            	onclick="f=document.forms.ulaForm; ch_param('do_ins_bld','st='+f.id_street.value+'&nb='+f.new_bld.value+'&k='+f.new_krp.value+'&r='+f.new_rgn.value,'podjezd');                 	setTimeout('f.id_street.onchange();', 1000);"
			 /><?php /*?>setTimeout('ch(\'ch_street\',\'<?php
		echo $par; ?>st\',this,\'num_build\'); ', 300);<?php */?>
		</div>
<? } ?>
<!--		</td>
	<tr>
</table>-->