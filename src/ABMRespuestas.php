<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 
require("funcionesEncuestas.php");

$act = $_REQUEST['act'];
if ($act == SAVE_RTA){
	$insRtaType = "insert into tipos_respuestas_desc values (NULL, '".$_REQUEST['rtaDesc']."')";
	$tipo_rta = doInsertAndGetLast($insRtaType);
	for ($i = 1; $i <= $_REQUEST['cantValues']; $i++){
		$insValue = "insert into tipos_respuestas (tipo_rta, value, respuesta) values (".$tipo_rta.",".$i.",'".$_REQUEST['valor'.$i]."')";
		doInsert($insValue);
	}
} else if ($act == DEL_RTA){
	$tipo_rta = $_REQUEST['tipo_rta'];
	$delRta = "delete from tipos_respuestas where tipo_rta = ".$tipo_rta;
	$delRtaType = "delete from tipos_respuestas_desc where tipo_rta = ".$tipo_rta;
	doInsert($delRta);
	doInsert($delRtaType);
}
?>
<html>
<head>
<title>Untitled Document</title>
</head>

<body>
<script language="javascript">
var cant = 1;
function agregarOpcion(){
	cant++;
	var tabla = document.getElementById("tabla");
	var row = document.createElement("TR");
	var col = document.createElement("TD");
	col.colspan = "2";
	var input  = document.createElement("INPUT");
	input.name = "valor" + cant;
	input.id = "valor" + cant;
	col.appendChild(input);
	row.appendChild(col);
	tabla.appendChild(row);
	
	document.getElementById("cantValues").value = cant;	
}
</script>
<?php
	$qry = "select * from tipos_respuestas_desc";
	$result = doSelect($qry);
	while ($tipos = mysql_fetch_array($result)){
		echo $tipos[1];
		if (mostrarBorrar($tipos[0])){
			echo "<a href=\"/cti/src/ABMRespuestas.php?act=".DEL_RTA."&tipo_rta=".$tipos[0]."\"> Borrar </a>";
		}
		echo "<br>";
	} 
?>
<form name="frmMain" action="/cti/src/ABMRespuestas.php" method="post">
<input type="hidden" name="cantValues" id="cantValues" value="1">
<input type="hidden" name="act" id="act" value="<?php echo SAVE_RTA ?>">
<input type="button" value="Agregar nuevo" onClick="document.getElementById('divNuevo').style.display=''">
<div id="divNuevo" style="display:none">
	<table id="tabla">
		<tr>
			<td> Ingrese un nombre genérico del tipo de respuesta: </td>
			<td> <input type="text" name="rtaDesc" id="rtaDesc" value=""> </td>
		</tr>
		<tr>
			<td> Ingrese las respuestas posibles ordenadas de mayor a menor en importancia</td>
			<td> <a href="javascript:agregarOpcion();"> Agregar opción </a> </td>
		</tr>
		<tr>
			<td colspan="2"> <input type="text" name="valor1" value=""> </td>
		</tr>
	</table>
	<table>
		<tr>
			<td align="center"> <input type="submit" value="Grabar"> </td>
		</tr>
	</table>
</div>
</form>
</body>
</html>
