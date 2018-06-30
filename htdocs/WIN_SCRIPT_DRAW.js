var map ;
var ctx ;
var kolvoDet = 0;
var kolvoOper = 0;
var razmPart = 0;
var vremOper = [];
vremOper.length = 0;
var Tposl = 0, Tnepr = 0, Tpp = 0;
var z = 0;
 var k = 0;


function main() {
  map = document.getElementById('map');

  ctx = map.getContext('2d');
    
  console.log(map);
     
  input();  //ввод данных
  drawXY(30);  //рисуем координатные оси
  calcTposl(); //время посл. цикла
  drawRiskiX(30); // рисуем метки на оси Х
  drawRiskiY(30); // рисуем метки на оси Y
  //alert('Цена деления по оси X= ' + tsenaDelX());
  //alert('Цена деления по оси Y= ' + tsenaDelY());
  //alert('Тпосл.= ' + Tposl);
  calkK();
  drawTposl(30);
  drawTnepr();
  drawTpp(30);
  
    document.getElementById('Posl_time').value = Tposl;
    document.getElementById('Paral_time').value = Tnepr;
    document.getElementById('Smesh_time').value = Tpp;
}


function input() {
    //alert("INPUT");
  kolvoDet = document.getElementById('quantity_obj').value;
      //alert(kolvoDet);
  razmPart = document.getElementById('part_obj').value;
    //alert(razmPart);
  kolvoOper = document.getElementById('quantity_tech_oper').value;
    //alert(kolvoOper);
  for (var i = 0; i < kolvoOper; i++) {
      var time_tech_oper = "time_tech_oper"+Number(i+1);
    vremOper[i] = eval("document.getElementById(time_tech_oper).value");
      //alert(vremOper[i]);
  } 
}   

function drawXY(x) {
  x = x+0.5;
	//--------------X
  ctx.moveTo(x, x);
  ctx.lineTo(map.width-30.5, x);
  ctx.lineTo(map.width-30.5-15,x-5);
  ctx.moveTo(map.width-30.5, x);
  ctx.lineTo(map.width-30.5-15,x+5);
  ctx.moveTo(x, x);

  //--------------Y
  ctx.moveTo(x,x);
  ctx.lineTo(x, map.height-30.5);
  //ctx.lineTo(x-5, map.height-30.5-15);
  //ctx.moveTo(x, map.height-30.5);
  //ctx.lineTo(x+5, map.height-30.5-15);
  ctx.strokeStyle = 'black';
  ctx.stroke();
}

function calcTposl() {
  Tposl = 0;
  for (var i = 0; i < vremOper.length; i++) {
      //alert("CALC_TPOSL = "+vremOper[i]);
    Tposl = Tposl + kolvoDet*vremOper[i]; //переделать алгоритм расчета ???
    } 
}

function minOper() {
  var x = vremOper[0];
  for (var i = 0; i < vremOper.length; i++) {
    if (x > vremOper[i]) x = vremOper[i];
  }
  return x;
}

function tsenaDelX() {
  return Math.round( (map.width - 30.5 - 30.5) / (Tposl / (razmPart * minOper())) ); //переделать алгоритм (считает не правильно)
}

function tsenaDelY() {
  return Math.round( (map.height -30.5 - 30.5) / kolvoOper );
}

function drawRiskiX(x){
  x = x+0.5;
  ctx.beginPath();
  for (var i = x; i < map.width - 30.5 - x; i = i +  tsenaDelX()) {
    ctx.moveTo(i, x);
    ctx.lineTo(i, x-4);
  }
  ctx.strokeStyle = 'red';
  ctx.stroke();
}

function drawRiskiY(x) {
  x=x+0.5;
  ctx.beginPath();
  ctx.textAlign = 'center';
  ctx.font = '24px serif';
  for (var i = 0, z = 1, y = x; i < kolvoOper; i++) {
    ctx.fillText(z, x-x/2, y + tsenaDelY()/2+6);
    ctx.moveTo(x,y);
    ctx.lineTo(0,y);
    z = z+1;
    y = y + tsenaDelY();
  }
  ctx.moveTo(x,y);
  ctx.lineTo(0,y);
  ctx.strokeStyle = 'black';
  ctx.stroke();
}

function drawTposl(x) {
  x = x+0.5;
  var e = x;
  ctx.beginPath();
  ctx.strokeStyle = 'black';
  ctx.lineWidth = 3;
  //alert =('k=' +k);
  for (var j = 0; j < kolvoOper; j++) {
   // alert(j);
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()/4)+0.5);
    ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()/4-5)+0.5);     
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()/4)+0.5);   
    
    for (var i = 0; i < kolvoDet/razmPart; i++) {
      ctx.lineWidth = 3;
      e = e + k * vremOper[j]*razmPart;
     // alert (e+'- '+i);
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()/4)+0.5);
      ctx.lineWidth = 1;
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()/4-5)+0.5);     
      ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()/4)+0.5);       
    }
  }
  ctx.stroke();
}

function calkK() {
    //alert =('MW='+map.width);
 k = (map.width-61)/Tposl ;
    //alert =('k=' +k);
}

function draw1part(r) {
  var x = 30.5;
  var e = r+0.5;
  ctx.beginPath();
  ctx.strokeStyle = 'blue';
  ctx.lineWidth = 3;
  //alert =('k=' +k);
  for (var j = 0; j < kolvoOper; j++) {
   // alert(j);
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + 2*tsenaDelY()/4)+0.5);
    ctx.lineTo(e, Math.round(x + tsenaDelY()*j + 2*tsenaDelY()/4-5)+0.5);     
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + 2*tsenaDelY()/4)+0.5);   
    
    for (var i = 0; i < 1; i++) {
      ctx.lineWidth = 3;
      e = e + k * vremOper[j]*razmPart;
     // alert (e+'- '+i);
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + 2*tsenaDelY()/4)+0.5);
      ctx.lineWidth = 1;
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + 2*tsenaDelY()/4-5)+0.5);     
      ctx.moveTo(e, Math.round(x + tsenaDelY()*j + 2*tsenaDelY()/4)+0.5);       
    }
  }
  ctx.stroke();
    //alert =('Tnepr=' +e); 
  Tnepr = e;
}

function drawTnepr() {
  for (var i = 0; i < kolvoDet/razmPart; i++) {
    draw1part(30+(i*maxOper()*razmPart)*k);

  }
}

function drawTpp(x) {
  x = x+0.5;
  var e = x;
var Z=0;
  ctx.beginPath();
  ctx.strokeStyle = 'red';
  ctx.lineWidth = 3;
  //alert =('k=' +k);
  for (var j = 0; j < 1; j++) {
   // alert(j);
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
    ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4-5)+0.5);     
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);   
    
    for (var i = 0; i < kolvoDet/razmPart; i++) {
      ctx.lineWidth = 3;
      e = e + k * vremOper[j]*razmPart;
     // alert (e+'- '+i);
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
      ctx.lineWidth = 1;
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4-5)+0.5);     
      ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);  
     
    }
  }
Z=e;


for(var j = 1; j < kolvoOper; j++){


if(vremOper[j] >= vremOper[j-1]){

    e=Z-vremOper[j-1]*k*razmPart*((kolvoDet/razmPart)-1);    

    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
    ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4-5)+0.5);     
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);


    for (var i = 0; i < kolvoDet/razmPart; i++) {

      ctx.lineWidth = 3;

      e = e + k * vremOper[j]*razmPart;
      
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
      ctx.lineWidth = 1;
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4-5)+0.5);     
      ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
Z=e;
    }


  }else{

    e=Z-vremOper[j]*k*razmPart*((kolvoDet/razmPart)-1);

    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
    ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4-5)+0.5);     
    ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);


     for (var i = 0; i < kolvoDet/razmPart; i++) {

      ctx.lineWidth = 3;

      e = e + k * vremOper[j]*razmPart;
      
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
      ctx.lineWidth = 1;
      ctx.lineTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4-5)+0.5);     
      ctx.moveTo(e, Math.round(x + tsenaDelY()*j + tsenaDelY()*3/4)+0.5);
      Z=e;
    }

  }

}



  ctx.stroke();
  Tpp = e;
}


function maxOper() {
  var x = vremOper[0];
    
    //alert("LENGTH_VREMOPER = "+vremOper.length);
  for (var i = 0; i < vremOper.length; i++) {
      //alert("MAX_FOR = "+vremOper[i]);
      //alert("X-[] = "+x+" - "+vremOper[i]+" = "+((x-vremOper[i])<0));
      
    if ((x-vremOper[i])<0){ 
        x = vremOper[i];
       //alert("MAX_IF = "+x);
                        }
  }
  return x;

}

function drawRazd() {
  var x = 30.5
  var y = x;
  ctx.beginPath();
  for (var i = 1; i < kolvoOper + 1; i++) {
    ctx.moveTo(x, y + i * tsenaDelY());
    ctx.lineTo(map.width, y + i * tsenaDelY());
  }
  ctx.setLineDash([3,3]);
  ctx.strokeStyle = 'grey';
  ctx.stroke();  
}

function drawResult() {
  ctx.beginPath();
  ctx.textAlign = 'center';
  ctx.font = '24px serif';
  ctx.fillStyle = 'black';
  ctx.fillText('Tпосл = ' + Tposl, map.width/4, map.height-5);
  ctx.stroke();
  
  ctx.fillStyle = 'blue';
  ctx.fillText('Tпарал = ' + Math.round(Tnepr), 2*map.width/4, map.height-5);
  ctx.stroke();
  ctx.fillStyle = 'red';
  ctx.fillText('Tпп = ' + Math.round(Tpp), 3*map.width/4, map.height-5);
  ctx.stroke();

}
/*
function drawK() {
  ctx.beginPath();
  ctx.moveTo(30.5+k,30.5);
  ctx.lineTo(30.5+k,30.5-15);
  ctx.strokeStyle = 'black';
  ctx.stroke();
}
*/
//http://cherry01b.000webhostapp.com/