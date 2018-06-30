<?
	require_once("db_fns.php");
	require_once("user_auth_fns.php");
	$conn = db_connect();
	if (!$conn) return 0;
	$account =  $_REQUEST ["account"];
	$amount =  $_REQUEST ["amount"];
echo date("Y-m-d H:i:s")."<br> ";
error_reporting(E_ALL); 

set_time_limit(10); 
ob_implicit_flush(); 


if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
    echo "socket_create() failed: reason: " . socket_strerror($sock) . "\n";
}
$result    = socket_connect($socket, $IPb, 49160); 
if (!get_wellcome($socket)) 
	echo date("Y-m-d H:i:s")," Ошибка при прочитении приветствия<br> ";

echo date("Y-m-d H:i:s"),"look: ";
$nic = ($n = getNic(get_000($socket, "look $account"))==""?getNic(get_000($socket, "look $account")):$n);
echo $nic,"<br> ";
//echo getNic(get_000($socket, "look $account")),"<br> ";

echo date("Y-m-d H:i:s"),"acc1: ";
echo $acc_1 = getSum(send_command($socket, "acc $account")),"<br> "; 

echo date("Y-m-d H:i:s"),"add: ";
 $add_res = get_000($socket, "add $account $amount"); 

echo date("Y-m-d H:i:s"),"acc2: ";
echo $acc_2 = getSum(send_command($socket, "acc $account")),"<br> "; 
echo date("Y-m-d H:i:s")."_"."<br> ";
/*
send_command($socket, 'conf t'); 
send_command($socket, 'int fa0/1'); 
send_command($socket, 'shut'); 
send_command($socket, 'end'); 
   */ 
socket_close($socket); 



function read_welcome_message($socket) 
{ 
   while ($out = socket_read($socket, 512))
   { 
      if(preg_match('/Username:/i',$out)) 
         return (true); 
   } 
} 

function send_login($socket, $username) 
{ 
   socket_write($socket, $username . "\n", strlen($username) + 1); 

   while ($out = socket_read($socket, 512)) 
   { 
      if(preg_match('/Password:/i',$out)) 
         return (true); 
   } 
} 

function send_passw($socket, $password) 
{ 
   socket_write($socket, $password . "\n", strlen($password) + 1); 

   while ($out = socket_read($socket, 512)) //
   { 
      if(preg_match('/#/i',$out)) 
         return(true); 
          
      if(preg_match('/Username:/i',$out)) 
         return(false); 
   } 
} 
/*
function send_command($socket, $command) 
{ 
   socket_write($socket, $command . "\n", strlen($command) + 1); 
    
   while ($out = socket_read($socket, 200)) // 
   { 
//   echo $out;
      if(preg_match('/#/i',$out)) 
         return $out; //(true)
   } 
}

function get_look($socket, $acc) 
{ 
	$command= 'look '.$acc;
   socket_write($socket, $command . "\n", strlen($command) + 1); 
   while ($out = socket_read($socket, 512)) // 
   { 
//   echo $out;
      if(preg_match('/000/i',$out)) 
         return $out; //(true); 
   } 
}

function get_wellcome($socket) 
{ 
   while ($out = socket_read($socket, 512)) // 
   { 
//   echo $out;
      if(preg_match('/Ready/i',$out)) 
         return(true); 
   } 
}*/
?>