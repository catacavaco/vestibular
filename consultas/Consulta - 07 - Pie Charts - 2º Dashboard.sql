/* Consulta - 07 - Pie Charts - 2º Dashboard
   http://bl.ocks.org/mbostock/3887235#data.csv

   Mostra as estatísticas de acerto de cada questão a partir do 
   do parâmetro "Matéria" ser alimentado.

Como o número de questões varia dependendo da matéria, a melhor forma
que penso é criar uma consulta que traz o número n de questões da 
matéria indicada no parâmetro. A partir desse valor, fazer um for each 
i de 1 a n, para buscar os dados de cada um dos n Pie Charts. passando
i como parâmetro para a segunda consulta.
*/

-- Busca número de questões
SELECT (FIM-INICIO+1) NUMERO_QUESTOES
FROM PROVAS
WHERE PROVA = :PROVA
AND PROCESSO = '20111_integrado';

-- Busca estatísticas de acertos da questão i
SELECT (P.INICIO +  :I  -1) QUESTAO, 
  'Sim' TIPO,
  ROUND(SUM(IF(
    SUBSTRING(G.GABARITO, (P.INICIO +  :I  -1), 1)=
		(SUBSTRING(C.CREL_RESPOSTAS_D1,(P.INICIO +  :I  -1),1)) OR 
(SUBSTRING(G.GABARITO, (P.INICIO +  :I  -1), 1)='#'), 1,0))/T.TOTAL*100,1) PORCENTAGEM
FROM PROVAS P, GABARITO G, CANDIDATO_RELATORIO C,
	(SELECT COUNT(1) TOTAL
	FROM CANDIDATO_RELATORIO
	WHERE CREL_PROCESSO = '20111_integrado'
	AND CREL_CLASSIFICACAO > 0) T
WHERE CREL_PROCESSO = '20111_integrado'
  AND CREL_CLASSIFICACAO > 0
AND P.PROCESSO = '20111_integrado'
AND P.PROVA =  :PROVA 
AND G.PROCESSO = '20111_integrado'
GROUP BY QUESTAO, TIPO

UNION
SELECT (P.INICIO +  :I  -1) QUESTAO, 
  'Não' TIPO,
  ROUND(SUM(IF((SUBSTRING(G.GABARITO, (P.INICIO +  :I  -1), 1)<>SUBSTRING(C.CREL_RESPOSTAS_D1,(P.INICIO +  :I  -1),1))
				AND (SUBSTRING(G.GABARITO, (P.INICIO +  :I  -1), 1)<>'#'), 1,0)				
				)/T.TOTAL*100,1) PORCENTAGEM
    FROM PROVAS P, GABARITO G, CANDIDATO_RELATORIO C,
	(SELECT COUNT(1) TOTAL
	FROM CANDIDATO_RELATORIO
	WHERE CREL_PROCESSO = '20111_integrado'
	AND CREL_CLASSIFICACAO > 0) T
WHERE CREL_PROCESSO = '20111_integrado'
  AND CREL_CLASSIFICACAO > 0
AND P.PROCESSO = '20111_integrado'
AND P.PROVA =  :PROVA 
AND G.PROCESSO = '20111_integrado'
GROUP BY QUESTAO, TIPO;

