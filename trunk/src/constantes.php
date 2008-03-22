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
	define('MENU_RES_ENCUESTAS','resultadosEncuestas');
	define('MENU_RES_RESULTADOS','resEncuesta');
	define('MENU_RESPUESTAS','abmRespuestas');
	define('MENU_SERVICIOS', 'servicios');
	define('MENU_REGISTROS', 'registros');
	define('MENU_USUARIOS', 'usuarios');
	define('MENU_USUARIOS_ALTA', 'usuariosAgregar');
	define('MENU_USUARIOS_MODIFICAR', 'usuariosModificar');
	define('MENU_ABM_ATRIBUTOS','atributos');
	define('MNU_HOME', '');
	define('MENU_CELULARES_FILTROS','celularesFiltros');
	define('MENU_CELULARES_LISTADO','celularesListado');
	define('MENU_REGISTROS','registros');
		
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
	
	//Constantes de los tipos de atributos
	define('ATTR_TYPE_TEXT','T');
	define('ATTR_TYPE_SELECT','S');
	define('ATTR_TYPE_NUMBER','N');
	define('ATTR_TYPE_CHECKBOX','CH');	
	define('ATTR_TYPE_IMAGE','I');
	define('ATTR_TYPE_MULTIPLE','SM');
	
	//Constatntes para acciones de atributos
	define('SAVE_ATTR','save_attr');
	define('INACTIVATE_ATTR','inactivate_attr');
	define('DELETE_ATTR','delete_attr');
	define('ACTIVATE_ATTR','activate_attr');
	define('MODIF_ATTR','modif_attr');
	define('UPDATE_ATTR','update_attr');
	
	define('LOGIN',1);
	define('LOGOUT',2);

?>