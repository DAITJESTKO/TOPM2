<meta charset="utf-8">

<?
require_once("bookmark_fns.php"); 
session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

$id_workshop = $_REQUEST["id_workshop"];
//$_REQUEST["r_name_workshop"] = $text;
//echo "TEXT = ",$text," ID = ",$id_workshop;
$type_action = $_REQUEST["type_action"];
 ?>
<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="css/style.css">
<input type="hidden" id="id_workshop" value="<? echo $id_workshop; ?>" />
<h3>Выберете действие :</h3>
<select id="s_type_action" onChange="f_select_type_action()">
	<option value="">---</option>
	<option <? if(!empty($type_action) && $type_action == 1) echo "selected" ?> value="1">Создать новое оборудование</option>
	<option <? if(!empty($type_action) && $type_action == 2) echo "selected" ?> value="2">Выбрать оборудование из библиотеки</option>
</select>
<div id="d_type_action"></div>


