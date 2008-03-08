<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 

?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
if ($_REQUEST['act'] == SAVE_ENC){
	$qryInsEnc = "insert into encuestas (titulo) values ('".$_REQUEST['titulo']."')";
	$encID = doInsertAndGetLast($qryInsEnc);
	echo($_REQUEST['cantidad']);
	for ($i = 1; $i <= $_REQUEST['cantidad']; $i++){
		$preg = $_REQUEST['pregunta'.$i];
		$rta = $_REQUEST['respuesta'.$i];
		$qryInsPreg = "insert into preguntas (enc_id, tipo_rta, pregunta) values (".$encID.",".$rta.",'".$preg."')";
		doInsert($qryInsPreg);
	}
	echo ("<b>La encuesta se ha guardado correctamente</b><br>");
}
?>
<table>
<tr>
	<td> Título </td>
	<td> Acciones </td>
</tr>
<?php 
	$query = "select enc_id,titulo from encuestas where status = 'A'";
	$registros = doSelect($query) or die ("Problemas en select".mysql_error());
	while ($reg = mysql_fetch_array($registros)){
		echo "<tr>";
		//echo "ID: ".$reg['enc_id']."<br>";
		echo "<td>".$reg['titulo']."</td>";
		echo "<td><a href=/cti/src/responderEncuesta.php?enc_id=".$reg['enc_id']."> Responder </a></td>";
		echo "</tr>";
	}
?>
</table>
</body>
</html>
