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

	$k = $_REQUEST ["k"];
	if (isset($_REQUEST ["nx"])){$nx = $_REQUEST ["nx"];} else {$nx = "tab_Cust".$new;} //podjezd//adress

//	$q_Flat = "SELECT Min(FirstFlat) as mn, Max(LastFlat) as mx FROM v_podjezd where Num_build='$Num_build' and id_street = $st"; // id_korp spr_podjezd
	$q_Flat = "SELECT Min(FirstFlat) as mn, Max(LastFlat) as mx FROM `spr_podjezd` where id_korp=$k";

	$Flat = mysql_query($q_Flat) or die(mysql_error());
	$row_Flat = mysql_fetch_assoc($Flat);
	$totalRows_Flat = mysql_num_rows($Flat);
	$MinFlat = $row_Flat['mn'];
	$MaxFlat = $row_Flat['mx'];	
//	echo $MinFlat, " ", $MaxFlat;
//	'ch_flt("ch_flt", this, "<? echo $nx; ? >")' //$id_korp clr_adress();

//$onchange='ch("ch_flt", "st='.$st.'&Num_build='.$Num_build.'&fl", this,"'.$nx.'")';
//echo $onchange;
$onchange='clr_adress(); ch("ch_flt","k="+this.value, "em","'.$nx.'"); chk_adress();'; //this"st="'.$st.'"&fl"'"&Num_build="'.$Num_build.
//echo '<select name="flat" class="font8pt" lang="ru" onchange="'.'" >';//$onchange.
// st=$st&Num_build=$Num_build& ?>
<?php //echo ((isset($_REQUEST ["menu"]))? "menu=".$_REQUEST ["menu"]."&":""); ?>
<input id="refr" type="hidden" value='f=document.forms.ulaForm.flat; if(f.value==0){f[1].selected=true;} <? echo $new!="new"?"clr_adress();":""; ?> ch("ch_flt","<?php echo $par."k=$k" ?>",f,"<? echo $nx; ?>")' />
<select name="flat<? echo $new; ?>" class="font8pt" lang="ru" onchange='if(this.value==0){this[1].selected=true;} <? echo ($new!="new")?"clr_adress();":""; ?> ch("ch_flt", "<?php echo $par."k=$k"; ?>", this, "<? echo $nx; ?>")'>
  		<option value="0"><? echo "кв."?> </option>
<?	 $i=$MinFlat; do {  // $row_Flat['FirstFlat']
        echo '<option value="'.$i.'">'.$i.'</option>';
	} while (++$i <= $MaxFlat); // $row_Flat['LastFlat'] ?>
</select>

<?  function f_get_id_korp($Num_build, $Flat)
{
	$q_id_korp = "SELECT id_korp FROM v_podjezd".
				" where FirstFlat <= ".$Flat." and LastFlat >= ".$Flat.
				" and Num_build= '".$Num_build."'"; // id_korp spr_podjezd, Korpus

	$id_korp = mysql_query($q_id_korp) or die(mysql_error());
	$row_id_korp = mysql_fetch_assoc($id_korp);
	$totalRows_id_korp = mysql_num_rows($id_korp);
	$id_korp_ = $row_id_korp['id_korp'];
} ?>