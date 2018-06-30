<?
require_once("for_form.php"); 
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
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? 
//if () {
	$tn = isset($_REQUEST ["tn"])?' and TabNum='.$_REQUEST ["tn"]:'';
//}
$Bill_Dog  = $_REQUEST ["Bill_Dog"];
$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$result = mysql_query("select * from v_hist_cod where `Bill_Dog`=$Bill_Dog");		// Выполняем запрос кроме платежей за интернет(id_ActionType<>2)
if ($result) {
	$nbo = 1;	$i = 0;
	$cfg['BgcolorOne'] = "#CCCCCC";//E5E5E5
	$cfg['BgcolorTwo'] = "#D5D5D5";
	$bgcolor = $cfg['BgcolorOne'];
	$num_res = mysql_num_rows($result);
	?>
	<table width="700" border="0" cellpadding="2" cellspacing="1" >
	<tr>
		<td bgcolor="<?php echo $bgcolor; ?>"> №</br>п/п</td>
		<td bgcolor="<?php echo $bgcolor; ?>">дисп.</td>
		<td bgcolor="<?php echo $bgcolor; ?>">дата<br />смены адреса</td>
		<td bgcolor="<?php echo $bgcolor; ?>">код нового адреса</td>
		<td bgcolor="<?php echo $bgcolor; ?>">Новый адрес</td>
		<td bgcolor="<?php echo $bgcolor; ?>">регион</td>
	</tr>
	
	<?php
	// Печатаем данные построчно
//	$m = Array(1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря");
		while ($row = mysql_fetch_assoc($result))  { ?>
			<? $bgcolor = ($i++ % 3) ? $cfg['BgcolorOne'] : $cfg['BgcolorTwo'];			?>
		<tr>
			<td align="center" bgcolor="<?php echo $bgcolor; ?>"> <?php echo $nbo++;	?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["TabNum"];		?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php if ($row["ch_date"]!='0000-00-00 00:00:00'){
								echo date("j ",strtotime($row["ch_date"])).$m[date("n",strtotime($row["ch_date"]))]." ".date("Y",strtotime($row["ch_date"]));}?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["new_cod"];		?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo "ул.".$row["name_street"]." д.".$row["Num_build"]." ".$row["Korpus"]." кв.".$row["flat"]		?> </td>
			<td bgcolor="<?php echo $bgcolor; ?>"> <?php echo $row["RegionName"];		?> </td>
	</tr>		<?php 
		}
	?></table>
<? } else {
	echo "Смены адреса пока небыло";
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>