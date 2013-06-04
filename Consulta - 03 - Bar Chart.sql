/* Consulta - 03 - Bar Chart
   http://bl.ocks.org/mbostock/3885304

   Consulta o número de candidatos inscritos por curso.
   Descrição do curso está pendente.
*/

SELECT CREL_CODCURSO CURSO, COUNT(1) QTDE
FROM CANDIDATO_RELATORIO
WHERE CREL_PROCESSO = '20111_integrado'
GROUP BY CURSO
ORDER BY CURSO;
