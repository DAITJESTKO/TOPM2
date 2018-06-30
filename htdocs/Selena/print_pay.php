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
		$Bill_Dog 	= $_REQUEST ["Bill_Dog"];
		$conn_pay 	= $_REQUEST ["conn_pay"];
		$abon_p 	= $_REQUEST ["abon_p"];
		$inet_pay 	= $_REQUEST ["inet_pay"];
		$total_pay	= $_REQUEST ["total_pay"];
		$TabNum 	= $_REQUEST ["TabNum"];
		if (isset($_REQUEST ["adr"])) {
			$adr 		= $_REQUEST ["adr"];
			$fio 		= $_REQUEST ["fio"];
			$Date_pay 	= $_REQUEST ["Date_pay"];
			$Tday 		= date("d.m.Y");
			$Login 		= $_REQUEST ["Login"];
			$action 	= isset($_REQUEST ["action"])?$_REQUEST ["action"]:0;
		} else {
			$adr 		= $_REQUEST ["adr"];
			$fio 		= $_REQUEST ["fio"];
			$Bill_Dog 	= $_REQUEST ["Bill_Dog"];
			$Date_pay 	= $_REQUEST ["Date_pay"];
			$sum_con 	= $_REQUEST ["sum_con"];
			$sum_ab 	= $_REQUEST ["sum_ab"];
			$sum_3w 	= $_REQUEST ["sum_3w"];
			$Summa 		= $_REQUEST ["Summa"];
			$Tday 		= date("d.m.Y");
			$Login 		= $_REQUEST ["Login"];
		}
/*		$st = $_REQUEST ["st"];
		$name_street = $_REQUEST ["name_street"];
		$Num_build = $_REQUEST ["Num_build"];
		$fl = $_REQUEST ["fl"];
		$tarif3w = $_REQUEST ["tarif3w"];	*/
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
/*	$q_podjezd = "SELECT * FROM v_podjezd where Num_build = '$Num_build' and id_street = $st".
		" and FirstFlat <=".$fl." and LastFlat >=".$fl;

	$s_podjezd =  mysql_query($q_podjezd) or die(mysql_error());
	$row_podjezd = mysql_fetch_assoc($s_podjezd);
	$totalRows_podjezd = mysql_num_rows($s_podjezd);
	$RegionName = $row_podjezd['RegionName'];
	$id_Podjezd = $row_podjezd['id_Podjezd'];
	$Podjezd = $row_podjezd['Podjezd'];
	$Korpus = $row_podjezd['Korpus'];
	$id_p = $id_Podjezd;
*/
/* $Bill_Dog = 0;
 if (isset($Podjezd)) { */
	$q_customer = "SELECT * FROM v_customer where ".($Bill_Dog>0?"Bill_Dog=$Bill_Dog":"Nic='".$_REQUEST ["Nic"]."'");	//id_Podjezd=".$id_Podjezd." and flat =".$fl; 
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
/*  	echo '<input name="h_st" type="hidden" value="'.$st.'" />';
  	echo '<input name="h_nb" type="hidden" value="'.$Num_build.'" />';
  	echo '<input name="h_fl" type="hidden" value="'.$fl.'" />';
  	echo '<input name="h_id_Podjezd" type="hidden" value="'.$id_Podjezd.'" />';
	echo '<input name="h_Rows" type="hidden" value="'.$totalRows_customer.'" />';	*/
//	echo (($Korpus>0)?'корп.'.$Korpus:'').'</b> пд.<u><b>&nbsp;'.$Podjezd.'&nbsp;</b></u>';
//	echo 'эт.<input name="floor" type="text" id="floor" value="'.$row_customer['floor'].'" size="1" />';
//	echo ', р-он "<u>&nbsp;'.$RegionName.'&nbsp;</u>"&nbsp;';
	//<tr><td></td></tr>
//	echo '<b>Код адреса: <FONT size=+1 '.(($row_customer['Cod_flat']==0)? 'COLOR="#FF0000">'.
//		$Cod_flat.' новый':'>'.$Cod_flat).'.</FONT> Всего договоров по адресу - '.$totalRows_customer.'</b>';
/*	echo '<input name="h_new_Cod" type="hidden" value="'.(($totalRows_customer == 0) ? 1 : 0).'" />';
	echo '<input name="h_Cod_flat" type="hidden" value='.$Cod_flat.' />';
	echo '<input name="h_Conn" type="hidden" value="'.(($totalRows_customer == 0) ? 0 : 1).'" />';
	//	echo '<b><font class="quote"> </font>ул.'.$row_customer['name_street'].' д.'.$row_customer['Num_build'];	$row_customer[''].row_customer[''].	*/
	if ($totalRows_customer==0) return
?>

<table border=0 class="quote" width="700">
	<tr>
	  <td align="center" class="quote"><FONT size=+2>ООО "СТС-Развитие"</FONT></td>
	</tr>
	<tr>
	  <td align="left" class="quote"><FONT size=+1>Квитанция от <u> <? echo $Tday; ?></u>г.</FONT></td>
	</tr>
	<tr>
	  <td colspan="2" class="quote"><? echo $adr;//"$name_street д.$Num_build $Korpus кв.$fl" ?></td>
	</tr>
	<tr>
	  <td colspan="2" class="quote">Абонент: <? echo $fio;//row_customer['Fam']," ",$row_customer['Name']," ",$row_customer['Father']; ?></td>
	<tr>
	<tr>
	  <td colspan="2" class="quote">Договор №<FONT size=+1> <u>&nbsp;<? echo $Bill_Dog>0?$Bill_Dog:$row_customer["Bill_Dog"];//row_customer['Bill_Dog']; ?>&nbsp;</u></FONT></td>
	</tr>
<? if ($conn_pay>0) {?>
	<tr>
	  <td colspan="2" class="quote">Сумма за подключение <? echo $conn_pay; ?>руб.</td>
	<tr>
<? } ?>
	<tr>
	  <td colspan="2" class="quote">Абонплата <? echo $abon_p; ?>руб. оплачено по <? echo $Date_pay;//row_customer['Date_pay']; ?>.</td>
	<tr>
<? if ($action>0) {?>
	<tr>
	  <td colspan="2" class="quote">В том числе <? echo $action; ?>мес. по акции</td>
	<tr>
<? } ?>
<? if ($inet_pay>0) {?>
	<tr>
	  <td colspan="2" class="quote">Сумма на интернет-счёт (логин "<? echo $Login; ?>") <? echo $inet_pay; ?>руб.</td>
	<tr>
<? } ?>
	<tr>
	  <td colspan="2" class="quote">Итого <? echo $total_pay; ?>руб.</td>
	<tr>
</table>
<table width="700" border="0">
  <tr>
    <td width="150" align="center" rowspan="2" scope="col">М.П.</td>
    <td width="223" scope="col">&nbsp;<br />&nbsp;<br />&nbsp;<hr /></td>
    <td width="413" rowspan="2" class="quote" scope="col">&nbsp;- диспетчер/кассир № <? echo $TabNum; ?></td>
  </tr>
  <tr>
    <td align="center" valign="top">Подпись</td>
  </tr>
</table>
