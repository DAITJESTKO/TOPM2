SELECT
v_cust_cod.mac,
v_cust_cod.Bill_Dog,
v_cust_cod.Nic,
v_cust_cod.id_Podjezd,
v_cust_cod.name_street,
v_cust_cod.Num_build,
v_cust_cod.flat,
v_cust_cod.IP,
v_cust_cod.pasp_Ser,
v_cust_cod.pasp_Num,
v_cust_cod.pasp_Date,
v_cust_cod.pasp_Uvd,
v_cust_cod.phone_Home,
v_cust_cod.phone_Cell,
v_cust_cod.Jur,
v_cust_cod.Birthday,
v_cust_cod.pasp_Adr,
v_cust_cod.phone_Work,
v_cust_cod.Fam,
v_cust_cod.Name,
v_cust_cod.Father,
v_cust_cod.cost,
v_cust_cod.Saldo,
v_cust_cod.name_ab,
v_cust_cod.Podjezd,
v_cust_cod.Korpus,
v_cust_cod.id_tarifab,
v_cust_cod.TabNum,
v_cust_cod.DateKor,
v_cust_cod.tarifab_date,
v_cust_cod.From_Net,
v_cust_cod.Cod_flat,
v_cust_cod.`Comment`,
v_cust_cod.Bill_frend,
v_cust_cod.state,
v_cust_cod.conn,
v_cust_cod.FirstFlat,
v_cust_cod.LastFlat,
v_cust_cod.id_street,
v_cust_cod.Date_start_st,
v_cust_cod.Date_end_st,
v_cust_cod.RegionName,
v_cust_cod.name_abon,
v_cust_cod.con_sum,
v_cust_cod.opl_period,
v_cust_cod.ab_sum,
v_cust_cod.con_typ,
v_cust_cod.m_TabNum,
v_cust_cod.id_korp,
v_cust_cod.Date_pay,
v_cust_cod.Town,
v_cust_cod.inet,
v_cust_cod.k_tar,
v_cust_cod.`floor`,
v_cust_cod.VLan,
v_cust_cod.switch,
v_cust_cod.auto
FROM v_cust_cod
where `Cod_flat` > 0
union
select v_cust_cod_0.mac,
v_cust_cod_0.Bill_Dog,
v_cust_cod_0.Nic,
v_cust_cod_0.id_Podjezd,
v_cust_cod_0.name_street,
v_cust_cod_0.Num_build,
v_cust_cod_0.flat,
v_cust_cod_0.IP,
v_cust_cod_0.pasp_Ser,
v_cust_cod_0.pasp_Num,
v_cust_cod_0.pasp_Date,
v_cust_cod_0.pasp_Uvd,
v_cust_cod_0.phone_Home,
v_cust_cod_0.phone_Cell,
v_cust_cod_0.Jur,
v_cust_cod_0.Birthday,
v_cust_cod_0.pasp_Adr,
v_cust_cod_0.phone_Work,
v_cust_cod_0.Fam,
v_cust_cod_0.Name,
v_cust_cod_0.Father,
v_cust_cod_0.cost,
v_cust_cod_0.Saldo,
v_cust_cod_0.name_ab,
v_cust_cod_0.Podjezd,
v_cust_cod_0.Korpus,
v_cust_cod_0.id_tarifab,
v_cust_cod_0.TabNum,
v_cust_cod_0.DateKor,
v_cust_cod_0.tarifab_date,
v_cust_cod_0.From_Net,
v_cust_cod_0.Cod_flat,
v_cust_cod_0.`Comment`,
v_cust_cod_0.Bill_frend,
v_cust_cod_0.state,
v_cust_cod_0.conn,
v_cust_cod_0.FirstFlat,
v_cust_cod_0.LastFlat,
v_cust_cod_0.id_street,
v_cust_cod_0.Date_start_st,
v_cust_cod_0.Date_end_st,
v_cust_cod_0.RegionName,
v_cust_cod_0.name_abon,
v_cust_cod_0.con_sum,
v_cust_cod_0.opl_period,
v_cust_cod_0.ab_sum,
v_cust_cod_0.con_typ,
v_cust_cod_0.m_TabNum,
v_cust_cod_0.id_korp,
v_cust_cod_0.Date_pay,
v_cust_cod_0.Town,
v_cust_cod_0.inet,
v_cust_cod_0.k_tar,
v_cust_cod_0.`floor`,
v_cust_cod_0.VLan,
v_cust_cod_0.switch,
v_cust_cod_0.auto
 FROM v_cust_cod_0 where `Cod_flat` = 0
