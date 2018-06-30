<?php

/**
 * @author Bessonov
 * @copyright 2007
 */
require_once("for_form.php"); 
do_html_header("Селена база оборудования");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;


$q_equipment = "SELECT * from v_equipment"; 
$result = mysql_query($q_equipment);		// Выполняем запрос
//$row_Recordset1 = mysqli_fetch_assoc($result);
//$totalRows_Recordset1 = mysql_num_rows($result);

// Выводим заголовки таблицы
$nbo = 1;
$i = 0;
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgcolor = $cfg['BgcolorOne'];
?>

<script src="libraries/functions.js" type="text/javascript" language="JavaScript"></script>
    
<table border="0" cellpadding="2" cellspacing="1" >
<tr>
<td bgcolor="<?php echo $bgcolor; ?>"> № п/п<br />
  город</td>
<td bgcolor="<?php echo $bgcolor; ?>">улица<br />        
  дом</td>
<td bgcolor="<?php echo $bgcolor; ?>">корпус</td>
<td bgcolor="<?php echo $bgcolor; ?>">подъезд</td>
<td bgcolor="<?php echo $bgcolor; ?>">этаж</td>
<td bgcolor="<?php echo $bgcolor; ?>">тип об.</td>
<td bgcolor="<?php echo $bgcolor; ?>">Серийный №</td>
</tr>

<?php
// Печатаем данные построчно
$t1 = ""; // $row["town"];
$s1 = ""; // $row["name_street"];
$GLOBALS['cfg']['BrowsePointerEnable'] = TRUE;
$GLOBALS['cfg']['BrowseMarkerEnable'] = TRUE;

    while ($row = mysql_fetch_assoc($result))  {   
		if (!strcmp ($t1, $row["Town"])==0) { 	?> 
<tr>
  <th background="gray2_bg.gif" bgcolor="#DFDFDF">
              <span style="font-size:15px"> <? echo $row["Town"]; ?></span></th>
</tr> <tr>
			<? $t1 = $row["Town"]; //town
		} else {
//			echo '<td>'.' '.'</td>';
		};
		if (!strcmp ($s1, $row["name_street"])==0) {  //  name_street
			echo '<td>'.' '.'</td>';
			?> <th background="gray2_bg.gif" bgcolor="#DFDFDF">
              <span style="font-size:15px"> <? echo $row["name_street"];?></span></th>
			</tr>
			<tr>
			<? $s1 = $row["name_street"]; } else { /*			echo '<td>'.' '.'</td>'; */	 }; //  name_street
		
		$bgcolor = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
		?>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $nbo++;				?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Num_build"];		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Korpus"];		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Podjezd"];		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Place"];			?> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <a href="sql.php?<?php echo $row["typeName"].'?'.$row["Serial_Num"];?>" title="подробнее ..." target="цель"> <?php echo $row["typeName"];?> </a></td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Serial_Num"];					?> </td>
		</tr>		<?php 
    }

// Освободить память (необязательное действие)
//    mysql_free_result($result);

?>
<table width="720" border="0" cellpadding="3" cellspacing="3" bgcolor="#FFFFFF" class="style_2">
	<hr />
          <tr>
            	<th width="120" background="gray_bg.gif" bgcolor="#DFDFDF">
              <span style="font-size:10px">Просмотр таблиц:</span></th>
				<td background="blue_bg.gif" bgcolor="#C0E1EF"><center>
              <a href="customers.php">Пользователи</a> 
            </center></td>
				<td background="blue_bg.gif" bgcolor="#C0E1EF"><center>
              <a href="equipment.php">Оборудование</a> 
            </center></td>
          </tr>
</table>
<hr />