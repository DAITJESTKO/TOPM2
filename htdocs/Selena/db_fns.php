<? function db_connect()
{
	$result = mysql_pconnect("localhost", "=usr=", "24861379"); 
	if (!$result)
		return false;
	if (!mysql_select_db("Selena"))
		return false;
	$r1 = mysql_query("set character_set_results='utf8'");
	$r1 = mysql_query("SET NAMES 'utf8'");
	return $result;
}?>