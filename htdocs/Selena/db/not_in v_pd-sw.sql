SELECT
v_podjezd.id_Podjezd,
v_podjezd.name_street,
v_podjezd.Num_build,
v_podjezd.Korpus,
v_podjezd.Podjezd
FROM
v_podjezd where v_podjezd.id_Podjezd 
not in (SELECT
id_Podjezd from `v_pd-sw`)
order by v_podjezd.name_street,
v_podjezd.Num_build,
v_podjezd.Korpus,
v_podjezd.Podjezd
