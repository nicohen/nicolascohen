<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 

$qryIns = "insert into encuestas_realizadas (enc_id,vendedor,timestamp) values (".$_REQUEST['enc_id'].",0,sysdate())";
$resp_id = doInsertAndGetLast($qryIns);
//print_r($_REQUEST['cantPregs']);
for ($i = 1; $i <= $_REQUEST['cantPregs']; $i++){
//	print_r($i);
	$respuesta = $_REQUEST['resp'.$i];
	$qryResp = "insert into respuestas (resp_id, prg_id, respuesta) values (".$resp_id.",".$i.",".$respuesta.")";
//	die($qryResp);
//	print_r($qryResp);
	doInsert($qryResp);// or die("Error en insert ".mysql_error());
}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
La encuesta se ha enviado correctamente.
Muchas gracias por su colaboración.
</body>
</html>
