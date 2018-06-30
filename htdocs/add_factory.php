<meta charset="utf-8">
<?
require_once("bookmark_fns.php"); 

session_start();
$conn = db_connect();
  if (!$conn)
    return 0;


?>

<? if(isset($_POST['submit'])){
    
    $name_factory=$_POST['name_factory'];
    $id_company=$_POST['id_company'];
    $id_factory = $_POST['id_factory'];
    
    $query = "INSERT INTO factory (id_factory,name_factory,id_company) VALUES ('{}','{$name_factory}','{$_REQUEST['id_company']}')";
    //echo "query = ",$query;
    $result1 = mysqli_query($conn,$query);
    
}
    
    ?>
<? 
$id_company = $_REQUEST['id_company'];
$text = mysqli_fetch_assoc (mysqli_query($conn,"SELECT name_company from company where id_company = $id_company"));
$text = $text['name_company'];
if(!isset($_POST['submit']))echo "Добавить филлиал в ", $text;else echo $name_factory," добавлен в ",$text; ?>

    <form method="post" action="add_factory.php?id_company=<? echo $_REQUEST['id_company']; ?>">
    
    <input type="text" name="name_factory" placeholder="name_factory" required /><br>


        <input type="submit" name="submit" value="Добавить" />

    </form>

