<?

function do_html_header($title)
{
  // print an HTML header
?>
  <html>
  <head>
    <title><?=$title?></title>
    <style>
      body { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      li, td { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      hr { color: #3333cc; width:300; text-align:left; }
      a { color: #000000 }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="selena.css" type="text/css" />
  </head>
  <body>
<!--  <img src="top_1.gif" alt="Selena logo" border=0
       align=left valign=bottom height = 82 width = 350><br>
  <h1>&nbsp;</h1><br>  <hr>-->
<?
  if($title)
    do_html_heading($title);
}

function do_html_footer()
{
  // print an HTML footer PHPbookmark
?>
  </body>
  </html>
<?
}

function do_html_heading($heading)
{
  // print heading
?>
  <h2><?=$heading?></h2>
<?
}

function do_html_URL($url, $name)
{
  // output URL as link and br
?>
  <br><a href="<?=$url?>"><?=$name?></a><br>
<?
}

function display_site_info()
{
  // display some marketing info
?>
  <ul>
  <li>Сервер статистики пользователей
  <li>Управление пользователями
  <li>Подключение и переподключение
  </ul>
<?
}

function display_login_form()
{
?>
  <a href="register_form.php">Не зарегистрированы?</a>
  <form method=post action="selena.php"> <? //member.php ?>
  <table bgcolor=#cccccc>
   <tr>
     <td colspan=2>Авторизация:</td>
   <tr>
     <td>Имя:</td>
     <td><input type=text name=username></td></tr>
   <tr>
     <td>Пароль:</td>
     <td><input type=password name=passwd></td></tr>
   <tr>
     <td colspan=2 align=center>
     <input type=submit value="Вход"></td></tr>
   <tr>
     <td colspan=2><a href="forgot_form.php">Забыли пароль?</a></td>
   </tr>
 </table></form>
<?
}

function display_registration_form()
{
?>
 <form method=post action="register_new.php">
 <table bgcolor=#cccccc>
   <tr>
     <td>Email адресс:</td>
     <td><input type=text name=email size=30 maxlength=100></td></tr>
   <tr>
     <td>Предпочитаемое имя <br>(max 16 симв.):</td>
     <td valign=top><input type=text name=username
                     size=16 maxlength=16></td></tr>
   <tr>
     <td>Пароль <br>(от 6 до 16 симв.):</td>
     <td valign=top><input type=password name=passwd
                     size=16 maxlength=16></td></tr>
   <tr>
     <td>Повторите пароль:</td>
     <td><input type=password name=passwd2 size=16 maxlength=16></td></tr>
   <tr>
     <td colspan=2 align=center>
     <input type=submit value="Register"></td></tr>
 </table></form>
<? 

}


function display_user_urls($url_array)
{
  //display the table of URLs

  // set global variable, so we can test later if this is on the page
  global $bm_table;
  $bm_table = true;
?>
  <br>
  <form name=bm_table action="delete_bms.php" method=post>
  <table width=300 cellpadding=2 cellspacing=0>
  <?
  $color = "#cccccc";
  echo "<tr bgcolor=$color><td><strong>Bookmark</strong></td>";
  echo "<td><strong>Delete?</strong></td></tr>";
  if (is_array($url_array) && count($url_array)>0)
  {
    foreach ($url_array as $url)
    {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";
      // remember to call htmlspecialchars() when we are displaying user data
      echo "<tr bgcolor=$color><td><a href=\"$url\">".htmlspecialchars($url)."</a></td>";
      echo "<td><input type=checkbox name=\"del_me[]\"
             value=\"$url\"></td>";
      echo "</tr>"; 
    }
  }
  else
    echo "<tr><td>No bookmarks on record</td></tr>";
?>
  </table> 
  </form>
<?
}

function display_user_menu()
{
  // display the menu options on this page
?>
<hr>
<a href="selena.php">Home</a> &nbsp;|&nbsp;
<!--<a href="add_bm_form.php">Add BM</a> &nbsp;|&nbsp; -->
<?
  // only offer the delete option if bookmark table is on this page
  global $bm_table;
  if($bm_table==true)
    echo "<a href='#' onClick='bm_table.submit();'>Delete BM</a>&nbsp;|&nbsp;"; 
  else
    echo "<font color='#cccccc'>Delete BM</font>&nbsp;|&nbsp;"; 
?>
<a href="change_passwd_form.php">Изменить пароль</a>
<br>
<!--<a href="recommend.php">Recommend URLs to me</a> &nbsp;|&nbsp;-->
<a href="logout.php">Выход</a> 
<hr>

<?
}

function display_add_bm_form()
{
  // display the form for people to ener a new bookmark in
?>
<form name=bm_table action="add_bms.php" method=post>
<table width=250 cellpadding=2 cellspacing=0 bgcolor=#cccccc>
<tr><td>New BM:</td><td><input type=text name=new_url  value="http://"
                        size=30 maxlength=255></td></tr>
<tr><td colspan=2 align=center><input type=submit value="Add bookmark"></td></tr>
</table>
</form>
<?
}

function display_password_form()
{
  // display html change password form
?>
   <br>
   <form action="change_passwd.php" method=post>
   <table width=250 cellpadding=2 cellspacing=0 bgcolor=#cccccc>
   <tr><td>Старый пароль:</td>
       <td><input type=password name=old_passwd size=16 maxlength=16></td>
   </tr>
   <tr><td>Новый пароль:</td>
       <td><input type=password name=new_passwd size=16 maxlength=16></td>
   </tr>
   <tr><td>Повторите новый пароль:</td>
       <td><input type=password name=new_passwd2 size=16 maxlength=16></td>
   </tr>
   <tr><td colspan=2 align=center><input type=submit value="Изменить пароль">
   </td></tr>
   </table>
   <br>
<?
};

function display_forgot_form()
{
  // display HTML form to reset and email password
?>
   <br>
   <form action="forgot_passwd.php" method=post>
   <table width=250 cellpadding=2 cellspacing=0 bgcolor=#cccccc>
   <tr><td>Enter your username</td>
       <td><input type=text name=username size=16 maxlength=16></td>
   </tr>
   <tr><td colspan=2 align=center><input type=submit value="Change password">
   </td></tr>
   </table>
   <br>
<?
};

function display_recommended_urls($url_array)
{
  // similar output to display_user_urls
  // instead of displaying the users bookmarks, display recomendation
?>
  <br>
  <table width=300 cellpadding=2 cellspacing=0>
<?
  $color = "#cccccc";
  echo "<tr bgcolor=$color><td><strong>Recommendations</strong></td></tr>";
  if (is_array($url_array) && count($url_array)>0)
  {
    foreach ($url_array as $url)
    {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";
      echo "<tr bgcolor=$color><td><a href=\"$url\">".htmlspecialchars($url)."</a></td></tr>";
    }
  }
  else
    echo "<tr><td>No recommendations for you today.</td></tr>";
?>
  </table>
<?
}
  ?>
