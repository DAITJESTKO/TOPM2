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
		}
	} else {
		$k = $GLOBALS['k'];
		$fl = $GLOBALS ["fl"];
		$Town = $GLOBALS ["Town"];
		$name_street = $GLOBALS ["name_street"];
		$id_Podjezd = $GLOBALS ["id_Podjezd"];
		?>
		<span COLOR="#FF0000" class="quote"><? echo "г.$Town ул.$name_street д.$Num_build кв.$fl"; ?> </span>
		<?
	}
	$Nic = (isset($_REQUEST ["Nic"]))? $_REQUEST ["Nic"]:"";
	$new = (isset($_REQUEST ["new"]))? $_REQUEST ["new"]:"";
	$not_new_adr = !(isset($_REQUEST ["menu"]) && (!$_REQUEST ["menu"]=="new_adr"));
	$tp = (isset($_REQUEST['tp']))? $_REQUEST['tp']:(isset($GLOBALS['tp']))? $GLOBALS['tp']:"9";
	if (isset($GLOBALS['tn'])) {
		$tn = $GLOBALS['tn'];
	} else {
		$tn = (isset($_REQUEST['tn']))? "tn=".$_REQUEST['tn']."&":"";
	}
//	$par = $tn.$tp.$trg.$new_p.$menu_p;
	$GLOBALS['tp'] = $tp;
	$ToDay = date("Y-m-d");
	$ar_s = array(''=>'не уст.', 0=>'не уст.', 1=>'подключ.', 2=>'замороз.', 3=>'расторг');
	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
	$Bill_Dog_New = get_Bill_Dog();
	//echo (isset($_REQUEST ["menu"])?"menu:".$_REQUEST ["menu"]:"#");
	if (isset($GLOBALS['menu'])) $p1 = $GLOBALS['menu']; else $p1 = "no menu";
	$q_podjezd = "SELECT * FROM v_podjezd where id_korp=$k and FirstFlat<=$fl and LastFlat>=$fl";

	$s_podjezd =  mysql_query($q_podjezd) or die(mysql_error());
	$row_podjezd = mysql_fetch_assoc($s_podjezd);
	if ($totalRows_podjezd = mysql_num_rows($s_podjezd)==0) { 
		echo "<br>Ошибка в справочнике адресов (таблица зданий). Не найден подъезд с кв $fl, id_korp=$k.";
		return;
	}
	$RegionName = $row_podjezd['RegionName'];
	$name_street = $row_podjezd["name_street"];
	$Num_build = $row_podjezd['Num_build'];
	$id_Podjezd = $row_podjezd['id_Podjezd'];
	$Podjezd = $row_podjezd['Podjezd'];
	$Korpus = $row_podjezd['Korpus'];
	$id_p = $id_Podjezd;

/* $Bill_Dog = 0;
 if (isset($Podjezd)) { */
/* if (!$not_new_adr) { //$Nic!=""
	$q_customer = "SELECT * FROM v_customer where Nic='$Nic'"; 
 } else*/ $q_customer = "SELECT * FROM v_customer where ".($not_new_adr?"id_Podjezd=$id_Podjezd and flat =$fl order by inet":"Nic='$Nic'");

	$s_customer =  mysql_query($q_customer) or die($s_customer.mysql_error());
	$row_customer = mysql_fetch_array($s_customer, MYSQL_ASSOC);
	$totalRows_customer = mysql_num_rows($s_customer);
	$new_Cod = check_flat($id_p, $fl);
	$Cod_flat = $new_Cod ? 0 /*new_Cod_flat()*/ : get_Cod_flat($id_p, $fl);		//$row_customer['Cod_flat'];		//($row_customer['Cod_flat'] == 0)
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
	echo '<b><FONT '.($row_podjezd['auto']==0? 'COLOR="#FF0000"> МОНТ.':'> авто').' </FONT></b>';
	echo '<b>Код адреса: <FONT '.($new_Cod? 'COLOR="#FF0000">'.
		/*$Cod_flat.*/' новый':'>'.$Cod_flat).'.</FONT> Договоров - '.$totalRows_customer.'</b>';
	if ($totalRows_customer > 0) {?>
		<button name="B_reload" type=button onClick="clr_adress();chk_adress();">
        					<img src="reload.png" align=middle alt="Обнови"></button>
<?	}
	echo "<input name='h_new_Cod' type='hidden' value='".( $new_Cod? 1 : 0)."' />";	//($totalRows_customer == 0 || $row_customer['Cod_flat']==0)
	echo "<input name='h_Cod_flat{$new}' type='hidden' value=$Cod_flat />";
	echo "<input name='h_Conn' type='hidden' value='".(($totalRows_customer == 0) ? 0 : 1)."' />";
	//	echo '<b><font class="quote"> </font>ул.'.$row_customer['name_street'].' д.'.$row_customer['Num_build'];
	if ($_REQUEST ["menu"] == "new_adr" and !isset($_REQUEST ["Nic"])) {
		?> <input name="B_edt_cust" type="button" onclick="set_new_adr();" value="Перевести на этот адрес" /><? //';
	}
	if ($totalRows_customer!=0) {
		
//			$GLOBALS['a_cus'][$row_customer['Bill_Dog']]['rad'] = isRadreply($row_customer['mac']);
//$conn_db = db_connect();
//			$Bill_Dog = $row_customer['Bill_Dog']; ?>
	<table align="center" border=0 width=100%>
  		<tr>
  		  <?	//	$s_onchange='"clr_adress(); show_cust('.$Bill_Dog.','.$id_p.','.$fl.'); f_btn();"';
		$s_onchange='"clr_adress(); chk_adress();"'; 
/*	<td  rowspan="2"><select name="tabl_cust" id="tabl_cust" class="headText" size="<? echo $totalRows_customer ?>"<? echo ($not_new_adr?' onchange='.$s_onchange:'')?> > <?	*/ ?>
  		  <td ><!-- rowspan="2"-->
    	<select name="tabl_cust" id="tabl_cust" class="headText" size="<? echo ''.$totalRows_customer.''?>"
        	<? echo	 ($not_new_adr?'onchange='.$s_onchange:'')?> >
<?		//,this.value //,document.getElementById(&quot;rbtn_cust&quot;).value
		$Bill_Dog1 = $row_customer['Bill_Dog'];
		$inet1 = $row_customer['inet'];
		$ab_numbs = 0;
		$tot_ab = 0;
		$ab_sum = $row_customer['ab_sum']>0?$row_customer['ab_sum']:0;
		do {
			$i +=1;
			$Bill_Dog = $row_customer['Bill_Dog'];
//			$F = ($tp<3?$row_customer['Fam']:chr(ord(($row_customer['Fam'])).'.');
			$Fio = $row_customer['Fam'].' '.$row_customer['Name'].' '.$row_customer['Father'];
//			.' '.($tp<3?$row_customer['Fam']:substr($row_customer['Fam'],1,1).'.', ENT_NOQUOTES,"UTF-8");
			$Y = date("Y", strtotime($row_customer["Date_start_st"]));
			if ($ab_sum==0) { if (!$row_customer['inet']) { $ab_sum = $row_customer['ab_sum']; }}
			$GLOBALS['a_cus'][$Bill_Dog] = $row_customer;
			$GLOBALS['a_cus'][$Bill_Dog]['rad'] = isRadreply($row_customer['mac']);
$conn_db = db_connect();
			$GLOBALS['a_cus'][$Bill_Dog]['dolg'] = is_off_dolg($Bill_Dog) && $ab_sum>0;
			$ab_numbs = $ab_numbs + ($row_customer['inet'] || $GLOBALS['a_cus'][$Bill_Dog]['dolg']?0:1);
			$tot_ab = $tot_ab + ($row_customer['inet']?0:1);
			echo '<option value='.$Bill_Dog.(($i ==1)?" selected":""); //Num_PC Bill_Dog Num &#009;
			$s_1 = 'Дог.№'.$Bill_Dog.($row_customer['inet']?"(инет)":"").', '.$ar_s[$row_customer['state']].' '.
				(!empty($row_customer['Date_end_st'])?'по '.date("j ", strtotime($row_customer['Date_end_st'])).$m[date("n", strtotime($row_customer['Date_end_st']))].(date("Y")==$Y?'':' '.$Y.'г.'):
					($GLOBALS['a_cus'][$Bill_Dog]['dolg']?' за долг':'')).
				', ник: '.$row_customer['Nic'].'&#009;: '.$Fio; 
			echo ' >'.$s_1.'</option>';
		} while ($row_customer = mysql_fetch_array($s_customer, MYSQL_ASSOC));
		//echo ' ?>
		</select>
    </td></tr></table>
    <table><tr>
<? //!	} elseif (isset($_REQUEST ["Nic"])||$_REQUEST ["menu"]=="show_err"){//<button id="sel_Bill" type="button">уст</button>	?>
	<td><div id="d_show_err" style="display:none"><table><tr><td>
<?		$noNic = !isset($_REQUEST ["Nic"]);
		foreach ($a_cus as $cust_row) {?>
       <!--     <button name='B_chng' type='button' onClick='alert(document.forms.ulaForm.hNic.value)' >       -->
			<label><input name="sel_Dog" type="radio" <? if(!$cust_row['inet']){?>disabled="disabled"<? }?>
                	value="<? echo $cust_row['Bill_Dog']?>"
                    onchange="document.getElementById('b_chng').innerHTML='<button name=\'B_chng\' type=\'button\' onClick=\'if(confirm(&quot;Вы согласны выполнить следущее: В абон учётке Ник <? echo $cust_row["Nic"]?> будет заменён на <? if ($noNic) {?>'+document.forms.ulaForm.hNic.value+'<? } else { echo $_REQUEST["Nic"];}?>, в таблице интернет логинов для Логина <? echo $cust_row["Nic"]?> будет изменён Ник на <? if ($noNic) {?>'+document.forms.ulaForm.hNic.value+'<? } else { echo $_REQUEST["Nic"];}?>. Вы уверены? &quot;)){ch_param(&quot;do_chng_nic&quot;,&quot;<? echo "tn=".$tn."&Bill_Dog=".$cust_row['Bill_Dog']."&newNic="?><? if($noNic) {?>'+document.forms.ulaForm.hNic.value+'<? } else { echo $_REQUEST["Nic"];}?><? echo '&oldNic='.$cust_row["Nic"].'&TabNum=' ?>'+document.forms.ulaForm.TabNum.value+'&quot;, &quot;new_adr<? if ($noNic) {?>'+document.forms.ulaForm.hNic.value+'<? } else { echo $_REQUEST["Nic"];}?>&quot;);}   \'><b><? if ($noNic) {?>'+document.forms.ulaForm.hNic.value+'<? } else { echo $_REQUEST ["Nic"];}?></b> -> Аб.ник<br><b><? echo $cust_row["Nic"]?></b> -> Инет.логин</button>'" />
					Дог.№ <? echo $cust_row['Bill_Dog']?>
                </label><br />
<?		}?></td>
		<td><div id="b_chng"><? if(!$noNic){echo "{$_REQUEST['Nic']} -> Абон.ник<br>{$cust_row['Nic']} -> Инет.логин";}?></div>
<?	//!	}	?>
		</td></tr></table>
</div></td>
<?		if ($_REQUEST ["menu"] != "show_err") { ?>
<?php /*?><b><font style="font-size:14px">&nbsp;
	<label id="lrec"><input name="B_frm" type="radio" onclick="f_frm(this.value);" value="rec"/> Сеть </label>&nbsp;
	<label id="lpay"><input name="B_frm" type="radio" onclick="f_frm(this.value);" value="pay"/> Финансы </label>&nbsp;
	<label id="lnot"><input name="B_frm" type="radio" onclick="f_frm(this.value);" value="not"/> Заявка на ремонт </label>&nbsp;
</font></b><?php */?>
			<b><font style="font-size:14px">
			<td id="L_rec" bgcolor="#CCFFFF"><div>Сеть</div></td>
            <td id="B_rec" style="display:none"><button name="B_rec" type=button
            	onClick="shw('L_rec');hid('B_rec');hid('L_pay');shw('B_pay');hid('L_not');shw('B_not');
                	shw('phn');shw('fio');shw('net');shw('w3');shw('rec_itog');hid('noti2rep');hid('fin');
                    hid('d_b_pay');shw('d_b_rec');">
                Сеть</button></td>
			<td id="L_pay" bgcolor="#FFCC99" style="display:none"><div>Финанcы</div></td>
            <td id="B_pay"><button name="B_pay" type=button 
            	onClick="shw('L_pay');hid('B_pay');hid('L_rec');hid('L_not');shw('B_rec');shw('B_not');
                   	hid('phn');hid('fio');shw('net');shw('w3');hid('rec_itog');hid('noti2rep');shw('fin');
                    shw('d_b_pay');hid('d_b_rec');">
                Финанcы</button></td>
			<td id="L_not" bgcolor="#99FFFF" style="display:none"><div>Заявка на ремонт</div></td>
            <td id="B_not"><button name="B_not" type=button 
            	onClick="hid('L_rec');shw('B_rec');hid('L_pay');shw('B_pay');shw('L_not');hid('B_not');
            		hid('phn');hid('fio');hid('net');hid('w3');hid('rec_itog');shw('noti2rep');hid('fin');
                    hid('d_b_pay');hid('d_b_rec');">
            Заявка на ремонт</button></td>
<?		} //hid('B_pay');shw('B_rec');?>
</font></b>
    <td id="d_b_rec">
		<? //';// valign="baseline"
		echo "<input name='h_ab_numbs' value=$ab_numbs type='hidden' />";
		echo "<input name='h_tot_ab' value=$tot_ab type='hidden' />";
	 //!		if ($_REQUEST ["menu"] == "recon") {//
//			$GLOBALS['Bill_Dog_New'] = get_Bill_Dog();	.$GLOBALS['Bill_Dog_New']. form=document.forms[&quot;ulaForm&quot;]; 
//			echo //' <input name="B_edt_cust" type="button" onclick='.$s_onchange.' value="Показать" />';phone_Home=form.phone_Home.value; 
?>		&nbsp;<button name="B_add_cust" type=button onClick="javascript:add_cust('<? echo $Bill_Dog_New ?>');">
            	<img src="ico_create.gif" align=middle alt="Доп.подкл."></button>&nbsp;
			<button name="B_del_cust" type=button 
            	onClick="javascript:B_D=f_Bill_Dog();tn=<? echo $tn?>;if(confirm(&quot;Вы согласны удалить договор№&quot;+B_D+&quot; в архив? &quot;)){ch_param('do_del_cust','Bill_Dog='+B_D+'&tn='+tn,'tab_Cust');}"><img src="ico_delete.gif" align=middle alt="Удалить"></button></td>
		  <td id="d_b_pay" style="display:none">
          	<div><table><tr>
	<?php /*?>		<button name="B_print" type=button onClick="javascript:dogovor();"><img src="printer.gif" width="16" alt="Печать"></button><?php */?>
<?php /*?>			<button name="B_pays" type=button onClick="javascript:document.forms.ulaForm.Menu_Item.value='pay';ch('srch','menu=pay&tp=<? echo $tp.($new_Cod?'&Bill_Dog='.$Bill_Dog1:'&Cod_flat='.$Cod_flat)?>',0,'tab_Cust');">Финан</button><?php */?>
<?	//	} elseif ($_REQUEST ["menu"] == "pay") {	?>
		<?php /*?>	<button name="B_pays" type=button onClick="document.forms.ulaForm.Menu_Item.value='recon';ch('srch','menu=recon&tp=<? echo $tp?>&Bill_Dog=<? echo $Bill_Dog1?>',0,'tab_Cust');">Сеть</button><?php */?>
<?		if($tp==1 || $tn==6 || $tn==8) { // (!$inet1 && ($tn==2 || $tn==6)) ?>
            <td>
                <button name="B_dhd" type=button 
                    onClick="toggle('dhd');c=document.forms.ulaForm.B_dhd;c.caption=(c.caption=='◄')?'►►':'◄'">►►</button>
			</td>
			<td><div id="dhd" style="display:none"><table><tr>
				<td><? echo $Bill_Dog1?>:</td>
<?			if($inet1) {?>
        		<td><div id="toab"><button name="B_2ab" type=button onClick="javascript:f=document.forms.ulaForm;f.sBill_Dog.value=<? echo $Bill_Dog1?>;ch_param('toab','B=<? echo $Bill_Dog1?>','toab');">Аб.</button></div></td>
<?         } else { ?>
        		<td><div id="otkat" style="background-color:#9FF">
            	&nbsp;<button name="B_otkat" type=button onClick="javascript:otkat()">Откт</button>&nbsp;</div></td>
        		<td><div id="toinet" style="background-color:#FCC"><button name="B_2inet" type=button onClick="javascript:f=document.forms.ulaForm;f.sBill_Dog.value=<? echo $Bill_Dog1?>;ch_param('toinet','B=<? echo $Bill_Dog1?>','toinet');setTimeout('f.sBill_Dog.onchange()', 500);">3w</button></div></td>
        <? } ?>
        <!--dhd--><?
		$d_test = strtotime ("2000-01-01");
		if ($GLOBALS['a_cus'][$Bill_Dog1]['state']==1 && (strtotime($GLOBALS['a_cus'][$Bill_Dog1]['Date_end_st']) < $d_test || strtotime($GLOBALS['a_cus'][$Bill_Dog1]['Date_pay']) < $d_test) ) { 
			$D_e = strtotime ($GLOBALS['a_cus'][$Bill_Dog1]['Date_end_st'])<$d_test?"de":"";
			$D_p = strtotime ($GLOBALS['a_cus'][$Bill_Dog1]['Date_pay'])<$d_test?"dp":"";
			if($D_e=="" || $D_p=="") {
?>        		<td><div id="d_cor" style="background-color:#F99"><button type=button onClick="javascript:ch_param('d_cor','B=<? echo $Bill_Dog1."&d=".$D_e==""?"p":"e"?>','d_cor');setTimeout('f.sBill_Dog.onchange()', 500);">Дата</button></div></td>
<?			}
		}	?></tr></table>
	</div><!-- dhd -->
<?	 } ?>
    </td></tr></table>
</div> </td><!--d_b_pay -->
</tr></table>	
<table><tr>
	<?	// Заполняем массив данных
		foreach ($a_cus as $cust_row) {
			$id = $cust_row['Bill_Dog'];
			// Получаем все Логины для Ника
			$q_login = "SELECT Login,id_tarif3w,tarif3w_date,saldo,account FROM `logins` where Bill_Dog=".$id;	//Nic='".$cust_row['Nic']."'"
			$s_login =  mysql_query($q_login) or die(mysql_error());
			$totalRows_login = mysql_num_rows($s_login);
			$Logins[$cust_row['Bill_Dog']]["Logins"] = $totalRows_login;
			$inp_name = "h_".$cust_row['Bill_Dog']."_Logins";
		//	echo $inp_name."=".$val."</br>";
?>        <div id="d<? echo $id?>" style="display:none">d<? echo $id?></div>	<?
			echo '<input name="'.$inp_name.'" id="'.$inp_name.'" type="hidden" value="'.$totalRows_login.'" />';//$inp_name.
			if ($totalRows_login >0) {
				$j = 1;
				while ($row_login = mysql_fetch_array($s_login, MYSQL_ASSOC)) {
					$Logins[$cust_row['Bill_Dog']][$j] = $row_login;
					foreach ($row_login as $key => $val) {
//						$inp_name = "h_".$cust_row['Bill_Dog']."_".$key.$j;
					//	echo $inp_name."=".$val."; ";
//						echo '<input name="'.$inp_name.'" id="'.$inp_name.'" type="hidden" value="'.$val.'" />';//
						print_hid($id, $key.$j, $val);
					}
					$j++;
				}
			} else { // не найдены логины
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
		$GLOBALS['a_cus']['ab_sum'] = $ab_sum;
	//	print_r($a_cus);
		//echo '</br>print_arr(a_cus):</br>';
		//print_arr($a_cus);
		//echo '</br>print_r(Logins) ==========================================================</br>';
		//print_r($Logins);
		
		echo '</td></tr>';

$frm_phn = $rem_addr."frm_phn.php?phone_Home=".$a_cus[$Bill_Dog1]['phone_Home']."&phone_Cell=".
	$a_cus[$Bill_Dog1]['phone_Cell']."&phone_Work=".$a_cus[$Bill_Dog1]['phone_Work'];

$frm_fio = $rem_addr."frm_fio.php?Fam=".$a_cus[$Bill_Dog1]['Fam']."&Name=".$a_cus[$Bill_Dog1]['Name'].
	"&Father=".$a_cus[$Bill_Dog1]['Father']."&Birthday=".$a_cus[$Bill_Dog1]['Birthday'].
	"&pasp_Ser=".$a_cus[$Bill_Dog1]['pasp_Ser']."&pasp_Num=".$a_cus[$Bill_Dog1]['pasp_Num'].
	"&pasp_Date=".$a_cus[$Bill_Dog1]['pasp_Date']."&pasp_Uvd=".$a_cus[$Bill_Dog1]['pasp_Uvd'].
	"&pasp_Adr=".$a_cus[$Bill_Dog1]['pasp_Adr']."&Comment=".$a_cus[$Bill_Dog1]['Comment'];

$frm_net = $rem_addr."frm_net.php?conn=".$a_cus[$Bill_Dog1]['conn']."&Bill_Dog=".$a_cus[$Bill_Dog1]['Bill_Dog'].
	"&id_tarifab=".$a_cus[$Bill_Dog1]['id_tarifab']."&tarifab_date=".$a_cus[$Bill_Dog1]['tarifab_date'].
	"&Nic=".$a_cus[$Bill_Dog1]['Nic'];

//$frm_w3 = $rem_addr."frm_w3.php?Login=".$Logins[$Bill_Dog1][1]['Login']."&From_Net=".$a_cus[$Bill_Dog1]['From_Net'].
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
	$phone_Home = $a_cus[$Bill_Dog1]['phone_Home'];
	$phone_Cell = $a_cus[$Bill_Dog1]['phone_Cell'];
	$phone_Work = $a_cus[$Bill_Dog1]['phone_Work'];
	$Jur = $a_cus[$Bill_Dog1]['Jur'];

	$Fam = $a_cus[$Bill_Dog1]['Fam'];//$tp<3?$row_customer['Fam']:html_entity_decode(chr(ord($a_cus[$Bill_Dog1]['Fam'])).ord($a_cus[$Bill_Dog1]['Fam']).'.');//($tp<3?$row_customer['Fam']:(chr(ord($row_customer['Fam'])).'.'))
	$Name = $a_cus[$Bill_Dog1]['Name'];
	$Father = $a_cus[$Bill_Dog1]['Father'];
	$Birthday = $a_cus[$Bill_Dog1]['Birthday'];
	$pasp_Ser = $a_cus[$Bill_Dog1]['pasp_Ser'];
	$pasp_Num = $a_cus[$Bill_Dog1]['pasp_Num'];
	$pasp_Date = $a_cus[$Bill_Dog1]['pasp_Date'];
	$pasp_Uvd = $a_cus[$Bill_Dog1]['pasp_Uvd'];
	$pasp_Adr = $a_cus[$Bill_Dog1]['pasp_Adr'];
	$Comment = $a_cus[$Bill_Dog1]['Comment'];

	$conn = $a_cus[$Bill_Dog1]['conn']==''?0:$a_cus[$Bill_Dog1]['conn'];
	$Bill_Dog = $a_cus[$Bill_Dog1]['Bill_Dog'];
	$id_tarifab = $a_cus[$Bill_Dog1]['id_tarifab'];
	$tarifab_date = $a_cus[$Bill_Dog1]['tarifab_date'];
	$Nic = $a_cus[$Bill_Dog1]['Nic'];
//	$ = $a_cus[$Bill_Dog1][''];
	$Date_start_st = $a_cus[$Bill_Dog1]['Date_start_st'];
	$Date_end_st = $a_cus[$Bill_Dog1]['Date_end_st'];
	$state = $a_cus[$Bill_Dog1]['state'];
	$Date_pay = $a_cus[$Bill_Dog1]['Date_pay'];
	
	$rad = $a_cus[$Bill_Dog1]['rad'];
	$From_Net = $a_cus[$Bill_Dog1]['From_Net'];
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
	$Bill_Dog = "новый";	//get_Bill_Dog();//""
	$Bill_frend = "";
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
	$rad = 0;
	$tarif3w_date = $ToDay;
}
$disp_rec=$GLOBALS['menu'] == 'recon'?'':'style="display:none"';
$disp_pay=$GLOBALS['menu'] == 'pay'?'':'style="display:none"';
$disp_not=$GLOBALS['menu'] == 'noti'?'':'style="display:none"';
?><div id="recon" <? echo $disp_rec;?>> <?
/*if ($GLOBALS['menu'] == 'recon') {*/
	form_phn($phone_Home, $phone_Cell, $phone_Work, $Jur);
	form_fio($Fam, $Name, $Father, $Birthday, $pasp_Ser, $pasp_Num, $pasp_Date, $pasp_Uvd, $pasp_Adr, $Comment);
	form_net($conn, $Bill_Dog, $id_tarifab, $tarifab_date, $Nic, $Date_start_st, $Date_end_st, $tp, $state, $Date_pay, $GLOBALS['totalRows_customer']>0?$a_cus:"");
	form_w3($From_Net, $Login, $id_tarif3w, $tarif3w_date, isset($a_cus)?$a_cus:"");
?>
<div id="rec_itog">
<table width="800" border=0>
  <tr <? if($GLOBALS['tp']>2) echo 'style="display:none"'?>>
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
</div>
</div>
<?php
/*} elseif ($GLOBALS['menu'] == 'noti') { /******************************************************/
?><div id="noti2rep" <? echo $GLOBALS['menu'] == 'noti'?'':'style="display:none"'?>><?
	form_phn($phone_Home, $phone_Cell, $phone_Work, $Jur);
	?><td>Дополнительный телефон: <input name="phone_Dop" size="15" /></td>
<table width="800" border=0>
  <tr>
    <td colspan="3" align="right">Неисправность</td>
    <td colspan="4"><input name="noti" type="text" size="70" onchange='chk_noti();' />
		<select name='_noti' id='_noti' onchange='document.forms["ulaForm"].noti.value=this.value;chk_noti();' class='headText' >
		  <option value="0" >-</option>
		  <option value="Нет связи" >Нет связи</option>
		  <option value="Обрыв линии" >Обрыв</option>
		  <option value="Неизвестная причина" >Неизвестная</option>
		</select>
</td>
  </tr>
</table>
<table width="800" border=0>
  <tr>
<?php /*?>	<td width="161" align="right">передать монтажнику: </td>
		<td width="62"><select name="mont" class='font8pt' id="mont" lang="ru" onchange='chk_noti();'>
	<?php	$q_mont = "SELECT * FROM `personal` WHERE `id_TypePers`=4";
			$mont = mysql_query($q_mont) or die(mysql_error());
			$row_mont = mysql_fetch_assoc($mont);
			$totalRows_mont = mysql_num_rows($mont);
		echo "<option value=0>выбрать</option>";
	do {
		echo "<option value=".$row_mont['TabNum'].">".$row_mont['Fam']." (таб.№ ".$row_mont['TabNum'].")</option>";
			} while ($row_mont = mysql_fetch_assoc($mont));
			$rows = mysql_num_rows($mont);
			if($rows > 0) { mysql_data_seek($mont, 0); $row_mont = mysqli_fetch_assoc($mont);  } ?>
		</select>    </td>  <?php */?>  
		<td width="189" align="right">Плановая дата выполнения:  </td>
		<td width="220" valign="middle"><input name="Date_Plan" type="date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("m"),date("d")/*+3*/,date("Y"))); ?>" size="1" onchange='chk_noti();' />
	<!--    , факт <input name="Date_Fact" type="date" size="8" />-->
	</td>
  </tr>
</table>
</div>
<?
//} elseif ($GLOBALS['menu'] == 'pay') { 
?><div id="fin" <? echo $GLOBALS['menu'] == 'pay'?'':'style="display:none"'?>><?
/*  
	form_net($conn,$Bill_Dog,$id_tarifab,$tarifab_date,$Nic,$Date_start_st,$Date_end_st,$tp,$state,$Date_pay,$a_cus);
	form_w3($From_Net, $Login, $id_tarif3w, $tarif3w_date, $a_cus);
*/	$n_cod = $GLOBALS['new_Cod'] == 0;	
	$c_ar=$a_cus[$Bill_Dog];	
	$is_dolg=($state==2 && $Date_end_st==''/* && $c_ar['auto']==0)||($c_ar['auto']==1 && $state==0*/); 
//	$is_dolg=(($state=$c_ar['state'])==2 && ($Date_end_st=$c_ar['Date_end_st'])=='') /*&& ($d_st > (strtotime("+1 day",$d_py)))*/;// && $cust['auto']==0)||($cust['auto']==1 && $state==0); 
	$can_auto = $c_ar['auto']==1 && $state >0;
				$d_st = strtotime($Date_start_st	/* = $c_ar['Date_start_st']*/);
				$d_py = strtotime($Date_pay 		/* = $c_ar['Date_pay']*/);
	$m_dolg = /*($state==2 && $Date_end_st=='')*/$is_dolg?($d_st - (strtotime("+1 day",$d_py)))/60/60/24:0;
//	if {
///		$m_dolg = ($state==2 && $Date_end_st=='')?/**/(strtotime($Date_start_st) - strtotime("+1 day",$Date_pay))/60/60/24:0/**/;
//	}
//	if ($a_cus[$Bill_Dog]['inet']==1){//cust_row !$n_cod?>
		<div id="it_inet" align="center"<? if (!($GLOBALS['menu'] == 'pay' && $inet1==1)) { echo 'style="display:none"'; }?>><b><font size="3"><u>
        	Интернет учётка!</br>Установите соотвествие сетевому нику из Ошибок базы</u></font></b></div>
	<? // } ?>
</br>

<div id="sel" <? if ($inet1==1){ echo 'style="display:none"'; } // $a_cus[$Bill_Dog]['inet']?>>
<? $dolg = $a_cus[$Bill_Dog]['dolg']; ?><? // echo $dolg?'hist_pay':''; ?>
<input name="sel" type="hidden" value="all" /><input name="selBill" type="hidden" value="<? echo $Bill_Dog?>" />
<b><font style="font-size:14px">&nbsp;
	<? $tp_ds = $tp>2?' disabled="disabled"':'' ?>
	<label id="lpay"><input name="B_sel" type="radio" onclick="f_sel(this.value);" value="pay"<? echo $tp_ds?>/> Платёж </label>&nbsp;
	<label id="lall" style="background-color:#CCFF99"><input name="B_sel" type="radio" onclick="f_sel(this.value);" value="all" checked<? echo $tp_ds?>/> Платёж поровну </label>&nbsp;
	<label id="lfrz"><input name="B_sel" type="radio" onclick="f_sel(this.value)" value="frz" /> Заморозить </label>&nbsp;
	<label id="lhist_pay"><input name="B_sel" type="radio" onclick="f_sel(this.value)" value="hist_pay"/> Платежи </label>&nbsp;
	<label id="lhist_cod"><input name="B_sel" type="radio" onclick="f_sel(this.value)" value="hist_cod"/> Смены адреса </label>&nbsp;
	<label id="lhist_not"><input name="B_sel" type="radio" onclick="f_sel(this.value);" value="hist_not" /> Операции и заявки </label>
</font></b>

<?php /*?><table width="800" border=0>
  <tr>
	<td bgcolor="#66FF99"><?php */?>
    <div id="pay" <? echo 'style="display:none"' ?>>
		<table width="800" border=0 cellpadding="2" cellspacing="2" bgcolor="#66FF99">
		  <tr>
			<td border=1 height="30" colspan="7" align="center" valign="middle"><font size="3"><b>Платёж по договору </b>
		    	<?php /*?> bgcolor="#660066" color="#FFFF99"<input name="all_dog" type="checkbox" value="1"/><?php */?></font></td>
			<td colspan="1" align="left"><font size="3"><b>Примечания</b></font></td>
		  </tr>
		  <tr id="r_dolg" <?if(!$is_dolg){ ?>style="display:none"<? } ?>>
<?				$m_ab = isset($GLOBALS['ab_numbs'])?($id_tarifab==6&&$c_ar['Comment']!=''?$c_ar['Comment']:round($c_ar['ab_sum']/2*(1+1/($GLOBALS['ab_numbs']+1/*>0?$GLOBALS['ab_numbs']:1*/)))):'';
			//		echo '<b>'.$row_rslt['name_ab'].' </b>', $m_ab, ' руб./мес.';	?>
			<td>Долг <? $d=date("Y-m-d", strtotime($Date_pay." +1 day")); echo round($m_dolg)>0?"с $d по $Date_start_st":""?></td>
			<td align="right">
            <input id="s_dolg" name="s_dolg" size="3" readonly="true" value="<? echo $s_dolg=$is_dolg?round($m_ab/30*$m_dolg,0):0;?>" align="right" /></td><td > руб.</td>
			<td colspan="3" ><div id="m_dolg">за <? if($is_dolg){ echo round($m_dolg)," дней";}?></div></td>
			<td><b><div id="c_dolg" <? //if(!($state==2 && $Date_end_st=='')) { echo ' style="display:none"'; } ?>>Подключить <? $c_dolg = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+($c_ar['auto']==1&&$state==2?0:1),date("Y"))) ?>
            	<input name="c_dolg" type="date" size="8" value="<? echo $c_dolg ?>" onchange="adj_pay()"/></div></td>
            <td><b><? if($m_dolg>0) {?>Оплата долга <? } echo ($can_auto?"авто":"+ <b>100</b>руб.")."переподкл"?> </td>
		  </tr>
		  <tr>
			<td width="79">Абон.платёж: </td>
			<td width="32" align="right"><input name="abon_p" size="3" onchange="f=document.forms.ulaForm;f.abon_per=''; <? if($n_cod) {?>adj_pay('p')<? } else {?>alert('не присвоен код адреса')<? }?>" align="right" /></td><td width="34" > руб.</td>
		  	<td width="16">за </td>
		  	<td width="24" colspan="2" >
            	<input name="opl_per" type="text" size="2" onchange="<? if($n_cod) { ?>adj_pay('per')<? } else {?>alert('не присвоен код адреса')<? }?>" align="right" /></td>
            <td width="88"><div id="days">мес</div></td>            
		  	<td><input name="abon_Com" type="text" size="30" value="Абон.плата"/></td>
		  </tr>
		  <tr>
			<td>Интернет: </td>
            <? $dsbl_inet = strtotime($Date_pay." +1 day")<time() && $m_ab>0 ?>
           	<td align="right"><input id="inet_pay" name="inet_pay" size="3" onchange="<? if($n_cod) {?>adj_pay()<? } else {?>alert('не присвоен код адреса')<? }?>" align="right" <? if($dsbl_inet) {?>style="display:none"<? }?>/></td>
   	        <td id="inet_rub" colspan="5"><? if($dsbl_inet) {?><b>ДОЛЖНИК !<? } else {?> руб.<? }?></td>
            <td id="inet_Com" <? if($dsbl_inet) {?>style="display:none"<? }?>><input name="inet_Com" type="text" size="30"/></td>
		  </tr>
		  <tr>
			<td class="стиль1">Итого: </td>
			<td align="right"><input name="total_pay" id="all_cost" size="3"  align="right" value="<? if($is_dolg) {echo ($can_auto?0:100)+$s_dolg;}//readonly="true"?>" onchange="<? if($n_cod) { ?>adj_pay('tot')<? } else {?>alert('не присвоен код адреса')<? }?>"/></td><td > руб.</td>
			<td colspan="5">
				<table border=0 width="100%">
				  <tr>
					<td colspan="3" align="right"><div class="quote" id="opl_to"></div></td>
					<td width="39%"><div class="quote" id="action"></div></td>
				  </tr>
			  	</table>
   			</td>
		  </tr>
		  <tr><td colspan="8"><div id="res_pay"></div></td>
		  </tr>
    	</table>
		
	</div>
	<div id="all" <? if($tp>2) echo' style="display:none"'?><? //if ($dolg) { echo ' style="display:none"'; }?>>
		<table width="800" border=0 cellspacing="3" bgcolor="#CCFF99">
<?php /*		  <tr id="a_dolg"<? if (!$dolg) { echo ' style="display:none"'; }?>>
			<td colspan="9" height="12" align="center" bgcolor="#FFFF99"><? if ($dolg) { ?><font size="+1" color="#669900"><b>Абонент отключен за долг!</b></font><? }?></td>
		  </tr>	*/ 
		  ?>
		  <tr>
			<td width="79" align="right">Абон.платёж: </td>
<?	$cust = $c_ar;
	$m_ab = isset($GLOBALS['tot_ab'])?($id_tarifab==6&&$cust['Comment']!=''?$cust['Comment']:round($cust['ab_sum']/2*(1+1/($GLOBALS['tot_ab']+1>0?$GLOBALS['tot_ab']:1)))):'';
?>			<td width="19"><input name="ab_" type="text" size="3" onchange="<? if($n_cod) {?>adj_pay()<? } else {?>alert('не присвоен код адреса')<? }?>" align="right" />
           	  <input name="m_ab" type="hidden" value="<? echo $m_ab?>"/>
            </td>
            <td width="24"> руб.</td>
			<td width="193" align="left" style="border:thin solid #6FF">
                <table id="m_op1" width="100%" border=0 style="border: thick solid #933">
                  <tr>
                    <td width="43">
					  <input type="radio" name="radio" id="sl" value="sl" 
                      	onclick="document.getElementById('m_op1').style='border: thick solid #933'; document.getElementById('m_op2').style='border:'; " checked/>за
                    </td>
                    <td width="19" >
		              <input name="opl_" type="text" size="3" align="right" onchange="
					  	<? if($n_cod) {?>
                            f=document.ulaForm; 
                            //f.ab_.value=f.opl_mon.value*f.h_ab_numbs.value*this.value; 
                            f.ab_.value=f.m_ab.value*f.h_tot_ab.value*this.value; 
                            adj_pay()
						<? } else {?>alert('не присвоен код адреса')<? }?>" />
            		</td>
                    <td width="78">
                        <div id="days_">мес</div>
                    </td>
            	  </tr>
            	</table>
            </td>
            <td width="29" align="right">
           	  <input name="t_pay" type="hidden"/><input name="i_pay" type="hidden"/>
            </td>
			<td width="166" align="left" style="border:thin solid #6FF">
                <table id="m_op2" width="100%" border=0>
                  <tr>
                    <td width="44">
                        <input type="radio" name="radio" id="sl2" value="sl" onclick="document.getElementById('m_op2').style='border: thick solid #933'; document.getElementById('m_op1').style='border:'; "/>
                        по
                    </td>
                    <td width="91">
                        <input name="opl_2" type="date" size="10"/>
                    </td>
            	  </tr>
            	</table>
			<td width="92"><input name="Comm_all" type="text" size="15" value="Абон. плата"/></td>
		  </tr>
   		</table>
		<table width="800" cellspacing="2" bgcolor="#CCFF99" >
		  <tr bgcolor="#CCCC99">
			<td align="center"><b>Дог. №</b></td>
			<td align="center" width="78"><b>оплачено по</b></td>
			<td align="center" width="170"><b>долг</b></td>
			<td align="center"><b>подключение</b></td>
			<td align="center" ><b>сумма</b></td>
			<td align="center" width="96"><b>платёж по</b></td>
			<td align="center"><b>акция</b></td>
			<td align="center"><b></b></td>
		  </tr>
<?		$i = 0;
	$cust = $c_ar;
	$m_ab = isset($GLOBALS['tot_ab'])?($id_tarifab==6&&$cust['Comment']!=''?$cust['Comment']:round($cust['ab_sum']/2*(1+1/($GLOBALS['tot_ab']+1>0?$GLOBALS['tot_ab']:1)))):'';
	$s_tot = 0;
		foreach ($a_cus as $cust) 
		  if($cust['inet']==0) {
			 if( 1 /*&& !$cust['dolg']*/){ 
	//		 echo ">",$cust['inet'],"<";
				$i++;	// id="ND_<? echo $i? >"	
			//	$cust = $cust;//$a_cus[$Bill_Dog];	
				$d_st = strtotime($Date_start_st = $cust['Date_start_st']);
				$d_py = strtotime($Date_pay = $cust['Date_pay']);
				$is_dolg=(($state=$cust['state'])==2 && ($Date_end_st=$cust['Date_end_st'])=='') /*&& ($d_st > (strtotime("+1 day",$d_py)))*/;// && $cust['auto']==0)||($cust['auto']==1 && $state==0); 
				$can_auto = $cust['auto']==1 && $state >0;
		//		$need2con = $state!=1 && !$can_auto;
			//	if(!$is_dolg){  }
				$m_dolg = /*($state==2 && $Date_end_st=='')*/$is_dolg?($d_st - (strtotime("+1 day",$d_py)))/60/60/24:0;
//	echo "st= $Date_start_st, dp=$Date_pay";
  ?>
			<tr bgcolor="#CCCC99"><td colspan="8"></td></tr>
            <tr>
				<td><? //echo $cust['auto'], ' ', strlen($cust['mac']), ' ', $state, '!';?><label>&nbsp;<input name="ND_<? echo $i?>" type="checkbox" value="<? echo $cust['Bill_Dog']?>"
							onchange="" checked="checked" disabled="disabled" /><? echo $cust['Bill_Dog']?></label></td>
				<td align="center" colspan="1"><? echo sh_date($cust['Date_pay'])?>
                	<input name="D_st_<? echo $i ?>" value="<? echo $cust['Date_start_st']?>" type="hidden"/></td>
  <? ///////////////////////////////////////////                  
//	echo			$m_ab = isset($GLOBALS['tot_ab'])?($id_tarifab==6&&$cust['Comment']!=''?$cust['Comment']:round($cust['ab_sum']/2*(1+1/($GLOBALS['tot_ab']+1/*>0?$GLOBALS['ab_numbs']:1*/)))):'';
			//		echo '<b>'.$row_rslt['name_ab'].' </b>', $m_ab, ' руб./мес.';	?>
			<td align="center"><div id="d_<? echo $i ?>"><? $d=date("Y-m-d", strtotime($Date_pay." +1 day")); echo round($m_dolg)>0?"".sh_date($d)." ÷ ".sh_date($Date_start_st):""?></div>
<!--            </td>
			<td align="right">	--> 
           <input id="sd_<? echo $i /*s_dolg*/?>" name="sd_<? echo $i /*s_dolg*/?>" type="hidden" size="3" readonly="true" value="<? echo $s_dolg=$is_dolg /*&& $m_dolg>0*/?round($m_ab/30*$m_dolg,0):0; $s_tot += $s_dolg;?>" align="right" /><!--</td><td >--><b><? echo $s_dolg?></b> руб.<!--</td>
			<td >
            <div id="m_dolg<? echo $i ?>">--><? if($is_dolg){ echo "за ",round($m_dolg)," дней";}?><!--</div>--></td>
            <td align="center"><? if($is_dolg>0) {echo ($can_auto?"авто":"+ <b>100</b>руб.")."подкл"; $s_tot+=$can_auto?0:100;}?> <!--</td>
			<td><b><div id="c_dolg<? echo $i ?>" <? //if(!($state==2 && $Date_end_st=='')) { echo ' style="display:none"'; } ?>>Подключить--> <? $c_dolg = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+($can_auto?0:1),date("Y"))) ?>
         <? if($is_dolg) {?>  	<input name="c_dolg<? echo $i ?>" type="date" size="8" value="<? echo $c_dolg ?>" onchange="adj_pay()"/>
		 <? 	if(strlen($cust['mac'])==0) {?><font style="background:#F90"><br><b>&nbsp;&nbsp;&nbsp;Отсутствует МАС!&nbsp;&nbsp;&nbsp;</b></font><? }
		 	}?><!--</div>--></td>

                    
  <? ///////////////////////////////////////////?>                  
                <td align="center"><div class="quote" id="ab_<? echo $i ?>">
                  <input name="ab_<? echo $i ?>" size="3" disabled="disabled"/>
              		</div></td>
                <td align="center"><div class="quote" id="opl_<? echo $i ?>"></div></td>
                <td align="center"><div class="quote" id="act_<? echo $i ?>"></div></td>
				<td colspan="1"><div id="D_end_<? echo $i ?>">
                	<input name="D_end_<? echo $i ?>" type="hidden"/></div></td>
			</tr>
<?			} /*elseif (gettype($cust)!='string') { ?>
			<tr>
				<td align="center"><? echo $cust['Bill_Dog']?></td>
				<td align="center"><? echo $cust['Date_pay']?></td>
                <td colspan="4"><? echo ($cust['dolg']?'<b>Нет заявки на откл. Сделайте переоформление!</b>':'')
				//*.($cust['state']==2?' Отключен':'Подключен').' с '.$cust['Date_start_st'].
				//	($cust['state']==2 && $cust['Date_end_st']==''?' за долг':' по '.$cust['Date_end_st']) ?> </td>
			</tr>
			
<?			} */?>
<?		}?>
	  <input name="ab_ps<? echo $i ?>" type="hidden"/>
      </table>
<!--            <tr>
				<td colspan="7">-->
					<table border=0 width="800" bgcolor="#CCFF99">
					  <tr bgcolor="#CCCC99">
						<td width="23"></td>
						<td align="left"><input name="s_tot" type="hidden" value="<? echo $s_tot; ?>" /><div class="quote" id="t_pay">Всего к оплате: <? echo $s_tot; ?>руб</div></td>
                        <td align="left"><div class="quote" id="res_pay_all"></div></td>
					  </tr>
			  		</table>
<!--				</td>
			</tr>
		  <tr>
            <td><div class="quote" id="action"></div></td>
		  </tr>
	  </table>-->
	</div>
    <div id="frz" <? echo 'style="display:none"'?>>
		<table width="800" border=0 cellspacing="3" bgcolor="#99FFFF">
		  <tr>
		  	<td height="40" colspan="2" align="center" valign="bottom"><b>Приостановка сети (отключить)</b></td>
		  </tr>
		  <tr>
			<td height="40" width="555" align="center">
				с <input name="Date_start_fr" type="date" size="9" onchange="frz_chk()"/> 
				по <input name="Date_end_fr" type="date" size="9"  onchange="frz_chk()"/>
				примечание&nbsp;<input name="Comment" type="text" size="30" />			</td>
		  </tr>
          <tr>
          	<td><div id="res_frz"><?php //echo date('Y-m-d', strtotime($Date_pay))?><b>Начало заморозки не должно быть позже <? echo $Date_pay?></b></div></td>
          </tr>
	  </table>
	</div>
    <!-------------------------------------------------------------------------------------->
    <div id="hist_pay" style="background-color:#E5E5E5<? echo $dolg && $tp<3?'':'; display:none' ?>"></div><? /*if($tp>2) echo' style="display:none"'*/?>
    <!----------------------------------------------------------------->
    <div id="hist_cod" <? echo 'style="display:none"'?>></div>
    <!----------------------------------------------------------------->
    <div id="hist_not" <? echo 'style="display:none"'?>></div>
</div>
</div>
<?
//}
//============================================================================
function inp_echo($desc, $name, $value, $type, $size, $onChange) {
    if ($GLOBALS['tp']<3)
    	echo " $desc <input name='$name' type='$type' id='$name' value='$value' size='$size' onChange='$onChange' />";
    else 
    	echo " $desc <input name='$name' id='$name' value='$value' type='$type' size='$size' />";// disabled='disabled'
//		echo strlen($value)>0?($type=="text"?(strlen($desc)>0?"$desc:":"")." <b>$value</b> ":($value>0?$desc:"")):"";
}
//============================================================================
function form_phn($phone_Home, $phone_Cell, $phone_Work, $Jur) { 	//require($frm_phn); //============================================================================
/*if ($GLOBALS['totalRows_customer']>0) {
	$phone_Home = $a_cus[$Bill_Dog1]['phone_Home'];
	$phone_Cell = $a_cus[$Bill_Dog1]['phone_Cell'];
	$phone_Work = $a_cus[$Bill_Dog1]['phone_Work'];
	$Jur = $a_cus[$Bill_Dog1]['Jur'];
} else {
	$phone_Home = "";
	$phone_Cell = "";
	$phone_Work = "";
	$Jur = 0;
} */
?><div id="phn">
<table width="800" border=0 style="background-color:#9FF">
  <tr>
    <td width="65"  align="left" valign="middle"><strong>телефоны:</strong></td>
	<td width="725" <? if ($GLOBALS['tp']<3) {?>align="center"<? }?>> 	<?
    	inp_echo("домашний", "phone_Home", $phone_Home, "text", 7, "adjustPhn();");
    	inp_echo("сотовый", "phone_Cell", $phone_Cell, "text", 16, "adjustPhn();");
    	inp_echo("рабочий", "phone_Work", $phone_Work, "text", 7, "adjustPhn();");
    	inp_echo("юридическое лицо", "Jur", $Jur, "checkbox", 0, "adjustPhn();"); ?>
<!--    	домашний <input name="phone_Home" type="text" id="phone_Home" value="<? echo $phone_Home; ?>" size="7" onChange="adjustPhn();" />
        , сотовый <input name="phone_Cell" type="text" id="phone_Cell" value="<? echo $phone_Cell; ?>" size="16" onChange="adjustPhn();" />
        , рабочий <input name="phone_Work" type="text" id="phone_Work" value="<? echo $phone_Work; ?>" size="7" onChange="adjustPhn();" />
        , юридическое лицо <input name="Jur" type="checkbox" value="<? echo $Jur; ?>" onChange="adjustPhn();"/>-->
     </td>
  </tr>
</table>
</div>
<?	}
//============================================================================
function form_fio($Fam, $Name, $Father, $Birthday, $pasp_Ser, $pasp_Num, $pasp_Date, $pasp_Uvd, $pasp_Adr, $Comment) { //require($frm_fio); //============================================================================
/* if ($totalRows_customer>0) {
	$Fam = $a_cus[$Bill_Dog1]['Fam'];
	$Name = $a_cus[$Bill_Dog1]['Name'];
	$Father = $a_cus[$Bill_Dog1]['Father'];
	$Birthday = $a_cus[$Bill_Dog1]['Birthday'];
	$pasp_Ser = $a_cus[$Bill_Dog1]['pasp_Ser'];
	$pasp_Num = $a_cus[$Bill_Dog1]['pasp_Num'];
	$pasp_Date = $a_cus[$Bill_Dog1]['pasp_Date'];
	$pasp_Uvd = $a_cus[$Bill_Dog1]['pasp_Uvd'];
	$pasp_Adr = $a_cus[$Bill_Dog1]['pasp_Adr'];
	$Comment = $a_cus[$Bill_Dog1]['Comment'];
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
	<div id="fio">
    <table width="800" border=0>
  <tr>
    <td width="76" align="left"><strong>Ф.И.О.:</strong></td>
	<td width="714"  <? if ($GLOBALS['tp']<3) {?>align="right"<? }?>> <?
    	inp_echo("", "Fam", $Fam, "text", 25, "adjustPasp();");
    	inp_echo("", "Name", $Name, "text", 20, "adjustPasp();");
    	inp_echo("", "Father", $Father, "text", 20, "adjustPasp();");
    	/*if ($Birthday!="0000-00-00")*/ inp_echo("Дата рождения", "Birthday", $Birthday, "text", 9, "adjustPasp();");
?><!--		<input name="Fam" type="text" id="Fam" onChange="adjastPasp();" value="<? echo $Fam; ?>" size="25" />
		<input name="Name" type="text" id="Name" onChange="adjastPasp();" value="<? echo $Name; ?>" size="20" />
		<input name="Father" type="text" id="Father" onChange="adjastPasp();" value="<? echo $Father; ?>" size="20" />
		Дата рождения: <input name="Birthday" type="text" id="Birthday" onChange="adjastPasp();" value="<? echo $Birthday; ?>" size="9" />-->	</td>
  </tr>
  <tr <? if($GLOBALS['tp']>2) echo 'style="display:none"'?>>
        <td align="left"><strong>Паспорт:</strong></td>
        <td  align="right">
<?	    	inp_echo("серия", "pasp_Ser", $pasp_Ser, "text", 3, "adjustPasp();");
	    	inp_echo("номер", "pasp_Num", $pasp_Num, "text", 5, "adjustPasp();");
	    	inp_echo("выдан", "pasp_Date", $pasp_Date, "text", 9, "adjustPasp();");		?>
<!--            серия <input name="pasp_Ser" type="text" id="pasp_Ser" onChange="adjastPasp();" value="<? echo $pasp_Ser; ?>" size="3" />
            номер <input name="pasp_Num" type="text" id="pasp_Num" onChange="adjastPasp();" size="5" value="<? echo $pasp_Num; ?>" />
            выдан <input name="pasp_Date" type="text" id="pasp_Date" onChange="adjastPasp();" value="<? echo $pasp_Date; // type="date"?>" size="9" />-->
            кем 
            <input name="pasp_Uvd" id="pasp_Uvd" type="text" onchange="adjastPasp();" value="<? echo $pasp_Uvd; ?>" size="38" />    
            <select name='_pasp_Uvd' id='_pasp_Uvd' onchange='document.forms["ulaForm"].pasp_Uvd.value=this.value;' class='headText' >
                  <option value="0" >-</option>
                  <option value="отделением №1 (с местом дислокации в р-не Талнах г.Норильска) отдела УФМС России Красноярского края" >отд.1 УФМС</option>
                  <option value="Талнахским ГОВД Норильского УВД Красноярского края" >Т-ким ГОВД</option>
                  <option value="УФМС Россиии г.Норильск по р-ну Талнах" >р-н Талнах</option>
                  <option value="ОВД г.Талнаха" >ОВД Талнах</option>
                  <option value="О-нием в р-не Талнах отдела УФМС России по Красноярскому краю" >отд. Талнах</option>
                  <option value="УФМС России по г.Норильску" >Норильск</option>
                  <option value="УФМС России по г." >УФМС</option>
          </select>    </td>
      </tr>
      <tr <? if($GLOBALS['tp']>2) echo 'style="display:none"'?>>
        <td align="right"></td>
        <td  align="right">
            зарегистрирован по адресу:
            <input name="B_get_adress" type="button" onclick="document.forms.ulaForm.pasp_Adr.value=get_adress();" value="тот же" /><!--initialiseInputs();start_date();-->
          <input name="pasp_Adr" type="text" id="pasp_Adr" onChange="adjastPasp();" value="<? echo $pasp_Adr; ?>" size="65" />    </td>
      </tr>
  <? // } ?>
  <tr>
	<td align="left"><strong>Примечание:</strong></td>
	<td  align="right">
    <input name="Comment" type="text" id="Comment" value="<? echo $Comment; ?>" size="97" onchange='adj_Cust();' />    </td>
  </tr>
</table>
</div>
<?	}
//=================================================================================================
function form_net($conn, $Bill_Dog, $id_tarifab, $tarifab_date, $Nic, $Date_start_st, $Date_end_st, $tp, $state, $Date_pay, $a_cus) { //require($frm_net); //============================================================================
//echo "$conn, $Bill_Dog, $id_tarifab, $tarifab_date, $Nic";
	$conn = ($conn>0)?$conn:0;
	$id_tarifab = ($id_tarifab>0)?$id_tarifab:0;
	$tarifab_date = ($tarifab_date>0)?$tarifab_date:date("Y-m-d");
	$ar_s = array(''=>'не устан.', 0=>'не устан.', 1=>'подключен', 2=>'замороз.', 3=>'расторг');
	$m_pay = $GLOBALS['menu']=='pay';
//	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
	$m = Array(1=>"янв",2=>"фев",3=>"мар",4=>"апр",5=>"мая",6=>"июн",7=>"июл",8=>"авг",9=>"сен",10=>"окт",11=>"ноя",12=>"дек");
?>
<div id="net" style="background-color:#CCFFFF;">
    <div id="nNic" <? if (!($m_pay && $a_cus[$Bill_Dog]['inet']==1)) { echo 'style="display:none"'; }?>>
        <table bgcolor="#FF9900" width="800"><tr align="center" height="40">
            <td>Установить <input name="B_err_nic" type="button" onclick='ch_param("err_nic","","nNic");' value="соответствие сетевому нику из Ошибок базы" /></td>
            <td>или как доп.инет.учётка к абон.договору №&nbsp;
                <input name="NewBill" id="NewBill" value="<? echo $Bill_Dog; ?>" type="text" size="4" 
                    onchange='ch_param("Bill2acc_chk","nb1="+this.value+"&nb2="+document.forms.ulaForm.Bill_Dog.value,"dNewBill");'/></td>
            <td align="left"><div id="dNewBill"></div></td>
        </tr></table>
    </div>
<table id="net_tab" width="800" border=0 <?php echo (($m_pay && $a_cus[$Bill_Dog]['inet']==1)?'style="display:none"':'');?>>
  <tr>
    <td width="20" align="left" <?php //echo ($m_pay?'width="40"':'colspan="3" width="450"');?>><strong>Сеть: </strong></td>
	<td <?php if (!$m_pay) {?> width="550"<? }?>align="left">
    	<? //print_r($a_cus[$Bill_Dog]); ?>
	<?php if (!$m_pay) {?>&nbsp;Договор<? }?>
      	<input name="Bill_Dog" id="Bill_Dog" value="<? echo $Bill_Dog?>" type="text" <?php echo $m_pay?'style="display:none"':''?> size="4" <? echo $GLOBALS['tp']<3?"onchange='adj_Bill_Dog(this)'":""?> <? echo $Bill_Dog=="новый"?'disabled="disabled"':''?>/>
		<?php if (!$m_pay) {?>MAC<? 
		$mac = isset($a_cus[$Bill_Dog]['mac'])?$a_cus[$Bill_Dog]['mac']:"";//isset($a_cus[$Bill_Dog])?$a_cus[$Bill_Dog]['mac']:"";
		$mac = $mac==""?"":substr($mac, 0, 2)."-".substr($mac, 2, 2)."-".substr($mac, 4, 2)."-".
			   substr($mac, 6, 2)."-".substr($mac, 8, 2)."-".substr($mac, 10, 2);
		}?>
        <input name="mac" id="mac<? //echo $Bill_Dog?>" value="<? echo $mac; ?>" type="text" <? if($m_pay){?>style="display:none"<? }?> size="15"  onchange='<? if($GLOBALS['tp']>2){?><? echo "f=document.forms.ulaForm;cor_mac(f.tabl_cust.options[f.tabl_cust.selectedIndex].value)"; } else {?>adj_mac()<? }?>;' maxlength="17" onkeyup="v_MAC(this.value,'<? echo $Bill_Dog?>');" />
		<?php if (!$m_pay) {?>&nbsp;ник<? }?>
		<input name="Nic" type="text" <?php echo ($m_pay?'style="display:none"':'');?> id="Nic" <? if($GLOBALS['tp']<3) {?>onChange="adj_Nic(this);"<? }?> value="<? echo $Nic; ?>" size="9" />
      	&nbsp;<?php echo ($m_pay?'':'&nbsp;подключ.'); // ?>
	<select name='conn' id='conn' <? if($GLOBALS['tp']<3) {?>onchange='adj_Conn(this.value);'<? }?> class='headText' <?php echo $m_pay?'style="display:none"':'';?> >
<? 	if ($conn>0) {
		$v_tar = mysql_query("SELECT * FROM `v_tarifab` where id_tar_con=$conn") or die(mysql_error());
		$r_v_tar = mysql_fetch_assoc($v_tar);
		$c_typ = $r_v_tar["con_typ"];
	} else {
		$c_typ = 0;
	}
	 	$rslt = mysql_query("SELECT * FROM `spr_con_typ`") or die(mysql_error());
		$row_rslt = mysql_fetch_assoc($rslt);
		do {
			$op = $row_rslt['con_typ'];
			$able = ($op==5 ? !$GLOBALS['new_Cod'] :1)?"":"disabled='disabled' ";
			echo "<option value=".$op." ".$able./*($op==$c_typ/ *$conn* / ? "selected":"").*/">".$row_rslt['typ_name']."</option>";
		} while ($row_rslt = mysql_fetch_assoc($rslt));
	?>
	</select>
	</td>
	<td<?php if(!$m_pay) {?> width="100"<? }?>>
	  <div id="con_tar" <?php echo ($m_pay?'align="left"':'');?>>
	  <?php //if(!$m_pay) { echo "тариф"; }	  	
			$r_con = mysql_query("SELECT `con_typ` FROM `spr_tar_con` WHERE `id_tar_con`=".$conn) or die(mysql_error());//`id_tar_con`
			$r_c = mysql_fetch_assoc($r_con);
			$c_typ = $r_c["con_typ"];
//			$s_qer = "SELECT * FROM `spr_tar_con` WHERE `con_typ`=".$conn.(($conn==4)?" or (`con_typ`=1 and `con_sum`<500)":"")." order by `id_tar_con`";
/*echo $conn," ",*/	$s_qer = "SELECT * FROM `v_tarifab` WHERE `con_typ`=".$c_typ.(($c_typ==4)?" or (`id_tar_con`=1 and `con_sum`<500)":"")." order by `id_tar_con`";
			$rslt = mysql_query($s_qer) or die(mysql_error());//`id_tar_con`
			$row_rslt = mysql_fetch_assoc($rslt);
			$rows = mysql_num_rows($rslt);
			$nm_tarif = "";	?> 
		<select name='id_tar_con' class='font8pt' id='id_tar_con' <? echo ($GLOBALS['tp']<3?"onchange='adj_con_tar(this)'":"").$m_pay?"style='display:none'":"" ?> >	
<?			echo "<option value=0 ".(($row_rslt['id_tar_con']==0)?"selected":"").">выбрать</option>";	//$i++
			do { 
			//		".strval($row_rslt['perstypes'])." ".strval($GLOBALS['TypePers'])."-  ($row_rslt['perstypes'] >= $GLOBALS['TypePers'])strval()
				echo "<option value=".$row_rslt['id_tar_con']." ".(($row_rslt['id_tar_con']==$conn/*$id_tarifab*/)?"selected":"").
					($tp>$row_rslt['perstypes']?" disabled='disabled' ":"")." >".$row_rslt['name_cn']."</option>";
				if ($row_rslt['id_tar_con']==$conn/*id_tarifab*/) { $nm_tarif = $row_rslt['name_cn']; }
				$tarifs[] = $row_rslt;
			 } while ($row_rslt = mysql_fetch_assoc($rslt));
			$rows = mysql_num_rows($rslt);	?>
		</select>
		<?php echo ($m_pay?'<b>&nbsp;'.$nm_tarif.'&nbsp;</b>':''); ?>
		<input name="h_ts" type="hidden" value="<? echo $rows ?>" />
<?		foreach ($tarifs as $t_row) {		//	print_r($t_row);
			echo '<input id="h_op_'.$t_row['id_tar_con'].'" type="hidden" value="'.$t_row['opl_period'].'" size=3/>';//
			echo '<input id="h_cn_'.$t_row['id_tar_con'].'" type="hidden" value="'.$t_row['con_sum'].'" />';//
			echo '<input id="h_ab_'.$t_row['id_tar_con'].'" type="hidden" value="'.$t_row['ab_sum'].'" />';//
			echo '<input id="h_id_'.$t_row['id_tar_con'].'" type="hidden" value="'.$t_row['id_tarifab'].'" />';//
			echo '<input id="h_kt_'.$t_row['id_tar_con'].'" type="hidden" value="'.$t_row['k_tar'].'" />';//
		}	?>
		</div>
	</td>
    <td align="left" <?php echo ($m_pay?'style="display:none"':'');//<div id="conn_pay" ></div>?>>
    	<input name="conn_pay" size="3" <? if($GLOBALS['tp']<3) {?> type="hidden" onchange="adj_CPay()" <? } else echo ' type="hidden"'?> /> <? if($GLOBALS['tp']<3) echo"руб."?>
		<input name="tarifab_date" type="hidden" value="<? echo $tarifab_date; ?>" /><? // echo $tarifab_date; ?>
		<?php
			$s_qer = "SELECT * FROM `spr_tarifab` where id_tarifab=$id_tarifab order by `id_tarifab`";
			$rslt = mysql_query($s_qer) or die(mysql_error());
			$row_rslt = mysql_fetch_assoc($rslt);
			$rows = mysql_num_rows($rslt);
			$nm_tarif = "";
			echo '<input type="hidden" id="id_tarifab" value="'.$id_tarifab.'" />';//
		?>
    </td>
    <td><div id="opl_p" style="display:none"></div></td>
<? if(!$m_pay) {?>
  </tr>
</table>
<table width="800" border=0>
  <tr><td width="10"></td>
<? }?>
	<td <? if(!$m_pay){?><? }?>width="220"><div id="con_s">Абон.тариф<b>
		<? if ($id_tarifab>0) {
				if ($id_tarifab==3) {?>
					<font size="+1" style="background-color:#FF0000" color="#FFFF00">
                    	&nbsp;<b><? echo $row_rslt['name_ab']?>&nbsp</font></b>
                <? } else {
					$c_ar = $a_cus[$Bill_Dog];
					$m_ab = isset($GLOBALS['ab_numbs'])?($id_tarifab==6&&$c_ar['Comment']!=''?$c_ar['Comment']:round($c_ar['ab_sum']/2*(1+1/($GLOBALS['ab_numbs']>0?$GLOBALS['ab_numbs']:1)))):'';
					echo '<b>'.$row_rslt['name_ab'].' </b>', $m_ab, ' руб./мес.';
                 }
                 
			}		?>
<?php /*	?>		<select name="id_tarifab" class='font8pt' id="id_tarifab" onchange='adj_con_tar();' <?php echo ($m_pay?'style="display:none"':'');?>>
			echo "<option value=0>выбрать</option>";	//$i++
			do { 
			//		".strval($row_rslt['perstypes'])." ".strval($GLOBALS['TypePers'])."-  ($row_rslt['perstypes'] >= $GLOBALS['TypePers'])strval()
				echo $tp,$row_rslt['perstypes'],"<option value=".$row_rslt['id_tarifab']." ".(($row_rslt['id_tarifab']==$id_tarifab)?"selected":"").($tp>$row_rslt['perstypes']?" disabled='disabled' ":"")." >".$row_rslt['name_ab']."</option>";
				if ($row_rslt['id_tarifab']==$id_tarifab) { $nm_tarif = $row_rslt['name_ab']; }
				$ab_tarifs[] = $row_rslt;
			 } while ($row_rslt = mysql_fetch_assoc($rslt));
			$rows = mysql_num_rows($rslt);	?>
		</select>	<?php echo ($m_pay?'<b>&nbsp;'.$nm_tarif.'&nbsp;</b>':'');?><? 
		echo '<input name="h_ts" type="hidden" value="'.$rows.'" />';
		foreach ($ab_tarifs as $t_row) {
			echo '<input id="h_opl_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['opl_period'].'" />';//
			echo '<input id="h_con_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['con_sum'].'" />';//
			echo '<input id="h_ab_'.$t_row['id_tarifab'].'" type="hidden" value="'.$t_row['ab_sum'].'" />';//
		}	<?php */?>
</div></td> <!-- Сюда записать параметры подключения -->
	<td <? if(!$m_pay){?>width="0"<? }?> align="left"><div id="abon_pay">
	<?	echo '<input name="abon_pay" size="4" type="hidden" />'; // type="text" <!-- onchange="adj_CPay()"руб.-->
//!		if (isset($a_cus[$Bill_Dog]['inet']) && !$a_cus[$Bill_Dog]['inet']) {
//			$m_ab = isset($GLOBALS['ab_numbs'])?(round(100*(1+1/($GLOBALS['ab_numbs']>0?$GLOBALS['ab_numbs']:1)))):'';
//	$a_cus[$Bill_Dog]['ab_sum']
			$m_ab = isset($GLOBALS['ab_numbs'])?($id_tarifab==6&&$a_cus[$Bill_Dog]['Comment']!=''?$a_cus[$Bill_Dog]['Comment']:round($a_cus[$Bill_Dog]['ab_sum']/2*(1+1/($GLOBALS['ab_numbs']>0?$GLOBALS['ab_numbs']:1)))):'';	//totalRows_customer
//			$m_ab = isset($GLOBALS['ab_numbs'])?($id_tarifab==6&&$a_cus[$Bill_Dog]['Comment']!=''?$a_cus[$Bill_Dog]['Comment']:round($a_cus['ab_sum']/2*(1+1/($GLOBALS['ab_numbs']>0?$GLOBALS['ab_numbs']:1)))):'';	//totalRows_customer
			echo /*$m_ab, ' руб./мес.',*/'<input name="opl_mon" value="'.$m_ab.'" size=4 type="hidden" />'; //," руб./мес." type="hidden"
//!		}
	 ?> 
	</div></td>
	<? $ar_c = array(''=>'333333', '0'=>'333333', '1'=>'33CC66', '2'=>'0000FF', '3'=>'00FFFF'); ?>
	<? if($m_pay){?>
		<td align="center"><div id="state" style="border:solid #<? echo $ar_c[$state] ?>">&nbsp;<strong><? echo $ar_s[$state]; ?></strong>&nbsp;
		c <? echo $Date_start_st; ?>&nbsp;<input name="Date_start_st" value="<? echo $Date_start_st; ?>" type="hidden"/>
		<? if ($state==2 && $Date_end_st=='') { //?> за долг<? } else { ?>
			по <? echo $Date_end_st; } ?>&nbsp;<input name="Date_end_st" value="<? echo $Date_end_st; ?>" type="hidden"/></div></td>
	<? } else {//?>
		<td width="300" align="right"><div id="state" style="border:solid #<? echo $ar_c[$state] ?>">&nbsp;<strong><? echo $ar_s[$state]; ?></strong>&nbsp;
        	c <input name="Date_start_st" value="<? echo $Date_start_st; ?>" size="8" type="date"/>
            по <input name="Date_end_st" value="<? echo $Date_end_st; ?>" size="8" onchange="document.forms.ulaForm.Date_pay.value=this.value" type="date"/></div></td>
	<? }?>
	<td align="left"><div id="Date_pay"> оплачено по <input name="Date_pay" value="<? echo $Date_pay; ?>" size="8" <? if($m_pay){?>type="hidden"<? }?>/>
		<? if($m_pay){ echo '<b>'.(empty($Date_pay)?'___':date("j ", strtotime($Date_pay)).$m[date("n", strtotime($Date_pay))].' '.date("Y", strtotime($Date_pay)).'г.').'</b>';
		//$Date_pay; 
		}
		if ($GLOBALS['totalRows_customer']>0 && $a_cus[$Bill_Dog]['dolg'] && $Date_end_st!='' && $m_ab>0 && $a_cus[$Bill_Dog]['inet']==0) { ?><font size="3" style="background-color:#FF0000" color="#FFFF00"><br><b>Нет заявки на откл!</b></font>
			<!--<input name="B_add_off" type="button" onclick='ch_param("err_nic","","nNic");' value="+" />-->
            <? //print_r($a_cus);
			put_noti2off ($Date_pay, $Bill_Dog, $a_cus[$Bill_Dog]['Cod_flat'], $a_cus[$Bill_Dog]['id_Podjezd'], $a_cus[$Bill_Dog]['flat'], $GLOBALS['tn']/*TabNum*/);
		}?></div></td>
	<td><div id="rad"><? /**/echo isset($a_cus[$Bill_Dog]['rad']) && $a_cus[$Bill_Dog]['rad']?"<b>R√":"R-";/**/?></div></td>
    <td align="right"><div id="frnd">
<? if(!$m_pay){ echo $is_frend=isset($a_cus[$Bill_Dog]['Bill_frend'])/*&&($frend=$a_cus[$Bill_Dog]['Bill_frend'])!=""*/?"друг: ":""; ?>
    	<input name="Bill_frend" size="4" value="<? echo $is_frend?$frend:"" ?>" <? echo $GLOBALS['tp']<3?'onchange="adjastNet()"':''?>/>
<? }?>
	</div></td>
  </tr>
</table>
</div>  

<!--    <input name="dt3w2day2" type="button" id="dt3w2day2" onclick="javascript:document.forms['ulaForm'].tarifab_date.value=TODAY2" value="сегодня" /></td>-->
<?	}
//============================================================================
function form_w3($From_Net, $Login, $id_tarif3w, $tarif3w_date, $a_cus1) { //require($frm_w3); //============================================================================
	$m_pay = $GLOBALS['menu']=='pay';
/*if ($totalRows_customer>0) {
	$From_Net = $a_cus[$Bill_Dog1]['From_Net'];
	$Login = $Logins[$Bill_Dog1][1]['Login'];
	$id_tarif3w = $Logins[$Bill_Dog1][1]['id_tarif3w'];
	$tarif3w_date = $Logins[$Bill_Dog1][1]['tarif3w_date'];
} else {
	$From_Net = "";
	$Login = "";
	$id_tarif3w = 0;
	$tarif3w_date = "";
}
'a_cus'][$GLOBALS['Bill_Dog1']][
*/?>
<div id="w3" border=1 style="background-color:#CCFFCC">
 <table id="inet_tab" width="800" border="0" <?php echo (($m_pay && $GLOBALS['inet1']==1)?'style="display:none"':'');?>>
  <tr>
    <td <? if($m_pay){?>width="95"<? }?>align="<?php if ($m_pay) {?>right<? }else{?>left<? }?>"><strong>Интернет&nbsp;
    	<button name="refr_3w" type=button onClick="f = document.forms.ulaForm; Bill_Dog = f_Bill_Dog();
		ch_param('refr_3w','tn='+f.TabNum+'&account='+val_nm(Bill_Dog, 'account'+(1*f.Login.selectedIndex+1)), 'inet_inf');"><img src="reload.png" align=middle alt="Обнови"></button>:</strong> <?php if (!$m_pay) {?>из сети <? }?>
   	  <input name="From_Net" id="From_Net" type="text" <?php echo $m_pay?'style="display:none"':'' ?> <? echo $GLOBALS['tp']<3?'onChange="adjastNet()"':''?> value="<? echo $From_Net?>" size="7" />
    </td>
<?php /*?>	<td width="35" align="right" <?php echo (($GLOBALS['menu']=='pay')?'':'width="40"');?>>логин </td><?php */?>
	<td><div id="Login" align="<?php if ($m_pay) {?>left<? }else{?>rihgt<? }?>">
<?php 	if (($GLOBALS['totalRows_customer']>0) && ($GLOBALS['Logins'][$GLOBALS['Bill_Dog1']]["Logins"]>0)) {
			$inp_sz = $GLOBALS['Logins'][$GLOBALS['Bill_Dog1']]["Logins"];
			echo '<select name="Login" id="Login" size="'.$inp_sz.'" onchange="adjustLogin()" class="navText" >';
			for($i=1; $i<=$inp_sz; $i++){
				$Log = $GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][$i];
				echo '<option value='.$Log['Login'].(($i ==1)?" selected":"").' >№'.$Log['account'].', '.$Log['Login'].', ',1*$Log['saldo'],' руб.'.'</option>';
			}
			echo '</select>';//</td>
	/*?>//		echo '<table><tr>';
			$sc1 = '';//логин 
			$sc2 = '';
			/*echo* / $ss = '< td rowspan="'.$inp_sz.'">< select name="Login" id="Login" class="navText" size="'.$inp_sz.'" onchange="adjustLogin()" >';
			for($i=1; $i<=$inp_sz; $i++){
				$Log = $GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][$i];
				$sc1 .= ($i>1?'< tr>':'').'< td>'.$Log['account'].'< /td>'.($i>1?'< /tr>':'');
				/*echo* / $ss .= '< option value='.$Log['Login'].(($i ==1)?" selected":"").' >'.$Log['Login'].'</option>';
				$sc2 .= ($i>1?'< tr>':'').'< td>'.$Log['saldo'].' руб.'.'< /td>'.($i>1?'</tr>':'');
			}
			/*echo* / $ss .= '< /select></td>';
/*			$Log1 = $GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][1];
			echo '<td>',1*$Log1['saldo'],' руб.'.$Log1['account'].'</td>';
			echo '<td rowspan="'.$inp_sz.'" valign="bottom">'.
				'<input name="addLogin" type="button" id="addLogin" onclick="faddLogin();" value="+"'.
						(($GLOBALS['menu']=='pay')?'style="display:none"':'').'/>'.
				'</td></tr>';
			for($i=1; $i<=$inp_sz; $i++){
				$Log = $GLOBALS['Logins'][$GLOBALS['Bill_Dog1']][$i];
				echo ($i>1?'<tr>':'').'<td>',1*$Log['saldo'],' руб.'.$Log['account'].'</td>'.($i>1?'</tr>':'');
			}	* /
			echo $sc1, $ss, $sc2;
			echo '< /tr>< /table>';<?php */
			echo //'<td valign="bottom">'.
				'<input name="addLogin" type="button" id="addLogin" onclick="faddLogin();" value="+"'.
						(($GLOBALS['menu']=='pay')?'style="display:none"':'').'/>'.
				'</td>';//</tr>
		} elseif ($m_pay) {
			//echo '<b>логин отсутствует!</b>';	?>
<!--			<input type="button" onclick="faddLogin();" value="+" />-->
<?		} else {
			echo '<input name="nic2login" type="button" id="nic2login" onclick= "f=document.forms.ulaForm;f.Login.value=f.Nic.value;adjastNet();" value="как ник" />'.
				 '<input name="Login" type="text" value="'.$Login.'" onChange="adjastNet();" size="12" />';
			}
//			echo $str_L;
	?>
    </div></td>
	<td align="left" <?php echo $m_pay?'style="display:none"':'' ?>><div id="addLogin"></div></td>
	<td align="center"><?php if ($GLOBALS['menu']!='pay') {?>тариф<? }?>
    	<select name="id_tarif3w" class='headText' id="id_tarif3w" <? echo $GLOBALS['tp']<3?"onchange='adjustTarif3w()'":""?> <?php echo $m_pay?'style="display:none"':'' ?>>
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
	<td align="left" <?php echo (($GLOBALS['menu']=='pay')?'style="display:none"':'');?>><div id="d_t3w_date">установлен с 
    <input name="tarif3w_date" id="tarif3w_date" value="<? echo $tarif3w_date; ?>" type="date" <? echo $GLOBALS['tp']<3?'onChange="adjastNet()"':''?> size="10" /><!-- value=< ?php $DateNotify=date("Y-m-d"); echo $DateNotify ? >-->
<!--    <input name="dt3w2day" type="button" id="dt3w2day" onclick="javascript:document.forms['ulaForm'].tarif3w_date.value=TODAY2" value="сегодня" />--></div></td>
	<script language="JavaScript" type="text/javascript">
		document.write('OOOOOO<a title="Календарь" href="javascript:openCalendar(\'\', \'ulaForm\', \'tarif3w_date\', \'date\')"><img class="calendar" src="b_calendar.png" alt="Календарь"/></a>');
	</script>
    
    <td <?php echo (($GLOBALS['menu']=='pay')?'width="300"':'width="0"');?>></td>
  </tr>
  <tr>
  	<td colspan="4"><div id="inet_inf"></div></td>
  </tr>
 </table>
 <div id="new_adr"></div>
</div>
<? }
//}
/*"&*/
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
function sh_date($Dt) {
	return strftime("%d-%m-%Y",strtotime($Dt));
	//date("j ", strtotime($Dt)).$m[date("n", strtotime($Dt))].' '.date("Y", strtotime($Dat)).'г.';
}
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
function print_hid($id, $key, $val)
{
	$inp_name = 'h_'.$id.'_'.$key; // $inp_name.'='.
//	echo "<FONT size=-1>".$inp_name."=".$val." </FONT>";	//</br>
  	echo '<input name="'.$inp_name.'" id="'.$inp_name.'" value="'.$val.'" type="hidden"/>';//
}
// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
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
        foreach ($line as $col_value) { print "\t\t<td>$col_value</td>\n";  }
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