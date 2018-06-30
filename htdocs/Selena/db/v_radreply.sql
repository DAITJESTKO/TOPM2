drop view v_radreply;

create view v_radreply as
SELECT
customers.mac AS username,
if((`spr_tarifab`.`VLan` > 0),`spr_tarifab`.`VLan`,`spr_pd_p`.`VLan`) AS value,
switches.nasipaddress,
`customers`.`Bill_Dog`,
`customers`.`Date_end_st`,
`customers`.`Date_pay`,
customers.id_tarifab
FROM
((customers
Inner Join cod_flat ON customers.Cod_flat = cod_flat.Cod_flat
Inner Join spr_podjezd AS spr_pd_p ON ((cod_flat.id_Podjezd = spr_pd_p.id_Podjezd)))
Inner Join spr_tarifab ON ((customers.id_tarifab = spr_tarifab.id_tarifab)))
Inner Join switches ON spr_pd_p.switch = switches.switch
where 
	(`customers`.`inet` is null)
	 and (`spr_tarifab`.`VLan` > 0 or `spr_pd_p`.`VLan`>0)
	 and (LENGTH(`customers`.`mac`) > 0)
	 and (`customers`.`state` = 1)
              and (`customers`.`Date_end_st` >= CURDATE() 
			 OR
			customers.id_tarifab =  '2' # сеть
			 OR
			customers.id_tarifab =  '3' # свой
		)
order by 
	`customers`.`Bill_Dog`;
#=================================================