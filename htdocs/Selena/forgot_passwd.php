<?
 require_once("bookmark_fns.php");
 do_html_header("Восстановить пароль");

 if ($password=reset_password($username))
 { 
    if (notify_password($username, $password))
      echo "Ваш новый пароль был отправлен Вам на email адрес.";
    else
      echo "Ваш пароль не может быть отправлен Вам по email."
           ." Try pressing refresh.";
 }
 else
   echo "Your password could not be reset - please try again later.";

  do_html_url("login.php", "Login");

 do_html_footer();
?>
