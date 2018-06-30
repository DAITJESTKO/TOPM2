<link href="selena.css" rel="stylesheet" type="text/css" />
<?
require_once("for_form.php"); 
check_valid_user();
  $conn = db_connect();
  if (!$conn) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
//$result = mysql_query("select * from customers where (Bill_Dog not in (select Bill_Dog from v_customer)) or (Bill_Dog in (select Bill_Dog from v_err))");		// Выполняем запрос

// Выводим заголовки таблицы

err_nics();
echo "</br>";
if (!isset($_REQUEST ["N"])) err_cust();
/////////////////////////////////
function err_nics(){
$result = mysql_query("select left(Nic, 1) as N, sum(1) as n from v_empt_nic GROUP BY left(Nic, 1) order by left(Nic, 1)")
				or die(mysql_error());?>
<table border="0" cellpadding="2" cellspacing="1" >
    <tr>
<? if (!isset($_REQUEST ["N"])) { ?>
		<td align="center"><h3>
			<? if (!isset($result)) { ?>
				Неучтённых сетевых ников нет</h3></td></tr> <? return;
			} ?>
			Неучтённые (без адреса) сетевые ники (по ним есть платежи)</h3>
		</td>
    </tr>
    <tr>
		<td bgcolor="#E5E5E5" align="center"><b>
		<? 	while ($row = mysql_fetch_assoc($result))  {	?>
                <a style="color:#00C" href='javascript:{ch_param("show_err","N=<? echo $row["N"]?>","fNics");}'>&nbsp;
					<? echo $row["N"]?>&nbsp;</a>
                <? echo "</b>(".$row["n"].")<b>"?>&nbsp;
        <?    }
?>		</b></td>
	</tr>
	<tr><td colspan="3"><div id="sNics">Набирайте Ник здесь: 
    	<input name="sNics" onkeyup='ch_param("show_err","N="+this.value,"fNics");' /></div>
    	<div id="fNics"></div>
		<div id="B_Sub" align="centr"></div>
		<input name="Menu_Item" value="show_err" type="hidden" />
<?   }  
$N = (isset($_REQUEST ["N"]))? $_REQUEST ["N"]:"";
//echo "N=",$N, "</br>";
if ($N=="") return;
//echo "select * from v_empt_nic where left(Nic, 1)=$N order by `Nic`";
$result = mysql_query("select * from v_empt_nic where left(Nic, ".strlen($N).")='".$N."' order by `Nic`");
	// Выполняем запрос	customer where Bill_Dog not in (select Bill_Dog from v_cust)
$GLOBALS['menu'] = 'show_err';
$nbo = 1;
$i = 0;
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgcolor = $cfg['BgcolorOne'];
?>
    <tr>
        <td bgcolor="<?php echo $bgcolor; ?>"> №</br>п/п</td>
        <td bgcolor="<?php echo $bgcolor; ?>">Сетевой ник</td>
        <td bgcolor="<?php echo $bgcolor; ?>"></td>
    </tr>

<?php
// Печатаем данные построчно
	$t1 = ""; // $row["town"];
	$s1 = ""; // $row["name_street"];
    while ($row = mysql_fetch_assoc($result))  {
		$bgcolor = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
		?>
		<tr>
			<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $nbo++;		?> </td>
			<td align="right" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Nic"];	?> </td>
			<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <a href='javascript:{ch_param("frm_adress", "new=new&menu=show_err&Nic=<?php echo $row["Nic"]?>", "new_adr<?php echo $row["Nic"]?>");}'>указать адрес</a> </td>
		</tr>
		<tr><td colspan="3"><div id="new_adr<?php echo $row["Nic"] ?>" style="background-color:#FFCCCC"></div></td></tr>
		<?php 
    }
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function err_cust(){
$result = mysql_query("select * from v_customer where Bill_Dog not in (select Bill_Dog from v_cust)");
// Выполняем запрос	?>
<table border="0" cellpadding="2" cellspacing="1" >
	<tr>
    	<td align="center" colspan="10"><h3><?
			if (!isset($result)) { ?>
				Ошибок в базе нет</h3></td></tr></table><?	return;
			} ?>
				Ошибки в базе. Клиенты с неверным адресом</h3></td></tr>
<?
$nbo = 1;
$i = 0;
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgcolor = $cfg['BgcolorOne']; ?>
<td bgcolor="<?php echo $bgcolor; ?>"> №</br>п/п</td>
<td bgcolor="<?php echo $bgcolor; ?>">улица<br />  дом-корпус</td>
<td bgcolor="<?php echo $bgcolor; ?>">id подъезда</td>
<td bgcolor="<?php echo $bgcolor; ?>">подъезд</td>
<td bgcolor="<?php echo $bgcolor; ?>">кв.</td>
<td bgcolor="<?php echo $bgcolor; ?>">макс.</br>№ кв.</td>
<td bgcolor="<?php echo $bgcolor; ?>"></td>
<td bgcolor="<?php echo $bgcolor; ?>">Ф.И.О.</td><!--тип об.-->
<td bgcolor="<?php echo $bgcolor; ?>">Nic</td>
<td bgcolor="<?php echo $bgcolor; ?>"> № </br>Договора</td>
<td bgcolor="<?php echo $bgcolor; ?>">телефон</td><!--Серийный №-->
</tr>

<?php
// Печатаем данные построчно
	$t1 = ""; // $row["town"];
	$s1 = ""; // $row["name_street"];
    while ($row = mysql_fetch_assoc($result))  { ?>
    	<tr>	<?
		if (!strcmp ($s1, $row["name_street"])==0) {  //  name_street
			?>
            <td> </td>
			<th background="gray2_bg.gif" bgcolor="#DFDFDF">
              <span style="font-size:14px"> <? echo $row["name_street"];?></span>
            </th>
			<tr>
			<? $s1 = $row["name_street"];
		} else { /*			echo '<td>'.' '.'</td>'; */	 }; //  name_street
		$bgcolor = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
		?>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $nbo++;				?> </td>
		<td align="right" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Num_build"].(empty($row["Korpus"])?"      ":"-".$row["Korpus"]);		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["id_Podjezd"];		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Podjezd"];		?> </td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>">
        	<input name="f<?php echo $row["Bill_Dog"] ?>" type="text" size="3"
            	onchange="if (this.value>=<?php echo $row["FirstFlat"]?> && this.value<=<?php echo $row["LastFlat"]?>){
                				new_fl(this,<?php echo $row["Bill_Dog"] ?>);
                			} else { alert('Не верный номер квартиры!');document.getElementById('d<?php echo $row["Bill_Dog"] ?>').innerHTML = '';}" value="<?php echo $row["flat"]?>"/> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["LastFlat"];						?> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"><div id="d<?php echo $row["Bill_Dog"] ?>"></div></td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Fam"].' '.$row["Name"].' '.$row["Father"]; ?> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Nic"];							?> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Bill_Dog"];						?> </td>
		<td bgcolor="<?php echo $bgcolor; ?>"> <?php 
			if ($row["phone_Home"]>0) { echo $row["phone_Home"];
				} else if ($row["phone_Cell"]>0) { echo $row["phone_Cell"];
				} else echo $row["phone_Work"];			?> </td>
		</tr>		<?php 
    } ?>
    </td>	<?
}
?>