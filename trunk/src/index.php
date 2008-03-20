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
	<table align="center" border="0" width="768" cellpadding="0" cellspacing="10">
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
												<td><table border="1" cellpadding="2" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;border-style:inset"><tr><td>
												<?php echo '<b>'.$_COOKIE['user_nickname_'.$tok].'</b> (<a href="logout.php?user_id='.$tok.'">Salir</a>)'; ?>
												</td></tr></table></td>
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
					<tr>
						<?php if ($_COOKIE['user_active']!='' && !$_REQUEST['login']=='Y') { ?>
						<td width="140" valign="middle">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
								<tr align="center">
									<td>Celulares</td>
									<td>Servicios</td>
									<td><a href="/cti/src/index.php?lbl=<?php echo MENU_ENCUESTAS ?>">Encuestas</a></td>
									
									<?php if ($_COOKIE['user_super_'.$_COOKIE['user_active']]==true) {?>
									<td><a href="/cti/src/index.php?lbl=<?php echo MENU_REGISTROS ?>">Registros</a></td>
									<td><table border="1" style="border-collapse:collapse;border-style:inset"><tr><td><a href="/cti/src/index.php?lbl=<?php echo MENU_USUARIOS ?>">Usuarios</a></td></tr></table></td>
									<?php } ?>
								</tr>
							</table>
						</td>
						<?php }?>
					</tr>
					<tr>
						<td>
							<table width="100%" border="0" cellpadding="0" cellspacing="3">
								<tr align="center">
									<?php addEncOptions($label,$_COOKIE['user_active']) ?>
								</tr>
							</table>
						</td>
					</tr>
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