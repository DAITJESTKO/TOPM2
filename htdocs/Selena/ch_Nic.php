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
//if (isset($_REQUEST["Nic"]))
	$Nic = $_REQUEST["Nic"]; 
//else 
//	echo "Ник не определен";
				
	$q_Login = "SELECT * FROM logins where Nic='".$Nic."'";
	$Login = mysql_query($q_Login) or die(mysql_error());
	$row_Login = mysql_fetch_assoc($Login);
	$totalrow_Login = mysql_num_rows($Login);
//	echo $q_Login, "-", $Nic, "-", 
	$totalRows_Login = mysql_num_rows($Login);
?>

<!--<select name='login' size="4" class='font8pt' onchange='ch("ch_Login", "Login", this, "pay_table")'>-->
<select name='login' size="<?php echo $totalRows_Login ?>" class='font8pt' onchange='ch_param("ch_Login", "Login="+this.value, "pay_table")'>
<option value="0">-</option>
	 <?php 
			do {  ?>
	  			<option value="<?php echo $row_Login["Login"] ?>"> <?php echo $row_Login["Login"] ?></option>
	 <?php
			} while ($row_Login = mysql_fetch_assoc($Login));
	?>
</select>
