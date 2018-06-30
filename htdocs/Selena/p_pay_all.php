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

$id_p		= $_REQUEST ["id_p"];
$fl			= $_REQUEST ["fl"];
$TabNum  	= $_REQUEST ["TabNum"];
$abon_Com 	= $_REQUEST ["abon_Com"];
$adr 		= $_REQUEST ["adr"];
$t_pay		= $_REQUEST ["t_pay"];
$nums		= $_REQUEST ["nums"];

//*********************		//	*********************	//	*********************
if (isset($GLOBALS['menu'])) $p1 = $GLOBALS['menu']; else $p1 = "no menu";
?>

<table border=0 class="quote" width="700">
	<tr><td align="center" class="quote"><FONT size=+2>ООО "СТС-Развитие"</FONT></td></tr>
	<tr><td align="left" class="quote"><FONT size=+1>Квитанция от <u> <? echo date("d.m.Y"); ?></u>г.</FONT></td></tr>
	<tr><td class="quote"><? echo $adr; ?></td></tr>

<? for($i=1; $i<=$nums; $i++){
	$Bill_Dog  	= $_REQUEST ["bd$i"];
	$fio 		= $_REQUEST ["fio$i"];
	$abon 		= $_REQUEST ["ab$i"];
	$action 	= $_REQUEST ["act$i"];
	$Date_start = $_REQUEST ["ds$i"];
	$Date_end 	= $_REQUEST ["de$i"];
	$nDateAct 	= $_REQUEST ["nda$i"];
	$Date_pay 	= $_REQUEST ["dp$i"];
	p_pay($Date_pay, $adr, $fio, $id_p, $fl, $TabNum, $abon_Com, $Bill_Dog, $abon, $action, $Date_start, $Date_end, $nDateAct);
}
?>
	<tr><td style="border:thin; border-top:thin #000 solid" colspan="2" class="quote">Итого <? echo $t_pay; ?>руб.</td></tr>
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

<? function p_pay($Date_pay, $adr, $fio, $id_p, $fl, $TabNum, $abon_Com, $Bill_Dog, $abon_p, $action, $Date_start, $Date_end, $nDateAct)
{
	$q_customer = "SELECT * FROM v_customer where Bill_Dog=$Bill_Dog";
	$s_customer =  mysql_query($q_customer) or die(mysql_error());
	$row_customer = mysql_fetch_array($s_customer, MYSQL_ASSOC);
	$totalRows_customer = mysql_num_rows($s_customer);
	if ($totalRows_customer==0) return 
?>
	<tr><td class="quote">Абонент: <? echo $fio; ?></td><td> </td><td class="quote">Договор №<FONT size=+1> <u>&nbsp;<? echo $Bill_Dog; ?>&nbsp;</u></FONT></td></tr>
	<tr><td class="quote">Абонплатёж <? echo $abon_p; ?>руб. оплачено по <? echo $Date_pay; ?>.</td></tr>
	<? if ($action>0) {?>
        <tr><td class="quote">В том числе <? echo $action; ?>мес. по акции</td></tr>
    <? } ?>
    <tr></tr>
<? } ?>
