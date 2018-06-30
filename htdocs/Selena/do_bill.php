<?
  require_once("db_fns.php");
  require_once("user_auth_fns.php");
$conn = db_connect();
if (!$conn) return 0;
header("ETag: PUB" . time());
header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
header("Pragma: no-cache");
header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
session_cache_limiter("nocache");

$fp = fsockopen("10.1.5.115", 49160, $errno, $errstr, 30);
if (!$fp) {
echo "$errstr ($errno)<br>\n";
} else {
	$account =  $_REQUEST ["account"];
	$txn_id =  $_REQUEST ["txn_id"];
	$trm_id =  $_REQUEST ["trm_id"];
	$amount =  $_REQUEST ["amount"];
	if ($account<900) {
		//echo "Необходима трансляция номера договора";
		$account = get_acc($account);
	}
	while (false !== ($char = fgetc($fp))) { }
	
	echo "Счёт: ".$account. "<br>";
	echo "Пользователь: ", $wNic = getNic(fcmd($fp, "look $account")),"<br>";
	echo "Баланс 1: ", $acc_1 = getSum(fcmd($fp, "acc $account")),"<br>";
	echo "Результат: ", $res = "'".getSum(fcmd($fp, "add $account $amount"))."'","<br>";
	echo "Баланс 2: ", $acc_2 = getSum(fcmd($fp, "acc $account")),"<br>";
	$now = "'".date("Y-m-d H:i:s")."'";
	if (floatval($acc_2) - floatval($acc_1) == floatval($amount)) {
		$q_ins = "insert into `t_int` (time, account, txn_id, amount, trm_id,result) ".
						"values ($now,$account,$txn_id,$amount,$trm_id,$res)"	;//echo,'</br>'
		echo "Информация о платеже за интернет ... ";
	//*********************	
		$B_inf = get_inf_acc($account);
		$q_ins_inet = "insert into `actions` (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Summa,id_ActionType,Comment) ".
						"values (11,".$B_inf['Bill_Dog'].",'".$B_inf['Nic']."','".$B_inf['Login']."',$now,'".date("Y-m-d")."',".
						"$amount,2,'".($wNic !=$B_inf['Login']?$wNic." ":"")."$txn_id $acc_1 +')";//echo 	,'</br>'
		$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
	//*********************	
	/*	$s_inet =  mysql_query("select `saldo` from `logins` where Login='$Login'") or die(mysql_error());
		$row_inet = mysql_fetch_array($s_inet, MYSQL_ASSOC);	*/
		$s_c = "update `logins` set saldo=$acc_2 where Login='".$B_inf['Login']."'";//echo 	,'</br>'
		$s_cor_inet =  mysql_query($s_c) or die(mysql_error());
		echo "Добавлена.</br>";
		
	//*********************	
	} else {
		echo "Ошибка !<br>";
		$q_ins = "insert into `t_int` (time, account, txn_id, amount, trm_id,result) ".
						"values ($now,$account,$txn_id,$amount,$trm_id,$res)";
	}
		$s_ins =  mysql_query($q_ins) or die(mysql_error());
	
	
//echo "---------------------<br>";
	fclose ($fp);
}
//====================================================================================================
function get_acc($account) {
	return $account;
	$r_acc =  mysql_query("select account from acc_bill where Bill_Dog=$account") or die(mysql_error());
	$acc = mysql_fetch_array($r_acc, MYSQL_ASSOC);
	return /*mysql_num_rows($acc)>0?*/$acc["account"];
}

function fcmd($fp, $cmd) {
	fputs ($fp, "$cmd\n");	$s = '';
	while (false !== ($char = fgetc($fp))) { $s .= "$char"; }
	return $s;
}

function getNic($s) {
	$n_ = explode(chr(9), strstr($s, "adder"));
	$nic = explode(chr(10), $n_[2]);	
	return $nic[0];
}
function getSum($s) {
	$s2 = ltrim(strstr(strstr($s, ")"), " "));
	$s3 = explode(" ", $s2);
	return $s3[0];
}

function show_chr($s) {
	$i=0; 
	while ($i < strlen($s)) { 
		echo " ", $s{$i}==" "?"_":ord($s{$i}); 
		$i += 1; 
	}
}
?>