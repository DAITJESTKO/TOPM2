<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

$id_company = $_REQUEST["id_company"];

//echo "id_company = ",$id_company;

$query ="SELECT * from v_users where id_company = '$id_company'";
$result = mysqli_query($conn,$query);
?>
<h3>Персонал компании</h3>
<table id="TABLE_PERS_INFO" border="1" width="200" >
    <tbody>
        <tr>
            <td id="colt">
                 №
            </td>
            <td id="colt">
                Имя
            </td>
            <td id="colt">
                Тип пользователя
            </td>
        </tr>
<?
        $i=1;
while($row = mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td id="colt">
                <? echo $i; ?>
            </td>
            <td id="colt">
                <? echo $row["login"]; ?>
            </td>
            <td id="colt">
                <? echo $row["user_type"]; ?>
            </td>
        </tr>
        <? $i++; } ?>
    </tbody>
</table>

<style type="text/css">
     #colt {
      background: #b9b5b2;
    }
</style>