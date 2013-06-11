/* Consulta - 01 - Multi-Series Line Chart
   http://bl.ocks.org/mbostock/3884955   
   Traz para cada data do período de inscrições abertas a 
   quantidade de inscrições e a quantidade de pagamentos. */
SELECT DATAINSCRICAO DATA, C_INSC, C_PAG
  FROM
	-- Qtde. Inscrição/Dia
	(SELECT DATE(CREL_DATAINSCRICAO) DATAINSCRICAO, COUNT(1) C_INSC
	FROM CANDIDATO_RELATORIO
	WHERE CREL_PROCESSO = '20111_integrado'
	AND CREL_DATAINSCRICAO > '2010-01-01' -- tira datas inválidas
	GROUP BY DATAINSCRICAO) INSC,
	-- Qtde. Pagamento/Dia
	(SELECT DATE(CREL_DATAPAGAMENTO) DATAPAGAMENTO, COUNT(1) C_PAG
	FROM CANDIDATO_RELATORIO
	WHERE CREL_PROCESSO = '20111_integrado'
	AND CREL_DATAPAGAMENTO > '2010-01-01' -- tira datas inválidas
	GROUP BY DATAPAGAMENTO) PAG
 WHERE DATAINSCRICAO = DATAPAGAMENTO
 ORDER BY DATAINSCRICAO;