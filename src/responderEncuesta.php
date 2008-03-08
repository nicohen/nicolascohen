<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php");
$queryEnc = "select titulo from encuestas where enc_id = ".$_REQUEST['enc_id'];
$reg = doSelect($queryEnc) or die("Error en select ".mysql_error());
$encResult = mysql_fetch_array($reg);
$title = $encResult['titulo'];
?>
<html>
<head>
<title>Responder encuesta</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
Título: <?php echo $title ?><br>
<form name="frmQues" action="/cti/src/finalizaEncuesta.php" method="post">
<input type="hidden" name="enc_id" value="<?php echo $_REQUEST['enc_id'] ?>">
<?php 
	$qrySelectPreg = "Select prg_id, tipo_rta, pregunta from preguntas where enc_id = ".$_REQUEST['enc_id'];
	$result = doSelect($qrySelectPreg) or die("Error en select ".mysql_error());
	$j = 1;
	while ($preg = mysql_fetch_array($result)){
		$preguntas[$j++] = $preg;	
	}
		
	for ($i = 1; $i < $j; $i ++){
		echo "Pregunta ".$i.": ".$preguntas[$i]['pregunta']."<br>";
		//echo "tipo rta ".$preguntas[$i]['tipo_rta'];
		$tipoRta = $preguntas[$i]['tipo_rta'];
		$qryResp = "select value, respuesta from tipos_respuestas where tipo_rta = ".$tipoRta;
		$resResp = doSelect($qryResp) or die("Error en select ".mysql_error());
		while ($resp = mysql_fetch_array($resResp)){
			//echo $resp['value']."=>".$resp['respuesta'];
			echo "<input type=\"radio\" name=\"resp".$i."\" value=\"".$resp['value']."\"> ".$resp['respuesta'];
		}	
		echo "<br>";	
	}
?>
</form>
</body>
</html>
