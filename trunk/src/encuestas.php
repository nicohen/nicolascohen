
<?php
$act = $_REQUEST['act'];
if ( $act == SAVE_ENC){
	$qryInsEnc = "insert into encuestas (titulo) values ('".$_REQUEST['titulo']."')";
	$encID = doInsertAndGetLast($qryInsEnc);
//	echo($_REQUEST['cantidad']);
	for ($i = 1; $i <= $_REQUEST['cantidad']; $i++){
		$preg = $_REQUEST['pregunta'.$i];
		$rta = $_REQUEST['respuesta'.$i];
		$qryInsPreg = "insert into preguntas (prg_id, enc_id, tipo_rta, pregunta) values (".$i.",".$encID.",".$rta.",'".$preg."')";
		doInsert($qryInsPreg);
	}
	echo ("<b><i>La encuesta se ha guardado correctamente</i></b><br>");
} else if ($act == DELETE_ENC){
	$query = "update encuestas set status = 'I' where enc_id= ".$_REQUEST['enc_id'];
	doInsert($query);
}
?>
<br>
<table border="1" cellpadding="3" cellspacing="0" width="550" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td width="350">&nbsp;<span class="style1"> Título </span></td>
	<td width="200" align="center"><span class="style1">  Acciones </span></td>
</tr>
<?php 
	$query = "select enc_id,titulo from encuestas where status = 'A'";
	$registros = doSelect($query) or die ("Problemas en select".mysql_error());
	while ($reg = mysql_fetch_array($registros)){
		echo "<tr bgcolor=\"#FFCC66\">";
		//echo "ID: ".$reg['enc_id']."<br>";
		echo "<td class=\"textCS\">&nbsp;&nbsp;&nbsp;&nbsp;".$reg['titulo']."</td>";
		echo "<td><a class=\"ca\" href=/cti/src/responderEncuesta.php?enc_id=".$reg['enc_id']." target=\"_blank\">Responder</a>";
		if (isSupervisor($_COOKIE['user_active'])){
			echo "&nbsp;&nbsp;&nbsp;";
			echo "<a class=\"ca\" href=\"/cti/src/index.php?lbl=".MENU_ENCUESTAS."&enc_id=".$reg['enc_id']."&act=".DELETE_ENC."\">Borrar</a>";
		}
		echo "</td>";
		echo "</tr>";
	}
?>
</table>
