 truncate `radius`.`radreply`;
 insert into `radius`.`radreply` 
	select id, username, attribute, op, value,nasipaddress from `selena`.`v_radreply`;