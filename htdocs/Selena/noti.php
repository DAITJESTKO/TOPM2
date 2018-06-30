<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
?>

<table width=800 border=0>
  <tr><td colspan="10" height="20"> <h3 align="center"><?php 	$Num_Notify = get_num_noti();?>
	Заявка на ремонт в сети Селена №<u>&nbsp;<?php echo $Num_Notify; ?>&nbsp;</u>от <input name="n_date" type="date" id="n_date" value=<?php $DateNotify=date("Y-m-d"); echo $DateNotify ?> size="9" /></h3>	</td></tr>
  </tr>
</table>
<?		$GLOBALS['menu'] = 'noti'; ?>
<?  	$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;?>
<?  	$GLOBALS['tn'] = isset($_REQUEST ["tn"]) ? $_REQUEST ["tn"]: 0;?>
<? require_once("frm_adress.php"); ?>
<? // require_once("frm_phn.php"); ?>
<? // require_once("frm_net.php"); ?>

<table width="800" border=0>
  <tr>
    <td colspan="3" align="right">Неисправность</td>
    <td colspan="4"><input name="noti" type="text" size="70" onchange='chk_noti();' />
		<select name='_noti' id='_noti' onchange='document.forms["ulaForm"].noti.value=this.value;chk_noti();' class='headText' >
		  <option value="0" >-</option>
		  <option value="Нет связи" >Нет связи</option>
		  <option value="Обрыв линии" >Обрыв</option>
		  <option value="Неизвестная причина" >Неизвестная</option>
		</select>
</td>
  </tr>
</table>
<table width="750" border=0>
  <tr>
<?php /*?>	<td width="161" align="right">передать монтажнику: </td>
		<td width="62"><select name="mont" class='font8pt' id="mont" lang="ru" onchange='chk_noti();'>
	<?php	$q_mont = "SELECT * FROM `personal` WHERE `id_TypePers`=4";
			$mont = mysql_query($q_mont) or die(mysql_error());
			$row_mont = mysql_fetch_assoc($mont);
			$totalRows_mont = mysql_num_rows($mont);
		echo "<option value=0>выбрать</option>";
	do {
		echo "<option value=".$row_mont['TabNum'].">".$row_mont['Fam']." (таб.№ ".$row_mont['TabNum'].")</option>";
			} while ($row_mont = mysql_fetch_assoc($mont));
			$rows = mysql_num_rows($mont);
			if($rows > 0) { mysql_data_seek($mont, 0); $row_mont = mysqli_fetch_assoc($mont);  } ?>
		</select>    </td>  <?php */?>  
		<td width="189" align="right">Плановая дата выполнения:  </td>
		<td width="220" valign="middle"><input name="Date_Plan" type="date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("m"),date("d")/*+3*/,date("Y"))); ?>" size="1" onchange='chk_noti();' />
	<!--    , факт <input name="Date_Fact" type="date" size="8" />-->
	</td>
  </tr>
</table>
<div id="B_Sub" align="centr">  </div>
  	<input name="Menu_Item" type="hidden" value="noti" />
