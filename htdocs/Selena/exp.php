<meta charset="utf-8"><?php 
/*
<link href="selena.css" rel="stylesheet" type="text/css" />

require_once("for_form.php"); 
do_html_header("");
check_valid_user();
$conn = db_connect();
if (!$conn) return 0;
*/
	require_once("db_fns.php");
	$conn = db_connect();

	$m = date("m")-1;
	$m = ($m<10?"0":"").($m==0?12:$m);
	$y = date("y") - ($m==12?1:0);
//global 
	$filename = "/srv/exp/sel_".$y.$m.".csv";
//	$filename = "/srv/exp/sel{$name}_".$y.$m.".csv";
			if (!$handle = fopen($filename, 'w')) {
				 echo "Cannot open file ($filename)";	exit;
			}
			$somecontent = '"абон/инет";"Дата время";"account";"txn_id";"prv_txn";"sum";"результат";"prv_id";"ошибка"'.chr(13);
			if (fwrite($handle, $somecontent) === FALSE) {
				echo "Cannot write head to file ($filename)";	exit;
			}
			
do_exp("abon", $filename, $handle);
do_exp("inet", $filename, $handle);
			
			echo "Success, wrote otchet to file ($filename)", "<br>";
			fclose($handle);

function do_exp($name, $filename ){
echo $name,$filename,"<br>";	
	$q_noti = "SELECT d_time,account,txn_id,prv_txn,sum,result,prv_id,er_descr
		FROM v_t_{$name} 
		WHERE d_time>=DATE_ADD(DATE_ADD(LAST_DAY(DATE_ADD(CURDATE(),INTERVAL -1 MONTH)),INTERVAL '1 4' DAY_HOUR),INTERVAL -1 MONTH)
				AND d_time < DATE_ADD(LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -1 MONTH)), INTERVAL '1 4' DAY_HOUR)
			order by DATE_FORMAT(d_time, '%y%m%d%T')";	//	YEAR(d_time)+MONTH(d_time)+DAY(d_time)+TIME(d_time)
	$result = mysql_query($q_noti) or die(mysql_error());		// Выполняем запрос
	if (mysql_num_rows($result)>0) { 
		//if (is_file ($filename)) {} //	if (is_writable($filename)) {
		
			while ($row = mysql_fetch_assoc($result))  {
				$somecontent = $name.";".r_($row,"d_time").r_($row,"account").r_($row,"txn_id").r_($row,"prv_txn").str_replace(".",",", r_($row,"sum")).r_($row,"result").r_($row,"prv_id").r_($row,"er_descr").chr(13);
				if (fwrite($handle, $somecontent) === FALSE) {
					echo "Cannot write '{$somecontent}' to file ($filename)";	exit;
				}
			}
	//	} else {
		//	echo "Файл $filename не доступен для записи";
		//}
	}
}
//-----------------------------------------------------------------------------------------------

function r_($row, $fld){
	return '"'.$row[$fld].'";';
}
//----------------------
function wr_file($handle, $somecontent){
	if (fwrite($handle, $somecontent) === FALSE) {
		echo "Невозможно записать в файл"; // ($filename)
		exit;
	}
}
?>