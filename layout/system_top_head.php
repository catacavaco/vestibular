<?php
	require_once("system_config.php");
	GLOBAL $_SYS;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>


	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title><? echo $_SYS['lyt']['menu_title']; ?></title>
	
	
<script src="http://d3js.org/d3.v3.min.js"></script>	

<style type="text/css">
*{
	margin:0;
	padding:0;
}
body{
	background-color:<? echo $_SYS['lyt']['body_background_color'];?>;
	color:#333333;
	
	 font: 10px sans-serif;
	font-size: 100%;
	text-align:center;
}




#geral {
    margin: 0px auto;
	text-align: left;
    width: 770px;	
	
}
#logo {
	background-color: <? echo $_SYS['lyt']['content_background_color'];?>; 
	color: <? echo $_SYS['lyt']['content_color_font'];?>;
	margin: 0px auto;
	padding-left: 15px;
	padding-right: 15px;
	width: 770px;

	 
}

#content {
	background-color: <? echo $_SYS['lyt']['content_background_color'];?>; 
	color: <? echo $_SYS['lyt']['content_color_font'];?>;
	margin: 0px auto;
	padding-left: 0px;
	padding-right: 0px;
	width: 770px;
	
	 
}
#filterstrip{
		
		background-color: #000040;
		width: 100%;
		height: 368px;
		margin-right: 15px;
		text-align: right;
		 font: 10px sans-serif;
		color: #FFFFFF;
		
		font-size: 12px;
	
}

#filterstrip a{
		
		background-color: #000040;
		width: 100%;
		height: 368px;
		margin-right: 15px;
		text-align: right;
		 font: 10px sans-serif;
		color: #FFFFFF;
		
	
	
}
#footer { 

	background-color: #FFFFFF;
	background-image: url(images/footer.png);
	background-position: top right;
	background-repeat: no-repeat;
	color: <? echo $_SYS['lyt']['footer_color'];?>;
	 font: 10px sans-serif;
	height: 60px; 
	padding-left: 12px; 
	padding-top: 17px;
	text-align: left; 
	

}
#footer a {

	text-decoration: underline;
}
#footer a:hover { 
	text-decoration: none; 
}

.topmenu{
	background-image: url(images/logo2.png); height: 77px;
	background-repeat: no-repeat;
	color: #0064A2;
	float: left; 
	font:12px Arial, Helvetica, sans-serif; 
	height: 325px;
	padding-top: 25px;
	width: 100%;
} 

.topmenu ul{
	float: left;
	margin: 0;
	padding: 0;
}

.topmenu ul li{ 
	display: inline;
}

.topmenu ul li a{
	background-color: <? echo $_SYS['lyt']['menu_top_color'];?>;
	color: <? echo $_SYS['lyt']['menu_top_color_font'];?>;
	float: left; 
	margin: 1px;
	padding: 8px 11px; 
	text-decoration: none;
}

.topmenu ul li a:hover, .topmenu ul li .current{
	background-color:<? echo $_SYS['lyt']['menu_top_color_a'];?>;
	color:<? echo $_SYS['lyt']['menu_top_color_font_a'];?>;	
}
	
#titleStyle {
	color:<? echo $_SYS['lyt']['title_color'];?>;
	font: 110% "arial black", Helvetica, sans-serif, monospace;
	font-size: 24px;
	padding-left:10px;
}
#titleForm {
	border:1px dashed #000080;
	float: left; 
	padding:10px;
	margin: 2px;
	width: 745px;
	text-align: left;
}

#borderfield{
	border:1px dashed #3399FF;
	float: left; 
	padding:10px;
	margin: 2px;
	text-align: left;
	min-height:75px;
}
#labelfield {
	font-weight: bold;
	width: 140px;
	font-size: 12px;
	padding-right: 20px;
}
#labelfieldSubject {
	width: 140px;
	font-size: 11px;
	padding-right: 15px;
}
#containerTable{
	display: table;
}

#normalButton{
	font-family: "frutiger linotype","lucida grande",helvetica,arial,sans-serif];
	color: #FFFFFF; 
	font-size: 12px; 
	border: 1px solid #3399FF; 
	background-color: #66CCFF;
	padding: 2px;
}

#normalEdit {
	color: #000000; 
	border: 1px dotted #000000; 
	padding-left: 3px; 
	padding-right: 3px; 
	padding-top: 3px; 
	padding-bottom: 3px; 
	background-color: #EEEEEE;
}

#mensagemError{
   font-size: 11px;
   border:1px dashed #FF0000;
   color:#BF0000;
   background-color: #FF8080;
   padding: 3px 3px 3px 3px;
   font-weight:bold;
   clear: none;
   float: none;
}

#fieldError{

	color: #000000; 
	border: 1px dotted #FF0000; 
	padding-left: 3px; 
	padding-right: 3px; 
	padding-top: 3px; 
	padding-bottom: 3px; 
	background-color: #FF8080;
	
	
   
}

	
</style>


<script>

function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}



function soNumeros(v){
    return v.replace(/\D/g,"")
}

function telefone(v){
    v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
    v=v.replace(/^(\d\d)(\d)/g,"($1)$2") //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d{4})(\d)/,"$1$2")    //Coloca hífen entre o quarto e o quinto dígitos
    return v
}

function cpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
                                             //de novo (para o segundo bloco de números)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
    return v
}

function cep(v){
    v=v.replace(/D/g,"")                //Remove tudo o que não é dígito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2") //Esse é tão fácil que não merece explicações
    return v
}



function data(v){
  	v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    
    return v

}
function intervalo(v,interv){
	var result = "";
	var i=0;
	var j=0;
	for (i=0;i<v.length;i++) {
		for (j=0;j<interv.length;j++) {
			if(v.charAt(i)==interv.charAt(j)){
				result += v.charAt(i);
			}
		}

	}

	return result;
}
function contamask(v){

    var str = intervalo(v,"0123456789xX");

	var i=0;
	var result = ""
	
	
	for (i=0;i<str.length-1;i++) {
		result += str.charAt(i)

	}
	
		result += "-"

	result += str.charAt(str.length - 1);
	v = result;
    return v;
}
</script>




</head>

<body>



<div id="geral">

		  


    <div class="topmenu" >
		<ul>
		<? require("system_top_menu.php"); ?>
		</ul>
    </div>		
	
	<div id="logo">
	</div>
	
	<div id="content">
		<div id="filterstrip">
		<a href="index.php">Filtros</a>&nbsp;&nbsp;&nbsp;
		</div>
