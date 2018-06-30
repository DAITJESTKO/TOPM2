<?
require_once("for_form.php"); 
check_valid_user();
  $db_conn = db_connect();
  if (!$db_conn) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");

//	$id_p  = $_REQUEST ["id_p"];
//	$fl  = $_REQUEST ["fl"];
//	$conn  = $_REQUEST ["conn"];
	$comment  = $_REQUEST ["com"];
	$Notify = $_REQUEST ["Notify"];	//get_param("Notify");
	$Date_Fact = $_REQUEST ["Date_Fact"];	//get_param("Date_Fact");
	$TabNum = $_REQUEST ["tn"];
	$Y = date("Y");
//	$Date_Plan = $_REQUEST ["Date_Plan"];	//get_param("Date_Plan");
//	$Cod_flat = $_REQUEST ["Cod_flat"];	//get_param("Cod_flat");
//	$Date_in = $_REQUEST ["Date_in"];	//get_param("Date_in");
//	$mont  = $_REQUEST ["mont"];
//	$phone_Dop  = $_REQUEST ["phone_Dop"];
/*
if (isset($_REQUEST ["Cod_flat"])) {
	$q_cor_Cod_Flat = "update `customers` set Cod_flat=".$Cod_flat." where ".$id_p." and flat=".$fl;
	echo $q_cor_Cod_Flat."</br>";
	$s_cor_Cod_Flat =  mysql_query($q_cor_Cod_Flat) or die(mysql_error());
	echo "Адресу присвоен код $Cod_flat <br>";
} else {
	$q_cor_Cod_Flat = "select Cod_flat from `customers` where ".$id_p." and flat=".$fl;
	echo $q_cor_Cod_Flat."</br>";
	$s_cor_Cod_Flat =  mysql_query($q_cor_Cod_Flat) or die(mysql_error());
	$row_Cod_Flat = mysql_fetch_array($s_cor_Cod_Flat, MYSQL_ASSOC);
	$Cod_Flat = " Cod_flat=".$row_Cod_Flat["Cod_flat"];	//.", "
}
*/
	$s_col = "";								$v_col = ""; //Cod_flat, Bill_Dog,		$Cod_flat, $Bill_Dog,
//	if (strlen($)>0) {$s_col = $s_col." ,";						$v_col = $v_col." '$',";}
//	if (strlen($Notify)>0) {$s_col = $s_col." Notify,";			$v_col = $v_col." '$Notify',";}
//	if (strlen($Date_Plan)>0) {$s_col = $s_col." Date_Plan,";	$v_col = $v_col." '$Date_Plan',";}
	if (strlen($Date_Fact)>0) {$s_col = $s_col." Date_Fact,"; 	$v_col = $v_col." '$Date_Fact'";}
//	if (strlen($Date_in)>0) {$s_col = $s_col." Date_in,"; 		$v_col = $v_col." '$Date_in',";}
//	if (strlen($TabNum)>0) {$s_col = $s_col." TabNum,"; 		$v_col = $v_col." '$TabNum',";}
//	if (strlen($mont)>0) {$s_col = $s_col." mont,"; 			$v_col = $v_col." '$mont',";}
//	if (strlen($phone_Dop)>0) {$s_col = $s_col." phone_Dop,";	$v_col = $v_col." '$phone_Dop',";}

//	$s_col = $s_col. " id_p, fl";
//	$v_col = $v_col. " $id_p, $fl";

// Получение данных заявки
	$q_not = mysql_query("select * from `notify_repair` where Num_Notify = $Notify") or die(mysql_error());//``
	$r_not = mysql_fetch_array($q_not, MYSQL_ASSOC);
	$Cod_flat_v = $r_not['Cod_flat']==0?get_Cod_flat($id_p=$r_not['id_p'], $fl=$r_not['fl']).",":"";
	$Cod_flat_s = empty($Cod_flat_v)?"":("Cod_flat,");
	$Cod_flat   = empty($Cod_flat_v)?"":("Cod_flat=".$Cod_flat_v);
	$Bill_Dog  = $r_not["Bill_Dog"];
	$Date_in = $r_not['Date_in'];
	$Date_Plan = $r_not['Date_Plan'];
	$conn = $r_not['conn'];
//	$Cod_flat = $r_not['Cod_flat'];
	
// Изменение даты в заявке
	$q_noti = "update notify_repair set $Cod_flat Date_Fact='$Date_Fact',comment='$comment',mont=$TabNum,Date_ed='".
					date("Y-m-d H:i:s")."' where Num_Notify = $Notify";
	$s_noti =  mysql_query($q_noti) or die(mysql_error());
	
//	Отключение при смене адреса. Нужна только отметка в заявке, больше ничего.	№№№№№№№№№№№№№№№№№№№№№№№№№№№№№№№
	if ($r_not["Notify"]=='откл.(смена адр.)') { echo "<img src='create_check.gif'/>"; return; }	//№№№№№№№№№№
	
// Изменение даты в таблице ников
	$q_nic = mysql_query("select * from `customers` where `Bill_Dog`=$Bill_Dog") or die(mysql_error());//``
	$r_nic = mysql_fetch_array($q_nic, MYSQL_ASSOC);

$df_ = strtotime($Date_Fact);	$dp_ = strtotime($Date_Plan);
$Period = $df_>$dp_?($df_ - $dp_):0;
$PeriodD = $Period/60/60/24;
$dp1 = strtotime($r_nic["Date_pay"]); $Date_pay = date("Y-m-d",mktime(0,0,0,date("m",$dp1),date("d",$dp1)+$PeriodD,date("Y",$dp1)));
$dp = strtotime($Date_pay); 		 $Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
$Date_start_st = $Date_Fact;
if (1*$conn>=0 && 1*$conn<7 ) { // Изменение подключения	conn = 0	-	ремонт сети
	$state = 1;
	$Date_end_st = $Date_pay;
	if ($PeriodD > 3) {
		$Date_pay1 = date("Y-m-d",mktime(0,0,0,date("m",$dp1),date("d",$dp1)+1,date("Y",$dp1)));
		echo "Длительность выполнения заявки- $PeriodD дн. (больше 3-х).</br>";
		$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'".date("Y-m-d H:i:s")."','$Date_pay1','$Date_pay',0,1,'+$PeriodD дн.')";
		$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
		$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'".date("Y-m-d H:i:s")."','$Date_pay1','$Date_pay',0,1,'+$PeriodD дн.')";
		$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
		echo "Заявка на отключение за долг на ... ";
		$q_DP = mysql_query("select Num_Notify as nn from notify_repair where Bill_Dog=$Bill_Dog and conn=-1 ".
						"and Date_Fact IS NULL and Notify='откл.(долг)'") or die(mysql_error());
		if(mysql_num_rows($q_DP)>0) {
			$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
			$s_noti ="update notify_repair set $Cod_flat Date_Plan='$Date_Plan' where Num_Notify=".$r_DP["nn"];
		} else {
			$s_noti = "insert into `notify_repair` ($Cod_flat_s id_p,fl,TabNum,Bill_Dog,Date_in,Date_Plan,conn) ".
							"values ($Cod_flat_v $id_p,$fl,$TabNum,$Bill_Dog,'".date("Y-m-d H:i:s")."','$Date_Plan',-1)";
		}
		$q_noti = mysql_query($s_noti) or die(mysql_error());
		echo "$Date_Plan.</br>";
	}
//	$Date_end_st = date("Y-m-d", strtotime($r_nic['Date_end_st']) + strtotime($Date_Fact) - strtotime($r_nic['Date_start_st']) );
	$q_nics="update customers set state=$state,Date_pay='$Date_pay',Date_start_st='$Date_start_st',Date_end_st='$Date_end_st' where Bill_Dog=$Bill_Dog";
} else {	// conn < 0	-	отключить
	$state = 2;
	$q_DP = mysql_query("select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` ".
			"where Bill_Dog=$Bill_Dog and conn=1 and Date_Fact IS NULL order by `Date_Plan` desc") or die(mysql_error());
	if(mysql_num_rows($q_DP)>0) {
		$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
		$Date_end_st = $r_DP["Max_Date_Plan"];
	} else {
		$Date_end_st = strtotime($r_nic['Date_pay'])>strtotime($Date_start_st)?$r_nic['Date_pay']:'';
	}
	$Date_end_st = (empty($Date_end_st)?"null":"'$Date_end_st'");
	$q_nics = "update customers set state=$state, Date_start_st='$Date_start_st', Date_end_st=$Date_end_st ".
			"where `Bill_Dog`=$Bill_Dog";
}
//$q_nics = "update customers set state=$state,Date_start_st='$Date_start_st',Date_end_st=$Date_end_st where `Bill_Dog`=$Bill_Dog";
$s_nics =  mysql_query($q_nics) or die(mysql_error());
echo "<img src='create_check.gif'/>";

function get_param ($name) {
	$$name = $_REQUEST [$name];
	return (($$name=="")? " " : " ".$name."='".$$name."',");
}
?>