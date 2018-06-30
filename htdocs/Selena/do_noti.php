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

	$id_p  = $_REQUEST ["id_p"];
	$fl  = $_REQUEST ["fl"];
	$Bill_Dog  = $_REQUEST ["Bill_Dog"];
	$Cod_flat = $_REQUEST ["Cod_flat"];	//get_param("Cod_flat");
	$Notify = $_REQUEST ["Notify"];	//get_param("Notify");
	$Date_Plan = $_REQUEST ["Date_Plan"];	//get_param("Date_Plan");
//	$Date_Fact = $_REQUEST ["Date_Fact"];	//get_param("Date_Fact");
//	$Date_in = $_REQUEST ["Date_in"];	//get_param("Date_in");
//	$mont  = $_REQUEST ["mont"];
	$phone_Dop  = $_REQUEST ["phone_Dop"];
/*
if (isset($_REQUEST ["Cod_flat"])) {
	$q_cor_Cod_Flat = "update `customers` set Cod_flat=".$Cod_flat." where ".$id_p." and flat=".$fl;
	echo $q_cor_Cod_Flat."</br>";
	$s_cor_Cod_Flat =  mysql_query($q_cor_Cod_Flat) or die(mysql_error());
	echo "Адресу присвоен код $Cod_flat <br>";
} else {
	$q_cor_Cod_Flat = "select Cod_flat from `customers` where ".$id_p." and flat=".$fl;
	echo $q_cor_Cod_Flat."</br>";
	$s_cor_Cod_Flat =  mysql_query($q_cor_Cod_Flat) or die(mysql_error());
	$row_Cod_Flat = mysql_fetch_array($s_cor_Cod_Flat, MYSQL_ASSOC);
	$Cod_Flat = " Cod_flat=".$row_Cod_Flat["Cod_flat"];	//.", "
}
*/
	$s_col = "Cod_flat, Bill_Dog,";								$v_col = "$Cod_flat, $Bill_Dog,";
//	if (strlen($)>0) {$s_col = $s_col." ,";						$v_col = $v_col." '$',";}
	if (strlen($Notify)>0) {$s_col = $s_col." Notify,";			$v_col = $v_col." '$Notify',";}
	if (strlen($Date_Plan)>0) {$s_col = $s_col." Date_Plan,";	$v_col = $v_col." '$Date_Plan',";}
							$s_col = $s_col." Date_in,"; 		$v_col = $v_col." '".date("Y-m-d H:i:s")."',";
	if (strlen($TabNum)>0) {$s_col = $s_col." TabNum,"; 		$v_col = $v_col." '$TabNum',";}
	if (strlen($phone_Dop)>0) {$s_col = $s_col." phone_Dop,";	$v_col = $v_col." '$phone_Dop',";}

	$s_col = $s_col. " id_p, fl";								$v_col = $v_col. " $id_p, $fl";

	$q_ins_noti = "insert into `notify_repair` (".$s_col.") values (".$v_col.")";
//	echo $q_ins_noti."</br>";
	$s_ins_noti =  mysql_query($q_ins_noti) or die(mysql_error());
	echo "Внесена заявка на ремонт сети:<br> $Notify <br> Плановая дата исполнения:$Date_Plan";

function get_param ($name)
{
	$$name = $_REQUEST [$name];
	return (($$name=="")? " " : " ".$name."='".$$name."',");
}
?>