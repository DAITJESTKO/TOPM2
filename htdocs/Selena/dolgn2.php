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
	$prg = 'dolgn2';
	$param = "tp=$tp&tn=$tn&";
	$v_db = 'v_customer';
	$GLOBALS['menu'] = 'dolgn2';
$m_TabNum = isset($_REQUEST ["m_TabNum"])? $_REQUEST ["m_TabNum"]:"";
$q_TN = "m_TabNum=$m_TabNum";
$_TN = isset($_REQUEST ["m_TabNum"])?"$q_TN&":"";

$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgc = $cfg['BgcolorTwo'];

if (!isset($d_m) || isset($_REQUEST ["d_m"])) {
	if(isset($_REQUEST ["d_m"])) {
		$d_m = $_REQUEST ["d_m"]; //
		$d_d = (isset($_REQUEST ["d_d"]))? $_REQUEST ["d_d"]:"0";
 	} else if (!isset($d_m)) {
		$d_m = "6";
		$d_d = "0";
		$Date_pay = date("Y-m-d", mktime(0,0,0,date("m")-$d_m,date("d")-$d_d,date("Y")));
		$q_DP = "DATE_FORMAT(`Date_pay`,'%Y-%m-%d')<='$Date_pay'";
		?>	Просроченность оплаты более <input name="d_m" value="<? echo $d_m?>" size="3" onchange='document.forms.ulaForm.Date_pay.value = d2str(date_add(new Date(<? //"echo $Date_pay"?>), "month", -this.value));ch_param("<? echo $prg?>","<? echo $param,$_TN?>d_m="+this.value,"dolgn2");'/> мес. 
        <input name="d_d" value="0" size="3" onchange="alert('d2 = date_add(d2, \"day\", d');"/>дн. 
        Оплата по <input name="Date_pay" value="<? echo $Date_pay?>" size="10"/>
		<?
		?><div id="<? echo $prg?>"><?
	}
	$Date_pay = date("Y-m-d", mktime(0,0,0,date("m")-$d_m,date("d")-$d_d,date("Y")));
	$q_DP = "inet is null and DATE_FORMAT(`Date_pay`,'%Y-%m-%d')<='$Date_pay' and `state`=1";

	if($_TN=="" || ((!isset($_REQUEST ["name_street"])) && ($tp==4))) {
		$q_con ="SELECT Sum('1') AS coun from $v_db where $q_DP ".($m_TabNum==""?"":" and $q_TN ");
		$r_coun = mysql_query($q_con) 
							or die(mysql_error());
		$_coun = mysql_fetch_assoc($r_coun);
		echo "<b>&nbsp;&nbsp;Всего должников: ".$_coun["coun"]."</b>";
	}
}
//if($tp<=4) {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$q_noti = "SELECT m_TabNum, RegionName, Sum('1') AS coun from $v_db where $q_DP ".
		($m_TabNum==""?"":" and $q_TN ")."GROUP BY m_TabNum";
	$result = mysql_query($q_noti) or die(mysql_error());?>
	<? if (!isset($result)) return; ?>
	<table border="0" cellpadding="2" cellspacing="1" >
	<? if (!isset($_REQUEST ["m_TabNum"])) { ?>
        <tr><td>Регион (заявок)</td></tr>
			<? 	while ($row = mysql_fetch_assoc($result))  {	?>
                <tr>
                    <td bgcolor="#E5E5E5"><b>
					<a href='javascript:{ch_param("<? echo $prg?>","<? echo $param?>m_TabNum=<? echo $row["m_TabNum"]?>&d_m="+document.forms.ulaForm.d_m.value,"d_TN<? echo $row["m_TabNum"]?>");}'> <? echo $row["RegionName"]?> </a>&nbsp;</b>(<? echo $row["coun"];?>)
                    <div id="d_TN<? echo $row["m_TabNum"]?>"></div></td>
                </tr>
			<?    }	?>
         </table>
	<?   }  
////////////////////////////////////////////////////////////////////////////////////////////////

if ($m_TabNum=="") return;

$q = "SELECT name_street,Sum(1) AS coun from $v_db WHERE $q_DP AND $q_TN GROUP BY name_street";
$result = mysql_query($q) or die(mysql_error());

if (!isset($_REQUEST ["name_street"])) { ?>
	<table border="0">
		<? 	while ($row = mysql_fetch_assoc($result))  {	?>
            <tr><td width="10"></td>
                <td bgcolor="#E5E5E5"><b>
                <a href='javascript:{ch_param("<? echo $prg?>","<? echo $param?>m_TabNum=<? echo $m_TabNum?>&name_street=<? echo $row["name_street"]?>&d_m="+document.forms.ulaForm.d_m.value,"d_s<? echo $row["name_street"]?>");}'> <? echo $row["name_street"]?> </a>&nbsp;</b>(<? echo $row["coun"];?>)<!--</td>
                <td colspan="3">--><div style="border:thin #009 solid" id="d_s<? echo $row["name_street"]?>"></div></td>
            </tr>
        <?    }	?>
    </table>
	<?
}
$name_street = (isset($_REQUEST ["name_street"]))? $_REQUEST ["name_street"]:"";
if ($name_street=="") return;

$q_str = "name_street = '".$name_street."'";

$q = "SELECT Num_build,Sum(1) AS coun from $v_db WHERE $q_DP AND $q_TN and $q_str GROUP BY Num_build";
$result = mysql_query($q) or die(mysql_error());

if (!isset($_REQUEST ["Num_build"])) { ?>
	<table border="0">
            <tr>
		<? 	while ($row = mysql_fetch_assoc($result))  {	?>
            <tr><td width="10"></td>
                <td bgcolor="#E5E5E5"><b>
                <a href='javascript:{ch_param("<? echo $prg?>","<? echo $param?>m_TabNum=<? echo $m_TabNum?>&name_street=<? echo $name_street?>&Num_build=<? echo $row["Num_build"]?>&d_m="+document.forms.ulaForm.d_m.value,"d_b<? echo $name_street.$row["Num_build"]?>");}'> <? echo $row["Num_build"]?> </a>&nbsp;</b>(<? echo $row["coun"];?>)<div id="d_b<? echo $name_street.$row["Num_build"]?>"></div></td>
        <?    }	?>
            </tr>
		<tr><td width="10"></td><td colspan="3"><div id="div_build"></div></td></tr>
    </table>
	<?
}

$Num_build = (isset($_REQUEST ["Num_build"]))? $_REQUEST ["Num_build"]:"";
if ($Num_build=="") return;

$q_bld = "Num_build = '".$Num_build."'";
$nbo = 1;
$i = 0;
$s_conn = array(0 => "не устан.", 1 => "новое", 2 => "доп.подкл.", 3 => "смена влад.", 4 => "переподкл.", 5 => "смена адреса", 6 => "переоформление" );
$m = Array(1=>"янв",2=>"фев",3=>"мар",4=>"апр",5=>"мая",6=>"июн",7=>"июл",8=>"авг",9=>"сен",10=>"окт",11=>"ноя",12=>"дек");
//,Date_in,phone_Dop
 $q_noti = "SELECT Bill_Dog,RegionName,Korpus,Podjezd,`floor`,flat,Fam,Name,Father,phone_Cell,phone_Home,phone_Work,Date_pay,`Comment`, conn FROM $v_db WHERE $q_DP AND $q_TN and $q_str AND $q_bld";
$result = mysql_query($q_noti) or die(mysql_error());		// Выполняем запрос
if (mysql_num_rows($result)>0) { 
	echo "ул.<b>".$name_street."</b>, дом <b>".$Num_build."</b>";
	$bgh = 'bgcolor="#FFFFFF"';
	?>
<!--<table width="800" border="0" cellpadding="2" cellspacing="1" >
	<tr><td align="right">
	  <hr /></td>
	</tr>
</table>-->
	<table width="100%" border="0">
<?php /*?>	<tr>
	  <td colspan="12" align="center" class="quote" bgcolor="#99CC66">
      		Заявки монтажника на ремонт в сети Селена и подключение</td>
	</tr><?php */?>
	<tr  bgcolor="#00FFFF">
	<td align="center" ><strong>№</strong></td>
	<? if($tp<4) { ?>
    	<td align="center" ><strong>Договор</strong></td>
	<? }?>
	<td align="center" ><strong><!--Улица дом.-->кв.<br />(подъезд, эт.)</strong></td>
	<td align="center" ><strong>Ф.И.О.</strong></td>
	<td align="center" ><strong>Конт. телефоны</strong></td>
<!--	<td align="center" ><strong>Заявка</strong></td>-->
	<td align="center" ><strong>Дата<br />оплачен.</strong></td>
	<td align="center" ><strong>Коммент.</strong></td>
<!--	<td align="center" ><strong>Дата<br /> исполн.</strong></td>-->
	<td align="center" ><strong><? if($tp<4) {?><? }?></strong></td>
	</tr>
	
	<?php
	// Печатаем данные построчно
	$t1 = ""; // $row["town"];
		while ($row = mysql_fetch_assoc($result))  {   ?>
<?php /*?>			if (!strcmp ($t1, $row["RegionName"])==0) { //mont_Fam	?> 
	<tr>
	  <th background="gray2_bg.gif" bgcolor="#DFDFDF" colspan="12"><span style="font-size:12px"> <? echo $row["RegionName"]; //mont_Fam ?></span></th>
	</tr>
	<tr>
		<?	$t1 = $row["RegionName"]; //town	mont_Fam
			$s1 = ""; // $row["name_street"];
	} else {
//			echo '<td>'.' '.'</td>';
	};
	if (!strcmp ($s1, $row["name_street"])==0) {  //  name_street
		echo '<td> </td><td> </td>'; ?>
		 <th colspan="1" background="gray2_bg.gif" bgcolor="#DFDFDF"><span style="font-size:12px"> <? echo $row["name_street"];?></span></th>
	</tr>
	<tr>
	<? $s1 = $row["name_street"]; } else { /*			echo '<td>'.' '.'</td>'; * /	 }; //  name_street	
<?php */?>
<?		$bgc = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];		?>
		<td align="center" bgcolor="<?php echo $bgc; ?>"> <?php echo $i;	?> </td>
	<? if($tp<4) { ?>
		<td align="center" bgcolor="<?php echo $bgc; ?>">
        	<a href="javascript:{ch_param('sh_form','menu=pay&tn=<? echo $tn.'&tp='.$tp.'&Bill_Dog='.$row["Bill_Dog"]?>','Mform');s_Bill_Dog();}"><? echo $row["Bill_Dog"] ?></a>
      <? // $d_in = strtotime($row["Date_in"]); echo date("j ", $d_in).$m[date("n", $d_in)].date(" Y", $d_in) ?>
      </td>
    <? }?>
		<td bgcolor="<?php echo $bgc; ?>" colspan="1">
			<?php echo /*"д.<b>".$Num_build.*/($row["Korpus"]>0?"К.".$row["Korpus"]:"").
			"</b> кв.<b>".$row["flat"]."</b>"." (пд.<b>".$row["Podjezd"].($row["floor"]>0?$row["floor"]:"")."</b>)";	?> </td>
		<td bgcolor="<?php echo $bgc; ?>"> <?php echo ($tp<4?$row["Fam"].'<br>':"").$row["Name"].' '.$row["Father"]; 			?> </td>
		<td bgcolor="<?php echo $bgc; ?>"> <?php 
			echo //($row["phone_Dop"]>0?"к.т.".$row["phone_Dop"]."<br>":"").
				 ($row["phone_Home"]>0?"д.".$row["phone_Home"]."<br>":"").
				 ($row["phone_Cell"]>0?     $row["phone_Cell"]."<br>":"").
				 ($row["phone_Work"]>0?"р.".$row["phone_Work"]."<br>":"") ?> </td>
	<?php /*?>	<td bgcolor="<?php echo $bgc; ?>"> <?php echo $row["Notify"]; ?><?php */?>
		<td align="center" bgcolor="<?php echo $bgc; ?>"> <?php $d_pln = strtotime($row["Date_pay"]);	 echo date("j ", $d_pln).$m[date("n", $d_pln)].date(" Y", $d_pln)						?> </td>
		<td bgcolor="<?php echo $bgc; ?>"><input id="c<?php echo $row["Bill_Dog"]?>" size="9" onchange='ch_param("do_com_cust","com="+document.getElementById("c<? echo $row["Bill_Dog"]?>").value+"&Bill_Dog=<? echo $row["Bill_Dog"]?>","d<? echo $row["Bill_Dog"]; ?>");' value="<? echo $row["Comment"]; ?>"/> </td>
<?php /*?>		<td bgcolor="<?php echo $bgc; ?>"><input name="d_fact<?php //echo $row["Num_Notify"]?>" type="date" size="8" onchange='ch_param("do_cls_noti","com="+document.getElementById("c<? echo //$row["Num_Notify"]?>").value+"&conn=<? echo $row["conn"]?>&Notify=<? //echo $row["Num_Notify"]?>&Bill_Dog=<? echo $row["Bill_Dog"]?>&Date_Fact="+this.value,"d<? //echo $row["Num_Notify"]; ?>"); this.disabled="disabled";document.getElementById("c<? //echo $row["Num_Notify"]?>").disabled="disabled";' /> </td><?php */?>
		<td bgcolor="<?php echo $bgc; ?>"><div id="d<? echo $row["Bill_Dog"]; ?>">
<?php /*?>	<? if ($tp<4 && $row["conn"]=='' && $row["Date_in"]==date("Y-m-d"/*, mktime(0,0,0,date("m"),date("d"),date("Y"))* /) ){ ?>
			<button name="B_canc" type=button onClick="alert('<?php echo $row["Num_Notify"]?>');"><img src="BD14755_.gif" align=middle alt="Отмени"></button>
	<? } ?><?php */?>
			</div>
		</td>
	</tr>		<?php 
		}
} /*else {
?>Нет заявок<? }*/

//*****************************************************************************************************************************
?>
</div>
<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="mont" />
<p>&nbsp;</p>