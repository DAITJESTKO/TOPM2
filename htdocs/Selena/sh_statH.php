<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
if (isset($GLOBALS['pers'])) { /*	echo $GLOBALS['pers']['TabNum']; */	}
$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;
$tp = $_REQUEST ["tp"];
$tn = $_REQUEST ["tn"];
$tn2 = isset($_REQUEST ["tn2"])?$_REQUEST ["tn2"]:'';
$per = $_REQUEST ["per"];
$prog = "sh_statH";
$d_nm = "t_actions";
$s_frm = "'%Y-%m".($per==0?"-%d":"")."'";
$pers = $tp>1?" and TabNum=$tn":" and TabNum<>11";/// || $tn==6 || $tn==8
if (isset($_REQUEST ["b"])) {
	if($_REQUEST ["b"]=='prv') {
		$r_q = "SELECT DATE_FORMAT(max(InputDate),$s_frm) as d_inp from actions where InputDate<'".$_REQUEST ["di"]."'".$pers;
	} else {
		$r_q = "SELECT DATE_FORMAT(min(InputDate),$s_frm) as d_inp from actions where InputDate>'".$_REQUEST ["di"]." 23:59:59'".$pers;
	}
} else {
	$r_q = "SELECT DATE_FORMAT(max(InputDate),$s_frm) as d_inp from actions where 1 $pers order by `InputDate`";
} //echo $r_q; $r_q = "SELECT DATE_FORMAT(max(InputDate),'%Y-%m".($per==0?"-%d":"")."') as mx_inp from actions ".($tp>1?" where TabNum=".$tn:" where TabNum<>11")." order by `InputDate`";
$res = mysql_query($r_q);
$row = mysql_fetch_assoc($res);
$d_inp = $row['d_inp'];
$di =  isset($_REQUEST ["di"]) && (!isset($_REQUEST ["b"])) ? $_REQUEST ["di"]:$d_inp;//date("Y-m".($per==0?"-d":""));
/*$mx_di = $row['mx_inp'];	$di =  isset($_REQUEST ["di"]) ? $_REQUEST ["di"]:$mx_di;	*/
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
	
$InputDate = $di;//date("2010-05-26");//, mktime(0,0,0,date("m"),date("d"),date("Y"))
$m = Array(1=>"января",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");
//if (!isset($_REQUEST ["di"])) {	?>
<div id="<? echo $d_nm?>">
	<table width="500" border="0" cellpadding="1" cellspacing="1" >
	<tr>
	  <td align="left" class="quote" bgcolor="#99CC66">Статистика загруженности по часам 
      <select name='f_per' id='f_per' onchange='ch_param("<? echo "$prog\".\"tn=$tn&tp=$tp&di=\"+(this.value==0?\"".date("Y-m-d")."\":\"".date("Y-m")."\")+\"&per=\"+this.value\",\"$d_nm"?>");' class='headText' >
			  <option value="0" <? if ($_REQUEST["per"]==0) {?>selected<? }?> >смена</option>
			  <option value="1" <? if ($_REQUEST["per"]==1) {?>selected<? }?> >месяц</option>
			  <option value="2" <? if ($_REQUEST["per"]==2) {?>selected<? }?> >с начала</option>
	  </select>
	  <? if ($_REQUEST["per"]==0) { //?>
      		<button onclick='ch_param("<? echo "$prog\",\"tn2=$tn2&tn=$tn&tp=$tp&per=0&b=prv&di=$InputDate\",\"$d_nm"?>");' ><</button>
			<input name="InputDate" id="InputDate" value="<?php echo $InputDate; ?>" type="date" onChange='ch_param("<? echo "$prog\",\"tn=$tn&tp=$tp&di=+this.value+&per=0\",\"$d_nm"?>");' size="10" />
      		<button onclick='ch_param("<? echo "$prog\",\"tn2=$tn2&tn=$tn&tp=$tp&per=0&b=nxt&di=$InputDate\",\"$d_nm"?>");' >></button>
	  <? } else if ($_REQUEST["per"]==1) { ?>
		  <select name='f_mon' id='f_mon' onchange='ch_param("<? echo "$prog\",\"tn=$tn&tp=$tp&per=1&di=".date("Y-m")."\",\"$d_nm"?>");' class='headText' >
		  		<? $cm = 1*date("m"); for($i=1; $i<=$cm; $i++){
                    echo '<option value='.$i.(($i == $cm)?" selected":"").' >'.$m[$i].'</option>';
                }
               ?>
		  </select>
	  <? } ?>
	  </td>
	</tr>
	</table>
<?
//} SELECT   ".($tp>1?" and TabNum=".$tn:"")." order by `TabNum`, `InputDate`
$q_noti = "SELECT DATE_FORMAT(`InputDate`, '%H') as hi, sum(1) ci FROM `actions` where 1 $pers".
				($per==2?"":(" and DATE_FORMAT(`InputDate`,'%Y-%m".($per==1?"":"-%d")."')='$InputDate' ")).
				"group by DATE_FORMAT(`InputDate`, '%H')";
$result = mysql_query($q_noti);		// Выполняем запрос `Summa`<>0 and `id_ActionType`<3 and
if (mysql_num_rows($result)>0) { 
	$bgc = $cfg['BgcolorOne']; ?>
    	<table width="500" border="0" cellpadding="1" cellspacing="1" > <?
	echo $head = '<tr>
		<td align="center" bgcolor="'.$bgc.'"><span style="font-size:14px"><strong> час </strong></span></td>
		<td align="center" bgcolor="'.$bgc.'"><span style="font-size:14px"><strong> операций </strong></span></td>
	</tr>';
	
	// Печатаем данные построчно
	$disp = "";
		while ($row = mysql_fetch_assoc($result))  if ($row["hi"]!='00'){   
			$s_th = 'background="gray2_bg.gif" bgcolor="#DFDFDF"';
			$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
	
			echo '<td align="center" bgcolor="'.$bgc.'">'.$row["hi"].'</td>
				<td bgcolor="'.$bgc.'">('.$row["ci"].")".str_repeat("█", $row["ci"]).'</td>
			</tr>';
		}	
?>    	</table> <? 
} else {	echo 'Нет платежей'; }	?>
</div>
<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="<? echo $prog?>" />
