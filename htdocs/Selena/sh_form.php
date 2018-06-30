<link href="selena.css" rel="stylesheet" type="text/css" />
<?php
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
	global $totalRows_customer;
	
	$DateNotify=date("Y-m-d");
	$a_title = Array("ab_err"=>"Требуется переоформл./переподкл",
					 "activ"=>"Действующие",
					 "dolgn"=>"Должники",
					 "otp"=>"Отпускники",
					 "noti"=>"Заявка на ремонт в сети",
					 "recon"=>"Пере/подключение",
					 "pay"=>"Абонентская и интернет оплата",
					 "mont"=>"Заявки монтажника на ремонт в сети и подключение",
					 "edt_bld"=>"Редактор справочника адресов");
?>
	
<? //	if($_REQUEST ["menu"]) {
		$GLOBALS['menu'] = $_REQUEST ["menu"];
		$Menu_Item = $GLOBALS['menu'];
	//} ?>
<?  	$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;?>
<?  	$GLOBALS['tn'] = isset($_REQUEST ["tn"]) ? $_REQUEST ["tn"]: 0;?>
<?php /*?><table width=800 border=0>
<tr>
  <td colspan="10" height="20" align="center"><font size="+1" ><?php echo $a_title[$GLOBALS['menu']];//" ",, " ", date("d-m-Y") ?>	</font></td>
</tr></table><?php */?>
	<? if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { $rem_addr="http://selena/"; } else { $rem_addr="https://10.1.2.22/"; }?>
	<? require_once("frm_adress.php"); ?>
<table>
<tr>
	<td width=10></td><td><div id="B_Create"></div></td><td><div id="B_Edit"></div></td>
</tr>
</table>
<table>
<tr>
	<td width=10><input name="Menu_Item" value="<? echo $Menu_Item; ?>" type="hidden" /></td>
	<td><div id="B_Sub"></div></td>
</tr>
</table>