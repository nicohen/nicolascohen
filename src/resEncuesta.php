<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php");

function completeValores($valores, $resValores){
	if ($resValores != NULL){
			while ($valores = mysql_fetch_array($resValores)){
				echo $valores['respuesta']." 0%";		
			}
	}
}

function getEncTitle(){
	$qryTitle = "select titulo from encuestas where enc_id=".$_REQUEST['enc_id'];
	$resTitle = doSelect($qryTitle);
	$resTitle = mysql_fetch_array($resTitle);
	return $resTitle[0];
}
?>
<html>
<head>
<title>Untitled Document</title>
</head>

<body>
Resumen de la encuesta: <?php echo getEncTitle() ?>

<?php 
$where = " AND e.enc_id =".$_REQUEST['enc_id'];
if ($_REQUEST['vend_id'] != NULL && $_REQUEST['vend_id'] != ''){
	$where = $where." AND vend_id = ".$_REQUEST['vend_id'];
}

if ($_REQUEST['dia'] != NULL && $_REQUEST['dia'] != ''){
	$where = $where." AND timestamp = '".$_REQUEST['dia']."'";
}

$qryCount = "select count(*) as 'cantidad' FROM encuestas_realizadas e where 1 = 1 ".$where;
print_r($qryCount);
$res = doSelect($qryCount) or die ("Error en select 1".mysql_error());			
$resCount = mysql_fetch_array($res);
$cantRtas = $resCount['cantidad'];

$qryRtas = "select r.prg_id, r.respuesta, count(*) as 'cantidad' from respuestas r, encuestas_realizadas e 
			WHERE r.resp_id = e.resp_id".$where;
$qryRtas = $qryRtas." group by r.prg_id, r.respuesta order by r.prg_id, r.respuesta";

$lastPreg = 0;
$resRtas = doSelect($qryRtas) or die ("Error en select 2".mysql_error());

$resValores = NULL;
$preg = NULL;
while ($rta = mysql_fetch_array($resRtas)){
	if ($rta['prg_id'] != $lastPreg){
		//Completo el resto
		completeValores($valores,$resValores);
	
	
		$qryPreg = "select pregunta, tipo_rta from preguntas where prg_id = ".$rta['prg_id']." and enc_id = ".$_REQUEST['enc_id'];
		$resPreg = doSelect($qryPreg);
		$preg = mysql_fetch_array($resPreg);
		$lastPreg = $rta['prg_id']; 
		echo "<br>";
		echo $preg['pregunta'];
		echo "<br>";
		
		$qryInfo = "select value, respuesta from tipos_respuestas where tipo_rta = ".$preg['tipo_rta']." order by value";
		//print_r($qryInfo);
		$resValores = doSelect($qryInfo) or die ("Error en select ".mysql_error());
	}	

	while ($valores = mysql_fetch_array($resValores)){
		//echo ($valores['value']);
		//echo ($rta['respuesta']);
		if ($valores['value'] >= $rta['respuesta']) {
			break;
		}
		echo $valores['respuesta']." 0%";		
	}
	echo $valores['respuesta']." ".($rta['cantidad'] / $cantRtas * 100)."%";	
}

completeValores($valores,$resValores);

?>
</body>
</html>
