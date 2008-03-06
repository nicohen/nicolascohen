<?php 
include("constantes.php");

$language = ($_REQUEST['lang']==INGLES)?INGLES:ESPAÑOL;
$label = get_label($_REQUEST['lbl']);
$imagen = ($_REQUEST['img']!='')?$_REQUEST['img']:'';

?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?php echo IMAGENES?>h_fotos1.jpg" /></td>
				</tr>
				<tr>
					<td><img src="<?php echo IMAGENES?>h_fotos2.jpg" /></td>
				</tr>
				<tr>
					<td background="<?php echo TIGRE?>fondo_celeste.jpg" style="background-repeat: no-repeat;">
						<div style="height:350px; margin-left:40px;margin-top:10px;">
							<?php
								if (file_exists(FOTOS.$imagen.'.jpg'))
									echo "<img src='".FOTOS.$imagen.".jpg' border='0' />";
							?>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" height="100%">
				<tr>
					<td><img src="<?php echo cargar_imagen(IMAGENES,$language,'t_fotos.jpg') ?>" /></td>
				</tr>
				<tr>
					<td background="<?php echo MAPAS?>fondo_azul.jpg" height="100%" align="center" style="background-repeat: no-repeat;">
						<div style="height:425px;">
							<form name="opaca">
								<table border="0" cellpadding="0" cellspacing="0" align="center" height="300" width="200" style="margin-top:70px">
										<?php
											$path = MINIFOTOS;
											$dir = opendir($path);
											$fotodos=false;
											while ($elemento = readdir($dir)) {
												$extensiones = explode(".",$elemento) ;
												$nombre = $extensiones[1] ;
												$tipo = array ("jpg");
												if(in_array($nombre, $tipo)) {
													if ($fotodos==false) echo "<tr valign='top'>";
													if ($fotodos==true) {
														$fotodos=false;
														if ($imagen == substr($elemento,0,strpos($elemento,'.'))) {
															echo "<td align='center'><a href='index.php?lbl=".$label."&lang=".$language."&img=".substr($elemento,0,strpos($elemento,'.'))."'>";
															echo "<span style='width: 100%; opacity:.30;filter: alpha(opacity=30); -moz-opacity: 0.3;border:0px solid black;'>";
															echo "<img id='".$elemento."' src='".MINIFOTOS.$elemento."' border='0' /></span></a></td>";															
														} else {
															echo "<td align='center'><a href='index.php?lbl=".$label."&lang=".$language."&img=".substr($elemento,0,strpos($elemento,'.'))."'><img id='".$elemento."' src='".MINIFOTOS.$elemento."' border='0' /></a></td>";
														}
														echo "</tr>" ;
													} else {
														$fotodos=true;
														if ($imagen == substr($elemento,0,strpos($elemento,'.'))) {
															echo "<td align='center'><a href='index.php?lbl=".$label."&lang=".$language."&img=".substr($elemento,0,strpos($elemento,'.'))."'>";
															echo "<span style='width: 100%; opacity:.30;filter: alpha(opacity=30); -moz-opacity: 0.3;border:0px solid black;'>";
															echo "<img id='".$elemento."' src='".MINIFOTOS.$elemento."' border='0' /></span></a></td>";															
														} else {
															echo "<td align='center'><a href='index.php?lbl=".$label."&lang=".$language."&img=".substr($elemento,0,strpos($elemento,'.'))."'><img id='".$elemento."' src='".MINIFOTOS.$elemento."' border='0' /></a></td>";
														}
													}
												}
											}
											if ($fotodos==true) echo "</tr>" ;
											closedir($dir);
										?>
								</table>
							</form>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>