<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="background/dist/particles.min.js"></script>   
  

<div class="form" id="registration">

      <ul class="tab-group2">
        <li class="tab active" width="50%"><a href="#signup">Зарегистрироваться</a></li>
        <li class="tab"><a href="#login">Войти</a></li>
      </ul>
      

    
      <div class="tab-content">
        <div id="signup">
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
        print_r($result);
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
          <form method="post" action="signup.php">
        <table>
            <tr>
                <td>Login:</td>
                <td><input type="text" name="login" placeholder="Логин" required /></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="text" name="email" placeholder="Email" required /></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" placeholder="Пароль" required /></td>
            </tr>
            <tr>
                <td>Repeat Pass:</td>
                <td><input type="password" name="password2" placeholder="Повторите пароль " required /></td>
            </tr>
            <tr><input type="submit" name="submit" value="Зарегистрироваться" /></tr>
          
            </table>
            </form>
        </div>
        
        <div id="login">   
<?
 require_once("bookmark_fns.php");
 do_html_header("");

 display_site_info(); 
 display_login_form();

 do_html_footer();
?>
        </div>
        
      </div><!-- tab-content -->
     
</div> <!-- /form -->
  <canvas class="background"></canvas>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script type="text/javascript">
    
$('.form').find('input, textarea').on('keyup blur focus', function (e) {
  
  var $this = $(this),
      label = $this.prev('label');

	  if (e.type === 'keyup') {
			if ($this.val() === '') {
          label.removeClass('active highlight');
        } else {
          label.addClass('active highlight');
        }
    } else if (e.type === 'blur') {
    	if( $this.val() === '' ) {
    		label.removeClass('active highlight'); 
			} else {
		    label.removeClass('highlight');   
			}   
    } else if (e.type === 'focus') {
      
      if( $this.val() === '' ) {
    		label.removeClass('highlight'); 
			} 
      else if( $this.val() !== '' ) {
		    label.addClass('highlight');
			}
    }

});


$('.tab a').on('click', function (e) {
  
  e.preventDefault();
  
  $(this).parent().addClass('active');
  $(this).parent().siblings().removeClass('active');
  
  target = $(this).attr('href');

  $('.tab-content > div').not(target).hide();
  
  $(target).fadeIn(600);
  
});
    
        window.onload = function() {
        
  Particles.init({  
      selector: '.background',

      breakpoint: 150,
      maxParticles: 150,
      color: '414a4c',
      connectParticles: true
      
  });

};
</script>