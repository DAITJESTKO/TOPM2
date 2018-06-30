<meta charset="UTF-8">


<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;
$id_object = $_REQUEST["id_object"];  // - id объекта
$query1 = "SELECT * from objects where id_object = '$id_object'";
$query = "SELECT * from established_obj where id_object = '$id_object'";
$result = mysqli_query($conn,$query);
$result1 = mysqli_query($conn,$query1);
$row = mysqli_fetch_assoc ($result);
$row1 = mysqli_fetch_assoc ($result1);
$name_object = $row1["name_object"];  // - имя объекта
$quantity_obj = $row["quantity_obj"];  // - количетсво объекта
$type;
$part_obj=$quantity_obj;
$quantity_obj = 1;
while($part_obj>=$quantity_obj){
	//echo "<br>while quantity_obj - ",$quantity_obj;
	if($part_obj%$quantity_obj==0){
		//echo "<br>Деление иф - ",$part_obj%$quantity_obj;
	    $Paral_time=0;                          
        $query = "SELECT MAX(time_tech_oper) AS max_time from v_est_objects where id_object = '$id_object'"; 
        $result = mysqli_query($conn,$query); 
        $row = mysqli_fetch_assoc ($result);
        $max_time = $row["max_time"];
        //echo "MAX TIME - ",$max_time;                           
        $Paral_time = $max_time * ($quantity_obj-$part_obj); 
        //echo "P1 - ",$Paral_time;                           
        $query = "SELECT * from v_est_objects where id_object = '$id_object' ORDER BY priority_tech_oper ASC"; 
        $result = mysqli_query($conn,$query); 
		$i=1;
		if($i==1){
			$query3 = "SELECT * from v_established where id_est_m = '$id_est_m'"; 
			$result3 = mysqli_query($conn,$query3); 
			$row3 = mysqli_fetch_assoc($result3); 
			$Paral_time = $Paral_time + $row3["changeover_time"];
			$Smesh_time = $Smesh_time + $row3["changeover_time"];
			$i=0;
			}	
        while($row = mysqli_fetch_assoc ($result)){
            //echo "ROW - ",$row["time_tech_oper"]; 
        $Paral_time = $Paral_time + $row["time_tech_oper"]*$part_obj;
            //echo "PW - ",$Paral_time; 
			
        }
		if($Paral_time<=$min_time) {$min_time = $Paral_time; $opt_part = $quantity_obj; $type = " Параллельное ";}
		if ($quantity_obj==1){ $min_time = $Paral_time; $opt_part = $quantity_obj; $type = " Параллельное ";}
	
	
	
	
        $query = "SELECT MAX(priority_tech_oper) AS quantity_oper from v_est_objects where id_object = '$id_object'"; 
        $result = mysqli_query($conn,$query); 
        $row = mysqli_fetch_assoc($result);
        $quantity_oper = $row["quantity_oper"];
        $Smesh_time=0;
        for($i=1;$i<$quantity_oper;$i++){ 
            $query1 = "SELECT time_tech_oper from v_est_objects where priority_tech_oper = '$i' AND id_object = '$id_object'"; 
            $result1 = mysqli_query($conn,$query1); 
            $row1 = mysqli_fetch_assoc($result1);
            $j = $i+1;           
            $query2 = "SELECT time_tech_oper from v_est_objects where priority_tech_oper = '$j' AND id_object = '$id_object'"; 
            $result2 = mysqli_query($conn,$query2); 
            $row2 = mysqli_fetch_assoc($result2);
            if($j!=$quantity_oper){
            if($row2["time_tech_oper"]>$row1["time_tech_oper"]){
                $Smesh_time = $Smesh_time + $row1["time_tech_oper"]*$part_obj;
                //echo "<br>Smesh1 - ",$Smesh_time;
            }else {
                $Smesh_time = $Smesh_time + $row1["time_tech_oper"]*$quantity_obj - ($quantity_obj-$part_obj)*$row2["time_tech_oper"];
            }
            }else {
                
                //echo "<br>last - ",$quantity_obj-$part_obj;
                //echo "<br>ROW2 - ",$row2["time_tech_oper"];
                //echo "<br>Smesh2 - ",$Smesh_time;
                if($row2["time_tech_oper"]<$row1["time_tech_oper"]){
                $Smesh_time = $Smesh_time + $row1["time_tech_oper"]*$quantity_obj;
                $Smesh_time = $Smesh_time + $row2["time_tech_oper"]*$part_obj;}
                else {
                    $Smesh_time = $Smesh_time + $row1["time_tech_oper"]*$part_obj;
                $Smesh_time = $Smesh_time + $row2["time_tech_oper"]*$quantity_obj;
                }
               
            }
            //echo "<br>Smesh - ",$Smesh_time;
			
        }
		if($Smesh_time<=$min_time) {$min_time = $Smesh_time; $opt_part = $quantity_obj; $type = " Непрерывное ";}
}
	$quantity_obj++;
	
}
	?> <h3><? echo "<br>Минимальное время : ",$min_time," <br>Оптимальная партия : ",$opt_part," <br>Тип производства : ",$type; ?></h3>