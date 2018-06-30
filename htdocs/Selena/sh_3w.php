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

$now		= date("Y-m-d H:i:s");	

//*********************	
//print "php_sockets.dll - "; 
    if(extension_loaded('sockets')) 
    {	//        print "loaded"; 
    } else {    print "<b>Не загружена библиотека для работы с сокетами!!!</b><br>";     }
//*********************	
 $deb = 0;
error_reporting(E_ALL); 
set_time_limit(60); 
ob_implicit_flush(); 

if (($fp = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === False) {
    echo "Ошибка соединения с сервером биллинга: " . socket_strerror($sock) . "\n";
//	return
}
$result    = socket_connect($fp, $IPb, 49160); 

if (!get_wellcome($fp)) 
	echo date("Y-m-d H:i:s")," Ошибка при прочитении данных приветствия<br> ";

if (!$fp) {	echo "$errstr ($errno)<br>\n"; }
elseif (preg_match("/\D/",$account = HACK($_REQUEST ["account"]))) {
	echo "ошибка в номере счёта!";
} else	{

//	echo "Информация об интернет счёте: <b>".$account. "</b><br>";
//	echo $wNic = ($n==""?getNic(get_000($fp, "look $account")):$n);
	$n = getNic(get_000($fp, "look $account"));		$wNic = ($n==""?getNic(get_000($fp, "look $account")):$n);
//	echo $wNic;
	if ($wNic == "") return;
	$acc_1 = getSum($acc = send_command($fp, "acc $account"));
	$acc_2 = $acc_1;
//*********************	
		$B_inf = get_inf_acc($account);
//*********************	
	$s_c = "update `logins` set saldo=$acc_2 where Login='".$B_inf['Login']."'";//echo 	,'</br>'
	$s_cor_inet =  mysql_query($s_c) or die(mysql_error());
//	echo "... счёт обновлен.</br>";
//*********************	
	echo (isFrosen($acc)?'<img src="fr.png" />':"");	//
	echo (isOFF($acc)?'<img src="off.jpg" />':"");
//*********************	
	$q_inet = "select `saldo` from `logins` where account=$account";//." and Flat=".$fl
	$s_inet =  mysql_query($q_inet) or die(mysql_error());
	$row_inet = mysql_fetch_array($s_inet, MYSQL_ASSOC);
//	echo '<FONT size=+1>Баланс интернет-счёта №'.$account.': '.set_color($row_inet["saldo"]).' руб.</FONT></br>';
//*********************	
	echo '<input name="B_" type="button" onclick="ch_param(&quot;set_t_inet&quot;, &quot;txn_id='.$_REQUEST ["txn_id"].
			'&account='.$_REQUEST ["account"].'&tn='.$_REQUEST['tn'].'&quot;, &quot;v'.$_REQUEST ["txn_id"].'&quot;);'.
			' document.getElementById(\''.$_REQUEST ["txn_id"].'\').style=\'display:none\';'.
			'" value=">>"/>'.$wNic;//√
//*********************	
}
if ($fp) { socket_close($fp); }
///*********************	//	*********************	//	*********************

function set_color ($sum) {
	return '<font color="'.(($sum<0)?'FF0000':'0033FF').'"><b>'.$sum.'</b></font>';
}
function fdebug($sdeb) {
	if ($GLOBALS['deb']==1) { echo date("Y-m-d H:i:s")." ".$sdeb; }
}
?>