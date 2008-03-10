<?php

Function mostrarBorrar($tipo_rta){
	$query = "select count(*) as cantidad from preguntas where tipo_rta = ".$tipo_rta;
	$resultado = doSelect($query);
	$res = mysql_fetch_array($resultado);
	return ($res['cantidad'] == 0);
}
?>