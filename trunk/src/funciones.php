<?php

include("constantes.php");

Function get_label($label) {
	//Si el label no es ninguno, setea MENU_FILTROS por default
	return ($label!='') ? $label:'';
}

Function do_header($titulo) {
	echo "<html><head><title>".$titulo."</title>";
	
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
	if ($_COOKIE['user_active']!='' || $lbl=='') {
		if($lbl==MENU_CELULARES_FILTROS)
			include("celularesFiltros.php");
		else if($lbl==MENU_CELULARES_FILTROS)
			include("celularesListado.php");
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
		else if($lbl==MENU_RES_ENCUESTAS)
			include("resultadosEncuestas.php");
		else if($lbl==MENU_RES_RESULTADOS)
			include("resEncuesta.php");
		else if($lbl==MENU_RESPUESTAS)
			include("ABMRespuestas.php");
		else if($lbl==MENU_SERVICIOS)
			include("servicios.php");
		else if($lbl==MENU_INFO)
			include("info.php");
		else if($lbl==MENU_USUARIOS)
			include("usuarios.php");
		else if($lbl==MENU_USUARIOS_ALTA)
			include("usuariosAgregar.php");
		else if($lbl==MENU_USUARIOS_MODIFICAR)
			include("usuariosModificar.php");
		else if($lbl==MENU_ABM_ATRIBUTOS)
			include("abmAtributos.php");
		else if($lbl==MENU_USUARIOS_MODIFICAR)
			include("usuariosModificar.php");
		else if($lbl==MENU_REGISTROS)
			include("registros.php");
		else if($lbl==MENU_ALTA_CELULARES)
			include("altaCelulares.php");
		else if($lbl==MANU_ABM_CELULARES)
			include("abmCelulares.php");

		else
			include("home.php");
	} else {
		echo "<br><br><center><b>Acceso denegado.</b><br><br><a href='index.php'>Continuar</a></center>";
	}
}

//Footer comun de la pagina
Function do_footer($lang) {
	$hand=fopen("text/footer.txt",READ) or die("Problemas en el footer");
	leer_archivo($hand);
	cerrar_archivo($hand);
}

Function cargar_imagen($folder, $ruta) {
	if (file_exists($path))
		return $path;
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
	setcookie($name,$value,time()+60*60*10,"/");
}

function isEncuestasSubSection($label){
	if ($label == MENU_ALTA_ENCUESTAS)
		return true;
	else if ($label == MENU_RESPUESTAS)
		return true;
	else if ($label == MENU_RES_ENCUESTAS)
		return true;
	else if ($label == MENU_RES_RESULTADOS)
		return true;

		
	return false;
}
	
function appendEncRow($valor, $texto){
	echo "<td> <a href=\"/cti/src/index.php?lbl=".$valor."\">". $texto ."</a></td>";
}

function isSupervisor($user_id){
	return $_COOKIE['user_super_'.$user_id] == USER_SUPERVISOR;
}	
	
function addEncOptions($label,$user_id){
	if (isSupervisor($user_id)){
		if ($label == MENU_ENCUESTAS || isEncuestasSubSection($label)){
			echo "<tr><td><table width='100%' border='0' cellpadding='0' cellspacing='3'><tr align='center'>";
			appendEncRow(MENU_ALTA_ENCUESTAS, "Dar de alta");
			appendEncRow(MENU_RESPUESTAS, "Ver respuestas");
			appendEncRow(MENU_RES_ENCUESTAS,"Ver resultados");
			echo "</tr></table></td></tr>";
		}
	}
}

?>