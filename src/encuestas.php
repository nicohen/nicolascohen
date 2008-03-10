<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 
?>

<html>
<head>
<title>Untitled Document</title>
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.ca {
	font-weight: bold;	
}
.ca:link {
	color: #0000CC;
	text-decoration: none;
}
.ca:visited {
	color: #0000CC;
	text-decoration: none;
}
.ca:hover {
/*	text-decoration: underline;*/
	color: #000066;
}
.ca:active {
	text-decoration: none;
}
.textCS {
	font-family:"Rockwell";
}
a:link {

}
a:visited {
	color: #0000CC;
}
a:hover {

}
.tabla{
	border-left-color:#000000;
	border-right-color:#000000;
	border-top-color:#000000;
	border-bottom-color:#000000;
/*	border-style:outset;*/

}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

<body>
<?php
$act = $_REQUEST['act'];
if ( $act == SAVE_ENC){
	$qryInsEnc = "insert into encuestas (titulo) values ('".$_REQUEST['titulo']."')";
	$encID = doInsertAndGetLast($qryInsEnc);
	echo($_REQUEST['cantidad']);
	for ($i = 1; $i <= $_REQUEST['cantidad']; $i++){
		$preg = $_REQUEST['pregunta'.$i];
		$rta = $_REQUEST['respuesta'.$i];
		$qryInsPreg = "insert into preguntas (prg_id, enc_id, tipo_rta, pregunta) values (".$i.",".$encID.",".$rta.",'".$preg."')";
		doInsert($qryInsPreg);
	}
	echo ("<b>La encuesta se ha guardado correctamente</b><br>");
} else if ($act == DELETE_ENC){
	$query = "update encuestas set status = 'I' where enc_id= ".$_REQUEST['enc_id'];
	doInsert($query);
}
?>
<table class="tabla" cellpadding="0" cellspacing="0" width="550">
<tr bgcolor="#FF9900">
	<td width="350">&nbsp;<span class="style1"> Título </span></td>
	<td width="200" align="center"><span class="style1">  Acciones </span></td>
</tr>
<tr height="1" bgcolor="#000000">
	<td colspan="2"></td>
</tr>
<?php 
	$query = "select enc_id,titulo from encuestas where status = 'A'";
	$registros = doSelect($query) or die ("Problemas en select".mysql_error());
	while ($reg = mysql_fetch_array($registros)){
		echo "<tr bgcolor=\"#FFCC66\">";
		//echo "ID: ".$reg['enc_id']."<br>";
		echo "<td class=\"textCS\">&nbsp;&nbsp;&nbsp;&nbsp;".$reg['titulo']."</td>";
		echo "<td><a class=\"ca\" href=/cti/src/responderEncuesta.php?enc_id=".$reg['enc_id']."> Responder </a>";
		echo "&nbsp;&nbsp;&nbsp;";
		echo "<a class=\"ca\" href=\"/cti/src/encuestas.php?enc_id=".$reg['enc_id']."&act=".DELETE_ENC."\"> Borrar </a>";
		echo "</td>";
		echo "</tr>";
	}
?>
</table>
</body>
</html>
