<meta charset="utf-8">

<?
//require_once("for_form.php"); 
//do_html_header("");
//check_valid_user();
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;

$username = $_REQUEST["username"];
$id_type = $_REQUEST["id_type"];

    $query = mysqli_query($conn, "SELECT * from company where id_company = (SELECT id_company from v_users where login = '$username')") or die(mysqli_error()); $row = mysqli_fetch_assoc($query);
             ?>
      <form metod="post" class="Container" action="add_factory.php?id_company=<? echo $row["id_company"]; ?>" onsubmit="NewWindow(this.action,'name','600','600','yes');return false"> 
                    <div onclick="tree_toggle(arguments[0])" style="float: left;">
                        <div class="Content">
                            <h3><? echo "Структура компании ",chr(34),$row["name_company"],chr(34); ?></h3>
                        </div>
                        <? $result = mysqli_query($conn, "SELECT * from factory where id_company = (SELECT id_company from v_users where login = '$username')") or die(mysqli_error()); ?>
                            <ul class="Container">
                                <form></form>
                <? while ($row = mysqli_fetch_assoc ($result)){ ?>
                          <form metod="post" class="Container" action="add_workshop.php?id_factory=<? echo $row["id_factory"]; ?>" onsubmit="NewWindow(this.action,'name','600','600','yes');return false">
                                            
                               <li class="Node IsRoot ExpandOpen" id="<? echo $row["id_factory"]; ?>factory">
                                    <div class="Expand"></div>
                                     <div class="Content">
                                      <? echo $row["name_factory"]; ?>
                                            <input type="hidden" name="id_factory" value="<? $rowi=mysqli_fetch_assoc (mysqli_query ($conn, "SELECT * from factory where name_factory='". $row["name_factory"]."' ")); echo $rowi["id_factory"]; ?>">
                                               <?
                                            $free_row = $row["name_factory"];
    $query = "SELECT name_workshop,id_workshop from workshops where id_factory = (SELECT id_factory from factory where name_factory = '$free_row')";
                                            $result1 = mysqli_query($conn,$query) or die(mysqli_error());
                                            ?>
                                                <? if($id_type==1 || $id_type==2) { ?><input type="button" name="b_delete" value="-" onclick="ch_param('delete','id_factory=<? echo $row["id_factory"]; ?>','<? echo $row["id_factory"]; ?>factory');"/><? } ?>
                                                    <input type="button" name="start_simulation" value="симуляция" onclick="NewWindow('start_simulation.php?id_factory=<? echo $row["id_factory"]; ?>','name','1920','1080','yes');return false"/>
                                                </div>
                                                <ul class="Container">
                                                    <form></form>
                                                    <? while ($row1 = mysqli_fetch_assoc ($result1)){ ?>
                                                        <form metod="post" class="Container" action="javascript:ch_param('add_machine','id_workshop=<? echo $row1["id_workshop"] ?>','add_new_elem_str')" onsubmit="this.action">
                                                            <li class="Node ExpandClosed" id="<? echo $row1["id_workshop"]; ?>workshop">
                                                                <div class="Expand"></div>
                                                                <div class="Content">
                                                                    <? echo $row1["name_workshop"] ?>
                                                                        <input type="hidden" name="id_workshop" value="<? echo $row1["id_workshop"]; ?>">
                                                                <? if($id_type==1 || $id_type==2) { ?><input type="button" name="b_delete" value="-" onclick="ch_param('delete','id_workshop=<? echo $row1["id_workshop"]; ?>','<? echo $row1["id_workshop"]; ?>workshop');"/> <? } ?>
                                                                </div>
                                                                <? 
                                                $query = "SELECT * from v_established where name_workshop = '" . $row1["name_workshop"]."'";
                                                     $result2 = mysqli_query($conn,$query) or die(mysqli_error()); ?>
                                                                    <ul class="Container">
                                                                        <? while ($row2 = mysqli_fetch_assoc ($result2)){ ?>
                                                                            <li class="Node ExpandLeaf" id="<? echo $row2["id_est_m"]; ?>id_est_m">
                                                                                <div class="Expand"></div>
                                                                                <div class="Content">
                                                                                    <? echo "<b>",$row2["name_machine"],"</b>";
                                                                                    $id_est_m_sel = $row2["id_est_m"];
                                                                                     if($id_type==1 || $id_type==2) { ?>
                                                                                    <input type="button" name="b_delete" value="-" onclick="ch_param('delete','id_est_m=<? echo $row2["id_est_m"]; ?>','<? echo $row2["id_est_m"]; ?>id_est_m');"/>
                                                                                    <? } ?>
                                                                                </div>
                                                                            </li>
                                                                            <? } ?>
                                                                        <? $query_c = mysqli_query($conn, "SELECT id_type from v_users where login = '$username'") or die(mysqli_error()); $row = mysqli_fetch_assoc($query_c); if($id_type==1 || $id_type==2){ ?> 
                                                                                <li class="Node ExpandLeaf IsLast">
                                                                                    <div class="Expand"></div>
                                                                                    <div class="Content">
                                                                                        <input type="submit" name="submit_m" value="Добавить оборудование" />
                                                                                    </div>
                                                                                </li> <? } ?>
                                                                    </ul>
                                                            </li>
                                                        </form>
                                                        <? } ?>
                                                    <? $query_c = mysqli_query($conn, "SELECT id_type from v_users where login = '$username'") or die(mysqli_error()); $row = mysqli_fetch_assoc($query_c); if($id_type==1 || $id_type==2){ ?> 
                                                            <li class="ExpandLeaf">
                                                                <div class="Content">
                                                                    <input type="submit" name="submit_w" value="Добавить цех" />
                                                                </div>
                                                            </li> <? } ?>
                                                </ul>
                                                
                                            </li>
                                        </form>
                                        <? } ?>
                                    
                                </ul>
                            <? $query_c = mysqli_query($conn, "SELECT id_type from v_users where login = '$username'") or die(mysqli_error()); $row = mysqli_fetch_assoc($query_c); if($id_type==1 || $id_type==2){ ?> 
                            <li class="ExpandLeaf">
                                                               <div class="Content">
                                                                    <input type="submit" name="submit_w" value="Добавить филлиал" />
                                                                </div>
                                                            </li> <? } ?>
                        </div>
		  <div id="add_new_elem_str" style="float: left;margin: 0 0 0 20px"></div>
            </form>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>            