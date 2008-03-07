<?php

include("constantes.php");

Function getConnection(){
	//Por ahora no uso password para conectarme a la base.
	$conexion = mysql_connect(DB_SERVER,DB_USER) or die ("Problemas en la conexión");
	mysql_select_db(DB_NAME,$conexion);
	return $conexion;
}

Function doSelect($query){
	return mysql_query($query,getConnection());// or die ("Problemas en select".mysql_error());
}

?>