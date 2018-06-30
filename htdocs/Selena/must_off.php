<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
//if (isset($_REQUEST ["dolgn"])) { echo $_REQUEST ["dolgn"]; }

//$k = isset($_REQUEST ["k"])?" and (id_korp=".$_REQUEST ["k"].")":"";
$tn = (!$_REQUEST ["tp"]==4 && isset($_REQUEST ["tn"]))?" and (m_TabNum=".$_REQUEST ["tn"].")":"";
echo $tp = !(isset($_REQUEST ["tp"])?($_REQUEST ["tp"]==4):1);
//$q_dolgn = "SELECT * from v_customer where (`Date_pay` >= '".date('Y-m-d')."' $tn $k and `state`=1) order by name_street, space(5-length(Num_build))+Num_build+Korpus, flat";
//$q_dolgn = "SELECT * from v_customer where ( (`Date_pay` < date('Y-m-d')) or ( ( isnull(`Saldo`) or (`Saldo` <= 0) ) ".$tn.$k.") ) order by name_street, space(5-length(Num_build))+Num_build, flat";
$s_quer = "SELECT `Bill_Dog`,`Nic`,`Cod_flat`,name_street,Num_build,Korpus,Podjezd,flat,Fam,Name,Father,
`state`, `Date_start_st` , `Date_end_st`, mac, RPAD(Fam,1,'?') as F
FROM `v_customer` 
WHERE 
`state` =1
AND `ab_sum` >0
AND `Date_pay` <  curdate()
AND `auto` =1
and inet is null
and `Cod_flat` in (
    SELECT
v_n_cods.Cod_flat
FROM
v_n_cods
WHERE
v_n_cods.nm >  '1'
)
ORDER BY
v_customer.id_Podjezd ASC";
$result = mysql_query($s_quer);		// Выполняем запрос
if (!isset($result)) {
	echo "не найдены";
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
		<td bgcolor="<?php echo $bgcolor; ?>" width="40"> № п/п</td>
		<td bgcolor="<?php echo $bgcolor; ?>" width="140">улица<br />     дом (Корп.) п-зд кв.</td>
		<td bgcolor="<?php echo $bgcolor; ?>"><? if($tp) { ?>Фамилия <? } ?>Имя Отчество</td>
	<? if($tp) { ?>
		<td bgcolor="<?php echo $bgcolor; ?>">Договор</td>
		<td bgcolor="<?php echo $bgcolor; ?>">Ник (сетев.)</td>
		<td bgcolor="<?php echo $bgcolor; ?>">MAC</td>
		<? } else { ?>
		<td bgcolor="<?php echo $bgcolor; ?>">MAC</td>
<? } ?>
		<td bgcolor="<?php echo $bgcolor; ?>">R</td>
<?	  if($tp) { ?>		<td bgcolor="<?php echo $bgcolor; ?>">Оплата по</td><? } ?>
<?php /*?>		<td bgcolor="<?php echo $bgcolor; ?>">Оплаченный срок</td><?php */?>
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
		<td align="right" bgcolor="<?php echo $bgcolor; ?>"> <?php echo "д.".$row["Num_build"]."  ".($row["Korpus"]>0?"(".$row["Korpus"].")":"   ")." п.".$row["Podjezd"]." кв.".$row["flat"];?> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo ($tp?$row["Fam"].' ':'').$row["Name"].' '.$row["Father"].($tp?'':' '.$row["F"].'.'); ?> </td>
		<? if($tp) { ?>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> 
			<?php echo '<a href="javascript:{ch_param(\'sh_form\',\'menu=pay&tn='.$tn.'&tp='.$tp.'&Bill_Dog='.$row["Bill_Dog"].'\',\'Mform\');s_Bill_Dog();}">'.$row["Bill_Dog"].'</a>';		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Nic"];		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["mac"];		?> </td>
		<? } else { ?>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["mac"]==""?"не установл.":" есть";		?> </td>
<? }	?>
		<td bgcolor="<?php echo $bgcolor; ?>"><? echo isRadreply($row["mac"])?"√":"-" ?></td>
<?
		if($tp) { ?>		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Date_end_st"];	?> </td><? } ?>
<?php /*?>		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo round((strtotime("now") - strtotime($row["Date_pay"]))/60/60/24, 0), " дн.";	?> </td><?php */?>
		</tr>		<?php 
//		$SDolg += $row["Saldo"];
    }
// Освободить память (необязательное действие)
//    mysql_free_result($result);<td colspan="8" align="left" bgcolor="<?php echo $bgcolor; ? >">  </td>
?>
