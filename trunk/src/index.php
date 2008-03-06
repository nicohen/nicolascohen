<?php 
//Funciones para operar con archivos y otros
require("funciones.php"); 

//Arma el encabezado de la pagina
do_header("Administrador de Claro");

$language = ($_REQUEST['lang']==INGLES)?INGLES:ESPAÑOL;
$label = get_label($_REQUEST['lbl']);
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
	<table align="center" border="1" width="768" cellpadding="0" cellspacing="10">
		<tr>
			<td valign="top">
				<table align="center" width="100%" border="1" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center" height="100">
							hola
						</td>
					</tr>
					<tr>
						<td>
							<table width="100%" border="1" cellpadding="0" cellspacing="0">
								<tr>
									<td width="140">
										<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
											<tr><td>uno</td></tr>
											<tr><td>dos</td></tr>
											<tr><td>tres</td></tr>
											<tr><td>cuatro</td></tr>
											<tr><td>cinco</td></tr>
										</table>
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