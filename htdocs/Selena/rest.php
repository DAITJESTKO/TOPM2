<?
require_once("for_form.php"); 
check_valid_user();

function db_c()
{
	$result = mysql_pconnect("localhost", "root", "455029"); 
	if (!$result)
		return false;
	if (!mysql_select_db("Selena"))
		return false;

	$r1 = mysql_query("set character_set_results='utf8'");
	$r1 = mysql_query("SET NAMES 'utf8'");
	return $result;
}
$conn = db_c();

if (!$conn) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");
/*
$res = mysql_query("delete from actions; delete from customers; delete from logins; ".
	"delete from cod_flat; delete from notify_repair".
	"insert into cod_flat select * from v_2_cod_flat; insert into customers select * from v_osn;".
	"insert into logins select * from v_2_logins; ") or die(mysql_error());
/* Для сервера:
delete from actions;
delete from customers;
delete from logins;
delete from cod_flat;
delete from notify_repair;
delete from customers_xls;
delete from customers_mdb;

if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { // $rem_addr="http://selena/";
	$res = f1("delete from actions");
	$res = f1("delete from customers");
	$res = f1("delete from logins");
	$res = f1("delete from cod_flat");
	$res = f1("delete from notify_repair");
}	// else { $rem_addr="https://10.1.2.22/"; }

$res = f1("insert into cod_flat select * from v_2_cod_flat");
$res = f1("insert into customers select * from v_osn");
$res = f1("insert into customers select * from v_osn_2");
$res = f1("insert into logins select * from v_2_logins");
//$res = f1("");
*/
AnalizCust($_REQUEST ["Bill_Dog"]);

/*
insert into logins (
Nic,
Login,
id_tarif3w,
tarif3w_date,
passwd,
state,
Bill_Dog,
saldo
) select Nic, Nic as Login, 0,NULL,NULL,1, Bill_Dog,NULL from customers;
*/

function AnalizCust($Bill_Dog)
{
	echo " Счёт $Bill_Dog";
	//	$res = mysql_query("select * from actions WHERE Nic >= '''' order by Nic, Date_start") or die(mysql_error());
	$res = mysql_query("select * from customers WHERE Bill_Dog = ".$Bill_Dog."") or die(mysql_error());
	$totalRows = mysql_num_rows($res);//, "</br>"
	$row = mysql_fetch_assoc($res);
//	do {
	echo " абонент ".$row["Nic"]."</br>";//	echo , " ", $row["Nic"];
	// вставить удаление новых данных (после 17 окт.2010)
//	$res = mysql_query("delete from actions where Bill_Dog=".$Bill_Dog." and InputDate>='2010-10-19'") or die(mysql_error());
//	echo "операции";//, mysql_fetch_assoc($res)
	$res = mysql_query("delete from notify_repair where Notify='откл.(долг)' and Bill_Dog=".$Bill_Dog) or die(mysql_error());
	echo ", заявки удалены"."</br>";//, mysql_fetch_assoc($res)
	
	$resNic= mysql_query("select * from actions WHERE Nic='".$row["Nic"]."' order by Date_start DESC") or die(mysql_error());
	$q_s = "update customers set `tarifab_date`=null,`DateKor`='".date("Y-m-d H:i:s")."',`Date_start_st`=";
	if (mysql_num_rows($resNic)>0) {
		$rowNic = mysql_fetch_assoc($resNic);
		$state = ($rowNic["id_ActionType"]==1 or $rowNic["id_ActionType"]==3)?1:2;
//		echo "<b> c ".$rowNic["Date_start"]." состояние - ".($state==2?"не ":"")."активен</b>";
		$q_s .= "'".$rowNic["Date_start"]."',`Date_end_st`='".$rowNic["Date_end"]."',`Date_pay`='".$rowNic["Date_end"].
				"',id_tarifab=1,`state`=$state where`Nic`='".$row["Nic"]."'";
//+++++++++++++++++++++++++++++++
		$dp = strtotime($rowNic["Date_end"]);
		$Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
		$q_ins_noti = "insert into `notify_repair` (id_p,fl,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn,Notify) ".
						"values (".$row['id_Podjezd'].",".$row['flat'].",".$_REQUEST ["tn"].",".$rowNic['Bill_Dog'].",'".$row["Nic"]."','".
								date("Y-m-d H:i:s")."','".$Date_Plan."',-1,'откл.(долг)')";
		$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		if ($state == 2) /* отключён */ { /* заявка на подключение */
			$q_ins_noti = "insert into `notify_repair` (id_p,fl,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn,Notify) ".
							"values (".$row['id_Podjezd'].",".$row['flat'].",".$_REQUEST ["tn"].",".$rowNic['Bill_Dog'].",'".$row["Nic"]."','".
									date("Y-m-d H:i:s")."','".$rowNic["Date_end"]."',1,'подкл.(замор.)')";
			$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		}
//+++++++++++++++++++++++++++++++
	} else { /* нет платежей */
		$q_s .= "null,`Date_end_st`=null,`Date_pay`=null,id_tarifab=0,`state`=null,`inet`=1 where`Nic`='".$row["Nic"]."'";
		echo " - платежей не было";
	}
	$q_upd = mysql_query($q_s) or die(mysql_error()); //nics
	echo "заявка на откл испр.";
//	} while ($row = mysql_fetch_assoc($res));
//	echo "работа закончена";
}
//#################################################################################################################

function fill_actions($v_from)
{
/*  $d_tmp = mysql_pconnect("localhost","","");
  mysql_select_db("Selena");	*/
/*	$q_db = "insert into actions (Nic,Login,ActionYear,ActionMonth,Summa,id_ActionType)
                  		 select a.Nic,a.Nic,2007,".$i.",a.".$i.",1 from pays_frm as a";	*/
echo "<br>Обработка ".$v_from;
$a_type = array("отп"=>4, "отк"=>4, "акция"=>3);
$monthname= Array(0=>"декабрь",1=>"январь",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");
$monthDays= Array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
$tod = strtotime("now");
$t_d = "'2010-10-17'"; 	//"'".date("Y-m-d")."'";
$y_ = 0;
//for ($j=1; $i <= 12; $i++) { //
  for ($i=0; $i <= 36; $i++) { //
  	if ($i==0) {
		$y = '2009';		$i_=12;
	} else if ($i>0 and $i<13) {
		$y = '2010';		$i_=$i;
	} else if ($i>12 and $i<25) {
		$y = '2011';		$i_=$i-12;
	} else {
		$y = '2012';		$i_=$i-24;
	}
	if ($y_!=$y) {
		$y_ = $y;		echo "<br>", $y;
	}
  	echo "<br>".$monthname[$i_], ": ";
	$s_d = "'".$y."-".$i_."-01'";
	$e_d = "'".date("Y-m-d", mktime(0, 0, 0, $i_+1, 0, $y))."'";
 	$i_d = $t_d;	//$tod > strtotime($y."-".$i_."-01")?$s_d:$t_d;
	
	echo "Платежи ...";		
	$q_db = "insert into actions (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,Summa,id_ActionType)
                  		 select 2,a.Bill_Dog,a.Nic,a.Nic,$i_d,$s_d,$e_d,a.".$i.",1 from ".$v_from." as a where a.".$i.">0";
  	$res = mysql_query($q_db) or die(mysql_error());

	echo "Акции ...";
	$q_db = "insert into actions (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment)
                select 2,a.Bill_Dog,a.Nic,a.Nic,$i_d,$s_d,$e_d,a.".$i.",3,a.".$i." from ".$v_from." as a where a.".$i."='акция'";
  	$res = mysql_query($q_db) or die(mysql_error());

	echo "Отключения ...";
	$q_db = "insert into actions (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment)
                select 2,a.Bill_Dog,a.Nic,a.Nic,$i_d,$s_d,$e_d,a.".$i.",4,a.".$i." from ".$v_from." as a where INSTR(a.".
				$i.",'полом')>0 or INSTR(a.".$i.",'отк')>0 or INSTR(a.".$i.",'отп')>0 or INSTR(a.".$i.", 'растор')>0 
				or INSTR(a.".$i.", 'ненадоб')>0";
  	$res = mysql_query($q_db) or die(mysql_error());

	echo "Коменты ...";
	$q_db = "insert into actions (TabNum,Bill_Dog,Nic,Login,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment)
                select 2,a.Bill_Dog,a.Nic,a.Nic,$i_d,$s_d,$e_d,a.".$i.",1,a.".$i." from ".$v_from." as a 
				where a.".$i."*1=0 and a.".$i."<>'' and a.".$i."<>'отп' and a.".$i."<>'акция' and 
					INSTR(a.".$i.",'отк')=0 and INSTR(a.".$i.",'полом')=0 and INSTR(a.".$i.",'растор')=0";
  	$res = mysql_query($q_db) or die(mysql_error());
	}
};

function StateAnaliz()
{
echo "</br>Start</br>";
//	$res = mysql_query("select * from actions WHERE Nic >= '''' order by Nic, Date_start") or die(mysql_error());
	$res = mysql_query("select * from customers WHERE Nic >= '''' limit 0,9000") or die(mysql_error()); //nics
	$totalRows = mysql_num_rows($res);//, "</br>"
	$row = mysql_fetch_assoc($res);
	$i=0;
	do {
		$i++; echo "$i Nic =".$row["Nic"];//	echo , " ", $row["Nic"];."</br>"
		$resNic = mysql_query("select * from actions WHERE Nic ='".$row["Nic"]."' order by Date_start") or die(mysql_error());	// DESC
		if (mysql_num_rows($resNic)>0) {
			$qMaxMin = mysql_query("SELECT Max(Summa) AS Max, Min(Summa) AS Min from actions WHERE actions.Nic = '".$row["Nic"]."'") or die(mysql_error());
			$MaxMin = mysql_fetch_assoc($qMaxMin);
			$OplPred = 1;
			$rowNic1 = mysql_fetch_assoc($resNic);
			$D_st1 = $rowNic1["Date_start"];
			$D_en1 = $rowNic1["Date_end"];
			$D_in1 = $rowNic1["InputDate"];
			while ($rowNic = mysql_fetch_assoc($resNic))  {   
				$D_st = $rowNic["Date_start"];
				$D_en = $rowNic["Date_end"];
				$D_in = $rowNic["InputDate"];
				if ($rowNic["id_ActionType"]==1/*абонплата*/) {
					if ($rowNic["Summa"]==$MaxMin["Max"]/*абонплата за весь месяц */) {
						$q_s = "update actions set `Date_start`='".$D_st."',`Date_end`='".$D_en."',`Date_pay`='".$D_en."' where`Nic`='".$row["Nic"]."' and InputDate='".$D_in."'";
					} else {/* абонплата за ЧАСТЬ месяця */
						if ($OplPred) {/* предыдущий период оплачен, оплата с начала месяца */
							$Date_start = "`Date_start`='".$D_st."'";
							$Date_end = "`Date_end`='".date("Y-m-d",mktime(0,0,0,date("t",strtotime($D_en)),round($rowNic["Summa"]/$MaxMin["Max"]*date("t",strtotime($D_en)),0), date("Y",strtotime($D_en))))."'";
							$ds = '';
							$de = '';
						} else {/* предыдущий период ОТКЛ, оплата до конца месяца */
							$Date_start = "`Date_start`='".date("Y-m-d",mktime(0,0,0,date("t",strtotime($D_st)),round($rowNic["Summa"]/$MaxMin["Max"]*date("t",strtotime($D_st)),0), date("Y",strtotime($D_st))));
							$Date_end = "`Date_end`='".$rowNic["Date_end"];
						}
						$q_s = "update actions set $Date_start,$Date_end,`Date_pay`='".$D_en."' where`Nic`='".$row["Nic"]."' and InputDate='".$D_in."'";
					}
					$OplPred = 1;
				} else if ($row["id_ActionType"]==3/*акция*/) {
				} else if ($row["id_ActionType"]==4/*заморозить*/) {
					if ($rowNic["Summa"]==$MaxMin["Max"]/*абонплата за весь месяц */) {
						$q_s = "update actions set `Date_start`='".$D_st."',`Date_end`='".$D_en."',`Date_pay`='".$D_en."' where`Nic`='".$row["Nic"]."' and InputDate='".$D_in."'";
					} else {/* абонплата за ЧАСТЬ месяця */
						if ($OplPred) {/* предыдущий период оплачен, оплата с начала месяца */
							$Date_start = "`Date_start`='".$D_st."'";
							$Date_end = "`Date_end`='".date("Y-m-d",mktime(0,0,0,date("t",strtotime($D_en)),round($rowNic["Summa"]/$MaxMin["Max"]*date("t",strtotime($D_en)),0), date("Y",strtotime($D_en))))."'";
							$q_s = "update actions set $Date_start_st,$Date_end_st,`Date_pay`='".$D_en."' where`Nic`='".$row["Nic"]."' and InputDate='".$D_in."'";
						} else {/* предыдущий период ОТКЛ, оплата до конца месяца */
							$Date_start = "`Date_start`='".date("Y-m-d",mktime(0,0,0,date("t",strtotime($D_st)),round($rowNic["Summa"]/$MaxMin["Max"]*date("t",strtotime($D_st)),0), date("Y",strtotime($D_st))));
							$Date_end = "`Date_end`='".$rowNic["Date_end"];
						}
					}
					$OplPred = 1;
				} 
			}
		}
		echo "</br>";
	} while ($row = mysql_fetch_assoc($res));
	echo "работа закончена";
}

function f1($st)
{
echo $st,"<br>";
$res = mysql_query($st) or die(mysql_error());
}
?>
<div id="pay_table">&nbsp;</div>