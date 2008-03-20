<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 
require("funciones.php");

$qryIns = "insert into encuestas_realizadas (enc_id,vendedor) values (".$_REQUEST['enc_id'].",".$_COOKIE['user_active'].")";
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
<?php do_header("Gracias por responder la encuesta"); ?>
</head>

<body>
<table width="70%">
<tr><td align="center" valign="top">
La encuesta se ha enviado correctamente. <br>
Muchas gracias por su colaboración. <br>
<input type="button" value="Cerrar" onClick="javascript:window.close();">
</td></tr>
</table>
</body>
</html>
