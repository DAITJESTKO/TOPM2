drop view v_podjezd;
create view v_podjezd as
SELECT
spr_podjezd.id_Podjezd AS id_Podjezd,
spr_podjezd.id_korp AS id_korp,
spr_podjezd.IP_Range AS IP_Range,
spr_podjezd.Podjezd AS Podjezd,
spr_podjezd.FirstFlat AS FirstFlat,
spr_podjezd.LastFlat AS LastFlat,
spr_build.id_street AS id_street,
spr_build.Num_build AS Num_build,
spr_build.Korpus AS Korpus,
spr_build.id_Region AS id_Region,
spr_street.name_street AS name_street,
spr_region.RegionName AS RegionName,
spr_street.id_Town AS id_Town,
spr_podjezd.VLan AS VLan,
spr_podjezd.switch,
spr_podjezd.auto
from (((`spr_podjezd` 
	join `spr_region`) 
	join `spr_street`) 
	join `spr_build` 
		on(((`spr_podjezd`.`id_korp` = `spr_build`.`id_korp`) and 
		(`spr_build`.`id_street` = `spr_street`.`id_street`) and 
		(`spr_build`.`id_Region` = `spr_region`.`id_Region`))))
