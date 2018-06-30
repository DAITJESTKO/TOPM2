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
	echo "Создание записей в архиве: абонентов ...";
	$Bill_Dog = $_REQUEST ["Bill_Dog"];
	$q_ = "select * from `customers_arc` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());
	if (mysql_num_rows($s_)>0) {
		echo "в архиве строка заменена</br>";
		$q_ = "delete from `customers_arc` where Bill_Dog=$Bill_Dog";
		$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());
	}	
	$q_ = "insert into `customers_arc` select * from `customers` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	echo "операций ...";
	$q_ = "insert into `actions_arc` select * from `act".date("Y")."` where Bill_Dog=$Bill_Dog".
						" union select * from `act".(date("Y")-1)."` where Bill_Dog=$Bill_Dog".
						" union select * from `act".(date("Y")-2)."` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	echo "заявок ...";
	$q_ = "insert into `notify_repair_arc` select * from `notify_repair` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	echo "готово.</br>Метка удалившего ... ";
//$now		= date("Y-m-d H:i:s");	//
	$q_upd_cust = "update `customers_arc` set `TabNum`=".$_REQUEST ["tn"].",DateKor=NOW() where Bill_Dog=$Bill_Dog";
	$s_upd_cust =  mysql_query($q_upd_cust) or die($q_." => ".mysql_error());	
	echo "установлена.</br>Удаление записей из таблиц: абонентов ... ";
	$q_ = "delete from `customers` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	echo "операций ... ";
	$q_ = "delete from `actions` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	$q_ = "delete from `act".date("Y")."` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	$q_ = "delete from `act".(date("Y")-1)."` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	$q_ = "delete from `act".(date("Y")-2)."` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	echo "заявок ... ";
	$q_ = "delete from `notify_repair` where Bill_Dog=$Bill_Dog";
	$s_ =  mysql_query($q_) or die($q_." => ".mysql_error());	
	echo "готово.</br>";

//***********************************
echo '<button type=button onClick="f=document.forms.ulaForm;f.sBill_Dog.value = '.$Bill_Dog.'; setTimeout(&quot;f.sBill_Dog.onchange();&quot;, 300);">'.
		'<img src="reload.png" align=middle alt="Обнови"></button>';
?>