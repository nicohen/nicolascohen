<?php 
include("constantes.php");

$language = ($_REQUEST['lang']==INGLES)?INGLES:ESPAÑOL;
?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr><td><img src="<?php echo HOME?>main_image.gif" /><img src="<?php echo cargar_imagen(HOME,$language,"paragraph.gif") ?>" /></td></tr>
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?php echo HOME?>image_1.gif" /></td>
					<td><img src="<?php echo HOME?>image_2.gif" /></td>
					<td><img src="<?php echo HOME?>image_3.gif" /></td>
				</tr>
				<tr>
					<td><img src="<?php echo cargar_imagen(HOME,$language,"text_1.gif") ?>" /></td>
					<td><img src="<?php echo cargar_imagen(HOME,$language,"text_2.gif") ?>" /></td>
					<td><img src="<?php echo cargar_imagen(HOME,$language,"text_3.gif") ?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>