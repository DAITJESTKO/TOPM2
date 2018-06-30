<script src="phaser.js"></script>
<script src="phaser-input.js"></script>
<script src="game.js"></script>
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

if(isset($_REQUEST["id_factory"])){
    
$id_factory=$_REQUEST["id_factory"];
//echo "ID_FACTORY = ",$id_factory;
$query = "SELECT count(id_est_m) as count from v_established where id_factory = '$id_factory'";
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result);

?>
<input type="hidden" id="quantity_mach" value="<? echo $row["count"] ?>" /> 
<?
$query = "SELECT * from v_established where id_factory = '$id_factory'";
$result = mysqli_query($conn,$query);
        
    
$i=0;
while($row = mysqli_fetch_assoc($result)){
    
    $id_machine = $row["id_est_m"];
    $query1 = "SELECT * from v_established where id_est_m = '$id_machine'";
    echo $query1,"<br>";
    $result1 = mysqli_query($conn,$query1);
    $row1 = mysqli_fetch_assoc($result1);
    //echo "id_machine = ",$id_machine,"<br>";
    $name_machine = $row["name_machine"];
    //echo "name_machine = ",$name_machine,"<br>";
    $X_machine = $row1["X_machine"];
    //echo "X_machine = ",$X_machine,"<br>";
    $Y_machine = $row1["Y_machine"];
    //echo "Y_machine = ",$Y_machine,"<br>";
    ?>
<input type="hidden" id="id_<? echo $i ?>" value="<? echo $id_machine ?>" />
<input type="hidden" id="name_<? echo $i ?>" value="<? echo $name_machine ?>" />
<input type="hidden" id="x_<? echo $i ?>" value="<? echo $X_machine ?>" />
<input type="hidden" id="y_<? echo $i ?>" value="<? echo $Y_machine ?>" />
<?
    $i++;
}
    $query = "SELECT * from v_est_objects where id_factory = '$id_factory' order by id_est_object";
    echo $query,"<br>";
    $result = mysqli_query($conn,$query);
    $id_est_object;
    $k=0;
    while($row = mysqli_fetch_assoc($result)){
        echo $row["id_est_object"]," --- ",$id_est_object,"<br>";
        if($id_est_object!=$row["id_est_object"]){
            
            $id_est_object = $row["id_est_object"];
            echo " id_est_object = ",$id_est_object;
            $name_object  = $row["name_object"];
            echo " name_object = ",$name_object;
            $query = "SELECT count(id_est_m) as count from v_est_objects where id_est_object = '$id_est_object'";
            $result = mysqli_query($conn,$query);
            $row = mysqli_fetch_assoc($result);
            $quantity_mach = $row["count"];
            $query = "SELECT * from established_obj where id_est_object = '$id_est_object'";
            $result = mysqli_query($conn,$query);
            $row = mysqli_fetch_assoc($result);
            $id_group_tech_proc = $row["id_group_tech_proc"];
            echo " id_group_tech_proc = ",$id_group_tech_proc;
            $quantity_object = $row["quantity_obj"];
            echo " quantity_object = ",$quantity_object;
?>

<input type="hidden" id="quantity_object<? echo $k ?>" value="<? echo $quantity_object ?>" /> 
<input type="hidden" id="id_object_<? echo $k ?>" value="<? echo $id_est_object ?>" />
<input type="hidden" id="name_object<? echo $k ?>" value="<? echo $name_object ?>" />

<? $k++; } } ?>
    <input type="hidden" id="k" value="<? echo $k ?>" />
<?    

}else {
    $id_est_object=$_REQUEST["id_est_object"];
    $query = "SELECT count(id_est_m) as count from v_est_objects where id_est_object = '$id_est_object'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $quantity_mach = $row["count"];
    $query = "SELECT * from v_est_objects where id_est_object = '$id_est_object'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $name_object  = $row["name_object"];
    
    $query = "SELECT * from established_obj where id_est_object = '$id_est_object'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $id_group_tech_proc = $row["id_group_tech_proc"];
    $quantity_object = $row["quantity_obj"];
    ?>
<input type="hidden" id="quantity_mach0" value="<? echo $quantity_mach ?>" /> 
<input type="hidden" id="quantity_object0" value="<? echo $quantity_object ?>" /> 
<input type="hidden" id="id_object_0" value="<? echo $id_est_object ?>" />
<input type="hidden" id="name_object0" value="<? echo $name_object ?>" />
<input type="hidden" id="k" value="1" />
<?
$query = "SELECT * from v_est_objects where id_est_object = '$id_est_object' order by priority_tech_oper";
$result = mysqli_query($conn,$query);
$i=0;
while($row = mysqli_fetch_assoc($result)){
    
    $id_machine = $row["id_est_m"];
    $query1 = "SELECT * from v_established where id_est_m = '$id_machine'";
    echo $query1;
    $result1 = mysqli_query($conn,$query1);
    $row1 = mysqli_fetch_assoc($result1);
    //echo "id_machine = ",$id_machine,"<br>";
    $name_machine = $row1["name_machine"];
    //echo "name_machine = ",$name_machine,"<br>";
    $X_machine = $row1["X_machine"];
    //echo "X_machine = ",$X_machine,"<br>";
    $Y_machine = $row1["Y_machine"];
    //echo "Y_machine = ",$Y_machine,"<br>";
    ?>
<input type="hidden" id="id_<? echo $i ?>" value="<? echo $id_machine ?>" />
<input type="hidden" id="name_<? echo $i ?>" value="<? echo $name_machine ?>" />
<input type="hidden" id="x_<? echo $i ?>" value="<? echo $X_machine ?>" />
<input type="hidden" id="y_<? echo $i ?>" value="<? echo $Y_machine ?>" />
<?
$i++;
    }
    $query1 = "SELECT * from tech_processes where id_group_tech_proc = '$id_group_tech_proc' order by priority_tech_oper";
    $result1 = mysqli_query($conn,$query1);
    while($row1 = mysqli_fetch_assoc($result1)){
?>
    <input type="hidden" id="Stanki_<? echo $id_est_object,"_",$row1["id_est_m"]; ?>" value="<? echo $row1["id_est_m"] ?>" />

<? } } ?>