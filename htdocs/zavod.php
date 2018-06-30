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
//echo "НАЧАЛОСЬ СРАВНЕНИЕ <br>";
if (!isset($username)) 
	if(isset($_REQUEST ["username"]))
	{
		$username = $_REQUEST ["username"];
		$passwd = $_REQUEST ["passwd"];     
       // echo $username, " - <br>";
       // echo $passwd, " - <br>";

	} else {
		echo "не заданы пароль и логин";
		if (isset($http_SESSION_VARS["username"]))
			$username = $http_SESSION_VARS["username"];
		else {
		  do_html_header("Проблема:");
		  echo "Вы не можете войти. Для просмотра страницы, Вы должны авторизоваться.";
		  do_html_url("login.php", "Login");
		  do_html_footer();
		  exit;
		}
	}
//echo "ПРОВЕРКА ЛОГИН ПАРОЛЬ";
if ($username && $passwd) //isset($http_SESSION_VARS["username"])&& isset($http_SESSION_VARS["passwd"])
// they have just tried logging in
{
//echo "<br>username ", $username, " ", "passwd ", $passwd, " Crypt_pass ", crypt($passwd,'2r');   
    if (login($username, $passwd))
    {
     $valid_user = $username;
   //   session_register("valid_user");
        session_start();
    } else {
      do_html_header("Проблема:");
      echo "Вы не можете войти. Для просмотра страницы, Вы должны авторизоваться.";
      do_html_url("index.php", "Login");
      do_html_footer();
      exit;
    }      
}
//do_html_header("");
check_valid_user();
if (!isset($username)) $username = $valid_user;
//		document.getElementById("B_chk_adress").style.display = "none";
//		document.getElementById("B_set_adress").style.display = "none";
//		document.getElementById("Nic").value = document.getElementById("h_Nic").value;

/*	echo $GLOBALS['tpers'] = $TypePers;
	echo $GLOBALS['pers']['id_TypePers'];
	echo $GLOBALS['TypePers'] = $TypePers;*/
?>

<? do_html_header("Заводик"); 
$query = "SELECT * from v_users where login = '$username'";
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result);
$id_type = $row["id_type"]; ?>


   
<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="css/style.css">


<table class="form" cellpadding="5" cellspacing="0" VALIGN=TOP ALIGN=LEFT id="main_table">   
<td>
    <input type="button" id="exit" onclick="location.href='index.php';" align="right" value="Выйти"/>
    <h3><? echo "Имя пользователя : ",$username," (",$row["user_type"],")<br>"; ?></h3>
</td>
      <tr>
          <td id="col1">
              <ul class="tab-group">
                  <li class="tab active"><a href="#st_comp" onclick="javascript:ch_param('struct_company','username=<? echo $username; ?>&id_type=<? echo $id_type; ?>','st_comp')">Структура компании</a></li>
                  
                  <li class="tab"><a href="#st_obj" onclick="javascript:ch_param('str_obj','id_company=<? echo $row["id_company"]; ?>','st_obj_info')">Структура объектов</a></li>
                  
                  <li class="tab"><a href="#add_obj" onclick="javascript:ch_param('add_ob','id_company=<? echo $row["id_company"]; ?>','add_new_object')">Добавление объектов</a></li>
                  
                  <li class="tab"><a href="#comp_info">Отчет по компании</a></li>
                  
                  <li class="tab"><a href="#personal_info" onclick="javascript:ch_param('personal_info','id_company=<? echo $row["id_company"]; ?>','tabl_personal_info')">Персонал</a></li>
              </ul>
                        
                   <div class="tab-content">
                       <div id="st_comp"></div>
                       
                       <div id="st_obj">
                           <div onclick="tree_toggle(arguments[0])">
                                <div id="st_obj_info"></div>
                           </div>
                       </div>
                       
                       <div id="add_obj">               
                           <div id="add_new_object"></div>
                       </div>
                        
                       <div id="comp_info"></div>
                        
                       <div id="personal_info">
                           <div id="tabl_personal_info"></div>
                       </div>
                   </div>
          </td>
    </tr>
</table>

<script src="background/dist/particles.min.js"></script>
<canvas class="background"></canvas> 

<script src="CSS_JS.js" type="text/javascript"></script>
<script src="set_div_table_TP.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="WIN_SCRIPT_DRAW.js" type="text/javascript"></script>

<script type="text/javascript">
    var arr = [[]];
    var arr_name = [[]];
    
    
    function Click() {
    //alert("button was clicked");           
    document.getElementById('draw_canvas').innerHTML = '';
    document.getElementById('draw_canvas').innerHTML = '<canvas id="map" width="600" height="350"></canvas>';
    setTimeout(main,300);
}
    
    function Sel_com(){
        
        var i = document.getElementById('count_row').value+'name_factory';
        
        ch_param('select_comp_TP','id_company=<? $query = mysqli_query($conn, "SELECT id_company from v_users where login = '$username'") or die(mysqli_error()); $row = mysqli_fetch_assoc($query); echo row["id_company"]; ?>',i);
    }
    
    function Sel_fac(i){
        
        var Text_sel_fc = document.getElementById(i).value;
        var check = document.getElementById('count_row').value;
        ch_param('select_ws_TP','id_factory='+Text_sel_fc,'name_workshop');
    }
    
    function Sel_ws(i){
        
        var Text_sel_ws = document.getElementById(i).value;
        var check = document.getElementById('count_row').value;
        ch_param('select_mch_TP','id_workshop='+Text_sel_ws,'name_machine');
    }
    
    function NewWindow(mypage, myname, w, h, scroll) {
        LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
        settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
        win = window.open(mypage, myname, settings)
    }

                 //---------------------------------------------------------------------------------
     var smetod = "POST"; //GET &nbsp; &quot;
     var nxt_el = "";
     var nxt_vl = "";
                 //---------------------------------------------------------------------------------
    function update() {
        //	alert("-"+ajax.readyState+"-"+ajax.status+"-");
        //       if (ajax.readyState == 4 || ajax.status == 200) {
        if (ajax.readyState == 4)
            if (ajax.status == 200) {
                document.getElementById(nxt_el).innerHTML = ajax.responseText;
                return;
            }
    }

                //---------------------------------------------------------------------------------
    function startAJAX() {
        var xmlHttp = false;
        if (window.XMLHttpRequest) { // if Mozilla, IE7, Safari etc
            xmlHttp = new XMLHttpRequest();
        } else
            if (window.ActiveXObject) { // if IE
                try {
                    xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {}
                }
            } else xmlHttp = false;
        return (xmlHttp);
    }
                //---------------------------------------------------------------------------------
    function ch_param(prgm, param, upd_elm) {
        if(prgm!="delete"){
            document.getElementById(upd_elm).innerHTML = "<center><img src='img/load.gif' width='50' alt='' /></center>";
            //alert(prgm+ param+ upd_elm);
            nxt_el = upd_elm;
            ajax.onreadystatechange = update;
            ajax.open(smetod, prgm + ".php?" + param, true);
            ajax.send(null);
        } else {
            var r = confirm("Удалить объект?");
            if(r==true){
                document.getElementById(upd_elm).innerHTML = "<center><img src='img/load.gif' width='50' alt='' /></center>";
                //alert(prgm+ param+ upd_elm);
                nxt_el = upd_elm;
                ajax.onreadystatechange = update;
                ajax.open(smetod, prgm + ".php?" + param, true);
                ajax.send(null);
            }
        }
    }
	function f_add_machine(){
		var id_workshop = document.getElementById('id_workshop').value;
		var id_machine  = document.getElementById('id_machine').value;
		console.log('id_workshop='+id_workshop+'&id_machine='+id_machine);
		update_param('choose_machine_from_librirary','id_workshop='+id_workshop+'&id_machine='+id_machine);
		//document.getElementById('type_action2').innerHTML = '';
		
	}
	
	
                //---------------------------------------------------------------------------------
	function update_param(prgm, param) {
            //document.getElementById(upd_elm).innerHTML = "<center><img src='img/load.gif' width='50' alt='' /></center>";
            //alert(prgm+ param+ upd_elm);
            //nxt_el = upd_elm;
		    console.log("prgm = "+prgm+" param = "+param);
            ajax.onreadystatechange = update;
            ajax.open(smetod, prgm + ".php?" + param, true);
            ajax.send(null);
        
    }
	
	function f_select_type_action(){
		
		var type_action = document.getElementById('s_type_action').value;
		//alert(type_action);
		console.log("TYPE ACTION = "+type_action);
		if(type_action == 2){
			var id_workshop = document.getElementById('id_workshop').value;
			ch_param('choose_machine_from_librirary','id_workshop='+id_workshop,'d_type_action');
		}
		if(type_action == 1){
			console.log("IF = 1");
			var id_workshop = document.getElementById('id_workshop').value;
			console.log("ID WORKSHOP = "+id_workshop);
			ch_param('create_new_machine','id_workshop='+id_workshop,'d_type_action');
		}
		//update_param('add_machine','type_action='+type_action);
	}
	function f_select_type_machine(){
		
		var id_type_mach = document.getElementById('id_type_mach').value;
		var id_workshop = document.getElementById('id_workshop').value;
		//alert(type_action);
		//alert('id_type_mach='+id_type_mach+'&id_workshop='+id_workshop);
		update_param('choose_machine_from_librirary','id_type_mach='+id_type_mach+'&id_workshop='+id_workshop);
	}
	
	function f_type_new_machine(){
		console.log("F_TYPE_NEW_MACHINE");
		var id_workshop = document.getElementById('id_workshop').value;
		var s_type_new_machine = document.getElementById('s_type_new_machine').value;
		update_param('create_new_machine','type_new_machine='+s_type_new_machine+'&id_workshop='+id_workshop);
	
	}
	function f_add_new_type_machine(){
		console.log("f_add_new_type_machine");
		var id_workshop = document.getElementById('id_workshop').value;
		var name_new_type_machine = document.getElementById('name_new_type_machine').value;
		update_param('create_new_machine','name_new_type_machine='+name_new_type_machine+'&id_workshop='+id_workshop);
	
	}
	function f_add_new_machine(){
		console.log("f_add_new_machine");
		var id_workshop = document.getElementById('id_workshop').value;
		var s_type_new_machine = document.getElementById('s_type_new_machine').value;
		var name_machine = document.getElementById('name_new_machine').value;
		var changeover_time = document.getElementById('changeover_time_new_machine').value;
		update_param('create_new_machine','s_type_new_machine='+s_type_new_machine+'&id_workshop='+id_workshop+'&name_machine='+name_machine+'&changeover_time='+changeover_time+'&acept_addition=true');
	
	}
                //---------------------------------------------------------------------------------

    ajax = startAJAX();
    
    $('.form').find('input, textarea').on('keyup blur focus', function (e) {
        
        var $this = $(this),
            label = $this.prev('label');
        
        if (e.type === 'keyup') {
            if ($this.val() === '') {
                label.removeClass('active highlight');
            } else {
                label.addClass('active highlight');
            }
        } else if (e.type === 'blur') {
            if( $this.val() === '' ) {
                label.removeClass('active highlight');
            } else {
                label.removeClass('highlight');
            }
        } else if (e.type === 'focus') {
            if( $this.val() === '' ) {
                label.removeClass('highlight');
            }
            else if( $this.val() !== '' ) {
                label.addClass('highlight');
            }
        }
    });
    
    $('.tab a').on('click', function (e) {
        e.preventDefault();
        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');
        
        target = $(this).attr('href');
        $('.tab-content > div').not(target).hide();
        $(target).fadeIn(600);
    });
    
    window.onload = function() {
        
        Particles.init({
            selector: '.background',
            breakpoint: 150,
            maxParticles: 150,
            color: '414a4c',
            connectParticles: true
        }); 
    };
</script>

<? do_html_footer(); ?>





