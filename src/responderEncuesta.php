<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php");
require("funciones.php");
$queryEnc = "select titulo from encuestas where enc_id = ".$_REQUEST['enc_id'];
$reg = doSelect($queryEnc) or die("Error en select ".mysql_error());
$encResult = mysql_fetch_array($reg);
$title = $encResult['titulo'];
?>
<html>
<head>
<?php do_header("Responder encuesta: ".$title ); ?>
<script language="javascript">
function submitear(){
	for (i = 1; i <= document.getElementById("cantPregs").value ; i++){
		var radio = eval("document.frmQues.resp" + i);
		selected = false;
		for (j = 0 ; j < radio.length; j++){
			if (radio[j].checked){
				selected = true;
				break;
			}
		}
		if (!selected){
			alert("Debe responder todas las preguntas");
			return;
		}
	}
	
	document.frmQues.submit();
	
}
</script>
</head>

<body>
<br>
<form name="frmQues" action="finalizaEncuesta.php" method="post">
<input type="hidden" name="enc_id" value="<?php echo $_REQUEST['enc_id'] ?>">
<table align="center" border="1" cellpadding="3" cellspacing="0" width="700" style="border-collapse:collapse;border-color:gray">
<tr bgcolor="#FFCC99">
<td align="center"><h2><?php echo $title ?></h2></td>
</tr>
<tr><td><br>
<?php 
	$qrySelectPreg = "Select prg_id, tipo_rta, pregunta from preguntas where enc_id = ".$_REQUEST['enc_id'];
	$result = doSelect($qrySelectPreg) or die("Error en select ".mysql_error());
	$j = 1;
	while ($preg = mysql_fetch_array($result)){
		$preguntas[$j++] = $preg;	
	}
		
	for ($i = 1; $i < $j; $i ++){
		echo "<i>Pregunta ".$i.": </i><b>".$preguntas[$i]['pregunta']."</b><br>";
		//echo "tipo rta ".$preguntas[$i]['tipo_rta'];
		$tipoRta = $preguntas[$i]['tipo_rta'];
		$qryResp = "select value, respuesta from tipos_respuestas where tipo_rta = ".$tipoRta;
		$resResp = doSelect($qryResp) or die("Error en select ".mysql_error());
		
		//Armado de la tabla
		?>
		<table><tr>
		<?php
		while ($resp = mysql_fetch_array($resResp)){
			//echo $resp['value']."=>".$resp['respuesta'];
			echo "<td><h3><input type=\"radio\" name=\"resp".$i."\" value=\"".$resp['value']."\"> ".$resp['respuesta']."</h3></td>";
		}
		?>
		</tr></table>
		<?php	
		echo "</td></tr>";
		if ($i != $j - 1){
			echo "<tr><td><br>";
		}
	}
?>
</td></tr>
<tr><td align="center">
<input type="hidden" name="cantPregs" id="cantPregs" value="<?php echo ($j-1) ?>">
<input type="button" onClick="javascript:submitear();" value="Terminar">
</td></tr>
</table>
</form>
</body>
</html>
