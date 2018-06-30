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

$result = mysql_query("select left(Nic, 1) as N from v_empt_nic GROUP BY left(Nic, 1) order by left(Nic, 1)")
				or die(mysql_error());?>
<table border="0" cellpadding="2" cellspacing="1" >
    <tr>
<? if (!isset($_REQUEST ["N"])) { ?>
		<td align="center">
			<? if (!isset($result)) { ?>
				Неучтённых сетевых ников нет</td></tr> <? return;
			} ?>
		</td>
    </tr>
<?php /*?>    <tr>
		<td bgcolor="#E5E5E5" align="center"><b>
		<? 	while ($row = mysql_fetch_assoc($result))  {	?>
                <a href='javascript:{ch_param("err_nic","N=<? echo $row["N"]?>","fNics");}'> <? echo $row["N"]?> </a>&nbsp;
        <?    }
?>		</b></td>
	</tr><?php */?>
	<tr><td colspan="3">
    	<div id="sNics"><b>Новый абонентский Ник: </b>
    		<input name="sNics" onkeyup='ch_param("err_nic","N="+this.value,"fNics");' /></div>
    	<div id="fNics"></div>
		<div id="B_Sub" align="centr"></div>
		<input name="Menu_Item" value="" type="hidden" />
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
			<td bgcolor="<?php echo $bgcolor; ?>"> <a href="javascript:{f=document.forms.ulaForm; if(confirm('Вы согласны выполнить следущее: В абон учётке ник '+f.Nic.value+' будет заменён на <? echo $row["Nic"]?>, в таблице интернет логинов для логина '+f.Nic.value+' ник будет изменён на <? echo $row["Nic"]?>. Вы уверены? ')){ch_param('do_chng_nic','tn='+f.TabNum.value+'&Bill_Dog='+f.Bill_Dog.value+ '&newNic=<? echo $row["Nic"]?>&oldNic='+f.Nic.value,'new_<? echo $row["Nic"]?>'); } else { alert ('Отменено.');};}"> <? echo $row["Nic"]?></a> </td>
		</tr>
		<tr><td colspan="3"><div id="new_<?php echo $row["Nic"] ?>" style="background-color:#FFCCCC"></div></td></tr>
<?php // ch_param("frm_adress", "new=new&menu=show_err&Nic=<?php echo $row["Nic"]? >", "new_adr<?php echo $row["Nic"]? >");}
    }
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
?>