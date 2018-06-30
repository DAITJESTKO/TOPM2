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
	$tw = 1; // $_REQUEST ["tw"];
//	$nx = $_REQUEST ["nx"];
//	if (isset($_REQUEST ["nx"]))
//		{ $nx = $_REQUEST ["nx"]; } else { $nx = "num_build"; }

	$nx = "num_build";
	$ind = 1; 
	$q_Streets = "SELECT * FROM spr_street where id_town=".$tw;
	$Streets = mysql_query($q_Streets) or die(mysql_error());
	$row_Streets = mysql_fetch_assoc($Streets);
	$totalRows_Streets = mysql_num_rows($Streets);
	
	$q_Streets_2 = "SELECT * FROM _street";// order by КодУлицы
	$Streets_2 = mysql_query($q_Streets_2) or die(mysql_error());
	$row_Streets_2 = mysql_fetch_assoc($Streets_2);
	$totalRows_Streets_2 = mysql_num_rows($Streets_2);
?>	
	<table border="1"><tr><td> № </td><td>spr_street</td><td> № </td><td>_street</td></tr>
<?	do {  echo  "<tr><td>",$row_Streets['id_street'], "</td><td>", $row_Streets['name_street'], "</td><td>",
				$row_Streets_2['КодУлицы'], "</td><td>", $row_Streets_2['Улица'], 
				"</td></tr>";
				} while ($row_Streets = mysql_fetch_assoc($Streets) and $row_Streets_2 = mysql_fetch_assoc($Streets_2))

//
?> 		</table>
		<p>начальное id улицы
		  <input name="street1" type="text" id="street1" size="5" />
		новое id улицы
		<input name="street2" type="text" id="street2" size="5" />
</p>
		<p>
		  <input name="btn_upd" type="button"
		  onclick="{do_upd('change_street',document.getElementById('street1').value, document.getElementById('street2').value); write_temp('исправление'); op_f('cor_street', 'Mform'); write_temp('готово'); }" value="Исправить" />
		</p>
