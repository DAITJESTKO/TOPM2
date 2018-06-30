<? require_once("for_form.php"); 
check_valid_user();
  $conn = db_connect();
  if (!$conn) return 0;
    // вот это нужно что бы браузер не кешировал страницу...
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");	

$tn = isset($_REQUEST ["tn"])?' and TabNum='.$_REQUEST ["tn"]:'';
$Bill_Dog  = $_REQUEST ["Bill_Dog"]; //notify_repair
$result = mysql_query("select * from v_notify where `Bill_Dog`=$Bill_Dog ORDER BY `Date_Plan` desc, Date_in desc");// кроме платежей за интернет(id_ActionType<>2)
if ($result) {
	$nbo = 1;	$i = 0;
	$cfg['BgcolorOne'] = "#CCCCCC";//E5E5E5
	$cfg['BgcolorTwo'] = "#D5D5D5";
	$bgcolor = $cfg['BgcolorOne'];
	$num_res = mysql_num_rows($result);
	?>
	<table width="800" border="0" cellpadding="2" cellspacing="1" >
	<tr>
		<td width="60" bgcolor="<?php echo $bgcolor; ?>"> №</br>п/п</td>
		<td width="133" bgcolor="<?php echo $bgcolor; ?>">дата<br />создания</td>
		<td width="83" bgcolor="<?php echo $bgcolor; ?>">диспетчер</td>
		<td width="98" bgcolor="<?php echo $bgcolor; ?>">Заявка</td>
		<td width="101" bgcolor="<?php echo $bgcolor; ?>">плановая<br />дата исп.</td>
		<td width="132" bgcolor="<?php echo $bgcolor; ?>">фактическая<br />дата исп.</td>
		<td width="108" bgcolor="<?php echo $bgcolor; ?>">кто</td>
	</tr>
	
	<?php
	// Печатаем данные построчно
//	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
		while ($row = mysql_fetch_assoc($result))  { ?>
			<? $bgcolor = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];		$NNoti = $row["Num_Notify"];			?>
		<tr>
			<td align="center" bgcolor="<?php echo $bgcolor; ?>"><div id="<? echo $NNoti?>"> <?php echo $nbo++;
		    if ($_REQUEST ["tp"]==1 || $_REQUEST ["tn"]==6) {	?>
				 <button name="B_del_<? echo $NNoti?>" type=button 
            	onClick="javascript:if(confirm(&quot;Вы согласны удалить заявку на <? echo $row["Notify"]." ".$row["Date_Plan"]?> ? &quot;)){ch_param('del_noti','N=<? echo $NNoti?>','<? echo $NNoti?>');}"><img src="ico_delete.gif" align=middle alt="Удалить"></button>
		<?	}		?></div>
	 </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Date_in"];		?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["disp"];		?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>" <? echo $row["canc"]>0?'font style=" text-decoration:line-through"':''?>> <?php echo $row["Notify"];	?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>" <? echo $row["canc"]>0?'font style=" text-decoration:line-through"':''?>> <?php echo $row["Date_Plan"];	?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <? if ($row["Date_Fact"]=='' && $row["TN_canc"]=='' && strtotime($row["Date_Plan"]) <= mktime()) { ?>
	            <input name="d_fact<?php echo $NNoti?>" type="date" size="8"
onchange='ch_param("do_cls_noti","com=<? echo "&Notify=$NNoti&tn={$_REQUEST['tn']}&Date_Fact="?>"+this.value,"d<? echo $NNoti?>"); this.disabled="disabled";' />
				<div id="d<? echo $NNoti; ?>">
                <? } ?>
			<?php echo $row["Date_Fact"]>0?$row["Date_Fact"]:($row["TN_canc"]>0?('<i>  /'.($row["canc"]>0?'от':'из').'менил '.$row["Date_ed"].' /'):'');	?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["Date_Fact"]>0?$row["m_login"]:($row["TN_canc"]>0?'<i> / '.$row["c_login"].' /</i>':'');	?> </td>
		</tr>		<?php 
		}
	?></table>
<? } else {
	echo "Заявок нет";
} //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>