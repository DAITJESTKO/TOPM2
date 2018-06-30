<? // Проверка на ИП адрес, дополнительная защита, ИП адреса лучше лишний раз уточнить у Бибгаева, они могли поменяться
$ip = getenv("REMOTE_ADDR");
$ok='1';
$deb = 0;

//if ($ip=='81.176.214.110'){$ok ='1';}
//if ($ip=='81.176.214.107'){$ok ='1';}
if ($ip=='10.1.90.26'){$ok ='1'; $deb = 1; }
if ($ip=='10.1.253.6'){$ok ='1'; $deb = 1; }
if ($ip=='10.1.253.22'){$ok ='1'; $deb = 1; }
if ($ip=='127.0.0.1'){$ok ='1'; $deb = 1; }
//$deb = 0;

	fdebug("IP - Ok!<br>");
//

if ($ok=='1'){

	fdebug("подключаем библиотеки<br>");
	require_once("db_fns.php");
	require_once("user_auth_fns.php");
	$conn = db_connect();
	if (!$conn)			// Нет связи с базой	//##     В Ы Х О Д    ##//
			Go_Out($txn_id, 1); // return 0;

//	$account =  $account;
//	$account =  preg_match("^\d{1,20}$", $account)?$account:0;
//	if ($account == 0) {}
	fdebug("получение переменных<br>");
	fdebug("account=".$account=	intval(HACK($_REQUEST ["account"]))); // G8AB:0 ?5@5<5==>9 >B >?0A=KE A8<2>;>2
	fdebug("txn_id =".$txn_id =  $_REQUEST ["txn_id"]);
	fdebug("trm_id =".$trm_id =  isset($_REQUEST ["trm_id"])?$_REQUEST ["trm_id"]:"");
	fdebug("prv_id =".$prv_id =  isset($_REQUEST ["prv_id"])?$_REQUEST ["prv_id"]:0);
	fdebug("sum 	=".$sum 	=  HACK($_REQUEST ["sum"]));
	fdebug("command =".$command =  $_REQUEST ["command"]);
	fdebug("now =".$now = "'".date("Y-m-d H:i:s")."'");
	fdebug("fp =".$fp = "");
	fdebug("<br>");
$Y = date("Y");
	
	//***********************	есть ли запись с номером $txn_id
	fdebug("есть ли запись с номером $txn_id<br>");
	$prv_txn = get_new_prv_txn();
	$r_txn = get_prv_txn($txn_id);		//	print_r($r_txn);

	fdebug(date("Y-m-d H:i:s")."<br>");
	fdebug("запись с номером txn_id=$txn_id ".(($cr = is_txn_id($txn_id))?"есть<br>":"отсутствует, "));
	if ($cr) { // уже есть запись с таким номером txn_id
		fdebug("повторный запрос по txn_id=$txn_id<br>");
		$q_upd = "update `t_abon` set coun = ".$r_txn["coun"]."+1 where txn_id=$txn_id";
		$r_upd =  mysql_query($q_upd) or die(mysql_error());
		if (($r_txn["result"] == 90 || $r_txn["result"] == 1) && $r_txn["error"] == 0) { // Проведение платежа не окончено
			fdebug("платёж не был завершён"."<br>");
			$to_resp = out_resp($txn_id, 0);
		} else {
			fdebug("платёж ".($r_txn["error"] > 0?"закончился ошибкой":"был завершён")."<br>");
			//##     В Ы Х О Д    ##//
			Go_Out($txn_id, 0);
		}
	} else {
		//***********************	создаём запись с txn_id=$txn_id ...
		fdebug("создаём запись с txn_id=$txn_id ...");
		if ($err_acc = preg_match("/\D/", $account)) { // счёт имеет другие символы
			//***********************	Ошибка ! в номере счёта есть не цифры! error = 2
			//	fdebug( "цифры, длина от 1 до 20:". preg_match("/\d{1,20}/", $account). "<br>");
			fdebug( "<br>Ошибка ! в номере счёта есть не цифры!<br>");
			//	command=check&txn_id=9715351212001&account=xobet35&sum=104.50&prv_id=29145&sum_from=110.00
			$q_ins = "insert into `t_abon` (d_time, account, txn_id, prv_txn, sum, result, prv_id, coun, error) ".
							"values ($now,'{$account}', $txn_id, ".$prv_txn.", $sum, 0, $prv_id, 1, 2)"	;//echo,'</br>'   '".$res."'
			$s_ins_inet =  mysql_query($q_ins) or die(mysql_error());
			fdebug("готово<br>");
			//##     В Ы Х О Д    ##//
			Go_Out($txn_id, 0);
		}
		$q_ins = "insert into `t_abon` (d_time, account, txn_id, prv_txn, sum, result, prv_id, coun) ".
						"values ($now, $account, $txn_id, ".$prv_txn.", $sum, 90, $prv_id, 1)"	;//echo,'</br>'         '".$res."'
		$s_ins_inet =  mysql_query($q_ins) or die(mysql_error());
		fdebug("готово<br>");
	}

//##################################################################################################################################
//	require_once("bookmark_fns.php");
	$conn = db_connect();
	if (!$conn) return 0;
	$Bill_Dog = $account;
	$q_s = "select Bill_Dog, Cod_flat, id_Podjezd, flat, inet from customers where Bill_Dog=$Bill_Dog";
	$res =  mysql_query($q_s) or die(mysql_error());
	if (mysql_num_rows($res)==0) {
		fdebug("пользователь отсутстствует<br>");
		put_er($txn_id, 1);
			//##     В Ы Х О Д    ##//
			Go_Out($txn_id, 0);
	}
	$rowadr = mysql_fetch_array($res, MYSQL_ASSOC);
	$adr = '';
	if ($rowadr["Cod_flat"]>0) {
		$adr = "Cod_flat=".$rowadr["Cod_flat"];
	} elseif ($rowadr["id_Podjezd"]>0 && $rowadr["flat"]>0) {
		$adr = "id_Podjezd=".$rowadr["id_Podjezd"]." and flat=".$rowadr["flat"];
	}
	if ($adr=='') {		// Записать в базу ошибочных платежей
//		put_er($account, $txn_id, $sum, 'ошб в адресе');
		fdebug("ошиб в адресе<br>");
		put_er($txn_id, 6);
			//##     В Ы Х О Д    ##//
			Go_Out($txn_id, 0);
	} elseif ($rowadr["inet"]!='') {		// Записать в базу ошибочных платежей
//		put_er($account, $txn_id, $sum, 'инет учётка');
		fdebug("инет учётка<br>");
		put_er($txn_id, 7);
			//##     В Ы Х О Д    ##//
			Go_Out($txn_id, 0);
	} else {
		$q_s = "select * from v_customer where $adr";	//Bill_Dog=$Bill_Dog
		$res =  mysql_query($q_s) or die(mysql_error());
	if (mysql_num_rows($res)==0) {
		fdebug("пользователь не найден по адресу $adr<br>");
		put_er($txn_id, 1);
			//##     В Ы Х О Д    ##//
			Go_Out($txn_id, 0);
	}
		$row = mysql_fetch_array($res, MYSQL_ASSOC);
	
			$Bill_Dog1 = $row['Bill_Dog'];
			$inet1 = $row['inet'];
			$Nic = $row['Nic'];
			$ab_numbs = 0;
			$ab_sum = $row['ab_sum']>0?$row['ab_sum']:0;
			$is_err = '';
			$i = 0;
			do {
				$i +=1;
				$Bill_Dog = $row['Bill_Dog'];
				fdebug("Договор $Bill_Dog<br>");
			//	$Fio = $row['Fam'].' '.$row['Name'].' '.$row['Father'];
			//	$Y = date("Y", strtotime($row["Date_start_st"]));
				if ($ab_sum==0) { if (!$row['inet']) { $ab_sum = $row['ab_sum']; }}
				$GLOBALS['arr_cust'][$Bill_Dog] = $row;
				$GLOBALS['arr_cust'][$Bill_Dog]['dolg'] = is_off_dolg($Bill_Dog);
				if ($GLOBALS['arr_cust'][$Bill_Dog]['dolg'] && $row['state']!=2) {
					$is_err = 'Необх. переоформление';
				}
				$ab_numbs = $ab_numbs + ($row['inet'] || $GLOBALS['arr_cust'][$Bill_Dog]['dolg']?0:1);
/*				echo '<option value='.$Bill_Dog.(($i ==1)?" selected":"").' >';
				echo 'Дог.№'.$Bill_Dog.($row['inet']?"(инет)":"").', '.$ar_s[$row['state']].' '.
					(!empty($row['Date_end_st'])?'по '.date("j ", strtotime($row['Date_end_st'])).$m[date("n", strtotime($row['Date_end_st']))].(date("Y")==$Y?'':' '.$Y.'г.'):
													($GLOBALS['arr_cust'][$Bill_Dog]['dolg']?' за долг':'')). ', ник: '.$row['Nic'].'&#009;: '.$Fio.'</option>';		*/
			} while ($row = mysql_fetch_array($res, MYSQL_ASSOC));
			
			fdebug("всего активных абонентских учёток ".$ab_numbs."<br>");
			$c_ar = $GLOBALS['arr_cust'][$account];
			$auto = $c_ar['mac']!='' && $c_ar['auto']==1;
			$m_ab = isset($ab_numbs)?($c_ar['id_tarifab']==6&&$c_ar['Comment']!=''?$c_ar['Comment']:round($ab_sum/2*(1+1/($ab_numbs>0?$ab_numbs:1)))):'';
			fdebug("абон.плата =".$m_ab."<br>");
			$v_st = $c_ar['state'];
			$d1_st = strtotime($c_ar['Date_start_st']);
			if ($dolg = empty($c_ar['Date_end_st']) && $v_st==2) { // откл за долг
				fdebug("отключен за долг ");
				$s_dolg = round($m_ab/30*(($d1_st - strtotime($c_ar['Date_pay']))/3600/24-1),0);
				$abon_p = $sum - ($s_dolg + ($auto && $v_st==2?0:100));
				fdebug($s_dolg."руб<br>");
			} else {
//				fdebug("не должник<br>");
				$s_dolg = 0;
				$abon_p = $sum;
			}
			$abon_p = $abon_p>0?$abon_p:0;
			$p_dolg=$abon_p>0?$abon_p:$sum;
			$m = round(($p_dolg - ($p_dolg % $m_ab))/$m_ab);
			$d = round(30/$m_ab*($p_dolg % $m_ab));
			
//			$m = ($abon_p - ($abon_p % $m_ab))/$m_ab;
//			$d = round(30/$m_ab*($abon_p % $m_ab));
			fdebug("Суммой {$sum}руб. оплачивается".($dolg?($abon_p>0?" долг и ":" от долга"):"")." {$m}мес. и {$d}дн.<br>");
			$opl_per = $m;
			$d2d = mktime();												//	d2d = сегодня"Y-m-d"
			$d1_ = mktime(0,0,0,date("m"),date("d")+($auto && $v_st==2?0:1),date("Y"));			//	d1_ = сегодня"Y-m-d" + 1
			$d1 = mktime(0,0,0,date("m")+6,date("d")+($auto && $v_st==2?0:1),date("Y"));			//	d1 = сегодня + 6 мес + 1
			$dp = strtotime($c_ar['Date_pay']);								//	Date_pay
			$D_pay = mktime(0,0,0,date("m",$dp),date("d",$dp),date("Y",$dp));
//			fdebug("dolg=$dolg abon_p=$abon_p d1_=$d1_ D_pay=$D_pay<br>");
			$d2_ = $dolg?($abon_p>0?$d1_:$D_pay):$D_pay;					// d2_ = new_start =Date_pay оплачено по
			$da_ = $d2d > $d2_?$d2d:$d2_;									// da_ - дата отсчёта начала акции
			$de_ = date_add_month($da_, 6);		
			$de_ = d_setIfLast($d, $da_, $de_);								//	de_ = дата оплаты для получения акции
			$d2 = mktime(0,0,0,date("m", $d2_)+$m,date("d", $d2_)+$d,date("Y", $d2_));	//	d2 = new_start + Opl_period
			$d2 = d_setIfLast($d, $d2_, $d2);	/* если d=0 и оплачен последний день месяца, то опаченным сделать последний день месяца */
			fdebug("оплачивается по ".d2str($d2).", дата для отсчёта акции ".d2str($da_)." + 6мес. = ".d2str($de_)."<br>");
			$action = $de_ <= $d2 ? round(($d2 - $da_)/3600/24/30/6):0;
			fdebug("месяцев по акции: $action<br>");
			$nDate = d2str($d2);	///Date_Add(f.Date_pay.value, m, d);//Date_end_st
			$d3 = date_add_month($d2, $action);								// d3 - после добавления акции
			$d3 = d_setIfLast($d, $d2, $d3);
			
			$nDateAct = d2str($d3);	///Date_Add(nDate, action, 0);
			$nDateOff = date_add_day($d3, 1); //date_add(d3, "day", 1);// Новая дата для заявки на откл. за долг
	//		n_Date = new Date(f.Date_end_st.value);
//			$new_Date_end = $nDate;
/*			echo "Оплата по "+d2str2($d2)+'<input name="new_Date_end" type="hidden" value="'+$nDate+'"/>';
			echo ($action>0?'+ '+$action+'мес.='+d2str2($d3):'акция н/д.').
				'<input name="action" type="hidden" value="'+$action+'"/>'.
				'<input name="nDateAct" type="hidden" value="'+$nDateAct+'"/>';		*/
	//		}
	//////////////////////////////////////////////////////////////////////////////////////////////
			$Bill_Dog = $account;
			$vtoday = mktime();	//date();
	//		D_st = d2str(date_add(dp==''?time2Y_m_d(vtoday):dp, "day", 1));	//Date_end_st	//time2Y_m_d()
			$D_st = d2str(date_add_day($D_pay==''?$vtoday:$D_pay, 1));	//Date_end_st
			$id_p=$rowadr["id_Podjezd"];
			$fl = $rowadr["flat"];
	/*************************************************************
			s_param = "id_p="+id_p+"&fl="+fl+"&TabNum="+f.TabNum.value+"&Bill_Dog="+Bill_Dog+"&Nic="+Nic+"&Login="+vLogin+
				"&abon="+f.abon_p.value+"&inet="+f.inet_pay.value+"&abon_Com="+f.abon_Com.value+"&inet_Com="+f.inet_Com.value+
				"&Date_start="+D_st+"&Date_end="+f.new_Date_end.value+"&action="+f.action.value+"&nDateAct="+f.nDateAct.value+
				(dolg?'&dolg=dolg&s_dolg='+f.s_dolg.value+'&d_off='+f.Date_start_st.value+'&c_dolg='+f.c_dolg.value:'');
		//	ch_param('do_pay', s_param, 'res_pay');
	/*************************************************************/
			$TabNum  	= 11;				//$_REQUEST ["TabNum"];
			$abon 		= $abon_p;			//$_REQUEST ["abon"];
			$Date_start = $D_st;			//$_REQUEST ["Date_start"];
			$Date_end 	= $nDate;	//$new_Date_end;	//$_REQUEST ["Date_end"];
		//	$nDateAct 	= $_REQUEST ["nDateAct"];
			if ($dolg) {
			//	$s_dolg	= $_REQUEST ["s_dolg"]+100;
				$c_dolg = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+($auto && $v_st==2?0:1),date("Y")));	//$_REQUEST ["c_dolg"];
				$d_off	= $abon>0?($auto && $v_st==2?$c_dolg:$c_ar['Date_start_st']):$Date_end;	//$_REQUEST ["d_off"];
				$summa = $abon>0?$s_dolg+($auto && $v_st==2?0:100):$sum;
				$Date_start = $auto && $v_st==2 && $abon_p>0?$c_dolg:$D_st;
			}
			$now		= date("Y-m-d H:i:s");	
		
			if ($sum > 0) {
				//		$to_Date_pay = ($action > 0?$nDateAct:$new_Date_pay);
				//*********************	
				$cod = get_Cod_flat($id_p, $fl);
				if ($cod==0) { $cod = get_cod($Bill_Dog); }
//				$comm_txn = $c_ar['Date_pay']."+".($m>0?"{$m}м.":"").($d>0?"{$d}д.":"")." $txn_id";
				if ($dolg) {	//date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")))
					if($abon_p<=0) {
						fdebug("платежа не хватает на покрытие долга и подключение, изменяем оплаченную дату<br>");
						$s_com = "'от долга ".($m>0?"{$m}м.":"").($d>0?"{$d}д.":"")." $txn_id'";
//?						$Date_start = $auto && $v_st==2?$c_dolg:$D_st;
					} else {
						fdebug("Вставка заявки на подключение ...<br>");
						 $s_com= "'".($auto && $v_st==2?"авто.":"долг+100р.")."подкл, $txn_id'";
						//	put_noti2conn ('1', $c_dolg, $Bill_Dog, $cod, $id_p, $fl, $TabNum);
						//		function put_noti2conn ($conn, $Date_Plan, $Bill_Dog, $Cod_flat, $id_p, $fl, $TabNum) {	//
						$Date_in = $now;	//date("Y-m-d H:i:s");
						fdebug("Заявка на смену подключения абонента. Плановая дата исполнения:$Date_Plan. ");
						$s_col = "Cod_flat,Bill_Dog,conn,Notify,  Date_Plan,  Date_in, TabNum, id_p, fl";					
						$v_col = "$cod, $Bill_Dog,1,'подключение','$c_dolg','$Date_in',$TabNum,$id_p,$fl";
					
						$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
						$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
						fdebug("Внесена</br>");
					}
				//*********************	
					fdebug("Информация об оплате долга ... ");//$Date_start -> $c_ar['Date_start_st']
					$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
									"values ($TabNum,$Bill_Dog,'$Nic','$now','','$d_off',$summa,1,$s_com)";//		.'</br>'
					$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
					$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
									"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_start','$d_off',$summa,1,$s_com)";//		.'</br>'
					$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
					fdebug("Добавлена.</br>");//$q_ins_abon.
					
			//		put_noti2off ($_REQUEST["Date_pay"], $Bill_Dog, $cod, $id_p, $fl, $TabNum); 
					if($abon_p>0) {
						fdebug("Окончание откл. остояния абонента ... ");
						$q_stt = mysql_query("update `customers` set Date_end_st='$c_dolg' where Bill_Dog=$Bill_Dog") 
																or die(mysql_error());
						fdebug("установлено в $c_dolg.</br>");
					}
				}
			if($abon_p>0) {
				$dp = strtotime($nDateAct); $Date_Plan = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
			//	$s_q = "select Max(`Date_Plan`) as Max_Date_Plan from `notify_repair` where Bill_Dog=$Bill_Dog and conn=-1 and `Date_Fact` IS NULL";
				$s_q = "select Num_Notify, `Date_Plan` as Max_Date_Plan from `notify_repair` 
							where Bill_Dog=$Bill_Dog and conn=-1 and `Date_Fact` IS NULL and Notify='откл.(долг)'";
				fdebug("Дата отключения за долг ");//$Bill_Dog
				$q_DP = mysql_query($s_q) or die(mysql_error());// order by `Date_Plan` desc
				if(mysql_num_rows($q_DP)>0) {
					$r_DP = mysql_fetch_array($q_DP, MYSQL_ASSOC);
					fdebug($r_DP["Max_Date_Plan"]);
					$s_del ="update `notify_repair` set Date_Plan='$Date_Plan' where Num_Notify={$r_DP['Num_Notify']}"; //Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Max_Date_Plan"]."'";
					$q_del = mysql_query($s_del) or die(mysql_error());
			//		$q_del = mysql_query("update `customers` set Date_Plan='$Date_end' where Bill_Dog=$Bill_Dog and conn=-1 and Date_Plan='".$r_DP["Date_end_st"]."'") or die(mysql_error());
					fdebug(" перенесена на $Date_Plan.</br>");//$Date_end
				} else { /* нет заявки на отключение, внести */
					$q_ins_noti = "insert into `notify_repair` (Cod_flat,id_p,fl,TabNum,Bill_Dog,Nic,Date_in,Date_Plan,conn,Notify) 
									values ($cod,$id_p,$fl,$TabNum,$Bill_Dog,'$Nic','$now','$Date_Plan',-1,'откл.(долг)')";
					$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
					fdebug("установлена на $Date_Plan.</br>");
				}
				// Отключен ли интернет? ВКЛЮЧИТЬ ! ##########################################################################

///########    Соединяемся с сервером      ###############################################################
	error_reporting(E_ALL); 
	set_time_limit(30); 
	ob_implicit_flush(); 
			$s_st = "";
	if (($GLOBALS['fp'] = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === False) {
		fdebug( "Ошибка соединения с сервером биллинга: " . socket_strerror(socket_last_error()) . "<br>" );
		//***********************	ошибка соединения с сокетом error = 3
		$s_st = "!i_bill";
//			$q_upd = "update `t_inet` set error = 3 where txn_id=$txn_id";
//			$r_upd =  mysql_query($q_upd) or die(mysql_error());
			//##     В Ы Х О Д    ##//
//			Go_Out($txn_id, 0);
	} else { ///  есть соединение с сервером биллинга
		$result    = socket_connect($fp, $IPb, 49160); 
		
		if (!get_wellcome($fp)) {	//***********************	ошибка чтения с сокета error = 4
			$s_st = "!i_well";
			fdebug( " Ошибка при прочитении данных приветствия<br> " );
	//		$q_upd = "update `t_inet` set error = 4 where txn_id=$txn_id";
	//		$r_upd =  mysql_query($q_upd) or die(mysql_error());
			//##     В Ы Х О Д    ##//
	//		Go_Out($txn_id, 0);
		} else {
			//***********************	
			fdebug("Есть соединение!<br>");
			$n = getNic(get_000($fp, "look $account"));		$wNic = ($n==""?getNic(get_000($fp, "look $account")):$n);
			fdebug("Пользователь: ". (($errNic = $wNic == "")?"<b>ОТСУТСТВУЕТ!</b>":$wNic). "<br>");
			if ($errNic) { 	//***********************	ошибка! пользователь отсутствует! error = 1
				$s_st = "!i_out";
				fdebug("Счёт ".$_REQUEST ["account"]." не существует!<br>");
				fdebug("в базе уже есть запись с txn_id=$txn_id<br>");
		//		$q_upd = "update `t_inet` set error = 1 where txn_id=$txn_id";
		//		$r_upd =  mysql_query($q_upd) or die(mysql_error());
				//##     В Ы Х О Д    ##//
		//		Go_Out($txn_id, 0);
			} else {
			//***********************
		//	$acc_1 = getSum($acc = send_command($fp, "acc $account"));
		//	fdebug("Начальный баланс: ". $acc_1. "<br>");
			//*************************************************************
			//*********************** первый запрос "check"
		//	$r_txn = get_prv_txn($txn_id);	//*****
		//	if ($r_txn["result"] == 90 || $r_txn["result"] == 1) { // Проведение платежа не окончено
			/*	$acc_1 = getSum($acc = send_command($fp, "acc $account"));
				fdebug("Начальный баланс: ". $acc_1. "<br>");	*/
		//		fdebug("... обнаружен не оконченный платеж<br>");
				//*********************	
		//		fdebug( substr(ltrim(strstr($acc, "-"), " "), 5, 1) );
				fdebug("acc: ". ($acc = send_command($fp, "acc $account")). "<br>");
				$fros = isFrosen($acc);
				$off = isOFF($acc);
				fdebug("Абонент ".($fros?"<b>":"<b>Не </b>")."заморожен"."</b><br>");
				fdebug("Абонент ".($off ?"<b>":"<b>Не </b>")."отключен"."</b><br>");
		/*		 не надо проверять состояния (?)
				if ($fros) {
					fdebug("... РАЗморозка<br>");
					$res = get_000($fp, "unfreeze $account");
					fdebug("Абонент: ".(isFrosen(send_command($fp,"acc $account"))?"<b>НЕ</b>":"успешно<b>")." разморожен</b><br>");
				}
		//		if ($off) fdebug("Абонент: <b>".(substr(get_000($fp, "on $account"),6,7)=="Success"?"успешно":"НЕ")." включен</b><br>");
				//*********************	
		//		$res = get_000($fp, "add $account $sum");				fdebug("Операция: ". $res."<br>");//getSum()
		//		$res = 'err_'.str_ireplace ('"', ' ', str_ireplace ("'", " ", $res));
		//		$acc_2 = getSum(send_command($fp, "acc $account"));			fdebug("Конечный баланс: ". $acc_2."<br>");
				//*********************	
				if ($fros) {
					fdebug("... ЗАморозка<br>");
					$res = get_000($fp, "freeze $account");
					fdebug("Абонент: ".(isFrosen(send_command($fp, "acc $account"))?"успешно<b>":"<b>НЕ</b>")." заморожен</b><br>");
				}
		*/
		//		if (round(floatval($acc_2)) != round(floatval($acc_1))/*, 2) == round(floatval($sum), 2)*/) {
		//			$q_upd = "update `t_inet` set d_time = $now, account=$account, sum=$sum, result=0 where txn_id=$txn_id";
		//			$r_upd =  mysql_query($q_upd) or die(mysql_error());
		//			fdebug("получение данных пользователя<br>");
					//*********************	
		//			$B_inf = get_inf_acc($account);	// получение данных пользователя
					//*********************	date("Y-m-d",)
		//			fdebug($B_inf['Bill_Dog']."<br>");
		//			$s_st = "";
		//			if ($B_inf['Bill_Dog']>0) {
					/*	$dp = strtotime(get_date2off($account)); */
						$D_pay2 = mktime(0,0,0,date("m",$dp),date("d",$dp)/*+31*/,date("Y",$dp));
						if ( /*get_ab_sum($account)>0 &&*/ /*$D_pay2*/ $d3 < mktime()) {
							fdebug("Должник даже после оплаты, ОТКЛЮЧИТЬ интернет!<br>");
				/********** Должник более месяца, ЗАМОРОЗИТЬ и/или ОТКЛЮЧИТЬ интернет!
							$res = get_000($fp, "freeze $account");
							fdebug("Абонент: ". (($frz=isFrosen(send_command($fp, "acc $account")))?"успешно<b>":"<b>НЕ</b>"). " заморожен</b><br>");
							$s_st = ($frz)?" FRZ":"";
		/*		ОТКЛЮЧИТЬ		*******/
							$res = get_000($fp, "off $account");
							$off = isOFF(send_command($fp, "acc $account"));	
							$s_st = ($off)?"OFF":"";
							fdebug("<b>".($off?"ОТ":"В")."КЛ</b>ючен</br>");	
						} else {	// долга НЕТ! включить и разморозить!
							if($off) {/*		В_КЛЮЧИТЬ		*******/
								$res = get_000($fp, "on $account");
								$off = isOFF(send_command($fp, "acc $account"));	
								$s_st = ($off)?"!iOFF":"ON";
								fdebug("<b>".($off?"ОТ":"В")."КЛ</b>ючен</br>");	
							}
							if($fros) {/*		РАЗ_морозить		*******/
								$res = get_000($fp, "unfreeze $account");
								$fros = isFrosen(send_command($fp, "acc $account"));	
								$s_st .= ($fros)?"!iFRZ":"UN";
								fdebug("<b>".($fros?"!ЗА":"РАЗ")."</b>морожен</br>");	
							}
						}
			}
		//	fdebug("---------------------<br>");
			//##     В Ы Х О Д    ##//
		//	Go_Out($txn_id, 0);
			if ($fp) { socket_close($fp); }
		}
	}
				// конец *** Отключен ли интернет? ВКЛЮЧИТЬ !
	}
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##
	//*********************	
		fdebug("Изменение в подключении к сети: оплачено по $nDateAct.");//Date_end
			//Saldo=".($_REQUEST ["abon"]+$row_abon["Saldo"])."
		$q_cor_abon = "update `customers` set Date_pay='$nDateAct' where Bill_Dog=$Bill_Dog";//, Date_end_st='$Date_end'
		$s_cor_abon =  mysql_query($q_cor_abon) or die(mysql_error());// Date_start_st='$Date_start', Date_end_st='$to_Date_end',
		
		if($v_st==1){
			fdebug("состояние продлено по: $nDateAct...");//Date_end
			$q_cor_abon = "update `customers` set Date_end_st='$nDateAct' where Bill_Dog=$Bill_Dog and state=1";//Date_end
			$s_cor_abon =  mysql_query($q_cor_abon) or die(mysql_error());// Date_start_st='$Date_start', Date_end_st='$to_Date_end',
		}
		
		fdebug("Внесено.<br>Пометка платежа как успешного...");
		$q_upd = "update `t_abon` set d_time = '$now', account=$account, sum=$sum, result=0 where txn_id=$txn_id";
		$r_upd =  mysql_query($q_upd) or die(mysql_error());
	
		fdebug(" Внесено.</br>");	//	 state=1,
	//*********************	
		if($abon>0){
			fdebug("Информация о платеже абонплаты ... ");
			if ($dolg) {
				$dp = strtotime($d_off); $Date_start = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+($auto && $v_st==2?0:1),date("Y",$dp)));
			}
			$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
										"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_start','$Date_end',$abon,1,".
								"'"./*$c_ar['Date_pay'].*/"+".($m>0?"{$m}м.":"").($d>0?"{$d}д.":"")." $txn_id $s_st')";//	.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
										"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_start','$Date_end',$abon,1,".
								"'"./*$c_ar['Date_pay'].*/"+".($m>0?"{$m}м.":"").($d>0?"{$d}д.":"")." $txn_id $s_st')";//	.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			fdebug(" Добавлена.</br>");
		}
		//*********************	
		if ($action > 0) {
			$dp = strtotime($Date_end); $Date_s = date("Y-m-d",mktime(0,0,0,date("m",$dp),date("d",$dp)+1,date("Y",$dp)));
			fdebug("Информация о предоставлении $action мес. по акции ... ");
			$q_ins_abon = "insert into `act{$Y}` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
				"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_s','$nDateAct',0,3,'по акции $action мес. $txn_id')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			$q_ins_abon = "insert into `actions` (TabNum,Bill_Dog,Nic,InputDate,Date_start,Date_end,Summa,id_ActionType,Comment) ".
				"values ($TabNum,$Bill_Dog,'$Nic','$now','$Date_s','$nDateAct',0,3,'по акции $action мес. $txn_id')";//		.'</br>'
			$s_ins_abon =  mysql_query($q_ins_abon) or die(mysql_error());
			fdebug("Добавлена.</br>");
		}
	}
	//*********************	
	if ($auto && $v_st==2 && $abon_p > 0) {
		do_on_cust($Bill_Dog);
	}
	}
}
//#################################################################################################################################

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

function Go_Out($txn_id, $resp) {
	$to_resp = "<?xml version=\"1.0\"?>\n<response>\n<osmp_txn_id>$txn_id</osmp_txn_id>\n<result>0</result>\n</response>";
	fdebug("возвращаем :".HACK($to_resp)."<br>");
	print "<?xml version=\"1.0\"?>\n"; 	print "<response>\n<osmp_txn_id>$txn_id</osmp_txn_id>\n<result>0</result>\n</response>";
/*	print $to_resp;	*/
	if ($GLOBALS['fp']) { socket_close($GLOBALS['fp']); }
	exit;
}

function fdebug($sdeb) {
	if ($GLOBALS['deb']==1) { echo /*date("Y-m-d H:i:s")." ".*/$sdeb; }
}

function out_resp($txn_id, $resp) {
//			$to_resp = "<osmp_txn_id>$txn_id</osmp_txn_id><prv_txn>".$r_txn["prv_txn"]."</prv_txn><sum>$sum</sum><result>0</result><comment>OK</comment>";
			return "<response>\n<osmp_txn_id>$txn_id</osmp_txn_id>\n<result>0</result>\n</response>";
}

function is_txn_id ($txn_id) {
	$qs = "select txn_id from `t_abon` where txn_id=$txn_id";
	$res =  mysql_query($qs) or die(mysql_error());
	return mysql_num_rows($res);
}

function get_new_prv_txn()
{
	$q = "SELECT max(`prv_txn`) AS `MAX_prv_txn` FROM t_abon";
  	$rq = mysql_query($q) or die(mysql_error());
	$r_txn = mysql_fetch_assoc($rq);
	return $r_txn["MAX_prv_txn"]+1;
}

function get_prv_txn($txn_id)
{
	$q = "SELECT * FROM t_abon where  txn_id=$txn_id";
  	$rq = mysql_query($q) or die(mysql_error());
	$r_txn = mysql_fetch_assoc($rq);
	return $r_txn;
}

function get_date2off($acc)
{
	if(($bd = get_Bill_acc($acc))==0) return 0;
	$q = "SELECT Date_pay FROM customers where Bill_Dog = $bd" ;
  	$rq = mysql_query($q) or die(mysql_error());
	$r_ = mysql_fetch_assoc($rq);
	return $r_["Date_pay"];
}

function date_add_month($d, $m) {
	return mktime(0,0,0,date("m",$d)+$m,date("d",$d),date("Y",$d));
}

function date_add_day($d, $dd) {
	return mktime(0,0,0,date("m",$d),date("d",$d)+$dd,date("Y",$d));
}

function d2str($dp) {
	return date("Y-m-d",$dp);
}

function put_er($txn_id, $er){
/*function put_er($account, $txn_id, $sum, $comm){
echo	$q_r = "insert into tab_er (time, account, txn_id, sum, comm) values ('".date("Y-m-d H:i:s")."', $account, $txn_id, $sum, 'ошб в адресе')";
	$res =  mysql_query($q_r) or die(mysql_error());	*/
			$q_upd = "update `t_abon` set error = $er where txn_id=$txn_id";
			$r_upd =  mysql_query($q_upd) or die(mysql_error());
}

function d_getDaysInMonth($d){
	return date("d",mktime(0,0,0,date("m",$d)+1,0,date("Y",$d)));
}

function d_setIfLast($d, $da_, $de_){
	return (($d == 0) && (d_getDaysInMonth($da_)==date("d",$da_)))?mktime(0,0,0,date("m",$de_),d_getDaysInMonth($de_),date("Y",$de_)):$de_;
}

#############################################################################################?>
