<? /*
header("ETag: PUB" . time());
header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
header("Pragma: no-cache");
header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
session_cache_limiter("nocache");
*/
// Проверка на ИП адрес, дополнительная защита, ИП адреса лучше лишний раз уточнить у Бибгаева, они могли поменяться
$ip = getenv("REMOTE_ADDR");
$ok='';
$deb = 0;
if ($ip=='81.176.214.110'){$ok ='1';}
if ($ip=='81.176.214.107'){$ok ='1';}
if ($ip=='10.1.90.26'){$ok ='1'; $deb = 1; }
if ($ip=='10.1.253.6'){$ok ='1'; $deb = 1; }
if ($ip=='127.0.0.1'){$ok ='1'; $deb = 1; }
	fdebug("Старт!<br>");
//

if ($ok=='1'){

	require_once("db_fns.php");
	require_once("user_auth_fns.php");
	$conn = db_connect();
	if (!$conn) return 0;

	$account =  $_REQUEST ["account"];
	$txn_id =  $_REQUEST ["txn_id"];
	$trm_id =  $_REQUEST ["trm_id"];
	$amount =  $_REQUEST ["amount"];
	$now = "'".date("Y-m-d H:i:s")."'";
	$account=HACK($account); // Очистка переменной от опасных символов

	if ($account<900) {
		//echo "Необходима трансляция номера договора";
		$account = get_acc($account);
	}
error_reporting(E_ALL); 

set_time_limit(30); 
ob_implicit_flush(); 


$fp    = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); 
$result    = socket_connect($fp, $IPb, 49160); 
if (!get_wellcome($socket)) 
	echo date("Y-m-d H:i:s")," Ошибка при прочитении приветствия<br> ";

/*
$fp = fsockopen($IPb, 49160, $errno, $errstr, 1);
*/
fdebug("Есть соединение!<br>");
//fdebug(send_command($fp, 'add $account $amount')); 
//fdebug(send_command($fp, 'acc 3')); 
//fdebug(send_command($fp, 'look 3')); 
//$out = socket_read($fp, 512);
//echo $result;
if (/*$result === FALSE*/!$fp) {
	echo $err="$errstr ($errno)","<br>\n";
	fdebug("Ошибка !<br>");
	fdebug("Запрос $txn_id с терминала $trm_id по счёту $account на сумму $amount не обработан. Время: $now<br>");
	$q_ins = "insert into `t_i_er` (time, account, txn_id, amount, trm_id, result) ".
					"values ($now,$account,$txn_id,$amount,$trm_id,'$err')";
	$s_ins =  mysql_query($q_ins) or die(mysql_error());
		
	$f_txn = fopen("/txn_id/$txn_id.txn", "a+"); ////t_inet
    if (fwrite($f_txn, "$now,$txn_id,$trm_id,$account,$amount") === FALSE) {
        fdebug("Cannot write to file ($txn_id.txn)<br>");
		file_put_contents("/txn_id/errors.txt", "$now,$txn_id,$trm_id,$account,$amount", FILE_APPEND); //https://10.1.2.22/t_inet/
//        exit;
    }
//    echo "Success, wrote ($somecontent) to file ($filename)";
    fclose($f_txn);
		
} else {
	send_command($fp, 'acc 3');
$out = socket_read($fp, 512);
$out = socket_read($fp, 512);
$out = socket_read($fp, 512);
//	echo $out;

//	while (false !== ($char = fgets($fp))) { }
	
	fdebug("Счёт: ".$account. "<br>");
//	send_command($fp, 'look 3');
return;

	$acc_1 = getSum(fcmd($fp, "acc $account"));			fdebug("Начальный баланс: ". $acc_1. "<br>");
	$wNic = getNic(fcmd($fp, "look $account"));			fdebug("Пользователь: ". $wNic. "<br>");
	$res = "'".fcmd($fp, "add $account $amount")."'" ;	fdebug("Операция: ". $res."<br>");//getSum()
	$acc_2 = getSum(fcmd($fp, "acc $account")) ;		fdebug("Конечный баланс: ". $acc_2."<br>");
	if (round(floatval($acc_2)) != round(floatval($acc_1))/*, 2) == round(floatval($amount), 2)*/) {
		$q_ins = "insert into `t_inet` (time, account, txn_id, amount, trm_id,result) ".
						"values ($now,$account,$txn_id,$amount,$trm_id,$res)"	;//echo,'</br>'
		$s_ins_inet =  mysql_query($q_ins) or die(mysql_error());
	fdebug("Информация о платеже за интернет ... ");
	//*********************	
		$B_inf = get_inf_acc($account);
	//*********************	
		$q_ins_inet = "insert into `actions` (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
						"values (11,".$B_inf['Bill_Dog'].",'".$B_inf['Nic']."','".$B_inf['Login']."',$now,'".date("Y-m-d")."','".date("Y-m-d")."',".
						"$amount,2,'".($wNic !=$B_inf['Login']?$wNic." ":"")."$txn_id $acc_1 +')";//echo 	,'</br>'
		$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
	//*********************	
		$s_c = "update `logins` set saldo=$acc_2 where Login='".$B_inf['Login']."'";//echo 	,'</br>'
		$s_cor_inet =  mysql_query($s_c) or die(mysql_error());
		fdebug("Добавлена.</br>");
		
	//*********************	
	} else {
		fdebug("Ошибка !<br>");
		$q_ins = "insert into `t_int` (time, account, txn_id, amount, trm_id, result) ".
						"values ($now,$account,$txn_id,$amount,$trm_id,'err_'.$res)";
		$s_ins =  mysql_query($q_ins) or die(mysql_error());
	}
	
	fdebug("---------------------<br>");
	fclose ($fp);
	}
}

print "<?xml version=\"1.0\"?>\n"; // Далее всегда возвращается нулевая ошибка (т.е. отсутсвие ошибки), если работать напрямую с ОСМП, то там целый спектр ошибок, у Бибгаева их нет, поэтому всегда возвращаем ОК, как и сделано ниже.
?>
<response>
<osmp_txn_id><? print $txn_id;?></osmp_txn_id>
<result>0</result>
</response>

<?
//====================================================================================================
function HACK($varforsql){ // ФУНКЦИЯ ДЛЯ ФИЛЬТРОВКИ ТОГО, ЧТО ПИШУТ ЛЮДИ В ТЕРМИНАЛЕ, В ОСНОВНОМ ЗАЩИТА ОТ ИНЬЕКЦИЙ SQL
$varforsql=str_replace('`',"&#96;",$varforsql);
$varforsql=str_replace("'","&#39;",$varforsql);
$varforsql=str_replace('"',"&#34;",$varforsql);
$varforsql=str_replace('\\',"&#92;",$varforsql);
$varforsql=str_replace('/',"&#47;",$varforsql);
$varforsql=str_replace("<","&#60;",$varforsql);
$varforsql=str_replace(">","&#62;",$varforsql);
$varforsql=str_replace("*","&#42;",$varforsql);
$varforsql=str_replace('­',"&#45;",$varforsql);
return $varforsql;
}

function fdebug($sdeb) {
	if ($GLOBALS['deb']==1) { echo date("Y-m-d H:i:s")." ".$sdeb; }
}

/*
function get_acc($account) {
	return $account;
	$r_acc =  mysql_query("select account from acc_bill where Bill_Dog=$account") or die(mysql_error());
	$acc = mysql_fetch_array($r_acc, MYSQL_ASSOC);
	return /*mysql_num_rows($acc)>0?* / $acc["account"];
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
*/
function show_chr($s) {
	$i=0; 
	while ($i < strlen($s)) { 
		echo " ", $s{$i}==" "?"_":ord($s{$i}); 
		$i += 1; 
	}
}
?>
