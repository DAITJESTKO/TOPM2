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

	$TabNum  		=  $_REQUEST ["TabNum"];
	$Bill_Dog 		= $_REQUEST ["Bill_Dog"];
	$Comment 		= $_REQUEST ["Comment"];
	$Date_start_fr 	= $_REQUEST ["Date_start_fr"];
	$Date_end_fr 	= $_REQUEST ["Date_end_fr"];
	$new_Date_end 	= $_REQUEST ["new_Date_end"];
	$Date_in 		= date("Y-m-d H:i:s");	//$DFrz = strtotime($Date_start_fr);
	$f_prd			= strtotime($Date_end_fr) - strtotime($Date_start_fr);
	
//*********************	
	$q_abon = "select * from `v_customer` where Bill_Dog=$Bill_Dog";//	`Saldo`	." and Flat=".$fl
	$r_abon =  mysql_query($q_abon) or die(mysql_error());
	$row_abon = mysql_fetch_array($r_abon, MYSQL_ASSOC);
//	$new = $row_abon["Date_end_st"];
	
//*********************	!!!!
	$Date_pay = date("Y-m-d", strtotime($row_abon['Date_pay']) + $f_prd );
	echo "Изменение оплаченной даты на $Date_pay ... ";	//,$row_abon['Date_pay']," + ",$Date_end_fr," - ",$Date_start_fr, " = ",$Date_pay;
	$Date_end_st = date("Y-m-d", strtotime($Date_start_fr." -1 day") ); //-1 day// ) - 1 );
	$q_cor_nics = "update `customers` set Date_pay='$Date_pay' ".($row_abon['state']==1?",Date_end_st='$Date_end_st'":"")." where Bill_Dog=$Bill_Dog";//		.'</br>' state=2, Date_end_st='$new_Date_end'
	$s_cor_nics =  mysql_query($q_cor_nics) or die(mysql_error());	// echo "Ошибка при обновлении подключения к сети.\n";
	echo "внесено.</br>";

// Изменение даты в заявке на отключение по ДОЛГу
	$q_DP = mysql_query("select Date_Plan, Num_Notify from `notify_repair` where Bill_Dog=$Bill_Dog and conn=-1 and Date_Fact is null and Notify='откл.(долг)'") or die(mysql_error());// order by `Date_Plan` desc
	if(mysql_num_rows($q_DP)>0) {
		$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
		$Date_Plan = date("Y-m-d", strtotime($r_DP["Date_Plan"]) + $f_prd );
		echo "Изменение даты отключения за долг на $Date_Plan ... ";
		$Num_Notify = $r_DP["Num_Notify"];
		$q_noti = "update `notify_repair` set Date_Plan='$Date_Plan' where Num_Notify = $Num_Notify";
		$s_noti =  mysql_query($q_noti) or die(mysql_error());
		echo "внесено.</br>";
	}

//*********************	
	echo "Информация о процедуре ... ";
	$Y = date("Y");
	$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Date_in','$Date_start_fr','$Date_end_fr',0,4,'$Comment')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die($q_ins_abon." ".mysql_error());
	$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
					"values ($TabNum,$Bill_Dog,'$Date_in','$Date_start_fr','$Date_end_fr',0,4,'$Comment')";//		.'</br>'
	$s_ins_abon =  mysql_query($q_ins_abon) or die($q_ins_abon." ".mysql_error());
	echo "Добавлена.</br>";
	
//*********************	Заявки на откл/подкл
	$Notify1 = "откл.(замороз)";		$Date_Plan_1 = $Date_start_fr; //date("Y-m-d", mktime(0,0,0,date("m", $DFrz),date("d", $DFrz)+3,date("Y", $DFrz)));
	$Notify2 = "подкл.(замороз)";		$Date_Plan_2 = $Date_end_fr; //date("Y-m-d", mktime(0,0,0,date("m", $DFrz),date("d", $DFrz)+3,date("Y", $DFrz)));
	
	$s_col = "Cod_flat, Bill_Dog, Date_in, TabNum,";			$v_col = $row_abon["Cod_flat"].",".$_REQUEST["Bill_Dog"].",'$Date_in','$TabNum',";
	$s_col = $s_col. " id_p, fl";								$v_col = $v_col. " ".$row_abon["id_Podjezd"].", ".$row_abon["flat"];	//_REQUEST ["id_p"]		_REQUEST ["fl"]

	echo "Заявка на отключение абонента, с плановой датой исполнения:$Date_Plan_1... ";
	$q_ins_noti = "insert into `notify_repair` (Date_Plan, conn, Notify, ".$s_col.") values ('$Date_Plan_1', -1, '$Notify1', ".$v_col.")";
	$s_ins_noti =  mysql_query($q_ins_noti) or die($q_ins_noti." ".mysql_error());
	echo "Внесена.</br>";
	
	echo "Заявка на подключение абонента, с плановой датой исполнения:$Date_Plan_2... ";
	$q_ins_noti = "insert into `notify_repair` (Date_Plan, conn, Notify, ".$s_col.") values ('$Date_Plan_2', 1, '$Notify2', ".$v_col.")";
	$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
	echo "Внесена.</br>";
//*********************	

//----------------------------------------------------------------------------------	
//*********************	
//	echo '    <input name="B_chk_adress" type="button" onclick="ch(&quot;ch_flt&quot;,&quot;menu=pay&&quot;,2,&quot;tab_Cust&quot;); '.
//			'document.getElementById(&quot;B_Sub&quot;).innerHTML = &quot; &quot;;" value="Обновить"/>';
	echo '    <input name="B_chk_adress1" type="button" onclick="document.forms.ulaForm.sCod_flat.value=document.forms.ulaForm.h_Cod_flat.value; '.
			'document.getElementById(\'B_Sub\').innerHTML = \'\';srch(\'Cod_flat\');'.
			'" value="Обновить"/>';
?>