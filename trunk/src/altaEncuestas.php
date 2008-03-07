<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 
$query="select tipo_rta, 'DESC' from tipos_respuestas_desc";
$registros = doSelect($query) or die ("Problemas en select".mysql_error());
$num_rows = mysql_num_rows($registros);
?>

<html>
<head>
<title>Untitled Document</title>
<?php 
echo "<script language=\"javascript\">";
echo "var rtas = new Array(".$num_rows.");";
$i = 0;
while ($reg=mysql_fetch_array($registros)){
	echo "rtas[".$i."] = new Array(2);";
	echo "rtas[".$i."][0]=".$reg['tipo_rta'].";";
	echo "rtas[".$i."][1]=\"".$reg['desc']."\";";
	$i = $i + 1;
}
echo "var cantRtas = ".$num_rows.";";
echo "</script>";
?>
<script language="javascript">
function loadRtas(){
	var combo = document.getElementById("respuesta1");
	appendRtas(combo);
}

function appendRtas(combo){
	for (i = 0; i < cantRtas; i++){
		var option = document.createElement("option");
		option.text = rtas[i][1];
		option.value = rtas[i][0];
		combo.appendChild(option);
	}
}
</script>
</head>

<body onLoad="loadRtas">
Bienvenido al administrador de encuastas.
<form name="frmAlta" action="/cti/src/altaEncuesta.php">
Título: <input type="text" id="titulo" name="titulo">
Pregunta 1: <input type="text" name="pregunta" id="pregunta1">
Tipo de respuesta1: <select name="respuesta" id="respuesta1"></select>
</form>

</body>
</html>
