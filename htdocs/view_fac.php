
<meta charset="utf-8">
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
      return 0; 
//print_r($_REQUEST);
$id_parent=$_REQUEST["id_parent"];
//echo "<br> id_parent = ",$id_parent;
$id_company=$_REQUEST["id_company"];
//echo "<br> id_company = ",$id_company;
$count_row = $_REQUEST['count_row']-1;
$name_object = $_REQUEST["name_object"];
//echo "<br> name_object",$name_object;
$quantity_object = $_REQUEST["quantity_object"];
//echo "<br> quantity_object",$quantity_object;
$part_object = $_REQUEST["part_object"];
//echo "<br> part_object",$part_object;   
$name_oper = "1"."name_oper";
$name_oper = $_REQUEST[$name_oper];
//echo "<br> name_oper ",$name_oper;
   // $id_factory = $_REQUEST['1id_factory'];
   // $id_workshop = $_REQUEST['1id_workshop'];
   // $id_machine = $_REQUEST['1id_machine'];
   // $time_oper = $_REQUEST['1time_oper'];


//запись имени нового объекта в библиотеку
$query = "INSERT INTO objects (id_object,name_object) VALUES ('{}',$name_object)";
//echo $query;
$result = mysqli_query($conn,$query) or die(mysqli_error());
//запись в таблицу существующих объектов
$query = "SELECT MAX(id_object) AS `m_obj` from objects ";
$result = mysqli_query($conn,$query) or die(mysqli_error());
$row = mysqli_fetch_assoc ($result);
$id_object = $row["m_obj"];
//echo "<br> id_obj",$id_object;

$query = "SELECT MAX(id_group_tech_proc)+1 AS `m_gr` from tech_processes";
$result = mysqli_query($conn,$query) or die(mysqli_error());
$row = mysqli_fetch_assoc ($result);
$id_group_tech_proc = $row["m_gr"];

$query = "INSERT INTO established_obj (id_est_object,id_object,id_group_tech_proc,quantity_obj,part_obj) VALUES ('{}',$id_object,$id_group_tech_proc,$quantity_object,$part_object)";
$result = mysqli_query($conn,$query) or die(mysqli_error());
//echo "<br>count_row = ",$count_row;
//запись техпроцесса
for($i=1;$i<=$count_row;$i++){
    $name_tech_oper = $i."name_oper";
    $name_tech_oper = $_REQUEST[$name_tech_oper];
   // echo "<br>name_tech_oper = ",$name_tech_oper;
    $time_tech_oper = $i."time_oper";
    $time_tech_oper = $_REQUEST[$time_tech_oper];
    $id_est_m = $i."id_machine";
    $id_est_m = $_REQUEST[$id_est_m];
    $query = "INSERT INTO tech_processes (id_tech_proc,id_group_tech_proc,name_tech_oper,time_tech_oper,id_est_m,priority_tech_oper) VALUES ('{}',$id_group_tech_proc,$name_tech_oper,$time_tech_oper,$id_est_m,$i)";
    $result = mysqli_query($conn,$query) or die(mysqli_error());
}

$query = "SELECT MAX(id_est_object) AS id_est_object from established_obj";
$result = mysqli_query($conn,$query) or die(mysqli_error());
$row = mysqli_fetch_assoc ($result);
$id_est_object = $row["id_est_object"];
if(!isset($id_est_object))$id_est_object=0;
$query = "INSERT INTO family (id_family,id_est_object,id_parent,id_company) VALUES ('{}',$id_est_object,$id_parent,$id_company)";
echo $query;
$result = mysqli_query($conn,$query) or die(mysqli_error());

?>