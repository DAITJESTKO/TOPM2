<?

// include function files for this application
require_once("bookmark_fns.php"); 
session_start();

if ($username && $passwd)
// they have just tried logging in
{
    if (login($username, $passwd))
    {
      // if they are in the database register the user id
      $valid_user = $username;
      session_register("valid_user");
    }  
    else
    {
      // unsuccessful login
      do_html_header("Проблема:");
      echo "Вы не можете войти. 
            Для просмотра страницы, Вы должны авторизоваться.";
      do_html_url("login.php", "Login");
      do_html_footer();
      exit;
    }      
}

do_html_header("");
check_valid_user();
// get the bookmarks this user has saved  Home
if ($url_array = get_user_urls($valid_user));
  display_user_urls($url_array);

// give menu of options
display_user_menu();

do_html_footer();

?>
