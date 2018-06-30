<link href="selena.css" rel="stylesheet" type="text/css" />
<?php 
require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
if (isset($GLOBALS['pers'])) {
	echo $GLOBALS['pers']['TabNum'];
}
$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;
$tp = $_REQUEST ["tp"];
$tn = $_REQUEST ["tn"];
$s = isset($_REQUEST ["s"])?$_REQUEST ["s"]:1;
$param = "tp=$tp&tn=$tn&".(isset($_REQUEST ["s"])?("s=".$_REQUEST ["s"]."&"):"");
$GLOBALS['menu'] = 'mont';
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgc = $cfg['BgcolorTwo'];
$q_dlg = "Notify = 'откл.(долг)'";
$q_rem = "Notify <> 'откл.(долг)'";
$q_ = $s==1?$q_dlg:$q_rem;$Date_Plan = date("Y-m-d", mktime(0,0,0,date("m"),date("d")/*+3*/,date("Y")));
$q_DP = "$q_ and `Date_Fact` IS NULL and DATE_FORMAT(`Date_Plan`,'%Y-%m-%d')<='$Date_Plan'";
if (!isset($_REQUEST ["s"])) {
 ?><input name="s" type="checkbox" value="<? echo $s?>" <? if($s==1) {?>checked="checked"<? }?> onchange='this.value=this.value==1?0:1;ch_param("logs","<? echo $param?>&s="+this.value,"dlogin")' />
группировать
<div id="dlogin">
<?
}
if($tp==1) {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	$q_noti = "SELECT m_TabNum, RegionName, Sum('1') AS coun from logs where $q_DP GROUP BY m_TabNum"; WHERE state='1'
	$q_noti = "SELECT DT,login,ip,state".($s==1?",Sum('1') AS coun":"")." FROM `logs`".($s==1?" GROUP BY login":"");
	$result = mysql_query($q_noti) or die(mysql_error());?>
	<table border="0" cellpadding="2" cellspacing="1" >
		<tr>
	<? if (!(isset($_REQUEST ["login"]) || $s==0)) { ?>
			<td align="center" colspan="2"><h3>
				<? if (!isset($result)) { ?>
					Посещений нет</h3></td></tr> <? return;
				} ?>
				Посещения</h3>
			</td></tr>		
<!--        <tr><td>Персонал</td></tr>-->
			<? 	while ($row = mysql_fetch_assoc($result))  { ?>
                <tr>
                    <td bgcolor="#E5E5E5">
					<b><a href='javascript:{ch_param("logs","<? echo $param."s=$s&login=".$row["login"]?>","d_TN<? echo $row["login"]?>");}'> <? echo $row["login"]?> </a>&nbsp;</b>(<? echo $row["coun"];?>)
                    <div id="d_TN<? echo $row["login"]?>"></div></td>
                </tr>
			<?    }	?>
         </table>
	<?   }  
////////////////////////////////////////////////////////////////////////////////////////////////
}
$login = (isset($_REQUEST ["login"]))? $_REQUEST ["login"]:"";
if ($login=="" && $s==1) return;

$s_conn = array(0 => "не устан.", 1 => "новое", 2 => "доп.подкл.", 3 => "смена влад.", 4 => "переподкл.", 5 => "смена адреса", 6 => "переоформление" );
$m = Array(1=>"янв",2=>"фев",3=>"мар",4=>"апр",5=>"мая",6=>"июн",7=>"июл",8=>"авг",9=>"сен",10=>"окт",11=>"ноя",12=>"дек");
$q_TN = "login = '$login'";
$q = "SELECT DT,login,ip,state from logs ".($login==""?"":"WHERE $q_TN")." order by DT desc";
$result = mysql_query($q) or die(mysql_error());

$nbo = 1;
$i = 0;
if (mysql_num_rows($result)>0) { 
	$bgh = 'bgcolor="#FFFFFF"';	?>
	<table width="100%" border="0">
	<tr  bgcolor="#00FFFF">
        <td align="center" ><strong>№</strong></td>
        <td align="center" ><strong>Дата</strong></td>
    <? if ($s==0) {?>
        <td align="center" ><strong>логин</strong></td>
    <? }?>    
        <td align="center" ><strong>IP</strong></td>
        <td align="center" ><strong>результат</strong></td>
	</tr>
	
	<?php
	// Печатаем данные построчно
	$t1 = ""; // $row["town"];
	while ($row = mysql_fetch_assoc($result))  {   
		$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];		 ?>
	<tr>
		<td align="center" bgcolor="<?php echo $bgc; ?>"> <?php echo $i;						?> </td>
		<td align="center" bgcolor="<?php echo $bgc; ?>"> 
				<?php $d_in = strtotime($row["DT"]); echo date("j ", $d_in).$m[date("n", $d_in)].date(" Y", $d_in).date(" H:i", $d_in)." "?> </td>
    <? if ($s==0) {?>
		<td bgcolor="<?php echo $bgc; ?>" colspan="1"><?php echo $row["login"];?> </td>
    <? }?>
		<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["ip"]; 			?> </td>
		<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["state"]; ?></td>
	</tr>		<?php 
		}
} 	?>
</div>