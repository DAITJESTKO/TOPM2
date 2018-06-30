<link href="selena.css" rel="stylesheet" type="text/css" />
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
	if (isset($_REQUEST ["Cod_flat"]) && ($_REQUEST ["Cod_flat"]>0)) {
		$Cod_flat = $_REQUEST ["Cod_flat"];
		$str_srch = " Cod_flat = $Cod_flat";
	} elseif (isset($_REQUEST ["Bill_Dog"]) && ($_REQUEST ["Bill_Dog"]>0)) {
		$Bill_Dog = $_REQUEST ["Bill_Dog"];
		$str_srch = " Bill_Dog = $Bill_Dog";
	} elseif (isset($_REQUEST ["Nic"]) && ($_REQUEST ["Nic"]!="")) {
		$Nic = $_REQUEST ["Nic"];
		$str_srch = " Nic = '$Nic'";
	} else {
		return;
	}
		
/*	$q_srch = "SELECT * FROM v_customer where ".$str_srch;
	$s_srch =  mysql_query($q_srch) or die(mysql_error());
	$row_srch = mysql_fetch_assoc($s_srch);
	$totalRows_srch = mysql_num_rows($s_srch);
	if ($totalRows_srch==0) {	*/
		$s_adr = '';
		$q_srch = "SELECT * FROM v_customer where ".$str_srch;
		$s_srch =  mysql_query($q_srch) or die(mysql_error());
		if (mysql_num_rows($s_srch)==0) {
			if (isset($_REQUEST ["Cod_flat"])) {
				$q_srch = "SELECT * FROM v_cod_adr where ".$str_srch;
				$s_srch =  mysql_query($q_srch) or die(mysql_error());
				if (mysql_num_rows($s_srch)>0) {
					$row_srch = mysql_fetch_assoc($s_srch);
					$s_adr = "Адрес: ул.<b>".$row_srch["name_street"]."</b> д.<b>".$row_srch["Num_build"]."</b> кв.<b>".$row_srch["flat"]."</b></br>";
					$q_srch = "SELECT * FROM v_customer where `id_Podjezd`=".$row_srch["id_Podjezd"]." and `flat`=".$row_srch["flat"];
					$s_srch =  mysql_query($q_srch) or die(mysql_error());
					if (mysql_num_rows($s_srch)==0) {
						echo $s_adr, "<h1>Ничего не найдено</h1>";
						return;
					}
				} else { echo $s_adr, "<h1>Ничего не найдено</h1>";	return; }
			} else { echo $s_adr, "<h1>Ничего не найдено</h1>";	return; }
		}
		$row_srch = mysql_fetch_assoc($s_srch);
/*		echo "<h2>Обнаружен клиент с ошибочными данными</br>";
		echo "Номер договора: <b>".$row_srch["Bill_Dog"]."</b>, Ф.И.О.: <b>".(empty($row_srch["Fam"])?"":$row_srch["Fam"])." ".(empty($row_srch["Name"])?"":$row_srch["Name"])." ".(empty($row_srch["Father"])?"":$row_srch["Father"])."</b></br>";
		echo "Адрес: ул.<b>".$row_srch["name_street"]."</b> д.<b>".$row_srch["Num_build"]." кв.<b>".$row_srch["flat"]."</b></br>";
		echo ($row_srch["flat"]>$row_srch["LastFlat"]?"№ кв. больше максимальной по подъезду (<b>".$row_srch["LastFlat"]."</b>)":"")."</h2>";
		return;
	}	*/
	if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { $rem_addr="http://selena/"; } else { $rem_addr="https://10.1.2.22/"; }
//echo	$GLOBALS['menu'];
//echo	$GLOBALS['menu'] = $_REQUEST['menu'];
	$GLOBALS['Town'] = $row_srch["Town"];
	$GLOBALS['st'] = $row_srch["id_street"];
	$GLOBALS['k'] = $row_srch["id_korp"];
	$GLOBALS['name_street'] = $row_srch["name_street"];
	$GLOBALS['Num_build'] = $row_srch["Num_build"];
	$GLOBALS['fl'] = $row_srch["flat"];
	$GLOBALS['id_Podjezd'] = $row_srch["id_Podjezd"];
	$tp = (isset($_REQUEST['tp']))? $_REQUEST['tp']:(isset($GLOBALS['tp']))? $GLOBALS['tp']:"9";
	require_once("ch_flt.php"); //$rem_addr."frm_adress.php?menu=recon" 
//	$GLOBALS[''] = '';
  	echo '<input name="Town" value="'.$row_srch["Town"].'" type="hidden" />';//
return;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	$ = $row_srch[''];
  	echo '<td><div id="id_town">'.'<input name="Town" value="'.$row_srch["Town"].'" />'.'</div></td>';// type="hidden"
	echo '<td align="right">ул.</td><td><div id="id_street">'.'<input name="id_street" value="'.$row_srch["name_street"].'" />'.'</div></td>';
	echo '<td align="right">дом: </td><td><div align="left" id="num_build">'.'<input name="num_build" value="'.$row_srch["Num_build"].'" />'.'</div></td>';
	echo '<td align="right">кв. </td><td><div align="left" id="flat">'.'<input name="flat" value="'.$row_srch["flat"].'" />'.'</div></td>';	
//echo '<td><div id="id_town">'.$row_srch["Town"].'</div></td>';
	print_h("Town", $row_srch["Town"]); 				// = $row_srch['Town'];
	print_h("name_street", $row_srch["name_street"]); // = $row_srch['name_street'];
	print_h("id_korp", $row_srch["id_korp"]); 		// = $row_srch['id_korp'];
	print_h("Num_build", $row_srch["Num_build"]);		// = $row_srch['Num_build'];
	print_h("flat", $row_srch["flat"]);				// = $row_srch['flat'];
	print_h("id_Podjezd", $row_srch["id_Podjezd"]);	// = $row_srch['id_Podjezd'];
	print_h("Bill_Dog", $row_srch["Bill_Dog"]);		// = $row_srch['Bill_Dog'];
	print_h("Cod_flat", $row_srch["Cod_flat"]);		// = $row_srch['Cod_flat'];
	
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

function print_h($name, $val) { // id, $key
//	$inp_name = 'h_'.$id.'_'.$key; //$inp_name.' = '.
//	echo '$name ='.$val.", ";	//</br>
  	echo '<input name="'.$name.'" value="'.$val.'" type="hidden" />';//
}
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
?>