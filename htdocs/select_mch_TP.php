
<meta charset="utf-8">
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

$id_workshop = $_REQUEST['id_workshop'];
$i=0;
$query = "SELECT * from v_established where id_workshop = '{$id_workshop}' ORDER BY id_est_m ASC";
echo $query;
$result = mysqli_query($conn,$query) or die(mysqli_error());
$check; ?>
<option selected="selected" value="">---</option>
<?
while ($row = mysqli_fetch_assoc ($result)){ if($check!=$row["id_est_m"]){ ?>
<option value="<? $i++; $check = $row["id_est_m"];echo $row["id_est_m"]; ?>"><? echo $row["name_machine"]; ?></option> 
<? } } ?>

