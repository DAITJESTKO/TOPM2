<link href="selena.css" rel="stylesheet" type="text/css" />
<?php
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
	global $totalRows_customer;
?>
<style type="text/css">
<!--
.стиль1 {
	font-size: 14px;
	font-weight: bold;
	text-align: left;
}
-->
</style>
<table width=780 border=0>
<tr>
  <td colspan="10" height="20" align="center"><font size="+1" >Платёж абонплаты и за интернет сети Селена от &nbsp;
    <input name="n_date" type="text" id="n_date" value=<?php $DateNotify=date("Y-m-d"); echo $DateNotify ?> size="9" />
	</font></td>
</tr>
</table>
	<? if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { $rem_addr="http://selena/"; } else { $rem_addr="https://10.1.2.22/"; }?>
	<? $GLOBALS['menu'] = 'pay'; ?>
	<? require_once("frm_adress.php"); //$rem_addr."frm_adress.php?menu=recon" ?>
<table>
<tr>
	<td width=10></td>
	<td><div id="B_Create"></div></td>	<td><div id="B_Edit"></div></td>
</tr>
</table>
<table>
<tr>
	<td width=10><input name="Menu_Item" type="hidden" value="pay" /></td>
	<td><div id="B_Sub"></div></td>
</tr>
</table>
