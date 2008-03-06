<?php 
include("constantes.php");

$language = ($_REQUEST['lang']==INGLES)?INGLES:ESPAÑOL;
?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr><td><img src="<?php echo SERVICIOS?>h_servicios_1.jpg" /></td></tr>
				<tr><td><img src="<?php echo SERVICIOS?>h_servicios_2.jpg" /></td></tr>
				<tr><td height="360" background="<?php echo TIGRE ?>fondo_celeste.jpg" valign="top" style="background-repeat: no-repeat;">
					<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" height="95%">
						<tr valign="top">
							<td>
								<div id="div_externo" STYLE="overflow:hidden;position:relative; top:20px; left:30px; width:430px; height:280px;"> 
								<div STYLE="position:relative;"> <font class="contenido"> 
								  <?php $hand=fopen(TEXT.$language."_servicios.txt",READ) or die("<center>[No hay texto cargado]</center>");
															  leer_archivo($hand);
															  cerrar_archivo($hand);
														?>
								  </font> </div>
							  </div>
							<form name="formu">
								<input type="hidden" name="zoom" value="">
							</form>
							</td>
						</tr>
						<tr valign="bottom">
							<td>
								<table align="right" border="0" cellpadding="8" cellspacing="0">
									<tr>
										<td>
											<a href="javascript:subir();"><img src="<?php echo TIGRE?>arr_up.jpg" border="0" /></a>
											<a href="javascript:bajar();"><img src="<?php echo TIGRE?>arr_dn.jpg" border="0" /></a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
		<td>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr><td><img src="<?php echo cargar_imagen(SERVICIOS,$language,'t_servicios.jpg') ?>" /></td></tr>
				<tr><td><img src="<?php echo SERVICIOS ?>p_servicios.jpg" /></td></tr>
			</table>
		</td>
	</tr>
</table>