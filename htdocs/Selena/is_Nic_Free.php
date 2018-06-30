<?php
require_once("for_form.php"); 
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
//is_Nic_Free()
$Nic = $_REQUEST ["Nic"];
$new = $_REQUEST ["prm"]=="new";

// function is_Nic_Free($nic) {
//  	$result = mysql_query("SELECT Nic FROM `nics` where `Nic`='$Nic'");
  	$result = mysql_query("SELECT Nic, Bill_Dog FROM `customers` where UPPER(Nic)='".strtoupper($Nic)."'");
  if (!$result) {
  	echo "Возникли проблемы";
     return 0; }
  if (mysql_num_rows($result)>0) {
	$row = mysql_fetch_assoc($result);
?>	<input name="DublNic" type="hidden" value="1" />
	<FONT size=+1 COLOR="#FF0000">Такой ник в договоре <? echo $row["Bill_Dog"]?>
    <? /*<a href="javascript:{ch_param('sh_form','<? echo "menu=pay&tn=$tn&tp=$tp&Bill_Dog={$row['Bill_Dog']}','Mform');s_Bill_Dog();}"?>"><? echo $row["Bill_Dog"]?></a> */?>
	!</FONT>
<?     return; }
  else
	if ($new) {
		  echo '<input name="DublNic" type="hidden" value="0" /><input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();" />';
	} else {
		  echo '<input name="DublNic" type="hidden" value="0" /><input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />';
	}
//     return 0;
//}

?>