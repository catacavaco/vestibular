<?php
	function db_connect(){
		$link=mysql_connect("localhost","root","");
		if($link && mysql_select_db("vestibular")){
			mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');

			return($link);
		}
		return(FALSE);
	}
	db_connect()
		or die ("Erro na conexão ao servidor!");
function utf8($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
} 
?>