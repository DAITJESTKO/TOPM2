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
	if ($conn == 7) { // Смена тарифа
		$s_qer = "SELECT * FROM `spr_tarifab` WHERE `perstypes`>=".$tp;
		$rslt = mysql_query($s_qer) or die(mysql_error());
		$row_rslt = mysql_fetch_assoc($rslt);
		$totalRows_rslt = mysql_num_rows($rslt);
//тариф ?>
		<select name="id_tar_con" class='headText' id="id_tar_con" lang="ru" onchange='adj_con_tar(this);'> <?
//			if ($row_rslt['id_tarifab']!=0) {echo "<option value=0>выбор</option>";}
			do {  
				echo "<option value=".$row_rslt['id_tarifab']." "/*.(($row_rslt['id_tarifab']==$id_tarifab)?"selected":"")*/.">".$row_rslt['name_ab']."</option>";
//				echo "<option value=".$row_rslt['id_tar_con']." ".(($totalRows_rslt ==1)/*||($row_rslt['id_tar_con']==$id_tarifab)*/?"selected":"").
	//				($tp>strval($row_rslt['perstypes'])?"disabled='disabled' ":"")." >".$row_rslt['name_cn']."</option>";
				$tarifs[] = $row_rslt;
			} while ($row_rslt = mysql_fetch_assoc($rslt));
			$rows = mysql_num_rows($rslt);
	?>	</select>	<?
	foreach ($tarifs as $t_row) {
/*		$s_qer = "SELECT * FROM `spr_tarifab` where id_tarifab='".$t_row['id_tarifab']."'";
		$rslt = mysql_query($s_qer) or die(mysql_error());
		$row_rslt = mysql_fetch_assoc($rslt);	*/
		h_input('h_nm_'.$t_row['id_tarifab'], $t_row['name_ab']);
		h_input('h_op_'.$t_row['id_tarifab'], $t_row['opl_period']);
		h_input('h_cn_'.$t_row['id_tarifab'], 0);//$t_row['con_sum']
		h_input('h_ab_'.$t_row['id_tarifab'], $t_row['ab_sum']);
		h_input('h_id_'.$t_row['id_tarifab'], $t_row['id_tarifab']);
		h_input('h_kt_'.$t_row['id_tarifab'], $t_row['k_tar']);
	}
	} else {
	$s_qer = "SELECT * FROM `v_tarifab` WHERE `con_typ`=".$conn.(($conn==4)?" or (`con_typ`=1 and `con_sum`<500)":"")." order by `id_tar_con`";//	$s_qer = "SELECT * FROM `spr_tar_con`
	$rslt = mysql_query($s_qer) or die(mysql_error());
	$row_rslt = mysql_fetch_assoc($rslt);
	$totalRows_rslt = mysql_num_rows($rslt);
//тариф ?>
	<select name="id_tar_con" class='headText' id="id_tar_con" lang="ru" onchange='adj_con_tar(this);'> <?
		if ($row_rslt['id_tar_con']!=0) {echo "<option value=0>выбор</option>";}
		do {  
//			echo "<option value=".$row_rslt['id_tarifcn']." ".(($row_rslt['id_tarifcn']==$id_tarifcn)?"selected":"").">".$row_rslt['name_ab']."</option>";
			echo "<option value=".$row_rslt['id_tar_con']." ".(($totalRows_rslt ==1)/*||($row_rslt['id_tar_con']==$id_tarifab)*/?/*"selected*/"":"").
				($tp>strval($row_rslt['perstypes'])?"disabled='disabled' ":"")." >".$row_rslt['name_cn']."</option>";
			$tarifs[] = $row_rslt;
		} while ($row_rslt = mysql_fetch_assoc($rslt));
		$rows = mysql_num_rows($rslt);
?>	</select>	<?
///	}
//			$rows = mysql_num_rows($rslt);
//			$nm_tarif = "";
	h_input('h_ts', $rows);
	h_input('h_op_0', 0);
	h_input('h_cn_0', 0);
	h_input('h_ab_0', 0);
	h_input('h_id_0', 0);
	h_input('h_kt_0', 0);
	foreach ($tarifs as $t_row) {
/*		$s_qer = "SELECT * FROM `spr_tarifab` where id_tarifab='".$t_row['id_tarifab']."'";
		$rslt = mysql_query($s_qer) or die(mysql_error());
		$row_rslt = mysql_fetch_assoc($rslt);	*/
		h_input('h_nm_'.$t_row['id_tar_con'], $t_row['name_ab']);
		h_input('h_op_'.$t_row['id_tar_con'], $t_row['opl_period']);
		h_input('h_cn_'.$t_row['id_tar_con'], $t_row['con_sum']);
		h_input('h_ab_'.$t_row['id_tar_con'], $t_row['ab_sum']);
		h_input('h_id_'.$t_row['id_tar_con'], $t_row['id_tarifab']);
		h_input('h_kt_'.$t_row['id_tar_con'], $t_row['k_tar']);
	}
//	echo '<input name="h_" type="hidden" value="'.$.'" />';
	}
function h_input($n, $v) {
//		echo $n.':'.$v.'.';
		echo '<input id="'.$n.'" value="'.$v.'" type="hidden"/>';//
}
?>