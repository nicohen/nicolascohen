<?php

require("funcionesDB.php");
require("funciones.php");

Function setear_cookies_usuario($res,$usr) {
	addCookie("user_name_".$res['user_id'], $res['name']);
	addCookie("user_last_name_".$res['user_id'], $res['last_name']);
	addCookie("user_nickname_".$res['user_id'], $usr);
	addCookie("user_super_".$res['user_id'], $res['super']);
	addCookie("user_status_".$res['user_id'], $res['status']);
	if (!isset($_COOKIE['user_ids']))
		addCookie("user_ids", $res['user_id']);
	else {
		if ($_COOKIE['user_ids']=='')
			addCookie("user_ids", $res['user_id']);
		else
			addCookie("user_ids", $_COOKIE['user_ids'].",".$res['user_id']);
	}
	addCookie("user_active", $res['user_id']);
	
}

Function esta_logueado($res) {
	if (isset($_COOKIE['user_ids'])) {
		$tok = strtok ($_COOKIE['user_ids'], ",");
		while ($tok !== false) {
			if ($tok==$res['user_id']) {
				return true;
			}
			$tok = strtok(" \n\t");
		}
		return false;
	} else {
		return false;
	}
}

Function check_login($usr, $pwd) {
		$query = "select user_id, name, last_name, super, status, ins_dt from usuarios where nickname='".$usr."' and pwd='".$pwd."'";
		$result = doSelect($query) or die("Error en select ".mysql_error());
		if ($res = mysql_fetch_array($result)) {
			if (!esta_logueado($res) && $res['status']=='A') {
				setear_cookies_usuario($res,$usr);
				store_action($res['user_id'],LOGIN,'',$_SERVER['REQUEST_URI']);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
}

$login = check_login($_REQUEST['usr'], $_REQUEST['pwd']);

header( 'Location: index.php' );

?>