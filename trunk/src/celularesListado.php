<?php
define('MAX_COLS',4);
define('MIN_COLS',1);

Function resetCols() {
	return MIN_COLS;
}

Function getColWidth() {
	return (100/MAX_COLS);
}

if (isset($_REQUEST['pos']))
  $inicio=$_REQUEST['pos'];
else
  $inicio=0;
?>

<table border="1" align="center" cellpadding="3" cellspacing="0" align="left">
<?php
$celQuery = 
"select distinct c.celu_id, c.marca, c.modelo 
from celulares c, celulares_atributos ca2
where c.celu_id=ca2.celu_id
and c.status='A' order by c.marca, c.modelo";
$celResult = doSelect($celQuery);
$celCount = 0;
$inCelulares="";
$colCount = 1;
while ($celRes = mysql_fetch_array($celResult)) {
	$celulares[$celCount++] = $celRes;
	$inCelulares = $inCelulares.$celRes['celu_id'];
	if ($colCount==1) {
		
		echo "<tr width='".getColWidth()."%'>";
	}
	echo "<td align='center'>".$celRes['marca']." ".$celRes['modelo']."</td>";
	if ($colCount==4) {
		echo "</tr>";
		$colCount = resetCols();
	}
	$colCount++;
}

$resultQuery = 
"select 
ca.celu_id, a.atr_id, a.name, ca.value 
from atributos a, celulares_atributos ca
where ca.status='A' and a.atr_id=ca.atr_id 
and c.celu_id in (".$inCelulares.")
order by a.name";
$result = doSelect($resultQuery);
//while ($res = mysql_fetch_array($result)) {
	
//}
?>

</table>

<?php
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

?>
