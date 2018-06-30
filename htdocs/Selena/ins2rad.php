<?
$ip = getenv("REMOTE_ADDR");
$ok='1';
$deb = 0;

if ($ip=='10.1.90.26'){$ok ='1'; $deb = 1; }
if ($ip=='10.1.253.6'){$ok ='1'; $deb = 1; }
if ($ip=='10.1.253.22'){$ok ='1'; $deb = 1; }
if ($ip=='127.0.0.1'){$ok ='1'; $deb = 1; }
//$deb = 0;

	fdebug("IP - Ok!<br>");
$link = mysql_connect("localhost", "root", "455029") or die("Невозможно соединиться (cud not to connect): " . mysql_error());
require_once("do_on_cust.php"); 
require_once("do_off_cust.php"); 
mysql_close($link);
	fdebug("<br>");

//$link = mysql_connect("localhost", "radik", "1597") or die("Невозможно соединиться (cud not to connect): " . mysql_error());
$link = mysql_connect("localhost", "root", "455029") or die("Невозможно соединиться (cud not to connect): " . mysql_error());

/* === Старый вариант */
$s_ins = "truncate radius.radcheck;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radcheck (username, nasipaddress) select username, nasipaddress from Selena.v_radcheck;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radcheck (username, nasipaddress) select MAC, NAS from radius.technology_equipment;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
fdebug("radcheck - √<br>".chr(13));

$s_ins = "truncate radius.radreply;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radreply (username, value, nasipaddress) select username, value, nasipaddress from Selena.v_radreply;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radreply (username,value,nasipaddress) select MAC,VLAN,NAS from radius.technology_equipment;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
fdebug("radreply - √<br>".chr(13));
/*======== */
/* ===== Удалить все строки и вставить
$s_ins = "delete from radius.radcheck where 1;"; //radius.radcheck.username not in (select username from Selena.v_radcheck)
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radcheck (username, nasipaddress) select username, nasipaddress from Selena.v_radcheck;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radcheck (username, nasipaddress) select MAC, NAS from radius.technology_equipment;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
fdebug("radcheck - √<br>".chr(13));

$s_ins = "delete from radius.radreply where 1;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radreply (username, value, nasipaddress) select username, value, nasipaddress from Selena.v_radreply;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
$s_ins = "insert into radius.radreply (username,value,nasipaddress) select MAC,VLAN,NAS from radius.technology_equipment;";
$r_ins = mysql_query($s_ins) or die($s_ins." ".mysql_error());
fdebug("radreply - √<br>".chr(13));
/*====================*/

//mysql_close($link);

$link = mysql_connect("localhost", "root", "455029") or die("Невозможно соединиться: " . mysql_error());
$r_ins = mysql_query("truncate table Selena.act2013;") or die(mysql_error());
$r_ins = mysql_query("insert into Selena.act2013 select * from Selena.actions where  DATE_FORMAT(InputDate,'%Y') = '2013';") or die(mysql_error());
//mysql_close($link);

fdebug("<br>выполнено без ошибок");
//#############################################################################################
function fdebug($sdeb) {
	if ($GLOBALS['deb']==1) { echo /*date("Y-m-d H:i:s")." ".*/$sdeb; }
}
?>

