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
$tn2 = isset($_REQUEST ["tn2"])?$_REQUEST ["tn2"]:'';
$of = isset($_REQUEST ["of"])?$_REQUEST ["of"]:1;
$per = $_REQUEST ["per"];
$s_frm = "'%Y-%m".($per==0?"-%d":"")."'";
$pers = $tp>1 || $tn==6 || $tn==8?" and TabNum=$tn":"";
$pers .= $of?" and TabNum<>11":"";
if (isset($_REQUEST ["b"])) {
	if($_REQUEST ["b"]=='prv') {
		$r_q = "SELECT DATE_FORMAT(max(InputDate),$s_frm) as d_inp from v_act where InputDate<'".$_REQUEST ["di"]."'".$pers;
	} else {
		$r_q = "SELECT DATE_FORMAT(min(InputDate),$s_frm) as d_inp from v_act where InputDate>'".$_REQUEST ["di"]." 23:59:59'".$pers;
	}
} else {
	$r_q = "SELECT DATE_FORMAT(max(InputDate),$s_frm) as d_inp from v_act where 1 $pers order by `InputDate`";
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
	
$InputDate = $di;//isset($_REQUEST ["di"]) ? $_REQUEST ["di"]:date("Y-m-d");//date("2010-05-26");//, mktime(0,0,0,date("m"),date("d"),date("Y"))
$D_sel = "DATE_FORMAT(`InputDate`,$s_frm)=".strftime($s_frm,strtotime($InputDate));
$m = Array(1=>"январь",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");
//if (!isset($_REQUEST ["di"])) {	
if ($tn2=='' ) {	//&& !$_REQUEST["per"]?>
	<div id="t_fin">
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
	  <td align="left" class="quote" bgcolor="#99CC66">Финансовый отчёт за
      <input name="s_tp" type="hidden" value="<? echo $tp?>"  />
      <input name="s_tn" type="hidden" value="<? echo $tn?>"  /><?php /*?>"+f.s_tn.value+"<?php */?>
	  <select name='f_per' id='f_per' onchange='ch_param("fin", "<? echo "tn=$tn&tp=$tp&di=$d_inp&per="?>"+this.value, "t_fin");' class='headText' >
			  <option value="0" <? if ($_REQUEST["per"]==0) {?>selected<? }?> >смена</option>
			  <option value="1" <? if ($_REQUEST["per"]==1) {?>selected<? }?> >месяц</option>
	  </select>
	  <? if ($per==0) {?>
      		<button onclick="ch_param('fin','<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=$per&b=prv&di=$InputDate"?>','t_fin');" ><</button>
		  	<input name="InputDate" id="InputDate" value="<?php echo $InputDate; ?>" type="date" onChange='ch_param("fin", "<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&di='+this.value+'&per=0"?>", "t_fin");' size="10" />
      		<button onclick="ch_param('fin','<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=0&b=nxt&di=$InputDate"?>','t_fin');" >></button>
	  <? } else { ?>
		  <select name='f_mon' id='f_mon' onchange='ch_param("fin", "<? echo "of='+document.forms.ulaForm.of.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=$per&di=".date("Y-m")?>", "t_fin");' class='headText' >
		  		<? $cm = 1*date("m",strtotime($di)); for($i=1; $i<=$cm; $i++){
                    echo '<option value='.$i.(($i == $cm)?" selected":"").' >'.$m[$i].'</option>';
                }
               ?>
		  </select><? echo date("Y",strtotime($di));?> года 
	  <? } ?>
      	Только оффис:
        <input name="of" type="checkbox" value="<? echo $of?>" <? if($of) {?>checked <? }?>
         onchange="this.value=this.checked?1:0;ch_param('fin',<? echo "'of='+this.value+'&tn2=$tn2&tn=$tn&tp=$tp&per=$per&di=".($per==0?"$InputDate'":"'+document.forms.ulaForm.f_mon.value");?>,'t_fin');"/>
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
			$s_prog = "ch_param('fin', 'tn2=".$row["TabNum"]."&tn=$tn&tp=$tp&di=$InputDate&per=$per',".
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
<input name="Menu_Item" type="hidden" value="fin" />
	<?	/*if ($tn2=='') {	*/	return; }
}
///////////////////////////////////////////////////////////////////
$tn=$tn2;
$q_noti = "SELECT * from v_{$db} where `Summa`<>0 and `id_ActionType`<3 and DATE_FORMAT(`InputDate`,'%Y-%m-%d')='$InputDate'  and TabNum=$tn order by `TabNum`, `InputDate`"; //; //
$result = mysql_query($q_noti);		// Выполняем запрос
if (mysql_num_rows($result)>0) { 
	$bgc = $cfg['BgcolorOne'];
	$std = 'align="center" bgcolor="'.$bgc.'"><span style="font-size:14px"><strong'; ?>
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
		<td <? echo $std?>> № </strong></span></td>
		<td <? echo $std?>> № договора (Ник) </strong></span></td>
		<td <? echo $std?>> абонентская </strong></span></td>
		<td <? echo $std?>> интернет </strong></span></td>
		<td <? echo $std?>> Итого </strong></span></td>
	</tr><?
	
	// Печатаем данные построчно
	$disp = ""; // $row["town"];
		while ($row = mysql_fetch_assoc($result))  {   
			$s_th = 'background="gray2_bg.gif" bgcolor="#DFDFDF"';
			if ($disp != $row["p_login"]) {
			if ($disp != "") {
				$Sabon += $abon[$j];
				$Sinet += $inet[$j];
				$Ssumm += $summ[$j];
			echo '<tr>
				  <th '.$s_th.' colspan="2"><span style="font-size:12px">Итого '.$disp.'</span></th>
				  <th '.$s_th.' colspan="1"><span style="font-size:12px">'.$abon[$j].'</span></th>
				  <th '.$s_th.' colspan="1"><span style="font-size:12px">'.$inet[$j].'</span></th>
				  <th '.$s_th.' colspan="1"><span style="font-size:12px">'.$summ[$j].'</span></th>
				</tr><tr><td>&nbsp;</td></tr>';
			}
			echo '<tr>
			  <th bgcolor="#FFFFFF" colspan="12"><span style="font-size:12px">'.$row["p_login"].'</span></th>
			</tr>
			<tr>';
			$disp = $row["p_login"];
			$j++;
			$abon[$j] = 0;
			$inet[$j] = 0;
			$summ[$j] = 0;
			$s1 = "";
		}
		if ($row["id_ActionType"]==1) {$abon[$j] += $row["Summa"];}
		if ($row["id_ActionType"]==2) {$inet[$j] += $row["Summa"];}
		$summ[$j] += $row["Summa"];
		$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
	echo '<td align="center" bgcolor="'.$bgc.'">'.$i.'</td>
		<td bgcolor="'.$bgc.'">'.$row["Bill_Dog"]./*'('.$row["Nic"].')'.*/'</td>
		<td bgcolor="'.$bgc.'" align="right">'.($row["id_ActionType"]==1?$row["Summa"]:'').'</td>
		<td bgcolor="'.$bgc.'" align="right">'.($row["id_ActionType"]==2?$row["Summa"]:'').'</td>
		<td bgcolor="'.$bgc.'"> </td>
	</tr>';
 	}	
echo "<tr>
	  <th $s_th colspan='2'><span style='font-size:12px'>Итого $disp</span></th>
	  <th $s_th colspan='1'><span style='font-size:12px'>$abon[$j]</span></th>
	  <th $s_th bgcolor='#DFDFDF' colspan='1'><span style='font-size:12px'>$inet[$j]</span></th>
	  <th $s_th bgcolor='#DFDFDF'> {$summ[$j]}руб. <span style='font-size:10px'><a href='javascript:{ toggle(\"v{$tn}\"); }'>свернуть ▲</a></span></th>
	</tr>";
	
	$Sabon += $abon[$j];
	$Sinet += $inet[$j];
	$Ssumm += $summ[$j];
	
echo '<tr>
	  <th '.$s_th.' colspan="2"><span style="font-size:12px">Всего</span></th>
	  <th '.$s_th.' colspan="1"><span style="font-size:12px">'.$Sabon.'</span></th>
	  <th '.$s_th.' colspan="1"><span style="font-size:12px">'.$Sinet.'</span></th>
	  <th '.$s_th.' colspan="1"><span style="font-size:12px">'.$Ssumm.'</span></th>
	</tr>';
 } else {	echo 'Нет платежей'; }	?>
</table>