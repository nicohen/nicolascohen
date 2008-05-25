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

function limpiarChecks(user_id) {
	SetCookie('compare1_'+user_id,"-1");
	SetCookie('compare2_'+user_id,"-1");
	SetCookie('compare3_'+user_id,"-1");
	SetCookie('compare4_'+user_id,"-1");
	SetCookie('compare_cant_'+user_id,0);
	//Desactivar checkboxes
	var inputs = document.getElementsByTagName("input");
	for(var i=0; i<inputs.length; i++){
		if(inputs[i].getAttribute('type')=='checkbox') {
			if (inputs[i]["checked"]) {
				inputs[i].checked=false;
			}
		}
	}
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

	function imprimir(){
		document.getElementById("divButtons").style.display = "none";
		window.print();
		setInterval('100000');
		document.getElementById("divButtons").style.display = "";
	}

</script>

<?php
function POST_to_GET(){
    foreach($_POST as $key=>$valor) {
        if(isset($temp)){
            $temp = $temp."&".$key."=".$valor;
        }
        else{
            $temp = "?".$key."=".$valor;    
        }
    }
    return $temp;
}

store_action($_COOKIE['user_active'],BUSQUEDA,'',$_SERVER['REQUEST_URI']);

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
			} else {
				$postAttrs = $postAttrs.",".$attrRes['atr_id'];
			}
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
	
	$prepagoMinPrice = $_REQUEST['precio_prepago_min'];
	$prepagoMaxPrice = $_REQUEST['precio_prepago_max'];
	$postPagoMinPrice = $_REQUEST['precio_postpago_min'];
	$postPagoMaxPrice = $_REQUEST['precio_postpago_max'];
	//echo $postPagoMinPrice;
	
}


echo "<br>
<form action='comparar.php' method='post' name='searchList'>";?>
<table border='1' align='center' cellpadding='3' cellspacing='0' align='left' style='border-collapse:collapse;border-color:gray;font-size:13px; font:Arial, Helvetica, sans-serif'>
<?php
if ($_REQUEST['compare']=='Y')
	$celCompare = $_COOKIE['compare1_'.$_COOKIE['user_active']].",".$_COOKIE['compare2_'.$_COOKIE['user_active']].",".$_COOKIE['compare3_'.$_COOKIE['user_active']].",".$_COOKIE['compare4_'.$_COOKIE['user_active']];

$celQuery = 
"SELECT DISTINCT c.celu_id, c.marca, c.modelo
FROM celulares c, celulares_atributos ca
WHERE c.celu_id = ca.celu_id
AND c.status = 'A'
".((!$firstMarca && $inMarcas!='')?(" and c.marca in (".$inMarcas.")"):"")." 
".(($prePagoMinPrice != "") ? " and c.precio_prepago >= ".$prePagoMinPrice : "")." 
".(($prePagoMaxPrice != "") ? " and c.precio_prepago <= ".$prePagoMaxPrice : "")." 
".(($postPagoMinPrice != "") ? " and c.precio_postpago >= ".$postPagoMinPrice : "")." 
".(($postPagoMinPrice != "") ? " and c.precio_postpago <= ".$postPagoMaxPrice : "")." 
".(($postAttrs!='')?(" and ca.atr_id in (".$postAttrs.")"):"")."
".((isset($_COOKIE['celulares_'.$_COOKIE['user_active']]) && !($_REQUEST['compare']=='Y'))?" AND ca.celu_id in (".$_COOKIE['celulares_'.$_COOKIE['user_active']].")":"")."
".(($_REQUEST['compare']=='Y')?"AND ca.celu_id in (".$celCompare.")":"")."
".(($postAttrs!='')?"AND ca.value !=0 AND ca.value IS NOT NULL":"")."
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
			$isCheckQuery = "select tipo from atributos where atr_id=".$tok;
			$isCheckResult = doSelect($isCheckQuery);
			$isCheckRes = mysql_fetch_array($isCheckResult);

			if ($isCheckRes['tipo']=='CH') {
				$hasAttrQuery = "select 1 from celulares_atributos where celu_id=".$celRes['celu_id']." and atr_id=".$tok." and value=".(($_REQUEST["atr".$tok]=='on')?'1':'0');
				$hasAttrResult = doSelect($hasAttrQuery);
				$hasAttrRes = mysql_fetch_array($hasAttrResult);
				$hasAllAttributes = $hasAllAttributes*$hasAttrRes['1'];
			} else if ($isCheckRes['tipo']=='SM') {
				$tieneAtributo=false;
				foreach($_REQUEST['atr'.$tok] as $atributo) {
					$hasAttrQuery = "select 1 from celulares_atributos where celu_id=".$celRes['celu_id']." and atr_id=".$tok." and trim(instr(value,'".$atributo."'))>0";
					$hasAttrResult = doSelect($hasAttrQuery);
					$hasAttrRes = mysql_fetch_array($hasAttrResult);
					if ($hasAttrRes['1']==1)
						$tieneAtributo=true;
				}
				if(!$tieneAtributo) 
					$hasAllAttributes=0;
			}

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
	//for ($i=$colCount;$i<MAX_COLS;$i++)
		//echo "<td></td>";
	echo "</tr>";	
}

if ($_REQUEST['compare']=='Y')
	$inCelulares=$celCompare;

if($inCelulares!='') {
	//Seteo la cookie para viajar por los listados de celulares
	setcookie("celulares_".$_COOKIE['user_active'],$inCelulares,time()*365*24*60*60,"/");
		
	echo "<tr><td><img src=\"imgs/primer_claro.jpg\"</td>";
	$tokNum = 1;
	$celTok = strtok ($inCelulares, ",");
	$minCel = ($_REQUEST['list']*MAX_COLS)-MAX_COLS;
	while ($celTok !== false) {
		if ($tokNum>$minCel && $tokNum<=($minCel+MAX_COLS)) {
			//$imgQuery = "select value from celulares_atributos where celu_id=".$celTok." and atr_id=15";
			$imgQuery = "SELECT c.value FROM celulares_atributos c, atributos a
						 WHERE a.atr_id = c.atr_id
						 AND a.tipo = '".ATTR_TYPE_IMAGE."'
						 and c.celu_id=".$celTok;
			
			$imgResult = doSelect($imgQuery);
			if ($imgRes = mysql_fetch_array($imgResult))
				echo "<td align='center'><a href='vpp.php?celu_id=$celTok' target='_blank'><img border='0' src='".getLinkImage("/img/".$imgRes['value'],120,136)."' /></a></td>"; 
		}
		$tokNum++;
		$celTok = strtok(",");
	}
	echo "</tr>";

	if (!($_REQUEST['compare']=='Y')) {
		
		echo "<tr><td align='right'>";
		echo "<a href='javascript:checkCountCompare(".$_COOKIE['user_active'].");'>Comparar celulares</a> (<a href='javascript:limpiarChecks(".$_COOKIE['user_active'].");'>Limpiar</a>)";
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
	where a.status='A' and a.tipo!='I' and a.publico=1 and a.comparable=1 and a.atr_id=ca.atr_id 
	and ca.celu_id in (".$inCelulares.") and ca.celu_id=c.celu_id
	order by a.peso, a.name, c.marca, c.modelo";
	//echo $resultQuery;
	$result = doSelect($resultQuery);
	/* 
	Fila 1:     Marca Modelo
	Fila 2:     Foto
	Fila 3:     Checks
	Fila 4...n: Atributos
	*/
	
	//Contador de columnas
	$indexCol = 0;
	//Contador de celulares
	$celCounter = 1;
	//Atributo actual
	$atributoActual = "";
	//echo $resultQuery;
	while ($res = mysql_fetch_array($result)) {
		if ($atributoActual!=$res['name']) {
			if ($atributoActual!="") {
				//echo "<br>index:".$indexCol;
				/*if ($indexCol<=$celCount) {
					for($i=$indexCol;$i<$celCount;$i++) {
						echo "<td></td>";
					}
				}*/
				echo "</tr>";
				$indexCol = 0;
			}
			echo "<tr width='".getColWidth()."%'><td>".$res['name']."</td>";
			$atributoActual = $res['name'];
			$celCounter=1;
		}
		//echo "<br>atributoActual:".$atributoActual;
		//echo "<br>res:".$res['value'];
		$tempCelCounter = $celCounter++;
		while($tempCelCounter<=MAX_COLS) {
			if ($tempCelCounter>((MAX_COLS*$_REQUEST['list'])-MAX_COLS) && $tempCelCounter<=($_REQUEST['list']*MAX_COLS)) {
				$indexCol++;
				if ($res['celu_id']==$celulares[$tempCelCounter-1]['celu_id']) {
					echo "<td align='center'>".( ($res['value']=='1') ? "Si" : ( ($res['value']=='0') ? "No" : $res['value'] ) )."</td>";
					break;
				} else {
					echo "<td></td>";
				}
			}
			$tempCelCounter++;
		}
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
	echo "<center><font face='Arial, Helvetica, sans-serif' size='2px'>";
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
	echo "</font></center><br>";
}

?>
<div id="divButtons">
<table width="100%" align="center" border="0">
	<tr>
		<td align="center"> <input type="button" value="Imprimir" onClick="imprimir()"> </td>
	</tr>
</table>
</div>