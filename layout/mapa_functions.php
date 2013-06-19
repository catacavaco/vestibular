<?php
function getDataArray($regiao) {
	$qr = "SELECT id$regiao, count(*)
			FROM `candidato_relatorio`
			WHERE crel_processo = '20111_integrado'
			AND id$regiao <> \"\"
			GROUP BY id$regiao
			order by id$regiao asc;";

	$RES = mysql_query($qr) or die(mysql_error());

	$i = 0;
	while ($row = mysql_fetch_array($RES)) {
		$mesos[$i] = $row['id' . $regiao . ''];
		$mesoat = $mesos[$i];
		$quantidade[$i] = $row[1];

		$qr2 = "SELECT avg(crel_notafinal) FROM candidato_relatorio WHERE id$regiao='$mesoat' AND crel_processo = '20111_integrado'";
		$RES2 = mysql_query($qr2) or die(mysql_error());
		$row2 = mysql_fetch_array($RES2);
		$media[$i] = $row2[0];

		$qr3 = "SELECT count(*) FROM candidato_relatorio WHERE id$regiao='$mesoat' AND crel_processo = '20111_integrado' AND crel_sit_vestibular = 'C' ";
		$RES3 = mysql_query($qr3) or die(mysql_error());
		$row3 = mysql_fetch_array($RES3);
		$candidatos_aprovados[$i] = $row3[0];

		$i++;
	}
	echo '<script>';
	echo 'var ' . $regiao . 'Array = [';

	for ($i = 0; $i < count($mesos); $i++) {
		if ($i != 0)
			echo ",\n\t\t\t\t";
		echo '{"id": '.$mesos[$i].',"candidatos": '.$quantidade[$i].', "candidatos_aprovados": '.$candidatos_aprovados[$i].', "media_prova": '.$media[$i].'}';

	}
	echo " ];\n";
	echo "\t</script>";
}
?>

