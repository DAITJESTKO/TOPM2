<meta charset="UTF-8">

<?
require_once("bookmark_fns.php"); 
session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

$id_workshop = $_REQUEST["id_workshop"];
//echo "ID_WORKSHOP = ",$id_workshop;
$text = mysqli_fetch_assoc (mysqli_query($conn,"SELECT name_workshop from workshops where id_workshop = '$id_workshop'"));
$text = $text["name_workshop"];
 //echo "TYPE_ACTION",$type_action;?>
<h3> Новое оборудование будет добавлено в  <? echo $text; ?></h3>
       <h3> Выберете тип оборудования :</h3><br>
<select id="id_type_mach" onChange="f_select_type_machine()">
	<?
				$query2 = "SELECT * from type_machines";
				$result2 = mysqli_query($conn,$query2) or die(mysqli_error());
				while ($row2 = mysqli_fetch_assoc ($result2)){ ?>
				<option <? if($_REQUEST['id_type_mach'] == $row2["id_type_mach"]) echo "selected"; ?> value="<? echo $row2["id_type_mach"] ?>"><? echo $row2["name_type_mach"] ?></option>
				<? } ?>
</select>
<? 
if(isset($_REQUEST['id_type_mach'])){
	$id_type_mach = $_REQUEST['id_type_mach'];
        ?>
<input type="hidden" name="id_workshop" value="<? echo $id_workshop ?>" />
<h3> Выберете оборудование :</h3>
<select id="id_machine">
	<option selected value="">---</option>
        <? 
        $query3 = "SELECT * from machines where id_type_mach = $id_type_mach";
	//echo $query;
        $result3 = mysqli_query($conn,$query3) or die(mysqli_error());
            //print_r($result);
        while ($row3 = mysqli_fetch_assoc ($result3)){ ?>
        <option value="<? echo $row3["id_machine"] ?>"><? echo $row3["name_machine"] ?></option>
	<? } ?>
</select>
<input type="button" id="submit2" value="добавить" onclick="f_add_machine()"/>
<? }
	
if(isset($_REQUEST["id_machine"])){
        $id_machine = $_REQUEST["id_machine"];
        
        $query = "INSERT INTO established_m (id_est_m,id_machine,id_workshop,DT,state) VALUES ('{}','{$id_machine}','{$id_workshop}','".date("Y-m-d H:i:s")."','')";
		$result1 = mysqli_query($conn,$query);
        $query = "SELECT * from machines where id_machine = $id_machine";

        $result = mysqli_query($conn,$query) or die(mysqli_error());
        $row = mysqli_fetch_assoc ($result);
        $name_machine = $row["name_machine"];
        echo "<br>",$name_machine," добавлен в ",$text;
} 
?>

<script type="text/javascript">
	
	

</script>
