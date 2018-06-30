<meta charset="utf-8">
<?
require_once("bookmark_fns.php"); 

session_start();
$conn = db_connect();
  if (!$conn)
    return 0;


?>

<? if(isset($_POST['submit'])){
    
    $name_workshop=$_POST['name_workshop'];
    $id_type_ws=$_POST['id_type_ws'];
    $id_factory = $_POST['id_factory'];
    
    $query = "INSERT INTO workshops (id_workshop,id_type_ws,name_workshop,id_factory) VALUES ('{}','{$id_type_ws}','{$name_workshop}','{$_REQUEST['id_factory']}')";
    //echo "query = ",$query;
    $result1 = mysqli_query($conn,$query);
    
}
    
    ?>
<? 
$id_factory = $_REQUEST['id_factory'];
$text = mysqli_fetch_assoc (mysqli_query($conn,"SELECT name_factory from factory where id_factory = $id_factory"));
$text = $text['name_factory'];
if(!isset($_POST['submit']))echo "Добавить цех в ", $text;else echo $name_workshop," добавлен в ",$text; ?>

    <form method="post" action="add_workshop.php?id_factory=<? echo $_REQUEST['id_factory']; ?>">
    
    <input type="text" name="name_workshop" placeholder="name_workshop" required /><br>

        
        <select name="id_type_ws">
        <? 
        $query2 = "SELECT * from type_workshops";
            //echo $query;
        $result2 = mysqli_query($conn,$query2) or die(mysqli_error());
            //print_r($result);
        while ($row2 = mysqli_fetch_assoc ($result2)){ ?>
        <option value="<? echo $row2["id_type_ws"] ?>"><? echo $row2["name_type_ws"] ?></option>
            
            <? } ?>
        
        </select><br>
        

        <input type="submit" name="submit" value="Добавить" />

    </form>

