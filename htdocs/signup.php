<?
$user = 'root';
$password = 'root';
$db = 'auth_reg';
$host = 'localhost';
$port = 8889;


$link = mysqli_init();

if (!$link) {
    die('mysqli_init завершилась провалом');
}

//$result = mysql_pconnect("localhost", "=usr=", "24861379"); 

$success = mysqli_real_connect(
   $link, 
   $host, 
   $user, 
   $password, 
   $db,
   $port
);

if (!$success) {
    die('mysqli_real_connect завершилась провалом');
}

if(isset($_POST['submit'])){
    
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    
    if($password == $password2){
        
        $password = crypt($password,'2r');
        //проверка на уже существующего пользователя
        $query = "SELECT * FROM v_users WHERE login = '$login'";  
        //ОШИБКА ОШИБКА не проходит LINK
        $result = mysqli_query($link, $query);
        //print_r($result);
        if(mysqli_num_rows($result)==0){
        $query = "INSERT INTO users (id_user,login,password,email) VALUES ('{}','{$login}','{$password}','{$email}')";
        $result = mysqli_query($link,$query);
        }else echo "<br>Такой пользователь уже зарегистрирован!<br>";
        
        
            if(!$result){
                
                echo "ERROR IN QUERY TO DATABASE <br>";
                echo "'$login'<br>'$password'<br>'$email'<br>'$query'<br>'$db'";
                
                if($link && $success) echo "<br> link and success";
                
                
            }
        if ($result = mysqli_query($link, "SELECT * FROM users")) 
    //printf("Select вернул %d строк.\n", mysqli_num_rows($result));
            
        $mysqli_close($db);
        
        
        
    }else{
        
        die("Password uncorrectly!");
        
    }
}

?>
    <link rel="stylesheet" type="text/css" href="css/menu.css" />

    <form method="post" action="signup.php">

        <input type="text" name="login" placeholder="Login" required /><br>
        <input type="text" name="email" placeholder="Email" required /><br>
        <input type="password" name="password" placeholder="Password" required /><br>
        <input type="password" name="password2" placeholder="Repeat password" required /><br>
        <input type="submit" name="submit" value="Register" />

    </form>
