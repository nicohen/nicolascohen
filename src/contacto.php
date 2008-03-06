<?php 
include("constantes.php");

$language = ($_REQUEST['lang']==INGLES)?INGLES:ESPAÑOL;
?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?php echo CONTACTENOS?>h_contactenos_1.jpg" /></td>
					<td><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_contactenos.jpg') ?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?php echo CONTACTENOS?>h_contactenos_2.jpg" /></td>
					<td><img src="<?php echo CONTACTENOS?>h_contactenos_3.jpg" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr valign="top">
		<td background="<?php echo CONTACTENOS?>f_celeste_completo.jpg" height="360" style="background-repeat: no-repeat;">
		<br>
			<table border="0" cellpadding="0" cellspacing="0" align="center" width="635">
					<tr>
						<td width="200"><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_nombre.jpg') ?>" /></td>
						<td width="200"><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_apellido.jpg') ?>" /></td>
						<td width="200"><img src="<?php echo CONTACTENOS?>t_hotel.jpg" /></td>
					</tr>
					<tr>
						<td width="200"><input type="text" name="t_nombre" value="" size="25"></td>
						<td width="200"><input type="text" name="t_apellido" value="" size="25"></td>
						<td width="200"><input type="text" name="t_hotel" value="" size="25"></td>
					</tr>
					<tr>
						<td height="10"></td>
						<td></td>
					</tr>
					<tr>
						<td width="200"><img src="<?php echo CONTACTENOS?>t_e-mail.jpg" /></td>
						<td width="200"><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_confirmacion.jpg') ?>" /></td>
					</tr>
					<tr>
						<td width="200"><input type="text" name="t_e-mail" value="" size="25"></td>
						<td width="200"><input type="text" name="t_confirmacion" value="" size="25"></td>
					</tr>
					<tr>
						<td height="10"></td>
						<td></td>
					</tr>
					<tr>
						<td width="200"><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_pais.jpg') ?>" /></td>
						<td width="200"><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_cant_pasaj.jpg') ?>" /></td>
					</tr>
					<tr>
						<td width="200"><input type="text" name="t_pais" value="" size="25"></td>
						<td>
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_adultos.jpg') ?>" /></td>
									<td><input type="text" name="t_adultos" value="" size="10"></td>
								</tr>
							</table>
						</td>
						<td>
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_ninos.jpg') ?>" /></td>
									<td><input type="text" name="t_ninos" value="" size="10"></td>
								</tr>
							</table>
						</td>
					</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" style="margin-left:57px;">
				<tr><td height="10"></td></tr>
				<tr><td><img src="<?php echo cargar_imagen(CONTACTENOS,$language,'t_mensaje.jpg') ?>" /></td></tr>
				<tr><td><textarea name="t_mensaje" style="height:50;width:400"></textarea></td></tr>
				<tr><td height="20"></td></tr>
				<tr><td><form name="contact"><a href=""><img id="contact_img" src="<?php echo cargar_imagen(CONTACTENOS,$language,'b_enviar_off.jpg') ?>" border="0" onMouseOver="javascript:switch_submit('<?php echo cargar_imagen(CONTACTENOS,$language,'b_enviar_on.jpg') ?>');" onMouseOut="javascript:switch_submit('<?php echo cargar_imagen(CONTACTENOS,$language,'b_enviar_off.jpg') ?>');" /></a></form></td></tr>
			</table>
		</td>
	</tr>
</table>
