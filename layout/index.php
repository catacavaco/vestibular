<?php
	set_time_limit(0);

	require_once("system_config.php");
	GLOBAL $_SYS;
	
	require_once("system_top_head.php");
	
	
	
	if(array_key_exists("B1",$_POST)&&$_POST['PROCESSO']!='*'){
		$dados=diaspagamento($_POST['PROCESSO']);

		$grafico = new stackChart();
		$grafico->data = $dados;
		$grafico->printStackChart();
		
		$dados=horainscricao($_POST['PROCESSO']);
		
		
		$radar = new radar1();
		$radar->data = $dados;
		$radar->printStackChart();
		
	}else{
		echo"<div id='formfilter'>";
	
		echo "<form action='index.php' method='POST'>";
		formFilter($_POST['PROCESSO'],$_POST['UNIDADE']);
	
		echo "</form>";
		echo "</div>";
	}
	require_once("system_footer.php");

?>
