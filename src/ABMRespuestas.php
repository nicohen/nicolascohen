<?php 
//Funciones para operar con DB y otros
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
<script language="javascript">
var cant = 1;
function agregarOpcion(){
	cant++;
	var tabla = document.getElementById("tabla");
	var row = document.createElement("TR");
	var col = document.createElement("TD");
	col.colspan = "2";
	var text = document.createTextNode("Opción " + cant + ": ");
	var input  = document.createElement("INPUT");
	input.name = "valor" + cant;
	input.id = "valor" + cant;
	input.className="input";
	col.appendChild(text);
	col.appendChild(input);
	row.appendChild(col);
	tabla.appendChild(row);
	
	document.getElementById("cantValues").value = cant;	
}
</script>
<link href="styles/styles.css" rel="stylesheet" type="text/css">
<table width="100%" align="center">
	<tr><td align="center" class="txt07"> Bienvenido al administrador de Respuestas </td> </tr>
</table>
<table width="300" align="center">
	<tr> <td align="left"> Respuestas actuales: </td></tr>
<?php
	$qry = "select * from tipos_respuestas_desc";
	$result = doSelect($qry);
	while ($tipos = mysql_fetch_array($result)){
?>
		<tr> <td>  &nbsp;&nbsp; <img src="http://www.mercadolibre.com.ar/org-img/homed/bul_v2.gif">
<?php
		echo $tipos[1];
		if (mostrarBorrar($tipos[0])){
			echo "<a href=\"/cti/src/ABMRespuestas.php?act=".DEL_RTA."&tipo_rta=".$tipos[0]."\"> Borrar </a>";
		}
		echo "<br>";
?>
	</td> </tr>
<?php	
	} 
?>
</table>
<form name="frmMain" action="/cti/src/index.php?lbl=<?php echo MENU_RESPUESTAS?>" method="post">
<input type="hidden" name="cantValues" id="cantValues" value="1">
<input type="hidden" name="act" id="act" value="<?php echo SAVE_RTA ?>">
<table width="100%"><tr><td align="center"> <input type="button" class="boton" value="Agregar nuevo" onClick="document.getElementById('divNuevo').style.display=''"> </td></tr></table>
<div id="divNuevo" style="display:none" align="center">
	<table id="tabla" width="400" align="center">
		<tr>
			<td> Ingrese un nombre genérico del tipo de respuesta: </td>
			<td> <input type="text" class="input" name="rtaDesc" id="rtaDesc" value=""> </td>
		</tr>
		<tr>
			<td> Ingrese las respuestas posibles ordenadas de mayor a menor en importancia</td>
			<td> <a href="javascript:agregarOpcion();"> Agregar opción </a> </td>
		</tr>
		<tr>
			<td colspan="2"> Opción 1: <input type="text" class="input" name="valor1" value=""> </td>
		</tr>
	</table>
	<table>
		<tr>
			<td align="center"> <input type="submit" class="boton" value="Grabar"> </td>
		</tr>
	</table>
</div>
</form>

