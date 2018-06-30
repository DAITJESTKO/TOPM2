SELECT
v.Bill_Dog,
v.id_Podjezd,
v.name_street,
v.Num_build,
v.flat,
`Date_pay`
from v_customer as v
where 
	(isnull(`v`.`inet`) and 
	(`v`.`state` = 1) and 
	(`v`.`mac` > 0)  and
         Date_pay >= DATE_FORMAT(Date_pay,"%Y-%m-%d") and
         Bill_Dog` not in (
SELECT
customers.Bill_Dog
FROM
((customers
Inner Join spr_podjezd AS spr_pd_p ON ((customers.id_Podjezd = spr_pd_p.id_Podjezd)))
Inner Join spr_tarifab ON ((customers.id_tarifab = spr_tarifab.id_tarifab)))
Inner Join switches ON spr_pd_p.switch = switches.switch
where 
	(isnull(`customers`.`inet`) and 
	(`customers`.`state` = 1) and 
	(`customers`.`mac` > 0) and 
	(`spr_tarifab`.`VLan` > 0 or `spr_pd_p`.`VLan`>0) )
)
)
order by 
	`v`.`id_Podjezd`
