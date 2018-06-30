<?
/* MYSQL запрос :

DROP table IF EXISTS `t_personal`; 
    create table t_personal (
    id_personal int (10) AUTO_INCREMENT,
    name_personal varchar(20) NOT NULL,
    date_bidth_day date NOT NULL,
    id_type_personal int(10) NOT NULL DEFAULT '0',
    id_departament int(10) NOT NULL DEFAULT '0',
    PRIMARY KEY (id_personal) );

DROP table IF EXISTS `t_departament`; 
    create table t_departament (
    id_departament int (10) AUTO_INCREMENT,
    name_departament varchar(20) NOT NULL,
    PRIMARY KEY (id_departament) );

INSERT into t_departament (id_departament,name_departament) value ('{}','Консультации'),('{}','Технический'),('{}','Управления'); 

INSERT into t_personal (id_personal,name_personal,date_bidth_day,id_type_personal,id_departament) value 
('{}','Петров Ю.Н.','1996-09-25','1','1'),
('{}','Сергеенко А.Н.','1997-09-25','1','2'),
('{}','Шемонаев С.В.','1994-10-25','1','3'),
('{}','Щербаков К.Ю.','1992-09-25','2','1'), 
('{}','Васильев Е.П.','1996-09-25','2','2'),
('{}','Нестерова А.С.','1984-12-25','2','3'),
('{}','Петрова Ю.Н.','1996-11-23','2','2'),
('{}','Иванова А.Н.','1990-08-12','2','3'), 
('{}','Захаренко Т.Ф.','1992-01-15','2','1'),
('{}','Озеров В.Ю.','1993-12-31','2','2');

DROP VIEW IF EXISTS `v_t_users`;

CREATE VIEW `v_t_users` AS 
select 
`a`.`id_personal` AS `id_personal`,
`a`.`name_personal` AS `name_personal`,
`a`.`date_bidth_day` AS `date_bidth_day`,
`a`.`id_type_personal` AS `id_type_personal`,
`b`.`name_departament` AS `name_departament` 
from `t_personal` `a` join `t_departament` `b` on(`a`.`id_departament` = `b`.`id_departament`);


*/

global $link;
function db_connect()
{   
$user = '=first=';
$password = 'picsel1996';
$db = 'auth_reg';
$host = 'localhost';
$port = 8889;
$link = mysqli_init();
    
if (!$link) {
    die('mysqli_init завершилась провалом');
}
   $result = mysqli_connect(
   $host, 
   $user, 
   $password, 
   $db
);
	if (!$result)
		return false;
    
	//$r1 = mysqli_query("set character_set_results='utf8'");
	//$r1 = mysqli_query("SET NAMES 'utf8'");
    //echo "<br>DB_CONNECT IS GOOD<br>";
    //mysqli_close();
return $result;
}
$conn = db_connect();

$query = "SELECT * from t_departament";
$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($result)){
$name_departament = $row["name_departament"];   
$id_departament = $row["id_departament"];
$query = "SELECT `name_personal` from t_personal where id_departament = '$id_departament' and id_type_personal = '1'";
$result1 = mysqli_query($conn,$query);   
$row1 = mysqli_fetch_assoc($result1);
$query2 = "SELECT `name_personal`,(SELECT YEAR(CURDATE()) - YEAR(date_bidth_day)) as `age` from t_personal where id_departament = '$id_departament'";
$result2 = mysqli_query($conn,$query2);
while($row2 = mysqli_fetch_assoc($result2)) {  
    if($row1["name_personal"]==$row2["name_personal"])echo "<br> Руководитель - ",$row2["name_personal"],", Возраст - ",$row2["age"],", Департамент - ",$name_departament,"<br>";
else echo "<br> Имя сотрудника - ",$row2["name_personal"],", Возраст - ",$row2["age"],", Департамент - ",$name_departament,"<br>"; 
}
}


?>
