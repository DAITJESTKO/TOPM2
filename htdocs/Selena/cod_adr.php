<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;

$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$nbo = 1;
$i = 0;
$bgc = $cfg['BgcolorOne'];

?>
<div id="t_fin">
	<table width="400" border="0" cellpadding="1" cellspacing="1" >
	<tr>
	  <td align="center" class="quote" bgcolor="#99CC66">Коды адресов
	  </td>
	</tr>
	</table>
<?
//}
$q_noti = "SELECT * from v_cod_adr order by `Cod_flat`"; //; //
$result = mysql_query($q_noti);		// Выполняем запрос
if (mysql_num_rows($result)>0) { 
	$bgc = $cfg['BgcolorOne'];	?>
	<table width="400" border="0" cellpadding="1" cellspacing="1" >
	<tr>
		<td align="center" bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Код </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Город </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Улица </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Дом </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Корпус </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> кв. </strong></span></td>
	</tr>
	
	<?php
	// Печатаем данные построчно
	while ($row = mysql_fetch_assoc($result))  {   	?>
		<tr>
		<?	$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];		?>
			<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["Cod_flat"];		?> </td>
			<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["Town"];			?> </td>
			<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["name_street"];	?> </td>
			<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["Num_build"];	?> </td>
			<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["Korpus"];		?> </td>
			<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["flat"];			?> </td>
		</tr>		
		<?
	}
} else { ?>Ошибка программы<? }
//*******************************************************************************************************************************
?>
	</div>
<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="pers" />
