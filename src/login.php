<?php

require("funcionesDB.php");

Function setear_cookies_usuario($res,$usr) {
	setcookie("user_name_".$res['user_id'], $res['name'], time()+60*60*24*365, "/");
	setcookie("user_last_name_".$res['user_id'], $res['last_name'], time()+60*60*24*365, "/");
	setcookie("user_nickname_".$res['user_id'], $usr, time()+60*60*24*365, "/");
	setcookie("user_super_".$res['user_id'], $res['super'], time()+60*60*24*365, "/");
	setcookie("user_status_".$res['user_id'], $res['status'], time()+60*60*24*365, "/");
	setcookie("user_ins_dt_".$res['user_id'], $res['ins_dt'], time()+60*60*24*365, "/");
	setcookie("user_log_dt_".$res['user_id'], time(), time()+60*60*24*365, "/");
	if (!isset($_COOKIE['user_ids']))
		setcookie("user_ids", $res['user_id'], time()+60*60*24*365, "/");
	else {
		if ($_COOKIE['user_ids']=='')
			setcookie("user_ids", $res['user_id'], time()+60*60*24*365, "/");
		else
			setcookie("user_ids", $_COOKIE['user_ids'].",".$res['user_id'], time()+60*60*24*365, "/");
	}
	setcookie("user_active", $res['user_id'], time()+60*60*24*365, "/");
	
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

//if ($login) {
	header( 'Location: index.php' );
//} else {
//	header( 'Location: index.php' );
//}

?>