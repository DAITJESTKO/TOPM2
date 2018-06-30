<meta charset="utf-8">
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;


if($_REQUEST['id_factory']){$id_factory = $_REQUEST['id_factory'];$query = "DELETE from `factory` where `id_factory` = '$id_factory'";}
if($_REQUEST['id_workshop']){$id_workshop = $_REQUEST['id_workshop'];$query = "DELETE from `workshops` where `id_workshop` = '$id_workshop'";}
if($_REQUEST['id_est_m']){$id_est_m = $_REQUEST['id_est_m'];$query = "DELETE from `established_m` where `id_est_m` = '$id_est_m'";}
if($_REQUEST['id_object']){
    $id_object = $_REQUEST['id_object'];
    
    $query1 = "DELETE from `tech_processes` where `id_group_tech_proc` = (SELECT id_group_tech_proc from established_obj where id_object = '$id_object')";
    //echo $query1;
    mysqli_query($conn,$query1) or die(mysqli_error());
    $query2 = "DELETE from `established_obj` where `id_object` = '$id_object'";
    $query3 = "SELECT id_est_object from `established_obj` where `id_object` = '$id_object'";
    $result3 = mysqli_query($conn,$query3) or die(mysqli_error());
    $row3 = mysqli_fetch_assoc($result3);
    
    $id_est_object = $row3["id_est_object"];
    //echo $id_est_object;
    mysqli_query($conn,$query2) or die(mysqli_error());
    $query = "DELETE from `objects` where `id_object` = '$id_object'";
}

//echo $query;
mysqli_query($conn,$query) or die(mysqli_error());
$query = "DELETE from `family` where `id_est_object` = '$id_est_object'";
mysqli_query($conn,$query) or die(mysqli_error());
?> <h3> <? echo "удален!"; ?> </h3> 