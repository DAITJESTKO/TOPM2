<link href="selena.css" rel="stylesheet" type="text/css" />
<?
require_once("for_form.php"); 
check_valid_user();
$conn_db = db_connect();
  if (!$conn_db) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") { $rem_addr="http://selena/"; } else { $rem_addr="https://10.1.2.22/"; }

	global $totalRows_customer;
	if (isset($_REQUEST ["k"])) { //st
		$k = $_REQUEST ["k"];		//	$st = $_REQUEST ["st"];
		if ($fl==0) { 
			$totalRows_customer=0;
			$id_Podjezd = 0;
		}echo "!";
	} else {
		$k = $GLOBALS['k'];
		$fl = $GLOBALS ["fl"];
		$Town = $GLOBALS ["Town"];
		$name_street = $GLOBALS ["name_street"];
		if ($fl>0) {
			?><span COLOR="#FF0000" class="quote"><? echo "г.$Town ул.$name_street д.$Num_build кв.$fl"; ?> </span><?
		} else {  }
	}
	$new = (isset($_REQUEST ["new"]))? $_REQUEST ["new"]:"";
	$not_new_adr = !(isset($_REQUEST ["menu"]) && (!$_REQUEST ["menu"]=="new_adr"));
	$tp = (isset($_REQUEST['tp']))? $_REQUEST['tp']:(isset($GLOBALS['tp']))? $GLOBALS['tp']:"9";
	$tn = (isset($_REQUEST['tn']))? "tn=".$_REQUEST['tn']."&":"";
//	$par = $tn.$tp.$trg.$new_p.$menu_p;
	$GLOBALS['tp'] = $tp;
	$ToDay = date("Y-m-d");
	$ar_s = array(''=>'не уст.', 0=>'не уст.', 1=>'подключ.', 2=>'замороз.', 3=>'расторг');
	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
	$Bill_Dog_New = get_Bill_Dog();
	//echo (isset($_REQUEST ["menu"])?"menu:".$_REQUEST ["menu"]:"#");
	if (isset($GLOBALS['menu'])) $p1 = $GLOBALS['menu']; else $p1 = "no menu";
	$q_podjezd = "SELECT * FROM v_podjezd where id_korp=$k and FirstFlat<=".$fl." and LastFlat>=".$fl;

	$s_podjezd =  mysql_query($q_podjezd) or die(mysql_error());
	$row_podjezd = mysql_fetch_assoc($s_podjezd);
	$totalRows_podjezd = mysql_num_rows($s_podjezd);
	$RegionName = $row_podjezd['RegionName'];
	$name_street = $row_podjezd["name_street"];
	$Num_build = $row_podjezd['Num_build'];
	$id_Podjezd = $row_podjezd['id_Podjezd'];
	$Podjezd = $row_podjezd['Podjezd'];
	$Korpus = $row_podjezd['Korpus'];
	$id_p = $id_Podjezd;

/* $Bill_Dog = 0;
 if (isset($Podjezd)) { */
	$q_customer = "SELECT * FROM v_customer where id_Podjezd=".$id_Podjezd." and flat =".$fl; 

	$s_customer =  mysql_query($q_customer) or die(mysql_error());
	$row_customer = mysql_fetch_array($s_customer, MYSQL_ASSOC);
	$totalRows_customer = mysql_num_rows($s_customer);
	$new_Cod = check_flat($id_p, $fl);
	$Cod_flat = $new_Cod ? new_Cod_flat() : get_Cod_flat($id_p, $fl);		//$row_customer['Cod_flat'];		//($row_customer['Cod_flat'] == 0)
	$GLOBALS['new_Cod'] = $new_Cod ? 1 : 0;			//($row_customer['Cod_flat'] == 0)
//	$RegionName = $row_customer['RegionName'];
//	$id_Podjezd = $row_customer['id_Podjezd'];
//	$Podjezd = $row_customer['Podjezd'];
//	$Korpus = $row_customer['Korpus'];
//	$id_p = $id_Podjezd;
//  	echo '<input name="h_" type="hidden" value="'.$.'" />';
  	echo '<input name="h_st" type="hidden" value="'.$name_street.'" />';//st
  	echo '<input name="h_nb" type="hidden" value="'.$Num_build.'" />';
  	echo '<input name="h_fl" type="hidden" value="'.$fl.'" />';
  	echo '<input name="h_id_Podjezd'.$new.'" type="hidden" value="'.$id_Podjezd.'" />';//
  	echo '<input name="h_kr" type="hidden" value="'.$Korpus.'" />';
	echo '<input name="h_Rows" type="hidden" value="'.$totalRows_customer.'" />';
	echo (($Korpus>0)?'корп.'.$Korpus:'').'</b> пд.<u><b>&nbsp;'.$Podjezd.'&nbsp;</b></u>';
	echo 'эт.<input name="floor'.$new.'" type="text" id="floor" value="'.$row_customer['floor'].'" size="1" />';
	echo ', р-он <FONT class="quote стиль1"> <u>&nbsp;'.$RegionName.'&nbsp;</u></FONT>&nbsp;';
	//<tr><td></td></tr>			//($row_customer['Cod_flat']==0)
	echo '<b>Код адреса: <FONT '.($new_Cod? 'COLOR="#FF0000">'.
		$Cod_flat.' новый':'>'.$Cod_flat).'.</FONT> Договоров по адресу - '.$totalRows_customer.'</b>';
	echo '<input name="h_new_Cod" type="hidden" value="'.( $new_Cod? 1 : 0).'" />';	//($totalRows_customer == 0 || $row_customer['Cod_flat']==0)
	echo '<input name="h_Cod_flat'.$new.'" type="hidden" value='.$Cod_flat.' />';
	echo '<input name="h_Conn" type="hidden" value="'.(($totalRows_customer == 0) ? 0 : 1).'" />';
	//	echo '<b><font class="quote"> </font>ул.'.$row_customer['name_street'].' д.'.$row_customer['Num_build'];
	if ($_REQUEST ["menu"] == "new_adr") {
		echo ' <input name="B_edt_cust" type="button" onclick="set_new_adr();" value="Перевести на этот адрес" />';
	}
	if ($totalRows_customer!=0) {
		
//			$Bill_Dog = $row_customer['Bill_Dog'];
		echo '<table align="center" border=0 width=100%>';
		echo '<tr>';
	//	$s_onchange='"clr_adress(); show_cust('.$Bill_Dog.','.$id_p.','.$fl.'); f_btn();"';
		$s_onchange='"clr_adress(); chk_adress();"'; //write_temp(&quot;Menu_Item=&quot;document.forms[&quot;ulaForm&quot;].Menu_Item.value); 
//		$sz = $totalRows_customer;navText
		echo '<td  rowspan="2"><select name="tabl_cust" id="tabl_cust" class="headText" size="'.$totalRows_customer.'" '.
			 ($not_new_adr?'onchange='.$s_onchange:'').'>';//,this.value //,document.getElementById(&quot;rbtn_cust&quot;).value
		$Bill_Dog1 = $row_customer['Bill_Dog'];
		$ab_numbs = 0;
		do {
			$i +=1;
			$Bill_Dog = $row_customer['Bill_Dog']; //&nbsp; - &nbsp;
			$Fio = $row_customer['Fam'].' '.$row_customer['Name'].' '.$row_customer['Father'];
			$Y = date("Y", strtotime($row_customer["Date_start_st"]));
			$ab_numbs = $ab_numbs + ($row_customer['inet']?0:1);
			echo '<option value='.$Bill_Dog.(($i ==1)?" selected":""); //Num_PC Bill_Dog Num &#009;
			$s_1 = 'Дог.№'.$Bill_Dog.($row_customer['inet']?"(инет)":"").', '.$ar_s[$row_customer['state']].' '.
				(!empty($row_customer['Date_end_st'])?'по '.date("j ", strtotime($row_customer['Date_end_st'])).$m[date("n", strtotime($row_customer['Date_end_st']))].(date("Y")==$Y?'':' '.$Y.'г.'):'').
				', ник: '.$row_customer['Nic'].'&#009;: '.$Fio; 
			echo ' >'.$s_1.'</option>';
			$arr_cust[$Bill_Dog] = $row_customer;
		} while ($row_customer = mysql_fetch_array($s_customer, MYSQL_ASSOC));
		echo '</select></td><td valign="baseline">';//
//		echo $Bill_Dog_New;
		if ($_REQUEST ["menu"] == "recon") {
//			$GLOBALS['Bill_Dog_New'] = get_Bill_Dog();	.$GLOBALS['Bill_Dog_New']. form=document.forms[&quot;ulaForm&quot;]; 
			echo //' <input name="B_edt_cust" type="button" onclick='.$s_onchange.' value="Показать" />';phone_Home=form.phone_Home.value; 
			' <button name="B_reload" type=button onClick='.$s_onchange.'><img src="reload.png" align=middle alt="Обнови"></button>';
			echo ' <button name="B_add_cust" type=button onClick="javascript:add_cust('.$Bill_Dog_New.');'.
		/*		f=document.forms.ulaForm; phone_Home=f.phone_Home.value==\'\'?\'\':f.phone_Home.value; '.
				'clr_adress(); f.phone_Home.value=phone_Home; f.Date_start_st.value = \''.$ToDay.'\'; '.
				'f.Bill_Dog.value=\''.$Bill_Dog_New.'\'; f.conn[2].selected = true; adj_Conn();					*/
		/*		'; document.forms[&quot;ulaForm&quot;].tarifab_date.value='.$ToDay.';'.'document.forms[&quot;ulaForm&quot;].tarif3w_date.value='.$ToDay. */
				'"><img src="ico_create.gif" align=middle alt="Доп.подкл."></button>';// 
			echo ' <button name="B_del_cust" type=button onClick="javascript:alert(&quot;удаление в разработке&quot;);"><img src="ico_delete.gif" align=middle alt="Удалить"></button>';
			echo '</td><td>';
			echo ' <button name="B_print" type=button onClick="javascript:dogovor();"><img src="printer.gif" align=middle alt="Печать"></button>';
	///		echo ' <input type="button" name="Submit_ins" id="Submit_ins" value="Печать" onClick="dogovor();" />'; //window.print  alert
		}
		echo '</td></tr><FONT size=-1><table><tr>';
	
		// Заполняем массив данных
		foreach ($arr_cust as $cust_row) {
			$id = $cust_row['Bill_Dog'];
				// Получаем все Логины для Ника
				$q_login = "SELECT Login,id_tarif3w,tarif3w_date,saldo FROM `logins` where Nic='".$cust_row['Nic']."'";
				$s_login =  mysql_query($q_login) or die(mysql_error());
				$totalRows_login = mysql_num_rows($s_login);
				$Logins[$cust_row['Bill_Dog']]["Logins"] = $totalRows_login;
				$inp_name = "h_".$cust_row['Bill_Dog']."_Logins";
			//	echo $inp_name."=".$val."</br>";
				echo '<input name="'.$inp_name.'" id="'.$inp_name.'" type="hidden" value="'.$totalRows_login.'" />';//$inp_name.
				if ($totalRows_login >0) {
					$j = 1;
					while ($row_login = mysql_fetch_array($s_login, MYSQL_ASSOC)) {
						$Logins[$cust_row['Bill_Dog']][$j] = $row_login;
						foreach ($row_login as $key => $val) {
							$inp_name = "h_".$cust_row['Bill_Dog']."_".$key.$j;
						//	echo $inp_name."=".$val."; ";
							echo '<input name="'.$inp_name.'" id="'.$inp_name.'" type="hidden" value="'.$val.'" />';//
						}
						$j++;
					}
				} else { // не найдены логины
				//echo "нет логинов";
					$Logins[$cust_row['Bill_Dog']][1] = array('Login'=>'', 'id_tarif3w'=>'','tarif3w_date'=>'','saldo'=>'');
					$inp_name = "h_".$cust_row['Bill_Dog'];
				//	echo $inp_name."=".'0'."; ";
					echo '<input name="'.$inp_name.'_Login1" id="'.$inp_name.'_Login1" value="" type="hidden" />';//
					echo '<input name="'.$inp_name.'_saldo1" id="'.$inp_name.'_saldo1" value="" type="hidden" />';//
				}
				foreach ($cust_row as $key => $val) {
					print_hid($id, $key, $val);
				}
		}
	//	print_r($arr_cust);
		//echo '</br>print_arr(arr_cust):</br>';
		//print_arr($arr_cust);
		//echo '</br>print_r(Logins) ==========================================================</br>';
		//print_r($Logins);
		
		echo '</td></tr>';

$frm_phn = $rem_addr."frm_phn.php?phone_Home=".$arr_cust[$Bill_Dog1]['phone_Home']."&phone_Cell=".
	$arr_cust[$Bill_Dog1]['phone_Cell']."&phone_Work=".$arr_cust[$Bill_Dog1]['phone_Work'];

$frm_fio = $rem_addr."frm_fio.php?Fam=".$arr_cust[$Bill_Dog1]['Fam']."&Name=".$arr_cust[$Bill_Dog1]['Name'].
	"&Father=".$arr_cust[$Bill_Dog1]['Father']."&Birthday=".$arr_cust[$Bill_Dog1]['Birthday'].
	"&pasp_Ser=".$arr_cust[$Bill_Dog1]['pasp_Ser']."&pasp_Num=".$arr_cust[$Bill_Dog1]['pasp_Num'].
	"&pasp_Date=".$arr_cust[$Bill_Dog1]['pasp_Date']."&pasp_Uvd=".$arr_cust[$Bill_Dog1]['pasp_Uvd'].
	"&pasp_Adr=".$arr_cust[$Bill_Dog1]['pasp_Adr']."&Comment=".$arr_cust[$Bill_Dog1]['Comment'];

$frm_net = $rem_addr."frm_net.php?conn=".$arr_cust[$Bill_Dog1]['conn']."&Bill_Dog=".$arr_cust[$Bill_Dog1]['Bill_Dog'].
	"&id_tarifab=".$arr_cust[$Bill_Dog1]['id_tarifab']."&tarifab_date=".$arr_cust[$Bill_Dog1]['tarifab_date'].
	"&Nic=".$arr_cust[$Bill_Dog1]['Nic'];

//$frm_w3 = $rem_addr."frm_w3.php?Login=".$Logins[$Bill_Dog1][1]['Login']."&From_Net=".$arr_cust[$Bill_Dog1]['From_Net'].
//	"&id_tarif3w=".$Logins[$Bill_Dog1][1]['id_tarif3w']."&tarif3w_date=".$Logins[$Bill_Dog1][1]['tarif3w_date'];

		}	else	{ // ======   нет клиентов по адресу ======================================================<	
if ($GLOBALS['menu'] == 'pay') { return; }
$frm_phn = $rem_addr."frm_phn.php?phone_Home=&phone_Cell=&phone_Work=";
$frm_fio = $rem_addr."frm_fio.php?Fam=''&Name=''&Father=''&Birthday=''".
	"&pasp_Ser=''&pasp_Num=''&pasp_Date=''&pasp_Uvd=''&pasp_Adr=''&Comment=''";
$frm_net = $rem_addr."frm_net.php?conn=''&Bill_Dog=".get_Bill_Dog()."&id_tarifab=''&tarifab_date=&Nic=''";
$frm_w3 = $rem_addr."frm_w3.php?Login=&id_tarif3w=''&tarif3w_date=";
	}
	echo '</table></FONT>';
//	} else { }  нет такого адреса http://selena	
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

//$Bill_Dog_New = get_Bill_Dog();//""

if ($GLOBALS['totalRows_customer']>0) {
	$phone_Home = $arr_cust[$Bill_Dog1]['phone_Home'];
	$phone_Cell = $arr_cust[$Bill_Dog1]['phone_Cell'];
	$phone_Work = $arr_cust[$Bill_Dog1]['phone_Work'];
	$Jur = $arr_cust[$Bill_Dog1]['Jur'];

	$Fam = $arr_cust[$Bill_Dog1]['Fam'];
	$Name = $arr_cust[$Bill_Dog1]['Name'];
	$Father = $arr_cust[$Bill_Dog1]['Father'];
	$Birthday = $arr_cust[$Bill_Dog1]['Birthday'];
	$pasp_Ser = $arr_cust[$Bill_Dog1]['pasp_Ser'];
	$pasp_Num = $arr_cust[$Bill_Dog1]['pasp_Num'];
	$pasp_Date = $arr_cust[$Bill_Dog1]['pasp_Date'];
	$pasp_Uvd = $arr_cust[$Bill_Dog1]['pasp_Uvd'];
	$pasp_Adr = $arr_cust[$Bill_Dog1]['pasp_Adr'];
	$Comment = $arr_cust[$Bill_Dog1]['Comment'];

	$conn = $arr_cust[$Bill_Dog1]['conn']==''?0:$arr_cust[$Bill_Dog1]['conn'];
	$Bill_Dog = $arr_cust[$Bill_Dog1]['Bill_Dog'];
	$id_tarifab = $arr_cust[$Bill_Dog1]['id_tarifab'];
	$tarifab_date = $arr_cust[$Bill_Dog1]['tarifab_date'];
	$Nic = $arr_cust[$Bill_Dog1]['Nic'];
//	$ = $arr_cust[$Bill_Dog1][''];
	$Date_start_st = $arr_cust[$Bill_Dog1]['Date_start_st'];
	$Date_end_st = $arr_cust[$Bill_Dog1]['Date_end_st'];
	$state = $arr_cust[$Bill_Dog1]['state'];
	$Date_pay = $arr_cust[$Bill_Dog1]['Date_pay'];
	
	$From_Net = $arr_cust[$Bill_Dog1]['From_Net'];
//echo 	$Logins[$Bill_Dog1]['Logins'];//$n = >0?1:1
	$Login = $Logins[$Bill_Dog1][1]['Login'];
	$id_tarif3w = $Logins[$Bill_Dog1][1]['id_tarif3w'];
	$tarif3w_date = $Logins[$Bill_Dog1][1]['tarif3w_date'];

} else {
	$phone_Home = "";
	$phone_Cell = "";
	$phone_Work = "";
	$Jur = 0;

	$Fam = "";
	$Name = "";
	$Father = "";
	$Birthday = "";
	$pasp_Ser = "";
	$pasp_Num = "";
	$pasp_Date = "";
	$pasp_Uvd = "";
	$pasp_Adr = "";
	$Comment = "";

	$conn = 0; // Новое
	$Bill_Dog = get_Bill_Dog();//""
	$id_tarifab = 0; // Стандарт
	$tarifab_date = $ToDay;
	$Nic = "";
	$Date_start_st = $ToDay;
	$Date_end_st = "";
	$state = "";
	$Date_pay = "";

	$From_Net = "";
	$Login = "";
	$id_tarif3w = 0;
	$tarif3w_date = $ToDay;
}
if ($GLOBALS['menu'] == 'recon') {
	form_phn($phone_Home, $phone_Cell, $phone_Work, $Jur);
	form_fio($Fam, $Name, $Father, $Birthday, $pasp_Ser, $pasp_Num, $pasp_Date, $pasp_Uvd, $pasp_Adr, $Comment);
	form_net($conn, $Bill_Dog, $id_tarifab, $tarifab_date, $Nic, $Date_start_st, $Date_end_st, $tp, $state, $Date_pay);
	form_w3($From_Net, $Login, $id_tarif3w, $tarif3w_date);
?>
<table width="800" border=0>
  <tr>
    <td width="160">Интернет: <input name="inet_pay" type="text" size="4" onchange="adj_CPay()" /> руб.</td>
    <td width="657" colspan="1" align="left">Итого: 
    <input name="total_pay" type="text" size="4" readonly="true" /> руб.</td>
  </tr>
</table>
<table width=800 border=0>
  <tr>
<?php /*?>	<td width="300" align="right">Заявка монтажнику: 
	<select name="mont" class='font8pt' id="mont" lang="ru" onchange='adjustmont();'>
<?php	$q_mont = "SELECT * FROM `personal` WHERE `id_TypePers`=4";
		$mont = mysql_query($q_mont) or die(mysql_error());
		$row_mont = mysql_fetch_assoc($mont);
		$totalRows_mont = mysql_num_rows($mont);
		echo "<option value=0>выбрать</option>";
		do { echo "<option value=".$row_mont['TabNum'].">".$row_mont['Fam']." (таб.№ ".$row_mont['TabNum'].")</option>"; }
			while ($row_mont = mysql_fetch_assoc($mont));
		$rows = mysql_num_rows($mont);
		if($rows > 0) { mysql_data_seek($mont, 0); $row_mont = mysqli_fetch_assoc($mont);  } ?>
    </select>    </td>	<?php */?>
    <td align="left"><div id="d_mont"></div></td>
  </tr>
</table>
<?php
} elseif ($GLOBALS['menu'] == 'noti') {
	form_phn($phone_Home, $phone_Cell, $phone_Work, $Jur);
	?><td>Дополнительный телефон: <input name="phone_Dop" size="15" /></td><?
} elseif ($GLOBALS['menu'] == 'pay') { 
	form_net($conn, $Bill_Dog, $id_tarifab, $tarifab_date, $Nic, $Date_start_st, $Date_end_st, $tp, $state, $Date_pay);
	form_w3($From_Net, $Login, $id_tarif3w, $tarif3w_date);
	$n_cod = $GLOBALS['new_Cod'] == 0;
	if (!$n_cod){?>
		<b><font size="3"><u> Для выполнения платежей сначала необходимо выполнить "подключение"</u></font></b></br></br>
	<? }?>
		<style type="text/css">
<!--
.hd3 {color: #FFFF99}
-->
        </style>
<table width="800" border=0>
  <tr>
    <td bgcolor="#66FF99">
		<table width="100%" border=0 cellpadding="2" cellspacing="2">
		  <tr bgcolor="#660066" border=1>
			<td colspan="6" align="center" valign="middle"><font color="#FFFF99">Платёж на все договора 
		    <input name="all_dog" type="checkbox" value="1"/></font></td>
			<td colspan="1" align="center"><font color="#FFFF99">Примечания</font></td>
		  </tr>
		  <tr valign="middle">
			<td width="70">Абон.плата: </td>
			<td width="21"><input name="abon_pay" type="text" size="3" onchange="<? if($n_cod) {?>adjust_pay()<? } else {?>alert('не присвоен код адреса')<? }?>" align="right" /></td><td width="26" > руб.</td>
		  	<td width="18">за </td>
		  	<td width="13" >
	  	    <input name="opl_per" type="text" size="1" onchange="<? if($n_cod) {?>f=document.ulaForm; f.abon_pay.value=f.opl_mon.value*this.value; adjust_pay()<? } else {?>alert('не присвоен код адреса')<? }?>" align="right" /></td><td width="30" > мес</td>
		  	<td width="282"><input name="abon_Com" type="text" size="30" />		  </td>
		  </tr>
		  <tr>
			<td>Интернет: </td>
			<td><input name="inet_pay" type="text" size="3" onchange="<? if($n_cod) {?>adjust_pay()<? } else {?>alert('не присвоен код адреса')<? }?>" align="right" /></td><td > руб.</td>
			<td colspan="3" ></td>
  	  	  <td><input name="inet_Com" type="text" size="30" />		  </tr>
		  <tr>
			<td class="стиль1">Итого: </td>
			<td><input name="total_pay" type="text" id="all_cost" size="3" readonly="true" align="right" /></td><td > руб.</td>
			<td colspan="4">
				<table border=0 width="100%">
				  <tr>
					<td colspan="2" align="right"><div class="quote" id="opl_to"></div></td>
					<td><div class="quote" id="action"></div></td>
				  </tr>
			  	</table>			</td>
		  </tr>
<!--		  <tr>	<td><div id="B_Create"></div></td>	<td><div id="B_Edit"></div></td>  </tr>-->
    </table>
	</td>
	<td>
		<table width="300" border=0 cellspacing="3" bgcolor="#99FFFF">
		  <tr>
<!--		  	<td bgcolor="#999999" align="right">Состояние:</td>
			<td><div id="d_state"><font style="border:solid" color=<? echo $state==1?'"#33CC66">&nbsp;активен':($state==2?'"#0000FF">&nbsp;заморожен':'"#333333">&nbsp;не установлено'); ?>&nbsp;</font></div></td>
-->		  	<td>Приостановка сети (отключить)</td>
		  </tr>
		  <tr>
			<td colspan="2">
				с <input name="Date_start_fr" type="date" size="9" /> 
				по <input name="Date_end_fr" type="date" size="9" />
			</td>
		  </tr>
		  <tr>
			<td colspan="2">
   				примечание&nbsp;<input name="Comment" type="text" size="25" />
			</td>
		  </tr>
		  <tr>
			<td colspan="2" align="right"><input name="B_freaze" type="button" onclick="frz_cust();"value="заморозь"  <?php if (empty($Date_pay) || (!$n_cod)) { echo 'style="display:none"';} ?>/></td>
		  </tr>
		</table>	</td>
  </tr>
</table>
<div id="res_pay"></div>
<div id="hist_pay">
<?
//require_once("sh_pays.php?BD=$Bill_Dog");
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//echo $q_res = "select * from v_actions where `Bill_Dog`=$Bill_Dog AND id_ActionType <>2 AND canc is null or canc <>1 ORDER BY `Date_start`";
$result = mysql_query("select * from v_actions where `Bill_Dog`=$Bill_Dog AND id_ActionType <>2 AND (canc is null or canc <>1) ORDER BY `Date_start`");		// Выполняем запрос кроме платежей за интернет(id_ActionType<>2)
if ($result) {
	$nbo = 1;	$i = 0;
	$cfg['BgcolorOne'] = "#E5E5E5";
	$cfg['BgcolorTwo'] = "#D5D5D5";
	$bgcolor = $cfg['BgcolorOne'];
	?>
	<table border="0" cellpadding="2" cellspacing="1" >
	<tr>
		<td bgcolor="<?php echo $bgcolor; ?>"> №</br>п/п</td>
		<td bgcolor="<?php echo $bgcolor; ?>">дата<br />операции</td>
		<td align="center" bgcolor="<?php echo $bgcolor; ?>">период</td>
		<td bgcolor="<?php echo $bgcolor; ?>">сумма</td>
		<td bgcolor="<?php echo $bgcolor; ?>">операция</td>
		<td bgcolor="<?php echo $bgcolor; ?>">коммент</td>
		<td bgcolor="<?php echo $bgcolor; ?>"></td>
	</tr>
	
	<?php
	// Печатаем данные построчно
//	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
		$o_Y = "";
		while ($row = mysql_fetch_assoc($result))  { ?>
			<? $bgcolor = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];			?>
		<? $o_Y2 = date("Y", strtotime($row["Date_start"]));
		if($o_Y!=$o_Y2) { $o_Y=$o_Y2; echo "<tr><td></td><td></td><td>$o_Y2 год</td></td></tr>"; }?>
		<tr>
			<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $nbo++;	?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo date("j ", strtotime($row["InputDate"])).$m[date("n", strtotime($row["InputDate"]))];		?> </td>
			<td align="center" bgcolor="<?php echo $bgcolor; ?>"> с <?php echo date("j ", strtotime($row["Date_start"])).$m[date("n", strtotime($row["Date_start"]))];	?> 
												   по <?php echo date("j ", strtotime($row["Date_end"])).$m[date("n", strtotime($row["Date_end"]))];	?> </td>
			<td align="right" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Summa"];			?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["ActionName"]		?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Comment"];			?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php //echo date("Y-m-d", strtotime($row["Date_start"]));
				if ($row["id_ActionType"]==4 /* заморозить */ ) {
					if (strtotime(date("Y-m-d")) < strtotime($row["Date_start"]) ) { ?>
						<input name="B_btn" type="button" onclick="del_otp(<? echo $Bill_Dog.",'".$row["Date_start"]."','".$row["Date_end"]."'" ?>);" value="отмени" />
<?					} else {
						if (strtotime(date("Y-m-d")) < strtotime($row["Date_end"]) ) { //echo strtotime($row["Date_end"]);
							$d_canc=(strtotime($row["Date_start"])>strtotime(date("Y-m-d")))?$row["Date_start"]:date("Y-m-d");
							//************************************************
							// !!! Изменить дату в заявке на подключение !!!
							?>
							до <input name="d_canc" id="d_canc" value="<? echo date("Y-m-d", strtotime($d_canc))?>" type="date" 
							onChange="if(this.value<<? echo date("Y-m-d", strtotime($d_canc))?>) this.value=<? echo date("Y-m-d", strtotime($d_canc))?>" 
							size="10" /> 
							<input name="B_btn" type="button" onclick="canc_otp(<? echo "document.forms.ulaForm.d_canc.value, ".$Bill_Dog.",'".
																				$row["Date_start"]."','".$row["Date_end"]."'" ?>);" value="измени" />
<?					 	}
					}
				} ?>
			</td>

<?php /*?>		<td bgcolor="<?php echo $bgcolor; ?>"><?php echo ($row["id_ActionType"]==4 and $row["Date_end"]>date("Y-m-d"))?'<input name="B_btn" type="button" onclick="canc_frz($Bill_Dog,'.date("d-m-Y", strtotime("+1 day")).');" value="отмени" /> с '.date("d-m-Y", strtotime("+1 day")):"";	?> </td><?php */?>
  </tr>		<?php 
		}
	?></table>
<? } else {
	echo "Платежей пока небыло";
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
	</div><?
}
//============================================================================
function form_phn($phone_Home, $phone_Cell, $phone_Work, $Jur) { 	//require($frm_phn); //============================================================================
/*if ($GLOBALS['totalRows_customer']>0) {
	$phone_Home = $arr_cust[$Bill_Dog1]['phone_Home'];
	$phone_Cell = $arr_cust[$Bill_Dog1]['phone_Cell'];
	$phone_Work = $arr_cust[$Bill_Dog1]['phone_Work'];
	$Jur = $arr_cust[$Bill_Dog1]['Jur'];
} else {
	$phone_Home = "";
	$phone_Cell = "";
	$phone_Work = "";
	$Jur = 0;
} */
?><table width="800" border=0>
<div id="phn">
  <tr>
    <td  align="center" valign="middle"><strong>телефоны:</strong>
    	домашний <input name="phone_Home" type="text" id="phone_Home" value="<? echo $phone_Home; ?>" size="7" onChange="adjustPhn();" />
        , сотовый <input name="phone_Cell" type="text" id="phone_Cell" value="<? echo $phone_Cell; ?>" size="16" onChange="adjustPhn();" />
        , рабочий <input name="phone_Work" type="text" id="phone_Work" value="<? echo $phone_Work; ?>" size="7" onChange="adjustPhn();" />
        , юридическое лицо <input name="Jur" type="checkbox" value="<? echo $Jur; ?>" onChange="adjustPhn();"/></td>
  </tr>
</div>
</table>
<?	}
function form_fio($Fam, $Name, $Father, $Birthday, $pasp_Ser, $pasp_Num, $pasp_Date, $pasp_Uvd, $pasp_Adr, $Comment) { //require($frm_fio); //============================================================================
/* if ($totalRows_customer>0) {
	$Fam = $arr_cust[$Bill_Dog1]['Fam'];
	$Name = $arr_cust[$Bill_Dog1]['Name'];
	$Father = $arr_cust[$Bill_Dog1]['Father'];
	$Birthday = $arr_cust[$Bill_Dog1]['Birthday'];
	$pasp_Ser = $arr_cust[$Bill_Dog1]['pasp_Ser'];
	$pasp_Num = $arr_cust[$Bill_Dog1]['pasp_Num'];
	$pasp_Date = $arr_cust[$Bill_Dog1]['pasp_Date'];
	$pasp_Uvd = $arr_cust[$Bill_Dog1]['pasp_Uvd'];
	$pasp_Adr = $arr_cust[$Bill_Dog1]['pasp_Adr'];
	$Comment = $arr_cust[$Bill_Dog1]['Comment'];
} else {
	$Fam = "";
	$Name = "";
	$Father = "";
	$Birthday = "";
	$pasp_Ser = "";
	$pasp_Num = "";
	$pasp_Date = "";
	$pasp_Uvd = "";
	$pasp_Adr = "";
	$Comment = "";
} */
?> 
    <table width="800" border=0>
	<div id="fio">
  <tr>
    <td align="center"><strong>Ф.И.О.:</strong> 
    <input name="Fam" type="text" id="Fam" onChange="adjastPasp();" value="<? echo $Fam; ?>" size="25" />
    <input name="Name" type="text" id="Name" onChange="adjastPasp();" value="<? echo $Name; ?>" size="24" />
    <input name="Father" type="text" id="Father" onChange="adjastPasp();" value="<? echo $Father; ?>" size="24" />
	Дата рождения: <input name="Birthday" type="text" id="Birthday" onChange="adjastPasp();" value="<? echo $Birthday; ?>" size="9" />	</td>
  </tr>
  <tr>
    <td align="right"><strong>Паспорт:</strong>
    	серия 
    	  <input name="pasp_Ser" type="text" id="pasp_Ser" onChange="adjastPasp();" value="<? echo $pasp_Ser; ?>" size="3" />
        номер <input name="pasp_Num" type="text" id="pasp_Num" onChange="adjastPasp();" size="5" value="<? echo $pasp_Num; ?>" />
        выдан <input name="pasp_Date" id="pasp_Date" type="date" onChange="adjastPasp();" value="<? echo $pasp_Date; ?>" size="9" />
        кем 
        <input name="pasp_Uvd" id="pasp_Uvd" type="text" onchange="adjastPasp();" value="<? echo $pasp_Uvd; ?>" size="38" />    
		<select name='_pasp_Uvd' id='_pasp_Uvd' onchange='document.forms["ulaForm"].pasp_Uvd.value=this.value;' class='headText' >
			  <option value="0" >-</option>
			  <option value="Талнахским ГОВД Норильского УВД Красноярского края" >Талнах</option>
			  <option value="УФМС Россиии г. Норильск по р-ну Талнах" >р-н Талнах</option>
			  <option value="УФМС России по г. Норильску" >Норильск</option>
			  <option value="УФМС России по г." >УФМС</option>
	  </select>    </td>
  </tr>
  <tr>
	<td align="right">
    	зарегистрирован по адресу:
		<input name="B_get_adress" type="button" onclick="document.forms.ulaForm.pasp_Adr.value=get_adress();" value="тот же" /><!--initialiseInputs();start_date();-->
   	  <input name="pasp_Adr" type="text" id="pasp_Adr" onChange="adjastPasp();" value="<? echo $pasp_Adr; ?>" size="75" />    </td>
  </tr>
  <tr>
	<td align="right"><strong>Примечание:</strong>
    <input name="Comment" type="text" id="Comment" value="<? echo $Comment; ?>" size="97" />    </td>
  </tr>
</div>
</table>
<?	}
// - - - - - - - - - - - - - - - - - - - - - - - -  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
function form_net($conn, $Bill_Dog, $id_tarifab, $tarifab_date, $Nic, $Date_start_st, $Date_end_st, $tp, $state, $Date_pay) { //require($frm_net); //============================================================================
//echo "$conn, $Bill_Dog, $id_tarifab, $tarifab_date, $Nic";
//	$conn = ($conn>0)?$conn:1;
	$id_tarifab = ($id_tarifab>0)?$id_tarifab:0;
	$tarifab_date = ($tarifab_date>0)?$tarifab_date:date("Y-m-d");
	$ar_s = array(''=>'не устан.', 0=>'не устан.', 1=>'подключен', 2=>'замороз.', 3=>'расторг');
	$m_pay = $GLOBALS['menu']=='pay';
	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
/* if ($totalRows_customer>0) {
	$conn = $arr_cust[$Bill_Dog1]['conn'];
	$Bill_Dog = $arr_cust[$Bill_Dog1]['Bill_Dog'];
	$id_tarifab = $arr_cust[$Bill_Dog1]['id_tarifab'];
	$tarifab_date = $arr_cust[$Bill_Dog1]['tarifab_date'];
	$Nic = $arr_cust[$Bill_Dog1]['Nic'];
} else {
	$conn = 0;
	$Bill_Dog = "";
	$id_tarifab = 0;
	$tarifab_date = "";
	$Nic = "";
} */
?>
<div id="net" style="background-color:#CCFFFF">
<table width="800" border=0>
  <tr>
    <td <?php echo ($m_pay?'width="40" align="left"':'colspan="3" width="450" align="right"');?>><strong>Сеть: </strong><?php if (!$m_pay) {?>&nbsp;Договор №<? }?>
      	<input name="Bill_Dog" id="Bill_Dog" value="<? echo $Bill_Dog; ?>" type="text" <?php echo ($m_pay?'style="display:none"':'');?> size="4" onchange='adj_Bill_Dog(this);'/>
		<?php if ($GLOBALS['menu']!='pay') {?>&nbsp;ник<? }?>
		<input name="Nic" type="text" <?php echo ($m_pay?'style="display:none"':'');?> id="Nic" onChange="adj_Nic(this);" value="<? echo $Nic; ?>" size="9" />
      	&nbsp;<?php echo ($m_pay?'':'&nbsp;подключение'); // ?>
	<select name='conn' id='conn' onchange='adj_Conn(this.value); ' class='headText' <?php echo ($m_pay?'style="display:none"':'');?> >
	<? 	$rslt = mysql_query("SELECT * FROM `spr_con_typ`") or die(mysql_error());
		$row_rslt = mysql_fetch_assoc($rslt);
		do {
			$op = $row_rslt['con_typ'];
			$able = ($op==5 ? !$GLOBALS['new_Cod'] :1)?"":"disabled='disabled' ";
			echo "<option value=".$op." ".$able.($op==$conn ? "selected":"").">".$row_rslt['typ_name']."</option>";
		} while ($row_rslt = mysql_fetch_assoc($rslt));
	?>
	</select>
	</td>
	<td<?php if(!$m_pay) {?> width="139"<? }?>>
	  <div id="con_tar" <?php echo ($m_pay?'align="left"':'');?>>тариф 
		<select name="id_tarifab" class='font8pt' id="id_tarifab" lang="ru" onchange='adj_con_tar();' <?php if($m_pay) {//disabled?><? }?> <?php echo ($m_pay?'style="display:none"':'');?>>	<?php
			$s_qer = "SELECT * FROM `spr_tarifab` WHERE `con_typ`=".$conn.(($conn=4)?" or (`con_typ`=1 and `con_sum`<500)":"")." order by `id_tarifab`";
			$rslt = mysql_query($s_qer) or die(mysql_error());
			$row_rslt = mysql_fetch_assoc($rslt);
			$rows = mysql_num_rows($rslt);
			$nm_tarif = "";
			echo "<option value=0>выбрать</option>";	//$i++
			do { 
			//		".strval($row_rslt['perstypes'])." ".strval($GLOBALS['TypePers'])."-  ($row_rslt['perstypes'] >= $GLOBALS['TypePers'])strval()
				echo "<option value=".$row_rslt['id_tarifab']." ".(($row_rslt['id_tarifab']==$id_tarifab)?"selected":"").($tp>$row_rslt['perstypes']?" disabled='disabled' ":"")." >".$row_rslt['name_ab']."</option>";
				if ($row_rslt['id_tarifab']==$id_tarifab) { $nm_tarif = $row_rslt['name_ab']; }
				$conn_tarifs[] = $row_rslt;
			 } while ($row_rslt = mysql_fetch_assoc($rslt));
			$rows = mysql_num_rows($rslt);	?>
		</select>	<?php echo ($m_pay?'<b>&nbsp;'.$nm_tarif.'&nbsp;</b>':'');?><? 
		echo '<input name="h_ts" type="hidden" value="'.$rows.'" />';
		foreach ($conn_tarifs as $t_row) {
			echo '<input id="h_opl_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['opl_period'].'" />';//
			echo '<input id="h_con_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['con_sum'].'" />';//
			echo '<input id="h_ab_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['ab_sum'].'" />';//
		}	?>
		</div>
	</td>
    <td align="left" <?php echo ($m_pay?'style="display:none"':'');//<div id="conn_pay" ></div>?>><input name="conn_pay" type="text" size="3" onchange="adj_CPay()" /> руб.
		<input name="tarifab_date" type="hidden" value="<? echo $tarifab_date; ?>" /><? echo $tarifab_date; ?></td>
<? if(!$m_pay) {?>
    </tr>
  </table>
	<table width="800" border=0>
	  <tr><td width="50"></td>
<? }?>
	<td <? if(!$m_pay){?>width="2"<? }?>><div id="con_s"></div></td> <!-- Сюда записать параметры подключения -->
	<td <? if(!$m_pay){?>width="190"<? }?> align="left"><div id="abon_pay">Абон.плата:
	<? if(!$m_pay){?>
		<input name="abon_pay" type="text" size="4" onchange="adj_CPay()" />руб.
	<? } else { 
		$m_ab = round(100*(1+1/($GLOBALS['ab_numbs']>0?$GLOBALS['ab_numbs']:1)));	//totalRows_customer
		echo  '<input name="opl_mon" value="'.$m_ab.'" size=4 />'," руб./мес."; // type="hidden"
	} ?> 
	</div></td>
	<? $ar_c = array(''=>'333333', '0'=>'333333', '1'=>'33CC66', '2'=>'0000FF', '3'=>'00FFFF'); ?>
	<? if($m_pay){?>
		<td align="center"><div id="state"><font style="border:solid" color="#<? echo $ar_c[$state] ?>">&nbsp;<? echo $ar_s[$state]; ?>&nbsp;
		c <input name="Date_start_st" value="<? echo $Date_start_st; ?>" type="hidden"/><? echo $Date_start_st;// ?>&nbsp;
	  по <input name="Date_end_st" value="<? echo $Date_end_st; ?>" type="hidden"/><? echo $Date_end_st; ?>&nbsp;</font></div></td>
	<? } else {?>
		<td <? if(!$m_pay){?><? }?> align="right"><div id="state"><font style="border:solid" color="#<? echo $ar_c[$state] ?>">&nbsp;<? echo $ar_s[$state]; ?>&nbsp;</font></div></td>
		<td <? if(!$m_pay){?>width="140"<? }?> align="left"><div id="Date_start_st"> c <? if(!$m_pay){?><input name="Date_start_st" value="<? } echo $Date_start_st; if(!$m_pay){?>" size="8" /><? }?></div></td>
		<td <? if(!$m_pay){?>width="140"<? }?> align="left"><div id="Date_end_st"> по <input name="Date_end_st" value="<? echo $Date_end_st; ?>" size="8" /></div></td>
	<? }?>
	<td <? if(!$m_pay){?><? }?> align="left"><div id="Date_pay"> оплачено по <input name="Date_pay" value="<? echo $Date_pay; ?>" size="8" <? if($m_pay){?>type="hidden"<? }?>/>
		<? if($m_pay){ echo '<b>'.(empty($Date_pay)?'___':date("j ", strtotime($Date_pay)).$m[date("n", strtotime($Date_pay))].' '.date("Y", strtotime($Date_pay)).'г.').'</b>';
		//$Date_pay; 
		}?></div></td>
	<td width="10"></td>
  </tr>
</table>
</div>  

<!--    <input name="dt3w2day2" type="button" id="dt3w2day2" onclick="javascript:document.forms['ulaForm'].tarifab_date.value=TODAY2" value="сегодня" /></td>-->
<?	}
function form_w3($From_Net, $Login, $id_tarif3w, $tarif3w_date) { //require($frm_w3); //============================================================================
	$m_pay = $GLOBALS['menu']=='pay';
/*if ($totalRows_customer>0) {
	$From_Net = $arr_cust[$Bill_Dog1]['From_Net'];
	$Login = $Logins[$Bill_Dog1][1]['Login'];
	$id_tarif3w = $Logins[$Bill_Dog1][1]['id_tarif3w'];
	$tarif3w_date = $Logins[$Bill_Dog1][1]['tarif3w_date'];
} else {
	$From_Net = "";
	$Login = "";
	$id_tarif3w = 0;
	$tarif3w_date = "";
}
*/?>
<div id="w3" border=1 style="background-color:#CCFFCC">
 <table width="800" border="0">
  <tr>
    <td <? if($m_pay){?>width="30"<? }?>align="<?php if ($m_pay) {?>right<? }else{?>left<? }?>"><strong>Интернет:</strong> <?php if (!$m_pay) {?>из сети <? }?>
   	  <input name="From_Net" id="From_Net" type="text" <?php echo $m_pay?'style="display:none"':'' ?> onChange="adjastNet();" value="<? echo $From_Net; ?>" size="7" /></td>
<?php /*?>	<td width="35" align="right" <?php echo (($GLOBALS['menu']=='pay')?'':'width="40"');?>>логин </td><?php */?>
	<td><div id="Login">
	<?php 	if (($GLOBALS['totalRows_customer']>0) && ($GLOBALS['Logins'][$GLOBALS['Bill_Dog1']]["Logins"]>0)) {
				$inp_sz = $GLOBALS['Logins'][$GLOBALS['Bill_Dog1']]["Logins"];
				echo '<table><tr><td rowspan="'.$inp_sz.'">логин ';
                echo '<select name="Login" id="Login" class="navText" size="'.$inp_sz.'" onchange="adjustLogin()" >';
                for($i=1; $i<=$inp_sz; $i++){
                    echo '<option value='.$GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][$i]['Login'].(($i ==1)?" selected":"").' >'.$GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][$i]['Login'].'</option>';
                }
                echo '</select></td>';
				echo '<td>',1*$GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][1]['saldo'],' руб.</td>';
				echo '<td rowspan="'.$inp_sz.'" valign="bottom">'.
					'<input name="addLogin" type="button" id="addLogin" onclick="faddLogin();" value="+"'.(($GLOBALS['menu']=='pay')?'style="display:none"':'').'/></td></tr>';
                for($i=2; $i<=$inp_sz; $i++){
					echo '<tr><td>',1*$GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][$i]['saldo'],' руб.</td></tr>'; //
				}
				echo '</table>';
            } else {
				echo '<input name="nic2login" type="button" id="nic2login" onclick= "f=document.forms[\'ulaForm\'];f.Login.value=f.Nic.value;adjastNet();" value="как ник" /><input name="Login" type="text" value="'.$Login.'" onChange="adjastNet();" size="12" />';
			}
//			echo $str_L;
	?>
    </div></td>
	<td align="left" <?php echo $m_pay?'style="display:none"':'' ?>><div id="addLogin"></div></td>
	<td align="center"><?php if ($GLOBALS['menu']!='pay') {?>тариф<? }?>
    	<select name="id_tarif3w" class='headText' id="id_tarif3w" onchange='adjustTarif3w();' <?php echo $m_pay?'style="display:none"':'' ?>>
      <?php
	  	$q_Tarif3w = "SELECT * FROM spr_tarif3w";
		$Tarif3w = mysql_query($q_Tarif3w) or die(mysql_error());
		$row_Tarif3w = mysql_fetch_assoc($Tarif3w);
		$totalRows_Tarif3w = mysql_num_rows($Tarif3w);
	do {
		echo "<option value=".$row_Tarif3w['id_tarif3w']." ".
			(($row_Tarif3w['id_tarif3w']==$id_tarif3w)?"selected":"").">".$row_Tarif3w['name_3w']."</option>";
    } while ($row_Tarif3w = mysql_fetch_assoc($Tarif3w));
  	$rows = mysql_num_rows($Tarif3w);
  	if($rows > 0) { mysql_data_seek($Tarif3w, 0); $row_Tarif3w = mysqli_fetch_assoc($Tarif3w);  } ?>
      </select></td>
	<td align="left" <?php echo (($GLOBALS['menu']=='pay')?'style="display:none"':'');?>>установлен с 
    <input name="tarif3w_date" id="tarif3w_date" value="<? echo $tarif3w_date; ?>" type="date" onChange="adjastNet();" size="10" /><!-- value=< ?php $DateNotify=date("Y-m-d"); echo $DateNotify ? >-->
<!--    <input name="dt3w2day" type="button" id="dt3w2day" onclick="javascript:document.forms['ulaForm'].tarif3w_date.value=TODAY2" value="сегодня" /></td>-->
	<script language="JavaScript" type="text/javascript">
		document.write('OOOOOO<a title="Календарь" href="javascript:openCalendar(\'\', \'ulaForm\', \'tarif3w_date\', \'date\')"><img class="calendar" src="b_calendar.png" alt="Календарь"/></a>');
	</script>
    <td <?php echo (($GLOBALS['menu']=='pay')?'width="300"':'width="8"');?>></td>
  </tr>
 </table>
</div>
<div id="new_adr"></div>
<? }
//}
/*"&*/
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

function print_hid($id, $key, $val)
{
	$inp_name = 'h_'.$id.'_'.$key; //$inp_name.' = '.
//	echo $inp_name."=".$val.", ";	//</br>
  	echo '<input name="'.$inp_name.'" id="'.$inp_name.'" value="'.$val.'" type="hidden" />';//
}

function print_arr($ar)
{
	foreach ($ar as $k => $v) {
		echo '</br>['.$k.']:</br>';
		if (is_array($v)){ echo "||";
			foreach ($v as $key => $val) {
				if (is_array($val)){ echo "||";
					foreach ($val as $key2 => $val2) {
						echo '['.$key2.']='.$val2.',';
					}
				} else { echo '['.$key.']= '.$val.' '; }
			}
		} else { echo $v; }
	}
}
/*========================================================================
/*    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        print "\t<tr>\n";
        foreach ($line as $col_value) {
            print "\t\t<td>$col_value</td>\n";
        }
        print "\t</tr>\n";
    }
*/ 
/*<table width="200" border="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>*/
?>

