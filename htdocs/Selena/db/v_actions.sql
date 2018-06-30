CREATE VIEW `v_act2012` AS 
select 
	`a`.`TabNum` AS `TabNum`,
	`a`.`Bill_Dog` AS `Bill_Dog`,
	`a`.`InputDate` AS `InputDate`,
	`a`.`Date_start` AS `Date_start`,
	`a`.`Date_end` AS `Date_end`,
	`a`.`Summa` AS `Summa`,
	`spr_actiontype`.`ActionName` AS `ActionName`,
	`a`.`Comment` AS `Comment`,
	`personal`.`login` AS `p_login`,
	`a`.`TN_canc` AS `TN_canc`,
	`a`.`canc` AS `canc`,
	`a`.`id_ActionType` AS `id_ActionType`,
	`a`.`err` AS `err`,
	`a`.`Date_ed` AS `Date_ed`,
	if((`a`.`TN_canc` > 0),(select `personal`.`login` AS `login` from `personal` 
where 
	(`a`.`TN_canc` = `personal`.`TabNum`)),_utf8'') AS `login_ed` 
from 
	((`act2012` `a` join `spr_actiontype` on((`a`.`id_ActionType` = `spr_actiontype`.`id_ActionType`))) 
		join `personal` on((`a`.`TabNum` = `personal`.`TabNum`))) 
order by 
	`a`.`Bill_Dog`,`a`.`InputDate` desc,`a`.`Date_start` desc;


--
-- Структура для представления `v_actions`
--


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_actions` AS 
select 
	`a`.`TabNum` AS `TabNum`,
	`a`.`Bill_Dog` AS `Bill_Dog`,
	`a`.`InputDate` AS `InputDate`,
	`a`.`Date_start` AS `Date_start`,
	`a`.`Date_end` AS `Date_end`,
	`a`.`Summa` AS `Summa`,
	`spr_actiontype`.`ActionName` AS `ActionName`,
	`a`.`Comment` AS `Comment`,
	`personal`.`login` AS `p_login`,
	`a`.`TN_canc` AS `TN_canc`,
	`a`.`canc` AS `canc`,
	`a`.`id_ActionType` AS `id_ActionType`,
	`a`.`err` AS `err`,
	`a`.`Date_ed` AS `Date_ed`,
	if((`a`.`TN_canc` > 0),(select `personal`.`login` AS `login` 
from `personal` 
where 
	(`a`.`TN_canc` = `personal`.`TabNum`)),_utf8'') AS `login_ed` 
from 
	((`actions` `a` join `spr_actiontype` on((`a`.`id_ActionType` = `spr_actiontype`.`id_ActionType`))) 
		join `personal` on((`a`.`TabNum` = `personal`.`TabNum`))) 
order by 
	`a`.`Bill_Dog`,`a`.`InputDate` desc,`a`.`Date_start` desc;