<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
/*
if (isset($GLOBALS['pers'])) {
//	echo $GLOBALS['pers']['TabNum'];
}
  	$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;
	$tp = $_REQUEST ["tp"];
	$tn = $_REQUEST ["tn"];
	$per = $_REQUEST ["per"];
*/
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$nbo = 1;
$i = 0;
$bgc = $cfg['BgcolorOne'];

?>
<div id="t_fin">
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
	  <td align="center" class="quote" bgcolor="#99CC66">Перечень персонала
	  </td>
	</tr>
	</table>
<?
//}
$q_noti = "SELECT * from v_personal order by `id_TypePers`"; //; //TabNum
$result = mysql_query($q_noti);		// Выполняем запрос
if (mysql_num_rows($result)>0) { 
	$bgc = $cfg['BgcolorOne'];	?>
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
		<td align="center" bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Таб № </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Фамилия </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Имя </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> Отчество </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> персонал </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> сот.телефон </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> дом.телефон </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> логин </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"><strong> регион </strong></span></td>
		<td bgcolor="<?php echo $bgc; ?>"><span style="font-size:14px"></span></td>
	</tr>
	
	<?php
	// Печатаем данные построчно
	while ($row = mysql_fetch_assoc($result))  {   	?>
		<tr>
		<?	$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];		?>
			<td bgcolor="<?php echo $bgc; ?>"><div id="t<?php echo $row["TabNum"]; ?>"> <?php echo $row["TabNum"];		?> </div><input name="it<?php echo $row["TabNum"];		?>" type="text" value="<? echo $row["Podjezd"]; ?>" size="3" /></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="f<?php echo $row["TabNum"]; ?>"> <?php echo $row["Fam"];			?> </div></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="n<?php echo $row["TabNum"]; ?>"> <?php echo $row["Name"]; 		?> </div></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="s<?php echo $row["TabNum"]; ?>"> <?php echo $row["SecName"];		?> </div></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="p<?php echo $row["TabNum"]; ?>"> <?php echo $row["NamePers"];	?> </div></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="c<?php echo $row["TabNum"]; ?>"> <?php echo $row["phone_Cell"];	?> </div></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="h<?php echo $row["TabNum"]; ?>"> <?php echo $row["phone_Home"];	?> </div></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="l<?php echo $row["TabNum"]; ?>"> <?php echo $row["login"];		?> </div></td>
			<td bgcolor="<?php echo $bgc; ?>"><div id="r<?php echo $row["TabNum"]; ?>"> <?php echo $row["id_Region"];	?> </div></td>
            <td bgcolor="<?php echo $bgc; ?>"><div id="d<?php echo $row["TabNum"]; ?>">
            	<a href='javascript:{cor_pers(<?php echo $row["TabNum"]; ?>);}'><img src="b_edit.png" border="0" width="16" height="16" hspace="2" alt="Редактировать"></a>
 </div></td>
		</tr>		
		<?
	}
} else { ?>Ошибка программы<? }
//*******************************************************************************************************************************	</div>
?>

<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="pers" />
<? 
function edit_fld ($bgc, $id, $fld, $v) {	?>
	<td bgcolor="<? echo $bgc; ?>"><div id="<? echo $id+$fld; ?>"> <? echo $v; ?> </div><input name="i<? echo $id+$fld; ?>" value="<? echo $v ?>" size="3" type="text" /></td>
<?	}
?>
