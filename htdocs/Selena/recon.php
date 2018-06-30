<link href="selena.css" rel="stylesheet" type="text/css" />
<?php
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
	global $totalRows_customer;
$Menu_Item = 'recon';
$DateNotify=date("Y-m-d");
?>
<?php /*?><table width=800 border=0>
<tr>
  <td colspan="10" height="20" align="center"><font size="+1" >Заявка №<u>&nbsp;
        <?php echo $Num_Notify = get_num_noti(); ?>
    &nbsp;</u> на пере\подключение к сети Селена от &nbsp;
    <input name="n_date" type="text" id="n_date" value=<?php echo $DateNotify ?> size="9" />
	</font></td>
</tr>
</table><?php */?>
	<? if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { $rem_addr="http://selena/"; } else { $rem_addr="https://10.1.2.22/"; }?>
	<? $GLOBALS['menu'] = $Menu_Item; ?>
	<? $GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0; ?>
	<? require_once("frm_adress.php"); //$rem_addr."frm_adress.php?menu=recon" ?>

<table width=800>
  <tr>
  	<td><div id="B_Create"><input name="DublNic" type="hidden" value="0" /></div></td>
    <td><div id="B_Edit"></div></td>
  </tr>
</table>
<input name="Menu_Item" type="hidden" value="<? echo $Menu_Item; ?>" />
	<div id="B_Sub"></div>