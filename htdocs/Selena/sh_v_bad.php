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
$per = isset($_REQUEST ["per"])?$_REQUEST ["per"]:0;
$prog = "sh_v_bad";
$d_nm = "d_v_bad";
$db = "v_bad";
$f_date = "InputDate";	//d_time
$s_frm = "'%Y-%m".($per==0?"-%d":"")."'";
$pers = ""; //$tp>1?" and TabNum=$tn":" and TabNum<>11";/// || $tn==6 || $tn==8
if (isset($_REQUEST ["b"])) {
	if($_REQUEST ["b"]=='prv') {
		$r_q = "SELECT DATE_FORMAT(max($f_date),$s_frm) as d_inp from $db where $f_date<'".$_REQUEST ["di"]."'".$pers;
	} else {
		$r_q = "SELECT DATE_FORMAT(min($f_date),$s_frm) as d_inp from $db where $f_date>'".$_REQUEST ["di"]." 23:59:59'".$pers;
	}
} else {
	$r_q = "SELECT DATE_FORMAT(max($f_date),$s_frm) as d_inp from $db where 1 $pers order by $f_date";
} echo $r_q; //$r_q = "SELECT DATE_FORMAT(max(InputDate),'%Y-%m".($per==0?"-%d":"")."') as mx_inp from actions ".($tp>1?" where TabNum=".$tn:" where TabNum<>11")." order by `InputDate`";
$res = mysql_query($r_q) or die(mysql_error());
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
$s_conn = array(0 => "не устан.", 1 => "новое", 2 => "доп.подкл.", 3 => "смена влад.", 4 => "переподкл.", 5 => "смена адреса", 6 => "переоформление" );
	
$InputDate = $di;//date("2010-05-26");//, mktime(0,0,0,date("m"),date("d"),date("Y"))
$m = Array(1=>"января",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");
if (!isset($_REQUEST ["di"])) {	/*?>
<div id="<? echo $d_nm?>">
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
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
<?	*/
}
/****************   группировка *******************/
if (!isset($_REQUEST ["di"])) {	?>
<div id="<? echo $d_nm?>">
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
	<tr>
	  <td align="centr" class="quote" bgcolor="#99CC66">Интернет платежи отключенных или задолжавших абонентов</td>
	</tr>
	</table>
<?
//}
//if ($tp==1 || $tn==6) {-%d-%d
//	$q_tn = "SELECT sum(1) from v_t_inet where error>0 GROUP BY DATE_FORMAT(`$f_date`,'%Y-%m".($per==0?"-%d":"")."') order by `$f_date`";
	$q_tn = "SELECT DATE_FORMAT($f_date,$s_frm) as di, sum(1) as cou from $db GROUP BY DATE_FORMAT($f_date,$s_frm)";
	$res = mysql_query($q_tn) or die(mysql_error());
	if (mysql_num_rows($res)==0) { echo "ничего не найдено"; return; }
	$i = 0;
	$bgc = $cfg['BgcolorOne'];	?>
	<table width="800" border="0" cellpadding="1" cellspacing="1" >
<?	while ($row = mysql_fetch_assoc($res))  {  // tn2='.$row["TabNum"].'& ?>
	<tr>
		<td align="center" bgcolor="<? echo $bgc?>">
        	<span style="font-size:14px"><strong>
				<a href="javascript:{ch_param('<? echo "$prog', 'tn=$tn&tp=$tp&per=0&di=".$row["di"]?>', 'v<? echo $row["di"]?>');}"><? echo $row["di"]?></a>
            </strong></span> (<? echo $row["cou"]?>)
            <div id="v<? echo $row["di"]?>"></div>
        </td>
	</tr>
<?		$bgc = ($i++ % 2) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
	}	?>
</table>
<?	if ($tn2=='') {	return; }
}
$tn=$tn2;
/****************   группировка   *******************/
 //SELECT   ".($tp>1?" and TabNum=".$tn:"")." order by `TabNum`, `InputDate`
$q_noti = "SELECT * FROM $db where DATE_FORMAT(`$f_date`,$s_frm)='$InputDate' $pers";
$res = mysql_query($q_noti);		// Выполняем запрос `Summa`<>0 and `id_ActionType`<3 and
if (mysql_num_rows($res)>0) { 
	$bgc = $cfg['BgcolorOne'];
	$th = "align='center' bgcolor='{$bgc}'"; ?>
    	<table width="800" border="0" cellpadding="1" cellspacing="1" align="center" > <?
	echo $head = '<tr>
		<td $th colspan="2"><span style="font-size:14px"><strong>Интернет счёт</strong></span></td>
<!--		<td $th><span style="font-size:14px"><strong></strong></span></td>-->
		<td $th><span style="font-size:14px"><strong>Абон.счёт</strong></span></td>
		<td $th><span style="font-size:14px"><strong>Оплачено по</strong></span></td>
		<td $th><span style="font-size:14px"><strong>Абон. тариф</strong></span></td>
		<td $th><span style="font-size:14px"><strong>абон. статус</strong></span></td>
		<td $th><span style="font-size:14px"><strong>с даты</strong></span></td>
	</tr>';
	
	// Печатаем данные построчно
	$disp = "";
//*********************	//print "php_sockets.dll - "; 
if(!extension_loaded('sockets')) 
	{ print "<b>Не загружена библиотека для работы с сокетами!!!</b><br>";		return; }
error_reporting(E_ALL); 
set_time_limit(30); 
ob_implicit_flush(); 
if (($fp = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === False) {
    echo "Ошибка соединения с сервером биллинга: " . socket_strerror($fp) . "\n";		return;	}

$result    = socket_connect($fp, $IPb, 49160); 
if (!$fp) {	echo "$errstr ($errno)<br>\n"; return; }

if (!get_wellcome($fp)) { echo date("Y-m-d H:i:s")," Ошибка при прочитении данных приветствия<br> ";	return;	}

$dfrz = isset($_REQUEST["dfrz"])?$_REQUEST["dfrz"]:"dfrz";
$doff = isset($_REQUEST["doff"])?$_REQUEST["doff"]:"doff";
//*********************	

		while ($row = mysql_fetch_assoc($res))  {   
			$off = isOFF(send_command($fp, "acc ".$row["account"]));
			$s_off = /*($off)?"on":*/"off";
			$b_off = "<button type=button onClick=\"f = document.forms.ulaForm; Bill_Dog = f_Bill_Dog(); ".
				"ch_param('refr_3w', 'cmd=".$s_off."&account=".$row["account"]."', 'doff".$row["account"]."');\">".($off?"В":"ОТ")."КЛ</b>ючить"."</button>";
			$s_th = 'background="gray2_bg.gif" bgcolor="#DFDFDF"';
			$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];
		?>	<tr>
				<td align="center" bgcolor="<? echo $bgc?>"><? echo $row["account"]?></td>
				<td align="center" bgcolor="<? echo $bgc?>"><div id="doff<? echo $row["account"]?>" align="centr"><? echo $off?"отключен":$b_off ?></div></td>
				<td align="center" bgcolor="<? echo $bgc?>"><a href="javascript:{ch_param('sh_form','<? echo "menu=pay&tn=$tn&tp=$tp&Bill_Dog=".$row["Bill_Dog"]?>','Mform');s_Bill_Dog();}">
					<? echo $row["Bill_Dog"]?></a></td>
				<td align="center" bgcolor="<? echo $bgc?>"><? echo $row["Date_pay"]?></td>
				<td align="center" bgcolor="<? echo $bgc?>"><? echo $row["name_ab"]?></td>
				<td align="center" bgcolor="<? echo $bgc?>"><? echo ($row["state"]==1?"ВКЛючен":($row["state"]==2?"<font color='0033FF'>ОТКЛючен</font>":" ?"))?></td>
				<td align="center" bgcolor="<? echo $bgc?>"><? echo $row["Date_start_st"]?></td>
			</tr>
<?		}
	if ($fp) { socket_close($fp); }
?>    	</table> <? 
} else {	echo 'Нет платежей'; }	?>
</div>
<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="<? echo $prog?>" />
