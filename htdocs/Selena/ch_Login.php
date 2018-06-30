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
<table width=250 border=1>
<tr align="center" bgcolor="#66FF66">
	<td width="100">Платёж за месяц</td>
	<td width="100">Сумма, руб</td>
</tr>
<?
$monthname= Array(1=>"январь",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");

	$Login = $_REQUEST["Login"];

	$q_Act = "SELECT * FROM `actions` as a where a.Login='".$Login."' and a.id_ActionType=1"; 
//echo $q_Act,"<br>";
	
	$s_Act =  mysql_query($q_Act) or die(mysql_error());
	$row_Act = mysql_fetch_assoc($s_Act);
	$totalRows_Act = mysql_num_rows($s_Act);

	if ($totalRows_Act > 0) {
		$Sum_Act = 0;
		echo "строк = ", $totalRows_Act," с ", $row_Act["Date_start"];
//		echo "Сетевой Nic:". $row_Act["Nic"]." Login:".$row_Act["Login"];
		do {  echo "<tr><td>&nbsp;", $monthname[1*strftime("%m", strtotime($row_Act["Date_start"]))],"</td><td align='center'>", $row_Act["Summa"],"</td></tr>";
			$Sum_Act = $Sum_Act + $row_Act["Summa"];
		} while ($row_Act = mysql_fetch_assoc($s_Act));
		?>
  <tr bgcolor="#669966">
	  <td><strong>Итого за год:</strong></td>
	<td align="center"><strong><? echo $Sum_Act?></strong></td>
</tr><?
	}
	?>
</table>
