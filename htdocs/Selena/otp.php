﻿<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
//if (isset($_REQUEST ["dolgn"])) { echo $_REQUEST ["dolgn"]; }

$k = isset($_REQUEST ["k"])?" and (id_korp=".$_REQUEST ["k"].")":"";
$tn = (!$_REQUEST ["tp"]==4 && isset($_REQUEST ["tn"]))?" and (m_TabNum=".$_REQUEST ["tn"].")":"";
$tp = !(isset($_REQUEST ["tp"])?($_REQUEST ["tp"]==4):1);

 $q_dolgn = "SELECT * from v_customer where ( ((`Date_pay` is not null) and (`Date_end_st` IS NOT NULL)) $tn $k and ".
				"(inet is null) and (`state` = 2) and (`id_tarifab` <> 3) )".
			"order by name_street, space(5-length(Num_build))+Num_build, flat";
//$q_dolgn = "SELECT * from v_customer where ( (`Date_pay` < date('Y-m-d')) or ( ( isnull(`Saldo`) or (`Saldo` <= 0) ) ".$tn.$k.") ) order by name_street, space(5-length(Num_build))+Num_build, flat";
$result = mysql_query($q_dolgn);		// Выполняем запрос	 `id_tarifab` in (11, 12, 13, 14)
if (!isset($result)) {
	echo "Отпускников нет";
	return;
}

// Выводим заголовки таблицы
$nbo = 1;
$i = 0;
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgcolor = $cfg['BgcolorOne'];?>
<table width="800" height="14" border="0" cellpadding="2" cellspacing="1" >
	<tr>
		<td bgcolor="<?php echo $bgcolor; ?>"> № п/п</td>
		<td bgcolor="<?php echo $bgcolor; ?>">улица<br />      дом-корп(под.)</td>
<?php /*?>		<td bgcolor="<?php echo $bgcolor; ?>"></td><?php */?>
		<td bgcolor="<?php echo $bgcolor; ?>">кв.</td>
		<td bgcolor="<?php echo $bgcolor; ?>">эт.</td>
		<td bgcolor="<?php echo $bgcolor; ?>"><? if($tp) { ?>Фамилия <? } ?>Имя Отчество</td>
		<? if($tp) { ?>
		<td bgcolor="<?php echo $bgcolor; ?>">Договор</td>
		<td bgcolor="<?php echo $bgcolor; ?>">Ник (сетев.)</td>
		<? } ?>
		<td bgcolor="<?php echo $bgcolor; ?>">телефон</td>
<? if($tp) { ?>		<td bgcolor="<?php echo $bgcolor; ?>">Оплата по</td><? } ?>
		<td bgcolor="<?php echo $bgcolor; ?>">Отпуск до</td>
	</tr>

<?php
// Печатаем данные построчно
$t1 = ""; // $row["town"];
$s1 = ""; // $row["name_street"];

$SDolg = 0;
while ($row = mysql_fetch_assoc($result))  {
/*		if (!strcmp ($t1, $row["town"])==0) { 	?> 
<tr>
  <th background="gray2_bg.gif" bgcolor="#DFDFDF">
              <span style="font-size:12px"> <? echo $row["town"]; ?></span></th>
</tr> <tr>
			<?	$t1 = $row["town"]; //town
				$s1 = ""; // $row["name_street"];
		} else {
//			echo '<td>'.' '.'</td>';
		};	*/
		if (!strcmp ($s1, $row["name_street"])==0) {  //  name_street ?>
			<td>&nbsp;</td>
			<th background="gray2_bg.gif" bgcolor="#DFDFDF">
              <span style="font-size:14px"> <? echo $row["name_street"];?></span></th>
			</tr>
			<tr>
			<? $s1 = $row["name_street"]; } else { /*			echo '<td>'.' '.'</td>'; */	 }; //  name_street
		$bgcolor = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];	?>
		
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $nbo++;				?> </td>
		<td align="right" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Num_build"]." ".
			($row["Korpus"]>0?"-".$row["Korpus"]:"   ")."({$row["Podjezd"]}п.)";?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["flat"];			?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["floor"];			?> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo ($tp?$row["Fam"].' ':'').$row["Name"].' '.$row["Father"]; ?> </td>
<? if($tp) { ?>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo '<a href="javascript:{ch_param(\'sh_form\',\'menu=recon&tn='.$tn.'&tp='.$tp.'&Bill_Dog='.$row["Bill_Dog"].'\',\'Mform\');s_Bill_Dog();}">'.$row["Bill_Dog"].'</a>';		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Nic"];		?> </td>
<? } ?>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php 
			if ($row["phone_Home"]>0) { echo "д.т. ".$row["phone_Home"];
				} else if ($row["phone_Cell"]>0) { echo $row["phone_Cell"];
				} else echo $row["phone_Work"]>0?"р.т. ".$row["phone_Work"]:"";					?> </td>
<? if($tp) { ?>		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Date_end_st"];	?> </td><? } ?>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Date_end_st"];	?> </td>
		</tr>		<?php 
//		$SDolg += $row["Saldo"];
    }
?>
    <tr>
    	<th colspan="8" background="gray2_bg.gif" bgcolor="#DFDFDF"><span style="font-size:12px">
		<?php //echo "Итого:"; ?></span></th>
		<td align="left" bgcolor="<?php echo $bgcolor; ?>"> <?php //echo $SDolg; ?> </td>
    </tr>

<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="otp" />

