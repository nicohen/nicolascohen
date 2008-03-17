<?php 
//Funciones para operar con archivos y otros
require_once("funciones.php"); 
require_once("funcionesDB.php");
ob_start();

//Arma el encabezado de la pagina
do_header("Administrador de Claro");

if ($_REQUEST['login']==true) {
	$login = check_login($_REQUEST['usr'], $_REQUEST['pwd']);
	if ($login) {
		echo "logueado ok<br>";
	} else {
		echo "usuario ya logueado o inexistente<br>";
	}
}

?>

<body bgcolor="#F0F0F0">
<style type="text/css"> 
<!--
.azul {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}
.gris {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #666666;}
.naranja {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: orange;}
.contenido {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0904A6; }
-->
</style>

<?php

if (isset($_COOKIE['user_ids']))
	echo "user_ids: ".$_COOKIE['user_ids'];

//setcookie("user_ids", "", time()-1000,"/");
?>


	<table align="center" border="0" width="768" cellpadding="0" cellspacing="10">
		<tr>
			<td valign="top">
				<table align="center" width="100%" border="1" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center" height="70">
							Administrador de Consultas para Claro
						</td>
					</tr>
					<tr>
						<td>
							<table align="right" border="0" cellpadding="0" cellspacing="10">
								<tr>
									<?php 
										$tok = strtok ($_COOKIE['user_ids'], ",");
										while ($tok !== false) {
											echo '<td align="right">'.$_COOKIE['user_name_'.$tok].' (<a href="logout.php?user_id='.$tok.'">Salir</a>)</td>';
											$tok = strtok(" \n\t");
										}
									?>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="140">
									<?php if ($login) { ?>
										<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
											<tr><td>Celulares</td></tr>
											<tr><td>Encuestas</td></tr>
											<tr><td>Servicios</td></tr>
											<tr><td>Informacion</td></tr>
											<tr><td>Otros</td></tr>
										</table>
									<?php }?>
									</td>
									<td>
										<?php do_content($language,$label); ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top" align="center">
							<!--Page Footer-->
							<?php do_footer($language); ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
<?php
close_html();
?>