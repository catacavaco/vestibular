<?php
	require_once("system_config.php");
	GLOBAL $_SYS;
	
function getSelectFromBD($qr){

	$RES=mysql_query($qr);
	
	while($row=mysql_fetch_array($RES)){
		$dados[$row[0]]=$row[1];
		
	}
	return $dados;
	
}

function getSelectFromBD_sel($qr){

	$RES=mysql_query($qr);
	$dados['*'] = "Selecione";
	while($row=mysql_fetch_array($RES)){
		$dados[$row[0]]=$row[1];
		
	}
	return $dados;
	
}

function getSelectFromBD_all($qr){

	$RES=mysql_query($qr);
	$dados['*'] = "Todos(as)";
	while($row=mysql_fetch_array($RES)){
		$dados[$row[0]]=$row[1];
		
	}
	return $dados;
	
}
function formFilter($processo_v,$unidade_v){
	$processo = new Field();
	$processo->name="PROCESSO";
	$processo->id="Processo";
	$processo->label="";
	$processo->type="select";
	$processo->idcss="normalEdit";
	$processo->value=$processo_v;
	$processo->mask="";
	$processo->maxlength="";
	$processo->size="";
	$processo->width="245";
	$processo->options=getSelectFromBD_sel("SELECT DISTINCT crel_processo,crel_processo FROM candidato_relatorio ORDER BY crel_processo");
	$processo->mandatory=true;
	$processo->enabled=true;
	$processo->script='onchange="this.form.submit();"';
	
	$unidade = new Field();
	$unidade->name="UNIDADE";
	$unidade->id="Unidade";
	$unidade->label="";
	$unidade->type="select";
	$unidade->idcss="normalEdit";
	$unidade->value=$unidade_v;
	$unidade->mask="";
	$unidade->maxlength="";
	$unidade->size="";
	$unidade->width="245";
	$unidade->options=getSelectFromBD_all("SELECT DISTINCT  unidade,unidade FROM candidato_relatorio INNER JOIN curso ON crel_codcurso = codcurso WHERE crel_processo='$processo_v'   ORDER BY unidade");
	$unidade->mandatory=true;
	$unidade->enabled=true;
	$unidade->script='onchange="this.form.submit();"';
	
	
	$curso = new Field();
	$curso->name="CURSO";
	$curso->id="Curso";
	$curso->label="";
	$curso->type="select";
	$curso->idcss="normalEdit";
	$curso->value=$curso_v;
	$curso->mask="";
	$curso->maxlength="";
	$curso->size="";
	$curso->width="280";
	$curso->options=getSelectFromBD_all("SELECT DISTINCT  codcurso,curso FROM candidato_relatorio INNER JOIN curso ON crel_codcurso = codcurso WHERE crel_processo='$processo_v' AND unidade='$unidade_v'   ORDER BY curso");
	$curso->mandatory=true;
	$curso->enabled=true;

	
	$isento = new Field();
	$isento->name="ISENTO";
	$isento->id="Isento";
	$isento->label="";
	$isento->type="select";
	$isento->idcss="normalEdit";
	$isento->value=$isento_v;
	$isento->mask="";
	$isento->maxlength="";
	$isento->size="";
	$isento->width="194";
	$isento->options=getSelectFromBD_all("SELECT DISTINCT  crel_isento,sn_descricao FROM candidato_relatorio INNER JOIN alt_sn ON sn_letra = crel_isento ORDER BY sn_descricao");
	$isento->mandatory=true;
	$isento->enabled=true;
	
	if($procedencia_v=='') $procedencia_v='*';
	$procedencia = new Field();
	$procedencia->name="PROCEDENCIA";
	$procedencia->id="Procedência";
	$procedencia->label="";
	$procedencia->type="select";
	$procedencia->idcss="normalEdit";
	$procedencia->value=$procedencia_v;
	$procedencia->mask="";
	$procedencia->maxlength="";
	$procedencia->size="";
	$procedencia->width="191";
	$procedencia->options=getSelectFromBD_all("SELECT DISTINCT  crel_procedencia,proc_descricao FROM candidato_relatorio INNER JOIN alt_procedencia ON crel_procedencia = proc_letra ORDER BY proc_descricao");
	$procedencia->mandatory=true;
	$procedencia->enabled=true;
	
	if($prevestibular_v=='') $prevestibular_v='*';
	$prevestibular = new Field();
	$prevestibular->name="PREVESTIBULAR";
	$prevestibular->id="Pré-Vestibular";
	$prevestibular->label="";
	$prevestibular->type="select";
	$prevestibular->idcss="normalEdit";
	$prevestibular->value=$prevestibular_v;
	$prevestibular->mask="";
	$prevestibular->maxlength="";
	$prevestibular->size="";
	$prevestibular->width="191";
	$prevestibular->options=getSelectFromBD_all("SELECT DISTINCT  crel_pre_vestibular,sn_descricao FROM candidato_relatorio INNER JOIN alt_sn ON sn_letra = crel_pre_vestibular ORDER BY sn_descricao");
	$prevestibular->mandatory=true;
	$prevestibular->enabled=true;

	$situacao = new Field();
	$situacao->name="SITUACAO";
	$situacao->id="Situação";
	$situacao->label="";
	$situacao->type="select";
	$situacao->idcss="normalEdit";
	$situacao->value=$situacao_v;
	$situacao->mask="";
	$situacao->maxlength="";
	$situacao->size="";
	$situacao->width="194";
	$situacao->options=getSelectFromBD_all("SELECT DISTINCT  crel_sit_vestibular,sit_descricao FROM candidato_relatorio INNER JOIN alt_situacao ON sit_letra = crel_sit_vestibular ORDER BY sit_descricao");
	$situacao->mandatory=true;
	$situacao->enabled=true;
	
	if($renda_v=='') $renda_v='*';
	$renda = new Field();
	$renda->name="RENDA";
	$renda->id="Renda";
	$renda->label="";
	$renda->type="select";
	$renda->idcss="normalEdit";
	$renda->value=$renda_v;
	$renda->mask="";
	$renda->maxlength="";
	$renda->size="";
	$renda->width="385";
	$renda->options=getSelectFromBD_all("SELECT DISTINCT  crel_renda,ren_descricao FROM candidato_relatorio INNER JOIN alt_renda ON ren_letra = crel_renda ORDER BY ren_letra");
	$renda->mandatory=true;
	$renda->enabled=true;
	
	if($etnia_v=='') $etnia_v='*';
	$etnia = new Field();
	$etnia->name="ETNIA";
	$etnia->id="Cor / Etnia";
	$etnia->label="";
	$etnia->type="select";
	$etnia->idcss="normalEdit";
	$etnia->value=$etnia_v;
	$etnia->mask="";
	$etnia->maxlength="";
	$etnia->size="";
	$etnia->width="385";
	$etnia->options=getSelectFromBD_all("SELECT DISTINCT  crel_renda,etn_descricao FROM candidato_relatorio INNER JOIN alt_etnia ON etn_letra = crel_renda ORDER BY etn_descricao");
	$etnia->mandatory=true;
	$etnia->enabled=true;
	
	
	$form = new Form();
	$form->image = "images/icon_archive.jpg";
	$form->title="Filtros";
	$form->label="Selecione os filtros para gerar a visualização:";
	
	$form->addField($processo);
	$form->addField($unidade);
	$form->addField($curso);
	$form->addField($isento);
	$form->addField($procedencia);
	$form->addField($prevestibular);
	$form->addField($situacao);
	$form->addField($renda);
	$form->addField($etnia);
	
	$form->printForm();
	
}

function diaspagamento($processo){
	$qr="SELECT DATE_FORMAT( crel_datainscricao, '%Y-%m-%d' ) AS
DATA FROM `candidato_relatorio`
WHERE crel_processo = '$processo' 
UNION SELECT DATE_FORMAT( crel_datapagamento, '%Y-%m-%d' ) AS
DATA FROM `candidato_relatorio`
WHERE crel_processo = '$processo' 
ORDER BY DATA";

	$RES = mysql_query($qr);
	$i=1;
	while($row = mysql_fetch_array($RES)){
		$dado[0]=$i;
	
		if($dado[0]<10) $dado[0]='0'.$dado[0];
		$dataat = $row[0];
		$qr = "SELECT count(*) FROM candidato_relatorio WHERE crel_processo = '$processo' AND crel_datainscricao<= '$dataat 23:59:59'";
		$RES2 = mysql_fetch_array(mysql_query($qr));
		$inscritos = $RES2[0];
		$qr = "SELECT count(*) FROM candidato_relatorio WHERE crel_processo = '$processo' AND crel_datapagamento<= '$dataat 23:59:59'";
		$RES2 = mysql_fetch_array(mysql_query($qr));
		$pagos = $RES2[0];
		
		$dado[1]=$pagos;
		$dado[2]=$inscritos-$pagos;
		$dados[$i]=$dado;
		
		if($dataat=='0000-00-00') $i--;
		$i++;
		
		
		
	}
	$i=$i--;
	$dados[$i][1] = $dados[$i][1] + $dados[$i][2];
	$dados[$i][2] =0;
	return $dados;
}
function horainscricao($processo){
	for($i=23;$i>=0;$i--){
		$hora =$i;
		if($hora<10) $hora = '0'.$hora;
		
		$qr="SELECT count(*) FROM candidato_relatorio WHERE crel_processo = '$processo'  AND  DATE_FORMAT(crel_datainscricao,'%H') = '$hora'"; 
		
		$RES2 = mysql_fetch_array(mysql_query($qr));
		$num_hora = $RES2[0]+0;
		
		$dados[$i] = $num_hora;
		
	}
	
	return $dados;
}
	
?>