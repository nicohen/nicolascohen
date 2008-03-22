<?php 
//Funciones para operar con archivos y otros
require_once("funciones.php"); 
require_once("funcionesDB.php");
ob_start();

//Arma el encabezado de la pagina
do_header("Administrador de Claro");

if ($_REQUEST['switch_user']!='') {
	setcookie("user_active", $_REQUEST['switch_user'], time()+60*60*24*365, "/");	
	header("Location: index.php");
}

if ($_REQUEST['login']=='Y') {
	setcookie("user_active", '', time()+60*60*24*365, "/");
	header("Location: index.php");
}

$label=get_label($_REQUEST['lbl']);

?>

<body background="imgs/bg.gif" style="background-repeat:repeat-x">
<br>
	<table align="center" border="0" width="768" cellpadding="0" cellspacing="0">
		<tr>
			<td valign="top">
				<table align="center" width="100%" border="1" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center" height="70">
							<h1>Administrador de Consultas para Claro</h1>
						</td>
					</tr>
					<tr>
						<td>
							<table align="right" border="0" cellpadding="0" cellspacing="10">
								<tr>
									<?php 
										$tok = strtok ($_COOKIE['user_ids'], ",");
										if ($tok !== false)
											echo "<td>Usuarios:<td>";
										while ($tok !== false) {
											if ($_COOKIE['user_active']==$tok) {?>
												<td><b><?php echo $_COOKIE['user_nickname_'.$tok]; ?> </b> (<a href="logout.php?user_id='<?php echo $tok; ?>'">Salir</a>)</td>
												<?php
											} else
												echo '<td><a href="index.php?switch_user='.$tok.'">'.$_COOKIE['user_nickname_'.$tok].'</a> (<a href="logout.php?user_id='.$tok.'">Salir</a>)</td>';
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
							<table width="100%" border="0" cellpadding="0" cellspacing="3">
								<tr align="center">
									<td width="20%">
										<?php if($label==MENU_CELULARES) { ?>
											<b>Celulares</b>
										<?php } else { ?>
											<a href="/cti/src/index.php?lbl=<?php echo MENU_CELULARES ?>">Celulares</a>
										<?php } ?>
									</td>
									<td width="20%">
										<?php if($label==MENU_SERVICIOS) { ?>
											<b>Servicios</b>
										<?php } else { ?>
											<a href="/cti/src/index.php?lbl=<?php echo MENU_SERVICIOS ?>">Servicios</a>
										<?php } ?>
									</td>
									<td width="20%">
										<?php if($label==MENU_ENCUESTAS) { ?>
											<b>Encuestas</b>
										<?php } else { ?>
											<a href="/cti/src/index.php?lbl=<?php echo MENU_ENCUESTAS ?>">Encuestas</a>
										<?php } ?>
									</td>
									<?php if ($_COOKIE['user_super_'.$_COOKIE['user_active']]==true) {?>
									<td width="20%">
										<?php if($label==MENU_REGISTROS) { ?>
											<b>Registros</b>
										<?php } else { ?>
											<a href="/cti/src/index.php?lbl=<?php echo MENU_REGISTROS ?>">Registros</a>
										<?php } ?>
									</td>
									<td width="20%">
										<?php if($label==MENU_USUARIOS || $label==MENU_USUARIOS_ALTA || $label==MENU_USUARIOS_MODIFICAR) { ?>
											<b>Usuarios</b>
   										<?php } else { ?>
											<a href="/cti/src/index.php?lbl=<?php echo MENU_USUARIOS ?>">Usuarios</a>
										<?php } ?>
									</td>
									<?php } ?>
								</tr>
							</table>
						</td>
					</tr>
					<?php }
					addEncOptions($label,$_COOKIE['user_active']) ?>
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