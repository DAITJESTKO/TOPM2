<?php
require_once("for_form.php"); 
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
//is_Login_Free()
$Login = $_REQUEST ["Login"];
// function is_Nic_Free($nic) {
  	$result = mysql_query("SELECT Login, Bill_Dog, account FROM `logins` where UPPER(Login)='".strtoupper($Login)."'");
  if (!$result) {
  	echo "Возникли проблемы";
     return 0; }
  if (mysql_num_rows($result)>0) {
	$row = mysql_fetch_assoc($result);
?>	<input name="DublLogin" type="hidden" value="1" /><FONT size=-1 COLOR="#FF0000"><b>
<?	echo "Логин занят в договоре {$row['Bill_Dog']}, инет счёт {$row['account']}!";?></b></FONT>
<?     return; }
  else
	if ($_REQUEST ["prm"] && ($_REQUEST ["prm"]=="new")) {
		  echo '<input name="DublLogin" type="hidden" value="0" /><b>&radic;</b>'; //<img src="checkbox_selXP.gif"/>
		} else {
		  echo '<input name="DublLogin" type="hidden" value="0" /><input name="addLogin" type="button" id="addLogin" onclick="DoaddLogin();" value="&radic;" />';
	}	
//  <input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();" />';
//     return 0;
//}

?>