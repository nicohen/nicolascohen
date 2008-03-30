<?php
define('MAX_COLS',4);
define('MIN_COLS',1);

Function getColWidth() {
	return (100/MAX_COLS);
}

/*
if (isset($_REQUEST['pos']))
  $inicio=$_REQUEST['pos'];
else
  $inicio=0;
*/
?>

<?php

//Parsear correctamente el request (para las opciones multiples)
$attrQuery = "SELECT distinct atr_id from atributos where filter=1 and STATUS = 'A'";
$attrResult = doSelect($attrQuery);
$firstAttr=true;
while ($attrRes = mysql_fetch_array($attrResult)) {
	if ($_REQUEST["atr".$attrRes['atr_id']]!='') {
		if ($firstAttr==true) {
			$postAttrs = $attrRes['atr_id'];
			$firstAttr = false;
		} else
			$postAttrs = $postAttrs.",".$attrRes['atr_id'];
	}
}

//Recorrer $postAttrs
/*$firstValue=true;
$tok = strtok ($postAttrs, ",");
while ($tok !== false) {
	if ($firstValue==true) {
		$postValues = (($_REQUEST["atr".$tok]=='on')?"'1'":"'".$_REQUEST["atr".$tok]."'");
		$firstValue = false;
	} else
		$postValues = $postValues.",".(($_REQUEST["atr".$tok]=='on')?"'1'":"'".$_REQUEST["atr".$tok]."'");
	echo "atributo:".$tok." value:".$postValues."<br>";
	$tok = strtok(" \n\t");
}*/
/*
$valueQuery = "SELECT DISTINCT atr_id, ca.value FROM atributos a, celulares_atributos ca WHERE a.filter = 1 AND a.status = 'A' AND a.atr_id = ca.atr_id";
$valueResult = doSelect($valueQuery);
$firstValue=true;
while ($valueRes = mysql_fetch_array($valueResult)) {
	if ($_REQUEST["atr".$valueRes['value']]==$valueRes['value']) {
		if ($firstValue==true) {
			$postValues = $valueRes['value'];	
			$firstValue = false;
		} else
			$postValues = $postValues.",".$valueRes['value'];
	}
}
*/
//echo "postattrs: ".$postAttrs;

$firstMarca=true;
$inMarcas = "";

if ($_REQUEST['marcas']!='') {
	foreach($_REQUEST['marcas'] as $marcas) {
		if ($firstMarca==true) {
			$inMarcas="'".$marcas."'";
			$firstMarca=false;
		} else {
			$inMarcas = $inMarcas.",'".$marcas."'";
		}
	}
}

echo "<table border='1' align='center' cellpadding='3' cellspacing='0' align='left'>";

$celQuery = 
"SELECT DISTINCT c.celu_id, c.marca, c.modelo
FROM celulares c, celulares_atributos ca
WHERE c.celu_id = ca.celu_id
AND c.status = 'A'
".((!$firstMarca)?(" and c.marca in (".$inMarcas.")"):"")."
".(($postAttrs!='')?(" and ca.atr_id in (".$postAttrs.")"):"")."
AND ca.value !=0
AND ca.value IS NOT NULL
ORDER BY c.marca, c.modelo";
/*
$celQuery = 
"select distinct c.celu_id, c.marca, c.modelo 
from celulares c, celulares_atributos ca 
where c.celu_id=ca.celu_id 
and c.status='A' 
".((!$firstMarca)?(" and c.marca in (".$inMarcas.")"):"")."
".(($postAttrs!='')?(" and ca.atr_id in (".$postAttrs.")"):"")."
order by c.marca, c.modelo";
*/
/*".(($postValues!='')?(" and ca.value in (".$postValues.")"):"")."*/

//echo $celQuery;
$celResult = doSelect($celQuery);
//Cuenta la cantidad de celulares
$celCount = 0;
//Seccion IN, que concatena celulares
$inCelulares="";
//Contador de columnas
$colCount = 0;
echo "<tr width='".getColWidth()."%'><td></td>";
while ($celRes = mysql_fetch_array($celResult)) {
	$hasAllAttributes = 1;
	$firstValue = true;
	$tok = strtok ($postAttrs, ",");
	while ($tok !== false) {
		$hasAttrQuery = "select 1 from celulares_atributos where celu_id=".$celRes['celu_id']." and atr_id=".$tok." and value=".(($_REQUEST["atr".$tok]=='on')?'1':'0');
		//echo "<br>".$hasAttrQuery;
		$hasAttrResult = doSelect($hasAttrQuery);
		$hasAttrRes = mysql_fetch_array($hasAttrResult);
		$hasAllAttributes = $hasAllAttributes*$hasAttrRes['1'];
		//echo "<br>".$hasAllAttributes;
		if ($firstValue==true) {
			$celular = (($_REQUEST["atr".$tok]=='on')?"'1'":"'".$_REQUEST["atr".$tok]."'");
			$firstValue = false;
		} else
			$postValues = $postValues.",".(($_REQUEST["atr".$tok]=='on')?"'1'":"'".$_REQUEST["atr".$tok]."'");
		//echo "<br>atributo:".$tok." value:".$postValues;
		$tok = strtok(" \n\t");
	}
	if ($hasAllAttributes) {
		$celulares[$celCount++] = $celRes;

		if ($celCount==1)
			$inCelulares = $celRes['celu_id'];
		else
			$inCelulares = $inCelulares.",".$celRes['celu_id'];

		if ($colCount<=MAX_COLS) {
			//Marca Modelo
			echo "<td align='center'>".$celRes['marca']." ".$celRes['modelo']."</td>";
			$colCount++;
			if ($colCount==MAX_COLS)
				echo "</tr>";
		}	
	} else {
		$hasAllAttributes = 1;
	}
}
if ($colCount>0 && $colCount<MAX_COLS && $celCount>0) {
	for ($i=$colCount;$i<MAX_COLS;$i++)
		echo "<td></td>";
	echo "</tr>";	
}
		
if($inCelulares!='') {
	$resultQuery = 
	"select 
	ca.celu_id, a.atr_id, a.name, ca.value 
	from atributos a, celulares_atributos ca, celulares c
	where a.status='A' and a.tipo!='I' and a.publico=1 and a.atr_id=ca.atr_id 
	and ca.celu_id in (".$inCelulares.") and ca.celu_id=c.celu_id
	order by a.name, c.marca, c.modelo";
	$result = doSelect($resultQuery);
	/* 
	Fila 1:     Marca Modelo
	Fila 2:     Foto
	Fila 3...n: Atributos
	*/
	
	//Contador de filas
	$indexCol = 0;
	//Atributo actual
	$atributo = "";
	
	while ($res = mysql_fetch_array($result)) {
	
		if ($atributo!=$res['name']) {
			if ($atributo!="") {
				if ($indexCol!=MAX_COLS)
					for($i=$indexCol;$i<MAX_COLS;$i++) {
						echo "<td></td>";
					}
				echo "</tr>";
				$indexCol = 0;
			}
			echo "<tr width='".getColWidth()."%'><td>".$res['name']."</td>";
		}
		if ($res['celu_id']==$celulares[$indexCol++]['celu_id'])
			echo "<td align='center'>".( ($res['value']=='1') ? "Si" : ( ($res['value']=='0') ? "No" : $res['value'] ) )."</td>";
		else
			echo "<td>Error</td>";
	
		$atributo = $res['name'];
	}
} else {
	echo "<tr><td>No hay celulares que concuerden con su búsqueda.</td></tr>";
}
?>

</table>

<?php

/*
if ($inicio==0)
  echo "anteriores ";
else
{
  $anterior=$inicio-2;
  echo "<a href=\"pagina1.php?pos=$anterior\">Anteriores </a>";
}
if ($impresos==2)
{
  $proximo=$inicio+2;
  echo "<a href=\"pagina1.php?pos=$proximo\">Siguientes</a>";
}
else
  echo "siguientes";
*/
?>
