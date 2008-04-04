<script language="javascript">

function SetCookie(cookieName,cookieValue) {
	var value = cookieValue;
	var name = cookieName;
	var d = new Date("January 31, 2009");
	var cd = d.toGMTString();
	var c = escape(name)+"="+escape(value)+";expires="+cd;
	document.cookie = c;
}
 
function getCookieVal (offset) {
  var endstr = document.cookie.indexOf (";", offset);
  if (endstr == -1)
  endstr = document.cookie.length;
  return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie (name) {
  var arg = name + "=";
  var alen = arg.length;
  var clen = document.cookie.length;
  var i = 0;
  while (i < clen) {
    var j = i + alen;
    if (document.cookie.substring(i, j) == arg) {
	    return getCookieVal (j);
	}
    i = document.cookie.indexOf(" ", i) + 1;
    if (i == 0) break;
  }
  return null;
}

function DelCookie(sName) {
  document.cookie = sName + "=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
}

function checkCountCompare(user_id) {
	if (parseInt(GetCookie('compare_cant_'+user_id))<2)
		alert("Debe seleccionar al menos 2 celulares.");
	else
		window.location.href = 'index.php?lbl=celularesListado&list=1&compare=Y';
}

function addComparePhone(user_id,celu_id,chk) {
	if (chk) {
		if (parseInt(GetCookie('compare_cant_'+user_id))<4) {
			if (GetCookie('compare1_'+user_id)<0)
				SetCookie('compare1_'+user_id,celu_id);
			else if (GetCookie('compare2_'+user_id)<0)
				SetCookie('compare2_'+user_id,celu_id);
			else if (GetCookie('compare3_'+user_id)<0)
				SetCookie('compare3_'+user_id,celu_id);
			else if (GetCookie('compare4_'+user_id)<0)
				SetCookie('compare4_'+user_id,celu_id);
			SetCookie('compare_cant_'+user_id,parseInt(GetCookie('compare_cant_'+user_id))+1);
		} else {
			alert("Debe seleccionar como maximo 4 celulares para comparar");
			document.getElementById('comparar'+celu_id).checked=false;
		}
	} else {
		if (GetCookie('compare1_'+user_id)==celu_id)
			SetCookie('compare1_'+user_id,"-1");
		else if (GetCookie('compare2_'+user_id)==celu_id)
			SetCookie('compare2_'+user_id,"-1");
		else if (GetCookie('compare3_'+user_id)==celu_id)
			SetCookie('compare3_'+user_id,"-1");
		else if (GetCookie('compare4_'+user_id)==celu_id)
			SetCookie('compare4_'+user_id,"-1");
		SetCookie('compare_cant_'+user_id,parseInt(GetCookie('compare_cant_'+user_id))-1);
	}
//	alert(GetCookie('compare1_'+user_id)+" "+GetCookie('compare2_'+user_id)+" "+GetCookie('compare3_'+user_id)+" "+GetCookie('compare4_'+user_id)+"  "+GetCookie('compare_cant_'+user_id));
}

</script>

<?php
define('MAX_COLS',4);
define('MIN_COLS',1);

Function getColWidth() {
	return (100/MAX_COLS);
}

Function chequearChecked($celu_id) {
	if (($celu_id == $_COOKIE['compare1_'.$_COOKIE['user_active']]) ||
	    ($celu_id == $_COOKIE['compare2_'.$_COOKIE['user_active']]) ||
	    ($celu_id == $_COOKIE['compare3_'.$_COOKIE['user_active']]) ||
	    ($celu_id == $_COOKIE['compare4_'.$_COOKIE['user_active']]))
		return "checked";
	else
		return "";
}

setcookie("list_".$_COOKIE['user_active'],$_REQUEST['list'],time()*365*24*60*60,"/");

if ( !isset($_COOKIE['celulares_'.$_COOKIE['user_active']]) && !($_REQUEST['compare']=='Y') ) {
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


echo "<br>
<form action='comparar.php' method='post' name='searchList'>
<table border='1' align='center' cellpadding='3' cellspacing='0' align='left' style='border-collapse:collapse;border-color:gray'>";

if ($_REQUEST['compare']=='Y')
	$celCompare = $_COOKIE['compare1_'.$_COOKIE['user_active']].",".$_COOKIE['compare2_'.$_COOKIE['user_active']].",".$_COOKIE['compare3_'.$_COOKIE['user_active']].",".$_COOKIE['compare4_'.$_COOKIE['user_active']];

$celQuery = 
"SELECT DISTINCT c.celu_id, c.marca, c.modelo
FROM celulares c, celulares_atributos ca
WHERE c.celu_id = ca.celu_id
AND c.status = 'A'
".((!$firstMarca && $inMarcas!='')?(" and c.marca in (".$inMarcas.")"):"")."
".(($postAttrs!='')?(" and ca.atr_id in (".$postAttrs.")"):"")."
".((isset($_COOKIE['celulares_'.$_COOKIE['user_active']]) && !($_REQUEST['compare']=='Y'))?"AND ca.celu_id in (".$_COOKIE['celulares_'.$_COOKIE['user_active']].")":"")."
".(($_REQUEST['compare']=='Y')?"AND ca.celu_id in (".$celCompare.")":"")."
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
	if (!isset($_COOKIE['celulares_'.$_COOKIE['user_active']]) && !($_REQUEST['compare']=='Y')) {
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
	
	if ($hasAllAttributes || isset($_COOKIE['celulares_'.$_COOKIE['user_active']]) || ($_REQUEST['compare']=='Y')) {
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

if ($_REQUEST['compare']=='Y')
	$inCelulares=$celCompare;

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

	if (!($_REQUEST['compare']=='Y')) {
		
		echo "<tr><td align='right'>";
		echo "<a href='javascript:checkCountCompare(".$_COOKIE['user_active'].");'>Comparar celulares</a> (<a href='javascript:limpiarChecks()'>Limpiar</a>)";
		echo "</td>";
		$tokNum = 1;
		$compareTok = strtok ($inCelulares, ",");
		$minCel = ($_REQUEST['list']*MAX_COLS)-MAX_COLS;
		while ($compareTok !== false) {
			if ($tokNum>$minCel && $tokNum<=($minCel+MAX_COLS)) {
				$chequear = chequearChecked($compareTok);
				echo "<td align='center'><input type='checkbox' id='comparar$compareTok' value='$compareTok' ".$chequear." onclick='javascript:addComparePhone(".$_COOKIE['user_active'].",".$compareTok.",this.checked);'></td>";
			}
			$tokNum++;
			$compareTok = strtok(",");
		}
		echo "</tr>";
	}
	
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
if ($_REQUEST['compare']=='Y') {
	echo "<br><center><input type='button' name='volver' value='Volver' onclick='javascript:history.back()'></center>";
	setcookie("celulares_".$_COOKIE['user_active'],"",time()*365*24*60*60,"/");
}
echo "</form>";

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
