<?php

require("funcionesDB.php");

Function store_action($usrid,$act,$desc,$url) {
		$query = "insert into logs (user_id, ins_dt, action, desc, url) values (".$usrid.",sysdate(),".$act.",'".$desc."','".$url."')";
		doInsert($query) or die("Error en select <br>".mysql_error());
}

Function logout() {
	store_action($_REQUEST['user_id'],LOGOUT,'',$_SERVER['REQUEST_URI']);
	//delete_from_users_cookie();
}

logout();

Response.Redirect("index.php");

?>