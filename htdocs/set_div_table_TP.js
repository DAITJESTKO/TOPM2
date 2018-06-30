 function set(inner, check) {
     
     var count_row = document.getElementById('count_row').value;     
     count_row = Number(count_row)+Number(check);
     document.getElementById('count_row').value = count_row;    
 if(check>0){  
   if(arr[Number(count_row)-1] == undefined) arr[Number(count_row)-1] = new Array;     
   if(arr_name[Number(count_row)-1] == undefined) arr_name[Number(count_row)-1] = new Array; 
        arr[Number(count_row)-1][0] = document.getElementById('name_oper').value;    
        arr[Number(count_row)-1][1] = document.getElementById('name_factory').value;
        arr_name[Number(count_row)-1][1] = document.getElementById('name_factory').options[document.getElementById('name_factory').selectedIndex].text;        
        arr[Number(count_row)-1][2] = document.getElementById('name_workshop').value;
        arr_name[Number(count_row)-1][2] = document.getElementById('name_workshop').options[document.getElementById('name_workshop').selectedIndex].text;    
        arr[Number(count_row)-1][3] = document.getElementById('name_machine').value;
        arr_name[Number(count_row)-1][3] = document.getElementById('name_machine').options[document.getElementById('name_machine').selectedIndex].text;     
        arr[Number(count_row)-1][4] = document.getElementById('time_oper').value;
        document.getElementById('colt_change_numb').innerHTML = count_row;
 }
document.getElementById(inner).innerHTML = '';
         for(var i=1;i<count_row;i++){
                  
              document.getElementById(inner).innerHTML += '<tr id="'+i+'row_tp"><td id="colt">'+i+'</td><td id="'+i+'name_oper"><input type="hidden" value="'+arr[i][0]+'" />'+arr[i][0]+'</td><td id="'+i+'name_factory">'+arr_name[i][1]+'<input type="hidden" id="'+i+'name_factory_i" value="'+arr[i][1]+'" /></td><td id="'+i+'name_workshop">'+arr_name[i][2]+'<input type="hidden" value="'+arr[i][2]+'" /></td><td id="'+i+'name_machine">'+arr_name[i][3]+'<input type="hidden" value="'+arr[i][3]+'" /></td><td id="colt">'+arr[i][4]+'</td></tr>';
         }    
if(check<0){
 document.getElementById(inner).innerHTML = '';
    
         for(var i=1;i<count_row;i++){
           
             document.getElementById(inner).innerHTML += '<tr id="'+i+'row_tp"><td id="colt">'+i+'</td><td id="'+i+'name_oper"><input type="hidden" value="'+arr[i][0]+'" />'+arr[i][0]+'</td><td id="'+i+'name_factory">'+arr_name[i][1]+'<input type="hidden" value="'+arr[i][1]+'" /></td><td id="'+i+'name_workshop">'+arr_name[i][2]+'<input type="hidden" value="'+arr[i][2]+'" />'+arr[i][2]+'</td><td id="'+i+'name_machine">'+arr_name[i][3]+'<input type="hidden" value="'+arr[i][3]+'" />'+arr[i][3]+'</td><td id="colt">'+arr[i][4]+'</td></tr>';             
         }    
}
     document.getElementById('colt_change_numb').innerHTML = count_row;   
 }

function send_TP(id_company){
    
    var count_row = document.getElementById('count_row').value;     
    //alert(count_row);
    var id_company = document.getElementById('id_company').value;
    var name_object = document.getElementById('name_object').value; 
    var quantity_object = document.getElementById('quantity_object').value; 
    var part_object = document.getElementById('part_object').value; 
    var id_parent = document.getElementById('id_parent').value; 
    //alert(id_parent);
    var last_v="id_company="+id_company+"&id_parent="+id_parent+"&count_row="+count_row+"&name_object='"+name_object+"'&quantity_object="+quantity_object+"&part_object="+part_object+"&";
    //alert(last_v);
    
for(var i=1;i<count_row;i++){
    
    var v0 = i+"name_oper='"+arr[i][0]+"'"; // имя операции
    var v1 = i+"id_factory="+arr[i][1]; // id_factory
    var v2 = i+"id_workshop="+arr[i][2]; // id_workshop
    var v3 = i+"id_machine="+arr[i][3]; // id_machine
    var v4 = i+"time_oper="+arr[i][4]; // time_oper
    
    last_v = last_v + v0+"&"+v1+"&"+v2+"&"+v3+"&"+v4+((i<count_row-1) ? "&":"");
    
}
    //alert(last_v);
    
    ch_param('view_fac',last_v,'D_INFO');
    
}
 
