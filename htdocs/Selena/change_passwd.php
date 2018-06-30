<?
 require_once("bookmark_fns.php");
 session_start();
 do_html_header("Изменение пароля");
 check_valid_user();
 if (!filled_out($HTTP_POST_VARS))
 {
   echo "You have not filled out the form completely.
         Please try again.";
   display_user_menu();
   do_html_footer();  
   exit;
 }
 else 
 {
$new_passwd = $_REQUEST ["new_passwd"];
$new_passwd2 = $_REQUEST ["new_passwd2"];
    if ($new_passwd!=$new_passwd2)
       echo "Введённые варианты пароля не одинаковы.  Пароль не изменён.";
    else if (strlen($new_passwd)>16 || strlen($new_passwd)<6)
       echo "New password must be between 6 and 16 characters.  Пароль не изменён. Длина пароля ".strlen($new_passwd);
    else
    {
        // attempt update
        if (change_password($valid_user, $old_passwd, $new_passwd))
           echo "Пароль изменён.";
        else
           echo "Пароль не может быть изменён.";
    }


 }
   display_user_menu(); 
   do_html_footer();
?>
