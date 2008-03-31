<?php
define('MAX_COLS',4);
define('MIN_COLS',1);

Function getColWidth() {
	return (100/MAX_COLS);
}

setcookie("list_".$_COOKIE['user_active'],$_REQUEST['list'],time()*365*24*60*60,"/");

if ( !isset($_COOKIE['celulares_'.$_COOKIE['user_active']]) ) {
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
}


echo "<br><table border='1' align='center' cellpadding='3' cellspacing='0' align='left' style='border-collapse:collapse;border-color:gray'>";

$celQuery = 
"SELECT DISTINCT c.celu_id, c.marca, c.modelo
FROM celulares c, celulares_atributos ca
WHERE c.celu_id = ca.celu_id
AND c.status = 'A'
".((!$firstMarca && $inMarcas!='')?(" and c.marca in (".$inMarcas.")"):"")."
".(($postAttrs!='')?(" and ca.atr_id in (".$postAttrs.")"):"")."
".((isset($_COOKIE['celulares_'.$_COOKIE['user_active']]))?"AND ca.celu_id in (".$_COOKIE['celulares_'.$_COOKIE['user_active']].")":"")."
AND ca.value !=0
AND ca.value IS NOT NULL
ORDER BY c.marca, c.modelo";

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
	$hasAllAttributes = 0;
	if (!isset($_COOKIE['celulares_'.$_COOKIE['user_active']])) {
		$hasAllAttributes = 1;
		$tok = strtok ($postAttrs, ",");
		while ($tok !== false) {
			$hasAttrQuery = "select 1 from celulares_atributos where celu_id=".$celRes['celu_id']." and atr_id=".$tok." and value=".(($_REQUEST["atr".$tok]=='on')?'1':'0');
			$hasAttrResult = doSelect($hasAttrQuery);
			$hasAttrRes = mysql_fetch_array($hasAttrResult);
			$hasAllAttributes = $hasAllAttributes*$hasAttrRes['1'];
			
			$tok = strtok(",");
		}
	}
	
	if ($hasAllAttributes || isset($_COOKIE['celulares_'.$_COOKIE['user_active']])) {
		$celulares[$celCount++] = $celRes;

		if ($celCount==1)
			$inCelulares = $celRes['celu_id'];
		else
			$inCelulares = $inCelulares.",".$celRes['celu_id'];

		if ($celCount>(MAX_COLS*$_REQUEST['list']-MAX_COLS) && $celCount<=(MAX_COLS*$_REQUEST['list'])) {
			//Marca Modelo
			echo "<td align='center'><a href='vpp.php?celu_id=".$celRes['celu_id']."' target='_blank'>".$celRes['marca']." ".$celRes['modelo']."</a></td>";
			$colCount++;
			if ($colCount==MAX_COLS)
				echo "</tr>";
		}
	}
}
if ($colCount>0 && $colCount<MAX_COLS && $celCount>0) {
	for ($i=$colCount;$i<MAX_COLS;$i++)
		echo "<td></td>";
	echo "</tr>";	
}
		
if($inCelulares!='') {
	//Seteo la cookie para viajar por los listados de celulares
	setcookie("celulares_".$_COOKIE['user_active'],$inCelulares,time()*365*24*60*60,"/");
		
	echo "<tr><td></td>";
	$tokNum = 1;
	$celTok = strtok ($inCelulares, ",");
	$minCel = ($_REQUEST['list']*MAX_COLS)-MAX_COLS;
	while ($celTok !== false) {
		if ($tokNum>$minCel && $tokNum<=($minCel+MAX_COLS)) {
			$imgQuery = "select value from celulares_atributos where celu_id=".$celTok." and atr_id=15";
			$imgResult = doSelect($imgQuery);
			if ($imgRes = mysql_fetch_array($imgResult))
				echo "<td align='center'><a href='vpp.php?celu_id=$celTok' target='_blank'><img border='0' width='100' height='100' src='img/".$imgRes['value']."' /></a></td>"; 
		}
		$tokNum++;
		$celTok = strtok(",");
	}
	echo "</tr>";

	$resultQuery = 
	"select 
	ca.celu_id, a.atr_id, a.name, ca.value 
	from atributos a, celulares_atributos ca, celulares c
	where a.status='A' and a.tipo!='I' and a.publico=1 and a.atr_id=ca.atr_id 
	and ca.celu_id in (".$inCelulares.") and ca.celu_id=c.celu_id
	order by a.name, c.marca, c.modelo";
	//echo $resultQuery;
	$result = doSelect($resultQuery);
	/* 
	Fila 1:     Marca Modelo
	Fila 2:     Foto
	Fila 3...n: Atributos
	*/
	
	//Contador de columnas
	$indexCol = 0;
	//Contador de celulares
	$celCounter = 1;
	//Atributo actual
	$atributoActual = "";
	
	while ($res = mysql_fetch_array($result)) {
		if ($atributoActual!=$res['name']) {
			if ($atributoActual!="") {
				if ($indexCol!=MAX_COLS) {
					for($i=$indexCol;$i<MAX_COLS;$i++) {
						echo "<td></td>";
					}
				}
				echo "</tr>";
				$indexCol = 0;
			}
			echo "<tr width='".getColWidth()."%'><td>".$res['name']."</td>";
			$atributoActual = $res['name'];
			$celCounter=1;
		}

		if ($celCounter>((MAX_COLS*$_REQUEST['list'])-MAX_COLS) && $celCounter<=($_REQUEST['list']*MAX_COLS)) {
			if ($res['celu_id']==$celulares[$celCounter-1]['celu_id']) {
				$indexCol++;
				echo "<td align='center'>".( ($res['value']=='1') ? "Si" : ( ($res['value']=='0') ? "No" : $res['value'] ) )."</td>";
			}
		}
		$celCounter++;
	}
} else {
	echo "<tr><td>No hay celulares que concuerden con su búsqueda.</td></tr>";
}

echo "</table>";

if ($celCount>MAX_COLS) {
	echo "<br><center>";
	if ($_REQUEST['list']>1)
		echo "<a href='index.php?lbl=".MENU_CELULARES_LISTADO."&list=".($_REQUEST['list']-1)."'>Anterior</a> ";
	$i=1;
	while($i<=$celCount) {
		if (++$cantLinks==$_REQUEST['list']) {
			echo "<b>".$cantLinks."</b>";
		} else {
			echo " <a href='index.php?lbl=".MENU_CELULARES_LISTADO."&list=".$cantLinks."'>".$cantLinks."</a> ";
		}
		$i+=MAX_COLS;
	}
	if ($_REQUEST['list']!=$cantLinks)	
		echo " <a href='index.php?lbl=".MENU_CELULARES_LISTADO."&list=".($_REQUEST['list']+1)."'>Siguiente</a>";
	echo "</center><br>";
}

?>
