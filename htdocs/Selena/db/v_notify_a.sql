drop view `v_notify_a`;

CREATE view `v_notify_a` AS 

select 
`n`.`Num_Notify` AS `Num_Notify`,`n`.`Bill_Dog` AS `Bill_Dog`,`n`.`Cod_flat` AS `Cod_flat`,`cod_flat`.`id_Podjezd` AS `id_p`,`cod_flat`.`flat` AS `flat`,`cod_flat`.`floor` AS `floor`,`n`.`Date_in` AS `Date_in`,
`n`.`Date_Plan` AS `Date_Plan`,`n`.`Date_Fact` AS `Date_Fact`,`n`.`Date_ed` AS `Date_ed`,`n`.`Notify` AS `Notify`,`n`.`Nic` AS `Nic`,`n`.`TabNum` AS `TabNum`,
if(`n`.`TabNum` in (select `t`.`TabNum` AS `TabNum` from `personal` `t` where (`n`.`TabNum` = `t`.`TabNum`)),(select `t1`.`login` AS `login` from `personal` `t1` where (`n`.`TabNum` = `t1`.`TabNum`)),NULL) AS `disp`,
`n`.`conn` AS `conn`,`n`.`phone_Dop` AS `phone_Dop`,`n`.`comment` AS `comment`,`n`.`TN_canc` AS `TN_canc`,`n`.`canc` AS `canc`,
if((`n`.`TN_canc` is not null),(select `mc`.`login` AS `login` from `personal` `mc` where (`n`.`TN_canc` = `mc`.`TabNum`)),NULL) AS `c_login`,
`n`.`fwdH` AS `fwdH`,`n`.`fwdW` AS `fwdW`,`n`.`fwd2` AS `fwd2`,`c`.`Fam` AS `Fam`,`c`.`Name` AS `Name`,`c`.`Father` AS `Father`,`c`.`phone_Home` AS `phone_Home`,`c`.`phone_Cell` AS `phone_Cell`,`c`.`phone_Work` AS `phone_Work`,
`personal`.`TabNum` AS `m_TabNum`,`spr_region`.`RegionName` AS `RegionName`,`s`.`name_street` AS `name_street`,`b`.`Num_build` AS `Num_build`,`b`.`Korpus` AS `Korpus`,`p`.`Podjezd` AS `Podjezd`,
if(`n`.`mont` in (select `m`.`TabNum` AS `mont` from `personal` `m` where (`n`.`mont` = `m`.`TabNum`)),(select `m1`.`login` AS `login` from `personal` `m1` where (`n`.`mont` = `m1`.`TabNum`)),NULL) AS `m_login` ,
`c`.`state` as `state`,`c`.`mac` as `mac`, `c`.`inet` as `inet`, `p`.`auto` as `auto`
from (((((((`notify_repair` `n` join `cod_flat` on((`n`.`Cod_flat` = `cod_flat`.`Cod_flat`))) 
	join `customers` `c` on((`n`.`Bill_Dog` = `c`.`Bill_Dog`))) 
	join `spr_podjezd` `p` on((`cod_flat`.`id_Podjezd` = `p`.`id_Podjezd`))) 
	join `spr_build` `b` on((`p`.`id_korp` = `b`.`id_korp`))) 
	join `spr_region` on((`b`.`id_Region` = `spr_region`.`id_Region`))) 
	join `personal` on((`b`.`id_Region` = `personal`.`id_Region`))) 
	join `spr_street` `s` on((`b`.`id_street` = `s`.`id_street`))) 
where (`n`.`Cod_flat` > _utf8'0') 

union 
select 
`n`.`Num_Notify` AS `Num_Notify`,`n`.`Bill_Dog` AS `Bill_Dog`,`c`.`Cod_flat` AS `Cod_flat`,`c`.`id_Podjezd` AS `id_p`,`c`.`flat` AS `flat`,`c`.`floor` AS `floor`,`n`.`Date_in` AS `Date_in`,
`n`.`Date_Plan` AS `Date_Plan`,`n`.`Date_Fact` AS `Date_Fact`,`n`.`Date_ed` AS `Date_ed`,`n`.`Notify` AS `Notify`,`n`.`Nic` AS `Nic`,`n`.`TabNum` AS `TabNum`,
if(`n`.`TabNum` in (select `t`.`TabNum` AS `TabNum` from `personal` `t` where (`n`.`TabNum` = `t`.`TabNum`)),(select `t1`.`login` AS `login` from `personal` `t1` where (`n`.`TabNum` = `t1`.`TabNum`)),NULL) AS `disp`,
`n`.`conn` AS `conn`,`n`.`phone_Dop` AS `phone_Dop`,`n`.`comment` AS `comment`,`n`.`TN_canc` AS `TN_canc`,`n`.`canc` AS `canc`,
if((`n`.`TN_canc` is not null),(select `mc`.`login` AS `login` from `personal` `mc` where (`n`.`TN_canc` = `mc`.`TabNum`)),NULL) AS `c_login`,
`n`.`fwdH` AS `fwdH`,`n`.`fwdW` AS `fwdW`,`n`.`fwd2` AS `fwd2`,`c`.`Fam` AS `Fam`,`c`.`Name` AS `Name`,`c`.`Father` AS `Father`,`c`.`phone_Home` AS `phone_Home`,`c`.`phone_Cell` AS `phone_Cell`,`c`.`phone_Work` AS `phone_Work`,
`personal`.`TabNum` AS `m_TabNum`,`spr_region`.`RegionName` AS `RegionName`,`s`.`name_street` AS `name_street`,`b`.`Num_build` AS `Num_build`,`b`.`Korpus` AS `Korpus`,`p`.`Podjezd` AS `Podjezd`,
if(`n`.`mont` in (select `m`.`TabNum` AS `mont` from `personal` `m` where (`n`.`mont` = `m`.`TabNum`)),(select `m1`.`login` AS `login` from `personal` `m1` where (`n`.`mont` = `m1`.`TabNum`)),NULL) AS `m_login`,
`c`.`state` as `state`,`c`.`mac` as `mac`, `c`.`inet` as `inet`, `p`.`auto` as `auto`
from (((((((`customers` `c` join `notify_repair` `n` on((`c`.`Bill_Dog` = `n`.`Bill_Dog`))) join `cod_flat` on((`c`.`Cod_flat` = `cod_flat`.`Cod_flat`))) 
	join `spr_podjezd` `p` on((`cod_flat`.`id_Podjezd` = `p`.`id_Podjezd`))) 
	join `spr_build` `b` on((`p`.`id_korp` = `b`.`id_korp`))) 
	join `spr_region` on((`b`.`id_Region` = `spr_region`.`id_Region`))) 
	join `personal` on((`b`.`id_Region` = `personal`.`id_Region`))) 
	join `spr_street` `s` on((`b`.`id_street` = `s`.`id_street`))) 
where ((`n`.`Cod_flat` = _utf8'0') and (`c`.`Cod_flat` > 0)) 

union 
select 
`n`.`Num_Notify` AS `Num_Notify`,`n`.`Bill_Dog` AS `Bill_Dog`,`c`.`Cod_flat` AS `Cod_flat`,`c`.`id_Podjezd` AS `id_p`,`c`.`flat` AS `flat`,`c`.`floor` AS `floor`,`n`.`Date_in` AS `Date_in`,`n`.`Date_Plan` AS `Date_Plan`,
`n`.`Date_Fact` AS `Date_Fact`,`n`.`Date_ed` AS `Date_ed`,`n`.`Notify` AS `Notify`,`n`.`Nic` AS `Nic`,`n`.`TabNum` AS `TabNum`,
if(`n`.`TabNum` in (select `t`.`TabNum` AS `TabNum` from `personal` `t` where (`n`.`TabNum` = `t`.`TabNum`)),(select `t1`.`login` AS `login` from `personal` `t1` where (`n`.`TabNum` = `t1`.`TabNum`)),NULL) AS `disp`,
`n`.`conn` AS `conn`,`n`.`phone_Dop` AS `phone_Dop`,`n`.`comment` AS `comment`,`n`.`TN_canc` AS `TN_canc`,`n`.`canc` AS `canc`,
if((`n`.`TN_canc` is not null),(select `mc`.`login` AS `login` from `personal` `mc` where (`n`.`TN_canc` = `mc`.`TabNum`)),NULL) AS `c_login`,
`n`.`fwdH` AS `fwdH`,`n`.`fwdW` AS `fwdW`,`n`.`fwd2` AS `fwd2`,`c`.`Fam` AS `Fam`,`c`.`Name` AS `Name`,`c`.`Father` AS `Father`,`c`.`phone_Home` AS `phone_Home`,`c`.`phone_Cell` AS `phone_Cell`,`c`.`phone_Work` AS `phone_Work`,
`personal`.`TabNum` AS `m_TabNum`,`spr_region`.`RegionName` AS `RegionName`,`s`.`name_street` AS `name_street`,`b`.`Num_build` AS `Num_build`,`b`.`Korpus` AS `Korpus`,`p`.`Podjezd` AS `Podjezd`,
if(`n`.`mont` in (select `m`.`TabNum` AS `mont` from `personal` `m` where (`n`.`mont` = `m`.`TabNum`)),(select `m1`.`login` AS `login` from `personal` `m1` where (`n`.`mont` = `m1`.`TabNum`)),NULL) AS `m_login`,
`c`.`state` as `state`,`c`.`mac` as `mac`, `c`.`inet` as `inet`, `p`.`auto` as `auto`
from ((((((`customers` `c` 
	join `notify_repair` `n` on((`c`.`Bill_Dog` = `n`.`Bill_Dog`))) 
	join `spr_podjezd` `p` on((`c`.`id_Podjezd` = `p`.`id_Podjezd`))) 
	join `spr_build` `b` on((`p`.`id_korp` = `b`.`id_korp`))) 
	join `spr_region` on((`b`.`id_Region` = `spr_region`.`id_Region`))) 
	join `personal` on((`b`.`id_Region` = `personal`.`id_Region`))) 
	join `spr_street` `s` on((`b`.`id_street` = `s`.`id_street`))) 
where ((`n`.`Cod_flat` = _utf8'0') and (`c`.`Cod_flat` = 0)) 

order by `Date_Plan`,`Num_Notify`;
