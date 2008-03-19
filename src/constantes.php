<?php

	//Defino constantes para operaciones con archivos
	define('READ', "r");
	define('WRITE', 'w');
	define('APPEND', 'a');
	
	//Defino static folders
	define('HOME','home/');
	define('SERVICIOS','servicios/');
	define('CELULARES','celulares/');
	define('ENCUESTAS','encuestas/');
	define('INFO','info/');
	
	//Defino los tipos de usuario
	define('USER_SUPERVISOR', 1);
	define('USER_NORMAL', 0);
	
	//Defino las constantes de la opcion del menu elegida
	define('MENU_FILTROS', 'filtros');
	define('MENU_BIGLIST', 'biglist');
	define('MENU_MINILIST', 'minilist');
	define('MENU_VPP', 'vpp');
	define('MENU_ENCUESTAS', 'encuestas');
	define('MENU_ALTA_ENCUESTAS','altaEncuestas');
	define('MENU_RESPUESTAS','abmRespuestas');
	define('MENU_SERVICIOS', 'servicios');
	define('MENU_INFO', 'info');
	define('MNU_HOME', '');
		
	//Defino las constantes para la base de datos
	define('DB_SERVER','localhost');
	define('DB_USER','root');
	define('DB_PASS','');
	define('DB_NAME','cti');
	
	//Defino constantes para encuesta
	define('SAVE_ENC','save_encuesta');
	define('DELETE_ENC','delete_encuesta');
	define('SAVE_RTA','save_rta');
	
	//Defino un año de vida para la cookie en el cliente
	define('COOKIE_TIME',time()+60*60*24*365);
	
	define('LOGOUT',2);

?>