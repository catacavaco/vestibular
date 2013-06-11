<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php 
include("Encoding.php");
$fileContent = file_get_contents("geojson/bairrobh.geojson");
$utf8_string = Encoding::fixUTF8($fileContent);
echo $utf8_string; 
 ?> 
 </body>
</html>