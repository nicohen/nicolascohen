<?php 
//Funciones para operar con archivos y otros
require_once("funciones.php"); 
require_once("funcionesDB.php");
ob_start();

//Arma el encabezado de la pagina
do_header("Administrador de Claro");

if ($_REQUEST['switch_user']!='') {
	addCookie("user_active", $_REQUEST['switch_user']);
	header("Location: index.php");
}

if ($_REQUEST['login']=='Y') {
	addCookie("user_active", '');
	header("Location: index.php");
}

$label=get_label($_REQUEST['lbl']);

/*try {
    $error = 'La sesión expiró, vuelva a loguearse nuevamente, muchas gracias.';
    throw new Exception($error);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}*/

?>

<body background="imgs/bg.gif" style="background-repeat:repeat-x">
<br><br>
	<table align="center" border="0" width="768" cellpadding="0" cellspacing="0">
		<tr>
			<td valign="top">
				<table align="center" width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-color:gray">
					<tr>
						
						<td valign="middle" align="center" height="70"> 
							<object width="566" height="70" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
								<param value="http://www.claroargentina.com/flash/top.swf" name="movie"/>
								<param value="high" name="quality"/>
								<param value="transparent" name="wmode"/>
								<embed width="566" height="98" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" quality="high" src="http://www.claroargentina.com/flash/top.swf"/>
							</object> 
							<div style="position:absolute; left: 287px; top: 53px; width: 565px;">
						  		<h1>Administrador de Consultas para Claro</h1>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<table align="right" border="0" cellpadding="0" cellspacing="10" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
								<tr>
									<?php 
										$tok = strtok ($_COOKIE['user_ids'], ",");
										if ($tok !== false)
											echo "<td>Usuarios:<td>";
										while ($tok !== false) {
											if ($_COOKIE['user_active']==$tok) {
												echo "<td><b>".$_COOKIE['user_nickname_'.$tok]."</b> (<a href='logout.php?user_id=".$tok."'>Salir</a>)</td>";
											} else
												echo "<td><a href='index.php?switch_user=".$tok."'>".$_COOKIE['user_nickname_'.$tok]."</a> (<a href='logout.php?user_id=".$tok."'>Salir</a>)</td>";
											$tok = strtok(" \n\t");
										}
										if ($_COOKIE['user_ids']!='')
											echo "<td><a href='index.php?login=Y'>Soy otro usuario</a></td>";
									?>
								</tr>
							</table>
						</td>
					</tr>
					<?php if ($_COOKIE['user_active']!='' && !$_REQUEST['login']=='Y') { ?>
					<tr align="center">
						<td>
							<table width="100%" border="0" cellpadding="0" cellspacing="3" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
								<tr align="center">
									<td width="20%">
										<?php if($label==MENU_CELULARES_FILTROS) { ?>
											<b>Celulares</b>
										<?php } else { ?>
											<a href="index.php?lbl=<?php echo MENU_CELULARES_FILTROS ?>">Celulares</a>
										<?php } ?>
									</td>
									<?php if (!isPriceLoader($_COOKIE['user_active'])){ ?>
									<td width="20%">
										<?php if($label==MENU_SERIVICIOS_RESUME) { ?>
											<b>Servicios</b>
										<?php } else { ?>
											<a href="index.php?lbl=<?php echo MENU_SERIVICIOS_RESUME ?>">Servicios</a>
										<?php } ?>
									</td>
									<td width="20%">
										<?php if($label==MENU_ENCUESTAS) { ?>
											<b>Encuestas</b>
										<?php } else { ?>
											<a href="index.php?lbl=<?php echo MENU_ENCUESTAS ?>">Encuestas</a>
										<?php } ?>
									</td>
									<?php } 
										if (isSupervisor($_COOKIE['user_active'])) {?>
									<td width="20%">
										<?php if($label==MENU_REGISTROS) { ?>
											<b>Registros</b>
										<?php } else { ?>
											<a href="index.php?lbl=<?php echo MENU_REGISTROS ?>">Registros</a>
										<?php } ?>
									</td>
									<td width="20%">
										<?php if($label==MENU_USUARIOS || $label==MENU_USUARIOS_ALTA || $label==MENU_USUARIOS_MODIFICAR) { ?>
											<b>Usuarios</b>
   										<?php } else { ?>
											<a href="index.php?lbl=<?php echo MENU_USUARIOS ?>">Usuarios</a>
										<?php } ?>
									</td>
									<?php } ?>
								</tr>
							</table>
						</td>
					</tr>
					<?php }
					addEncOptions($label,$_COOKIE['user_active']);
					addCelularesOptions($label); 
					addServiciosOptions($label); ?>
					<tr>
						<td>
							<table width="100%" border="0" cellpadding="0" cellspacing="3">
								<tr>
									<td height="400" valign="top">
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