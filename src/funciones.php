<?php

include("constantes.php");

Function get_label($label) {
	//Si el label no es ninguno, setea MENU_FILTROS por default
	return ($label!='') ? $label:'';
}

Function do_header($titulo) {
	echo "<html><head><title>".$titulo."</title>";
	include("javascript.php");
	?>
	<!-- estilos -->
	<style type="text/css"> 
	<!--
	.azul {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}
	.gris {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #666666;}
	.naranja {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: orange;}
	.contenido {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0904A6; }
	-->
	</style>
	<link rel='stylesheet' type='text/css' href='styles/stylesClaro.css' />
	<?php
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
	//print_r($lbl);
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
	else if($lbl==MENU_ALTA_ENCUESTAS)
		include("altaEncuestas.php");
	else if($lbl==MENU_RESPUESTAS)
		include("ABMRespuestas.php");
	else if($lbl==MENU_SERVICIOS)
		include("servicios.php");
	else if($lbl==MENU_INFO)
		include("info.php");
	else if($lbl==MENU_USUARIOS)
		include("usuarios.php");
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

function isEncuestasSubSection($label){
	if ($label == MENU_ALTA_ENCUESTAS)
		return true;
	else if ($label == MENU_RESPUESTAS)
		return true;
		
	return false;
}
	
function appendEncRow($valor, $texto){
	echo "<tr><td> <li><a href=\"/cti/src/index.php?lbl=".$valor."\">". $texto ."</a></li></td></tr>";
}
	
	
function addEncOptions($label,$user_id){
	if ($_COOKIE['user_super_'.$user_id] == USER_SUPERVISOR){
		if ($label == MENU_ENCUESTAS || isEncuestasSubSection($label)){
			appendEncRow(MENU_ALTA_ENCUESTAS, "Dar de alta una encuesta");
			appendEncRow(MENU_RESPUESTAS, "Ver respuestas posibles");
		}
	}
}

function addUserOptions($label,$user_id){
	if ($_COOKIE['user_super_'.$user_id] == USER_SUPERVISOR){
		if ($label == MENU_ENCUESTAS || isEncuestasSubSection($label)){
			appendEncRow(MENU_ALTA_ENCUESTAS, "Dar de alta una encuesta");
			appendEncRow(MENU_RESPUESTAS, "Ver respuestas posibles");
		}
	}
}


?>