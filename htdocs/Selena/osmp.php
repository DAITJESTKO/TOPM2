<?
mysql_connect("localhost", "loveland", "QQQwwwEEE");
mysql_select_db("loveland");

 $double='2'; //??????????? ???? ????
function HACK($varforsql){ // ?????????, ????? ??? ? ???? SQL
//$varforsql=str_replace(";","&#59;",$varforsql);
$varforsql=str_replace('`',"&#96;",$varforsql);
$varforsql=str_replace("'","&#39;",$varforsql);
$varforsql=str_replace('"',"&#34;",$varforsql);
$varforsql=str_replace('\\',"&#92;",$varforsql);
$varforsql=str_replace('/',"&#47;",$varforsql);
//$varforsql=str_replace('$',"&#36;",$varforsql);
$varforsql=str_replace("<","&#60;",$varforsql);
$varforsql=str_replace(">","&#62;",$varforsql);
//$varforsql=str_replace("=","&#61;",$varforsql);
$varforsql=str_replace("*","&#42;",$varforsql);
//$varforsql=str_replace("-","&#45;",$varforsql);
//$varforsql=str_replace("?","&#63;",$varforsql);
$varforsql=str_replace('?',"&#45;",$varforsql);
//$varforsql=str_ireplace("&amp;","&#45",$varforsql);
return $varforsql;
}

$account=HACK($account);

$ip = getenv("REMOTE_ADDR");
$ok='';
if ($ip=='81.176.214.110'){$ok=='1';}
if ($ip=='81.176.214.107'){$ok=='1';}

$ok='1';

if ($ok=='1'){

//$command;
//$txn_id;
//$account;
//$sum;

$errmsg="";


//////////// ???? ??? ????//////////////////////////////////////
$result=mysql_query("SELECT `id`,`login` FROM  `users` WHERE `id`='".$account."' LIMIT 1");
$line = mysql_fetch_array($result, MYSQL_ASSOC);
//print $line['login']."123";
if ($line['login']==''){
$result2=mysql_query("SELECT `id`,`login` FROM  `users` WHERE `login` LIKE '%".$account."%' LIMIT 1");
$line2 = mysql_fetch_array($result2, MYSQL_ASSOC);
if ($line2['id']==''){
mysql_query("INSERT INTO `money_terminal_osmp_no` VALUES('','$txn_id','".time()."','$account','$sum');");
$account='';
} else {
$account=$line2['id'];
}
}

if ($account<>'') {
mysql_query("INSERT INTO `money_terminal_osmp_ok` VALUES('','$txn_id','".time()."','$account','$sum');");
$anketa=$account;
$summa=$sum;
mysql_query("INSERT INTO `".'$$$'."` VALUES ('$anketa','0','$summa','".time()."','add','????? ?????????? ??? ??')");
$opl2=$summa;
if ($double=='2'){
$opl2=$summa*2;
mysql_query("INSERT INTO `".'$$$'."` VALUES ('$anketa','0','$summa','".time()."','add','??? ???? ????')");
}
$result = mysql_query("SELECT `oplata_dengi` FROM `users_aktiv` WHERE `id` ='$anketa' LIMIT 1");
$line = mysql_fetch_array($result, MYSQL_ASSOC);
$opl2=$opl2+$line['oplata_dengi'];
$query = "UPDATE `users` SET `oplata_data`='".time()."' WHERE `id`='$anketa' LIMIT 1";
mysql_query($query);
$query = "UPDATE `users_aktiv` SET `oplata_dengi`='$opl2',`oplata_vizit`='".date("Y-m-d",time())."' WHERE `id`='$anketa' LIMIT 1";
mysql_query($query);
///
$mestext="? ???? ???? ??? $sum ???. ??? ????????????? ??. ??????????????? ????. ?? ??? ???????? ????? ????? ? ???? 38-40-40.";
if ($double=='2'){$mestext=$mestext.' ???? ???????? ???? ???? ????? ???? ?? ???????????? $sum ???.';}
mysql_query("INSERT INTO `messages` VALUES('','$anketa','0','0','$mestext','".time()."','0','0','msg_servise');");
mysql_query("UPDATE `messages` SET `lastid`='".mysql_insert_id()."' WHERE `userid`='$anketa' AND `sesuserid`='0'");
$result22 = mysql_query("SELECT `new_messages` FROM `users_aktiv` WHERE `id`='".$anketa."'");
$line22 = mysql_fetch_array($result22, MYSQL_ASSOC);
$line22['new_messages']++;
mysql_query("UPDATE `users_aktiv` SET `new_messages`='".$line22['new_messages']."' WHERE `id`='".$anketa."' LIMIT 1");
///

}

}

print "<?xml version=\"1.0\"?>\n";
?>
<response>
<osmp_txn_id><?print $txn_id;?></osmp_txn_id>
<result>0</result>
</response>

