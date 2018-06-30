<?
//require_once("for_form.php"); 
//do_html_header("");
//check_valid_user();
require_once("bookmark_fns.php"); 
  session_start();

if (!isset($username)) 
	if(isset($_REQUEST ["username"]))
	{
		$username = $_REQUEST ["username"];
		$passwd = $_REQUEST ["passwd"];
	} else {
//		echo "не заданы пароль и логин";
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
if ($username && $passwd) //isset($http_SESSION_VARS["username"])&& isset($http_SESSION_VARS["passwd"])
// they have just tried logging in
{
//echo $username, " ", $passwd;
    if (login($username, $passwd))
    {
      $valid_user = $username;
      session_register("valid_user");
    } else {
      do_html_header("Проблема:");
      echo "Вы не можете войти. Для просмотра страницы, Вы должны авторизоваться.";
      do_html_url("login.php", "Login");
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
<form name=ulaForm method="POST" >
<?
$TypePers = f_get_TypePers($username);
$TabNum = f_get_TabNum($username);
?><input name="tp" type="hidden" id="tp" value="<? echo $TypePers; ?>" /><?
$GLOBALS['pers'] = f_get_Pers($username);
$u_IP = $_SERVER["REMOTE_ADDR"];
?>
<link rel="stylesheet" href="selena.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="menu.css">
<script type="text/javascript">

//<script language="JavaScript" type="text/javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
var TODAY = d.getDate() + "-е " + monthname[d.getMonth()] + ", " + d.getFullYear()+"г.";
var TODAY2 = d.getFullYear() +"-"+ (1*d.getMonth()+1) + "-" + d.getDate();
//var TODAY3 = "&laquo;<u>"+d.getDate() + "</u>&raquo; <u> " + monthname[d.getMonth()] + " </u> " + d.getFullYear()+"г.";
var TODAY3 = "'<u> "+d.getDate() + " </u>' <u> " + monthname[d.getMonth()] + " </u> " + d.getFullYear()+"г.";
var smetod = "POST"; //GET &nbsp; &quot;
var nxt_el = "";
var nxt_vl = "";
var conn = 0;
var sel_bld = 0;
//---------------   END LOCALIZEABLE   ---------------
function $() {
    var elements = new Array();
    for (var i = 0; i < arguments.length; i++) {
        var element = arguments[i];
        if (typeof element == 'string')
            element = document.getElementById(element);
        if (arguments.length == 1)
            return element;
        elements.push(element);
    }
    return elements;
}
// Пример использования:
//var obj1 = document.getElementById('element1');
//var obj2 = document.getElementById('element2');
//function alertElements() {
//  var i;
//  var elements = $('a','b','c',obj1,obj2,'d','e');
//  for (i=0;i < elements.length;i++) { alert(elements[i].id); }	window.onerror = handleError; // safety net to trap all errors
//---------------------------------------------------------------------------------
//var divs = document.getElementsByTagName('div')
//    var l = divs.length
//    for(var i=0; i<l; i++){
//        alert(divs[i].innerHTML.name)//className
//        if(divs[i].className == 'tab'){
//            var name = divs[i].innerHTML
//            divs[i].innerHTML = "<div class='tab-right'><div class='tab-center'>"+name+"</div></div>"
//            divs[i].className = 'tab-left'            
//        }
//    }
//---------------------------------------------------------------------------------
function toggle(obj) {
var el = document.getElementById(obj);
//el.style.display = ( el.style.display != 'none' )? 'none' : '';
el.style.display = ( el.style.display != 'none' ) && 'none' || '';
}
//---------------------------------------------------------------------------------
	function handleError(message, URI, line) {
		// alert the user that this page may not respond properly write_temp
		alert(message+" "+ URI +" в строке - "+ line);
		return true; // this will stop the default message
	}
//---------------------------------------------------------------------------------
    function upd_(){
        if (ajax.readyState == 4)
            if (ajax.status == 200) {
                document.getElementById(nxt_el).innerHTML = document.getElementById(nxt_vl).value;
            }
    }
//---------------------------------------------------------------------------------
    function update(){
//	alert("-"+ajax.readyState+"-"+ajax.status+"-");
//       if (ajax.readyState == 4 || ajax.status == 200) {
        if (ajax.readyState == 4)
            if (ajax.status == 200) {
        		document.getElementById(nxt_el).innerHTML = ajax.responseText;
				return;
			}
    }
//---------------------------------------------------------------------------------
    function startAJAX (){
        var xmlHttp = false;
        if (window.XMLHttpRequest) {// if Mozilla, IE7, Safari etc
            xmlHttp = new XMLHttpRequest();
        } else
            if (window.ActiveXObject){ // if IE
                try {
                    xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e){
                    try{
                        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e){}
                }
            } else xmlHttp = false;
        return (xmlHttp);
    }
//---------------------------------------------------------------------------------
	function write_temp(txt_msg) {
		document.getElementById("temp_area").innerHTML = txt_msg+"  " + '<input name="B_clr_temp" type="button" onclick="clr_temp();" value="Очистить" />';
		return;
	}
//---------------------------------------------------------------------------------
	function clr_temp() {
		document.getElementById("temp_area").innerHTML = "";
	}
//---------------------------------------------------------------------------------
	function ch_flt(prgm, em, upd_elm){
	var form,idx,st,i;
	    document.getElementById(upd_elm).innerHTML = "<img src='load.gif' alt='' />";
		form = document.forms.ulaForm;
		id_korp = form.num_build.options[form.num_build.selectedIndex].value;
		nxt_el = upd_elm;
		ajax.onreadystatechange = update;
        ajax.open(smetod, prgm+".php?id_korp=" + id_korp+"&fl="+em.value, true);
        ajax.send(null);
   }
//---------------------------------------------------------------------------------
	function ch_temp(n_div, txt_cont){
		document.getElementById(n_div).innerHTML = txt_cont;
		nxt_el = n_div;
		ajax.onreadystatechange = update;
        ajax.send(null);
    }
//---------------------------------------------------------------------------------
	function ch_val(n_div, val_txt){
		document.getElementById(n_div).value = val_txt;
		nxt_el = n_div;
		ajax.onreadystatechange = upd_;
        ajax.send(null);
    }
//---------------------------------------------------------------------------------
	function op_f(prgm, upd_elm){
	    document.getElementById(upd_elm).innerHTML = "<img src='load.gif' alt='' />"; //wait.gif Подождите ...
		nxt_el = upd_elm;
		ajax.onreadystatechange = update;
        ajax.open(smetod, prgm+".php", true);
        ajax.send(null);
    }
//---------------------------------------------------------------------------------
	function do_upd(prgm, prm1, prm2){
        ajax.open(smetod, prgm+".php?s1="+prm1+"&s2="+prm2, true);
		nxt_el = "Mform";
		ajax.onreadystatechange = update;
        ajax.send(null);
    }
//---------------------------------------------------------------------------------
	function ch_param(prgm, param, upd_elm){
	    document.getElementById(upd_elm).innerHTML = " <img src='load.gif' alt='' />";
		nxt_el = upd_elm;
		ajax.onreadystatechange = update;
		ajax.open(smetod, prgm+".php?"+param, true);
        ajax.send(null);
    }
//---------------------------------------------------------------------------------
	function ch(prgm, prm, em, upd_elm){
	var form;
//		alert(prm);
	    document.getElementById(upd_elm).innerHTML = "<table width=100%><tr><td align='center'><img src='load.gif'/></td></tr></table>";
		form = document.forms.ulaForm;
//write_temp(em.name+"="+em.value);		//write_temp(prgm+".php?"+prm+"=`"+em.value+"`");
        if (document.getElementById("B_Create")) { document.getElementById("B_Create").innerHTML = ''; }
        if (document.getElementById("B_Edit")) { document.getElementById("B_Edit").innerHTML = ''; }
        if (document.getElementById("B_Sub")) { document.getElementById("B_Sub").innerHTML = ''; }
//		document.getElementById("tab_Cust").innerHTML = "";
		if (prgm=="ch_Login") {
		} else {
	//		document.getElementById("B_adress").innerHTML =""; 
    //    	document.getElementById("B_Sub").innerHTML = ' ';
	//		document.getElementById("tab_Cust").innerHTML = "";
		}
//		document.getElementById("B_get_adress").disabled="disabled";
		if (prgm=="ch_flt") {
			if (upd_elm=="tab_Custnew") { // выбор адреса для перевода адреса
				sq = "ch_flt.php?"+prm+
					"&st="+form.id_streetnew.options[form.id_streetnew.selectedIndex].value+
					"&Num_build="+form.num_buildnew.options[form.num_buildnew.selectedIndex].value+
					"&fl="+form.flatnew.options[form.flatnew.selectedIndex].value+
					"&Bill_Dog="+form.tabl_cust.options[form.tabl_cust.selectedIndex].value+
					"&menu=new_adr";
	//				alert(sq);//em.value;h_st form.Town.value
			} else {
			sq = "ch_flt.php?"+prm+
				"&st="+form.id_street.options[form.id_street.selectedIndex].value+
				"&k="+form.num_build.options[form.num_build.selectedIndex].value+
				"&fl="+form.flat.options[form.flat.selectedIndex].value;
			if (form.sBill_Dog.value != '') {
				prm_v = document.getElementById("sBill_Dog").value;
				sq = 'srch.php?menu='+form.Menu_Item.value+'&'+form.tp.value+'&Bill_Dog='+prm_v;
				upd_elm = 'tab_Cust';
				em = 0;
			} else 
				if (form.sCod_flat.value != '') {
					prm_v = document.getElementById("sCod_flat").value;
					sq = 'srch.php?menu='+form.Menu_Item.value+'&'+form.tp.value+'&Cod_flat='+prm_v;
					upd_elm = 'tab_Cust';
					em = 0;
				}
				//Num_build
/*				"&st="+form.h_st.value+
				"&Num_build="+form.h_nb.value+
				"&fl="+form.h_fl.value; */
		//	if (form.Menu_Item.value == "recon") {
			btn_reload();
			form.sBill_Dog.value = '';
			form.sCod_flat.value = '';
			document.getElementById("dCod_flat").innerHTML = '';
			document.getElementById("dBill_Dog").innerHTML = '';
		//	}
//    		document.getElementById("B_Sub").innerHTML =
//				'<input name="B_edt_cust" type="button" onclick="clr_adress(); chk_adress();" value=" >> " />';
//				'<input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();" />';
			}
		} else {
			sq = prgm+".php?"+ prm+(em==0?"":"="+em.value);
		}

		nxt_el = upd_elm;
		ajax.onreadystatechange = update;
		ajax.open(smetod, sq, true);
        ajax.send(null);
		
/*		if (prgm=="ch_Login") {
			} else if (prgm=="ch_flt") {
//				setTimeout('chk_adress();',t_wait);
//				btn_reload();
			} else {
//			document.getElementById("podjezd").innerHTML = '(корп._, под._эт.<input name="floor" type="text" id="floor" size="1" />, район _)';
		}	*/
    }
//---------------------------------------------------------------------------------
	function dogovor() {
		dv = 'Main';// tab_Cust B_Create Mform
		form = document.forms.ulaForm;
		Bill_Dog = form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
//		alert(Bill_Dog);
		param = "st="+form.id_street.options[form.id_street.selectedIndex].value+
			"&Num_build="+form.num_build.options[form.num_build.selectedIndex].value+
			"&fl="+form.flat.options[form.flat.selectedIndex].value+
			"&Bill_Dog="+Bill_Dog+
			"&name_street="+form.id_street.options[form.id_street.selectedIndex].text+
			"&Login="+form.Login.options[form.Login.selectedIndex].text+
			"&tarif3w="+form.id_tarif3w.options[form.id_tarif3w.selectedIndex].text+
			"&Tday="+TODAY3;
//	    document.getElementById(dv).innerHTML = "<table width=100%><tr><td align='center'><img src='load.gif'/></td></tr></table>";
		window.open("dogovor.php?"+ param, "dogovor", "width=750,height=600,status=yes");//
//		ch_param("dogovor", param, dv);//tab_Cust
		window.print();
	}
//---------------------------------------------------------------------------------
	function f_btn() {
    document.getElementById("B_Sub").innerHTML =
		'<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />   '+
		'<input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();" />';
	}
//---------------------------------------------------------------------------------
	function btn_reload(){
        document.getElementById("B_adress").innerHTML =
			'<button name="B_chk_adress" id="B_chk_adress" type=button onClick=\'ch("ch_flt","menu='+document.forms.ulaForm.Menu_Item.value+'&",2,"tab_Cust");\'>'+
			'<img src="reload.png" align=middle alt="Обнови"></button>';
	}
//---------------------------------------------------------------------------------
/*function fnew_Cod_flat() {
	document.getElementById("temp_area").innerHTML = "";
	op_f("new_Cod_flat", "temp_area");
	return document.forms.ulaForm.get_Cod_flat.value; //setTimeout('',300);
}	*/
//---------------------------------------------------------------------------------
	function val_nm(Bill_Dog, nm) {
		return document.getElementById("h_"+Bill_Dog+"_"+nm).value;
	}
//---------------------------------------------------------------------------------
	function set_new_adr() {
	var form = document.forms.ulaForm;
//	if (form.mont.value == 0) { alert("Не выбран монтажник!"); return}
	var tabl_cust = document.getElementById("tabl_cust");
	var Bill_Dog = (tabl_cust.options[tabl_cust.selectedIndex].value);
	var param = "id_p="+form.h_id_Podjezdnew.value+"&fl="+form.flatnew.options[form.flatnew.selectedIndex].value+"&floor="+form.floornew.value+
		"&Bill_Dog="+Bill_Dog+"&Cod_flat="+form.h_Cod_flatnew.value+"&TabNum="+form.TabNum.value;
		 // + "&menu=new_adr"+"&mont="+form.mont.options[form.mont.selectedIndex].value		+"&DateKor="+form.n_date.value
		ch_param("do_new_adr", param, "B_Create");//tab_Cust
//		setTimeout('"";'+
//			'document.forms.ulaForm.sCod_flat.value = form.h_Cod_flatnew.value;'+
//			'document.forms.ulaForm.sCod_flat.onchange();',3000);
	}
//---------------------------------------------------------------------------------
	function chk_adress() {
	var form, vNic;
		form = document.forms.ulaForm;
//		if (form.id_town.selectedIndex > 0) {
//for(i=0; i<elems.length; i++) alert(elems[i].id)
//alert (document.body.getElementsByName(nm).innerHTML);//.value
			Bill_Dog = (!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
// alert(document.getElementById("tabl_cust"));
/*			document.getElementById("B_Sub").innerHTML =
					'<input type="button" name="Submit_ins" id="Submit_ins" value="Создать'+
					((Bill_Dog>0)?" доп.подключение (новый договор)":"") +'" onClick="ins_cust();" />';
			if ((Bill_Dog>0) && (form.Menu_Item.value == "recon")) {
				document.getElementById("B_Edit").innerHTML = 
					'<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />';
			}	*/
	if (document.getElementById("adress")) {
				form.floor.value = val_nm(Bill_Dog, "floor");
//				form.Cod_flat.value	= val_nm(Bill_Dog, "Cod_flat");
		}
//		if (form.Menu_Item.value == "con3w") {	}
	if (document.getElementById("net")) {
//				(document.getElementById("tabl_cust")) {
				form.conn[val_nm(Bill_Dog, "conn")*1].selected = true;
//				form.conn.onchange();
				form.Bill_Dog.value = val_nm(Bill_Dog, "Bill_Dog");
//				v_id_tarifab=val_nm(Bill_Dog, "id_tarifab");
				form.id_tarifab[val_nm(Bill_Dog, "id_tarifab")].selected = true;
//		alert(form.Date_end_st.value);
//				form.tarifab_date.value = val_nm(Bill_Dog, "tarifab_date");
				form.Nic.value = val_nm(Bill_Dog, "Nic");
				v_Date_start_st = val_nm(Bill_Dog, "Date_start_st");
				v_Date_end_st = val_nm(Bill_Dog, "Date_end_st");
				v_Date_pay = val_nm(Bill_Dog, "Date_pay");
				ar_s = new Array('"#333333">&nbsp;не устан.', '"#33CC66">&nbsp;подключен', '"#0000FF">&nbsp;замороз.', '"#00FFFF">&nbsp;расторг');
				n_st = val_nm(Bill_Dog, "state");
				i_st = n_st==''?0:n_st;
				document.getElementById("state").innerHTML = '<font style="border:solid" color='+ar_s[i_st]+'&nbsp;</font>';//
				if (form.Menu_Item.value == "pay") {
					document.getElementById("state").innerHTML = '<font style="border:solid" color='+ar_s[i_st]+
						(v_Date_start_st!=''?' с ' + v_Date_start_st:'') +'<input name="Date_start_st" type="hidden" value="'+v_Date_start_st+'" />'+
						(v_Date_end_st!=''?' по  ' + v_Date_end_st:'') +'<input name="Date_end_st" type="hidden" value="'+v_Date_end_st+'" />&nbsp;</font>';
					form.B_freaze.style.display = (v_Date_pay=='' || form.h_new_Cod.value==1)?"none":"";
					document.getElementById("Date_pay").innerHTML = ' оплачено по '+v_Date_pay+'<input name="Date_pay" value="'+v_Date_pay+'" type="hidden"/>';//
	// 				document.getElementById("hist_pay").innerHTML = "<table width=100%><tr><td align='center'><img src='loader.gif'/></td></tr></table>";
					setTimeout('ch_param("sh_pays", "Bill_Dog='+Bill_Dog+'", "hist_pay");',300);
	//				document.getElementById("abon_pay").innerHTML = "Абон.плата: "+100*(1+1/form.tabl_cust.size)+" руб./мес.";
				} else {
					form.Date_start_st.value = v_Date_start_st;
					form.Date_end_st.value = v_Date_end_st;
					form.Date_pay.value = v_Date_pay;
				}
		}
	if (document.getElementById("phn")) {
				form.phone_Home.value = val_nm(Bill_Dog, "phone_Home");
				form.phone_Cell.value = val_nm(Bill_Dog, "phone_Cell");
				form.phone_Work.value = val_nm(Bill_Dog, "phone_Work");
				form.Jur.checked 	  = val_nm(Bill_Dog, "Jur")==1;
		}
	if (document.getElementById("fio")) {
				form.Fam.value 		 = val_nm(Bill_Dog, "Fam");
				form.Name.value 	 = val_nm(Bill_Dog, "Name");
				form.Father.value 	 = val_nm(Bill_Dog, "Father");
				form.Birthday.value  = val_nm(Bill_Dog, "Birthday");
				form.pasp_Ser.value  = val_nm(Bill_Dog, "pasp_Ser");
				form.pasp_Num.value  = val_nm(Bill_Dog, "pasp_Num");
				form.pasp_Date.value = val_nm(Bill_Dog, "pasp_Date");
				form.pasp_Uvd.value  = val_nm(Bill_Dog, "pasp_Uvd");
				form.pasp_Adr.value  = val_nm(Bill_Dog, "pasp_Adr");
				form.Comment.value 	 = val_nm(Bill_Dog, "Comment");
		}
	if (document.getElementById("w3")) {
//					document.getElementById("addLogin").innerHTML = '';
//		document.getElementById("d_Bill_Dog").innerHTML = val_nm(Bill_Dog, "Bill_Dog");
				form.From_Net.value = val_nm(Bill_Dog, "From_Net");
//alert(document.getElementById("h_"+Bill_Dog+"_"+"Logins").value);
				var num_L = val_nm(Bill_Dog, "Logins");
/*****		Если логинов нет - предлагать добавление
				if (num_L == "0" && confirm("Обнаружено отсутствие логина в базе данных."+
									"Вставить в базу логин такой же как ник - '"+val_nm(Bill_Dog, "Nic")+
									"', тариф интернет - 'Стандарт' и датой его установления - сегодня? ")) {
					ch_param("ins_Login", 'Bill_Dog='+form.Bill_Dog.value+'&Nic='+form.Nic.value+'&Login='+form.Nic.value+
						'&id_tarif3w=1&tarif3w_date="'+TODAY2 + '"', "B_Sub"); //'+val_nm(Bill_Dog, "id_tarif3w") + '		tarif3w_date.value 
					document.getElementById("h_"+Bill_Dog+"_"+"Login1").value = form.Nic.value;
					document.getElementById("h_"+Bill_Dog+"_"+"Logins").value = 1;
					num_L = 1;
				}		****/
				if (num_L == "0") {
					document.getElementById("Login").innerHTML = 'логин '+(form.Menu_Item.value!="pay"?'<input name="addLogin" type="button" id="addLogin" onclick="faddLogin();" value="+" />':'');
					/* добавить ли adjastNet(); ??? */
					/*'логин <INPUT name="nic2login" type="button" id="nic2login" '+
						'onclick="f=document.forms.ulaForm; f.Login.value=f.Nic.value;'+
						'adjastNet();" value="тот же"><INPUT name="Login" type="text" value="" onChange="adjastNet();" size="12">'; */
//				alert(val_nm(Bill_Dog, "Logins"));
				}
				if (num_L > "0") {
					h_N = "h_"+Bill_Dog+"_";
					t_d = h_N + 'tarif3w_date';
					get_E = 'document.getElementById';//'+get_E+'(&quot;id_tarif3w&quot;)[this.value].selected = true;'+
					set_ff = 'ff=document.forms.ulaForm; ';
					str_onCh = ' onchange="'+set_ff+'ff.id_tarif3w[ff.Login.selectedIndex].selected = true;'+
						get_E+'(\'tarif3w_date\').value = '+
						get_E+'(\''+h_N + 'tarif3w_date\'+this.value).value;"'; //
						N_sze = 1 + num_L; //math.abs('+5+'
					str_L = '<table><tr><td rowspan="'+N_sze+'">логин <select name="Login" id="Login" class="navText" size="'+num_L+'"'+
						' onchange="adjustLogin()"'+//'+str_onCh+'
						' >';
					for(var i=1; i<=num_L; i++){ //
						var nmLogin = document.getElementById(h_N+"Login"+i).value;
						str_L += '<option value='+nmLogin+'>'+ nmLogin + '</option>';
 					}
					str_L += '</select></td>';
					str_L += '<td>'+document.getElementById(h_N+"saldo"+1).value*1+' руб.</td>';
					str_L += '<td rowspan="'+N_sze+'" valign="bottom">'+
						(form.Menu_Item.value!="pay"?'<input name="addLogin" type="button" id="addLogin" onclick="faddLogin();" value="+" />':'')+
						'</td></tr>';
					for(var i=2; i<=num_L; i++){
						str_L += '<tr><td>'+document.getElementById(h_N+"saldo"+i).value*1+'р.</td></tr>';
					}	
					str_L += '</table>';
					document.getElementById("Login").innerHTML = str_L;
					form.Login[0].selected = true;
					form.Login.onchange();
//					write_temp(document.getElementById("Login").selectedIndex);//addLogin();
				}
//				alert(form.tarif3w+" "+val_nm(Bill_Dog, "id_tarif3w"));
		//		form.id_tarif3w[val_nm(Bill_Dog, "id_tarif3w")].selected = true;
		//		form.tarif3w_date.value = val_nm(Bill_Dog, "tarif3w_date");
		//		form.Login.value = val_nm(Bill_Dog, "Login1"); // dont only 1
		}
// disabled="disabled"
//write_temp("chk_adress");
//		document.getElementById("B_Sub").innerHTML +=
//			'<input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();" />';
//		'<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />   '+
//		} else {	}
//	alert("W");
	}
//---------------------------------------------------------------------------------
	function faddLogin() {
	var form = document.forms.ulaForm;
		document.getElementById("Login").innerHTML =
			'логин <input name="Login" type="text" value="" size="12" onChange="ch_param(&quot;is_Login_Free&quot;,&quot;Login=&quot;+this.value, &quot;addLogin&quot;);" />'+
			'<input name="cancLogin" type="button" id="cancLogin" onclick="document.getElementById(\'addLogin\').innerHTML =\'\';chk_adress();" value="X" />';
		document.getElementById("id_tarif3w")[0].selected = true;
		form.tarif3w_date.value = TODAY2;
		document.getElementById("B_Sub").innerHTML ="";
		form.Nic.value = val_nm(Bill_Dog, "Nic");
		document.getElementById("Nic").readOnly=true;
		document.getElementById("Bill_Dog").readOnly=true;
		document.getElementById("conn").readOnly=true;
		document.getElementById("id_tarifab").readOnly=true;
		document.getElementById("tab_w3").bgcolor="#00FF00";
	}
//---------------------------------------------------------------------------------
	function DoaddLogin() {
	var form;
		form = document.forms.ulaForm;
		var num_L = val_nm(Bill_Dog, "Logins");
		ch_param("ins_Login", 'Bill_Dog='+form.Bill_Dog.value+'&Nic='+form.Nic.value+'&Login='+form.Login.value+
			'&id_tarif3w='+form.id_tarif3w.value+'&tarif3w_date="'+form.tarif3w_date.value + '"', "B_Sub");
		Nnum_L = Math.abs(num_L)+1;
		// - ->tab_Cust
		document.getElementById("h_"+Bill_Dog+"_Logins").value = Nnum_L;
		var inp_name = "h_"+Bill_Dog+"_Login"+Nnum_L;
//		document.getElementById("tab_Cust").innerHTML += inp_name+'<input name="'+inp_name+'" id="'+inp_name+'" value="'+form.Login.value+'" />';
		//document.getElementById("h_"+Bill_Dog+"_Login"+Nnum_L).value = form.Login.value; type="hidden"
		setTimeout('chk_adress();', 300);
	}
//---------------------------------------------------------------------------------
	function adj_Cust() {
	var form;
		form = document.forms.ulaForm;
//write_temp("adj_Cust");
//		document.getElementById("Submit_cor").disabled="disabled";
//		form.Nic.value = val_nm(Bill_Dog, "Nic");
//		document.getElementById("conn").readOnly=true;
//		document.getElementById("id_tarifab").readOnly=true;
		Bill_Dog = (!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
		if (document.getElementById("tabl_cust") && (form.Nic.value == val_nm(Bill_Dog, "Nic")) &&
			(document.forms.ulaForm.Menu_Item.value == "recon")) {
			document.getElementById("Nic").readOnly=true;
			document.getElementById("Bill_Dog").readOnly=true;
			document.getElementById("B_Edit").innerHTML = 
				'<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />';
		}
	}
//---------------------------------------------------------------------------------
	function add_cust(Bill_Dog_New){
		var f = document.forms.ulaForm;
		phone_Home = f.phone_Home.value==''?'':f.phone_Home.value;
		clr_adress();
		f.phone_Home.value = phone_Home;
		f.Date_start_st.value = TODAY2;
		f.Bill_Dog.value = Bill_Dog_New;
		f.conn[2].selected = true;
		adj_Conn(2);
    }
//---------------------------------------------------------------------------------
	function adj_Conn(conn){
		var f = document.forms.ulaForm;
//		var conn = f.conn.value;
		var tp = f.tp.value;
		f.conn_pay.value = "";
		ch_param("ch_conn", "con_typ="+conn+"&tp="+tp, "con_tar");
		setTimeout('adj_con_tar();', 150);
		if (conn == 5) {
			setTimeout('ch_param("frm_adress", "new=new", "new_adr");', 250);
 		} else {
			document.getElementById("new_adr").innerHTML = "";
			adj_Cust();
		}
//		adj_CPay();//document.getElementById("tabl_cust").size
//		document.forms.ulaForm.con_tar.onchange();
    }
//---------------------------------------------------------------------------------
	function Date_Add(d_o, opl_m, opl_d) {
		opl_m = opl_m==""?0:opl_m;
		var s_date = new String(d_o);
		s_date = (s_date==""? TODAY2: s_date);
		i_M = s_date.indexOf("-", 0);
		s_Y = s_date.substring(0, i_M);
		i_D = s_date.lastIndexOf("-");
		s_M = s_date.substring(1*i_M+1, i_D);
		s_D = s_date.substring(i_D+1);
//		s_D = (1*s_D<10 ? "0" : "") + s_D;
		n_Y = (1*s_M+1*opl_m>12)?1*s_Y+1:s_Y;
		dd_2 = (n_Y % 4) == 0 ? 29 : 28;
		var dd = new Array(0,31,dd_2,31,30,31,30,31,31,30,31,30,31);
		n_M = (1*s_M+1*opl_m>12)?1*s_M+1*opl_m-12:1*s_M+1*opl_m;
		n_D = 1*s_D + 1*opl_d;
		if (n_D > 28) {
//		alert("n_M="+n_M+" dd[n_M]="+dd[n_M]+"n_D="+n_D);
			if(dd[n_M] < n_D) {
				n_D = n_D - dd[n_M];
				n_M = n_M < 12 ? 1*n_M + 1 : 1;
			}
		}
//		alert(1*s_M+1*opl_m+ " "+(1*s_Y+1)+  " "+s_Y);//
		n_M = (1*n_M<10 ? "0" : "") + n_M;
		n_D = (1*n_D<10 ? "0" : "") + n_D;
		return n_Y+"-"+n_M+"-"+n_D;
	}
//---------------------------------------------------------------------------------
	function f_Bill_Dog(){
		var f = document.forms.ulaForm;
		return (!document.getElementById("tabl_cust"))?0:f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
	}
//---------------------------------------------------------------------------------
	function adj_con_tar(){
		var f = document.forms.ulaForm;
		var t = f.id_tarifab.options[f.id_tarifab.selectedIndex].value;
//		alert(t);
//	if (f.conn.value==1) {	}
		var opl_p = document.getElementById("h_opl_"+t).value;//
//		write_temp("h_con_"+t.value);
		f.conn_pay.value = document.getElementById("h_con_"+t).value;
		f.abon_pay.value = document.getElementById("h_ab_"+t).value;
//		alert(">"+TODAY2+"<");
		if (f.Date_start_st.value=='') { f.Date_start_st.value=TODAY2; }
//		alert(f.Date_start_st.value);
		// вычислить число миллисекунд в дне	 lastIndexOf	.substring(indexA, indexB)	length		//msPerDay = 24 * 60 * 60 * 1000;	date(,"YYYY-MM-DD")
		f.Date_end_st.value = opl_p==''?f.Date_start_st.value:Date_Add(f.Date_start_st.value,opl_p,0); //n_Y+"-"+n_M+"-"+s_D;
		
		Bill_Dog = (!document.getElementById("tabl_cust"))?0:f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
//		alert(Bill_Dog);
		if (document.getElementById("tabl_cust") && Bill_Dog==f.Bill_Dog.value) {
			f.Date_pay.value = Date_Add(val_nm(Bill_Dog, "Date_pay"), opl_p, 0);
			ar_s = new Array('"#333333">&nbsp;не устан.', '"#33CC66">&nbsp;подкл.', '"#0000FF">&nbsp;замороз.', '"#00FFFF">&nbsp;расторг');
			n_st = val_nm(Bill_Dog, "state");
			i_st = n_st==''?0:n_st;
			document.getElementById("state").innerHTML = f.id_tarifab.value==0?'':'<font style="border:solid" color='+ar_s[i_st]+'</font>';//
		} else {
			f.Date_pay.value = f.Date_end_st.value;
			document.getElementById("state").innerHTML = f.id_tarifab.value==0?'':'<font style="border:solid" color="#000000">&nbsp;подключить&nbsp;</font>';//
		}
		adj_CPay();
    }
//---------------------------------------------------------------------------------
	function adjustTarif3w() {
 		adjastNet();
	}
//---------------------------------------------------------------------------------
	function adjastPasp(){
		adj_Cust();
    }
//---------------------------------------------------------------------------------
	function adjastNet() {
		adj_Cust();
	}
//---------------------------------------------------------------------------------
	function adjustPhn() {
		adj_Cust();
	}
//---------------------------------------------------------------------------------
	function adj_Bill_Dog(nBill_Dog) { // Bill_Dog
	var form, con;
		form = document.forms.ulaForm;
		//form.
//		alert(nBill_Dog.value+" - "+Bill_Dog);
//		if  { новый договор на новом адресе 		}
		document.forms.ulaForm.Nic.value = "";
		document.getElementById("B_Edit").innerHTML = "";
		document.getElementById("B_Create").innerHTML = "";
		Bill_Dog = (!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
		if ((Bill_Dog == 0) || (nBill_Dog.value != val_nm(Bill_Dog, "Bill_Dog"))) {
			document.getElementById("conn")[(Bill_Dog == 0)?1:2].selected = true;
			ch_param("is_Bill_Free", "Bill_Dog="+nBill_Dog.value, "B_Create");
			form.tarifab_date.value = TODAY2;
			form.tarif3w_date.value = TODAY2;
			if (document.getElementById("Submit_cor")) { document.getElementById("Submit_cor").disabled="disabled"; }
			document.getElementById("Login").innerHTML = 'логин <input name="nic2login" type="button" id="nic2login" '+
				'onclick="javascript:document.forms.ulaForm.Login.value=document.forms.ulaForm.Nic.value;adjustLogin();"'+
				' value="тот же" /><input name="Login" type="text" value="" onChange="adjustLogin();" size="12" />';
		} else {document.getElementById("Submit_cor").disabled=""; }
	}
//---------------------------------------------------------------------------------
	function adj_Nic(nic) {
	var form;
		form = document.forms.ulaForm;
//		if form.Bill_Dog.value != 
		ch_param("is_Nic_Free", "Nic="+nic.value, "B_Create");
		Bill_Dog = (!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
		prm = ((Bill_Dog == 0) || (form.Bill_Dog.value != val_nm(Bill_Dog, "Bill_Dog")))?"new":"add";
		s_LonChange = 'ch_param(&quot;is_Login_Free&quot;,&quot;Login=&quot;+this.value+&quot;&prm='+prm+'&quot;,&quot;addLogin&quot;);';
//		alert(s_LonChange);
		document.getElementById("Login").innerHTML = 'логин <input name="Login" type="text" value="'+nic.value+'" size="12" onChange="'+s_LonChange+'" />'+
			'<input type="button" name="chk_Login" value="?" onClick="'+s_LonChange+'" />'+
			'<input name="cancLogin" type="button" id="cancLogin" onclick="document.getElementById(&quot;addLogin&quot;).innerHTML =&quot;&quot;;chk_adress();" value="X" />';
		document.getElementById("id_tarif3w")[0].selected = true;
		form.tarif3w_date.value = TODAY2;
		document.getElementById("B_Sub").innerHTML ="";
//		form.Nic.value = val_nm(Bill_Dog, "Nic");
//		document.getElementById("Nic").readOnly=true;

		document.getElementById("Bill_Dog").readOnly=true;
		document.getElementById("conn").readOnly=true;
		document.getElementById("id_tarifab").readOnly=true;
		document.getElementById("tab_w3").bgcolor="#00FF00";

//javascript:if (form.Login.value==&quot;&quot;) {form.Login.value= this.val//			(!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value
			if (document.getElementById("tabl_cust") && (document.forms.ulaForm.DublNic.value == "0") &&
				(document.forms.ulaForm.Menu_Item.value == "recon")) {
				document.getElementById("B_Edit").innerHTML = 
					'<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />';
			}
	}
//---------------------------------------------------------------------------------
	function adjustLogin() {
	var form, h_N;
		form = document.forms.ulaForm;
		h_B = "h_"+form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
		id_w3t = document.getElementById(h_B + "_id_tarif3w" + Math.abs(form.Login.selectedIndex+1)).value;//value
		document.getElementById("id_tarif3w")[Math.abs(id_w3t-1)].selected = true;
		form.tarif3w_date.value = document.getElementById(h_B + "_tarif3w_date" + Math.abs(form.Login.selectedIndex+1)).value;
// 		adjastNet();
 	}
//---------------------------------------------------------------------------------
	function adj_CPay() {
	var form = document.forms.ulaForm;
		form.total_pay.value = 1*form.conn_pay.value + 1*form.abon_pay.value + 1*form.inet_pay.value;
	}
//---------------------------------------------------------------------------------
	function adjust_pay() {
		var form = document.forms.ulaForm;
//		alert(form.h_new_Cod.value);
		if (form.h_new_Cod.value == 1) {
			alert('не присвоен код адреса');	return;
		}
		var v_st = val_nm(f_Bill_Dog(), "state");
		if (v_st == '') {
			alert('Это ИНТЕРНЕТ Учётка! Выполните переоформление-подключение к сети');	return;
		}
		form.total_pay.value = 1*form.inet_pay.value + 1*form.abon_pay.value;
		var m = (form.abon_pay.value - (form.abon_pay.value % form.opl_mon.value))/form.opl_mon.value;
		var d = Math.round((form.abon_pay.value % form.opl_mon.value)*30/form.opl_mon.value);//
		form.opl_per.value = m;
		action = m >= 6 ? (m >= 12 ? 2 : 1) : 0;
		nDate = Date_Add(form.Date_pay.value, m, d);//Date_end_st
		nDateAct = Date_Add(nDate, action, 0);
//		n_Date = new Date(form.Date_end_st.value);
		document.getElementById("opl_to").innerHTML = "Оплата по "+nDate+'<input name="new_Date_end" type="hidden" value="'+nDate+'"/>';//new_Date_end
/*		if (action > 0) {
			document.getElementById("action").innerHTML = '+ '+action+' мес.='+nDateAct+'<input name="nDateAct" type="hidden" value="'+nDateAct+'"/><input name="action" type="hidden" value="'+action+'"/>';
		} else {	*/
		document.getElementById("action").innerHTML = '<input name="action" type="hidden" value="'+action+'"/>'+
			(action>0?'+ '+action+' мес.='+nDateAct:'')+'<input name="nDateAct" type="hidden" value="'+nDateAct+'"/>';//
		if (1*form.total_pay.value != 0) {
			document.getElementById("B_Create").innerHTML = 
					'<input type="button" name="Submit_ins" id="Submit_ins" value="Внести" onClick="ins_pay();" />';
		}
	}
//---------------------------------------------------------------------------------
	function ins_pay() {
	var form, con;
/*        document.getElementById("B_adress").innerHTML =
			'<input name="B_chk_adress" type="button" onclick="ch(&quot;ch_flt&quot;,&quot;menu=pay&&quot;'+
			',2,&quot;tab_Cust&quot;);" value="Обнови"/>';	*/
		form = document.forms.ulaForm;
		Bill_Dog = (!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value;

		if (form.Login) { vLogin = form.Login.value; }
			else if (form.Login.options[form.Login.selectedIndex]) {
				vLogin = form.Login.options[form.Login.selectedIndex].text; }
					else { alert("Ошибка, обратитесь к разработчику!"); }
		Nic = val_nm(Bill_Dog, "Nic");
		vtoday = new Date();
		D_st = form.Date_pay.value==''?time2Y_m_d(vtoday):form.Date_pay.value;//Date_end_st
		id_p=form.h_id_Podjezd.value;
		fl = form.h_fl.value;

		s_param = "id_p="+id_p+"&fl="+fl+"&TabNum="+form.TabNum.value+"&Bill_Dog="+Bill_Dog+"&Nic="+Nic+"&Login="+vLogin+"&abon="+form.abon_pay.value+
			"&inet="+form.inet_pay.value+"&abon_Com="+form.abon_Com.value+"&inet_Com="+form.inet_Com.value+
			"&Date_start="+D_st+"&Date_end="+form.new_Date_end.value+"&action="+form.action.value+"&nDateAct="+form.nDateAct.value;	 //+"&="++" &="+id_p="+id_p+"&fl="+fl+"&	/*+"&="+form..value*/
//		write_temp(s_param);
		setTimeout('document.getElementById("B_Create").innerHTML ="";', 100);
		ch_param('do_pay', s_param, 'res_pay');
//		document.getElementById('B_chk_adress').onClick();
		//ch('ch_flt','menu=pay&',2,'tab_Cust');
		FIO = val_nm(Bill_Dog, "Fam") + " " + val_nm(Bill_Dog, "Name") + " " + val_nm(Bill_Dog, "Father");
		adr = form.h_st.value + " д." + form.h_nb.value + (form.h_kr.value==''?"":" корп."+form.h_kr.value) + " кв." + form.h_fl.value;
//		 = 0; //form..value;
		abon_pay = form.abon_pay.value;
		inet_pay = form.inet_pay.value;
		total_pay = form.total_pay.value;
		Date_pay = form.action.value>0?form.nDateAct.value:form.new_Date_end.value;
		param = "TabNum="+form.TabNum.value+"&fio="+FIO+"&adr="+adr+"&Bill_Dog="+Bill_Dog+"&Date_pay="+Date_pay+"&conn_pay="+conn_pay+"&abon_pay="+abon_pay+"&inet_pay="+inet_pay+"&Login="+vLogin+"&total_pay="+total_pay+"&action="+form.action.value;
		if (document.B_chk_adress1) {alert(1)}
//		setTimeout('form.B_chk_adress1.click();', 300);
		w_check = window.open("print_pay.php?"+ param, "w_ch", "width=750,height=350,status=yes");//
		w_check.window.print();
////		print_pay();
	}
//---------------------------------------------------------------------------------
	function print_pay() { /// Отключена
		dv = 'Main';// tab_Cust B_Create Mform
		form = document.forms.ulaForm;
		Bill_Dog = form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
		FIO = val_nm(Bill_Dog, "Fam") + " " + val_nm(Bill_Dog, "Name") + " " + val_nm(Bill_Dog, "Father");
		adr = "ул."+form.h_st.value + " д." + form.h_nb.value + (form.h_kr.value==''?"":" корп."+form.h_kr.valu) + " кв." + form.h_fl.value;
/*		param1 = "st="+form.id_street.options[form.id_street.selectedIndex].value+
			"&Num_build="+form.num_build.options[form.num_build.selectedIndex].value+
			"&fl="+form.flat.options[form.flat.selectedIndex].value+
			"&Bill_Dog="+Bill_Dog+
			"&name_street="+form.id_street.options[form.id_street.selectedIndex].text+
			"&Login="+form.Login.options[form.Login.selectedIndex].text+
			"&tarif3w="+form.id_tarif3w.options[form.id_tarif3w.selectedIndex].text+
			"&Tday="+TODAY3;	*/
//	    document.getElementById(dv).innerHTML = "<table width=100%><tr><td align='center'><img src='load.gif'/></td></tr></table>";
		param= "fio='"+FIO+"'&adr='"+adr+"'"+"&Bill_Dog="+Bill_Dog;
//		document.write('<div id="d_chk" style="background-color:#FFFFFF; position:absolute; left: 0px; top: 0px; width: 800; height: 600;"></div>');
		ch_param('print_pay', param, 'd_chk');
		//w_check = window.open("print_pay.php?"+ param, "w_ch", "width=750,height=300,status=yes");//
		window.print();
	}
//---------------------------------------------------------------------------------
	function canc_otp(d_canc, Bill_Dog, Date_start, Date_end) { /// 
		var f = document.forms.ulaForm;
//	alert("1 "+d_canc+" "+Bill_Dog+" "+Date_start+" "+Date_end+" "+f.TabNum.value);
		ch("do_canc_otp", "d_canc="+f.d_canc.value+"&Bill_Dog="+Bill_Dog+"&Date_start="+Date_start+"&Date_end="+Date_end+"&TN_canc="+f.TabNum.value, 0 ,  'res_pay');
		setTimeout('alert("Выполнено изменение отпуска"); document.forms.ulaForm.B_chk_adress.click();', 100);
	}
//---------------------------------------------------------------------------------
	function del_otp(Bill_Dog, Date_start, Date_end) { /// 
		var f = document.forms.ulaForm;
//	alert("1 "+d_canc+" "+Bill_Dog+" "+Date_start+" "+Date_end+" "+f.TabNum.value);
		ch("do_del_otp", "Bill_Dog="+Bill_Dog+"&Date_start="+Date_start+"&Date_end="+Date_end+"&TN_canc="+f.TabNum.value, 0 ,  'res_pay');
		setTimeout('alert("Отпуск удалён"); document.forms.ulaForm.B_chk_adress.click();', 100);
	}
//---------------------------------------------------------------------------------
	function srch(prm){ //, prm_val
	var f = document.forms.ulaForm;
		prm_v = document.getElementById("s"+prm).value;
		if (prm == "Bill_Dog") { f.sCod_flat.value = ''; } else { f.sBill_Dog.value = ''; }
//		ch_param('srch', 'menu='+f.Menu_Item.value+'&tp='+f.tp.value+'&'+prm+'='+prm_v, 'tab_Cust');
		ch('srch', 'menu='+f.Menu_Item.value+'&tp='+f.tp.value+'&'+prm+'='+prm_v, 0 ,  'tab_Cust'); //_param
		f.id_town.options[0].selected=true;
		f.id_street.options[0].selected=true;
		f.num_build.options[0].selected=true;
		f.flat.options[0].selected=true;	

		document.getElementById("B_adress").innerHTML = '';
        document.getElementById("B_Create").innerHTML = '';
		document.getElementById("B_Sub").innerHTML = '';
		document.getElementById("dCod_flat").innerHTML = '';
		document.getElementById("dBill_Dog").innerHTML = '';
		document.getElementById("B_Edit").innerHTML = '';
        document.getElementById("d"+prm).innerHTML = 
			'<button name="B_chk_adress" type=button onClick="srch(\''+prm+'\')"><img src="reload.png" align=middle alt="Обнови"></button>';//
	}
//---------------------------------------------------------------------------------
	function get_adress(){
	var form = document.forms.ulaForm;
	return form.id_street.options[0].selected?("г.Талнах ул."+form.h_st.value+" д."+form.h_nb.value+(form.h_kr.value==''?"":" корп."+form.h_kr.value)+" кв."+form.h_fl.value):("г."+
		form.id_town.options[form.id_town.selectedIndex].text +
		" ул."+ form.id_street.options[form.id_street.selectedIndex].text +
		" д."+ form.num_build.options[form.num_build.selectedIndex].text +
		" кв."+ form.flat.options[form.flat.selectedIndex].text);
	}
//---------------------------------------------------------------------------------
	function cor_str() {
	var form, con, vLogin;
		form = document.forms.ulaForm;
//		alert("conn="+ form.conn.selectedIndex);
//				form.Bill_Dog.value = val_nm(Bill_Dog, "Bill_Dog");
		if (form.Menu_Item.value == "recon") {
/*			if (form.tarif3w_date.value=='') {//alert("Не установлена дата подключения к интернету");
//			} else if (form.tarifab_date.value=='') {alert("Не установлена дата подключения тарифа сети");
			} //else if ((form.conn.selectedIndex==0) || (form.mont.selectedIndex==0)) {alert("При смене подключения, не выбран монтажник!");} 
			else {	*/
	/*			if (form.h_Rows.value > 0) {
					Bill_Dog = form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
					form.Nic.value = val_nm(Bill_Dog, "Nic");
				}	*/
		//		form.Nic.value = form.h_Nic.value;
		//		if(isset(form.h_Nic.value) && (form.h_Nic.value!="")) con = 2;
				id_p=form.h_id_Podjezd.value;
		//		fl = form.flat.options[form.flat.selectedIndex].text;
				fl = form.h_fl.value;
				_Jur = (form.Jur.checked==true)?1:0;
/*				s_3w = "";
				if (form.tarif3w_date.value!='') {
					s_3w = "&id_tarif3w="+Math.abs(form.id_tarif3w.selectedIndex+1)+
						"&tarif3w_date="+form.tarif3w_date.value;
				}		*/
				if (form.Login) { vLogin =  form.Login.value; }
					else if (form.Login.options[form.Login.selectedIndex]) {
						vLogin = form.Login.options[form.Login.selectedIndex].text; }
							else { alert("Ошибка, обратитесь к разработчику!"); }
				s_param = "id_p="+id_p+"&fl="+fl+
					"&floor="+form.floor.value+
					"&conn="+Math.abs(form.conn.selectedIndex)+//document.getElementById("conn").selectedIndex+ 
					"&Nic="+form.Nic.value+
					"&id_tarifab="+form.id_tarifab.selectedIndex+
					"&tarifab_date="+form.tarifab_date.value+ //form.n_date.value+
					"&Login="+vLogin+ //form.Login.options[form.Login.selectedIndex].text+
					"&Fam="+form.Fam.value+
					"&Name="+form.Name.value+
					"&Father="+form.Father.value+
					"&Birthday="+form.Birthday.value+
					"&pasp_Ser="+form.pasp_Ser.value+
					"&pasp_Num="+form.pasp_Num.value+
					"&pasp_Date="+form.pasp_Date.value+
					"&pasp_Uvd="+form.pasp_Uvd.value+
					"&pasp_Adr="+form.pasp_Adr.value+
					"&Comment="+form.Comment.value+
					"&phone_Home="+form.phone_Home.value+
					"&phone_Cell="+form.phone_Cell.value+
					"&phone_Work="+form.phone_Work.value+
					"&Bill_Dog="+form.Bill_Dog.value+
					"&id_tarif3w="+Math.abs(form.id_tarif3w.selectedIndex+1)+
					"&tarif3w_date="+form.tarif3w_date.value+
					"&Jur="+_Jur+
					"&From_Net="+form.From_Net.value+
					"&conn_pay="+form.conn_pay.value+
					"&abon_pay="+form.abon_pay.value+
					"&inet_pay="+form.inet_pay.value+
					"&total_pay="+form.total_pay.value+
				//	"&mont="+form.mont.options[form.mont.selectedIndex].value+
					"&TabNum="+form.TabNum.value+
					"&Cod_flat="+form.h_Cod_flat.value+
				//	"&DateKor="+form.n_date.value+
					"&Date_start_st="+form.Date_start_st.value+
					"&Date_end_st="+form.Date_end_st.value+
					"&Date_pay="+form.Date_pay.value;
	//				alert ("form.h_new_Cod.value ="+form.h_new_Cod.value+" form.h_Cod_flat.value ="+form.h_Cod_flat.value);
				if (form.h_new_Cod.value == 1) {
	//				s_param = s_param + "&Cod_flat="+form.h_Cod_flat.value;
				}
	//				"&connect="+form.connect.value+
	/*		}	*/
		}
//alert
//write_temp("cor_str="+s_param);
return s_param;
}
//---------------------------------------------------------------------------------
	function cor_cust() {
	var form, con;
		form = document.forms.ulaForm;
		if (form.Menu_Item.value == "recon") {
//	корректировка абонента
		ch_param('do_cor_cust', cor_str(), 'B_Sub');
//			ajax.open(smetod, "do_cor_cust"+cor_str(), true);
		} 
		if (form.Menu_Item.value == "noti") {
// оформление заявки на ремонт.php?
		Bill_Dog = (!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value;
		c_str = "id_p="+form.h_id_Podjezd.value+"&fl="+form.h_fl.value+"&Notify="+form.noti.value+"&Bill_Dog="+Bill_Dog+
			"&Date_Plan="+form.Date_Plan.value+"&Date_in="+form.n_date.value+"&phone_Dop="+form.phone_Dop.value+
			"&TabNum="+form.TabNum.value+"&Cod_flat="+form.h_Cod_flat.value;
	//		alert(c_str);//+"&Date_Fact="+form.Date_Fact.value+"&mont="+form.mont.options[form.mont.selectedIndex].value
		ch_param('do_noti', c_str, 'B_Sub');
//			ajax.open(smetod, c_str, true);
		}
/*		if (form.Menu_Item.value == "con3w") {
			//	выполнение подключения к интернету");
			ajax.open(smetod, "do_con3w.php?Login="+form.Login.value, true);
			}	*/
///		nxt_el = "B_Sub";
///		ajax.onreadystatechange = update;
///        ajax.send(null);
//		document.getElementById("B_Edit").innerHTML = //'';
//					'<input type="button" name="Submit_ins" id="Submit_ins" value="Печать" onClick="window.print();" />';
	}
//---------------------------------------------------------------------------------
	function ins_cust() {
	var form, con;
		form = document.forms.ulaForm;
//		write_temp(st_);
		if (form.Menu_Item.value == "recon") {
		s_cor_str = cor_str();
//		st_ = "do_ins_cust"+s_cor_str;
		ch_param('do_ins_cust', s_cor_str, 'B_Sub');
		FIO = form.Fam.value + " " + form.Name.value + " " + form.Father.value;
		adr = get_adress();
		param = s_cor_str + "&fio=" + FIO + "&adr=" + adr;
//		alert(param); write_temp(param);
		w_chk = window.open("print_pay.php?"+ param, "", "width=750,height=350,status=yes");//
		w_chk.window.print();
		document.getElementById("B_Create").innerHTML = '';
return;
//		param = "fio="+FIO+"&adr="+adr+"&Bill_Dog="+Bill_Dog+"&Date_pay="+Date_pay+"&conn_pay="+conn_pay+"&abon_pay="+abon_pay+"&inet_pay="+inet_pay+"&Login="+vLogin+"&total_pay="+total_pay+"&action="+form.action.value;

			ajax.open(smetod, st_, true);
			} 
		if (form.Menu_Item.value == "con3w") {
			ajax.open(smetod, "do_con3w.php?Login="+form.Login.value, true);
			alert("выполнение подключения к интернету");
			}
			
		nxt_el = "B_Sub";
		ajax.onreadystatechange = update;
        ajax.send(null);
//		document.getElementById("B_Edit").innerHTML = 
//					'<input type="button" name="Submit_ins" id="Submit_ins" value="Печать" onClick="window.print();" />';
	}
//---------------------------------------------------------------------------------
	function new_cust() {
	var form, con;
		form = document.forms.ulaForm;
		if (form.Menu_Item.value == "recon") {
			ajax.open(smetod, "do_ins_cust"+cor_str(), true);
			} 
		if (form.Menu_Item.value == "con3w") {
			ajax.open(smetod, "do_con3w.php?Login="+form.Login.value, true);
			alert("выполнение подключения к интернету");
			}
			
		nxt_el = "B_Sub";
		ajax.onreadystatechange = update;
        ajax.send(null);
	}
//---------------------------------------------------------------------------------
	function str2date(s_date) {
		i_M = s_date.indexOf("-", 0);
		s_Y = s_date.substring(0, i_M);
		i_D = s_date.lastIndexOf("-");
		s_M = s_date.substring(1*i_M+1, i_D)-1;
		s_D = s_date.substring(i_D+1);
		return new Date(s_Y, s_M, s_D);
	}
//---------------------------------------------------------------------------------
	function time2Y_m_d(s_time) {
		return s_time.getYear()+"-"+(s_time.getMonth()+1)+"-"+s_time.getDate();
	}
//---------------------------------------------------------------------------------
	function frz_cust() {
		form = document.forms.ulaForm;
		Bill_Dog = (!document.getElementById("tabl_cust"))?0:form.tabl_cust.options[form.tabl_cust.selectedIndex].value;

		v_Date_end_st = str2date(form.Date_end_st.value);
		v_Date_start_fr = str2date(form.Date_start_fr.value);
		v_Date_end_fr = str2date(form.Date_end_fr.value);
		n_Date_end_st = new Date();
		n_Date_end_st.setTime(v_Date_end_st.getTime() + (v_Date_end_fr.getTime() - v_Date_start_fr.getTime()) + (24 * 60 * 60 * 1000));
		new_Date_end_st = time2Y_m_d(n_Date_end_st);
//		alert("Старая дата "+form.Date_end_st.value+", новая дата "+n_Date_end_st+" = "+);
		s_param = "TabNum="+form.TabNum.value+"&Bill_Dog="+Bill_Dog+"&Date_start_fr="+form.Date_start_fr.value+"&Date_end_fr="+form.Date_end_fr.value+
			"&new_Date_end="+new_Date_end_st+"&Comment="+form.Comment.value;//+"&="++"&="+id_p="+id_p+"&fl="+fl+"&//+"&inet="+form.inet_pay.value+"&Nic="+form.Nic.value
//		write_temp(s_param);
		ch_param('do_freaze', s_param, 'B_Sub');
}
//---------------------------------------------------------------------------------
	function chk_noti() {
	var form, con;
		form = document.forms.ulaForm;
//		var check = !((form.noti.value == '') or (form.mont.selectedIndex==0) or (form.Date_Plan.Value==''));
		if (!((form.noti.value == '') || (form.Date_Plan.value ==''))) { // || (form.mont.selectedIndex == 0)
				document.getElementById("B_Sub").innerHTML = 
					'<input type="button" name="Submit_cor" id="Submit_cor" value="Внести" onClick="cor_cust();" />';
		} else {document.getElementById("B_Sub").innerHTML = '';} //
	}
//---------------------------------------------------------------------------------
	function adjustmont() {
	var form, con;
		form = document.forms.ulaForm;
		if(!document.getElementById("h_Nic")) { //=""
//			con = 2;
//			document.getElementById("d_mont").innerHTML = 'заявку на новое подключение';
		}
	}
//---------------------------------------------------------------------------------
	function do_sub(reg) {
	var form, con;
		form = document.forms.ulaForm;
//		alert("Menu_Item"+form.Menu_Item.value);		//$Menu_Item = "con3w";
//		alert("Nic = "+form.Nic.value);
//			do_upd("do_con3w", "B_Sub");
		if (form.Menu_Item.value == "recon") {
			form.Nic.value = form.h_Nic.value;
			if(form.h_Nic.value!="") con = 2;
			ajax.open(smetod, "do_recon.php?con="+"&Nic="+form.Nic.value, true);
			alert("выполнение подключения к сети Селена "+ reg);
			} 
		if (form.Menu_Item.value == "con3w") {
			ajax.open(smetod, "do_con3w.php?Login="+form.Login.value, true);
			alert("выполнение подключения к интернету");
			}
			
		nxt_el = "B_Sub";
		ajax.onreadystatechange = update;
        ajax.send(null);
	}
//---------------------------------------------------------------------------------
	function set_adress() {
	var form = document.forms.ulaForm;
		document.getElementById("adress").innerHTML = '<a class="subHeader"><font size="3">' +
			"г."+form.id_town.options[form.id_town.selectedIndex].text +
			",  ул."+form.id_street.options[form.id_street.selectedIndex].text +
			",  д."+form.num_build.options[form.num_build.selectedIndex].text +
			",  пд."+form.h_Podjezd.value +
			",  эт."+form.h_floor.value +
			",  кв."+form.flat.options[form.flat.selectedIndex].text +
			'</ font></a>';
	}
//---------------------------------------------------------------------------------
	function set_town() {
	var form;
		form = document.forms.ulaForm;
//		form.id_town.selectedIndex=1;
		document.forms.ulaForm.id_town.options[1].selected=true;
	}
//---------------------------------------------------------------------------------
	function ins_Login() {	}
//---------------------------------------------------------------------------------
	function btn_addPod() {
	document.getElementById("B_Create").innerHTML="<button name='B_add_pd' type=button onClick='f=document.forms.ulaForm;ch_param(&quot;do_ins_pd&quot;,&quot;k=&quot;+f.h_k.value, &quot;B_Edit&quot;)'><img src='ico_create.gif' align=middle title='Добавить подъезд'></button>";//<a href=&quot;&quot;>111</a>
//	alert(document.getElementById("B_Sub").innerHTML);
	}
//---------------------------------------------------------------------------------
	function set_selrgn(sel) {
	if (sel=='on') {
		sel_bld = 1;
		document.getElementById("d_sel_bld").innerHTML = "выбранных";
	//	document.forms.ulaForm.bld2.options[1].selected=true;
	//	document.getElementById("bld").options[1].selected = true;
	//	alert("2-"+document.forms.ulaForm.bld.options[1].selected);
//		alert("1-"+document.getElementById("bld").value);
	}
	}
//---------------------------------------------------------------------------------
	function set_rgn(sel) {
//		alert(sel);
	}
//---------------------------------------------------------------------------------
	function clr_adress() {
	var form, vNic;
		form = document.forms.ulaForm;
//		alert("!");
//		document.getElementById("B_adress").innerHTML = "";
//        document.getElementById("B_adress").disabled="disabled"; //.innerHTML = ' ';
//		document.getElementById("B_get_adress").disabled="disabled";
//	document.getElementById("tab_Cust").innerHTML = '';
	document.getElementById("B_Sub").innerHTML = '';
	document.getElementById("d_Bill_Dog").innerHTML = '';//<input name="h_id_Podjezd" type="hidden" />
/*	if (form.Menu_Item.value == "recon") {
			document.getElementById("d_mont").innerHTML = '';
			form.mont[0].selected = true;
	}	*/
	if (form.Menu_Item.value == "pay") {
		if (document.getElementById("opl_to")) {
			document.getElementById("opl_to").innerHTML = '';
			document.getElementById("action").innerHTML = '';
			document.getElementById("hist_pay").innerHTML = '';
			form.abon_pay.value = "";
			form.opl_per.value = "";
			form.abon_Com.value = "";
			form.inet_pay.value = "";
			form.inet_Com.value = "";
			form.total_pay.value = "";
//			form.B_freaze.readOnly=true;
		}
	}
	if (form.Menu_Item.value == "edt_bld") {
	}
	if (form.Menu_Item.value == "cust") {
	} else {
//				form.Nic.value = form.h_Nic.value;
//		if (form.Menu_Item.value == "con3w") {
		if (document.getElementById("d_mont")) {
				document.getElementById("d_mont").innerHTML = '';
	//			form.mont[0].selected = true;
		}
		if (document.getElementById("floor")) {
				form.floor.value = "";
		}
		if (document.getElementById("net")) {
				form.Nic.value = "";
				form.id_tarifab[0].selected = true; //form.h_id_tarifab.value;
				form.tarifab_date.value = "";	//form.h_tarifab_date.value;
//				form.conn[0].selected = true;
				form.Bill_Dog.value = "";
				if (form.Menu_Item.value != "pay") {
					form.Date_start_st.value = "";
					form.Date_end_st.value = "";
					form.Date_pay.value = "";
				}
				document.getElementById("state").innerHTML = "";
		}
		if (document.getElementById("w3")) {
			form.From_Net.value = "";
			form.id_tarif3w[0].selected = true; //form.h_id_tarif3w.value;
			form.tarif3w_date.value = ""; //form.h_tarif3w_date.value;
			document.getElementById("Login").innerHTML = '';
			document.getElementById("addLogin").innerHTML = '';
//	alert("!!");
			//'логин <INPUT name="nic2login" type="button" id="nic2login" onclick="javascript:alert(form.Nic.value);form=document.forms.ulaForm; form.Login.value=form.Nic.value;adjastNet();" value="тот же"><INPUT name="Login" type="text" value="" onChange="adjastNet();" size="12">';
		}
		if (document.getElementById("fio")) {
			form.Fam.value = "";
			form.Name.value = "";
			form.Father.value = "";
			form.pasp_Ser.value = "";
			form.pasp_Num.value = "";
			form.pasp_Date.value = "";
			form.pasp_Uvd.value = "";
			form.pasp_Adr.value = "";
			form.Birthday.value = "";
		}
		if (document.getElementById("phn")) {
			form.phone_Home.value = "";
			form.phone_Cell.value = "";
			form.phone_Work.value = "";
			form.Jur.checked = 0;
		}
	}
}

//---------------------------------------------------------------------------------
	ajax = startAJAX();
</script>
<!-- ************************************************************************************************************* -->
  </head>
<body bgcolor="#F4FFE4" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<div id="Main">
<table width=800 border="0" cellspacing="0" cellpadding="0"><!--width=900-->
  <tr background="top_.gif">
    <td background="top_.gif" colspan="3" rowspan="1"><img src="top_2.gif" alt="СЕЛЕНА" width="225" height="82" border="0"></td>
    <td background="top_.gif" height="82" colspan="2" id="logo" valign="bottom" align="center" nowrap="nowrap"><h1>СЕЛЕНА</h1><font size="2">ДОМАШНЯЯ СЕТЬ И ИНТЕРНЕТ</font></td>
    <td width=130 background="top_.gif" rowspan="1" ><? if (isset($username)) {echo "Приветствую, $username<br>".$GLOBALS['pers']['NamePers']." т.№$TabNum<br>IP:",$u_IP/*,$GLOBALS['pers']['id_TypePers']*/;} ?>
      <input name="TabNum" id="TabNum" type="hidden" value="<? echo $TabNum ?>" />
      &nbsp;&nbsp;<div id="clock"></div>
 <SCRIPT LANGUAGE="JavaScript">
 <!--
 updateClock();
 function updateClock() {
     var time = new Date();
     var hours = time.getHours();
     var minutes = time.getMinutes();
     var seconds = time.getSeconds();
     //document.forms.ulaForm.face.value 
	 document.getElementById("clock").innerHTML =  ((hours < 10) ? '0' + hours : hours) +
                           ':' + ((minutes < 10) ? '0' + minutes : minutes)+
                           ':' + ((seconds < 10) ? '0' + seconds : seconds);
     setTimeout("updateClock()",1000);
 }
//-->
</SCRIPT>
	v.2010.06.16</td>
	<td background="top_right.gif" rowspan="1" width="117">	</td>
  </tr>
<!--  <tr bgcolor="#D5EDB3">  </tr> -->
  <tr>
    <td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>
  <tr>
    <td colspan="7" bgcolor="#99CC66" background="mm_dashed_line.gif"><img src="mm_dashed_line.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr bgcolor="#99CC66"><!--993300-->
  	<td colspan="1" id="dateformat" height="20" align="center"><b>&nbsp;&nbsp;<script language="JavaScript" type="text/javascript">
      document.write(TODAY); </script>&nbsp;&nbsp;</b></td>
  	<td colspan="7" id="dateformat" height="20">
    <ul id="hmenu" style="width:430px;">
<? if ($TypePers != 4) { ?>
	<li><a href="#">Абоненты</a>
		<ul>
			<li><a href='javascript:{ch_param("re_con", "tp=<? 	echo $GLOBALS['pers']['id_TypePers']; ?>", "Mform"); }'>Подключение</a></li>
			<li><a href='javascript:{op_f("pay", "Mform"); }'>Платеж</a></li>		
<!--			<li><a href='javascript:{op_f("dolgn", "Mform"); }'>Должники</a></li>-->
			<li><a href='javascript:{ch_param("dolg", "tn=<? echo $TabNum; ?>&tp=<? echo $TypePers?>", "Mform"); }'>Должники</a></li>
			<li><a href='javascript:{op_f("show_err", "Mform"); }'>Ошибки базы</a></li>
		    <? if ($TabNum==2) {?><li><a href='javascript:op_f("pay_usr", "Mform");' class="navText">> Платёжи абонента <</a></li><? }?>
			<li><a href='javascript:{ch_param("fin", "tn=<? echo $TabNum; ?>&tp=<? echo $TypePers?>&per=0", "Mform"); }'>Фин.отчёт</a></li>		
		</ul>
	</li>
<? } else { ?>
	<li><a href='javascript:{ch_param("dolg", "tn=<? echo $TabNum; ?>&tp=<? echo $TypePers?>", "Mform"); }'>Должники</a></li>
<? } ?>
<? if ($TypePers != 4) { ?>
	<li><a href="#">Заявки</a>
		<ul>
            <li><a href='javascript:ch_param("noti","tn=<? echo $TabNum?>&tp=<? echo $GLOBALS['pers']['id_TypePers']?>","Mform");'>на ремонт сети</a></li>
<? } ?>
            <li><a href='javascript:ch_param("mont","tn=<? echo $TabNum?>&tp=<? echo $TypePers?>","Mform");'><? if($TypePers == 4){echo "Заявки ".$GLOBALS['pers']['Fam']; } else {?>монтажников<? }?></a>
			<!--&nbsp;<select name="mont" class='font8pt' id="mont" lang="ru"
		    onchange='op_f("mont", "Mform");alert("!!!"); document.getElementById("Mform").innerHTML = ""; ch_param("mont", "mont=<? echo $_REQUEST ["mont"]; ?>, "Mform");'>
<?php	$q_mont = "SELECT * FROM `personal` WHERE `id_TypePers`<4";
		$mont = mysql_query($q_mont) or die(mysql_error());
		$row_mont = mysql_fetch_assoc($mont);
		$totalRows_mont = mysql_num_rows($mont);
    echo "<option value=0>выбрать</option>";
do {
    echo "<option value=".$row_mont['TabNum'];
	if (isset($_REQUEST ["mont"]) && ($row_mont["mont"]==$_REQUEST["mont"])) { echo " selected"; }
	echo ">".$row_mont['Fam']." (таб.№ ".$row_mont['TabNum'].")</option>";
		} while ($row_mont = mysql_fetch_assoc($mont));
  		$rows = mysql_num_rows($mont);
  		if($rows > 0) { mysql_data_seek($mont, 0); $row_mont = mysqli_fetch_assoc($mont);  } ?>
    </select>&nbsp;--> </li>
		<? if ($TypePers != 4) { ?></ul><? }?>
	</li>
	<li><a href="#">Справочники</a>
		<ul>
			<li><a href='javascript:op_f("edt_bld_frm", "Mform");'>адресов</a></li><? /* add_build */ ?>
			<li><a href='javascript:op_f("equip", "Mform");'>оборудования</a></li>
<?php /*?>			<li><a href="#">персонала</a></li>		<?php */?>
		</ul>
	</li>
	<li><a href="#">Пользователь</a>
		<ul>
			<li><a href="change_passwd_form.php">Сменить пароль</a></li>
			<li><a href="logout.php">Выход</a></li>		</ul>
	</li>
</ul>
</td>  	
  </tr>
  <tr>
    <td colspan="7" bgcolor="#99CC66" background="mm_dashed_line.gif"><img src="mm_dashed_line.gif" alt="line decor" width="4" height="3" border="0" /></td>
  </tr>

  <tr>
    <td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
  </tr>

 <tr>
   	<td colspan="7" valign="top"><div id="Mform"></div>

<!-- <p>
  <input type="button" name="Submit" value="Подтвердить" onClick="do_sub();" />
  <input name="Cancel" type="reset" value="Отменить" />
  <!--<input name="Subm" type="button" onclick="show_query()" value="запрос" />   <input name="get_Cod_flat" id="get_Cod_flat" type="hidden";/>  value=fget_Cod_flat() -- >
</p> -->

</td>  	
  </tr>
  <tr>
<td>  	
<p><div id="temp_area"></div></p></td>

    <!--  <td width="2"><img src="mm_spacer.gif" alt="" width="10" height="1" border="0" /></td>-->
</td>  	
  </tr>
  <tr>
    <td width="150">&nbsp;</td>
    <td width="8">&nbsp;</td>
    <td width="70">    </td>
    <td width="297"></td>
    <td>&nbsp;</td>
	<td width="90">&nbsp;</td>
	<td></td>
  </tr>
</table>

<!--<table width="200" border="1">
  <tr>
    <td rowspan="3" >&nbsp;</td>
    <td>&nbsp;</td>
    <td rowspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<table width="200" border="1">
  <tr>
	<td rowspan="3">&nbsp;</td>
    <td>1</td>
    <td rowspan="3" valign="bottom">&nbsp;</td>
  </tr>
  <tr> <td>2</td> </tr>
  <tr> <td>3</td> </tr>
</table>-->
</div>
</form>

<? do_html_footer(); ?>
