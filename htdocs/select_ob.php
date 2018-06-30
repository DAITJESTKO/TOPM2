<meta charset="utf-8">
<h1>Технологический процесс изготовления изделия</h1> 
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;
$id_est_object = $_REQUEST['id_est_object'];
$query = "SELECT * from established_obj where id_est_object = '$id_est_object'";
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_assoc ($result);
$id_object = $row["id_object"];
$query1 = "SELECT * from objects where id_object = '$id_object'";
$query = "SELECT * from established_obj where id_object = '$id_object'";
$result = mysqli_query($conn,$query);
$result1 = mysqli_query($conn,$query1);
$row = mysqli_fetch_assoc ($result);
$row1 = mysqli_fetch_assoc ($result1);
$name_object = $row1["name_object"];
$quantity_obj = $row["quantity_obj"];
//echo $quantity_obj;
$id_group_tech_proc = $row["id_group_tech_proc"];
$part_obj = $row["part_obj"];
//echo $part_obj;
$query = "SELECT * from v_est_objects where id_object = '$id_object' ORDER BY priority_tech_oper ASC";
?> <h3><? echo "<br> Объект : (",$name_object,") количество : ",$quantity_obj," штук, партийнсоть : ",$part_obj; ?> </h3> <?

 ?> 

<div id="D_TABLE_TP_DEL" style="float: left;">
    <input type="hidden" id="quantity_obj" value="<? echo $quantity_obj; ?>"/>
    <input type="hidden" id="part_obj" value="<? echo $part_obj; ?>"/>
    <? $query2 = "SELECT MAX(priority_tech_oper) AS quantity_tech_oper from tech_processes where id_group_tech_proc = '$id_group_tech_proc'"; 
	$result2 = mysqli_query($conn,$query2); $row2 = mysqli_fetch_assoc ($result2); ?>
    <input type="hidden" id="quantity_tech_oper" value="<? echo $row2["quantity_tech_oper"]; ?>"/>
    <table id="TABLE_TP" border="1">
    <tr>
        <td id="colt1">№</td>
        <td id="colt2">Операция</td>
        <td id="colt3">Оборудование</td>
        <td id="colt4">Время операции(мин.)</td>
        <td id="colt5">Цех</td>
        
    </tr>

<? $i=0;$result = mysqli_query($conn,$query);  while ($row = mysqli_fetch_assoc ($result)){ ++$i;?>
    <tr>
        <td id="colt1"><? echo $row["priority_tech_oper"]; ?> <input type="hidden" id="priority_tech_oper<? echo $i; ?>" value="<? echo $row["priority_tech_oper"]; ?>"/> </td>
        <td id="colt2"><? echo $row["name_tech_oper"]; ?> <input type="hidden" id="name_tech_oper<? echo $i; ?>" value="<? echo $row["name_tech_oper"]; ?>"/> </td>
        <td id="colt3"><? echo $row["name_machine"]; ?> <input type="hidden" id="name_machine<? echo $i; ?>" value="<? echo $row["name_machine"]; ?>"/> </td>
        <td id="colt4"><? echo $row["time_tech_oper"]; ?><input type="hidden" id="time_tech_oper<? echo $i; ?>" value="<? echo $row["time_tech_oper"]; ?>"/> </td>
        <td id="colt5"><? echo $row["name_workshop"]; ?><input type="hidden" id="name_workshop<? echo $i; ?>" value="<? echo $row["name_workshop"]; ?>"/> </td>
		<input type="hidden" id="changeover_time<? echo $i; ?>" value="<? $id_est_m = $row["id_est_m"]; $query3 = "SELECT * from v_established where id_est_m = '$id_est_m'"; $result3 = mysqli_query($conn,$query3); $row3 = mysqli_fetch_assoc($result3); echo $row3["changeover_time"]; ?>"
        
    </tr>
<? } ?>
    
       
    <tr>
        <td id="colt1">Время изготовления</td>
        <td id="Posl_time" value="<? 
								  $result = mysqli_query($conn,$query); $i=1;
								  while ($row = mysqli_fetch_assoc ($result)){
									  $Posl_time = $Posl_time + $row["time_tech_oper"]; 
									  if($i==1){
									  $id_est_m = $row["id_est_m"]; 
									  $query3 = "SELECT * from v_established where id_est_m = '$id_est_m'"; 
									  $result3 = mysqli_query($conn,$query3); 
									  $row3 = mysqli_fetch_assoc($result3); 
									  $Posl_time = $Posl_time + $row3["changeover_time"];
								      $i=0;
									  }
									  //$Posl_time = $Posl_time + $row3["changeover_time"];
									  //echo $row3["changeover_time"];
								  } 
								   $Posl_time = $Posl_time * $quantity_obj;
								   echo $Posl_time; 
								  ?>"><p style="color:#4B0082">Последовательное<br><? echo $Posl_time; ?> мин.</p></td>
        <td id="Paral_time" value="
        <?
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
        while($row = mysqli_fetch_assoc ($result)){
			$id_est_m = $row["id_est_m"]; 
			$query3 = "SELECT * from v_established where id_est_m = '$id_est_m'"; 
			$result3 = mysqli_query($conn,$query3); 
			$row3 = mysqli_fetch_assoc($result3); 
            //echo "ROW - ",$row["time_tech_oper"]; 
            $Paral_time = $Paral_time + $row["time_tech_oper"]*$part_obj;
			if($i==1){
			$query3 = "SELECT * from v_established where id_est_m = '$id_est_m'"; 
			$result3 = mysqli_query($conn,$query3); 
			$row3 = mysqli_fetch_assoc($result3); 
			$Paral_time = $Paral_time + $row3["changeover_time"];
			$i=0;
			}
			
            //echo "PW - ",$Paral_time; 
        }                                      
        ?>"><p style="color:#0000ff">Параллельное<br><? echo $Paral_time; ?> мин.</p></td>
        <td id="Smesh_time" value="<?
        $query = "SELECT MAX(priority_tech_oper) AS quantity_oper from v_est_objects where id_object = '$id_object'"; 
        $result = mysqli_query($conn,$query); 
        $row = mysqli_fetch_assoc($result);
        $quantity_oper = $row["quantity_oper"];
		$query = "SELECT * from v_est_objects where id_object = '$id_object' ORDER BY priority_tech_oper ASC"; 
        $result = mysqli_query($conn,$query); 
		$row = mysqli_fetch_assoc ($result);						   
		$id_est_m = $row["id_est_m"]; 
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
                echo "<br>Smesh1 - ",$Smesh_time;
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
			if($i==1){
			$query3 = "SELECT * from v_established where id_est_m = '$id_est_m'"; 
			$result3 = mysqli_query($conn,$query3); 
			$row3 = mysqli_fetch_assoc($result3); 
			$Smesh_time = $Smesh_time + $row3["changeover_time"];
			}
			
        }
         //echo $Smesh_time;            
        ?>"><p style="color:#ff0000">Непрерывное<br><? echo $Smesh_time; ?> мин.</p></td>
        
    </tr>
        
    </table>
    
<input type="button" name="b_delete" value="удалить объект" onclick="ch_param('delete','id_object=<? echo $id_object; ?>','D_TABLE_TP_DEL');"/>
<input type="button" name="b_opt_part_obj" value="установить оптимальную партию" onclick="ch_param('s_opt_part_obj','id_object=<? echo $id_object; ?>','D_TABLE_TP_DEL');"/>

</div>
<script src="WIN_SCRIPT_DRAW.js" type="text/javascript"></script>

<style type="text/css">
    #D_TABLE_TP_DEL {
                width:600px;
                /* Ширина таблицы */
            }
    TD {
                vertical-align: top;
                /* Выравнивание по верхнему краю ячейки */
            }
    #colt1 {
                width: 10%;
                /* Ширина первой колонки */
                background: #b9b5b2;
                /* Цвет фона первой колонки */
            }
    #colt2 {
                width: 25%;
                background: #b9b5b2;
                /* Цвет фона второй колонки */
            }
    #colt3 {
                width: 25%;
                /* Ширина третьей колонки */
                background: #b9b5b2;
                /* Цвет фона третьей колонки */
            }
    #colt4 {
                width: 25%;
                /* Ширина третьей колонки */
                background: #b9b5b2;
                /* Цвет фона третьей колонки */
            } 
    #colt5 {
                width: 75%;
                /* Ширина третьей колонки */
                background: #b9b5b2;
                /* Цвет фона третьей колонки */
            }
    #Paral_time {
                width: 25%;
                /* Ширина третьей колонки */
                background: #b9b5b2;
                /* Цвет фона третьей колонки */
            }
    #Posl_time {
                width: 25%;
                /* Ширина третьей колонки */
                background: #b9b5b2;
                /* Цвет фона третьей колонки */
            }
    #Smesh_time {
                width: 25%;
                /* Ширина третьей колонки */
                background: #b9b5b2;
                /* Цвет фона третьей колонки */
            }
</style>


    
