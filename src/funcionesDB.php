<?php

if (DB_USER == NULL || DB_USER == '' || DB_USER == 'DB_USER'){
	include("constantes.php");
}

Function getConnection(){
	//Por ahora no uso password para conectarme a la base.
	$connection = mysql_connect(DB_SERVER,DB_USER) or die ("Problemas en la conexi�n");
	mysql_select_db(DB_NAME,$connection);
	return $connection;
}

Function doSelect($query){
	$connection = getConnection();
	$returnSelect = mysql_query($query,$connection);// or die ("Problemas en select".mysql_error());
	closeConnection($connection);
	return $returnSelect;
}

Function doInsert($query){
	$connection = getConnection();
	mysql_query($query,$connection);
	closeConnection($connection);
}

Function doInsertAndGetLast($query){
	$conn = getConnection();
	mysql_query($query,$conn) or die ("Error en insert".mysql_error());
	$returnInsert = mysql_insert_id($conn);
	closeConnection($conn);
	return $returnInsert;
}

Function closeConnection($conn) {
	mysql_close($conn);
}

Function store_action($usrid,$act,$desc,$url) {
	$query = "insert into logs (user_id, ins_dt, act, descr, url) values (".$usrid.",sysdate(),".$act.",'".$desc."','".$url."')";
	doInsert($query);
}

?>