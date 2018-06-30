<link href="selena.css" rel="stylesheet" type="text/css" />
<?php
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
	global $totalRows_customer;
$Menu_Item = 'dolg';
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
<table width=800 border=0>
<tr>
  <td colspan="10" height="20" align="center"><font size="+1" >Должники сети на &nbsp;<?php echo date("d-m-Y"); ?>	</font></td>
</tr>
</table>
	<? if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { $rem_addr="http://selena/"; } else { $rem_addr="https://10.1.2.22/"; }?>
	<? $GLOBALS['menu'] = $Menu_Item; ?>
	<? require_once("frm_adress.php"); ?>
<table>
<tr>
	<td width=10></td>
	<td><div id="B_Create"></div></td>	<td><div id="B_Edit"></div></td>
</tr>
</table>
<table>
<tr>
	<td width=10><input name="Menu_Item" type="hidden" value="<? echo $Menu_Item; ?>" /></td>
	<td><div id="B_Sub"></div></td>
</tr>
</table>
