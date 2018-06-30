create view v_n_cods as
SELECT
Sum(1) AS nm,
customers.Cod_flat
FROM
customers
WHERE
customers.Cod_flat <>  0 AND
customers.inet IS NULL 
GROUP BY
customers.Cod_flat
