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
if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { $rem_addr="http://selena/"; } else { $rem_addr="https://10.1.2.22/"; }

	global $totalRows_customer;
	if (isset($_REQUEST ["Bill_Dog"])) {
		$st = $_REQUEST ["st"];
		$name_street = $_REQUEST ["name_street"];
		$Num_build = $_REQUEST ["Num_build"];
		$fl = $_REQUEST ["fl"];
		$Bill_Dog = $_REQUEST ["Bill_Dog"];
		$Login = $_REQUEST ["Login"];
		$tarif3w = $_REQUEST ["tarif3w"];
		$Tday = $_REQUEST ["Tday"];
//		if ($fl==0) { 
//			$totalRows_customer=0;
//			$id_Podjezd = 0;
		}
/*	} else {
		$st = $GLOBALS['st'];
		$Num_build = $GLOBALS ["Num_build"];
		$fl = $GLOBALS ["fl"];
		$Town = $GLOBALS ["Town"];
		$name_street = $GLOBALS ["name_street"];
//		echo '<FONT size=+1 '.'COLOR="#FF0000">'."$Town ул.$name_street д.$Num_build кв.$fl</FONT>";
	}	*/
if (isset($GLOBALS['menu'])) $p1 = $GLOBALS['menu']; else $p1 = "no menu";
//if ($p1=='recon') {
	$q_podjezd = "SELECT * FROM v_podjezd where Num_build = '$Num_build' and id_street = $st".
		" and FirstFlat <=".$fl." and LastFlat >=".$fl;

	$s_podjezd =  mysql_query($q_podjezd) or die(mysql_error());
	$row_podjezd = mysql_fetch_assoc($s_podjezd);
	$totalRows_podjezd = mysql_num_rows($s_podjezd);
	$RegionName = $row_podjezd['RegionName'];
	$id_Podjezd = $row_podjezd['id_Podjezd'];
	$Podjezd = $row_podjezd['Podjezd'];
	$Korpus = $row_podjezd['Korpus'];
	$id_p = $id_Podjezd;

/* $Bill_Dog = 0;
 if (isset($Podjezd)) { */
	$q_customer = "SELECT * FROM v_customer where Bill_Dog=$Bill_Dog";	//id_Podjezd=".$id_Podjezd." and flat =".$fl; 
//	$q_customer = "SELECT * FROM v_customer where Num_build=$Num_build and id_street=$st and flat=$fl"; 

	$s_customer =  mysql_query($q_customer) or die(mysql_error());
	$row_customer = mysql_fetch_array($s_customer, MYSQL_ASSOC);
	$totalRows_customer = mysql_num_rows($s_customer);
//	$Cod_flat = (($row_customer['Cod_flat'] == 0) ? new_Cod_flat() : $row_customer['Cod_flat']);
//	$RegionName = $row_customer['RegionName'];
//	$id_Podjezd = $row_customer['id_Podjezd'];
//	$Podjezd = $row_customer['Podjezd'];
//	$Korpus = $row_customer['Korpus'];
//	$id_p = $id_Podjezd;
//  	echo '<input name="h_" type="hidden" value="'.$.'" />';
  	echo '<input name="h_st" type="hidden" value="'.$st.'" />';
  	echo '<input name="h_nb" type="hidden" value="'.$Num_build.'" />';
  	echo '<input name="h_fl" type="hidden" value="'.$fl.'" />';
  	echo '<input name="h_id_Podjezd" type="hidden" value="'.$id_Podjezd.'" />';
	echo '<input name="h_Rows" type="hidden" value="'.$totalRows_customer.'" />';
//	echo (($Korpus>0)?'корп.'.$Korpus:'').'</b> пд.<u><b>&nbsp;'.$Podjezd.'&nbsp;</b></u>';
//	echo 'эт.<input name="floor" type="text" id="floor" value="'.$row_customer['floor'].'" size="1" />';
//	echo ', р-он "<u>&nbsp;'.$RegionName.'&nbsp;</u>"&nbsp;';
	//<tr><td></td></tr>
//	echo '<b>Код адреса: <FONT size=+1 '.(($row_customer['Cod_flat']==0)? 'COLOR="#FF0000">'.
//		$Cod_flat.' новый':'>'.$Cod_flat).'.</FONT> Всего договоров по адресу - '.$totalRows_customer.'</b>';
	echo '<input name="h_new_Cod" type="hidden" value="'.(($totalRows_customer == 0) ? 1 : 0).'" />';
	echo '<input name="h_Cod_flat" type="hidden" value='.$Cod_flat.' />';
	echo '<input name="h_Conn" type="hidden" value="'.(($totalRows_customer == 0) ? 0 : 1).'" />';
	//	echo '<b><font class="quote"> </font>ул.'.$row_customer['name_street'].' д.'.$row_customer['Num_build'];	$row_customer[''].row_customer[''].
	if ($totalRows_customer==0) return
?>
<div class="quote">

<table border=0 class="quote">
<tr><td colspan="4" align="center" class="quote"><FONT size=+2>ДОГОВОР № <u>&nbsp;<? echo $row_customer['Bill_Dog']; ?>&nbsp;</u></FONT></td></tr>
<tr><td height="57" valign="top" align="center" class="quote" colspan="4">на предоставление услуг передачи данных и телематических служб сети связи.</td></tr>
<tr><td colspan="4" align="center" class="quote">Код адреса <u>&nbsp;<? echo $row_customer['Cod_flat']; ?>&nbsp;</u></td>
</tr>
<tr><td align="left" class="quote" colspan="2">г. Талнах</td><td align="right" class="quote" colspan="2"><? echo $Tday; ?></td>
</tr>
<tr><td colspan="1" align="left" class="quote">Гражданин</td>
	<td colspan="3" class="quote"><? echo $row_customer['Fam']." ".$row_customer['Name']." ".$row_customer['Father']; ?><hr /></td></tr>
<tr><td align="left" class="quote" colspan="1">Проживающий</td>
	<td colspan="3" class="quote"><? echo "г.Талнах ул.$name_street д.$Num_build кв.$fl"; ?><hr /></td></tr>
<tr><td align="left" class="quote" colspan="1">Зарегистрирован</td>
	<td colspan="3" class="quote"><? echo $row_customer['pasp_Adr'],"&#009"; ?><hr /></td></tr>
<tr><td align="left" class="quote" colspan="4">Документ, удостоверяющий личность <u> паспорт </u> серия <u>  <? echo $row_customer['pasp_Ser']; ?>  </u> № <u>  <? echo $row_customer['pasp_Num']; ?>  </u></td></tr>
<tr><td align="left" class="quote">Выдан</td>
	<td align="left" class="quote" colspan="3"><? echo $row_customer['pasp_Date'], " ", $row_customer['pasp_Uvd']; ?><hr /></td></tr>
<tr><td align="left" class="quote" colspan="1">телефоны:</td>
	<td align="left" class="quote"> дом.:<? echo $row_customer['phone_Home']; ?>,<hr /></td>
	<td align="left" class="quote">сот.:<? echo $row_customer['phone_Cell']; ?>,<hr /></td>
	<td align="left" class="quote">раб.:<? echo $row_customer['phone_Work']; ?><hr /></td></tr>
</table>
<table width="780" border=0 class="quote">
<tr><td align="left" class="quote">Ник сети <u>  <? echo $row_customer['Nic']; ?>  </u></td><td class="quote">Тариф<u>  <? echo $row_customer['name_ab']; ?>  </u></td></tr>
<tr><td align="left" class="quote">Логин в интернете <u>  <? echo $Login; ?>  </u></td><td class="quote">Тариф<u>  <? echo $tarif3w; ?>  &#009;</u></td></tr>
</table>

<!--<input type="button" name="Submit_prn" id="Submit_ins" value="Распечатать" onClick="window.print();" />document.getElementById('Submit_prn').style.display = 'none'; -->
<!--           <u>    </u><br>-->
</div>
<script>window.print();</script>