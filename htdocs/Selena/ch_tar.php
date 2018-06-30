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

	$conn = $_REQUEST ["con_typ"];
	$tp = $_REQUEST ["tp"];
	$id_tarifab = (isset($_REQUEST ["id_tarifab"]))? $_REQUEST ["id_tarifab"]:0;
	if (isset($_REQUEST ["nx"]))
		{ $nx = $_REQUEST ["nx"]; } else { $nx = "num_build".$new; }

	$s_qer = "SELECT * FROM `spr_tarifab` WHERE `con_typ`=".$conn.(($conn==4)?" or (`con_typ`=1 and `con_sum`<500)":"")." order by `id_tarifab`";
	$rslt = mysql_query($s_qer) or die(mysql_error());
	$row_rslt = mysql_fetch_assoc($rslt);
	$totalRows_rslt = mysql_num_rows($rslt);
?>
тариф <select name="id_tarifab" class='headText' id="id_tarifab" lang="ru" onchange='adj_con_tar(this);'> <?
		if ($row_rslt['id_tarifab']!=0) {echo "<option value=0>выбор</option>";}
		do {  
//			echo "<option value=".$row_rslt['id_tarifab']." ".(($row_rslt['id_tarifab']==$id_tarifab)?"selected":"").">".$row_rslt['name_ab']."</option>";
			echo "<option value=".$row_rslt['id_tarifab']." ".(($totalRows_rslt ==1)||($row_rslt['id_tarifab']==$id_tarifab)?"selected":"").($tp>strval($row_rslt['perstypes'])?"disabled='disabled' ":"")." >".$row_rslt['name_ab']."</option>";
			$conn_tarifs[] = $row_rslt;
		} while ($row_rslt = mysql_fetch_assoc($rslt));
		$rows = mysql_num_rows($rslt);
?>	</select>	<?
///	}
	echo '<input name="h_ts" type="hidden" value="'.$rows.'" />';
	echo '<input id="h_opl_0" type="hidden" value="0" />';//
	echo '<input id="h_con_0" type="hidden" value="0" />';//
	echo '<input id="h_ab_0" type="hidden" value="0" />';//
	foreach ($conn_tarifs as $t_row) {
		echo '<input id="h_opl_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['opl_period'].'" />';//
		echo '<input id="h_con_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['con_sum'].'" />';//
		echo '<input id="h_ab_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['ab_sum'].'" />';//
	}
//	echo '<input name="h_" type="hidden" value="'.$.'" />';

?>