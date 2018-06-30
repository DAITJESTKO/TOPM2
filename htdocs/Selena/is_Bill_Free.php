<?php
require_once("for_form.php"); 
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
//is_Nic_Free()
$Bill_Dog = $_REQUEST ["Bill_Dog"];

// function is_Bill_Free($nic) {Bill_Dog
	$q_BD = "SELECT * FROM `customers` where `Bill_Dog`='$Bill_Dog'";
  	$result = mysql_query($q_BD); // "SELECT Bill_Dog FROM `customers` where `Bill_Dog`='$Bill_Dog'"
//	echo $q_BD;
  if (!$result) {
  	echo "Возникли проблемы";
     return 0; }
  if (mysql_num_rows($result)>0) {
  $var = mysql_fetch_assoc($result);
//	  echo "|".$var["Bill_Dog"]." - ".$var["Fam"]."-".mysql_num_rows($result);
	echo '<input name="DublBill_Dog" type="hidden" value="1" /><FONT size=+1 COLOR="#FF0000">Такой номер договора уже используется!</FONT>';
     return; }
  else
  echo '<input name="DublBill_Dog" type="hidden" value="0" /><input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();" />';
//     return 0;
//}

?>