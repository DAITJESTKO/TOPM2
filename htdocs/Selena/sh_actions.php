<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
if (isset($GLOBALS['pers'])) { /*	echo $GLOBALS['pers']['TabNum']; */  }
$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;
$tp = $_REQUEST ["tp"];
$tn = $_REQUEST ["tn"];
$of = isset($_REQUEST ["of"])?$_REQUEST ["of"]:1;
$per = $_REQUEST ["per"];
$s_frm = "'%Y-%m".($per==0?"-%d":"")."'";
$pers = $tp==1 || $tn==6 /*|| $tn==8*/?"":" and TabNum=$tn";
$tn2 = isset($_REQUEST ["tn2"])?$_REQUEST ["tn2"]:($tp==1 || $tn==6?'':$tn);
$pers .= $of?" and TabNum<>11":"";
if (isset($_REQUEST ["b"])) {
	if($_REQUEST ["b"]=='prv') {
		$r_q = "SELECT DATE_FORMAT(max(InputDate),$s_frm) as d_inp from v_actions where InputDate<'".$_REQUEST ["di"]."'".$pers;
	} else {
		$r_q = "SELECT DATE_FORMAT(min(InputDate),$s_frm) as d_inp from v_actions where InputDate>'".$_REQUEST ["di"]." 23:59:59'".$pers;
	}
} else {
	$r_q = "SELECT DATE_FORMAT(max(InputDate),$s_frm) as d_inp from v_actions where 1 $pers order by `InputDate`";
}
$res = mysql_query($r_q);
$row = mysql_fetch_assoc($res);
$d_inp = $row['d_inp'];
$di =  isset($_REQUEST ["di"]) && (!isset($_REQUEST ["b"])) ? $_REQUEST ["di"]:$d_inp;//date("Y-m".($per==0?"-d":""));
$db = "act".date("Y",strtotime($di));

$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$nbo = 1;
$i = 0;
$j = 0;
$Sabon = 0;
$Sinet = 0;
$Ssumm = 0;
$bgc = $cfg['BgcolorOne'];
$s_conn = array(0 => "не устан.", 1 => "новое", 2 => "доп.подкл.", 3 => "смена влад.", 4 => "переподкл.", 5 => "смена адреса", 6 => "переоформление" );
$s_th = 'background="gray2_bg.gif" bgcolor="#DFDFDF"';
	
$InputDate = $di;//date("2010-05-26");//, mktime(0,0,0,date("m"),date("d"),date("Y"))
$D_sel = "DATE_FORMAT(`InputDate`,$s_frm)=".strftime($s_frm,strtotime($InputDate));
$m = Array(1=>"январь",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");
//if (!isset($_REQUEST ["di"])) {	
if ($tn2=='' ) {	//&& !$_REQUEST["per"]?>
    <div id="t_actions">
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
	  <td align="left" class="quote" bgcolor="#99CC66">Статистика
      <input name="s_tp" type="hidden" value="<? echo $tp?>"  />
      <input name="s_tn" type="hidden" value="<? echo $tn?>"  /><?php /*?>"+f.s_tn.value+"<?php */?>
      <select name='f_per' id='f_per' onchange='ch_param("sh_actions","<? echo "tn=$tn&tp=$tp&di=$d_inp&per="?>"+this.value,"t_actions");' class='headText' >
			  <option value="0" <? if ($_REQUEST["per"]==0) {?>selected<? }?> >смена</option>
			  <option value="1" <? if ($_REQUEST["per"]==1) {?>selected<? }?> >месяц</option>
	  </select>
	  <? if ($per==0) { //?>
      		<button onclick="ch_param('sh_actions','<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=$per&b=prv&di=$InputDate"?>','t_actions');" ><</button>
	<input name="InputDate" id="InputDate" value="<?php echo $InputDate; ?>" type="date" onChange="ch_param('sh_actions', '<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&di='+this.value+'&per=0"?>', 't_actions');" size="10" />
      		<button onclick="ch_param('sh_actions','<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=0&b=nxt&di=$InputDate"?>','t_actions');" >></button>
	  <? } else { ?>
		  <select name='f_mon' id='f_mon' onchange='ch_param("sh_actions", "<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=$per&di=".date("Y-m")?>", "t_actions");' class='headText' >
		  		<? $cm = 1*date("m",strtotime($di)); for($i=1; $i<=$cm; $i++){
                    echo '<option value='.$i.(($i == $cm)?" selected":"").' >'.$m[$i].'</option>';
                }
               ?>
		  </select><? echo date("Y",strtotime($di));?> года 
	  <? } ?>
      	Только оффис:
        <input name="of" type="checkbox" value="<? echo $of?>" <? if($of) {?>checked <? }?>
         onchange="this.value=this.checked?1:0;ch_param('sh_actions',<? echo "'of='+this.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=$per&di=".($per==0?"$InputDate'":"'+document.forms.ulaForm.f_mon.value");?>,'t_actions');"/>
	  </td>
	</tr>
	</table>
<?
}
//}
if ($tn2=='') {
	if ($tp==1 || $tn==6 || $tn==8) {
		$q_tn = "SELECT TabNum, p_login, sum(Summa*(2-id_ActionType)) AS sumA, sum(Summa*(id_ActionType-1)) AS sumI ".
				"from v_{$db} where $D_sel and `id_ActionType` in (1,2,7,8,9,10) $pers GROUP BY TabNum order by TabNum,InputDate";
		$res = mysql_query($q_tn) or die(mysql_error());
		if (mysql_num_rows($res)==0) { echo "небыло проводок"; return; }
		$Ssumm = $SsumA = $SsumI = $i = 0;
		$bgc = $cfg['BgcolorOne'];
	////////////////////////////////////////////////////////////////////?>
		<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<?	while ($row = mysql_fetch_assoc($res))  {  
			$Ssumm += $row["sumA"] + $row["sumI"];
			$SsumA += $row["sumA"];
			$SsumI += $row["sumI"];
			$s_prog = "ch_param('sh_actions', 'tn2=".$row["TabNum"]."&tn=$tn&tp=$tp&di=$InputDate&per=$per',".
						"'v".$row["TabNum"]."');";?>
		<tr>
			<td align="center" bgcolor="<? echo $bgc?>" colspan="7">
				<span style="font-size:14px"><strong>
					<a href="javascript:{var v_div=document.getElementById('v<? echo $row["TabNum"]?>'); toggle('v<? echo $row["TabNum"]?>'); if(v_div.innerHTML=='') {<? echo $s_prog?>} }"><? echo $row["p_login"]?></a>
				</strong></span>
				<? echo " (", $row["sumA"],"+", $row["sumI"],"=", $row["sumA"]+$row["sumI"]," руб.)" ?>
				<div id="v<? echo $row["TabNum"]?>" style="display:none"></div>
			</td>
		</tr>
	<?		$bgc = ($i++ % 2) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
		}	?>

	<tr>
	  <th <? echo $s_th?>>Всего:<span style="font-size:12px"></span></th>
	  <th width="100" <? echo $s_th?>><? echo $SsumA?><span style="font-size:12px"> руб.</span></th>
	  <th width="100" <? echo $s_th?>><? echo $SsumI?><span style="font-size:12px"> руб.</span></th>
	  <th width="300" <? echo $s_th?>><? echo $Ssumm?><span style="font-size:12px"> руб.</span></th>
	  <!--<th width="210" <? echo $s_th?>></th>-->
	</tr>
	</table>
</div>
<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="actions" />
	<?	/*if ($tn2=='') {	*/	return; }
}
///////////////////////////////////////////////////////////////////
$tn=$tn2;
//echo $q_noti = "SELECT * from v_actions where DATE_FORMAT(`InputDate`,'%Y-%m".($per==0?"-%d":"").
//				"')='$InputDate' ".($tp>1?" and TabNum=".$tn:"")." order by `TabNum`, `InputDate`";
$q_noti = "SELECT * from v_{$db} where $D_sel and TabNum=$tn order by `TabNum`, `InputDate`";
$result = mysql_query($q_noti);		// Выполняем запрос `Summa`<>0 and `id_ActionType`<3 and
if (mysql_num_rows($result)>0) { 
	$bgc = $cfg['BgcolorOne'];	?>
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
		<td align="center" bgcolor="<? echo $bgc?>"> <span style="font-size:14px"><strong>№ </strong></span></td>
		<td width="50" align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong> <? if ($per==1) {?>Дата,<? }?>Время </strong></span></td>
		<td width="100" align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><b> № договора </b></span></td>
		<td width="100" align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong> абонентская </strong></span></td>
		<td width="100" align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong> интернет </strong></span></td>
		<td width="300" align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong> <!--</strong></span></td>
		<td width="210" align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong>--> Опер/Комментарий / Итого </strong></span></td>
	</tr>
<?	
	// Печатаем данные построчно
	$disp = ""; // $row["town"];
		while ($row = mysql_fetch_assoc($result))  {   
			if ($disp != $row["p_login"]) {
			if ($disp != "") {
				$Sabon += $abon[$j];
				$Sinet += $inet[$j];
				$Ssumm += $summ[$j];
			?> <tr>
				  <th <? echo $s_th?> colspan="3"><span style="font-size:12px">Итого <? echo $disp?></span></th>
				  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $abon[$j]?></span></th>
				  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $inet[$j]?></span></th>
				  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $summ[$j]?></span></th>
				  <!--<th <? echo $s_th?> colspan="1"><span style="font-size:12px"></span></th>-->
				</tr><tr><td>&nbsp;</td></tr>
<?			}	/*	?>
			<tr>
			  <th bgcolor="#FFFFFF" colspan="12"><span style="font-size:12px"><? echo $row["p_login"]?></span></th>
			</tr>	<? */	?>
			<tr>
<?			$disp = $row["p_login"];
			$j++;
			$abon[$j] = 0;			$inet[$j] = 0;			$summ[$j] = 0;
			$s1 = "";
		}
		if ($row["id_ActionType"]==1) {$abon[$j] += $row["Summa"];}
		if ($row["id_ActionType"]==2) {$inet[$j] += $row["Summa"];}
		$summ[$j] += $row["Summa"];
		$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];	//;frm_adress //op_f pay	
		$h_bgc = "align='center' bgcolor='$bgc'";
	// op_f(\'pay\', \'Mform\');setTimeout(&quot;srch(\'Nic\')&quot;,500);
	// ch(\'srch\',\'menu=pay&tp='.$tp.'&Bill_Dog='.$row["Bill_Dog"].'\',0,\'Mform\')
	// javascript:{ch_param("frm_adress", "new=new&menu=show_err&Nic=<?php echo $row["Nic"]? >", "");}
	// javascript:{ch_param(\'sh_form\',\'menu=pay&tn='.$tn.'&tp='.$tp.'&Nic='.$row["Nic"].'\',\'Mform\');}
	?>
		<td width="50" <? echo $h_bgc?>><? echo $i?></td>
		<td <? echo $h_bgc?>><? echo strftime(($per==1?"%d, ":"")."%H:%M:%S",strtotime($row["InputDate"]))?></td>
		<td <? echo $h_bgc?>>&nbsp;<a href="javascript:{ch_param('sh_form','<? echo "menu=pay&tn=$tn&tp=$tp&Bill_Dog={$row['Bill_Dog']}','Mform');s_Bill_Dog();}"?>"><? echo $row["Bill_Dog"]?></a></td>
		<td <? echo $h_bgc?> align="right"><? echo ($row["id_ActionType"]==1?$row["Summa"]:'')?>&nbsp;</td>
		<td <? echo $h_bgc?> align="right"><? echo ($row["id_ActionType"]==2?$row["Summa"]:'')?>&nbsp;</td>
		<!--<td bgcolor="<? echo $bgc?>"> </td>-->
		<td bgcolor="<? echo $bgc?>" align="left" colspan="2">&nbsp;<? echo ($row["Summa"]==0?($row["ActionName"]."/".$row["Comment"]):$row["Comment"])?></td>
	</tr>
<? 	}	?>
	<tr>
	  <th <? echo $s_th?> colspan="3"><span style="font-size:12px">Итого <? echo $disp?></span></th>
	  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $abon[$j]?>руб.</span></th>
	  <th <? echo $s_th?> bgcolor="#DFDFDF"><span style="font-size:12px"><? echo $inet[$j]?>руб.</span></th>
	 <!-- <th <? echo $s_th?> bgcolor="#DFDFDF"><span style="font-size:12px"><? echo $summ[$j]?></span></th>-->
	  <th <? echo $s_th?> bgcolor="#DFDFDF"><? echo $summ[$j]?>руб. <span style="font-size:10px"><a href="javascript:{ toggle('v<? echo $tn?>'); }"><? echo "свернуть ▲"?></a></span></th>
	</tr>
<?	
	$Sabon += $abon[$j];	$Sinet += $inet[$j];	$Ssumm += $summ[$j];
/* ?>	
	<tr>
	  <th <? echo $s_th?> colspan="3"><span style="font-size:12px">Всего</span></th>
	  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $Sabon?></span></th>
	  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $Sinet?></span></th>
	  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $Ssumm?></span></th>
	  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"></span></th>
	</tr>	<?	*/
 } else {	echo 'Нет платежей'; }	?>
</table>
