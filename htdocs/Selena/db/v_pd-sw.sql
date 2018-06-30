SELECT
v_podjezd.id_Podjezd,
`pd-sw`.VLan,
`pd-sw`.switch
FROM
`pd-sw`
Inner Join v_podjezd ON v_podjezd.name_street = `pd-sw`.street AND v_podjezd.Num_build = `pd-sw`.build AND v_podjezd.Korpus = `pd-sw`.korp AND v_podjezd.Podjezd = `pd-sw`.pd
