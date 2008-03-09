<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 
$query="select * from tipos_respuestas_desc";
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
	echo "rtas[".$i."][0]=".$reg[0].";";
	echo "rtas[".$i."][1]=\"".$reg[1]."\";";
	$i = $i + 1;
}
echo "var cantRtas = ".$num_rows.";";
echo "</script>";
?>
<script language="javascript">
var cantPregs = 1;
function loadRtas(){
	var combo = document.getElementById("respuesta1");
	appendRtas(combo);
}

function appendRtas(combo){
	for (i = 0; i < cantRtas; i++){
		combo.options[i] = new Option(rtas[i][1],rtas[i][0]);
	}
}

function addRow(){
	++cantPregs;
	var table = document.getElementById("tblGral");
	var row = document.createElement("TR");
	var col1 = document.createElement("TD");
	var text = document.createTextNode("Pregunta "+cantPregs+": ");
	var input = document.createElement("INPUT");
	input.type = "text";
	input.name = "pregunta" + cantPregs;
	input.id = "pregunta" + cantPregs;
	input.size = "100";
	col1.appendChild(text); 
	col1.appendChild(input);
	var col2 = document.createElement("TD");
	col2.innerHtml = "Tipo de respuesta "+cantPregs+": ";
	var combito = document.createElement("SELECT");
	combito.name = "respuesta" + cantPregs;
	combito.id = "respuesta" + cantPregs;
	appendRtas(combito);
	col2.appendChild(combito);
	var col3 = document.createElement("TD");
	row.appendChild(col1);
	row.appendChild(col2);
	row.appendChild(col3);
	table.appendChild(row);
	document.getElementById("cantidad").value = cantPregs;
}
</script>
</head>

<body>
Bienvenido al administrador de encuastas. <br>
<form name="frmAlta" action="/cti/src/encuestas.php" method="post">
<input type="hidden" name="act" id="act" value="<?php echo SAVE_ENC ?>">
<input type="hidden" name="cantidad" id="cantidad" value="0">
Título: <input type="text" id="titulo" name="titulo"><br>
<table width="100%"  border="0" id="tblGral" cellspacing="0" cellpadding="0">
  <tr>
    <td width="75%">Pregunta 1: <input type="text" name="pregunta1" size="100" id="pregunta1"></td>
    <td width="16%">Tipo de respuesta1: <select name="respuesta1" id="respuesta1"></select></td>
	<td width="9%"><a href="javascript:addRow();">Agregar pregunta</a> </td>
  </tr>
</table>
<table width="100%">
	<tr>
		<td align="center"> <input type="submit" value="Guardar"></td>
	</tr>
</table>
</form>
<script language="javascript">
	loadRtas();
</script>
</body>
</html>
