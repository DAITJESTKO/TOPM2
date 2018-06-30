SELECT
spr_podjezd.id_Podjezd AS id_Podjezd,
spr_podjezd.Podjezd AS Podjezd,
spr_build.Num_build AS Num_build,
spr_build.Korpus AS Korpus,
spr_street.name_street AS name_street,
spr_street.id_Town AS id_Town,
`pd-sw`.VLan,
`pd-sw`.switch
FROM
(((spr_podjezd) ,
spr_street)
Inner Join spr_build ON (((spr_podjezd.id_korp = spr_build.id_korp) AND (spr_build.id_street = spr_street.id_street))))
Inner Join `pd-sw` ON `pd-sw`.street = spr_street.name_street AND `pd-sw`.build = spr_build.Num_build AND `pd-sw`.korp = spr_build.Korpus AND `pd-sw`.pd = spr_podjezd.Podjezd
