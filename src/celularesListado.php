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

<table border="1" align="center" cellpadding="3" cellspacing="0" align="left">

<?php

$attrQuery = "select atr_id from atributos where filter=1 and status='A'";
$attrResult = doSelect($attrQuery);
$firstAttr=true;
while ($attrRes = mysql_fetch_array($attrResult)) {
	if ($_REQUEST["atr".$attrRes['atr_id']]!='') {
		//$postAttrs[$cantPostAttrs++]=$_REQUEST['atr_id'];
		if ($firstAttr==true) {
			$postAttrs = $attrRes['atr_id'];
			$firstAttr = false;
		} else
			$postAttrs = $postAttrs.",".$attrRes['atr_id'];
	}
}


$celQuery = 
"select distinct c.celu_id, c.marca, c.modelo 
from celulares c, celulares_atributos ca
where c.celu_id=ca.celu_id
and c.status='A' ".(($postAttrs!='')?("and ca.atr_id in (".$postAttrs.")"):"")."
order by c.marca, c.modelo";
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
	$celulares[$celCount++] = $celRes;
	if ($celCount==1)
		$inCelulares = $celRes['celu_id'];
	else
		$inCelulares = $inCelulares.",".$celRes['celu_id'];

	if ($colCount!=MAX_COLS) {
		$colCount++;
		//if ($colCount==MIN_COLS)
			//echo "<tr width='".getColWidth()."%'>";
		//Marca Modelo
		echo "<td align='center'>".$celRes['marca']." ".$celRes['modelo']."</td>";
		if ($colCount==MAX_COLS)
			echo "</tr>";
	}	
}

$resultQuery = 
"select 
ca.celu_id, a.atr_id, a.name, ca.value 
from atributos a, celulares_atributos ca
where a.status='A' and a.atr_id=ca.atr_id 
and ca.celu_id in (".$inCelulares.")
order by a.name";
$result = doSelect($resultQuery);
/* 
Fila 1:     Marca Modelo
Fila 2:     Foto
Fila 3...n: Atributos
*/

//Contador de filas
$indexCol = 0;
while ($res = mysql_fetch_array($result)) {
	echo "<tr><td>".$res['name']."</td>";
	if ($res['celu_id']==$celulares[$indexCol++]['celu_id'])
		echo "<td align='center'>".$res['value']."</td>";
	else
		echo "<td></td>";

	if ($res['celu_id']==$celulares[$indexCol++]['celu_id'])
		echo "<td align='center'>".$res['value']."</td>";
	else
		echo "<td></td>";

	if ($res['celu_id']==$celulares[$indexCol++]['celu_id'])
		echo "<td align='center'>".$res['value']."</td>";
	else
		echo "<td></td>";

	if ($res['celu_id']==$celulares[$indexCol]['celu_id'])
		echo "<td align='center'>".$res['value']."</td>";
	else
		echo "<td></td>";

	echo "</tr>";
	$indexCol = 0;
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
