<?php

require("funcionesDB.php");

Function store_action($usrid,$act,$desc,$url) {
		$query = "insert into logs (user_id, ins_dt, act, descr, url) values (".$usrid.",sysdate(),".$act.",'".$desc."','".$url."')";
		doInsert($query);
}

Function delete_from_users_cookie($userId) {
	$othertok = '';
	$firstToken = true;

	$tok = strtok ($_COOKIE['user_ids'], ",");
	if ($tok !== false) {
		if ($tok!=$userId) {
			$firstToken=false;
			$othertok=$tok;
		}
		$tok = strtok(" \n\t");
	}

	while ($tok !== false) {
		if ($tok!=$userId) {
			if (!$firstToken)
				$othertok+=',';
			else
				$firstToken=false;
			$othertok=$tok;
		}
		$tok = strtok(" \n\t");
	}

	setcookie("user_ids",$othertok,time()+60*60*24*365, "/");
}

Function logout() {
	store_action($_REQUEST['user_id'],LOGOUT,'',$_SERVER['REQUEST_URI']);
	delete_from_users_cookie($_REQUEST['user_id']);
}

logout();

header( 'Location: index.php' ) ;

?>