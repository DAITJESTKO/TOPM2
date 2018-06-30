<?
mysql_connect("localhost", "root", "") or die("?? ????????. ????????");
mysql_select_db("love") or die("??? ?? ??? ? ?????");

$text=$inputmessage;

$a=explode("\n",$text);
for ($i=0;$i<count($a);$i++){
list($name,$value)=explode("=",$a[$i]);
if ($name=='SD'){$sd=$value;} // ??????
if ($name=='AP'){$ap=$value;} // ???????? ????
if ($name=='OP'){$op=$value;} // ???????
if ($name=='SESSION'){$session=$value;} // ????? ??????
if ($name=='NUMBER'){$number=$value;} // ?????? ??????
if ($name=='ACCOUNT'){$account=$value;} // ??????? ???? (? ???
if ($name=='AMOUNT'){$amount=$value;} // ?????????????? ????????- ???
if ($name=='REQ_TYPE'){$req_type=$value;} // 1-??? ????? ???? ?????????
if ($name=='COMMENT'){$comment=$value;} // ??????????
}

$sd=str_replace("\r","",$sd);
$sd=str_replace("\n","",$sd);
$ap=str_replace("\r","",$ap);
$ap=str_replace("\n","",$ap);
$op=str_replace("\r","",$op);
$op=str_replace("\n","",$op);
$session=str_replace("\r","",$session);
$session=str_replace("\n","",$session);
$number=str_replace("\r","",$number);
$number=str_replace("\n","",$number);
$account=str_replace("\r","",$account);
$account=str_replace("\n","",$account);
$amount=str_replace("\r","",$amount);
$amount=str_replace("\n","",$amount);
$req_type=str_replace("\r","",$req_type);
$req_type=str_replace("\n","",$req_type);
$comment=str_replace("\r","",$comment);
$comment=str_replace("\n","",$comment);

/////////////////////////////////
$error='0';
$result1='0';
$errmsg="";
$date=date("d.m.Y H:i:s");
$session=$session;
$opname="LOVE LAND NORILSK";
//$account="";
/////////////////////////////////

//////////// ?????? ??????????????? ??? ///////////////
if (mysql_query("INSERT INTO `money_ter_check` VALUES ('$sd','$ap','$op','$session','$number','$account','$amount','$req_type','$comment','$date');")) {
} else {
$error='1';
$result1='1';
$errmsg="??? ???????????????!";
}
//////////// ???? ??? ????//////////////////////////////////////
if ($error=='0'){
$result=mysql_query("SELECT `login` FROM  `users` WHERE `id`='".$number."'");
$line = mysql_fetch_array($result, MYSQL_ASSOC);
if ($line['login']==''){
$error='23';
$result1='1';
$errmsg="??? ??? ? ?????!";
}}
//////////// ???? ??????? //////////////////////////////////////
if ($error=='0'){
if ($amount<=0){
$error='7';
$result1='1';
$errmsg="?????????? ?? ???????";
}}
//////////////////////////////////////////////////////////////////////////

$text="DATE=$date
SESSION=$session
ERROR=$error
RESULT=$result1
OPNAME=$opname
ACCOUNT=$account
ERRMSG=$errmsg";
print "$text";
?>

