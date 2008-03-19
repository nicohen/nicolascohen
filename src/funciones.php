<?php

//include("constantes.php");

Function get_label($label) {
	//Si el label no es ninguno, setea MENU_FILTROS por default
	if ($label!='') 
		return $label;
	else
		return '';
}

Function do_header($titulo) {
	echo "<html><head><title>".$titulo."</title>";
	include("javascript.php");
	echo "</head>";
}

Function close_html() {
	echo "</html>";
}

Function abrir_archivo($nombre,$modo) {
	return fopen($nombre,$modo) or die("Problemas en la creacion");
}

Function leer_archivo($handler) {
	if ($handler!='')
		while (!feof($handler)) {
			$linea=fgets($handler);
			$lineasalto=nl2br($linea);
			echo $lineasalto;
		}
}

Function cerrar_archivo($handler) {
	fclose($handler);
}

Function do_content($lang,$lbl) {
	if($lbl==MENU_FILTROS)
		include("filtros.php");
	else if($lbl==MENU_BIGLIST)
		include("biglist.php");
	else if($lbl==MENU_MINILIST)
		include("minilist.php");
	else if($lbl==MENU_VPP)
		include("vpp.php");
	else if($lbl==MENU_ENCUESTAS)
		include("encuestas.php");
	else if($lbl==MENU_SERVICIOS)
		include("servicios.php");
	else if($lbl==MENU_INFO)
		include("info.php");
	else
		include("home.php");
}

//Footer comun de la pagina
Function do_footer($lang) {
	$hand=fopen("text/footer.txt",READ) or die("Problemas en la creacion");
	leer_archivo($hand);
	cerrar_archivo($hand);
}

Function cargar_imagen($folder, $language, $ruta) {
	if ($language==ESPAÑOL) {
		$path = $folder."sp_".$ruta;
		if (file_exists($path))
			return $path;
	} else {
		$path = $folder."en_".$ruta;
		if (file_exists($path))
			return $path;
	}
	return $folder.$ruta;
}

Function leer_directorio() {
	$path = IMAGENES.'src';
	$dir = opendir($path);
	while ($elemento = readdir($dir)) {
		$extensiones = explode(".",$elemento) ;
		$nombre = $extensiones[0] ;
		$nombre2  = $extensiones[1] ;
		$tipo = array ("jpg");
		if(in_array($nombre2, $tipo)) {
			echo "$elemento<br>" ;
		}
	}
	closedir($dir);
}

Function addCookie($name, $value) {
	setcookie($name,$value,date(), "cookies");
}

Function setear_cookies_usuario($res) {
	setcookie("user_name_".$res['user_id'], $res['name'], time()+60*60*24*365, "/");
	setcookie("user_last_name_".$res['user_id'], $res['last_name'], time()+60*60*24*365, "/");
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
	//else
		//setcookie("user_ids", $res['user_id'], time()+60*60*24*365, "/");
	
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
			if (!esta_logueado($res)) {
				setear_cookies_usuario($res);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
}
	
	
	/*
		$posicion = strrpos($_COOKIE['user_ids'], $res['user_id']);
			if ($posicion === false) {
				setear_cookies_usuario($res);
				return true;
			} else {
				return false;
			}
		}
			
	} else
		return false;*/
?>