<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css">
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;
$id_company=$_REQUEST["id_company"];
$query = "SELECT * from family where id_company = '$id_company'order by id_parent";
$result = mysqli_query($conn,$query);

//echo "id_company = ",$id_company;

$array = array();
 while($row = mysqli_fetch_assoc($result)){
    $id_family = $row["id_family"];
    //echo "id_family = ",$id_family,"<br>";
    $id_parent = $row["id_parent"];
    //echo "id_parent = ",$id_parent,"<br>";
    $id_est_object = $row["id_est_object"];
    //echo "id_est_object = ",$id_est_object,"<br>";
    $query1 = "SELECT * from v_est_objects where id_est_object = '$id_est_object' ";
    $result1 = mysqli_query($conn,$query1);
    $row1 = mysqli_fetch_assoc($result1);
    $name_object = $row1["name_object"];
    //echo "name_object = ",$name_object,"<br>";
    $array[] = array('id'=>$id_est_object, 'pid'=>$id_parent, 'name'=>$name_object);
}
$query = "SELECT * from family order by id_parent";
$result = mysqli_query($conn,$query);

$tree = array();

 ?> 

<h3>Структура объектов производства</h3>
<table id="T_STR_OBJ" style="background: rgba(19, 35, 47, 0.5);margin: 0 0 0 27px;">
    <tbody>
<tr> 
    <td id="str_obj_td_1" style="float: left; margin: 0 0 0 25px;">
<h1>Дерево объектов</h1> 
    <?
foreach ($array as $row) {
    $tree[(int) $row['pid']][] = $row;
}

function treePrint($tree, $pid) {
    if (empty($tree[$pid]))
        return;
    
     if (isset($tree[$row['id']])){echo '<ul class="Node Container">';}else echo '<ul class="Container">';
    foreach ($tree[$pid] as $k => $row) {
        //echo $row['id'];
        if (isset($tree[$row['id']])){
        echo '<li class="Node IsRoot ExpandOpen"><div class="Expand"></div><div class="Content">';
        echo $row['name'];
            ?><input type="button" name="start_simulation" value="симуляция" onclick="NewWindow('start_simulation.php?id_est_object=<? echo $row['id']; ?>','name','1920','1080','yes');return false"/>
<input type="button" id="TP_info_simulation" value="Открыть ТП" onclick="ch_param('select_ob','id_est_object=<? echo $row['id']; ?>','str_obj_td_2'); Click();"/>
<?
        echo '</div>';
            treePrint($tree, $row['id']);
        }else{
            if($row['pid']==0){
                 
                echo '<li class="Node IsRoot ExpandOpen"><div class="Expand"></div><div class="Content">';
                echo $row['name'];
?><input type="button" name="start_simulation" value="симуляция" onclick="NewWindow('start_simulation.php?id_est_object=<? echo $row['id']; ?>','name','1920','1080','yes');return false"/>
        <input type="button" id="TP_info_simulation" value="Открыть ТП" onclick="ch_param('select_ob','id_est_object=<? echo $row['id']; ?>','str_obj_td_2'); Click(); "/>
        <?
                echo '</div>';
                treePrint($tree, $row['id']);
                continue;
            }
            echo '<li class="Node ExpandLeaf IsLast"><div class="Expand"></div><div class="Content">';
            echo $row['name'];
?><input type="button" name="start_simulation" value="симуляция" onclick="NewWindow('start_simulation.php?id_est_object=<? echo $row['id']; ?>','name','1920','1080','yes');return false"/>
        <input type="button" class="TP_info_simulation" value="Открыть ТП" onclick="ch_param('select_ob','id_est_object=<? echo $row['id']; ?>','str_obj_td_2'); Click();"/>
        <?
            echo '</div>';
        }
        echo '</li>';
    }
    echo '</ul>';
}

treePrint($tree,0);

?>
</td>
    
<td id="str_obj_td_2" style="float: left; margin: 0 0 0 50px;">
        <h1>Технологический процесс изготовления изделия</h1>         
        <div id="info_TP" style="height : 350"></div>                
                    
</td>
<td id="str_obj_td_3" style="float: left; margin: 0 0 0 50px;">
        <h1>Диаграмма Ганта</h1>
        <div id="draw_canvas" style="float: left;">
            
            <canvas id="map" width="600" height="350"></canvas>
        </div>
           
</td>
    
</tr>
    </tbody>
    </table>
<script src="CSS_JS.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="WIN_SCRIPT_DRAW.js" type="text/javascript"></script>
<script type="text/javascript">
    
function Click() {
    alert("button was clicked");           
    document.getElementById('draw_canvas').innerHTML = '';
    document.getElementById('draw_canvas').innerHTML = '<canvas id="map" width="600" height="350"></canvas>';
    setTimeout(main,300);
}
    

</script>