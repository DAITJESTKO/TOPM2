//создание вью с двух таблиц , соединение по id_type

DROP VIEW IF EXISTS `v_users`;

CREATE VIEW `v_users` AS 
select 
`a`.`id_user` AS `id_user`,
`a`.`id_type` AS `id_type`,
`a`.`login` AS `login`,
`a`.`password` AS `password`,
`a`.`email` AS `email`,
`b`.`name_type` AS `user_type`, 
`a`.`id_company` AS `id_company`
from (`auth_reg`.`users` `a` join `auth_reg`.`users_type` `b` on((`a`.`id_type` = `b`.`id_type`)))

//создание таблицы в БД

create table users (
id_user int (10) AUTO_INCREMENT,
name varchar(20) NOT NULL,
email varchar(50) NOT NULL,
password varchar(15) NOT NULL,
PRIMARY KEY (id_user)
); 


//добавление данных в таблицу users      
INSERT INTO users (id_user,login,password,email) VALUES ('{}','{$login}','{$password}','{$email}')
    

// создание таблицы логов и сброс ее
DROP TABLE IF EXISTS `logs`; //удаление ранее созданной таблицы

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!40101 SET character_set_client = utf8 */;

CREATE TABLE `logs` (

  `DT` datetime NOT NULL,

  `login` varchar(30) NOT NULL,

  `ip` varchar(15) NOT NULL DEFAULT '   .   .   .',

  `state` int(1) NOT NULL
) 
ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP VIEW IF EXISTS `v_established`;
CREATE VIEW `v_established` AS 
select 
`d`.`id_est_m` AS `id_est_m`,
`e`.`name_machine` AS `name_machine`,
`g`.`name_type_mach` AS `name_type_mach`,
`f`.`name_type_ws` AS `name_type_ws`,
`c`.`name_workshop` AS `name_workshop`,
`b`.`name_factory` AS `name_factory`,
`a`.`name_company` AS `name_company`


from (((((`auth_reg`.`established_m` `d` left join `auth_reg`.`workshops` `c` on((`c`.`id_workshop` = `d`.`id_workshop`))) 
         join `auth_reg`.`type_workshops` `f` on((`f`.`id_type_ws` = `c`.`id_type_ws`))) 
        join `auth_reg`.`machines` `e` on((`e`.`id_machine` = `d`.`id_machine`))) 
     join `auth_reg`.`type_machines` `g` on((`g`.`id_type_mach` = `e`.`id_type_mach`))) 
       join `auth_reg`.`factory` `b` on((`b`.`id_factory` = `c`.`id_factory`))) 
      join `auth_reg`.`company` `a` on((`a`.`id_company` = `b`.`id_company`))
      
//----------------------------------------------
      
      DROP VIEW IF EXISTS `v_est_objects`;
CREATE VIEW `v_est_objects` AS 
select `a`.`name_object` AS `name_object`,
`c`.`name_tech_oper` AS `name_tech_oper`,
`c`.`time_tech_oper` AS `time_tech_oper`,
`d`.`name_machine` AS `name_machine` 
from `auth_reg`.`objects` `a` left join `auth_reg`.`established_obj` `b` on(`a`.`id_object` = `b`.`id_object`) 
join `auth_reg`.`tech_processes` `c` on(`c`.`id_group_tech_proc` = `b`.`id_group_tech_proc`)
      join `auth_reg`.`machines` `d` on(`d`.`id_machine` = `c`.`id_machine`)
      

// двойная сортировка по login, а потом по amount
SELECT * FROM `v_est_objects` ORDER BY name_object ASC, priority_tech_oper ASC

    
//----------------------------------------------
    
    DROP VIEW IF EXISTS `v_est_objects`;
CREATE VIEW `v_est_objects` AS 
select 
`a`.`name_object` AS `name_object`,
`c`.`name_tech_oper` AS `name_tech_oper`,
`c`.`time_tech_oper` AS `time_tech_oper`,
`c`.`priority_tech_oper` AS `priority_tech_oper`,

`d`.`name_machine` AS `name_machine`,
`f`.`name_workshop` AS `name_workshop`,
`g`.`name_factory` AS `name_factory`,
`k`.`name_company` AS `name_company`

from `auth_reg`.`objects` `a` left join `auth_reg`.`established_obj` `b` on(`a`.`id_object` = `b`.`id_object`) 
join `auth_reg`.`tech_processes` `c` on(`c`.`id_group_tech_proc` = `b`.`id_group_tech_proc`)
      join `auth_reg`.`established_m` `e` on(`e`.`id_est_m` = `c`.`id_est_m`)
      join `auth_reg`.`machines` `d` on(`d`.`id_machine` = `e`.`id_machine`)
     
      join `auth_reg`. `workshops` `f` on(`f`.`id_workshop` = `e`.`id_workshop`)
      join `auth_reg`. `factory` `g` on(`g`.`id_factory` = `f`.`id_factory`)
      join `auth_reg`. `company` `k` on(`k`.`id_company` = `g`.`id_company`)