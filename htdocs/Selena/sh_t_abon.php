<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
$db = "t_abon";
$f_date = "d_time";	//InputDate
if (isset($GLOBALS['pers'])) {
//	echo $GLOBALS['pers']['TabNum'];
}
  	$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;
	$tp = $_REQUEST ["tp"];
	$tn = $_REQUEST ["tn"];
	$tn2 = isset($_REQUEST ["tn2"])?$_REQUEST ["tn2"]:'';
$per = isset($_REQUEST['per'])?$_REQUEST ["per"]:1;
$s_frm = isset($_REQUEST ["df"])?$_REQUEST ["df"]:"'%Y-%m".($per==0?"-%d":"")."'";
	$chkTabNum = ""; //	($tp>1 || $tn==6)?"and TabNum=$tn":"";
	if (isset($_REQUEST ["b"])) {
		if($_REQUEST ["b"]=='prv') {
			$r_q = "SELECT DATE_FORMAT(max($f_date),$s_frm) as d_inp from $db where error>0 and $f_date<'".$_REQUEST ["di"]."' $chkTabNum";
		}
	} else {
		$r_q = "SELECT DATE_FORMAT(max($f_date),$s_frm) as d_inp from $db where error>0 $chkTabNum";// order by `$f_date`
	}
//	echo $r_q;
$res = mysql_query($r_q);
$row = mysql_fetch_assoc($res);
$d_inp = $row['d_inp'];
	$di =  isset($_REQUEST ["di"]) && (!isset($_REQUEST ["b"])) ? $_REQUEST ["di"]:$d_inp;//date("Y-m".($per==0?"-d":""));

$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$nbo = 1;
$i = 0;
$j = 0;
$Sabon = 0;
$Sinet = 0;
$Ssumm = 0;
$bgc = $cfg['BgcolorOne'];
$flds = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "");
$s_conn = array(0 => "не устан.", 1 => "новое", 2 => "доп.подкл.", 3 => "смена влад.", 4 => "переподкл.", 5 => "смена адреса", 6 => "переоформление" );
	
$InputDate = $di;//date("2010-05-26");//, mktime(0,0,0,date("m"),date("d"),date("Y"))
$m = Array(1=>"января",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");
/****************   группировка *******************/
if ($s_frm != "'%Y-%m-%d'") { //(!isset($_REQUEST ["di"])) {	?>
<div id="d_sh"> <? if(!isset($_REQUEST ["df"])) { ?>
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
	  <td align="centr" class="quote" bgcolor="#99CC66">Ошибочные и неоприходованные абонентские платежи с терминалов 
      	<input name="s_id" onkeyup='ch_param("sh_<? echo $db?>","id="+this.value,"fid");' /></td>
    	<div id="fid"></div>
	</tr>
	</table>
<? }
//}
//if ($tp==1 || $tn==6) {
//	$q_tn = "SELECT sum(1) from v_{$db} where error>0 GROUP BY DATE_FORMAT(`$f_date`,'%Y-%m".($per==0?"-%d":"")."') order by `$f_date`";
	$s_frm2 = isset($_REQUEST ["df"])&& $_REQUEST ["df"] == "'%Y-%m'"?"'%Y-%m-%d'":$s_frm;
	$fltr = (isset($_REQUEST ["df"])&& $_REQUEST ["df"] == "'%Y-%m'"?"DATE_FORMAT(`$f_date`,$s_frm)='$InputDate' and ":"")."error>0";
	$q_tn = "SELECT DATE_FORMAT(`d_time`,$s_frm2) as di, sum(1) as n from v_{$db} where $fltr GROUP BY DATE_FORMAT(`$f_date`,$s_frm2) order by di desc";
	$res = mysql_query($q_tn) or die(mysql_error());
	if (mysql_num_rows($res)==0) { echo "<br>Ошибочных платежей небыло"; return; }
	$i = 0;
	$bgc = $cfg['BgcolorOne'];	?>
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
<?	while ($row = mysql_fetch_assoc($res))  {  // tn2='.$row["TabNum"].'& ?>
	<tr>
		<td <?php if ($s_frm2=="'%Y-%m'"){ ?>align="center" <? }?> bgcolor="<? echo $bgc?>">
        	<span style="font-size:14px"><strong>
				<a href="javascript:{ch_param('sh_<? echo $db?>', &quot;<? echo "tn=$tn&tp=$tp&df=$s_frm2&di={$row['di']}&per=0"?>&quot;, 'v<? echo $row["di"]?>');}"><? echo $row["di"]?></a>
            </strong></span>(<? echo $row["n"]?>)
            <div id="v<? echo $row["di"]?>"></div>
        </td>
	</tr>
<?		$bgc = ($i++ % 2) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
	}	?>
</table>
<? //!	if ($tn2=='') {	return; }
} else {
//! $tn=$tn2;
/****************   группировка   *******************/
$q_noti = "SELECT * from v_{$db} where error>0 and DATE_FORMAT(`$f_date`,$s_frm)='$InputDate'";
				// and TabNum=".$tn. order by `TabNum`, `$f_date`"
$result = mysql_query($q_noti);		// Выполняем запрос `sum`<>0 and `id_ActionType`<3 and
if (mysql_num_rows($result)>0) { 
	$bgc = $cfg['BgcolorOne'];	?>
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
		<td align="center" bgcolor="<? echo $bgc?>"> <span style="font-size:14px"><strong>№ </strong></span></td>on,lnhm ,?G.k79o
		<td align="center" bgcolor="<? echo $bgc?>" width="50"><span style="font-size:14px"><strong> Время </strong></span></td>
		<td align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong>договор</strong></span></td>
		<td align="center" bgcolor="<? echo $bgc?>" width="40"><span style="font-size:14px"><strong> txn_id </strong></span></td>
		<td align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong> сумма </strong></span></td>
		<td align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong> Ошибка </strong></span></td>
        <td align="center" bgcolor="<? echo $bgc?>"><span style="font-size:14px"><strong> Абон.информация </strong></span></td>
		<td align="center" bgcolor="<? echo $bgc?>" colspan="2" width="100"><span style="font-size:14px"><strong> что сделать </strong></span></td>
	</tr>
<?	
	// Печатаем данные построчно
	$disp = ""; // $row["town"];
			$j++;
			$abon[$j] = 0;			$inet[$j] = 0;			$summ[$j] = 0;
			$s1 = "";
		$stt = array("" => "не уст.", 1 => "подкл.", 2 => "откл.", 3 => "расторг.", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "");
		while ($row = mysql_fetch_assoc($result))  {   
			$s_th = 'background="gray2_bg.gif" bgcolor="#DFDFDF"';
	/*		if ($disp != $row["p_login"]) {
			if ($disp != "") {	*/
//				$Sabon += $abon[$j];
				$Sinet += $inet[$j];
				$Ssumm += $summ[$j];
/*			?> <tr>
				  <th <? echo $s_th?> colspan="3"><span style="font-size:12px">Итого <? echo $disp?></span></th>
				  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $abon[$j]?></span></th>
				  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $inet[$j]?></span></th>
				  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $summ[$j]?></span></th>
				  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"></span></th>
				</tr><tr><td>&nbsp;</td></tr>
<?			}	?>
			<tr>
			  <th bgcolor="#FFFFFF" colspan="12"><span style="font-size:12px"><? echo $row["p_login"]?></span></th>
			</tr>
			<tr>
<?			$disp = $row["p_login"];	*/
			$j++;
			$abon[$j] = 0;			$inet[$j] = 0;			$summ[$j] = 0;
			$s1 = "";
/*		}
		if ($row["id_ActionType"]==1) {$abon[$j] += $row["sum"];}
		if ($row["id_ActionType"]==2) {$inet[$j] += $row["sum"];}	*/
		$inet[$j] += $row["sum"];
		$summ[$j] += $row["sum"];
		$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];	//;frm_adress //op_f pay	
	// op_f(\'pay\', \'Mform\');setTimeout(&quot;srch(\'Nic\')&quot;,500);
	// ch(\'srch\',\'menu=pay&tp='.$tp.'&Bill_Dog='.$row["Bill_Dog"].'\',0,\'Mform\')
	// javascript:{ch_param("frm_adress", "new=new&menu=show_err&Nic=<?php echo $row["Nic"]? >", "");}
	// javascript:{ch_param(\'sh_form\',\'menu=pay&tn='.$tn.'&tp='.$tp.'&Nic='.$row["Nic"].'\',\'Mform\');}
	if ($row["error"] >0 /*== 1 || $row["error"] == 2*/) {
//		$rq = mysql_query("SELECT account FROM logins WHERE Login = '".$row["account"]."'") or die(mysql_error());
		$rq = mysql_query("SELECT Bill_Dog as account, Date_pay as dp, Date_start_st as ds, Date_end_st as de, state FROM customers WHERE Bill_Dog = '".$row["account"]."'") or die(mysql_error());
		$r_er = mysql_fetch_assoc($rq);

	}
	?>
		<td align="center" bgcolor="<? echo $bgc?>"><? echo $i?></td>
		<td bgcolor="<? echo $bgc?>" align="center"><? echo stristr($row["$f_date"], " ")?></td>
		<td bgcolor="<? echo $bgc?>" align="center"><a href="javascript:{ch_param('sh_form','<? echo "menu=pay&tn=$tn&tp=$tp&Bill_Dog=".$row['account']."','Mform'"?>);s_Bill_Dog();}"><? echo $row["account"]?></a></td>
		<td bgcolor="<? echo $bgc?>" align="right"><? echo $row["txn_id"] ?></td>
		<td bgcolor="<? echo $bgc?>" align="right"><? echo $row["sum"] ?></td>
		<td bgcolor="<? echo $bgc?>" align="left"><? echo $row["er_descr"] ?></td>
        <td bgcolor="<? echo $bgc?>" align="left"><? echo $stt[$r_er["state"]]," с ",$r_er["ds"],($r_er["de"]!=""?" по ".$r_er["de"]:""),",<br>оплачено по ",$r_er["dp"]; ?></td>
		<td bgcolor="<? echo $bgc?>" width="40">
        <? if (in_array($row["error"],array(1, 2, 7))) {?>
        	=><input id="<? echo $row["txn_id"] ?>" size="6" onchange='ch_param("dir_ab","txn_id=<? echo $row["txn_id"]."&tn=$tn" ?>&account="+this.value,"v<? echo $row["txn_id"] ?>");'/>
            <? }?>
        </td>
		<td bgcolor="<? echo $bgc?>">
        	<div id="v<? echo $row["txn_id"] ?>"></div></td>
	</tr>
<? 	}	/* ?>
	<tr>
	  <th <? echo $s_th?> colspan="4"><span style="font-size:12px">Итого <? echo $disp?></span></th>
	  <th <? echo $s_th?> bgcolor="#DFDFDF"><span style="font-size:12px"><? echo $inet[$j]?></span></th>
	  <th <? echo $s_th?> bgcolor="#DFDFDF"><span style="font-size:12px"><? echo $summ[$j]?></span></th>
	  <th <? echo $s_th?> bgcolor="#DFDFDF"><span style="font-size:12px"></span></th>
	</tr>
<?	*/
	$Sabon += $abon[$j];	$Sinet += $inet[$j];	$Ssumm += $summ[$j];
?>	
	<tr>
	  <th <? echo $s_th?> colspan="4"><span style="font-size:12px">Всего</span></th>
	  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $Sinet?></span></th>
<?php /*?>	  <th <? echo $s_th?> colspan="1"><span style="font-size:12px"><? echo $Ssumm?></span></th><?php */?>
	  <th <? echo $s_th?> colspan="4"><span style="font-size:12px"></span></th>
	</tr>	<?
 } else {	echo 'Нет платежей'; }	?>
</table>
<? } ?>
</div>
<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="<? echo $db?>" />
