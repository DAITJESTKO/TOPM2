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
$link = mysql_connect("localhost", "radik", "1597") or die("Невозможно соединиться: " . mysql_error());

$s_ins = "truncate radius.radreply;";
$r_ins = mysql_query($s_ins) or die(mysql_error());
$s_ins = "insert into radius.radreply select id, username, attribute, op, value, nasipaddress from Selena.v_radreply;";
$r_ins = mysql_query($s_ins) or die(mysql_error());
$s_ins = "truncate radius.radcheck;";
$r_ins = mysql_query($s_ins) or die(mysql_error());
$s_ins = "insert into radius.radcheck select id, username, attribute, op, value, nasipaddress from Selena.v_radcheck;";
$r_ins = mysql_query($s_ins) or die(mysql_error());

mysql_close($link);
fdebug("готово!");
//#############################################################################################
function fdebug($sdeb) {
	if ($GLOBALS['deb']==1) { echo /*date("Y-m-d H:i:s")." ".*/$sdeb; }
}
?>
