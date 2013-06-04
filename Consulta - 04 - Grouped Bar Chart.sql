/* Consulta - 04 - Grouped Bar Chart
   http://bl.ocks.org/mbostock/3887051

   Consulta o total de vagas e o número de candidatos
   aprovados para cada curso.
   Descrição e número de vagas por curso estão pendentes.
*/
SELECT CREL_CODCURSO CURSO, 
	   40 TOTAL_VAGAS,
	   MAX(CREL_CLASSIFICACAO) CLASSIFICACAO	   
FROM CANDIDATO_RELATORIO
WHERE CREL_CHAMADA >=1
GROUP BY CURSO, TOTAL_VAGAS
ORDER BY CURSO;
