<meta charset="utf-8">
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
    <form name=ulaForm method="POST">
        <?
$TypePers = f_get_TypePers($username);
$TabNum = f_get_TabNum($username);
$GLOBALS['pers'] = f_get_Pers($username);
$u_IP = $_SERVER["REMOTE_ADDR"];
/*<script type="text/javascript" src="dojo.js"></script>
<script language="JavaScript" type="text/javascript">
      dojo.require("dojo.date");
//<script type="text/javascript">	*/
?>
            <link rel="stylesheet" type="text/css" href="selena.css" />
            <link rel="stylesheet" type="text/css" href="menu.css" />

            <script language="JavaScript" type="text/javascript">
                //--------------- LOCALIZEABLE GLOBALS ---------------
                function date_getDaysInMonth(_1) {
                    var _2 = _1.getMonth();
                    var _3 = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    if (_2 == 1 && date_isLeapYear(_1)) {
                        return 29;
                    }
                    return _3[_2];
                };

                function date_isLeapYear(_4) {
                    var _5 = _4.getFullYear();
                    return !(_5 % 400) || (!(_5 % 4) && !!(_5 % 100));
                };

                function date_getTimezoneName(_6) {
                    var _7 = _6.toString();
                    var tz = "";
                    var _8;
                    var _9 = _7.indexOf("(");
                    if (_9 > -1) {
                        tz = _7.substring(++_9, _7.indexOf(")"));
                    } else {
                        var _a = /([A-Z\/]+) \d{4}$/;
                        if ((_8 = _7.match(_a))) {
                            tz = _8[1];
                        } else {
                            _7 = _6.toLocaleString();
                            _a = / ([A-Z\/]+)$/;
                            if ((_8 = _7.match(_a))) {
                                tz = _8[1];
                            }
                        }
                    }
                    return (tz == "AM" || tz == "PM") ? "" : tz;
                };

                function date_compare(_b, _c, _d) {
                    _b = new Date(+_b);
                    _c = new Date(+(_c || new Date()));
                    if (_d == "date") {
                        _b.setHours(0, 0, 0, 0);
                        _c.setHours(0, 0, 0, 0);
                    } else {
                        if (_d == "time") {
                            _b.setFullYear(0, 0, 0);
                            _c.setFullYear(0, 0, 0);
                        }
                    }
                    if (_b > _c) {
                        return 1;
                    }
                    if (_b < _c) {
                        return -1;
                    }
                    return 0;
                };

                function date_add(_e, _f, _10) {
                    var sum = new Date(+_e);
                    var _11 = false;
                    var _12 = "Date";
                    switch (_f) {
                        case "day":
                            break;
                        case "weekday":
                            var _13, _14;
                            var mod = _10 % 5;
                            if (!mod) {
                                _13 = (_10 > 0) ? 5 : -5;
                                _14 = (_10 > 0) ? ((_10 - 5) / 5) : ((_10 + 5) / 5);
                            } else {
                                _13 = mod;
                                _14 = parseInt(_10 / 5);
                            }
                            var _15 = _e.getDay();
                            var adj = 0;
                            if (_15 == 6 && _10 > 0) {
                                adj = 1;
                            } else {
                                if (_15 == 0 && _10 < 0) {
                                    adj = -1;
                                }
                            }
                            var _16 = _15 + _13;
                            if (_16 == 0 || _16 == 6) {
                                adj = (_10 > 0) ? 2 : -2;
                            }
                            _10 = (7 * _14) + _13 + adj;
                            break;
                        case "year":
                            _12 = "FullYear";
                            _11 = true;
                            break;
                        case "week":
                            _10 *= 7;
                            break;
                        case "quarter":
                            _10 *= 3;
                        case "month":
                            _11 = true;
                            _12 = "Month";
                            break;
                        default:
                            _12 = "UTC" + _f.charAt(0).toUpperCase() + _f.substring(1) + "s";
                    }
                    if (_12) {
                        sum["set" + _12](sum["get" + _12]() + _10);
                    }
                    if (_11 && (sum.getDate() < _e.getDate())) {
                        sum.setDate(0);
                    }
                    return sum;
                };

                function date_difference(_17, _18, _19) {
                    _18 = _18 || new Date();
                    _19 = _19 || "day";
                    var _1a = _18.getFullYear() - _17.getFullYear();
                    var _1b = 1;
                    switch (_19) {
                        case "quarter":
                            var m1 = _17.getMonth();
                            var m2 = _18.getMonth();
                            var q1 = Math.floor(m1 / 3) + 1;
                            var q2 = Math.floor(m2 / 3) + 1;
                            q2 += (_1a * 4);
                            _1b = q2 - q1;
                            break;
                        case "weekday":
                            var _1c = Math.round(date_difference(_17, _18, "day"));
                            var _1d = parseInt(date_difference(_17, _18, "week"));
                            var mod = _1c % 7;
                            if (mod == 0) {
                                _1c = _1d * 5;
                            } else {
                                var adj = 0;
                                var _1e = _17.getDay();
                                var _1f = _18.getDay();
                                _1d = parseInt(_1c / 7);
                                mod = _1c % 7;
                                var _20 = new Date(_17);
                                _20.setDate(_20.getDate() + (_1d * 7));
                                var _21 = _20.getDay();
                                if (_1c > 0) {
                                    switch (true) {
                                        case _1e == 6:
                                            adj = -1;
                                            break;
                                        case _1e == 0:
                                            adj = 0;
                                            break;
                                        case _1f == 6:
                                            adj = -1;
                                            break;
                                        case _1f == 0:
                                            adj = -2;
                                            break;
                                        case (_21 + mod) > 5:
                                            adj = -2;
                                    }
                                } else {
                                    if (_1c < 0) {
                                        switch (true) {
                                            case _1e == 6:
                                                adj = 0;
                                                break;
                                            case _1e == 0:
                                                adj = 1;
                                                break;
                                            case _1f == 6:
                                                adj = 2;
                                                break;
                                            case _1f == 0:
                                                adj = 1;
                                                break;
                                            case (_21 + mod) < 0:
                                                adj = 2;
                                        }
                                    }
                                }
                                _1c += adj;
                                _1c -= (_1d * 2);
                            }
                            _1b = _1c;
                            break;
                        case "year":
                            _1b = _1a;
                            break;
                        case "month":
                            _1b = (_18.getMonth() - _17.getMonth()) + (_1a * 12);
                            break;
                        case "week":
                            _1b = parseInt(date_difference(_17, _18, "day") / 7);
                            break;
                        case "day":
                            _1b /= 24;
                        case "hour":
                            _1b /= 60;
                        case "minute":
                            _1b /= 60;
                        case "second":
                            _1b /= 1000;
                        case "millisecond":
                            _1b *= _18.getTime() - _17.getTime();
                    }
                    return Math.round(_1b);
                };

                //--------------- LOCALIZEABLE GLOBALS ---------------
                //java.util.Date d;
                var d = new Date();
                var monthname = new Array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
                var mname = new Array("янв", "фев", "мар", "апр", "мая", "июн", "июл", "авг", "сен", "окт", "ноя", "дек");
                var TODAY = d.getDate() + "-е " + monthname[d.getMonth()] + ", " + d.getFullYear() + "г.";
                var TODAY2 = d.getFullYear() + "-" + (1 * d.getMonth() + 1 < 10 ? "0" : "") + (1 * d.getMonth() + 1) + "-" + d.getDate();
                //var TODAY3 = "&laquo;<u>"+d.getDate() + "</u>&raquo; <u> " + monthname[d.getMonth()] + " </u> " + d.getFullYear()+"г.";
                var TODAY3 = "'<u> " + d.getDate() + " </u>' <u> " + monthname[d.getMonth()] + " </u> " + d.getFullYear() + "г.";
                var smetod = "POST"; //GET &nbsp; &quot;
                var nxt_el = "";
                var nxt_vl = "";
                var conn = 0;
                var sel_bld = 0;
                var macPattern = /^([0-9A-F]{1}[0-9A-F]{1})\-([0-9A-F]{1}[0-9A-F]{1})\-([0-9A-F]{1}[0-9A-F]{1})\-([0-9A-F]{1}[0-9A-F]{1})\-([0-9A-F]{1}[0-9A-F]{1})\-([0-9A-F]{1}[0-9A-F]{1})$/;
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
                    el.style.display = (el.style.display != 'none') && 'none' || '';
                }
                //---------------------------------------------------------------------------------
                function shw(obj) {
                    var el = document.getElementById(obj).style.display = '';
                }
                //---------------------------------------------------------------------------------
                function hid(obj) {
                    var el = document.getElementById(obj).style.display = 'none';
                }
                //---------------------------------------------------------------------------------
                function handleError(message, URI, line) {
                    // alert the user that this page may not respond properly write_temp
                    alert(message + " " + URI + " в строке - " + line);
                    return true; // this will stop the default message
                }
                //---------------------------------------------------------------------------------
                function upd_() {
                    if (ajax.readyState == 4)
                        if (ajax.status == 200) {
                            document.getElementById(nxt_el).innerHTML = document.getElementById(nxt_vl).value;
                        }
                }
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
                function write_temp(txt_msg) {
                    document.getElementById("temp_area").innerHTML = txt_msg + "  " + '<input name="B_clr_temp" type="button" onclick="clr_temp();" value="Очистить" />';
                    return;
                }
                //---------------------------------------------------------------------------------
                function clr_temp() {
                    document.getElementById("temp_area").innerHTML = "";
                }
                //---------------------------------------------------------------------------------
                function ch_flt(prgm, em, upd_elm) {
                    var f, idx, st, i;
                    document.getElementById(upd_elm).innerHTML = "<img src='load.gif' alt='' />";
                    f = document.forms.ulaForm;
                    id_korp = f.num_build.options[f.num_build.selectedIndex].value;
                    nxt_el = upd_elm;
                    ajax.onreadystatechange = update;
                    ajax.open(smetod, prgm + ".php?id_korp=" + id_korp + "&fl=" + em.value, true);
                    ajax.send(null);
                }
                //---------------------------------------------------------------------------------
                function ch_temp(n_div, txt_cont) {
                    document.getElementById(n_div).innerHTML = txt_cont;
                    nxt_el = n_div;
                    ajax.onreadystatechange = update;
                    ajax.send(null);
                }
                //---------------------------------------------------------------------------------
                function ch_val(n_div, val_txt) {
                    document.getElementById(n_div).value = val_txt;
                    nxt_el = n_div;
                    ajax.onreadystatechange = upd_;
                    ajax.send(null);
                }
                //---------------------------------------------------------------------------------
                function op_f(prgm, upd_elm) {
                    document.getElementById(upd_elm).innerHTML = "<center><img src='load.gif' alt='' /></center>"; //wait.gif Подождите ...
                    nxt_el = upd_elm;
                    ajax.onreadystatechange = update;
                    ajax.open(smetod, prgm + ".php", true);
                    ajax.send(null);
                }
                //---------------------------------------------------------------------------------
                function ch_param(prgm, param, upd_elm) {
                    document.getElementById(upd_elm).innerHTML = "<center><img src='load.gif' alt='' /></center>";
                    nxt_el = upd_elm;
                    ajax.onreadystatechange = update;
                    ajax.open(smetod, prgm + ".php?" + param, true);
                    ajax.send(null);
                }
                //---------------------------------------------------------------------------------
                function ch(prgm, prm, em, upd_elm) {
                    var f;
                    document.getElementById(upd_elm).innerHTML = "<center><img src='load.gif'/></center>";
                    f = document.forms.ulaForm;
                    //write_temp(prgm+".php?"+prm+"=`"+em.value+"` =>"+upd_elm);	//write_temp(em.name+"="+em.value);
                    if (document.getElementById("B_Create")) {
                        document.getElementById("B_Create").innerHTML = '';
                    }
                    if (document.getElementById("B_Edit")) {
                        document.getElementById("B_Edit").innerHTML = '';
                    }
                    if (document.getElementById("B_Sub")) {
                        document.getElementById("B_Sub").innerHTML = '';
                    }
                    //		document.getElementById("tab_Cust").innerHTML = "";
                    if (prgm == "ch_Login") {} else {
                        //		document.getElementById("B_adress").innerHTML =""; 
                        //    	document.getElementById("B_Sub").innerHTML = ' ';
                        //		document.getElementById("tab_Cust").innerHTML = "";
                    }
                    //		document.getElementById("B_get_adress").disabled="disabled";
                    if (prgm == "ch_flt") {
                        if (upd_elm == "tab_Custnew") { // выбор адреса для перевода адреса
                            if (prm.indexOf("Nic", prm) == -1) {
                                sq = "ch_flt.php?" + prm +
                                    "&st=" + f.id_streetnew.options[f.id_streetnew.selectedIndex].value +
                                    "&Num_build=" + f.num_buildnew.options[f.num_buildnew.selectedIndex].value +
                                    "&fl=" + f.flatnew.options[f.flatnew.selectedIndex].value +
                                    "&Bill_Dog=" + f.tabl_cust.options[f.tabl_cust.selectedIndex].value +
                                    "&menu=new_adr";
                            } else {
                                sq = "ch_flt.php?" + prm +
                                    "&st=" + f.id_streetnew.options[f.id_streetnew.selectedIndex].value +
                                    "&Num_build=" + f.num_buildnew.options[f.num_buildnew.selectedIndex].value +
                                    "&fl=" + f.flatnew.options[f.flatnew.selectedIndex].value
                                /*+
                                						"&menu=new_adr"*/
                                ;
                                //		alert(prm+" "+sq);sq//em.value;h_st f.Town.value
                                //"&Bill_Dog="+f.tabl_cust.options[f.tabl_cust.selectedIndex].value+	
                                //						alert(sq);//em.value;h_st f.Town.value
                            }
                        } else {
                            btn_reload();
                            f.sBill_Dog.value = '';
                            f.sCod_flat.value = '';
                            f.sNic.value = '';
                            document.getElementById("dCod_flat").innerHTML = '';
                            document.getElementById("dBill_Dog").innerHTML = '';
                            document.getElementById("dNic").innerHTML = '';
                            sq = "ch_flt.php?" + prm +
                                "&st=" + f.id_street.options[f.id_street.selectedIndex].value +
                                "&k=" + f.num_build.options[f.num_build.selectedIndex].value +
                                "&fl=" + f.flat.options[f.flat.selectedIndex].value;
                            if (f.sBill_Dog.value != '') {
                                prm_v = document.getElementById("sBill_Dog").value;
                                sq = 'srch.php?menu=' + f.Menu_Item.value + '&tp=' + f.tp.value + '&Bill_Dog=' + prm_v;
                                upd_elm = 'tab_Cust';
                                em = 0;
                            } else
                            if (f.sCod_flat.value != '') {
                                prm_v = document.getElementById("sCod_flat").value;
                                sq = 'srch.php?menu=' + f.Menu_Item.value + '&tp=' + f.tp.value + '&Cod_flat=' + prm_v;
                                upd_elm = 'tab_Cust';
                                em = 0;
                            }
                        }
                    } else {
                        sq = prgm + ".php?" + prm + (em == 0 ? "" : "=" + em.value);
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
                    dv = 'Main'; // tab_Cust B_Create Mform
                    f = document.forms.ulaForm;
                    Bill_Dog = f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    param = "st=" + f.id_street.options[f.id_street.selectedIndex].value +
                        "&Num_build=" + f.num_build.options[f.num_build.selectedIndex].value +
                        "&fl=" + f.flat.options[f.flat.selectedIndex].value +
                        "&Bill_Dog=" + Bill_Dog +
                        "&name_street=" + f.id_street.options[f.id_street.selectedIndex].text +
                        "&Login=" + f.Login.options[f.Login.selectedIndex].text +
                        "&tarif3w=" + f.id_tarif3w.options[f.id_tarif3w.selectedIndex].text +
                        "&Tday=" + TODAY3;
                    //	    document.getElementById(dv).innerHTML = "<table width=100%><tr><td align='center'><img src='load.gif'/></td></tr></table>";
                    window.open("dogovor.php?" + param, "dogovor", "width=750,height=600,status=yes"); //
                    //		ch_param("dogovor", param, dv);//tab_Cust
                    window.print();
                }
                //---------------------------------------------------------------------------------
                function f_btn() {
                    document.getElementById("B_Sub").innerHTML =
                        '<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();"/>   ' +
                        '<input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();"/>';
                }
                //---------------------------------------------------------------------------------
                function btn_reload() {
                    document.getElementById("B_adress").innerHTML =
                        '<button name="B_chk_adress" id="B_chk_adress" type=button onClick=\'eval(document.getElementById("refr").value)\'>' +
                        '<img src="reload.png" align=middle alt="Обнови"></button>'; //srch() ch("ch_flt","menu='+document.forms.ulaForm.Menu_Item.value+'&",2,"tab_Cust");
                }
                //---------------------------------------------------------------------------------
                /*function fnew_Cod_flat() {
                	document.getElementById("temp_area").innerHTML = "";
                	op_f("new_Cod_flat", "temp_area");
                	return document.forms.ulaForm.get_Cod_flat.value; //setTimeout('',300);
                }	*/
                //---------------------------------------------------------------------------------
                function val_nm(Bill_Dog, nm) {
                    return document.getElementById("h_" + Bill_Dog + "_" + nm).value;
                }
                //---------------------------------------------------------------------------------
                function set_new_adr() {
                    var f = document.forms.ulaForm;
                    var tabl_cust = document.getElementById("tabl_cust");
                    var Bill_Dog = (tabl_cust.options[tabl_cust.selectedIndex].value);
                    var param = "id_p=" + f.h_id_Podjezdnew.value + "&fl=" + f.flatnew.options[f.flatnew.selectedIndex].value + "&floor=" + f.floornew.value +
                        "&Bill_Dog=" + Bill_Dog + "&Cod_flat=" + f.h_Cod_flatnew.value + "&TabNum=" + f.TabNum.value;
                    // + "&menu=new_adr"+"&mont="+f.mont.options[f.mont.selectedIndex].value		+"&DateKor="+f.n_date.value
                    ch_param("do_new_adr", param, "new_adr"); //	tab_Cust	//	B_Create
                    //		f.B_edt_cust.style.display = "none";
                    //		"";'+
                    //			'document.forms.ulaForm.sCod_flat.value = f.h_Cod_flatnew.value;'+
                    //			'document.forms.ulaForm.sCod_flat.onchange();
                }
                //---------------------------------------------------------------------------------
                function f_frm(dv) {}
                //---------------------------------------------------------------------------------
                function Sel(name, rgb) {
                    this.name = name
                    this.rgb = rgb
                }

                function f_get(ar, dv, ky) {}
                //---------------------------------------------------------------------------------
                function f_sel(dv) {
                    var mySel = new Sel('pay', '#66FF99')
                    //		alert(asel.filter( function(item){ return item.el == 'pay'; } )[0].rgb );
                    //   		.forEach( function(item){ alert(item.rgb); } );
                    //		asel.forEach( function(item){ alert(item); } );
                    //		for (var key in asel) {     var val = asel [key];     alert (key+' = '+val); } 
                    var asel = [{
                            'nm': 'pay',
                            'rgb': '#66FF99',
                            'prg': '',
                            'bd': ''
                        },
                        {
                            'nm': 'all',
                            'rgb': '#CCFF99',
                            'prg': '',
                            'bd': ''
                        },
                        {
                            'nm': 'frz',
                            'rgb': '#99FFFF',
                            'prg': '',
                            'bd': ''
                        },
                        {
                            'nm': 'hist_pay',
                            'rgb': '#E5E5E5',
                            'prg': 'hist_pay',
                            'bd': '+'
                        },
                        {
                            'nm': 'hist_cod',
                            'rgb': '#CCCCCC',
                            'prg': 'hist_cod',
                            'bd': '+'
                        },
                        {
                            'nm': 'hist_not',
                            'rgb': '#0CF',
                            'prg': 'hist_not',
                            'bd': '+'
                        }
                    ];
                    f = document.forms.ulaForm;
                    f.sel.value = dv;
                    Bill_Dog = (document.getElementById("tabl_cust")) ? f.tabl_cust.options[f.tabl_cust.selectedIndex].value : 0;
                    dv_BD = dv + Bill_Dog;
                    ab_s = document.getElementById('h_' + Bill_Dog + '_ab_sum').value;
                    ab_n = f.h_ab_numbs.value;
                    asel.forEach(function(e) {
                        document.getElementById('l' + e.nm).style.backgroundColor = '#FFF';
                        document.getElementById(e.nm).style.display = 'none';
                    });
                    el = asel.filter(function(item) {
                        return item.nm == dv;
                    })[0];
                    param = "tp=" + f.tp.value + "&tn=" + f.TabNum.value + "&Bill_Dog=" + Bill_Dog + "&ab_s=" + ab_s + "&ab_n=" + ab_n;
                    document.getElementById('l' + dv).style.backgroundColor = el.rgb;
                    document.getElementById(dv).style.display = '';
                    if (el.prg != '') {
                        if ((Bill_Dog != f.selBill.value) && (document.getElementById(dv + f.selBill.value))) {
                            hid(dv + f.selBill.value);
                        }
                        if (document.getElementById(dv_BD)) {
                            shw(dv_BD);
                        } else {
                            document.getElementById(dv).innerHTML += '<div id="' + dv_BD + '"></div>';
                            ch_param(el.prg, param, dv_BD);
                        }
                    }
                    f.selBill.value = Bill_Dog;
                }
                //---------------------------------------------------------------------------------
                function chk_adress() {
                    var f, vNic;
                    f = document.forms.ulaForm;
                    //		if (f.id_town.selectedIndex > 0) {
                    //for(i=0; i<elems.length; i++) alert(elems[i].id)
                    //alert (document.body.getElementsByName(nm).innerHTML);//.value
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    /*			document.getElementById("B_Sub").innerHTML =
                    					'<input type="button" name="Submit_ins" id="Submit_ins" value="Создать'+
                    					((Bill_Dog>0)?" доп.подключение (новый договор)":"") +'" onClick="ins_cust();" />';
                    				*/
                    if (document.getElementById("adress")) {
                        f.floor.value = val_nm(Bill_Dog, "floor");
                        //			f.Cod_flat.value	= val_nm(Bill_Dog, "Cod_flat");
                    }
                    //		if (f.Menu_Item.value == "con3w") {	}
                    if (document.getElementById("w3")) {
                        //					document.getElementById("addLogin").innerHTML = '';
                        //		document.getElementById("d_Bill_Dog").innerHTML = val_nm(Bill_Dog, "Bill_Dog");
                        f.From_Net.value = val_nm(Bill_Dog, "From_Net");
                        //alert(document.getElementById("h_"+Bill_Dog+"_"+"Logins").value);
                        var num_L = val_nm(Bill_Dog, "Logins");
                        /*****		Если логинов нет - предлагать добавление
                        if (num_L == "0" && confirm("Обнаружено отсутствие логина в базе данных."+
                        					"Вставить в базу логин такой же как ник - '"+val_nm(Bill_Dog, "Nic")+
                        					"', тариф интернет - 'Стандарт' и датой его установления - сегодня? ")) {
                        	ch_param("ins_Login", 'Bill_Dog='+f.Bill_Dog.value+'&Nic='+f.Nic.value+'&Login='+f.Nic.value+
                        		'&id_tarif3w=1&tarif3w_date="'+TODAY2 + '"', "B_Sub"); //'+val_nm(Bill_Dog, "id_tarif3w") + '		tarif3w_date.value 
                        	document.getElementById("h_"+Bill_Dog+"_"+"Login1").value = f.Nic.value;
                        	document.getElementById("h_"+Bill_Dog+"_"+"Logins").value = 1;
                        	num_L = 1;
                        }		****/
                        if (num_L == "0") {
                            /*	document.getElementById("Login").innerHTML = 'логин '+
                            		(f.Menu_Item.value!="pay"?'<input type="button" onclick="faddLogin();" value="+" />':'<b>отсутствует!</b>');
                            */
                            /* добавить ли adjastNet(); ??? */
                            /*'логин <INPUT name="nic2login" type="button" id="nic2login" '+
                            	'onclick="f=document.forms.ulaForm; f.Login.value=f.Nic.value;'+
                            	'adjastNet();" value="тот же"><INPUT name="Login" type="text" value="" onChange="adjastNet();" size="12">'; */
                            //				alert(val_nm(Bill_Dog, "Logins"));
                        }
                        if (num_L > "0") {
                            h_N = "h_" + Bill_Dog + "_";
                            t_d = h_N + 'tarif3w_date';
                            get_E = 'document.getElementById'; //'+get_E+'(&quot;id_tarif3w&quot;)[this.value].selected = true;'+
                            set_ff = 'ff=document.forms.ulaForm; ';
                            str_onCh = ' onchange="' + set_ff + 'ff.id_tarif3w[ff.Login.selectedIndex].selected = true;' +
                                get_E + '(\'tarif3w_date\').value = ' +
                                get_E + '(\'' + h_N + 'tarif3w_date\'+this.value).value;"'; //
                            N_sze = 1 + num_L; //math.abs('+5+'

                            str_L = '<td><select name="Login" id="Login" class="navText" size="' + num_L + '"' +
                                ' onchange="adjustLogin()" >';
                            for (var i = 1; i <= num_L; i++) { //
                                if (document.getElementById(h_N + "Login" + i)) {
                                    var nmLogin = document.getElementById(h_N + "Login" + i).value;
                                    str_L += '<option value=' + nmLogin + '>№' + document.getElementById(h_N + "account" + i).value + ', ' +
                                        nmLogin + ', ' + document.getElementById(h_N + "saldo" + i).value + 'руб.' +
                                        '</option>';
                                }
                            }
                            str_L += '</select>' +
                                //		(f.Menu_Item.value!="pay"?'<input type="button" onclick="faddLogin();" value="+" />':'')+
                                '</td>';
                            document.getElementById("Login").innerHTML = str_L;
                            f.Login[0].selected = true;
                            f.Login.onchange();
                        }
                        //				alert(f.tarif3w+" "+val_nm(Bill_Dog, "id_tarif3w"));
                        //		f.id_tarif3w[val_nm(Bill_Dog, "id_tarif3w")].selected = true;
                        //		f.tarif3w_date.value = val_nm(Bill_Dog, "tarif3w_date");
                        //		f.Login.value = val_nm(Bill_Dog, "Login1"); // dont only 1
                    }
                    v_inet = val_nm(Bill_Dog, "inet");
                    f.B_not.disabled = v_inet ? "disabled" : "";
                    f.Nic.value = val_nm(Bill_Dog, "Nic");
                    v_Date_start_st = val_nm(Bill_Dog, "Date_start_st");
                    v_Date_end_st = val_nm(Bill_Dog, "Date_end_st");
                    v_Date_pay = val_nm(Bill_Dog, "Date_pay");
                    ar_s = new Array('не устан.', 'подключен', 'замороз.', 'расторг');
                    ar_c = new Array('#333333', '#33CC66', '#0000FF', '#00FFFF');
                    n_st = val_nm(Bill_Dog, "state");
                    i_st = n_st == '' ? 0 : n_st;
                    f.Bill_Dog.value = val_nm(Bill_Dog, "Bill_Dog");
                    f.mac.value = mac2MAC(val_nm(Bill_Dog, "mac"));
                    t = val_nm(Bill_Dog, "id_tarifab"); // № тарифа
                    document.getElementById('nNic').style.display = v_inet ? '' : 'none';
                    if (document.getElementById("net").style.display == '') { //
                        hid('p_net');
                        hid('p_net_tab');
                        shw('net');
                        shw('net_tab');
                        shw('rec_itog');
                        document.getElementById('abon_pay').style.display = v_inet ? 'none' : '';
                        //		(document.getElementById("tabl_cust")) {
                        //		v_id_tarifab = val_nm(Bill_Dog, "id_tarifab");
                        //		t = v_id_tarifab;
                        //		t = f.id_tar_con.options[f.id_tar_con.selectedIndex].value; // № тарифа
                        /*		h_ab_numbs = document.getElementById('h_ab_numbs')?document.getElementById('h_ab_numbs').value:1;
                        		ab_sum = (round($arr_cust[$Bill_Dog]['ab_sum']/2*(1+1/($GLOBALS['ab_numbs']>0?$GLOBALS['ab_numbs']:1)))):'';	*/
                        nm = '&nbsp;' + val_nm(Bill_Dog, "name_ab") + '&nbsp;';
                        document.getElementById("con_s").innerHTML = t > 0 ?
                            'Абон.тариф <b><font' + (t == 3 ? ' size="+1" style="background-color:#FF0000" color="#FFFF00">' + nm + '</font></b>' : '>' +
                                nm + '</font></b>&nbsp;' + f.opl_mon.value + ' руб./мес.') : '';
                        //val_nm(Bill_Dog, "ab_sum")	val_nm(Bill_Dog, "ab_sum")
                        //"h_id_"+t		document.getElementById("h_nm_"+t).value
                        //	!*!*!				f.id_tarifab[val_nm(Bill_Dog, "id_tarifab")].selected = true;
                        //		f.tarifab_date.value = val_nm(Bill_Dog, "tarifab_date");
                        //	ar_s = new Array('"#333333">&nbsp;не устан.','"#33CC66">&nbsp;подключен','"#0000FF">&nbsp;замороз.','"#00FFFF">&nbsp;расторг');
                        //document.getElementById("state").innerHTML = '<font style="border:solid" color='+ar_s[i_st]+'&nbsp;</font>';
                        document.getElementById("state").style = 'border:solid ' + ar_c[i_st];
                        document.getElementById("state").innerHTML = '<b>' + ar_s[i_st] + '</b>';
                        document.getElementById("state").innerHTML +=
                            ' с <input name="Date_start_st" value="' + v_Date_start_st + '" size="8" type="date"/>' +
                            ' по <input name="Date_end_st" value="' + v_Date_end_st + '" size="8" type="date" onchange="document.forms.ulaForm.Date_pay.value=this.value;adj_CPay_act()"/>';
                        //		document.getElementById("net").style.display = (val_nm(Bill_Dog, "inet")==1 && f.Menu_Item.value == "pay")?"none":"";
                        document.getElementById("Date_pay").innerHTML = ' оплачено по <b>' + //d2str2(new Date(v_Date_pay))+
                            '<input name="Date_pay" value="' + v_Date_pay + '"  size="8"/>';
                        //				alert(f.selBill.value+' '+Bill_Dog);
                        if (f.Menu_Item.value == "pay") { //document.getElementById('fin').style.display ==''
                            //f.Menu_Item.value == "pay") {//'<font style="border:solid" color='+ar_s[i_st]+
                            hid('rec_itog');
                            hid('net_tab');
                            shw('p_net');
                            document.getElementById('sel').style.display = v_inet ? 'none' : '';
                            //		document.getElementById('net_tab').style.display = v_inet?'none':'';
                            document.getElementById('p_net_tab').style.display = v_inet ? 'none' : '';
                            document.getElementById('p_net').style.display = v_inet ? 'none' : '';
                            document.getElementById('inet_tab').style.display = v_inet ? 'none' : '';
                            document.getElementById('it_inet').style.display = v_inet ? '' : 'none';
                            /*		hid('net');shw('net');shw('p_net');shw('p_net_tab');*/
                            d_Date_start_st = new Date(v_Date_start_st);
                            d_Date_end_st = new Date(v_Date_end_st);
                            d_Date_pay = new Date(v_Date_pay);
                            y1 = d_Date_start_st.getYear();
                            y2 = d_Date_end_st.getYear(); //d2strFull

                            dolg = val_nm(Bill_Dog, "dolg");
                            //			document.getElementById("a_dolg").style.display = dolg?"display":"none";
                            is_off = n_st == 2 && v_Date_end_st == '';
                            //		document.getElementById("a_dolg").style.display = is_off?"":"none";
                            document.getElementById("r_dolg").style.display = is_off ? "" : "none";
                            document.getElementById("inet_pay").style.display = dolg ? "none" : "";
                            document.getElementById("inet_rub").innerHTML = dolg ? "<b>ДОЛЖНИК !" : " руб.";
                            document.getElementById("inet_Com").style.display = dolg ? "none" : "";
                            document.getElementById("rad").innerHTML = val_nm(Bill_Dog, "rad") == 1 ? "<b>R√" : "R-";
                            document.getElementById("p_state").style = 'border:solid ' + ar_c[i_st];
                            document.getElementById("p_state").innerHTML = '<b>' + ar_s[i_st] + '</b>';
                            document.getElementById("p_state").innerHTML += (v_Date_start_st != '' ? ' с <b>' + (y1 == y2 ? d2strS2(d_Date_start_st) : d2str2(d_Date_start_st)) + '</b>' : '') +
                                (v_Date_end_st != '' ? ' по <b>' + d2str2(d_Date_end_st) + '</b>' :
                                    is_off ? ' за долг' : '') +
                                //		'<input name="Date_start_st" type="hidden" value="'+v_Date_start_st+'" />'+
                                //		'<input name="Date_end_st" type="hidden" value="'+v_Date_end_st+'" />'+
                                '&nbsp;'; //</font>
                            f.Date_start_st.value = v_Date_start_st;
                            f.Date_end_st.value = v_Date_end_st;
                            //			f.B_freaze.style.display = (v_Date_pay=='' || f.h_new_Cod.value==1)?"none":"";
                            document.getElementById("p_Date_pay").innerHTML = v_Date_pay == '' || v_Date_pay == '0000-00-00' ? '' : ' оплачено по <b>' + d2str2(new Date(v_Date_pay)); //+
                            //		'</b><input name="Date_pay" value="'+v_Date_pay+'" type="hidden"/>';//
                            //!!				f.Date_pay.value = d2str2(new Date(v_Date_pay));
                            /*			document.getElementById("d_b_pay").innerHTML = '<button name="B_pays" type=button '+
                            				'onClick="javascript:document.forms.ulaForm.Menu_Item.value=\'recon\';'+
                            						'ch(\'srch\',\'menu=recon&tp='+f.tp.value+'&Bill_Dog='+Bill_Dog+'\',0,\'tab_Cust\');">'+
                            				'Сеть</button>';*/
                            //			f.opl_per.value = 1;
                            if (is_off) {
                                comm = val_nm(Bill_Dog, "Comment");
                                ab_numbs = (f.h_ab_numbs ? f.h_ab_numbs.value : 1) * 1 + 1 * 1;
                                m_ab = f.h_ab_numbs ? (t == 6 && comm != '' ? comm * 1 : Math.round(val_nm(Bill_Dog, "ab_sum") / 2 * (1 + 1 / ab_numbs))) : '';
                                m_dolg = date_difference(new Date(v_Date_pay), d_Date_start_st, "day") - 1;
                                f.s_dolg.value = Math.round(m_ab / 30 * m_dolg);
                                document.getElementById("s_dolg").innerHTML = f.s_dolg.value;
                                document.getElementById("m_dolg").innerHTML = 'за ' + m_dolg + ' дней';
                                //			document.getElementById("c_dolg").innerHTML = 'Подключить '+m_dolg+' дней';
                                f.total_pay.value = 1 * f.s_dolg.value + 1 * 100;
                            } else if (dolg) {}
                            adj_pay();
                            if (!v_inet) { // договор абонентской учётки
                                if (document.getElementById("res_frz")) {
                                    frz_chk();
                                }
                                if (f.tp.value == 1 || f.TabNum.value == 6 || f.TabNum.value == 8) {
                                    //		$d_test = strtotime ("2000-01-01");
                                    d_test = new Date("2000-01-01");
                                    D_e = d_Date_end_st < d_test ? "de" : "";
                                    D_p = d_Date_pay < d_test ? "dp" : "";
                                    document.getElementById("d_b_pay").innerHTML = '<button name="B_dhd" type=button' +
                                        ' onClick="toggle(\'dhd\')">▼</button><div id="dhd" style="display:none"><table><tr><td>' +
                                        Bill_Dog + ':</td><td></button><div id="otkat"><button name="B_otkat" type=button onClick="javascript:' +
                                        'ch_param(\'otkat\',\'Bill_Dog=' + Bill_Dog + '\',\'otkat\');">Откт</div></td><td><div id="toinet">' +
                                        '<button name="B_2inet" type=button onClick="javascript:f=document.forms.ulaForm;f.sBill_Dog.value=' +
                                        Bill_Dog + ';ch_param(\'toinet\',\'B=' + Bill_Dog + '\',\'toinet\');setTimeout(\'f.sBill_Dog.onchange()\',500)' +
                                        '">3w</button></div></td>' + ((n_st == 1 && (d_Date_end_st < d_test || d_Date_pay < d_test ||
                                                v_Date_end_st == "0000-00-00" || v_Date_pay == "0000-00-00")) && (D_e == "" || D_p == "") ? '<td><div id="d_cor" ' +
                                            'style="background-color:#F99"><button type=button onClick="javascript:ch_param(\'d_cor\',\'B=' +
                                            Bill_Dog + '&d=' + (D_e == "" ? "p" : "e") + '\',\'d_cor\');setTimeout(\'f.sBill_Dog.onchange()\', 500);">' +
                                            'Дата</button></div></td>' : '') +
                                        '</tr></table></div>';
                                }
                                if (f.selBill.value != Bill_Dog) {
                                    f_sel(f.sel.value);
                                }
                            } else { // договор помечен как интернет учётка
                                if (f.tp.value == 1 || f.TabNum.value == 6 || f.TabNum.value == 8) {
                                    document.getElementById("d_b_pay").innerHTML = '<button name="B_dhd" type=button' +
                                        ' onClick="document.getElementById(\'dhd\').style=\'display:\'">▼</button>' +
                                        '<div id="dhd" style="display:none"><table><tr><td>для ' + Bill_Dog + '</td><td>' +
                                        '<div id="toab"><button name="B_2ab" type=button onClick="javascript:f=document.forms.ulaForm;' +
                                        'f.sBill_Dog.value=' + Bill_Dog + ';ch_param(\'toab\',\'B=' + Bill_Dog + '\',\'toab\');' +
                                        'setTimeout(\'f.sBill_Dog.onchange()\', 500)">Абон ' + Bill_Dog + '</button></div></td>' +
                                        '</tr></table></div>';
                                }
                            }
                            document.getElementById("d_b_pay").innerHTML += '</tr></table>'
                        } else {
                            //			f.conn[val_nm(Bill_Dog, "con_typ")-1].selected = true;
                            f.conn[0].selected = true;
                            f.Bill_frend.value = val_nm(Bill_Dog, "Bill_frend");
                            /*			document.getElementById("state").innerHTML += 
                            				' с <input name="Date_start_st" value="'+v_Date_start_st+'" size="8" type="date"/>'+
                            				' по <input name="Date_end_st" value="'+v_Date_end_st+'" size="8" type="date" onchange="document.forms.ulaForm.Date_pay.value=this.value"/>';	*/
                            f.Date_start_st.value = v_Date_start_st;
                            f.Date_end_st.value = v_Date_end_st;
                            f.Date_pay.value = v_Date_pay;
                        }
                    }
                    if (document.getElementById("phn")) {
                        f.phone_Home.value = val_nm(Bill_Dog, "phone_Home");
                        f.phone_Cell.value = val_nm(Bill_Dog, "phone_Cell");
                        f.phone_Work.value = val_nm(Bill_Dog, "phone_Work");
                        f.Jur.checked = val_nm(Bill_Dog, "Jur") == 1;
                    }
                    if (document.getElementById("fio")) {
                        f.Fam.value = val_nm(Bill_Dog, "Fam");
                        f.Name.value = val_nm(Bill_Dog, "Name");
                        f.Father.value = val_nm(Bill_Dog, "Father");
                        f.Birthday.value = val_nm(Bill_Dog, "Birthday");
                        f.pasp_Ser.value = val_nm(Bill_Dog, "pasp_Ser");
                        f.pasp_Num.value = val_nm(Bill_Dog, "pasp_Num");
                        f.pasp_Date.value = val_nm(Bill_Dog, "pasp_Date");
                        f.pasp_Uvd.value = val_nm(Bill_Dog, "pasp_Uvd");
                        f.pasp_Adr.value = val_nm(Bill_Dog, "pasp_Adr");
                        f.Comment.value = val_nm(Bill_Dog, "Comment");
                    }
                    // disabled="disabled"
                    //write_temp("chk_adress");
                }
                //---------------------------------------------------------------------------------
                function faddLogin() {
                    var f = document.forms.ulaForm;
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    prm = ((Bill_Dog == 0) || (f.Bill_Dog.value != val_nm(Bill_Dog, "Bill_Dog"))) ? "new" : "add";
                    //			s_LonChange = 'ch_param(&quot;is_Login_Free&quot;,&quot;Login=&quot;+this.value+&quot;&prm='+prm+'&quot;,&quot;addLogin&quot;);';
                    document.getElementById("Login").innerHTML = 'логин ' +
                        '<input type="button" id="nic2login" value="ник->" ' +
                        'onclick= "f=document.forms.ulaForm;f.Login.value=f.Nic.value;' +
                        'ch_param(&quot;is_Login_Free&quot;,&quot;Login=&quot;+f.Nic.value+&quot;&prm=' + prm + '&quot;, &quot;daddLogin&quot;);" />' +
                        '<input name="Login" type="text" value="" size="12" ' +
                        'onChange="ch_param(&quot;is_Login_Free&quot;,&quot;Login=&quot;+this.value+&quot;&prm=' + prm + '&quot;, &quot;daddLogin&quot;);" />'
                    /*+
                    			'<input name="cancLogin" type="button" id="cancLogin" onclick="document.getElementById(\'addLogin\').innerHTML =\'\';chk_adress();" value="X" />'*/
                    ;
                    document.getElementById("daddLogin").innerHTML = "";
                    document.getElementById("id_tarif3w")[0].selected = true;
                    f.tarif3w_date.value = TODAY2;
                    //		document.getElementById("d_t3w_date").innerHTML ="установить с "+ 
                    //    		"<input name='tarif3w_date' id='tarif3w_date' value='"+TODAY2+"' type='date' onChange='adjastNet();' size='10' />";
                    document.getElementById("B_Sub").innerHTML = "";
                    f.Nic.value = val_nm(Bill_Dog, "Nic");
                    document.getElementById("Nic").readOnly = true;
                    document.getElementById("Bill_Dog").readOnly = true;
                    document.getElementById("conn").readOnly = true;
                    //		document.getElementById("id_tar_con").readOnly=true;
                    document.getElementById("id_tarifab").readOnly = true;
                    //		document.getElementById("tab_w3").bgcolor="#00FF00";
                }
                //---------------------------------------------------------------------------------
                function DoaddLogin() {
                    var f = document.forms.ulaForm;
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    var num_L = val_nm(Bill_Dog, "Logins");
                    ch_param("ins_Login", 'Bill_Dog=' + f.Bill_Dog.value + '&Nic=' + f.Nic.value + '&Login=' + f.Login.value +
                        '&id_tarif3w=' + f.id_tarif3w.value + '&tarif3w_date="' + f.tarif3w_date.value + '"', "B_Sub");
                    Nnum_L = Math.abs(num_L) + 1;
                    // - ->tab_Cust
                    document.getElementById("h_" + Bill_Dog + "_Logins").value = Nnum_L;
                    var inp_name = "h_" + Bill_Dog + "_Login" + Nnum_L;
                    //		document.getElementById("tab_Cust").innerHTML += inp_name+'<input name="'+inp_name+'" id="'+inp_name+'" value="'+f.Login.value+'" />';
                    //document.getElementById("h_"+Bill_Dog+"_Login"+Nnum_L).value = f.Login.value; type="hidden"
                    //		setTimeout('chk_adress();', 300);
                    f.sCod_flat.value = f.h_Cod_flat.value;
                    setTimeout("document.forms.ulaForm.sCod_flat.onchange();", 300);
                }
                //---------------------------------------------------------------------------------
                function NewBillAcc() {
                    var f = document.forms.ulaForm;
                    ch_param("Bill2acc_set", 'nb1=' + f.NewBill.value + '&nb2=' + f.Bill_Dog.value, "dNewBill");
                }
                //---------------------------------------------------------------------------------
                function adj_Cust() {
                    var f = document.forms.ulaForm;
                    //write_temp("adj_Cust");
                    //		document.getElementById("Submit_cor").disabled="disabled";
                    //		f.Nic.value = val_nm(Bill_Dog, "Nic");
                    //		document.getElementById("conn").readOnly=true;
                    //		document.getElementById("id_tarifab").readOnly=true;
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    if (document.getElementById("tabl_cust") &&
                        (f.Nic.value == val_nm(Bill_Dog, "Nic")) && (f.Menu_Item.value == "recon")) {
                        document.getElementById("Nic").readOnly = true;
                        document.getElementById("Bill_Dog").readOnly = true;
                        document.getElementById("B_Create").innerHTML = (f.Date_start_st.value == '' || f.Date_end_st.value == '' || f.Date_pay.value == '' || (f.conn.selectedIndex > 0 && f.id_tar_con.selectedIndex == 0)) ?
                            'Не установлена дата начала, окончания подключения к сети или дата по которое оплачено!' :
                            '<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();document.getElementById(\'B_Create\').innerHTML = \'\'" />';
                    }
                }
                //---------------------------------------------------------------------------------
                function add_cust(Bill_Dog_New) {
                    var f = document.forms.ulaForm;
                    phone_Home = f.phone_Home.value == '' ? '' : f.phone_Home.value;
                    clr_adress();
                    f.phone_Home.value = phone_Home;
                    document.getElementById("state").innerHTML = 'Подключить' + //+TODAY2
                        ' с <input name="Date_start_st" value="' + '" size="8" type="date"/>' +
                        ' по <input name="Date_end_st" value="' + '" size="8" type="date"/>';
                    f.Date_start_st.value = d2str(date_add(new Date(), "day", 1));
                    f.Date_end_st.value = d2str(date_add(new Date(f.Date_start_st.value), "month", 1));
                    f.Bill_Dog.value = "новый"; //Bill_Dog_New;
                    f.Bill_Dog.disabled = "disabled";
                    f.conn[2].selected = true;
                    adj_Conn(2);
                }
                //---------------------------------------------------------------------------------
                function get_month(frm_d) {
                    var s_date = new String(frm_d);
                    i_M = s_date.indexOf("-", 0);
                    s_Y = s_date.substring(0, i_M);
                    i_D = s_date.lastIndexOf("-");
                    s_M = s_date.substring(1 * i_M + 1, i_D);
                    s_D = s_date.substring(i_D + 1);
                    return s_M;
                }
                //---------------------------------------------------------------------------------
                function Date_Add(d_o, opl_m, opl_d) {
                    opl_m = opl_m == "" ? 0 : opl_m;
                    var s_date = new String(d_o);
                    s_date = (s_date == "" ? TODAY2 : s_date);
                    i_M = s_date.indexOf("-", 0);
                    s_Y = s_date.substring(0, i_M);
                    i_D = s_date.lastIndexOf("-");
                    s_M = s_date.substring(1 * i_M + 1, i_D);
                    s_D = s_date.substring(i_D + 1);
                    //		s_D = (1*s_D<10 ? "0" : "") + s_D;
                    n_Y = (1 * s_M + 1 * opl_m > 12) ? 1 * s_Y + 1 : s_Y;
                    dd_2 = (n_Y % 4) == 0 ? 29 : 28;
                    var dd = new Array(0, 31, dd_2, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                    n_M = (1 * s_M + 1 * opl_m > 12) ? 1 * s_M + 1 * opl_m - 12 : 1 * s_M + 1 * opl_m;
                    n_D = 1 * s_D + 1 * opl_d;
                    if (n_D > 28) {
                        if (dd[n_M] < n_D) {
                            n_D = n_D - dd[n_M];
                            n_M = n_M < 12 ? 1 * n_M + 1 : 1;
                        }
                    }
                    n_M = (1 * n_M < 10 ? "0" : "") + n_M;
                    n_D = (1 * n_D < 10 ? "0" : "") + n_D;
                    return n_Y + "-" + n_M + "-" + n_D;
                }
                //---------------------------------------------------------------------------------
                function f_Bill_Dog() {
                    var f = document.forms.ulaForm;
                    return (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                }
                //---------------------------------------------------------------------------------
                function new_fl(e, bd) {
                    document.getElementById("d" + bd).innerHTML =
                        '<button name="B_new_f" type=button onClick="javascript:document.getElementById(\'d' + bd + '\').innerHTML = \'√\'; ch_param(\'snewfl\',\'B=' + bd + '&f=' + e.value + '\',\'d' + bd + '\');">√</button>';
                }
                //---------------------------------------------------------------------------------
                function adj_Conn(conn) {
                    var f = document.forms.ulaForm;
                    //		var conn = f.conn.value;
                    var tp = f.tp.value;
                    f.conn_pay.value = "";
                    ch_param("ch_conn", "con_typ=" + conn + "&tp=" + tp, "con_tar");
                    document.getElementById("con_s").innerHTML = "";
                    //	setTimeout('adj_con_tar();', 1500);
                    if (conn == 5) { // Смена адреса
                        setTimeout('ch_param("frm_adress", "new=new", "new_adr");', 2000);
                    } else {
                        document.getElementById("new_adr").innerHTML = "";
                        setTimeout('adj_Cust();', 2000);
                    }
                    //		adj_CPay();//document.getElementById("tabl_cust").size
                    //		document.forms.ulaForm.con_tar.onchange();
                }
                //---------------------------------------------------------------------------------
                function adj_con_tar() {
                    //		document.getElementById('con_s').style.display = '';
                    var f = document.forms.ulaForm;
                    var Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    /*		if (!f.id_tar_con) {
                    			 var res;
                    			 for(var i=0; i<1000000; i++)
                    				  for(var j=i; j<1000000; j++)
                    					 res += i*i*i/(j*j);
                    		}	*/
                    //	i=0; while (!f.id_tar_con) { i += 1; }
                    var t = f.id_tar_con.options[f.id_tar_con.selectedIndex].value; // № тарифа подключения
                    var opl_p = document.getElementById("h_op_" + t).value; //
                    document.getElementById('abon_pay').style.display = '';
                    f.conn_pay.value = document.getElementById("h_cn_" + t).value;
                    f.abon_pay.value = document.getElementById("h_ab_" + t).value * opl_p;
                    if (f.conn[2].selected) {
                        f.h_ab_numbs.value = 1 * f.h_ab_numbs.value + 1;
                    }
                    m_opl = opl_p > 0 ? Math.round(f.abon_pay.value / 2 / opl_p * (1 + 1 / (f.h_ab_numbs ? (f.h_ab_numbs.value > 0 ? f.h_ab_numbs.value : 1) : 1))) : 0;
                    document.getElementById("con_s").innerHTML = t > 0 ?
                        'Абон.тариф <b>' + document.getElementById("h_nm_" + t).value + '</b> ' + m_opl + ' руб./мес.' : ''; //"h_id_"+t
                    f.abon_pay.value = m_opl * opl_p;
                    f.opl_mon.value = m_opl;

                    document.getElementById("opl_p").innerHTML = "+" + opl_p + "м.опл";
                    //		alert('!'+f.h_ab_numbs+', '+f.h_ab_numbs?(f.h_ab_numbs>0?f.h_ab_numbs.value:1):1);
                    //		write_temp(opl_p+" "+t);
                    //		f.id_tarifab.value = t;
                    //	if (f.conn.value==1) {	}
                    //		if (f.Date_start_st.value=='') { f.Date_start_st.value=TODAY2; }
                    if (f.Date_start_st.value == '') {
                        f.Date_start_st.value = d2str(date_add(new Date(), "day", 1));
                    }
                    // вычислить число миллисекунд в дне	 lastIndexOf	.substring(indexA, indexB)	length		//msPerDay = 24 * 60 * 60 * 1000;	date(,"YYYY-MM-DD")
                    //		f.Date_end_st.value = opl_p==''?f.Date_start_st.value:Date_Add(f.Date_start_st.value,opl_p,0); //n_Y+"-"+n_M+"-"+s_D;
                    f.Date_end_st.value = opl_p == '' ? f.Date_start_st.value : d2str(date_add(new Date(f.Date_start_st.value), "month", 1 * opl_p)); //n_Y+"-"+n_M+"-"+s_D;
                    v_D_start = f.Date_start_st.value;
                    v_D_end = f.Date_end_st.value;
                    if (document.getElementById("tabl_cust") && Bill_Dog == f.Bill_Dog.value) {
                        f.Date_pay.value = Date_Add(val_nm(Bill_Dog, "Date_pay"), opl_p, 0);
                        ar_s = new Array('"#333333">&nbsp;не устан.', '"#33CC66">&nbsp;подкл.', '"#0000FF">&nbsp;замороз.', '"#00FFFF">&nbsp;расторг');
                        n_st = val_nm(Bill_Dog, "state");
                        i_st = n_st == '' ? 0 : n_st;
                        document.getElementById("state").innerHTML = f.id_tarifab.value == 0 ? '' : '<b><font color=' + ar_s[i_st] + '</font>'; // style="border:solid"
                    } else {
                        f.Date_pay.value = f.Date_end_st.value;
                        document.getElementById("state").innerHTML = /*f.id_tarifab.value==0?'':*/ '<font style="border:solid" color="#000000">&nbsp;подключить&nbsp;</font>'; //
                    }
                    document.getElementById("state").innerHTML +=
                        ' с <input name="Date_start_st" value="' + v_D_start + '" size="8" type="date"/>' +
                        ' по <input name="Date_end_st" value="' + v_D_end + '" size="8" type="date" onchange="document.forms.ulaForm.Date_pay.value=this.value;adj_CPay_act()"/>';
                    if (t == 24) {
                        //	alert(i_st);
                        sst = '<select name="n_st" id="n_st" class="headText" onchange="cor_n_st(this.value)" >' +
                            '<option value="0" ' + (i_st == 0 ? 'selected' : '') + '>не подк</option>' +
                            '<option value="1" ' + (i_st == 1 ? 'selected' : '') + '>подкл.</option>' +
                            '<option value="2" ' + (i_st == 2 ? 'selected' : '') + '>замороз</option>' +
                            '<option value="3" ' + (i_st == 3 ? 'selected' : '') + '>расторг</option>' +
                            '</select>';
                        document.getElementById("state").innerHTML = sst +
                            ' с <input name="Date_start_st" value="' + v_D_start + '" size="8" type="date"/>' +
                            ' по <input name="Date_end_st" value="' + v_D_end + '" size="8" type="date" onchange="document.forms.ulaForm.Date_pay.value=this.value"/>';

                    }
                    adj_CPay();
                    prm = ((Bill_Dog == 0) || (f.Bill_Dog.value != val_nm(Bill_Dog, "Bill_Dog"))) ? "new" : "add";
                    if (t > 0) {
                        document.getElementById("B_Create").innerHTML = (prm == "new") ?
                            '<input name="DublNic" type="hidden" value="0" /><input type="button" name="Submit_ins" id="Submit_ins" value="Создать" onClick="ins_cust();" />' :
                            '<input name="DublNic" type="hidden" value="0" /><input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />';
                    }
                    //		write_temp("prm="+prm);
                }
                //---------------------------------------------------------------------------------
                function adj_CPay_act() {
                    var f = document.forms.ulaForm;
                    //		var Bill_Dog = (!document.getElementById("tabl_cust"))?0:f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    var t = f.id_tar_con.options[f.id_tar_con.selectedIndex].value; // № тарифа подключения
                    if (t != 25 && t != 26) {
                        return;
                    } // по акции без оплаты за подключение
                    v_D_start = f.Date_start_st.value;
                    v_D_end = f.Date_end_st.value;

                    d_D_start = new Date(f.Date_start_st.value);
                    d_D_end = new Date(f.Date_end_st.value);
                    if (d_D_start.getDate() != d_D_end.getDate()) { //		d_D_start.getDate()
                        if (date_getDaysInMonth(d_D_end) != d_D_end.getDate()) { //		d_D_start.getDate()
                            d_D_end.setDate((d_D_start.getDate()));
                            f.Date_end_st.value = d2str(d_D_end);
                            f.Date_pay.value = d2str(d_D_end);
                        }
                    }
                    var opl_p = date_difference(d_D_start, d_D_end, "month");
                    //			var opl_p = document.getElementById("h_op_"+t).value;//
                    document.getElementById('abon_pay').style.display = '';
                    ///		f.conn_pay.value = document.getElementById("h_cn_"+t).value;
                    f.abon_pay.value = document.getElementById("h_ab_" + t).value * opl_p;
                    ///		if (f.conn[2].selected) { f.h_ab_numbs.value = 1*f.h_ab_numbs.value + 1; }	//	доп.комп?
                    m_opl = opl_p > 0 ? Math.round(f.abon_pay.value / 2 / opl_p * (1 + 1 / (f.h_ab_numbs ? (f.h_ab_numbs.value > 0 ? f.h_ab_numbs.value : 1) : 1))) : 0;
                    document.getElementById("con_s").innerHTML = t > 0 ?
                        'Абон.тариф <b>' + document.getElementById("h_nm_" + t).value + '</b> ' + m_opl + ' руб./мес.' : ''; //"h_id_"+t
                    f.abon_pay.value = m_opl * opl_p;
                    f.opl_mon.value = m_opl;
                    f.total_Cpay.value = 1 * f.conn_pay.value + 1 * opl_p * f.opl_mon /*abon_pay*/ .value + 1 * f.inet_Cpay.value;
                }
                //---------------------------------------------------------------------------------
                function cor_n_st(n_st) {
                    var f = document.forms.ulaForm;
                    if (n_st == 3) {
                        f.Date_end_st.value = "";
                    }
                }
                //---------------------------------------------------------------------------------
                function adjustTarif3w() {
                    adjastNet();
                }
                //---------------------------------------------------------------------------------
                function adjastPasp() {
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
                    var con;
                    var f = document.forms.ulaForm;
                    f.Nic.value = "";
                    document.getElementById("B_Edit").innerHTML = "";
                    document.getElementById("B_Create").innerHTML = "";
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    if ((Bill_Dog == 0) || (nBill_Dog.value != val_nm(Bill_Dog, "Bill_Dog"))) {
                        document.getElementById("conn")[(Bill_Dog == 0) ? 1 : 2].selected = true;
                        ch_param("is_Bill_Free", "Bill_Dog=" + nBill_Dog.value, "B_Create");
                        f.tarifab_date.value = TODAY2;
                        f.tarif3w_date.value = TODAY2;
                        if (document.getElementById("Submit_cor")) {
                            document.getElementById("Submit_cor").disabled = "disabled";
                        }
                        document.getElementById("Login").innerHTML = 'логин <input name="nic2login" type="button" id="nic2login" ' +
                            'onclick="javascript:f=document.forms.ulaForm;f.Login.value=f.Nic.value;adjustLogin();"' +
                            ' value="тот же" /><input name="Login" type="text" value="" onChange="adjustLogin();" size="12" />';
                    } else {
                        document.getElementById("Submit_cor").disabled = "";
                    }
                }
                //---------------------------------------------------------------------------------
                function adj_mac() {
                    if (macPattern.test(document.forms.ulaForm.mac.value)) adjastNet();
                    else document.getElementById("B_Create").innerHTML = '';
                }
                //---------------------------------------------------------------------------------
                function cor_mac(bd) {
                    //	var mac = "mac"+document.getElementById("net")?"":bd;
                    //	var dmac= "d"+document.getElementById("net")?"":bd;
                    b = document.getElementById("net") ? "" : bd;
                    if (macPattern.test(m = document.getElementById("mac" + b).value)) {
                        if (confirm("Изменить МАС-адрес?"))
                            ch_param("do_cor_mac", "bd=" + bd + "&m=" + get_mac(bd) + "&TabNum=" + document.getElementById("TabNum").value, "d" + bd);
                    } else alert("Неверный формат МАС-адреса");
                }
                //---------------------------------------------------------------------------------
                function v_MAC(MAC_val, bd) {
                    errorString = "";
                    //		var f = document.forms.ulaForm.mac.value;
                    var bd = bd.length > 0 ? bd : "";
                    //	mac = "mac";//+document.getElementById("net")?"":bd;
                    b = document.getElementById("net") ? "" : bd;
                    //	mac += document.getElementById("net")?"":bd;
                    theName = "MACaddress";
                    if (MAC_val.length == 0) return;
                    MAC_val = MAC_val.toUpperCase(MAC_val);
                    var macPatt = /^[0-9A-F]{1}$/;
                    var smb = MAC_val.charAt(MAC_val.length - 1)
                    if (!smb.match(macPatt)) {
                        alert(smb + ' - неверный символ!');
                        MAC_val = MAC_val.substring(0, MAC_val.length - 1);
                    }
                    document.getElementById("mac" + b).value = MAC_val;
                    if (MAC_val.length == 2 || MAC_val.length == 5 || MAC_val.length == 8 || MAC_val.length == 11 || MAC_val.length == 14)
                        document.getElementById("mac" + b).value = MAC_val + "-";
                    var ipArray = MAC_val.match(macPattern);
                    //	alert(ipArray);
                    //	if(ipArray!=null) if (MAC_val.length==12) document.forms.ulaForm.mac.value = mac2MAC(MAC_val) 
                    //		else return;
                    if (macPattern.test(MAC_val))
                        if (MAC_val.length == 17 && ipArray.length != 7) errorString = /*ipArray[1]+.length+" "+imac+*/ "Ошибка в МАС-адресе!";
                    /*if (errorString != "")*/
                    document.getElementById("B_Create").innerHTML = errorString;
                    //	alert("mac"+bd+" "+document.getElementById("mac"+bd).value);
                    /*for (i = 0; i < 4; i++) {	thisSegment = ipArray[i];   }	*/
                    //	if (errorString == "") document.forms.ulaForm.mac.blur();
                    //		else			alert (errorString);
                }
                //---------------------------------------------------------------------------------
                function get_mac(bd) {
                    b = document.getElementById("net") ? "" : bd == 0 ? "" : bd;
                    //		var mac = "mac"+document.getElementById("net")?"":bd;
                    //		alert(document.forms.ulaForm[mac].value);
                    var f = document.getElementById("mac" + b).value; //document.forms.ulaForm.mac.value;
                    //		var f = document.forms.ulaForm["mac"+b].value;
                    if (f == "") return "";
                    f = f.toUpperCase(f);
                    var mac = "";
                    var ipArray = f.match(macPattern);
                    for (i = 1; i < 7; i++) {
                        mac = mac + ipArray[i];
                    }
                    return mac;
                }
                //---------------------------------------------------------------------------------
                function mac2MAC(mac) {
                    return mac == "" ? "" : mac.substr(0, 2) + "-" + mac.substr(2, 2) + "-" + mac.substr(4, 2) + "-" +
                        mac.substr(6, 2) + "-" + mac.substr(8, 2) + "-" + mac.substr(10, 2);
                }
                //---------------------------------------------------------------------------------
                function adj_Nic(nic) {
                    var f = document.forms.ulaForm;
                    //		if f.Bill_Dog.value != 
                    //		nic = f.Nic;
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    prm = ((Bill_Dog == 0) || (f.Bill_Dog.value != val_nm(Bill_Dog, "Bill_Dog"))) ? "new" : "add";
                    ch_param("is_Nic_Free", "Nic=" + nic.value + '&prm=' + prm, "B_Create");
                    if (prm == "new") {
                        //			s_LonChange = 'ch_param(&quot;is_Login_Free&quot;,&quot;Login=&quot;+this.value+&quot;&prm='+prm+'&quot;,&quot;addLogin&quot;);';	//		alert(s_LonChange);
                        s_LonChange = 'ch_param(\'is_Login_Free\',\'Login=\'+this.value+\'&prm=' + prm + '\',\'daddLogin\');'; //		alert(s_LonChange);
                        document.getElementById("Login").innerHTML = 'логин <input name="Login" type="text" value="' +
                            nic.value + '" size="12" onChange="' + s_LonChange + '" />'; //+
                        document.getElementById("daddLogin").innerHTML = '<input type="button" name="chk_Login" value="?" onClick="' + s_LonChange + '" />' +
                            '<input name="cancLogin" type="button" id="cancLogin" onclick="document.getElementById(&quot;daddLogin&quot;).innerHTML =&quot;&quot;;chk_adress();" value="X" />';
                        document.getElementById("id_tarif3w")[0].selected = true;
                        f.tarif3w_date.value = TODAY2;
                        document.getElementById("daddLogin").innerHTML = '';
                        //			setTimeout('document.forms.ulaForm.chk_Login.Click()', 300);
                        /*ch_param("is_Login_Free","Login='+nic.value+'&prm='+prm+'","addLogin");*/
                        if (document.getElementById("conn")[0].selected == true)
                            setTimeout('document.getElementById("conn")[1].selected = true;adj_Conn(1);', 2000);
                    }
                    document.getElementById("B_Sub").innerHTML = "";
                    //		f.Nic.value = val_nm(Bill_Dog, "Nic");
                    //		document.getElementById("Nic").readOnly=true;
                    document.getElementById("Bill_Dog").readOnly = true;
                    document.getElementById("conn").readOnly = true;
                    document.getElementById("id_tarifab").readOnly = true;
                    //		document.getElementById("tab_w3").bgcolor="#00FF00";

                    //javascript:if (f.Login.value==&quot;&quot;) {f.Login.value= this.val//			(!document.getElementById("tabl_cust"))?0:f.tabl_cust.options[f.tabl_cust.selectedIndex].value
                    /*		setTimeout('f = document.forms.ulaForm;if (document.getElementById("tabl_cust")&&'+
                    				'(f.DublNic.value == "0")&&(f.Menu_Item.value == "recon")) {'+
                    					'document.getElementById("B_Edit").innerHTML=(f.Date_start_st.value==\'\' || f.Date_end_st.value==\'\' ||'+
                    					' f.Date_pay.value==\'\')?\'Не забудьте установить дату начала, и дату окончания подключения и '+
                    					'дату по какое оплачено!\':'+
                    					'\'<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />\';'+
                    			'}', 300);
                    */
                    /*			if (document.getElementById("tabl_cust") && (document.forms.ulaForm.DublNic.value == "0") &&
                    				(document.forms.ulaForm.Menu_Item.value == "recon")) {
                    				document.getElementById("B_Edit").innerHTML = (f.Date_start_st.value=='' || f.Date_end_st.value=='' || f.Date_pay.value=='')?
                    					'Не забудьте установить дату начала, и дату окончания подключения и дату по какое оплачено!':
                    					'<input type="button" name="Submit_cor" id="Submit_cor" value="Изменить" onClick="cor_cust();" />';
                    			}	*/
                }
                //---------------------------------------------------------------------------------
                function adjustLogin() {
                    var h_N;
                    var f = document.forms.ulaForm;
                    h_B = "h_" + f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    id_w3t = document.getElementById(h_B + "_id_tarif3w" + Math.abs(f.Login.selectedIndex + 1)).value; //value
                    document.getElementById("id_tarif3w")[Math.abs(id_w3t - 1)].selected = true;
                    f.tarif3w_date.value = document.getElementById(h_B + "_tarif3w_date" + Math.abs(f.Login.selectedIndex + 1)).value;
                    // 		adjastNet();
                }
                //---------------------------------------------------------------------------------
                function adj_CPay() {
                    var f = document.forms.ulaForm;
                    var t = f.id_tar_con.options[f.id_tar_con.selectedIndex].value; // № тарифа подключения
                    var opl_p = document.getElementById("h_op_" + t).value; //
                    f.total_Cpay.value = 1 * f.conn_pay.value + 1 * opl_p * f.opl_mon /*abon_pay*/ .value + 1 * f.inet_Cpay.value;
                    //		write_temp("opl_p="+opl_p+" f.conn_pay.value="+f.conn_pay.value+" f.opl_mon.value="+f.opl_mon.value
                    //				   +" f.inet_Cpay.value="+f.inet_Cpay.value);
                }
                //---------------------------------------------------------------------------------
                function d2strFull(d1) {
                    return new String(d1.getDate() + " " + monthname[d1.getMonth()] + " " + d1.getFullYear() + "г.");
                }
                //---------------------------------------------------------------------------------
                function d2strS(d1) {
                    return new String(d1.getDate() + " " + monthname[d1.getMonth()]);
                }
                //---------------------------------------------------------------------------------
                function d2strS2(d1) {
                    return new String(d1.getDate() + " " + mname[d1.getMonth()]);
                }
                //---------------------------------------------------------------------------------
                function d2str2(d1) {
                    return new String(d1.getDate() + " " + mname[d1.getMonth()] + " " + d1.getFullYear() + "г.");
                }
                //---------------------------------------------------------------------------------
                function d2str(d1) {
                    return new String(d1.getFullYear() + "-" + (d1.getMonth() < 10 ? "0" : "") + (1 * d1.getMonth() + 1) + "-" + (d1.getDate() < 10 ? "0" : "") + d1.getDate());
                }
                //---------------------------------------------------------------------------------
                function adj_pay(t_) {
                    var f = document.forms.ulaForm;
                    var f_all = document.getElementById('all').style.display != 'none';
                    if (f.h_new_Cod.value == 1) {
                        alert('не присвоен код адреса');
                        return;
                    }
                    //		f.opl_mon.value = val_nm(f_Bill_Dog(), "ab_sum");
                    var abs_ = f.h_ab_numbs.value;
                    if (f_all) {
                        //			var m = new Array(abs_);
                        //			var d = new Array(abs_);
                        var abs_ = f.h_tot_ab.value;
                        var Bill_Dog = new Array(abs_);
                        comm = val_nm(f.elements["ND_1"].value, "Comment");
                        //	ab_numbs = (f.h_ab_numbs?f.h_ab_numbs.value:1)*1 + 1*1;
                        t = val_nm(f.elements["ND_1"].value, "id_tarifab"); // № тарифа
                        opl_mon = t == 6 && comm != '' ? comm * 1 : Math.round(val_nm(f.elements["ND_1"].value, "ab_sum") / 2 * (1 + 1 / abs_));
                        //	alert("ab_sum="+Math.round(val_nm(f.elements["ND_1"].value, "ab_sum"));
                        var m = (f.ab_.value - (f.ab_.value % (opl_mon * abs_))) / (opl_mon * abs_);
                        var d = Math.round((f.ab_.value % (opl_mon * abs_)) * 30 / (opl_mon * abs_));
                        var ab_ = new Array(abs_);
                        var s_act = new Array(abs_);
                        var d1 = new Array(abs_);
                        var d2 = new Array(abs_);
                        var d3 = new Array(abs_);
                        var d1_ = new Array(abs_);
                        var d2_ = new Array(abs_);
                        var da_ = new Array(abs_);
                        var de_ = new Array(abs_);
                        var dp = new Array(abs_);
                        var action = new Array(abs_);
                        var v_st = new Array(abs_);
                        var dlg = new Array(abs_);
                        var nDate = new Array(abs_);
                        var nDateAct = new Array(abs_);
                        var s_act_ = "";
                        //			Bill_Dog = f_Bill_Dog();
                        f.t_pay.value = 1 * f.ab_.value + 1 * f.s_tot.value; /*1*f.i_pay.value +*/
                        document.getElementById("t_pay").innerHTML = "Всего к оплате: " + f.t_pay.value + "руб.";
                        document.getElementById("days_").innerHTML = "мес." + (d > 0 ? d + "дн." : "");
                        f.Comm_all.value = m + "мес." + (d > 0 ? d + "дн." : "");
                        f.opl_.value = m;
                        //	var d2_ = new Array(abs_);
                        for (var i = 1; i <= abs_; i++) {
                            Bill_Dog[i] = f.elements["ND_" + i].value; //f_Bill_Dog();
                            v_st[i] = eval("f.h_" + Bill_Dog[i] + "_state.value"); //val_nm(Bill_Dog[i], "state");
                            //		alert('h_'+Bill_Dog[i]+'_Date_pay.value');
                            //		alert('f.elements[);
                            //	if (v_st[i] == '') { alert('Номер договора '+Bill_Dog[i]+'. Это ИНТЕРНЕТ учётка! Выполните переоформление-подключение к сети');	return; }
                            dlg[i] = val_nm(Bill_Dog[i], "dolg") && v_st[i] == 2 && val_nm(Bill_Dog[i], "Date_end_st") == '';
                            //			if (val_nm(Bill_Dog[i], "dolg")) {
                            //					alert('Абонент отключен за долг!  Выполните переподключение абонента!');	return;	
                            //			}
                            ab_[i] = f.ab_.value / abs_;
                            eval("f.ab_" + [i] + ".value=" + ab_[i]);
                            d1_[i] = new Date(); //	d1_ = сегодня"Y-m-d"
                            d1[i] = date_add(d1_[i], "month", 6); //	d1 = сегодня + 6 мес
                            //		alert(eval("f.h_"+Bill_Dog[i]+"_Date_pay.value"););
                            dp[i] = new Date(val_nm(Bill_Dog[i], "Date_pay"));
                            d2_[i] = dlg[i] ? new Date(eval("f.c_dolg" + [i] + ".value")) : dp[i]; // Date(f.Date_pay.value);	//	d2_ = Date_pay оплачен по...
                            //		d2 = date_add(date_add(d2_, "month", m), "day", d);			//	d2 = Date_pay + Opl_period
                            da_[i] = date_compare(d1_[i], d2_[i], "date") > 0 ? d1_[i] : d2_[i]; // da для отсчёта акции
                            de_[i] = date_add(da_[i], "month", 6); //	de_ = дата оплаты для получения акции
                            action[i] = 0;
                            d2[i] = date_add(date_add(d2_[i], "month", m), "day", d); //	d2 = Date_pay + Opl_period 
                            if (d == 0 && date_getDaysInMonth(d2_[i]) == d2_[i].getDate()) {
                                d2[i].setDate(date_getDaysInMonth(d2[i]));
                            }
                            action[i] = date_compare(d1[i], d2[i], "date") <= 0 ? parseInt(date_difference(da_[i], d2[i], "month") / 6) : 0;
                            s_act[i] = action[i] == 0 ? 'Для получения акции оплатите ' +
                                date_difference(d2_[i], de_[i], "month") + 'мес. (до ' + d2str2(de_[i]) + ')<br>' : '';
                            /*
                            				if (v_st[i] == 1) {// подключён ли?
                            					d2[i] = date_add(date_add(d2_[i], "month", m), "day", d);//	d2 = Date_pay + Opl_period
                            // * если d=0 и оплачен последний день месяца, то опаченным сделать последний день месяца * /
                            					if (d == 0 && date_getDaysInMonth(d2_[i])==d2_[i].getDate()) {
                            						d2[i].setDate(date_getDaysInMonth(d2[i]));						
                            					}
                            					action[i] =date_compare(d1[i], d2[i], "date") <= 0?parseInt(date_difference(da_[i], d2[i], "month") / 6):0;
                            //					alert(date_compare(d1[i], d2[i], "date")+" "+action[i]+" "+d2str(d1[i])+" "+d2str(d2[i]));
                            					s_act[i] = action[i]==0?'Для получения акции оплатите '+
                            								date_difference(d2_[i], de_[i], "month")+'мес. (до '+d2str2(de_[i])+')<br>':'';
                            				} else if (v_st[i] == 2) {
                            // *	d2[i] = new Date(val_nm(Bill_Dog[i], "Date_start_st"));	// Date(f.Date_start_st.value);
                            					s_act[i] = 'Необходимо оплатить долг за '+date_difference(d2[i], d2_[i], "month")+'мес.';	* /
                            					d2[i] = date_add(date_add(d2_[i], "month", m), "day", d);//	d2 = Date_pay + Opl_period
                            					if (d == 0 && date_getDaysInMonth(d2_[i])==d2_[i].getDate()) {
                            						d2[i].setDate(date_getDaysInMonth(d2[i]));						
                            					}
                            					action[i] = date_compare(d1[i], d2[i], "date") <= 0?parseInt(date_difference(da_[i], d2[i], "month") / 6):0;
                            					s_act[i] = action[i]==0?'Для получения акции оплатите '+
                            								date_difference(d2_[i], de_[i], "month")+'мес. (до '+d2str2(de_[i])+')<br>':'';
                            				} else {
                            					return;// состояние ни 1 и ни 2
                            				}		*/
                            s_act_ += s_act[i];
                            //		alert(d2str2(d1)+" ? "+d2str2(d2)+" = "+action);
                            nDate[i] = d2str(d2[i]); ///Date_Add(f.Date_pay.value, m, d);//Date_end_st
                            d3[i] = date_add(d2[i], "month", action[i]); //	d3 = Date_pay + Opl_period + action
                            //		alert(date_getDaysInMonth(d2[i])+" "+d2[i].getDate());
                            if (d == 0 && date_getDaysInMonth(d2[i]) == d2[i].getDate()) {
                                d3[i].setDate(date_getDaysInMonth(d3[i]));
                            }
                            nDateAct[i] = d2str(d3[i]); ///Date_Add(nDate, action, 0);
                            //		n_Date = new Date(f.Date_end_st.value);
                            document.getElementById("opl_" + i).innerHTML = /*"Оплата по "+*/ (m > 0 || d > 0 ? d2str2(d2[i]) + '(' + (m > 0 ? m + 'мес.' : '') + (d > 0 ? d + 'дн.' : '') + ')' : '') +
                                '<input name="nDate_end' + i + '" type="hidden" value="' + nDate[i] + '"/>';
                            document.getElementById("act_" + i).innerHTML = (action[i] > 0 ? '+ ' + action[i] + 'мес.=' + d2str2(d3[i]) : '-') +
                                '<input name="act_' + i + '" type="hidden" value="' + action[i] + '"/>' +
                                '<input name="nDateAct' + i + '" type="hidden" value="' + nDateAct[i] + '"/>'; //d2str2(d3)
                            //		}
                        }
                        if (1 * f.t_pay.value != 0) {
                            document.getElementById("res_pay_all").innerHTML = //s_act_ +
                                '<input type="button" name="Submit_ins" id="Submit_ins" value="Внести" onClick="ins_all()" />';
                        } //ins_pay();
                        //		document.getElementById(""+i).
                    } else { // =#= =#= =#= =#= =#= =#= =#= =#= =#= =#= 	ОПЛАТА ОДНОГО ДОГОВОРА		 =#= =#= =#= =#= =#= =#= =#= =#= =#= =#=
                        Bill_Dog = f_Bill_Dog();
                        var v_st = val_nm(Bill_Dog, "state");
                        if (v_st == '') {
                            alert('Это ИНТЕРНЕТ учётка! Выполните переоформление-подключение к сети');
                            return;
                        }
                        //			if (val_nm(Bill_Dog, "dolg")) { alert('Абонент отключен за долг!  Выполните переподключение абонента!');	return;	}
                        //			opl_mon = val_nm(Bill_Dog, "ab_sum")
                        //val_nm(Bill_Dog, "state")==2 && f.Date_end_st.value==''
                        dolg = val_nm(Bill_Dog, "dolg");
                        auto = val_nm(Bill_Dog, "auto");
                        t = val_nm(Bill_Dog, "id_tarifab"); // № тарифа
                        if (dolg) {
                            comm = val_nm(Bill_Dog, "Comment");
                            ab_numbs = (f.h_ab_numbs ? f.h_ab_numbs.value : 1) * 1 + 1 * 1;
                            opl_mon = f.h_ab_numbs ? (t == 6 && comm != '' && Math.round(comm * 1) > 0 ? comm * 1 : Math.round(val_nm(Bill_Dog, "ab_sum") / 2 * (1 + 1 / ab_numbs))) : '';
                        } else {
                            opl_mon = f.opl_mon.value;
                        }
                        //		alert(f.total_pay.value);//?0:'f.s_dolg.value'
                        if (t_ == "per") {
                            f.abon_p.value = f.opl_per.value * opl_mon;
                            f.total_pay.value = 1 * f.inet_pay.value + 1 * f.abon_p.value + 1 * (dolg ? (auto ? 0 : 100) + 1 * f.s_dolg.value : 0);
                        } else if (t_ == "p") {
                            f.opl_per.value = f.abon_p.value == '' ? '' : f.abon_p.value / opl_mon;
                            f.total_pay.value = 1 * f.inet_pay.value + 1 * f.abon_p.value + 1 * (dolg ? (auto ? 0 : 100) + 1 * f.s_dolg.value : 0);
                        } else if (t_ == "tot") {
                            f.opl_per.value = f.abon_p.value == '' ? '' : f.abon_p.value / opl_mon;
                            f.abon_p.value = 1 * f.total_pay.value - (1 * f.inet_pay.value + 1 * (dolg ? (auto ? 0 : 100) + 1 * f.s_dolg.value : 0));
                        } else {
                            f.abon_p.value = f.opl_per.value * opl_mon;
                            f.total_pay.value = 1 * f.inet_pay.value + 1 * f.abon_p.value + 1 * (dolg ? (auto ? 0 : 100) + 1 * f.s_dolg.value : 0);
                        }
                        if (dolg) {
                            document.getElementById("inet_pay").style.display = f.abon_p.value > 0 ? "" : "none";
                            document.getElementById("inet_rub").innerHTML = f.abon_p.value > 0 ? " руб." : "<b>Оплатите хотя бы месяц !";
                            document.getElementById("inet_Com").style.display = f.abon_p.value > 0 ? "" : "none";
                        }
                        var m = (f.abon_p.value - (f.abon_p.value % opl_mon)) / opl_mon;
                        var d = Math.round(30 / opl_mon * (f.abon_p.value % opl_mon)); //
                        document.getElementById("days").innerHTML = "мес." + (d > 0 ? d + "дн." : "");
                        f.abon_Com.value = m + "мес." + (d > 0 ? d + "дн." : "");
                        f.opl_per.value = m;
                        var d2d = new Date(); //	d2d = сегодня"Y-m-d"
                        var d1_ = new Date(f.c_dolg.value); //	d1_ = сегодня"Y-m-d" + 1
                        d1 = date_add(d1_, "month", 6); //	d1 = сегодня + 6 мес
                        var d2_ = dolg ? d1_ : (new Date(f.Date_pay.value)); //date_add(, "day", 1) date_add(d1_, "day", 1)	d2_ = Date_pay оплачено по ...
                        da_ = date_compare(d2d, d2_, "date") > 0 ? d2d : d2_;
                        de_ = date_add(da_, "month", 6); //	de_ = дата оплаты для получения акции
                        if ((d == 0) && (date_getDaysInMonth(da_) == da_.getDate())) {
                            de_.setDate(date_getDaysInMonth(de_));
                        }
                        action = 0;
                        d2 = date_add(date_add(d2_, "month", m), "day", d); //	d2 = new_start + Opl_period
                        //		alert(d2_+"+"+m+"м + "+d+"="+date_add(date_add(d2_,"month",m),"day",2)+"="+date_add(date_add(d2_,"month", m),"day",3));
                        /* если d=0 и оплачен последний день месяца, то опаченным сделать последний день месяца */
                        if ((d == 0) && (date_getDaysInMonth(d2_) == d2_.getDate())) {
                            d2.setDate(date_getDaysInMonth(d2));
                        }
                        if (!dolg) { //(v_st == 1) {// подключён ли?
                            //				d2 = date_add(date_add(d2_, "month", m), "day", d);	//	d2 = Date_pay + Opl_period
                            //alert(d2str2(d1)+' '+d2str2(d2));
                            action = date_compare(d1, d2, "date") <= 0 ? parseInt(date_difference(da_, d2, "month") / 6) : 0;
                            s_act = action == 0 ? 'Для получения акции оплатите ' + date_difference(d2_, de_, "month") +
                                'мес. (до ' + d2str2(de_) + ')<br>' : '';
                            //			alert(d2str2(da_)+" ? "+d2str2(d2)+" = "+parseInt(date_difference(da_, d2, "month") / 6));//Math.round
                        } else if (v_st == 2) {
                            //				d2 = date_add(date_add(d2_, "month", m), "day", d);	//	 new Date(f.Date_start_st.value);
                            //				md = date_difference(d2, d2_, "month");
                            //				s_act = md>0?'Необходимо оплатить долг за '+md+'мес.':'';

                            action = date_compare(d1, d2, "date") <= 0 ? parseInt(date_difference(da_, d2, "month") / 6) : 0;
                            s_act = action == 0 ? 'Для получения акции, сверх долга, оплатите ' + date_difference(d2_, de_, "month") +
                                'мес. (до ' + d2str2(de_) + ')<br>' : '';
                            /*			if(){// отключен за долг
                            			} else {
                            			}	*/
                        } else {
                            f.total_pay.value = '';
                            f.opl_per.value = '';
                            f.abon_p.value = '';
                            alert('Необходимо выполнить переподключение или переоформление.');
                            return; // состояние ни 1 и ни 2
                        }
                        //		alert(d2str2(d1)+" ? "+d2str2(d2)+" = "+action);
                        nDate = d2str(d2); ///Date_Add(f.Date_pay.value, m, d);//Date_end_st
                        d3 = date_add(d2, "month", action); //	d3 = Date_pay + Opl_period + action
                        if (d == 0 && date_getDaysInMonth(d2) == d2.getDate()) {
                            d3.setDate(date_getDaysInMonth(d3));
                        }
                        nDateAct = d2str(d3); ///Date_Add(nDate, action, 0);
                        nDateOff = date_add(d3, "day", 1); // Новая дата для заявки на откл. за долг
                        //		n_Date = new Date(f.Date_end_st.value);
                        document.getElementById("opl_to").innerHTML = "Оплата по " + d2str2(d2) + '<input name="new_Date_end" type="hidden" value="' + nDate + '"/>'; //new_Date_end
                        document.getElementById("action").innerHTML = (action > 0 ? '+ ' + action + 'мес.=' + d2str2(d3) : 'акция н/д.') +
                            '<input name="action" type="hidden" value="' + action + '"/><input name="nDateAct" type="hidden" value="' + nDateAct + '"/>'; //d2str2(d3)
                        //		}
                        if (f.Login && 1 * f.total_pay.value != 0) {
                            document.getElementById("res_pay").innerHTML = s_act +
                                (dolg ? 'Заявки на подкл. на ' + d2str2(d2_) + ' и откл. за долг на ' + d2str2(nDateOff) : '') +
                                '<input type="button" name="Submit_ins" id="Submit_ins" value="Внести" onClick="' + 'ins_pay()' + ';" />';
                        } else if (!f.Login) {
                            alert("Отсутствует логин интернета");
                        }
                    }
                }
                //---------------------------------------------------------------------------------
                function ins_all() {
                    var f, con;
                    f = document.forms.ulaForm;
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;

                    if (f.Login) {
                        vLogin = f.Login.value;
                    } else if (f.Login.options[f.Login.selectedIndex]) {
                        vLogin = f.Login.options[f.Login.selectedIndex].text;
                    } else {
                        alert("Ошибка, обратитесь к разработчику!");
                    }
                    Nic = val_nm(Bill_Dog, "Nic");
                    vtoday = new Date();
                    id_p = f.h_id_Podjezd.value;
                    fl = f.h_fl.value;

                    s_param = "id_p=" + id_p + "&fl=" + fl + "&TabNum=" + f.TabNum.value + "&nums=" + f.h_tot_ab.value + "&abon_Com=" + f.Comm_all.value; //h_ab_numbs
                    adr = f.h_st.value + " д." + f.h_nb.value + (f.h_kr.value == '' ? "" : " корп." + f.h_kr.value) + " кв." + f.h_fl.value;
                    p_param = "&adr=" + adr;
                    for (var i = 1; i <= f.h_tot_ab.value; i++) { //h_ab_numbs
                        bd = f["ND_" + i].value;
                        auto = (val_nm(bd, "auto") == 1 && val_nm(bd, "state") > 0 != 0) ? 1 : 0; // && val_nm(bd,"mac")
                        dolg = val_nm(bd, "state") == 2 && val_nm(bd, "Date_end_st") == ''; //val_nm(bd,"dolg");
                        dp = val_nm(bd, "Date_pay"); //time2Y_m_d()
                        D_st = d2str(date_add(dp == '' ? vtoday : (new Date(dp)), "day", 1)); //Date_end_st
                        //			alert(d2str(date_add(d2_[i], "day", 1)));
                        s_param += "&bd" + i + "=" + bd + "&Nic" + i + "=" + val_nm(bd, "Nic") + "&ab" + i + "=" + f["ab_" + i].value + "&ds" + i + "=" + D_st +
                            "&de" + i + "=" + f["nDate_end" + i].value + "&act" + i + "=" + f["act_" + i].value + "&nda" + i + "=" + f["nDateAct" + i].value +
                            "&auto" + i + "=" + auto + "&s_p" + i + "=" + (f.Date_end_st.value == f.Date_pay.value ? "1" : "0") +
                            (dolg ? '&dolg' + i + '=' + dolg + '&s_dolg' + i + '=' + f["sd_" + i].value +
                                '&d_off' + i + '=' + f["D_st_" + i].value + '&c_dolg' + i + '=' + f["c_dolg" + i].value : ''); //"&"+"&"+
                        FIO = val_nm(bd, "Fam") + " " + val_nm(bd, "Name") + " " + val_nm(bd, "Father");

                        dp = f["act_" + i].value > 0 ? f["nDateAct" + i].value : f["nDate_end" + i].value;
                        p_param += '&fio' + i + '=' + FIO + '&dp' + i + '=' + dp;
                    }
                    //+"&Bill_Dog="+Bill_Dog+"&Nic="+Nic+"&Login="+vLogin+
                    //	"&abon="+f.abon_p.value+"&inet="+f.inet_pay.value+"&inet_Com="+f.inet_Com.value+
                    //	"&Date_start="+D_st+"&Date_end="+f.new_Date_end.value+"&action="+f.action.value+"&nDateAct="+f.nDateAct.value;	 //+"&="++" &="+id_p="+id_p+"&fl="+fl+"&	/*+"&="+f..value*/
                    //		write_temp(s_param);
                    ch_param('do_pall', s_param, 'res_pay_all');

                    //		FIO = val_nm(Bill_Dog, "Fam") + " " + val_nm(Bill_Dog, "Name") + " " + val_nm(Bill_Dog, "Father");

                    //		abon_p = f.abon_p.value;
                    //		param = "&Bill_Dog="+Bill_Dog+"&Date_pay="+Date_pay+"&abon_p="+abon_p+"&action="+f.action.value;
                    document.getElementById("hist_pay").innerHTML = '';
                    w_check = window.open("p_pay_all.php?" + s_param + p_param + "&t_pay=" + f.t_pay.value, "w_ch", "width=750,height=400,status=yes"); //
                    w_check.window.print();
                }
                //---------------------------------------------------------------------------------
                function ins_pay() {
                    var f, con;
                    f = document.forms.ulaForm;
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    dolg = val_nm(Bill_Dog, "dolg");
                    auto = (val_nm(Bill_Dog, "auto") == 1 && val_nm(Bill_Dog, "state") > 0 && val_nm(Bill_Dog, "mac") != 0) ? 1 : 0;
                    if (f.Login) {
                        vLogin = f.Login.value;
                    } else if (f.Login.options[f.Login.selectedIndex]) {
                        vLogin = f.Login.options[f.Login.selectedIndex].value /*text*/ ;
                    } else {
                        alert("Ошибка, обратитесь к разработчику!");
                    }
                    Nic = val_nm(Bill_Dog, "Nic");
                    vtoday = new Date();
                    //		D_st = d2str(date_add(dp==''?time2Y_m_d(vtoday):dp, "day", 1));	//Date_end_st	//time2Y_m_d()
                    D_st = d2str(date_add(f.Date_pay.value == '' ? vtoday : (new Date(f.Date_pay.value)), "day", 1)); //Date_end_st
                    id_p = f.h_id_Podjezd.value;
                    fl = f.h_fl.value;

                    //		alert(val_nm(Bill_Dog, "account"+(1*f.Login.selectedIndex+1)));
                    s_param = "id_p=" + id_p + "&fl=" + fl + "&TabNum=" + f.TabNum.value + "&Bill_Dog=" + Bill_Dog + "&Nic=" + Nic + "&Login=" + vLogin +
                        "&account=" + val_nm(Bill_Dog, "account" + (1 * f.Login.selectedIndex + 1)) +
                        "&abon=" + f.abon_p.value + "&inet=" + f.inet_pay.value + "&abon_Com=" + f.abon_Com.value + "&inet_Com=" + f.inet_Com.value +
                        "&Date_start=" + D_st + "&Date_end=" + f.new_Date_end.value + "&action=" + f.action.value + "&nDateAct=" + f.nDateAct.value +
                        "&auto=" + auto + "&s_p=" + (f.Date_end_st.value == f.Date_pay.value ? "1" : "0") +
                        (dolg ? '&dolg=dolg&s_dolg=' + f.s_dolg.value + '&d_off=' + f.Date_start_st.value + '&c_dolg=' + f.c_dolg.value : '');
                    //+"&="++" &="+id_p="+id_p+"&fl="+fl+"&	/*+"&="+f..value*/
                    //		alert(s_param);
                    //		write_temp(s_param);
                    //		setTimeout('document.getElementById("B_Create").innerHTML ="";', 100);
                    ch_param('do_pay', s_param, 'res_pay');
                    FIO = val_nm(Bill_Dog, "Fam") + " " + val_nm(Bill_Dog, "Name") + " " + val_nm(Bill_Dog, "Father");
                    adr = f.h_st.value + " д." + f.h_nb.value + (f.h_kr.value == '' ? "" : " корп." + f.h_kr.value) + " кв." + f.h_fl.value;
                    //		 = 0; //f..value;
                    abon_p = f.abon_p.value;
                    inet_pay = f.inet_pay.value;
                    total_pay = f.total_pay.value;
                    conn_pay = f.conn_pay.value;
                    Date_pay = f.action.value > 0 ? f.nDateAct.value : f.new_Date_end.value;
                    param = "TabNum=" + f.TabNum.value + "&fio=" + FIO + "&adr=" + adr + "&Bill_Dog=" + Bill_Dog + "&Date_pay=" + Date_pay +
                        "&conn_pay=" + conn_pay + "&abon_p=" + abon_p + "&inet_pay=" + inet_pay + "&Login=" + vLogin + "&total_pay=" + total_pay +
                        "&action=" + f.action.value;
                    document.getElementById("hist_pay").innerHTML = '';
                    w_check = window.open("print_pay.php?" + param, "w_ch", "width=750,height=350,status=yes"); //
                    w_check.window.print();
                    ////		print_pay();
                }
                //---------------------------------------------------------------------------------
                function print_pay() { /// Отключена
                    dv = 'Main'; // tab_Cust B_Create Mform
                    var f = document.forms.ulaForm;
                    Bill_Dog = f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    FIO = val_nm(Bill_Dog, "Fam") + " " + val_nm(Bill_Dog, "Name") + " " + val_nm(Bill_Dog, "Father");
                    adr = "ул." + f.h_st.value + " д." + f.h_nb.value + (f.h_kr.value == '' ? "" : " корп." + f.h_kr.valu) + " кв." + f.h_fl.value;
                    /*		param1 = "st="+f.id_street.options[f.id_street.selectedIndex].value+
                    			"&Num_build="+f.num_build.options[f.num_build.selectedIndex].value+
                    			"&fl="+f.flat.options[f.flat.selectedIndex].value+
                    			"&Bill_Dog="+Bill_Dog+
                    			"&name_street="+f.id_street.options[f.id_street.selectedIndex].text+
                    			"&Login="+f.Login.options[f.Login.selectedIndex].text+
                    			"&tarif3w="+f.id_tarif3w.options[f.id_tarif3w.selectedIndex].text+
                    			"&Tday="+TODAY3;	*/
                    //	    document.getElementById(dv).innerHTML = "<table width=100%><tr><td align='center'><img src='load.gif'/></td></tr></table>";
                    param = "fio='" + FIO + "'&adr='" + adr + "'" + "&Bill_Dog=" + Bill_Dog;
                    //		document.write('<div id="d_chk" style="background-color:#FFFFFF; position:absolute; left: 0px; top: 0px; width: 800; height: 600;"></div>');
                    ch_param('print_pay', param, 'd_chk');
                    //w_check = window.open("print_pay.php?"+ param, "w_ch", "width=750,height=300,status=yes");//
                    window.print();
                }
                //---------------------------------------------------------------------------------
                function canc_otp(d_canc, Bill_Dog, Date_start, Date_end) { /// 
                    var f = document.forms.ulaForm;
                    //	alert("1 "+d_canc+" "+Bill_Dog+" "+Date_start+" "+Date_end+" "+f.TabNum.value);
                    ch("do_canc_otp", "d_canc=" + f.d_canc.value + "&Bill_Dog=" + Bill_Dog + "&Date_start=" + Date_start + "&Date_end=" + Date_end + "&TN_canc=" + f.TabNum.value, 0, 'f' + Date_start);
                    //		setTimeout('alert("Выполнено изменение отпуска"); //document.forms.ulaForm.B_chk_adress.click();', 100);
                }
                //---------------------------------------------------------------------------------
                function del_otp(Bill_Dog, Date_start, Date_end) { /// 
                    var f = document.forms.ulaForm;
                    //	alert("1 "+d_canc+" "+Bill_Dog+" "+Date_start+" "+Date_end+" "+f.TabNum.value);
                    ch("do_del_otp", "Bill_Dog=" + Bill_Dog + "&Date_start=" + Date_start + "&Date_end=" + Date_end + "&TN_canc=" + f.TabNum.value, 0, 'f' + Date_start);
                    //	setTimeout('alert("Отпуск удалён"); document.forms.ulaForm.B_chk_adress.click();', 100);
                }
                //---------------------------------------------------------------------------------
                function srch() { //prm, prm_val
                    var f = document.forms.ulaForm;
                    if (f.sNic.value == '' && f.sCod_flat.value == '' && f.sBill_Dog.value == '') return;
                    prm = f.sNic.value != '' ? "Nic" : (f.sCod_flat.value != '' ? "Cod_flat" : "Bill_Dog");
                    prm_v = document.getElementById("s" + prm).value;
                    //	alert(prm);
                    /*		if (prm == "Bill_Dog") {
                    			f.sCod_flat.value = '';
                    			f.sNic.value = '';
                    		} else if (prm == "Cod_flat") {
                    			f.sBill_Dog.value = '';
                    			f.sNic.value = '';
                    		} else if (prm == "Nic") {
                    			f.sBill_Dog.value = '';
                    			f.sCod_flat.value = '';
                    		}	*/
                    //		ch_param('srch', 'menu='+f.Menu_Item.value+'&tp='+f.tp.value+'&'+prm+'='+prm_v, 'tab_Cust');
                    //			 alert('menu='+f.Menu_Item.value+'&tn='+f.TabNum.value+'&tp='+f.tp.value+'&'+prm+'='+prm_v);
                    ch('srch', 'menu=' + f.Menu_Item.value + '&tn=' + f.TabNum.value + '&tp=' + f.tp.value + '&' + prm + '=' + prm_v, 0, 'tab_Cust'); //_param
                    if (f.id_town) {
                        document.getElementById("B_adress").innerHTML = '';
                        document.getElementById("B_Create").innerHTML = '';
                        document.getElementById("B_Edit").innerHTML = '';
                        //			f.id_town.options[0].selected=true;
                        f.id_street.options[0].selected = true;
                        document.getElementById("num_build").innerHTML = '';
                        document.getElementById("flat").innerHTML = '';
                        /*		f.num_build.options[0].selected=true;
                        		f.flat.options[0].selected=true;	*/
                    } else if (f.id_townnew) {
                        f.id_townnew.options[0].selected = true;
                        f.id_streetnew.options[0].selected = true;
                        f.num_buildnew.options[0].selected = true;
                        f.flatnew.options[0].selected = true;
                    }

                    document.getElementById("B_Sub").innerHTML = '';
                    document.getElementById("dCod_flat").innerHTML = '';
                    document.getElementById("dBill_Dog").innerHTML = '';
                    document.getElementById("dNic").innerHTML = '';
                    document.getElementById("d" + prm).innerHTML =
                        '<button name="B_chk_adress" type=button onClick="srch()"><img src="reload.png" align=middle alt="Обнови"></button>'; //\''+prm+'\'
                }
                //---------------------------------------------------------------------------------
                function get_adress() {
                    var f = document.forms.ulaForm;
                    return f.id_street.options[0].selected ? ("г.Талнах ул." + f.h_st.value + " д." + f.h_nb.value +
                        (f.h_kr.value == '' ? "" : " корп." + f.h_kr.value) + " кв." + f.h_fl.value) : ("г." +
                        f.id_town.options[f.id_town.selectedIndex].text +
                        " ул." + f.id_street.options[f.id_street.selectedIndex].text +
                        " д." + f.num_build.options[f.num_build.selectedIndex].text +
                        " кв." + f.flat.options[f.flat.selectedIndex].text);
                }
                //---------------------------------------------------------------------------------
                function is_chng(param) {
                    Bill_Dog = f_Bill_Dog();
                    var n_val = document.forms.ulaForm[param].value;
                    var o_val = Bill_Dog > 0 ? document.forms.ulaForm["h_" + Bill_Dog + "_" + param].value : ""; //val_nm(Bill_Dog, param);
                    //		alert(h_Bill_Dog+param);
                    //		if (param=="tarifab_date")
                    //		alert(Bill_Dog+"["+param+"]="+ o_val+(o_val!=n_val?" !":"")+"="+n_val);
                    //		if (!val_nm(Bill_Dog, param)) alert(param+"="+o_val+"!"+n_val);
                    //		if (isset(val_nm(Bill_Dog, param))) { 
                    //			if (val_nm(Bill_Dog, param)!=n_val) { alert(param+ "="+n_val); }
                    return (o_val != n_val) ? "&" + param + "=" + n_val : "";
                    //		}
                    //		else alert(param+"!");
                }
                //---------------------------------------------------------------------------------
                function cor_str() {
                    var f, con, vLogin;
                    f = document.forms.ulaForm;
                    f.Bill_Dog.value = f_Bill_Dog(); //val_nm(Bill_Dog, "Bill_Dog");
                    if (f.Menu_Item.value == "recon") {
                        if (f.Date_start_st.value == '' || f.Date_end_st.value == '' || f.Date_pay.value == '') {
                            document.getElementById("B_Create").innerHTML = '';
                            alert("Не установлены даты подключения и оплаты!");
                            return 0;
                        }
                        if (!f.Login) {
                            alert("Не установлен Login интернет!");
                            return 0
                        }
                        /*			if (f.tarif3w_date.value=='') {//alert("Не установлена дата подключения к интернету");
                        //			} else if (f.tarifab_date.value=='') {alert("Не установлена дата подключения тарифа сети");
                        			} //else if ((f.conn.selectedIndex==0) || (f.mont.selectedIndex==0)) {alert("При смене подключения, не выбран монтажник!");} 
                        			else {	*/
                        /*			if (f.h_Rows.value > 0) {
                        				Bill_Dog = f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                        				f.Nic.value = val_nm(Bill_Dog, "Nic");
                        			}	*/
                        //		f.Nic.value = f.h_Nic.value;
                        //		if(isset(f.h_Nic.value) && (f.h_Nic.value!="")) con = 2;
                        id_p = f.h_id_Podjezd.value;
                        //		fl = f.flat.options[f.flat.selectedIndex].text;
                        fl = f.h_fl.value;
                        /*				s_3w = "";
                        				if (f.tarif3w_date.value!='') {
                        					s_3w = "&id_tarif3w="+Math.abs(f.id_tarif3w.selectedIndex+1)+
                        						"&tarif3w_date="+f.tarif3w_date.value;
                        				}		*/
                        nLogin = 1;
                        if (f.Login) {
                            vLogin = f.Login.value;
                        } else if (f.Login.options[f.Login.selectedIndex]) {
                            nLogin = f.Login.selectedIndex + 1;
                            vLogin = f.Login.options[nLogin - 1].text;
                        } else {
                            alert("Ошибка, обратитесь к разработчику!");
                        }
                        var t = f.id_tar_con.options[f.id_tar_con.selectedIndex].value; // № тарифа подключения

                        var t_par = is_chng("phone_Home") + is_chng("phone_Cell") + is_chng("phone_Work") +
                            is_chng("Fam") + is_chng("Name") + is_chng("Father") +
                            is_chng("Birthday") + is_chng("pasp_Ser") + is_chng("pasp_Num") +
                            is_chng("pasp_Date") + is_chng("pasp_Uvd") +
                            is_chng("pasp_Adr") +
                            is_chng("Comment") +
                            is_chng("From_Net") +
                            is_chng("Bill_frend") +
                            is_chng("tarifab_date")
                        /*		  +is_chng("conn_pay")+is_chng("abon_p")+is_chng("inet_Cpay")+is_chng("total_Cpay")	
                        		  +is_chng("Date_start_st")+is_chng("Date_end_st")+is_chng("Date_pay")
                        */
                        ;
                        //		alert("h_"+Bill_Dog+"_tarif3w_date"+nLogin);
                        o_Jur = Bill_Dog == 0 ? "" : f["h_" + Bill_Dog + "_Jur"].value;
                        n_Jur = (f.Jur.checked == true) ? 1 : 0;
                        o_mac = Bill_Dog == 0 ? "" : f["h_" + Bill_Dog + "_mac"].value;
                        n_mac = get_mac(Bill_Dog);
                        //		  n_st = f.n_st.options[f.n_st.selectedIndex].value;
                        s_param = "id_p=" + id_p + "&fl=" + fl + "&floor=" + f.floor.value +
                            "&Nic=" + f.Nic.value +
                            //Math.abs(f.conn.selectedIndex)+//document.getElementById("conn").selectedIndex+ 
                            //		f.id_tarifab.selectedIndex+
                            (f.conn.selectedIndex > 0 ?
                                "&conn=" + t +
                                (t > 0 ? "&id_tarifab=" + document.getElementById("h_id_" + t).value : "") + // № абон тарифа
                                (t == 24 ? "&n_st=" + f.n_st.options[f.n_st.selectedIndex].value : "") :
                                "") +
                            (o_mac == n_mac ? "" : "&mac=" + n_mac) +
                            (o_Jur == n_Jur ? "" : "&Jur=" + n_Jur) +
                            "&TabNum=" + f.TabNum.value +
                            "&Cod_flat=" + f.h_Cod_flat.value +
                            "&Bill_Dog=" + f.Bill_Dog.value +
                            "&conn_pay=" + f.conn_pay.value +
                            "&abon_p=" + f.abon_pay.value +
                            (f.inet_Cpay.value * 1 > 0 ? "&inet_Cpay=" + f.inet_Cpay.value : "") +
                            "&total_Cpay=" + f.total_Cpay.value +
                            "&Login=" + vLogin + //f.Login.options[f.Login.selectedIndex].text+
                            //	Bill_Dog==0 ?"": f["h_"+Bill_Dog+"_id_tarif3w"+nLogin].value == f.id_tarif3w.value?"":"&id_tarif3w="+Math.abs(f.id_tarif3w.selectedIndex+1))+
                            //is_chng("tarif3w_date")+
                            //	Bill_Dog==0 ?"": (f["h_"+Bill_Dog+"_tarif3w_date"+nLogin].value == f.tarif3w_date.value?"":"&tarif3w_date="+f.tarif3w_date.value)+
                            "&Date_start_st=" + f.Date_start_st.value +
                            "&Date_end_st=" + f.Date_end_st.value +
                            "&Date_pay=" + f.Date_pay.value +
                            "&tarif3w_date=" + f.tarif3w_date.value +
                            "&id_tarif3w=" + Math.abs(f.id_tarif3w.selectedIndex + 1) +
                            t_par;
                        /*				"&Fam="+f.Fam.value+
                        				"&Name="+f.Name.value+
                        				"&Father="+f.Father.value+
                        				"&Birthday="+f.Birthday.value+
                        				"&pasp_Ser="+f.pasp_Ser.value+
                        				"&pasp_Num="+f.pasp_Num.value+
                        				"&pasp_Date="+f.pasp_Date.value+
                        				"&pasp_Uvd="+f.pasp_Uvd.value+
                        				"&pasp_Adr="+f.pasp_Adr.value+
                        				"&Comment="+f.Comment.value+
                        				"&phone_Home="+f.phone_Home.value+
                        				"&phone_Cell="+f.phone_Cell.value+
                        				"&phone_Work="+f.phone_Work.value+
                        				"&tarifab_date="+f.tarifab_date.value+ //f.n_date.value+
                        				"&Bill_frend="+f.Bill_frend.value+
                        				"&From_Net="+f.From_Net.value
                        				;	*/
                        /*alert(s_param);		;*/ //				alert ("f.h_new_Cod.value ="+f.h_new_Cod.value+" f.h_Cod_flat.value ="+f.h_Cod_flat.value);
                        if (f.h_new_Cod.value == 1) {
                            //				s_param = s_param + "&Cod_flat="+f.h_Cod_flat.value;
                        }
                        //				"&connect="+f.connect.value+
                        /*		}	*/
                    }
                    //write_temp("cor_str="+s_param);

                    return s_param;
                }
                //---------------------------------------------------------------------------------
                function cor_cust() {
                    var f, con;
                    f = document.forms.ulaForm;
                    if (f.Menu_Item.value == "recon") {
                        //	корректировка абонента
                        s_cor_str = cor_str();
                        if (s_cor_str == 0) return;
                        ch_param('do_cor_cust', s_cor_str, 'B_Create'); //B_Sub
                        //			ajax.open(smetod, "do_cor_cust"+cor_str(), true);
                    }
                    if (f.Menu_Item.value == "noti") {
                        // оформление заявки на ремонт.php?
                        Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                        c_str = "id_p=" + f.h_id_Podjezd.value + "&fl=" + f.h_fl.value + "&Notify=" + f.noti.value + "&Bill_Dog=" + Bill_Dog + "&Date_Plan=" + f.Date_Plan.value +
                            "&phone_Dop=" + f.phone_Dop.value + "&TabNum=" + f.TabNum.value + "&Cod_flat=" + f.h_Cod_flat.value;
                        //+"&Date_in="+f.n_date.value
                        //		alert(c_str);//+"&Date_Fact="+f.Date_Fact.value+"&mont="+f.mont.options[f.mont.selectedIndex].value
                        ch_param('do_noti', c_str, 'B_Sub'); //
                        //			ajax.open(smetod, c_str, true);
                    }
                    //		document.getElementById("B_Edit").innerHTML = //'';
                    //					'<input type="button" name="Submit_ins" id="Submit_ins" value="Печать" onClick="window.print();" />';
                }
                //---------------------------------------------------------------------------------
                function ins_cust() {
                    var f = document.forms.ulaForm;
                    //		write_temp(st_);
                    if (f.Menu_Item.value == "recon") {
                        s_cor_str = cor_str();
                        //write_temp("cor_str="+s_cor_str);
                        if (s_cor_str == 0) return;
                        ch_param('do_ins_cust', s_cor_str, 'B_Sub');
                        FIO = f.Fam.value + " " + f.Name.value + " " + f.Father.value;
                        adr = get_adress();
                        param = s_cor_str + "&fio=" + FIO + "&adr=" + adr;
                        //	alert(param);	write_temp(param);
                        w_chk = window.open("print_pay.php?" + param, "", "width=750,height=350,status=yes"); //
                        w_chk.window.print();
                        document.getElementById("B_Create").innerHTML = '';
                    }
                }
                //---------------------------------------------------------------------------------
                function str2date(s_date) {
                    i_M = s_date.indexOf("-", 0);
                    s_Y = s_date.substring(0, i_M);
                    i_D = s_date.lastIndexOf("-");
                    s_M = s_date.substring(1 * i_M + 1, i_D) - 1;
                    s_D = s_date.substring(i_D + 1);
                    return new Date(s_Y, s_M, s_D);
                }
                //---------------------------------------------------------------------------------
                function time2Y_m_d(s_time) {
                    m = s_time.getMonth() + 1;
                    return s_time.getYear() + 1900 + "-" + (m < 10 ? "0" : "") + m + "-" + s_time.getDate();
                }
                //---------------------------------------------------------------------------------
                function otkat_chk() {
                    var f = document.forms.ulaForm;
                    var BD = (!document.getElementById('tabl_cust')) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    ch_param('otkat', 'Bill_Dog=' + BD, 'otkat'); //
                }
                //---------------------------------------------------------------------------------
                function otkat() {
                    var f = document.forms.ulaForm;
                    var BD = (!document.getElementById('tabl_cust')) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    ch_param('otkat', 'Bill_Dog=' + BD, 'otkat');
                }
                //---------------------------------------------------------------------------------
                function frz_chk() {
                    var f = document.forms.ulaForm;
                    var BD = (!document.getElementById('tabl_cust')) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    var d1_ = new Date(f.Date_start_fr.value);
                    var d2 = val_nm(BD, 'Date_pay');
                    var d2_ = new Date(d2);
                    var de_ = new Date(f.Date_end_fr.value);
                    if (f.Date_start_fr.value == "") {
                        document.getElementById('res_frz').innerHTML = 'Введите дату начала заморозки, она не должно быть позже ' + d2;
                        return;
                    } else {
                        if (date_compare(d1_, d2_, "date") > 0) {
                            document.getElementById('res_frz').innerHTML = '<h1>Попытка заморозить вне оплаченного периода! </br>Начало заморозки не может быть позже ' + d2str2(d2_) + '</h1>'; //
                            return;
                        } //else {document.getElementById('res_frz').innerHTML = date_compare(d1_,d2_);}
                    }
                    if (f.Date_end_fr.value == "") {
                        document.getElementById('res_frz').innerHTML = 'Введите дату окончания заморозки';
                        return;
                    }
                    if (f.Comment_fr.value == "") {
                        document.getElementById('res_frz').innerHTML = 'отпуск';
                    }
                    n_d = d2str2(date_add(d2_, "day", date_difference(d1_, de_, "day") + 1));
                    document.getElementById('res_frz').innerHTML = 'Перенос оплаченной даты на ' + n_d + '. <input name="B_freaze" type="button" onclick="frz_cust()" value="заморозь" />';
                }
                //---------------------------------------------------------------------------------
                function frz_cust() {
                    var f = document.forms.ulaForm;
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;

                    /*		d1_ = new Date(f.Date_start_fr.value);
                    		d2_ = new Date(val_nm(Bill_Dog, "Date_pay"));	//Date(f.Date_pay.value);	//d2_=Date_pay оплачен по...
                    		if (date_compare(d1_,d2_, "date")>0) {
                    			alert("Попытка заморозки вне оплаченного периода! Начало заморозки должно быть до " + time2Y_m_d(d2_)); 
                    			return;
                    		}	*/
                    //		v_Date_pay = str2date(dp);
                    v_Date_end_st = str2date(f.Date_end_st.value);
                    v_Date_start_fr = str2date(f.Date_start_fr.value);
                    v_Date_end_fr = str2date(f.Date_end_fr.value);
                    n_Date_end_st = new Date();
                    n_Date_end_st.setTime(v_Date_end_st.getTime() + (v_Date_end_fr.getTime() - v_Date_start_fr.getTime()) + (24 * 3600 * 1000));
                    new_Date_end_st = time2Y_m_d(n_Date_end_st);

                    //		alert("Старая дата "+f.Date_end_st.value+", новая дата "+n_Date_end_st+" = "+);
                    s_param = "TabNum=" + f.TabNum.value + "&Bill_Dog=" + Bill_Dog + "&Date_start_fr=" + f.Date_start_fr.value +
                        "&Date_end_fr=" + f.Date_end_fr.value + "&new_Date_end=" + new_Date_end_st + "&Comment=" + f.Comment_fr.value;
                    //		write_temp(s_param);
                    ch_param('do_freaze', s_param, 'res_frz'); //B_Sub
                }
                //---------------------------------------------------------------------------------
                function chk_noti() {
                    var f = document.forms.ulaForm;
                    //		var check = !((f.noti.value == '') or (f.mont.selectedIndex==0) or (f.Date_Plan.Value==''));
                    Bill_Dog = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    if ((f.noti.value != '') && (f.Date_Plan.value != '') && (Bill_Dog != 0)) { // || (f.mont.selectedIndex == 0)
                        document.getElementById("B_Sub").innerHTML =
                            '<input type="button" name="Submit_cor" id="Submit_cor" value="Внести" onClick="cor_cust();" />';
                    } else {
                        document.getElementById("B_Sub").innerHTML = '';
                    } //
                }
                //---------------------------------------------------------------------------------
                function set_adress() {
                    var f = document.forms.ulaForm;
                    document.getElementById("adress").innerHTML = '<a class="subHeader"><font size="3">' +
                        "г." + f.id_town.options[f.id_town.selectedIndex].text +
                        ",  ул." + f.id_street.options[f.id_street.selectedIndex].text +
                        ",  д." + f.num_build.options[f.num_build.selectedIndex].text +
                        ",  пд." + f.h_Podjezd.value +
                        ",  эт." + f.h_floor.value +
                        ",  кв." + f.flat.options[f.flat.selectedIndex].text +
                        '</ font></a>';
                }
                //---------------------------------------------------------------------------------
                function btn_addPod() {
                    document.getElementById("B_Create").innerHTML = "<button name='B_add_pd' type=button onClick='f=document.forms.ulaForm;ch_param(&quot;do_ins_pd&quot;,&quot;k=&quot;+f.h_k.value, &quot;B_Edit&quot;)'><img src='ico_create.gif' align=middle title='Добавить подъезд'></button>"; //<a href=&quot;&quot;>111</a>
                    //	alert(document.getElementById("B_Sub").innerHTML);
                }
                //---------------------------------------------------------------------------------
                function set_selrgn(sel) {
                    if (sel == 'on') {
                        sel_bld = 1;
                        document.getElementById("d_sel_bld").innerHTML = "выбранных";
                        //	document.forms.ulaForm.bld2.options[1].selected=true;
                        //	document.getElementById("bld").options[1].selected = true;
                        //	alert("2-"+document.forms.ulaForm.bld.options[1].selected);
                        //		alert("1-"+document.getElementById("bld").value);
                    }
                }
                //---------------------------------------------------------------------------------
                function set_d_end(Bill_Dog, Date_start, d_end) {
                    //	alert(Bill_Dog+" "+ Date_start+" "+d_end);
                    document.getElementById("d_end").innerHTML = "оплачено по<input name='d_end' value='" + d_end + "' size='8'/>" +
                        "<input type='button' onclick='if (confirm(\"Установить дату оплаты по " + d_end + "?\"))" +
                        "{ch_param(\"do_cor_d_end\", \"Bill_Dog=" + Bill_Dog + "&Date_start=" + Date_start + "&Date_end=" + d_end +
                        "&TabNum=" + document.forms.ulaForm.TabNum.value + "\", \"d_end\");}' value='&radic;' />";
                }
                //---------------------------------------------------------------------------------
                function f_tar() {
                    var f = document.forms.ulaForm;
                    return "id_tarifab=" + f.id_tarifab.value + "&name_ab=" + f.name_ab.value + "&name_abon=" + f.name_abon.value +
                        "&ab_sum=" + f.ab_sum.value + "&k_tar=" + f.k_tar.value + "&perstypes=" + (1 * f.perstypes.value);
                }
                //---------------------------------------------------------------------------------
                function sh_tar(cmd, id_tarifab, name_ab, name_abon, ab_sum, k_tar, perstypes) {
                    if (cmd == "del") {
                        if (confirm("Удалить тариф " + name_ab + " (" + name_abon + ")?")) {
                            alert("в разработке")
                        }
                    } else {
                        var f = document.forms.ulaForm;
                        f.id_tarifab.value = id_tarifab;
                        f.name_ab.value = name_ab;
                        f.name_abon.value = name_abon;
                        f.ab_sum.value = ab_sum;
                        f.k_tar.value = k_tar;
                        f.perstypes.selectedIndex = perstypes; //options[].selected = true;//alert write_temp	setTimeout(&quot;&quot;,100)	document.getElementById("perstypes")[perstypes].selected = true; +\"&quot;'+cmd+'&quot;&\"++
                        document.getElementById("B_").innerHTML = '<input type="button" onclick="ch_param(&quot;cor_ab&quot;,&quot;cmd=' + cmd + '&&quot;+f_tar(),&quot;B_&quot;);f_sh_tar()" value="' +
                            (cmd == "cor" ? 'Измени' : 'Добавить') + ' тариф"/>';
                    }
                }
                //---------------------------------------------------------------------------------
                function f_sh_tar() {
                    setTimeout("op_f('edt_tar', 'Mform');", 3000);
                }
                //---------------------------------------------------------------------------------
                function f_con() {
                    var f = document.forms.ulaForm;
                    //write_temp(f.con_typ.value);
                    return "id_tar_con=" + f.id_tar_con.value + "&name_cn=" + f.name_cn.value + "&name_con=" + f.name_con.value +
                        "&con_sum=" + f.con_sum.value + "&opl_period=" + f.opl_period.value + "&con_typ=" + (1 * f.con_typ.value) +
                        "&id_tarifab=" + (1 * f.id_tarifab.value) + "&perstypes=" + (1 * f.perstypes.value);
                }
                //---------------------------------------------------------------------------------
                function sh_con(cmd, id_tar_con, name_cn, name_con, con_sum, opl_period, con_typ, id_tarifab, perstypes) {
                    if (cmd == "del") {
                        if (confirm("Удалить тариф " + name_cn + " (" + name_con + ")?")) {
                            alert("в разработке")
                        }
                    } else {
                        var f = document.forms.ulaForm;
                        f.id_tar_con.value = id_tar_con;
                        f.name_cn.value = name_cn;
                        f.name_con.value = name_con;
                        f.con_sum.value = con_sum;
                        f.opl_period.value = opl_period;
                        f.con_typ.selectedIndex = con_typ;
                        //options[].selected = true;//alert write_temp	setTimeout(&quot;&quot;,100)	document.getElementById("perstypes")[perstypes].selected = true; +\"&quot;'+cmd+'&quot;&\"++
                        f.id_tarifab.selectedIndex = id_tarifab;
                        f.perstypes.selectedIndex = perstypes;
                        document.getElementById("B_").innerHTML = '<input type="button" onclick="ch_param(&quot;cor_con&quot;,&quot;cmd=' + cmd + '&&quot;+f_con(),&quot;B_&quot;);f_sh_con()" value="' +
                            (cmd == "cor" ? 'Измени' : 'Добавить') + ' тариф"/>';
                    }
                }
                //---------------------------------------------------------------------------------
                function f_sh_con() {
                    setTimeout("op_f('edt_con', 'Mform');", 3000);
                }
                //---------------------------------------------------------------------------------
                function set_1(dID, ID, Ds, De) {
                    var f = document.forms.ulaForm;
                    var BD = (!document.getElementById("tabl_cust")) ? 0 : f.tabl_cust.options[f.tabl_cust.selectedIndex].value;
                    document.getElementById(dID).innerHTML = '';
                    ch_param('set_1', 'BD=' + BD + '&ID=' + ID + '&Ds=' + Ds + '&De=' + De, dID);
                }
                //---------------------------------------------------------------------------------
                function cor_pers(t) {
                    var f = document.forms.ulaForm;
                    document.getElementById("" + t).innerHTML = '';
                    <?php /*	'<input name="t'+t+'" type="text" value="<? echo $row["Podjezd"]; ?>
                    " size="
                    3 " />

                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "t<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["TabNum"];		?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "f<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["Fam"];			?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "n<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["Name"]; 		?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "s<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["SecName"];		?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "p<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["NamePers"];	?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "c<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["phone_Cell"];	?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "h<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["phone_Home"];	?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "l<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["login"];		?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "r<?php echo $row["
                    TabNum "]; ?>" > <?php echo $row["id_Region"];	?> < /div></td >
                        <
                        td bgcolor = "<?php echo $bgc; ?>" > < div id = "d<?php echo $row["
                    TabNum "]; ?>" >
                        *
                        /	?>
                }
                //---------------------------------------------------------------------------------
                function clr_adress() {
                    var f, vNic;
                    f = document.forms.ulaForm;
                    //        document.getElementById("B_adress").disabled="disabled"; //.innerHTML = ' ';
                    //		document.getElementById("B_get_adress").disabled="disabled";
                    //	document.getElementById("tab_Cust").innerHTML = '';
                    document.getElementById("B_Sub").innerHTML = '';
                    if (document.getElementById("d_Bill_Dog")) {
                        document.getElementById("d_Bill_Dog").innerHTML = '';
                    } //<input name="h_id_Podjezd" type="hidden" />
                    if (f.Menu_Item.value == "pay") {
                        if (document.getElementById("opl_to")) {
                            document.getElementById("opl_to").innerHTML = '';
                            document.getElementById("action").innerHTML = '';
                            //			document.getElementById("hist_pay").innerHTML = '';
                            document.getElementById("days").innerHTML = "мес.";
                            document.getElementById("res_pay").innerHTML = '';
                            document.getElementById("Date_pay").innerHTML = '';
                            f.abon_p.value = "";
                            f.opl_per.value = "";
                            f.abon_Com.value = "";
                            f.inet_pay.value = "";
                            f.inet_Com.value = "";
                            f.total_pay.value = "";
                            //			f.B_freaze.readOnly=true;
                        }
                    }
                    if (f.Menu_Item.value == "edt_bld") {}
                    if (f.Menu_Item.value == "cust") {} else {
                        //				f.Nic.value = f.h_Nic.value;
                        //		if (f.Menu_Item.value == "con3w") {
                        if (document.getElementById("d_mont")) {
                            document.getElementById("d_mont").innerHTML = '';
                        }
                        if (document.getElementById("floor")) {
                            f.floor.value = "";
                        }
                        if (document.getElementById("net")) {
                            document.getElementById('abon_pay').style.display = 'none';
                            document.getElementById("dNewBill").innerHTML = "";
                            f.NewBill.value = "";
                            f.mac.value = "";
                            f.Nic.value = "";
                            f.id_tar_con.options[0].selected = true;
                            if (f.id_tarifab) {
                                f.id_tarifab.value = "0"
                            }; //f.id_tarifab[0].selected = true; //f.h_id_tarifab.value;
                            f.tarifab_date.value = ""; //f.h_tarifab_date.value;
                            f.Bill_Dog.value = "";
                            if (f.Date_start_s && f.Menu_Item.value != "pay") {
                                f.Date_start_st.value = "";
                                f.Date_end_st.value = "";
                                f.Date_pay.value = "";
                            }
                            document.getElementById("state").innerHTML = "";
                        }
                        if (document.getElementById("w3")) {
                            f.From_Net.value = "";
                            f.id_tarif3w[0].selected = true; //f.h_id_tarif3w.value;
                            f.tarif3w_date.value = ""; //f.h_tarif3w_date.value;
                            document.getElementById("Login").innerHTML = '';
                            document.getElementById("daddLogin").innerHTML = '';
                        }
                        if (document.getElementById("fio")) {
                            f.Fam.value = "";
                            f.Name.value = "";
                            f.Father.value = "";
                            f.pasp_Ser.value = "";
                            f.pasp_Num.value = "";
                            f.pasp_Date.value = "";
                            f.pasp_Uvd.value = "";
                            f.pasp_Adr.value = "";
                            f.Birthday.value = "";
                        }
                        if (document.getElementById("phn")) {
                            f.phone_Home.value = "";
                            f.phone_Cell.value = "";
                            f.phone_Work.value = "";
                            f.Jur.checked = 0;
                        }
                    }
                }
                //---------------------------------------------------------------------------------
                function s_Bill_Dog() {
                    setTimeout("srch('Bill_Dog');", 1500);
                }
                //---------------------------------------------------------------------------------
                ajax = startAJAX();

            </script>
            <!-- **************************************************************************************************** -->
            </head>

            <body bgcolor="#F4FFE4" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
                <div id="Main" style="Z-INDEX:100">
                    <table width=800 border="0" cellspacing="0" cellpadding="0">
                        <tr bgcolor="#99CC66">
                            <!--993300; POSITION:absolute;top:0;left:0-->
                            <td id="dateformat" height="20" align="center" colspan="3">
                                <input name="tp" type="hidden" id="tp" value="<? echo $TypePers; ?>" />
                                <input name="TabNum" id="TabNum" type="hidden" value="<? echo $TabNum ?>" />
                                <div id="clock"></div>
                            </td>
                            <SCRIPT LANGUAGE="JavaScript">
                                <!--
                                updateClock();

                                function updateClock() {
                                    var time = new Date();
                                    var hours = time.getHours();
                                    var minutes = time.getMinutes();
                                    var seconds = time.getSeconds();
                                    //document.forms.ulaForm.face.value 
                                    document.getElementById("clock").innerHTML = '<b>&nbsp;&nbsp;' + TODAY + '&nbsp;&nbsp;</b> ' +
                                        ((hours < 10) ? '0' + hours : hours) +
                                        ':' + ((minutes < 10) ? '0' + minutes : minutes) +
                                        ':' + ((seconds < 10) ? '0' + seconds : seconds);
                                    setTimeout("updateClock()", 1000);
                                }
                                //-->

//---------------------------------------------------------------------------------------------------------
                            </SCRIPT>
                            <td colspan="7" id="dateformat" height="20">
                                <ul id="hmenu" style="width:530px;">
                                    <li><a href="#">Абоненты</a>
                                        <ul>
                                            <?	$tn_tp = "tn=$TabNum&tp=$TypePers"; //	$GLOBALS['pers']['id_TypePers']
	$tPr = $TypePers==4?"&m_TabNum=$TabNum":"";
		?>
                                                <li><a href='javascript:op_f("ins2rad", "Mform");'>Обновить МАСи</a></li>
                                                <? /* add_build */ 
	if ($TypePers != 4) { ?>
                                                    <li><a href='javascript:{ch_param("recon", "<?	echo $tn_tp; ?>", "Mform"); }'>Подключение</a></li>
                                                    <li><a href='javascript:{ch_param("sh_form","menu=pay&<? echo $tn_tp?>","Mform");}'>Платеж</a></li>
                                                    <?php /*?>
                                                    <li><a href='javascript:{op_f("pay","Mform");setTimeout("document.forms.ulaForm.id_town.onchange()",300);}'>Платеж</a></li>
                                                    <?php */?>
                                                    <li><a href='javascript:{op_f("show_err", "Mform"); }'>Ошибки базы</a></li>
                                                    <li><a href='javascript:{ch_param("fin","<? echo "$tn_tp&per=0"?>","Mform");}'>Фин.отчёт</a></li>
                                                    <li><a href='javascript:{ch_param("sh_actions","<? echo "$tn_tp&per=0"?>","Mform");}'>Стат.отчёт</a></li>
                                                    <li><a href='javascript:{ch_param("sh_t_abon","<? echo "$tn_tp&per=1"?>","Mform");}'>Терминал(абон.ошиб)</a></li>
                                                    <li><a href='javascript:{ch_param("sh_t_inet","<? echo "$tn_tp&per=1"?>","Mform");}'>Терминал(инет.ошиб)</a></li>
                                                    <li><a href='javascript:{ch_param("sh_v_bad","<? echo "$tn_tp&per=1"?>","Mform");}'>Отключать!</a></li>
                                                    <!--			<li><a href='javascript:op_f("do_off_cust", "Mform");'>Откл. должн.(авто)</a></li>
			<li><a href='javascript:op_f("do_on_cust", "Mform");' >Подключить.(авто)</a></li>-->
                                                    <? } ?>
                                                        <?php /*?>
                                                        <li><a href='javascript:{ch_param("dolg", "tn=<? echo $TabNum; ?>&tp=<? echo $TypePers?>", "Mform"); }'>Должники</a></li>
                                                        <?php */?>
                                                        <li><a href='javascript:{ch_param("dolgn2","menu=dolgn2&<? echo $tn_tp.$tPr?>","Mform");}'>Должники (монтажн.)</a></li>
                                                        <li><a href='javascript:{ch_param("sh_form","menu=dolgn&<? echo $tn_tp?>", "Mform");}'>Должники</a></li>
                                                        <li><a href='javascript:{ch_param("sh_form","menu=otp&<? echo $tn_tp?>", "Mform");}'>Отпускники</a></li>
                                                        <li><a href='javascript:{ch_param("sh_form","menu=activ&<? echo $tn_tp?>", "Mform");}'>Действующие</a></li>
                                                        <li><a href='javascript:{ch_param("ab_err","<? echo $tn_tp?>", "Mform");}'>Нет заявки на откл.</a></li>
                                                        <li><a href='javascript:{ch_param("off_err","<? echo $tn_tp?>", "Mform");}'>откл.авто</a></li>
                                                        <li><a href='javascript:{ch_param("must_off","<? echo $tn_tp?>", "Mform");}'>Должн. с ошиб.!</a></li>
                                        </ul>
                                    </li>
                                    <?php /*?>
                                    <li><a href='javascript:{ch_param("dolg", "tn=<? echo $TabNum; ?>&tp=<? echo $TypePers?>", "Mform"); }'>Должники</a></li>
                                    <?php */?>
                                    <li><a href="#">Заявки</a>
                                        <ul>
                                            <? if ($TypePers != 4) { ?>
                                                <li><a href='javascript:ch_param("sh_form","menu=noti&<? echo $tn_tp.$tPr?>","Mform");'>на ремонт сети</a></li>
                                                <? } //$GLOBALS['pers']['id_TypePers']?>
                                                    <li><a href='javascript:ch_param("mont_frsh","<? echo $tn_tp.$tPr?>","Mform");'> (свежие) <? if($TypePers == 4){echo "Заявки ".$GLOBALS['pers']['Fam']; } else {?>монтажников<? }?></a>
                                                        <li>
                                                            <a href='javascript:ch_param("mont","<? echo $tn_tp.$tPr?>","Mform");'>
                                                                <? if($TypePers == 4){echo "Заявки ".$GLOBALS['pers']['Fam']; } else {?>монтажников
                                                                    <? }?>
                                                            </a>
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
    </select>&nbsp;--></li>
                                        </ul>
    //---------------------------------------------------------------------------------------------------------
                                        <? if ($TypePers != 4) { ?>
                                            <? }?>
                                                </li>
                                                <li><a href="#">Справочники</a>
                                                    <ul>
                                                        <? if ($TypePers < 3) { ?>
                                                            <li><a href='javascript:op_f("cod_adr", "Mform");'>коды адресов</a></li>
                                                            <? /* add_build */ ?>
                                                                <? } ?>
                                                                    <li><a href='javascript:{ch_param("sh_form","menu=edt_bld&<? echo $tn_tp?>", "Mform");}'>адресов</a></li>
                                                                    <!--		<li><a href='javascript:op_f("edt_bld_frm", "Mform");'>адресов</a></li><? /* add_build */ ?>-->
                                                                    <li><a href='javascript:op_f("equip", "Mform");'>оборудования</a></li>
                                                                    <?php /*?>
                                                                    <li><a href="#">персонала</a></li>
                                                                    <?php */?>
                                                                    <li><a href='javascript:op_f("edt_tar", "Mform");<?php /*?>ab_st(<? echo $res["ab_sum"] ?>)<?php */?>'>Абон. тарифы</a></li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <!--Пользователь-->
                                                        <? echo /*$TypePers!=1?"*/$username/*":""*/?>
                                                    </a>
                                                    <ul>
                                                        <li><a href='javascript:ch_param("logs","<? echo $tn_tp.($TypePers!=1?"&s=0&login=$username":"")?>","Mform");'>посещения</a></li>
                                                        <li><a href="change_passwd_form.php">Сменить пароль</a></li>
                                                        <li><a href="logout.php">Выход</a></li>
                                                    </ul>
                                                </li>
                                                <? if ($TypePers == 1) {?>
                                                    <li><a href="#">Админ</a>
                                                        <ul>
                                                            <li><a href='javascript:{op_f("exp", "Mform");}'>Отчёты‹</a></li>
                                                            <li><a href='javascript:op_f("pers", "Mform");'>Персонал</a></li>
                                                            <?	//$abon = mysql_query("SELECT * FROM `spr_tarifab` WHERE id_tarifab=1") or die(mysql_error());
			//	$res = mysql_fetch_assoc($abon);
		?>
                                                                <li><a href='javascript:ch_param("sh_statH","<? echo $tn_tp?>&per=0", "Mform");'>График</a></li>
                                                                <li><a href='javascript:op_f("edt_con", "Mform");<?php /*?>ab_st(<? echo $res["ab_sum"] ?>)<?php */?>'>Тарифы подключения</a></li>
                                                                <li><a href='javascript:{ch_param("sh_v","<? echo $tn_tp?>&tr=2", "Mform");}'>Сеть</a></li>
                                                                <li><a href='javascript:{ch_param("sh_v","<? echo $tn_tp?>&tr=3", "Mform");}'>Свои</a></li>
                                                                <li><a href='javascript:{ch_param("sh_v","<? echo $tn_tp?>&tr=4", "Mform");}'>Безнал</a></li>
                                                                <li><a href='javascript:{ch_param("sh_v","<? echo $tn_tp?>&tr=5", "Mform");}'>Льготные</a></li>
                                                                <li><a href='javascript:{ch_param("sh_v","<? echo $tn_tp?>&tr=6", "Mform");}'>VIP-ы</a></li>
                                                                <? if ($TabNum==2) {?>
                                                                    <li><a href='javascript:op_f("g_mac", "Mform");'>МАС</a></li>
                                                                    <!--<li><a href='javascript:op_f("pay_usr", "Mform");' class="navText">-Платёжи абонента-</a></li>
				<li><a href='javascript:op_f("tab_usr", "Mform");' class="navText">-Таблица абонентов-</a></li>-->
                                                                    <? }?>
                                                        </ul>
                                                    </li>
                                                    <? }?>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7" bgcolor="#5C743D"><img src="mm_spacer.gif" alt="" width="1" height="2" border="0" /></td>
                        </tr>
                    </table>
                </div>

                <table width=800 border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="7" valign="top">
                            <div id="Mform"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>
                                <div id="temp_area"></div>
                            </p>
                        </td>

                        <!--  <td width="2"><img src="mm_spacer.gif" alt="" width="10" height="1" border="0" /></td>-->
                        </td>
                    </tr>

                </table>
    </form>
    <? do_html_footer(); ?>
