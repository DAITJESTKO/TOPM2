﻿<?php
//echo date("Y-m-d H:i:s"), "<br>";
/*$cnct = new Telnet;
$cnct->__construct();
//echo $cnct->exec("telnet 10.1.5.115 49160");
$i = 1;
do { echo $i." - ". $cnct->exec("look 3")."#<br>"; $i += 1; }
while ($i <=1);
//$cnct->setPrompt("look 3");

$cnct->__destruct();

echo "<br>---------------------<br>";	*/
$fp = fsockopen("10.1.5.115", 49160, $errno, $errstr, 30);
//$fp = fsockopen ("www.example.com", 80, $errno, $errstr, 30);udp://GET / HTTP/1.0\r\nHost: www.example.com \r\n\r\n
if (!$fp) {
echo "$errstr ($errno)<br>\n";
} else {
	while (false !== ($char = fgetc($fp))) { }
	
	fputs ($fp, "look 3\n");	$s = '';
	while (false !== ($char = fgetc($fp))) { $s .= "$char"; }
//	echo $s,"<br>";
	$s_a = explode(" ", $s); echo "Пользователь: ";
//	show_chr($s_a[4]); echo "<br>";
	$n_ = explode(chr(9), $s_a[4]);
//	print_r($n_); echo "<br>";
	$nic = explode(chr(10), $n_[2]);	
	echo $nic[0]."<br>";
/*	list($cod1, $com1, $cmd, $bil, $cod2, $com2, $bill, $nic, $cod3, $inet, $bill2, $IP, $cod4, $list, $bill4, $vpn, $cod5) = explode(" ", $s);
	echo $nic,"<br>";*/

	fputs ($fp, "acc 3\n");	$s = '';
	while (false !== ($char = fgetc($fp))) { $s .= "$char"; }
//	echo $s."<br>";
	$s_a = explode(" ", $s);	echo "Баланс: ".$s_a[13]."<br>";
	
	fclose ($fp);
}
echo "---------------------<br>";

function show_chr($s) {
	$i=0; 
	while ($i < strlen($s)) { 
		echo ord($s{$i})." "; 
		$i += 1; 
	}
}
////////////////////////////////////////////////////
return;
error_reporting(E_ALL);

//echo "<h2>TCP/IP Connection</h2>\n";

/* Get the port for the WWW service. */
$service_port = 49160; //getservbyname('www', 'tcp');

/* Get the IP address for the target host. www.example.com*/
$address = gethostbyname('10.1.5.115');

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket < 0) {
    echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result < 0) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
} else {
    echo "OK.\n";
}

$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: www.example.com\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';
$in = 'look 3';

echo "Sending HTTP HEAD request...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";

return;
/**
* Telnet class
* 
* Used to execute remote commands via telnet connection 
* Usess sockets functions and fgetc() to process result
* 
* All methods throw Exceptions on error
* 
* Written by Dalibor Andzakovic <dali@swerve.co.nz>
* Based on the code originally written by Marc Ennaji and extended by 
* Matthias Blaser <mb@adfinis.ch>
*/
class Telnet {
	
	private $host;
	private $port;
	private $timeout;
	
	private $socket  = NULL;
	private $buffer = NULL;
	private $prompt;
	private $errno;
	private $errstr;
	
	private $NULL;
	private $DC1;
	private $WILL;
	private $WONT;
	private $DO;
	private $DONT;
	private $IAC;
	
	const TELNET_ERROR = FALSE;
	const TELNET_OK = TRUE;        
	
	/**
	* Constructor. Initialises host, port and timeout parameters
	* defaults to localhost port 23 (standard telnet port)
	* 
	* @param string $host Host name or IP addres
	* @param int $port TCP port number
	* @param int $timeout Connection timeout in seconds
	* @return void
	public function __construct($host = '127.0.0.1', $port = '23', $timeout = 10){
	*/
	public function __construct($host = '10.1.5.115', $port = 49160, $timeout = 30){
		
		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;
		
		// set some telnet special characters
		$this->NULL = chr(0);
		$this->DC1 = chr(17);
		$this->WILL = chr(251);
		$this->WONT = chr(252);
		$this->DO = chr(253);
		$this->DONT = chr(254);
		$this->IAC = chr(255);
		
		$this->connect();  	
		
	}
	
	/**
	* Destructor. Cleans up socket connection and command buffer
	* 
	* @return void 
	*/
	public function __destruct() {
		
		// cleanup resources
		$this->disconnect();
		$this->buffer = NULL;
		
	}
	
	/**
	* Attempts connection to remote host. Returns TRUE if sucessful.      
	* 
	* @return boolean
	*/
	public function connect(){
		
		// check if we need to convert host to IP
		if (!preg_match('/([0-9]{1,3}\\.){3,3}[0-9]{1,3}/', $this->host)) {
			
			$ip = gethostbyname($this->host);
			
			if($this->host == $ip){
				
				throw new Exception("Cannot resolve $this->host");
				
			} else{
				$this->host = $ip; 
			}
		}
		
		// attempt connection
		$this->socket = fsockopen($this->host, $this->port, $this->errno, $this->errstr, $this->timeout);
		
		if (!$this->socket){        	
			throw new Exception("Cannot connect to $this->host on port $this->port");
		}
		
		return self::TELNET_OK;
	}
	
	/**
	* Closes IP socket
	* 
	* @return boolean
	*/
	public function disconnect(){
		if ($this->socket){
			if (! fclose($this->socket)){
				throw new Exception("Error while closing telnet socket");                
			}
			$this->socket = NULL;
		}        
		return self::TELNET_OK;
	}
	
	/**
	* Executes command and returns a string with result.
	* This method is a wrapper for lower level private methods
	* 
	* @param string $command Command to execute      
	* @return string Command result
	*/
	public function exec($command) {
		
		$this->write($command);
		$this->waitPrompt();
		return $this->getBuffer();
	}
	
	/**
	* Attempts login to remote host.
	* This method is a wrapper for lower level private methods and should be 
	* modified to reflect telnet implementation details like login/password
	* and line prompts. Defaults to standard unix non-root prompts
	* 
	* @param string $username Username
	* @param string $password Password
	* @return boolean 
	*/
	public function login($username, $password) {
		
		try{
			$this->setPrompt('login:');
			$this->waitPrompt();
			$this->write($username);
			$this->setPrompt('Password:');
			$this->waitPrompt();
			$this->write($password);
			$this->setPrompt();
			$this->waitPrompt();
		} catch(Exception $e){
			
			throw new Exception("Login failed.");
		}
		
		return self::TELNET_OK;
	}
	
	/**
	* Sets the string of characters to respond to.
	* This should be set to the last character of the command line prompt
	* 
	* @param string $s String to respond to
	* @return boolean
	*/
	public function setPrompt($s = '$'){
		$this->prompt = $s;
		return self::TELNET_OK;
	}
	
	/**
	* Gets character from the socket
	*     
	* @return void
	*/
	private function getc() {
		return fgetc($this->socket); 
	}
	
	/**
	* Clears internal command buffer
	* 
	* @return void
	*/
	private function clearBuffer() {
		$this->buffer = '';
	}
	
	/**
	* Reads characters from the socket and adds them to command buffer.
	* Handles telnet control characters. Stops when prompt is ecountered.
	* 
	* @param string $prompt
	* @return boolean
	*/
	private function readTo($prompt){
	
		if (!$this->socket){
			throw new Exception("Telnet connection closed");            
		}
		
		// clear the buffer 
		$this->clearBuffer();
		
		do{
			
		$c = $this->getc();
			
			if ($c === false){
				throw new Exception("Couldn't find the requested : '" . $prompt . "', it was not in the data returned from server : '" . $this->buffer . "'");                
			}
			
			// Interpreted As Command
			if ($c == $this->IAC){
				if ($this->negotiateTelnetOptions()){
					continue;
				}
			}
			
			// append current char to global buffer           
			$this->buffer .= $c;
			
			// we've encountered the prompt. Break out of the loop
			if ((substr($this->buffer, strlen($this->buffer) - strlen($prompt))) == $prompt){
				return self::TELNET_OK;
			}
			
		} while($c != $this->NULL || $c != $this->DC1);
	}
	
	/**
	* Write command to a socket
	* 
	* @param string $buffer Stuff to write to socket
	* @param boolean $addNewLine Default true, adds newline to the command 
	* @return boolean
	*/
	private function write($buffer, $addNewLine = true){
	
		if (!$this->socket){
			throw new Exception("Telnet connection closed");
		}
	
		// clear buffer from last command
		$this->clearBuffer();
		
		if ($addNewLine == true){
			$buffer .= "\n";
		}
		
		if (!fwrite($this->socket, $buffer) < 0){
			throw new Exception("Error writing to socket");
		}
		
		return self::TELNET_OK;
	}
	
	/**
	* Returns the content of the command buffer
	* 
	* @return string Content of the command buffer 
	*/
	private function getBuffer(){
		// cut last line (is always prompt)
		echo $this->buffer;
		$buf = explode("\n", $this->buffer);
		unset($buf[count($buf)-1]);
		$buf = implode("\n",$buf);
		return trim($buf);
	}
	
	/**
	* Telnet control character magic
	* 
	* @param string $command Character to check
	* @return boolean
	*/
	private function negotiateTelnetOptions(){
		
		$c = $this->getc();
	
		if ($c != $this->IAC){
			
			if (($c == $this->DO) || ($c == $this->DONT)){
				
				$opt = $this->getc();
				fwrite($this->socket, $this->IAC . $this->WONT . $opt);
				
			} else if (($c == $this->WILL) || ($c == $this->WONT)) {
				
				$opt = $this->getc();            
				fwrite($this->socket, $this->IAC . $this->DONT . $opt);
				
			} else {
				throw new Exception('Error: unknown control character ' . ord($c ));
			}
		} else{
			throw new Exception('Error: Something Wicked Happened');
		}
		
		return self::TELNET_OK;
	}
	
	/**
	* Reads socket until prompt is encountered
	*/
	private function waitPrompt(){
		return $this->readTo($this->prompt);
	}
}

?>