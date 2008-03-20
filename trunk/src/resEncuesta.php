<?php 

function mostrarValor($texto, $porc){
	//echo $firstValor?"true":"false";	 
	echo "<b>".$texto."</b> <font style=\"font-style:italic\">".$porc."</font>% "."<br> &nbsp;&nbsp;&nbsp"; 
	$firstValor = false;
}

function completeValores($valores, $resValores){
	if ($resValores != NULL){
			while ($valores = mysql_fetch_array($resValores)){
				mostrarValor($valores['respuesta'],0);		
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
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99"><td align="center">
Resumen de la encuesta: <?php echo getEncTitle() ?> 
</td></tr>
<tr><td>
<?php 
$where = " AND e.enc_id =".$_REQUEST['enc_id'];
if ($_REQUEST['vend_id'] != NULL && $_REQUEST['vend_id'] != '' && $_REQUEST['vend_id'] != 0){
	$where = $where." AND vend_id = ".$_REQUEST['vend_id'];
}

if ($_REQUEST['dia'] != NULL && $_REQUEST['dia'] != ''){
	$where = $where." AND date(timestamp) = '".$_REQUEST['dia']."'";
}

$qryCount = "select count(*) as 'cantidad' FROM encuestas_realizadas e where 1 = 1 ".$where;
//print_r($qryCount);
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
$firstValor = false;
while ($rta = mysql_fetch_array($resRtas)){
	if ($rta['prg_id'] != $lastPreg){
		//Completo el resto
		completeValores($valores,$resValores);
	
		$firstValor = true;
		$qryPreg = "select pregunta, tipo_rta from preguntas where prg_id = ".$rta['prg_id']." and enc_id = ".$_REQUEST['enc_id'];
		$resPreg = doSelect($qryPreg);
		$preg = mysql_fetch_array($resPreg);
		$lastPreg = $rta['prg_id']; 
		if ($lastPreg > 1) echo "<br>";
		echo $lastPreg.": ".$preg['pregunta'];
		echo "<br> &nbsp;&nbsp;&nbsp;";
		
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
		echo mostrarValor($valores['respuesta'],0);		
	}
	echo mostrarValor($valores['respuesta'],round(($rta['cantidad'] / $cantRtas * 100),2));	
}

completeValores($valores,$resValores);

?>
</td></tr>
</table>
<table width="100%">
<tr>
	<td height="10"></td>
</tr>
<tr>
	<td align="center"><input type="button" value="Volver" onClick="javascript:history.back();"></td>
</tr>
</table>
