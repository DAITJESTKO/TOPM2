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

$id_p		= $_REQUEST ["id_p"];
$fl			= $_REQUEST ["fl"];
$TabNum  	= $_REQUEST ["TabNum"];
$abon_Com 	= $_REQUEST ["abon_Com"];
$nums		= $_REQUEST ["nums"];

for($i=1; $i<=$nums; $i++){
	$Bill_Dog  	= $_REQUEST ["bd$i"];
	$abon 		= $_REQUEST ["ab$i"];
	$action 	= $_REQUEST ["act$i"];
	$Date_start = $_REQUEST ["ds$i"];
	$Date_end 	= $_REQUEST ["de$i"];
	$nDateAct 	= $_REQUEST ["nda$i"];
	$auto 		= $_REQUEST ["auto$i"];
	$Nic 		= $_REQUEST ["Nic$i"];
	$dolg		= isset($_REQUEST ["dolg$i"]);
	if ($dolg) {
		$s_dolg	= $_REQUEST ["s_dolg$i"]; // *1 + $auto>0?0:100;
		$d_off	= $_REQUEST ["d_off$i"];
		$c_dolg = $_REQUEST ["c_dolg$i"];
		$s_dolg	+=  $auto>0?0:100;
	} else {
		$s_dolg	= 0;
		$d_off	= '';
		$c_dolg = '';
	}
	pay($id_p, $fl, $TabNum, $abon_Com, $Bill_Dog, $Nic, $abon, $action, $Date_start, $Date_end, $nDateAct, $dolg, $s_dolg, $d_off, $c_dolg);
}
//*********************	
	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value=document.forms.ulaForm.h_Cod_flat.value; '.
			'document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');'.
			'" value="Обновить"/>';	//		//document.getElementById(\'B_chk_adress\').onClick();	ch(\'ch_flt\',\'menu=pay&\',2,\'tab_Cust\');
//*********************		//	*********************	//	*********************

function pay($id_p, $fl, $TabNum, $abon_Com, $Bill_Dog, $Nic, $abon, $action, $Date_start, $Date_end, $nDateAct, $dolg, $s_dolg, $d_off, $c_dolg)
{
$Y = date("Y");
$now		= date("Y-m-d H:i:s");	
	echo "По договору $Bill_Dog:</br>";//date("Y",$nDateAct)+0;
	//*********************	
	$cod = get_Cod_flat($id_p, $fl);
	if ($cod==0) { $cod = get_cod($Bill_Dog); }
	if ($dolg) {	//date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))
		put_noti2conn ('1', $c_dolg, $Bill_Dog, $cod, $id_p, $fl, $TabNum);
	//*********************	
		if ($s_dolg>0) {
			echo "Информация об оплате долга ... ";//Date_end
			$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
				"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_start','$d_off',$s_dolg,1,'опл.долга и переподкл')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
				"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_start','$d_off',$s_dolg,1,'опл.долга и переподкл')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			echo "Добавлена.</br>";
		}
//		put_noti2off ($_REQUEST["Date_pay"], $Bill_Dog, $cod, $id_p, $fl, $TabNum);

		echo "Окончание откл. остояния абонента ... ";
		$q_stt = mysql_query("update `customers` set Date_end_st='$c_dolg' where Bill_Dog=$Bill_Dog") or die(mysql_error());
		echo "установлено в $c_dolg.</br>";

	}
	//*********************	

	$dp = strtotime($nDateAct);  $Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
if ($abon !=0) {
	echo "Срок в заявке на отключение ... ";//$Bill_Dog
//	$q_DP = mysql_query("select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` where `Date_Fact` IS NULL and Bill_Dog=$Bill_Dog and conn=-1 and Notify='откл.(долг)' order by `Date_Plan` desc")
	//				or die(mysql_error());
	$q_DP = mysql_query("select `Date_Plan` from `notify_repair` where `Date_Fact` IS NULL and Bill_Dog=$Bill_Dog and conn=-1 and Notify='откл.(долг)'")
					or die(mysql_error());
	if(mysql_num_rows($q_DP)>0) {
		$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
		$s_del ="update `notify_repair` set Date_Plan='$Date_Plan' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='{$r_DP['Date_Plan']}'";
		$q_del = mysql_query($s_del) or die(mysql_error());
		echo "перенесён на";//$Date_end
	} else { /* нет заявки на отключение, внести */
		$q_ins_noti = "insert into `notify_repair` (id_p,fl,Cod_flat,TabNum,Bill_Dog,Date_in,Date_Plan,conn,Notify) ".
						"values ($id_p,$fl,$cod,$TabNum,$Bill_Dog,'".date("Y-m-d H:i:s")."','$Date_Plan',-1,'откл.(долг)')";//,Nic,'$Nic'
		$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
		echo "установлен в";
	}
	echo " $Date_Plan.</br>";
	
//*********************	
	echo "Изменения в данные о подключении к сети... ";//Saldo=".($_REQUEST ["abon"]+$row_abon["Saldo"])."
	$q_cor_abon = "update `customers` set Date_end_st='$nDateAct' where Bill_Dog=$Bill_Dog and state=1 and Date_end_st=Date_pay";
	$s_cor_abon =  mysql_query($q_cor_abon) or die(mysql_error());// Date_start_st='$Date_start', Date_end_st='$to_Date_end',

 	$q_cor_abon = "update `customers` set Date_pay='$nDateAct' where Bill_Dog=$Bill_Dog";//
	$s_cor_abon =  mysql_query($q_cor_abon) or die(mysql_error());// Date_start_st='$Date_start', Date_end_st='$to_Date_end',
	echo "Внесены.</br>";	//	 state=1,
	
//*********************	
	echo "Информация о платеже абонплаты ... ";//,Nic,'$Nic'
	if ($dolg) {
	//	$dp = strtotime($d_off); $Date_start = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
		$Date_start = $c_dolg;
	}
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'".date("Y-m-d H:i:s")."','$Date_start','$Date_end',$abon,1,'$abon_Com')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'".date("Y-m-d H:i:s")."','$Date_start','$Date_end',$abon,1,'$abon_Com')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
	echo "Добавлена.</br>";
	
//*********************	
		if ($action > 0) {
			$dp = strtotime($Date_end); $Date_s = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
			echo "Информация о предоставлении $action мес. по акции ... ";
			$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
							"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_s','$nDateAct',0,3,'по акции $action мес.')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
							"values ($TabNum,$Bill_Dog,'$Nic','".date("Y-m-d H:i:s")."','$Date_s','$nDateAct',0,3,'по акции $action мес.')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			echo "Добавлена.</br>";
		}
	}
//*********************	
}

function set_color ($sum) {
	return '<font color="'.(($sum<0)?'FF0000':'0033FF').'"><b>'.$sum.'</b></font>';
}
?>