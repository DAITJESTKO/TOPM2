<meta charset="UTF-8">
<?
require_once("bookmark_fns.php"); 
session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

$id_workshop = $_REQUEST["id_workshop"];
//echo "ID WORKSHOP = ",$id_workshop;
if(isset($_REQUEST["name_new_type_machine"])){
	$name_new_type_machine = $_REQUEST["name_new_type_machine"];
	$query = "INSERT INTO type_machines (id_type_mach,name_type_mach) values ('{}','{$name_new_type_machine}')";
	//echo "Добавление новго типа объекта - ",$query;
	mysqli_query($conn,$query);
	$query = "SELECT MAX(id_type_mach) as max from type_machines";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result);
	$_REQUEST["type_new_machine"] = $row["max"];
	//echo "<br>НОВЫЙ РЕКУЕСТ = ",$_REQUEST["type_new_machine"];?>

	<h3>Выберете тип нового оборудования :</h3>
    <select id="s_type_new_machine" onChange="f_type_new_machine()">
	<option <? if(!isset($_REQUEST["type_new_machine"]))echo "selected"; ?> value="">---</option>
	<option <? if(isset($_REQUEST["type_new_machine"]) && $_REQUEST["type_new_machine"] == 0) echo "selected"; ?> value="0">создать новый тип</option>
	<?
	$query = "SELECT * from type_machines";
	$result = mysqli_query($conn,$query);
	
	while($row = mysqli_fetch_assoc($result)){ ?>
		<option <? if(isset($_REQUEST["type_new_machine"]) && $_REQUEST["type_new_machine"] == $row["id_type_mach"]) echo "selected"; ?> value="<? echo $row["id_type_mach"]; ?>"><? echo $row["name_type_mach"]; ?></option>
	<? } ?>
</select>
<?
}else {
?>

<h3>Выберете тип нового оборудования :</h3>
<select id="s_type_new_machine" onChange="f_type_new_machine()">
	<option <? if(!isset($_REQUEST["type_new_machine"]))echo "selected"; ?> value="">---</option>
	<option <? if(isset($_REQUEST["type_new_machine"]) && $_REQUEST["type_new_machine"] == 0) echo "selected"; ?> value="0">создать новый тип</option>
	<?
	$query = "SELECT * from type_machines";
	$result = mysqli_query($conn,$query);
	
	while($row = mysqli_fetch_assoc($result)){ ?>
		<option <? if(isset($_REQUEST["type_new_machine"]) && $_REQUEST["type_new_machine"] == $row["id_type_mach"]) echo "selected"; ?> value="<? echo $row["id_type_mach"]; ?>"><? echo $row["name_type_mach"]; ?></option>
	<? } ?>
</select> 

<? }

if(isset($_REQUEST["type_new_machine"])){
	if($_REQUEST["type_new_machine"] == 0){ ?>
<h3>Название нового типа обордуования :</h3>
<input type="text" id="name_new_type_machine" required/>
<input type="button" value="добавить" id="b_add_new_type_machine" onclick="javascript:f_add_new_type_machine()"/>
<? }else{ ?>
<h3>Название машины </h3>
<input type="text" id="name_new_machine" required/>
<h3>Время переналадки (мин.) </h3>
<input type="text" id="changeover_time_new_machine" required/>
<input type="button" value="добавить" id="b_add_new_type_machine" onclick="javascript:f_add_new_machine()"/>
<? } }
if(isset($_REQUEST["acept_addition"]) && $_REQUEST["acept_addition"]=='true'){
    $id_workshop = $_REQUEST["id_workshop"];
	$id_type_mach = $_REQUEST["s_type_new_machine"];
	$name_machine = $_REQUEST["name_machine"];
	$changeover_time = $_REQUEST["changeover_time"];
	$query = "INSERT INTO machines (id_machine,id_type_mach,name_machine,changeover_time) values ('{}','{$id_type_mach}','{$name_machine}','{$changeover_time}')";
	mysqli_query($conn,$query);
	$query = "SELECT MAX(id_machine) as max from machines";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result);
	$id_machine = $row["max"];
	$query = "INSERT INTO established_m (id_est_m,id_machine,id_workshop,DT,state,X_machine,Y_machine) values ('{}','{$id_machine}','{$id_workshop}','".date("Y-m-d H:i:s")."','{}','{100}','{100}')";
	mysqli_query($conn,$query);
}
?>

