
<meta charset="utf-8">
<?
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
      return 0; 

$id_company=$_REQUEST['id_company'];

$query = "SELECT * from v_established where id_company = '{$id_company}' ORDER BY id_factory ASC";
echo $query;
$result = mysqli_query($conn,$query) or die(mysqli_error());
$check; ?>
<option selected="selected" value="">---</option>
<?
while ($row = mysqli_fetch_assoc ($result)){ if($check!=$row["id_factory"]){ ?>
                
<option value="<? $check = $row["id_factory"]; echo $row["id_factory"]; ?>"><? echo $row["name_factory"]; ?></option> 

<? } } ?>