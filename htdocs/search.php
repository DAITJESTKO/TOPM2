<?php

require_once("bookmark_fns.php");
$conn = db_connect();
  if (!$conn)
    return 0;

if(!empty($_REQUEST['referal'])){ //Принимаем данные

    $referal = trim(strip_tags(stripcslashes(htmlspecialchars($_REQUEST['referal']))));
    $query("SELECT name_object,id_object from objects");
    
    $db_referal = mysqli_query($conn,"SELECT name_object,id_object from objects");
    or die('Ошибка Обратитесь к администратору сайта пожалуйста, сообщив номер ошибки.');

    while ($row["name_object"] = mysqli_fetch_array($db_referal)) {
        echo "\n<li>".$row["name_object"]."</li>"; //$row["name"] - имя поля таблицы
    }

}
?>