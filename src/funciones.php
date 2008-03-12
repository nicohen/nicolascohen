<?php

include("constantes.php");

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

Function check_login($usr, $pwd) {
	$query = "select 1 from usuarios where nickname=".$usr." and pwd=".$pwd."";
}

?>
