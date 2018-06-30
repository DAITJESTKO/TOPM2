<?php
/// ПЕРЕМЕННЫЕ КОТОРЫЕ ПРИЛЕТАЮТ НА СКРИПТ В ЗАПРОСЕ
// $account - Номер счета, либо телефона, либо другой параметр, который вводит клиент в терминале, это его идентификатор
// $txn_id - Номер транзакции, у каждого платежа он уникальный, обычно состоит из 13-ти цифр
// $sum - сумма платежа, разделитель точка, например 95.50 (95 рублей 50 копеек)
///

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

$account=HACK($account); // Очистка переменной от опасных символов



// Проверка на ИП адрес, дополнительная защита, ИП адреса лучше лишний раз уточнить у Бибгаева, они могли поменяться
$ip = getenv("REMOTE_ADDR");
$ok='';
if ($ip=='81.176.214.110'){$ok=='1';}
if ($ip=='81.176.214.107'){$ok=='1';}
//

if ($ok=='1'){
///
/*
Сюда добавляем процедуру проверки на существование такого идентификатора, который указал клиент (лицевого счета) и начисления денег на счет.
Рекомендую использовать 2 базы, одна для платежей успешно прошедших, другая для ошибочных, т.к. пользователи часто будут ошибаться и начислять не туда куда нужно,
к тому же часто пишут разную хрень, что-то вроде "Положите мне на интернет 500р." вместо номера счета или договора.
Если есть возможность сделать проверку по Логину интернета и по номеру счета одновременно, то это исключило бы много ошибок.
Лучше указывать время платежа time(), это тоже спасет от казусов когда начнутся притензии.
*/
///
//######################################################################################################################################
$conn = db_connect();
echo "Начало<br>";
if (!$conn) return 0;
echo "Подключен<br>";
echo "Начало<br>";
echo $ip;
//######################################################################################################################################
}

print "<?xml version=\"1.0\"?>\n"; // Далее всегда возвращается нулевая ошибка (т.е. отсутсвие ошибки), если работать напрямую с ОСМП, то там целый спектр ошибок, у Бибгаева их нет, поэтому всегда возвращаем ОК, как и сделано ниже.
?>
<response>
<osmp_txn_id><?print $txn_id;?></osmp_txn_id>
<result>0</result>
</response>

<? return;
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

$id_p		= $_REQUEST ["id_p"];
$fl			= $_REQUEST ["fl"];
$TabNum  	= $_REQUEST ["TabNum"];
$Bill_Dog  	= $_REQUEST ["Bill_Dog"];
$Nic  		= $_REQUEST ["Nic"];
$Login  	= $_REQUEST ["Login"];
$abon 		= $_REQUEST ["abon"];
$inet 		= $_REQUEST ["inet"];
$abon_Com 	= $_REQUEST ["abon_Com"];
$inet_Com 	= $_REQUEST ["inet_Com"];
//$new_Date_end = $_REQUEST ["new_Date_end"];
$action 	= $_REQUEST ["action"];
$Date_start = $_REQUEST ["Date_start"];
$Date_end 	= $_REQUEST ["Date_end"];
$nDateAct 	= $_REQUEST ["nDateAct"];
$dolg		= isset($_REQUEST ["dolg"]);
if ($dolg) {
	$s_dolg	= $_REQUEST ["s_dolg"]+100;
	$d_off	= $_REQUEST ["d_off"];
	$c_dolg = $_REQUEST ["c_dolg"];
}
$now		= date("Y-m-d H:i:s");	

if ($_REQUEST ["abon"] !=0) {
//$to_Date_pay = ($action > 0?$nDateAct:$new_Date_pay);
//*********************	
/*	$q_abon = "select `Saldo` from `v_customer` where Bill_Dog=$Bill_Dog";//." and Flat=".$fl
	$s_abon =  mysql_query($q_abon) or die(mysql_error());
	$row_abon = mysql_fetch_array($s_abon, MYSQL_ASSOC);
*/		
//*********************	empty
	$cod = get_Cod_flat($id_p, $fl);
	if ($cod==0) { $cod = get_cod($Bill_Dog); }
	if ($dolg) {	//date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))
		put_noti2conn ('1', $c_dolg, $Bill_Dog, $cod, $id_p, $fl, $TabNum);
	//*********************	
		echo "Информация об оплате долга ... ";//Date_end
		$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
						"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_start','$d_off',$s_dolg,1,'опл.долга и переподкл')";//		.'</br>'
		$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
		echo "Добавлена.</br>";
		
//		put_noti2off ($_REQUEST["Date_pay"], $Bill_Dog, $cod, $id_p, $fl, $TabNum);

		echo "Окончание откл. остояния абонента ... ";
		$q_stt = mysql_query("update `customers` set Date_end_st='$c_dolg' where Bill_Dog=$Bill_Dog") or die(mysql_error());
		echo "установлено в $c_dolg.</br>";

	}
	echo "Срок в заявке на отключение ... ";//$Bill_Dog
	$dp = strtotime($nDateAct); $Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
//	$s_q = "select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` where Bill_Dog=$Bill_Dog and conn=-1 and `Date_Fact` IS NULL";
	$s_q = "select `Date_Plan` as Max_Date_Plan from `notify_repair` where Bill_Dog=$Bill_Dog and conn=-1 and `Date_Fact` IS NULL";
	$q_DP = mysql_query($s_q) or die(mysql_error());// order by `Date_Plan` desc
	if(mysql_num_rows($q_DP)>0) {
		$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
		$s_del ="update `notify_repair` set Date_Plan='$Date_Plan' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Max_Date_Plan"]."'";
		$q_del = mysql_query($s_del) or die(mysql_error());
//		$q_del = mysql_query("update `customers` set Date_Plan='$Date_end' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Date_end_st"]."'") or die(mysql_error());
		echo "перенесён на $Date_Plan.</br>";//$Date_end
	} else { /* нет заявки на отключение, внести */
		$q_ins_noti = "insert into `notify_repair` (Cod_flat,id_p,fl,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn,Notify) ".
						"values ($cod,$id_p,$fl,$TabNum,$Bill_Dog,'$Nic','$now','$Date_Plan',-1,'откл.(долг)')";
		$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		echo "установлен в $Date_Plan.</br>";
	}
	
//*********************	
	echo "Изменения в данные о подключении к сети... ";//Saldo=".($_REQUEST ["abon"]+$row_abon["Saldo"])."
 	$q_cor_abon = "update `customers` set Date_pay='$nDateAct' where Bill_Dog=$Bill_Dog";//
	$s_cor_abon =  mysql_query($q_cor_abon) or die(mysql_error());// Date_start_st='$Date_start', Date_end_st='$to_Date_end',

	$q_cor_abon = "update `customers` set Date_end_st='$Date_end' where Bill_Dog=$Bill_Dog and state=1";//
	$s_cor_abon =  mysql_query($q_cor_abon) or die(mysql_error());// Date_start_st='$Date_start', Date_end_st='$to_Date_end',
	echo "Внесены.</br>";	//	 state=1,
	
//*********************	!!!!
/*	echo "Изменения в данные подключения к сети... ";
//	$q_cor_nics = "update `nics` set  state=1, Date_end_st='$new_Date_end' where Bill_Dog=$Bill_Dog";//		.'</br>'
	$q_cor_nics = "update `customers` set  state=1, Date_end_st='$new_Date_end' where Bill_Dog=$Bill_Dog";//		.'</br>'
	$s_cor_nics =  mysql_query($q_cor_nics) or die(mysql_error());	// echo "Ошибка при обновлении подключения к сети.\n";
	echo "Внесены.</br>";
*/	
//*********************	
	echo "Информация о платеже абонплаты ... ";
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_start','$Date_end',$abon,1,'$abon_Com')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	echo "Добавлена.</br>";
	
//*********************	
		if ($action > 0) {
			$dp = strtotime($Date_end); $Date_s = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
			echo "Информация о предоставлении $action мес. по акции ... ";
			$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
							"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_s','$nDateAct',0,3,'по акции $action мес.')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			echo "Добавлена.</br>";
		}
	}
//*********************	
	if ($inet !=0) {
		echo "Информация о платеже за интернет ... ";
	//*********************	
		$q_ins_inet = "insert into `actions` (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Summa,id_ActionType,Comment) ".
						"values ($TabNum,$Bill_Dog,'$Nic','$Login','$now','$Date_s',$inet,2,'$inet_Com')";//		.'</br>'
		$s_ins_inet =  mysql_query($q_ins_inet) or die(mysql_error());
	//*********************	
		$s_inet =  mysql_query("select `saldo` from `logins` where Login='$Login'") or die(mysql_error());
		$row_inet = mysql_fetch_array($s_inet, MYSQL_ASSOC);
		$s_cor_inet =  mysql_query("update `logins` set saldo=".($inet+$row_inet["saldo"])." where Login='$Login'") or die(mysql_error());
		echo "Добавлена.</br>";
		
	//*********************	
		$q_inet = "select `saldo` from `logins` where Login='$Login'";//." and Flat=".$fl
		$s_inet =  mysql_query($q_inet) or die(mysql_error());
		$row_inet = mysql_fetch_array($s_inet, MYSQL_ASSOC);
		echo '<FONT size=+1>Баланс интернета после внесения: '.set_color($row_inet["saldo"]).' руб.</FONT></br>';
	//*********************	
	}
//*********************	
	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value=document.forms.ulaForm.h_Cod_flat.value; '.
			'document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');'.
			'" value="Обновить"/>';	//		//document.getElementById(\'B_chk_adress\').onClick();	ch(\'ch_flt\',\'menu=pay&\',2,\'tab_Cust\');
//*********************		//	*********************	//	*********************

function set_color ($sum) {
	return '<font color="'.(($sum<0)?'FF0000':'0033FF').'"><b>'.$sum.'</b></font>';
}
#############################################################################################?>
