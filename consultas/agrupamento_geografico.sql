/* PostgreSQL */
update export set idbairro = a.idbairro from bairrobh a where 
ST_Contains(a.the_geom, export.the_geom)

update export set idmun = a.idmun from munmg a where 
ST_Contains(a.the_geom, export.the_geom)

update export set idmeso = a.idmeso from mesomg a where 
ST_Contains(a.the_geom, export.the_geom)

/* MySQL */

update candidato_relatorio c, export a set c.idbairro= a.idbairro
where c.crel_inscricao = a.crel_inscricao

update candidato_relatorio c, export a set c.idmun = a.idmun
where c.crel_inscricao = a.crel_inscricao

update candidato_relatorio c, export a set c.idmeso = a.idmeso
where c.crel_inscricao = a.crel_inscricao

/* Agrupamentos */

/* Media da prova por regiao */
SELECT idmeso , sum(crel_notafinal)/count(*)
FROM `candidato_relatorio`
WHERE crel_processo = '20111_integrado'
GROUP BY idmeso
order by sum(crel_notafinal)/count(*) desc;

SELECT idmun, sum(crel_notafinal)/count(*)
FROM `candidato_relatorio`
WHERE crel_processo = '20111_integrado'
GROUP BY idmun
order by sum(crel_notafinal)/count(*) desc;

SELECT idbairro, sum(crel_notafinal)/count(*)
FROM `candidato_relatorio`
WHERE crel_processo = '20111_integrado'
GROUP BY idbairro
order by sum(crel_notafinal)/count(*) desc;

/* Numero de candidatos por regiao*/
SELECT idmeso , count(*)
FROM `candidato_relatorio`
WHERE crel_processo = '20111_integrado'
GROUP BY idmeso
order by count(*) desc;

SELECT idmun, count(*)
FROM `candidato_relatorio`
WHERE crel_processo = '20111_integrado'
GROUP BY idmun
order by count(*) desc;

SELECT idbairro, count(*)
FROM `candidato_relatorio`
WHERE crel_processo = '20111_integrado'
GROUP BY idbairro
order by count(*) desc;

/* Numero de candidatos aprovados por regiao */
SELECT idmeso, count(*)
FROM `candidato_relatorio`
WHERE crel_sit_vestibular = 'C'
AND crel_processo = '20111_integrado'
GROUP BY idmeso
order by count(*) desc;

SELECT idmun, count(*)
FROM `candidato_relatorio`
WHERE crel_sit_vestibular = 'C'
AND crel_processo = '20111_integrado'
GROUP BY idmun
order by count(*) desc;

SELECT idbairro, count(*)
FROM `candidato_relatorio`
WHERE crel_sit_vestibular = 'C'
AND crel_processo = '20111_integrado'
GROUP BY idbairro
order by count(*) desc;