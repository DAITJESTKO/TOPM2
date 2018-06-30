create view v_act as
select * from act2010
union
select * from act2011
union
select * from act2012