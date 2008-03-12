<?php 
include("constantes.php");

$language = ($_REQUEST['lang']==INGLES)?INGLES:ESPAÑOL;
?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?php echo MAPAS?>h_mapas_1.jpg" /></td>
				</tr>
				<tr>
					<td background="<?php echo MAPAS?>h_mapas_2.jpg" height="64" style="background-repeat: no-repeat;" valign="bottom" align="right">
						<!--span style="float:left;filter:alpha(opacity=25);-moz-opacity:.50;opacity:.50;"-->
							<div id="ampliar" style="overflow:hidden;position:relative;left:-40px;">
								<a href="javascript:popup_map('<?php echo MAPAS.$language?>_');"><img src="<?php echo cargar_imagen(MAPAS,$language,'b_ampliar.jpg') ?>" border="0" /></a>
							</div>
						<!--/span-->
					</td>

				</tr>
				<tr>
					<td background="<?php echo TIGRE?>fondo_celeste.jpg" style="background-repeat: no-repeat;">
						<div id="div_externo" STYLE="overflow:hidden;position:relative; top:5px; left:30px; width:450px; height:360px;"> 
						  <span id="default" style="display:none"><img src="<?php echo MAPAS ?>recorrido_vacio.jpg" /></span> 
						  <span id="spantexto" style="position:absolute;display:block;left:100px;top:130px;"> 
							  <?php
									$hand=fopen(TEXT.$lang."_maptext.txt",READ) or die("Problemas en la creacion");
									leer_archivo($hand);
									cerrar_archivo($hand);
							  ?>
						  </span> 
						  <span id="recorrido_verde" style="display:none"><img src="<?php echo cargar_imagen(MAPAS,$language,'recorrido_verde.jpg') ?>" border="0" usemap="#mapaVerde" /></span> 
						  <span id="recorrido_naranja" style="display:none"><img src="<?php echo cargar_imagen(MAPAS,$language,'recorrido_naranja.jpg') ?>" border="0" usemap="#mapaNaranja" /></span> 
						  <span id="recorrido_rojo" style="display:none"><img src="<?php echo cargar_imagen(MAPAS,$language,'recorrido_rojo.jpg') ?>" border="0" usemap="#mapaRojo" /></span> 
						  <form name="formu">
							<input type="hidden" name="zoom" value="">
						  </form>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" height="100%">
				<tr>
					<td><img src="<?php echo cargar_imagen(MAPAS,$language,'t_mapas.jpg') ?>" /></td>
				</tr>
				<tr>
					<td background="<?php echo MAPAS?>fondo_azul.jpg" height="100%" align="center" style="background-repeat: no-repeat;">
						<div style="height:425px; display: table-cell; vertical-align: middle; " >
							<table border="0" cellpadding="0" cellspacing="0" align="center" height="100%">
								<tr valign="bottom">
									<td>
										<a href="javascript:setear_mapas('recorrido_verde','recorrido_naranja','recorrido_rojo')"><img src="<?php echo cargar_imagen(MAPAS,$language,'b_descubra.jpg') ?>" border="0" /></a>
									</td>
								</tr>
								<tr valign="middle">
									<td>
										<a href="javascript:setear_mapas('recorrido_naranja','recorrido_verde','recorrido_rojo')"><img src="<?php echo cargar_imagen(MAPAS,$language,'b_explore.jpg') ?>" border="0" /></a>
									</td>
								</tr>
								<tr valign="top">
									<td>
										<a href="javascript:setear_mapas('recorrido_rojo','recorrido_naranja','recorrido_verde')"><img src="<?php echo cargar_imagen(MAPAS,$language,'b_tigre.jpg') ?>" border="0" /></a>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script language="JavaScript">setear_mapas('default','','');</script>
<map id ="mapaVerde" name="mapaVerde">
  <area shape ="rect" coords ="190,205,239,220" href ="javascript:popup_recreo('<?php echo MAPAS?>recreo_verde.jpg');" alt="Recreo Verde" />
</map>
<map id ="mapaNaranja" name="mapaNaranja">
  <area shape ="rect" coords ="154,146,203,161" href ="javascript:popup_recreo('<?php echo MAPAS?>recreo_naranja.jpg');" alt="Recreo Naranja" />
</map>
<map id ="mapaRojo" name="mapaRojo">
  <area shape ="rect" coords ="154,146,203,161" href ="javascript:popup_recreo('<?php echo MAPAS?>recreo_rojo.jpg');" alt="Recreo Rojo" />
</map>