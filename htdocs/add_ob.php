<meta charset="utf-8">
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

$id_company = $_REQUEST["id_company"];
//echo "id_company = ",$id_company;

$i=0;
?>

<h3>Добавление нового объекта:</h3>
<div id="D_INFO">
<table id="TABLE_TP" border="1">
    <tr>
        <td id="colt">Имя объекта</td>
        <td id="colt">Общее количество объектов</td>
        <td id="colt">Партийность</td>
        <td id="colt">Родительский объект</td>
    </tr>
    <tr>
        <td id="colt"><input type="text" id="name_object" placeholder="name_object" required /></td>
        <td id="colt"><input type="text" id="quantity_object" placeholder="quantity_object" required /></td>
        <td id="colt"><input type="text" id="part_object" placeholder="part_object" required /></td>
        <td id="colt_id_parent">
        <select id="id_parent">
            <option selected="true" value="0">---</option>
        <? 
        $query1 = "SELECT * from v_est_objects where id_company = $id_company ORDER BY id_object ASC ";
            echo $query;
        $result1 = mysqli_query($conn,$query1) or die(mysqli_error());
            //print_r($result);
        $check1; while ($row1 = mysqli_fetch_assoc ($result1)){ if($check1!=$row1["id_est_object"]){ ?>
        <option value="<? $check1 = $row1["id_est_object"];echo $row1["id_est_object"]; ?>"><? echo $row1["name_object"] ?></option>  
            <? } } ?>
        </select>
        </td>
    </tr>
</table>

    
<h3>Тех процесс для нового объекта :</h3>


<input type="hidden" id="count_row" value="1"/>
<input type="hidden" id="id_company" value="<? echo $id_company; ?>"/>
    
<table id="TABLE_TP" border="1">
    <tr>
        <td id="colt">№</td>
        <td id="colt">Операция</td>
        <td id="colt">Филлиал</td>
        <td id="colt">Цех</td>
        <td id="colt">Оборудование</td>
        <td id="colt">Время операции</td>
    </tr>
    <tbody id="ROW_TP">
         </tbody>
    <tr id="L_ROW_TP">
        <td id="colt_change_numb">1</td>
        <td id="colt_change_name">
    <input id="name_oper" type="text" name="name_oper" onchange="Sel_fac('1name_factory')" placeholder="name_oper" value="" required />
        </td>
        <td id="colt_change_fac">            
        <select id="name_factory" onchange="Sel_fac('name_factory')">
            <option selected="selected" value="">---</option>
<? $query = "SELECT * from v_established where id_company = '{$id_company}' ORDER BY id_factory ASC";
echo $query;
$result = mysqli_query($conn,$query) or die(mysqli_error());
$check;
while ($row = mysqli_fetch_assoc ($result)){ if($check!=$row["id_factory"]){ ?>
<option value="<? $check = $row["id_factory"]; echo $row["id_factory"]; ?>"><? echo $row["name_factory"]; ?></option> 
<? } } ?>
        </select>
        </td>
        <td id="colt_change_ws">
          <div id="div_fixed" width="50">
          <select id="name_workshop" onchange="Sel_ws('name_workshop')">
             <option selected="selected" value="">---</option>
            </select>
              </div>
        </td>
        <td id="colt_change_mch">
            <div id="div_fixed" width="50">
            <select id="name_machine">
            <option selected="selected" value="">---</option>
            </select>
            </div>
        </td>
        <td id="colt_change_time">
            <div id="div_fixed" width="50">
            <input id="time_oper" type="text" name="time_oper" placeholder="time_oper" value="" required />
            </div>
        </td>
    </tr>
        
</table>
        


<input type="button" id="b_plus" value="Добавить строку" onclick="set('ROW_TP',1)" />
<input type="button" id="b_minus" value="Удалить строку" onclick="set('ROW_TP',-1)" />
<input type="button" id="b_submit" value="Сохранить новый объект" onclick="send_TP(<? echo $id_company ?>)" />
        </div>

<style type="text/css">
    #D_INFO {
                width:auto;
                /* Ширина таблицы */
            }
        </style>

