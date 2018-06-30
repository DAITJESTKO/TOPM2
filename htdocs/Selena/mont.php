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
if (!(isset($_REQUEST ["tp"]) && isset($_REQUEST ["tn"]))) return;
$GLOBALS['tp'] = isset($_REQUEST ["tp"]) ? $_REQUEST ["tp"]: 0;
$tp = $_REQUEST ["tp"];
$tn = $_REQUEST ["tn"];
$s = isset($_REQUEST ["s"])?$_REQUEST ["s"]:1;
$param = "tp=$tp&tn=$tn&".(isset($_REQUEST ["s"])?("s=".$_REQUEST ["s"]."&"):"s=1&");
$GLOBALS['menu'] = 'mont';
$cfg['BgcolorOne'] = "#E5E5E5";
$cfg['BgcolorTwo'] = "#D5D5D5";
$bgc = $cfg['BgcolorTwo'];
$q_dlg = "Notify = 'откл.(долг)'";
$q_rem = "Notify <> 'откл.(долг)'";
$q_ = $s==1?$q_dlg:$q_rem;$Date_Plan = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+3,date("Y")));
$q_DP = "$q_ and `Date_Fact` IS NULL and DATE_FORMAT(`Date_Plan`,'%Y-%m-%d')<='$Date_Plan' and (canc is null or canc=0)
		 AND not (length(mac)>0 and auto=1 and state>0)";
if (!isset($_REQUEST ["s"])) {
 ?><input name="s" type="checkbox" value="<? echo $s?>" <? if($s==1) {?>checked="checked"<? }?> onchange='this.value=this.value==1?0:1;ch_param("mont","<? echo $param?>s="+this.value,"dmont")' />
на отключение должников
<div id="dmont">
<?
}
if($tp<=4) {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$q_noti = "SELECT m_TabNum, RegionName, Sum('1') AS coun from v_notify where $q_DP GROUP BY m_TabNum";
	$result = mysql_query($q_noti) or die(mysql_error());?>
	<table border="0" cellpadding="2" cellspacing="1" >
		<tr>
	<? if (!isset($_REQUEST ["m_TabNum"])) { ?>
			<td align="center" colspan="2"><h3>
				<? if (!isset($result)) { ?>
					Неисполненных заявок нет</h3></td></tr> <? return;
				} ?>
				Заявки к исполнению</h3>
			</td>
		</tr>
        <tr><td>Регион (заявок)</td></tr>
			<? 	while ($row = mysql_fetch_assoc($result))  {	?>
                <tr>
                    <td bgcolor="#E5E5E5"><b>
					<a href='javascript:{ch_param("mont","<? echo $param?>m_TabNum=<? echo $row["m_TabNum"]?>","d_TN<? echo $row["m_TabNum"]?>");}'> <? echo $row["RegionName"]?> </a>&nbsp;</b>(<? echo $row["coun"];?>)
                    <div id="d_TN<? echo $row["m_TabNum"]?>"></div></td>
                </tr>
			<?    }	?>
         </table>
	<?   }  
////////////////////////////////////////////////////////////////////////////////////////////////
}
$m_TabNum = (isset($_REQUEST ["m_TabNum"]))? $_REQUEST ["m_TabNum"]:"";
if ($m_TabNum=="") return;
$q_TN = "m_TabNum = $m_TabNum";

$q = "SELECT name_street,Sum(1) AS coun from v_notify WHERE $q_DP AND $q_TN GROUP BY name_street";
$result = mysql_query($q) or die(mysql_error());

if (!isset($_REQUEST ["name_street"])) { ?>
	<table border="0">
		<? 	while ($row = mysql_fetch_assoc($result))  {	?>
            <tr><td width="10"></td>
                <td bgcolor="#E5E5E5"><b>
                <a href='javascript:{ch_param("mont","<? echo $param."m_TabNum=$m_TabNum&name_street=".$row["name_street"]?>","d_s<? echo $row["name_street"]?>");}'> <? echo $row["name_street"]?> </a>&nbsp;</b>(<? echo $row["coun"];?>)<div style="border:thin #009 solid" id="d_s<? echo $row["name_street"]?>"></div></td>
            </tr>
        <?    }	?>
    </table>
	<?
}
$name_street = (isset($_REQUEST ["name_street"]))? $_REQUEST ["name_street"]:"";
if ($name_street=="") return;

$q_str = "name_street = '".$name_street."'";

$q = "SELECT Num_build,Sum(1) AS coun from v_notify WHERE $q_DP AND $q_TN and $q_str GROUP BY Num_build";
$result = mysql_query($q) or die(mysql_error());

if (!isset($_REQUEST ["Num_build"])) { ?>
	<table border="0">
	<!--	<tr><td>Дом</td><td>заявок</td></tr>-->
            <tr>
		<? 	while ($row = mysql_fetch_assoc($result))  {	?>
            <tr><td width="10"></td>
                <td bgcolor="#E5E5E5"><b>
                <a href='javascript:{ch_param("mont","<? echo $param?>m_TabNum=<? echo $m_TabNum?>&name_street=<? echo $name_street?>&Num_build=<? echo $row["Num_build"]?>","d_b<? echo $name_street.$row["Num_build"]?>");}'> <? echo $row["Num_build"]?> </a>&nbsp;</b>(<? echo $row["coun"];?>)<div id="d_b<? echo $name_street.$row["Num_build"]?>"></div></td>
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
//	$q_noti = "SELECT * from v_notify where `Date_Fact` IS NULL and DATE_FORMAT(`Date_Plan`,'%Y-%m-%d')<='$Date_Plan' ".($tp<4?"":" and m_TabNum=".$tn);
 $q_noti = "SELECT RegionName,Num_Notify,Bill_Dog,Date_in,Korpus,Podjezd,`floor`,flat,Fam,Name,Father,phone_Cell,phone_Home,phone_Work,phone_Dop,Notify,Date_Plan,`comment`,Date_Fact, conn, mac, auto, state
FROM v_notify 
WHERE $q_DP AND $q_TN and $q_str AND $q_bld
ORDER by space(5-length(flat))+flat";//
$result = mysql_query($q_noti);		// Выполняем запрос
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
	<? if($tp<4) {?><!--<td align="center" ><strong>№ Дог.</strong></td>--><? }?>
	<td align="center" ><strong>Дата<br /> подачи</strong></td>
	<td align="center" ><strong><!--Улица дом.-->кв.<br />под.,эт.</strong></td>
	<td align="center" ><strong><? if($tp<4) {?>№ Дог.<? }?> Ф.И.О.</strong></td>
	<td align="center" ><strong>Конт. телефоны</strong></td>
	<td align="center" ><strong>Заявка</strong></td>
	<td align="center" ><strong>Дата<br /> план.</strong></td>
	<td align="center" ><strong>Коммент.</strong></td>
	<td align="center" ><strong>Дата<br /> исполн.</strong></td>
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
<?		$bgc = ($i++ % 2) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];		$NNoti = $row["Num_Notify"];
		$h_bgc = "align='center' bgcolor='$bgc'";
 		if($tp<4) { /*$NNoti;*/?><!-- <td <? echo $h_bgc?>></td>--><? }?>
		<td align="center" bgcolor="<?php echo $bgc; ?>"> 
				<?php $d_in = strtotime($row["Date_in"]); echo date("j ", $d_in).$m[date("n", $d_in)].date(" Y", $d_in)
		// date("d-m-Y", strtotime($row["Date_in"]));							?> </td>
		<td bgcolor="<?php echo $bgc; ?>" colspan="1">
			<?php echo ($row["Korpus"]>0?"К.".$row["Korpus"]:"").
			"</b> кв.<b><font size=+1>".$row["flat"]."</font></b>"." (пд.<b>".$row["Podjezd"].($row["floor"]>0?$row["floor"]:"")."</b>)";?> </td>
		<td bgcolor="<?php echo $bgc; ?>"> <? if($tp<4){ ?><a href="javascript:{ch_param('sh_form','<? echo "menu=pay&tn=$tn&tp=$tp&Bill_Dog={$row['Bill_Dog']}','Mform');s_Bill_Dog();}"?>"><? echo $row["Bill_Dog"]?></a>&nbsp;
		<?php echo $row["Fam"].'<br>'; } 
 			echo $row["Name"].' '.$row["Father"]; 	?>
        </td>
		<td bgcolor="<?php echo $bgc; ?>"> <?php 
			echo ($row["phone_Dop"]>0?"тел.".$row["phone_Dop"]."<br>":"").
				 ($row["phone_Home"]>0?"д.".$row["phone_Home"]."<br>":"").
				 ($row["phone_Cell"]>0?     $row["phone_Cell"]."<br>":"").
				 ($row["phone_Work"]>0?"р.".$row["phone_Work"]."<br>":"") ?></td>
		<td width="150" bgcolor="<?php echo $bgc; ?>"> <b><?php echo $row["Notify"]; ?></b>
        <div id="d<? echo $row["Bill_Dog"] ?>">
		<?php //if (!$m_pay) {?><? 
		$mac = isset($row['mac'])?$row['mac']:"";//isset($a_cus[$Bill_Dog])?$a_cus[$Bill_Dog]['mac']:"";
		$mac = $mac==""?"":substr($mac, 0, 2)."-".substr($mac, 2, 2)."-".substr($mac, 4, 2)."-".
			   substr($mac, 6, 2)."-".substr($mac, 8, 2)."-".substr($mac, 10, 2);
		//}?>
        MAC<input id="mac<? echo $row["Bill_Dog"] ?>" value="<? echo $mac; ?>" type="text" size="15" onchange='cor_mac(<? echo $row["Bill_Dog"] ?>);' maxlength="17" onkeyup="v_MAC(this.value, '<? echo $row["Bill_Dog"] ?>');" />
        </div>
		<td align="center" bgcolor="<?php echo $bgc; ?>"> <?php $d_pln = strtotime($row["Date_Plan"]);	echo date("j ", $d_pln).$m[date("n", $d_pln)].date(" Y", $d_pln) ?> </td>
		<td bgcolor="<?php echo $bgc; ?>"><input id="c<?php echo $NNoti?>" size="9"
        	onchange='ch_param("do_com_noti","com="+document.getElementById("c<? echo $NNoti?>").value+"&Notify=<? echo $NNoti?>","d<? echo $NNoti; ?>");'
            value="<? echo $row["comment"]; ?>"/> </td>
		<td bgcolor="<?php echo $bgc; ?>"><input name="d_fact<?php echo $NNoti?>" type="date" size="8"
onchange='ch_param("do_cls_noti","com="+document.getElementById("c<? echo $NNoti?>").value+"<? echo "&conn=".$row["conn"]."&Notify=$NNoti&tn=$tn&Bill_Dog=".$row["Bill_Dog"]."&Date_Fact="?>"+this.value,"d<? echo $NNoti?>"); this.disabled="disabled";document.getElementById("c<? echo $NNoti?>").disabled="disabled";'
<? /*        	onchange='if (confirm("Заявка выполнена "+this.value+"?") {ch_param("do_cls_noti","com="+document.getElementById("c<? echo $NNoti?>").value+"<? echo "&conn=".$row["conn"]."&Notify=$NNoti&tn=$tn&Bill_Dog=".$row["Bill_Dog"]."&Date_Fact="?>"+this.value,"d<? echo $NNoti?>"); this.disabled="disabled";document.getElementById("c<? echo $NNoti?>").disabled="disabled";}'
*/ ?>																																																																																				 /> </td>
		<td bgcolor="<?php echo $bgc; ?>"><div id="d<? echo $NNoti; ?>">
	<? if ($tp<4 && $row["conn"]=='' && $row["Date_in"]==date("Y-m-d"/*, mktime(0,0,0,date("m"),date("d"),date("Y"))*/) ){ ?>
			<button name="B_canc" type=button onClick="alert('<?php echo $NNoti?>');"><img src="BD14755_.gif" align=middle alt="Отмени"></button>
	<? } ?>
			</div>
		</td>
	</tr>		<?php 
		}
} /*else {
?>Нет заявок<? }*/

//*******************************************************************************************************************************
?>
<?php /*?>
<table width="800" border=0>
  <tr>
	<td width="102" align="right">монтажник: </td>
		<td width="638">
			<select name="mont" class='font8pt' id="mont" lang="ru"
				onchange='alert("!!!"); document.getElementById("Mform").innerHTML = ""; ch_param("mont", "mont=<? echo $_REQUEST ["mont"]; ?>, "Mform");'>
	<?php	$q_mont = "SELECT * FROM `personal` WHERE `id_TypePers`=4";
			$mont = mysql_query($q_mont) or die(mysql_error());
			$row_mont = mysql_fetch_assoc($mont);
			$totalRows_mont = mysql_num_rows($mont);
		echo "<option value=0>выбрать</option>";
	do {
		echo "<option value=".$row_mont['TabNum'];
		if (isset($_REQUEST ["mont"]) && ($row_mont["mont"]==$_REQUEST["mont"])) { echo " selected"; }
		echo ">".$row_mont['Fam']." (таб.№ ".$row_mont['TabNum'].")</option>";
			} while ($row_mont = mysql_fetch_assoc($mont));
			$rows = mysql_num_rows($mont);
			if($rows > 0) { mysql_data_seek($mont, 0); $row_mont = mysqli_fetch_assoc($mont);  } ?>
		</select>
    </td>
  </tr>
</table>
<?php */?>
<div id="B_Sub" align="centr">  </div>
<input name="Menu_Item" type="hidden" value="mont" />
<p>&nbsp;</p>
</div>