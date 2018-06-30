<? 
global $link;
function db_connect()
{
    
$user = '=first=';
$password = 'picsel1996';
$db = 'auth_reg';
$host = 'localhost';
$port = 8889;

$link = mysqli_init();
    
if (!$link) {
    die('mysqli_init завершилась провалом');
}
    
   $result = mysqli_connect(
   //$link, 
   $host, 
   $user, 
   $password, 
   $db
   //$port
);
    
	if (!$result)
		return false;
    
	//$r1 = mysqli_query("set character_set_results='utf8'");
	//$r1 = mysqli_query("SET NAMES 'utf8'");
    //echo "<br>DB_CONNECT IS GOOD<br>";
    //mysqli_close();
	return $result;
}?>
