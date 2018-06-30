<? require_once("for_form.php"); 
check_valid_user();
  $conn = db_connect();
  if (!$conn) return 0;

$tn = isset($_REQUEST ["tn"])?$_REQUEST ["tn"]:'';//' and TabNum='.
$tp = isset($_REQUEST ["tp"])?$_REQUEST ["tp"]:'';
$Bill_Dog  = $_REQUEST ["Bill_Dog"];
$ab_s  = $_REQUEST ["ab_s"];
$ab_n  = $_REQUEST ["ab_n"];
$_Y = date("Y");
$iY = isset($_REQUEST ["iY"])?$_REQUEST ["iY"]:$_Y; ?>
<table width="800" border="1" cellpadding="2" cellspacing="1" >
<?
$cfg['BgcolorOne'] = "#E5E5E5"; $cfg['BgcolorTwo'] = "#D5D5D5";	$bgcolor = $cfg['BgcolorOne'];
if (!isset($_REQUEST ["iY"])) { ?>
    <tr align="center">
        <td width="25" bgcolor="<?php echo $bgcolor; ?>"> №</br>п/п</td>
        <td width="55" bgcolor="<?php echo $bgcolor; ?>">оператор</td>
        <td width="50" bgcolor="<?php echo $bgcolor; ?>">дата<br />опер</td>
        <td width="140" bgcolor="<?php echo $bgcolor; ?>">период</td>
        <td width="35" bgcolor="<?php echo $bgcolor; ?>">сумма</td>
        <td width="75" bgcolor="<?php echo $bgcolor; ?>">операция</td>
        <td width="250" bgcolor="<?php echo $bgcolor; ?>">коммент</td>
        <td bgcolor="<?php echo $bgcolor; ?>"></td>
    </tr>
<?
}
?>	<tr><td colspan=8 bgcolor="<?php echo $cfg['BgcolorTwo']; ?>"><b><? echo $iY?></td></tr> <?
//echo "select * from v_act{$iY} where `Bill_Dog`=$Bill_Dog";
$result = mysql_query("select * from v_act{$iY} where `Bill_Dog`=$Bill_Dog ORDER BY `Date_start` DESC");// and DATE_FORMAT(InputDate,'%Y') = '".$iY."' ORDER BY InputDate DESC, `Date_start` DESC v_actions.Date_start DESC,
// AND id_ActionType <>2 AND (canc is null or canc <>1) ORDER BY `Date_start` Выполняем запрос кроме платежей за интернет(id_ActionType<>2)
if (($result)&&($num_res = mysql_num_rows($result))>0) {
	$otp = 0;
//!	$qMax = mysql_query("select max(summa) as maxs,max(InputDate) as maxd from v_actions where date(InputDate)>=date('2010-10-17') and `Bill_Dog`=$Bill_Dog AND id_ActionType <>2 AND (canc is null or canc <>1) ORDER BY `Date_start`");
	$rMax = $ab_s;//! mysql_fetch_assoc($qMax);	/* GLOBALS['']   $arr_cust[$Bill_Dog]['ab_sum'] */
	
	//$m_ab = isset($GLOBALS['ab_numbs'])?(round($ab_s/2*(1+1/($GLOBALS['ab_numbs']>0?$GLOBALS['ab_numbs']:1)))):'';	
	$m_ab = isset($ab_n)?(round($ab_s/2*(1+1/($ab_n>0?$ab_n:1)))):'';
	$MaxS = $m_ab;// $resMax["maxs"];//totalRows_customer

	$nbo = 1;	$i = 0;
//	$num_res = mysql_num_rows($result);
	// Печатаем данные построчно
	//	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
	$m = Array(1=>"янв",2=>"фев",3=>"мар",4=>"апр",5=>"мая",6=>"июн",7=>"июл",8=>"авг",9=>"сент",10=>"окт",11=>"ноя",12=>"дек");
	$_Y2 = "";	$o_Y = ""; ?>
	<tr><td colspan=8 align="center"><div width='800'>
    <table width="800"><?
	while ($row = mysql_fetch_assoc($result))  { 
		$bgcolor = $row["err"]==0?(($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo']):"#FFCC11";
		$D_st = strtotime($row["Date_start"]);
		$D_en = strtotime($row["Date_end"]); 
		$D_inp = strtotime($row["InputDate"]); 
		$o_Y2 = date("Y", $D_inp);//$D_st		<td></td><td></td>none
		$Y_ = $i==1?$o_Y2:'<input name="B_'.$o_Y2.'" type="button" onclick="toggle(\''.$o_Y2.'\')" value="'.$o_Y2.'" />';
		if($o_Y!=$o_Y2) { $o_Y=$o_Y2; 
		//	echo "</table></div></td></tr><tr><td colspan=8 align='center'><b>$Y_ год</b><div id='".$o_Y2."'".
		//	($_Y!=$o_Y2?" style='display:".($i==1?'':'none')."'><table width='800' border='0' cellpadding='2' cellspacing='1'>":"></td></tr>"); 
		}?>
    <tr>
        <td width="25" align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $nbo++; ?> </td>
        <td width="55" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["p_login"]		?> </td>
        <td width="50" bgcolor="<?php echo $bgcolor; ?>"> <?php 
            echo date("j ", $D_inp).$m[date("n", $D_inp)];	?> </td>
        <td width="140" align="center" bgcolor="<?php echo $bgcolor; ?>">
            <? if(!in_array($row["id_ActionType"],array(2,7,8,9,10))) {// не интернет операция
                ?>с <?php $m_s = $m[date("n", $D_st)];
                $m_e = $m[date("n", $D_en)];
                echo ($d_s = date("j", $D_st)).($m_s!=$m_e?"-".$m_s:"");
                ?> по <? $d_end=date("j-", $D_en).$m_e.
                    ($o_Y2 == date("Y", $D_en)?"":"-".date("yг", $D_en));
            //	echo $MaxS, " ", $rMax["maxs"], " ", $rMax["maxd"], " !";
            //	echo "!",date("Y-m-d",$D_inp),"!", date("Y-m-d",mktime(0,0,0,10,18, 2010));
            if ($i==1/*$num_res*/ && /*$MaxS*/$rMax["maxs"]>$row["Summa"] && $row["Summa"]!=0 && 
                    date("t",$D_en)==date("d",$D_en) && date("Y-m-d",$D_inp)<=date("Y-m-d",mktime(0,0,0,10,18, 2010))){	
                 echo $row["Date_end"];
                //	 echo date("Y",strtotime($D_en))," ",date("m",strtotime($D_en))," ",round($row["Summa"]/$MaxS*date("t",strtotime($D_en)),0);
                $D_en2 = date("Y-m-d",mktime(0,0,0,date("m",$D_en),round($row["Summa"]/ /*$MaxS*/ $rMax["maxs"]*date("t",$D_en),0), date("Y",$D_en)));	?>
                <div id='d_end'><a href='javascript:set_d_end("<? echo $Bill_Dog."\",\"".$row["Date_start"]."\",\"".$D_en2 ?>")'>измени</a></div>
            <? } else { echo $d_end; }	
            }  ?> </td>
        <td width="40" align="right" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Summa"];	?> </td>
        <td width="80" bgcolor="<?php echo $bgcolor; ?>" <? echo $row["canc"]>0?'font style=" text-decoration:line-through"':'' ?>><?php echo $row["ActionName"] ?></td>
        <td width="250" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Comment"], $row["TN_canc"]>0?('<i><br>/'.($row["canc"]>0?'от':'из').'менил '.$row["Date_ed"].' '.$row["login_ed"].'/'):'';	?> </td>
        <td bgcolor="<?php echo $bgcolor; ?>"> <?php //echo date("Y-m-d", $D_st);
            if( date("Y-m-d",$D_en)<=date("Y-m-d",mktime(0,0,0,1,1, 1980)) && ($row["id_ActionType"]==1) && $d_s!=1 && ($tp==1 || $tn==6 || $tn==8) ){
				// плохой платёж за абон, через терминал && ($row["TabNum"]==11
                $s_div = ereg_replace(':','-',ereg_replace(' ','_',$row["InputDate"]));
             ?>   <div id="t_er<? echo $s_div;?>">
          			<input type="button" onclick='ch_param("t_er_ab","<? echo "di={$row['InputDate']}&ds={$row['Date_start']}&de={$row['Date_end']}"?>","t_er<? echo $s_div;?>");' value="повтор" />
				  </div><?
            }
            if(!in_array($row["id_ActionType"],array(2,4,7,8,9,10)) && $d_s!=1 && ($tp==1 || $tn==6 || $tn==8) && date("Y-m-d",$D_inp)<=date("Y-m-d",mktime(0,0,0,5,1, 2011)) ){
				// не интернет и не отпуск
                $s_div = ereg_replace(':','-',ereg_replace(' ','_',$row["InputDate"]));
                echo '<div id="'.$s_div.'">'.//$s_div.
                    '<input name="B_btn" type="button" onclick="set_1(\''.$s_div.'\',\''.$row["InputDate"].'\',\''.$row["Date_start"].'\',\''.$row["Date_end"].'\');" value="1" /></div>';
            }
            if ($row["id_ActionType"]==4 /* && $row["TN_canc"]=='' заморозить */ ) { ?>
                <div id="f<? echo $row["Date_start"]?>">
            <?	if (strtotime(date("Y-m-d")) < $D_st ) { ?>
                    <input name="B_btn" type="button" 
                        onclick="if(confirm('Вы уверены, нужно ОТМЕНИТЬ отключение?')){del_otp(<? 
                                    echo $Bill_Dog.",'".$row["Date_start"]."','".$row["Date_end"]."'" ?>);}" 
                        value="отмени" />
            <?	} else { 
                    if ( $otp == 0 /*strtotime(date("Y-m-d")) < $D_en*/ ) { //echo $D_en;
						$otp = 1;
                        $d_canc=($D_st>strtotime(date("Y-m-d")))?$row["Date_start"]:date("Y-m-d");
                        
                        $q_quer = mysql_query("select Date_end_st as des, Date_pay as dp from customers where Bill_Dog=$Bill_Dog")
                                        or die(mysql_error());
                        $r_ = mysql_fetch_array($q_quer, MYSQL_ASSOC);
                        //$Date_pay = date("Y-m-d", strtotime(date($r_["dp"])) - strtotime($r_["des"]) + strtotime($d_canc));
						$Date_pay = date("Y-m-d", strtotime(date($r_["dp"])) - $D_en + strtotime($d_canc));
						//date("Y-m-d",strtotime($d_canc))
                   ?>   до <input name="d_canc" id="d_canc" value="<? echo $row["Date_end"] ?>" type="date" size="10" 
                          	onChange="d_s=new Date('<? echo $row["Date_start"]?>');
                            		  d_e=new Date('<? echo $row["Date_end"]?>');
                                      d_p=new Date('<? echo $r_["dp"]?>');
                                      d_t=new Date(this.value); document.forms.ulaForm.d_canc.value=this.value;
                                      d_np = date_add(d_p,'day',date_difference(d_e,d_t,'day'));
                                      if(d_s<d_t){
                                    	if(confirm('Вы уверены, что нужно изменить окончание отключения на '+
                                    		d2str2(d_t)+'. Оплаченная дата будет установлена на'+
                                ' - '+d2str2(d_np)+' <? //echo $Date_pay	document.forms.ulaForm.d_canc.value
									//.' = '.$r_["Date_pay"].' - '.$r_["Date_end_st"].' + '.$d_canc ?>')){
                            canc_otp(this.value,<? echo "$Bill_Dog,'".$row["Date_start"]."','".$row["Date_end"]."'" ?>);}}" /> 
                        <!--<input name="B_btn" type="button" onclick="" value="измени" />-->
<?					 	}
                } ?>
                </div>
<?				} ?>
        </td>
<?php /*?>		<td bgcolor="<?php echo $bgcolor; ?>"><?php echo ($row["id_ActionType"]==4 and $row["Date_end"]>date("Y-m-d"))?'<input name="B_btn" type="button" onclick="canc_frz($Bill_Dog,'.date("d-m-Y", strtotime("+1 day")).');" value="отмени" /> с '.date("d-m-Y", strtotime("+1 day")):"";	?> </td><?php */?>
    </tr>		<?php
    }
	?><!--</div></td></tr>-->
<? } else {	?>
    <tr><td colspan=8><? echo "Платежей небыло"; ?></td></tr>
<?
} ?>
</table> 
<tr><td colspan=8><div id="<? echo $iY-1?>">
    <input type="button" onclick="ch_param('sh_pays', '<? echo "tn={$tn}&Bill_Dog={$Bill_Dog}&ab_s={$ab_s}&ab_n={$ab_n}&iY=",$iY-1,"', '",$iY-1?>');" value="<? echo $iY-1?>" />
</div></td></tr>
