<?php 

//Preload de las marcas

$queryMarcas = "select distinct marca from celulares where status='A' order by marca";
$resultMarcas = doSelect($queryMarcas); 
$i = 0;
while ($marcaRes = mysql_fetch_array($resultMarcas)) {
	$marcas[$i] = $marcaRes['marca'];
	$i++;
}

?>
<script>
function SetCookie(cookieName,cookieValue) {
	var value = cookieValue;
	var name = cookieName;
	var d = new Date("January 31, 2009");
	var cd = d.toGMTString();
	var c = escape(name)+"="+escape(value)+";expires="+cd;
	document.cookie = c;
}

function searchMarca(marca){
	document.getElementById("marcaSola").value = marca;
	document.frmSubMarca.submit();
}

function goToCeluPage(combo){
	var celuID = combo.options[combo.selectedIndex].value;
	if (celuID != ""){
		document.frmSubCelus.celu_id.value = celuID;
		document.frmSubCelus.submit();
	}
}
</script>

<center><h4><u>Consulta de celulares</u></h4></center>

<form action="index.php?lbl=<?php echo MENU_CELULARES_LISTADO ?>&list=1" method="post" name="frmMain">
	<table border="0" align="left" width="100%">
		<tr>
			<td width="184" valign="top">
				<table width="184" align="center">
					<?
						$rows = 0;
	//					echo count($marcas);
						for (; $rows < count($marcas)/2; $rows++) {?>
							<tr>
								<td width="184" height="30" align="center"> <a href="javascript:searchMarca('<?php echo $marcas[$rows] ?>')"> <img border="0" src="imgs/<?php echo $marcas[$rows] ?>.gif"> </a> 
								</td>
							</tr>
						<?php  } ?>
				</table>
			</td>
			<td>
			
				<table width="428" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="428" colspan="3"><img src="imgs/top5.jpg" border="0" width="428" height="28"></td>
					</tr>
					<tr>
						<td background="imgs/left_bg.jpg" width="8" style="background-repeat:repeat-y;"></td>
						<td>
	
							<table border="0" align="center" cellpadding="3" cellspacing="0" width="400" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
								<tr><td>
									<table width="100%" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
										<tr>
											<td> <b> Elija un celular para acceder directamente: </b> </td>
											<td> <select name="celu_id" onchange="javascript:goToCeluPage(this)"> 
												<option value=""> Opciones... </option>
											<?php 
												$resCelus = doSelect("select celu_id, marca, modelo from celulares where status = 'A' order by marca, modelo");
												while ($celu = mysql_fetch_array($resCelus)){
													?>
													<option value="<?php echo $celu['celu_id'] ?>"> <?php echo $celu['marca']." ".$celu['modelo'] ?> </option>
													<?php
												}
											 ?> </select> </td>
										</tr>
									</table>
									<table width="100%" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
										<tr> <td> <b>O realice una b�squeda:</b> </td> <td align="right"> <input type="submit" name="consultar" value="Buscar"> </td> </tr>
									</table>
								</td></tr>
								<tr>
									<td>
										<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
										<!--tr>
											<td align="right">
												Elija una sucursal:
											</td>
											<td>
												<select name="sucursal" onChange="document.frmSubMarca.sucursal.value=this.options[this.selectedIndex].value;">
													<?php
													$qrySucursales = "select suc_id, nombre from sucursales where status = 'A'";
													$resSuc = doSelect($qrySucursales);
													while ($suc = mysql_fetch_array($resSuc)){
														?>
														<option value="<?php echo $suc['suc_id'] ?>" <?php if ($suc['suc_id'] == $_COOKIE['sucursal_'.$_COOKIE['user_active']]) echo "selected"; ?>> <?php echo $suc['nombre'] ?> </option>
														<?php
													}
													?>
													<option value="all" <?php if ($_COOKIE['sucursal_'.$_COOKIE['user_active']] == 'all') echo "selected"; ?>> Todas </option>
												</select>
											</td>
										</tr-->
										<?php
										echo "<tr><td align='right' width='100'>Marca:</td><td align='left'><select name='marcas[]' multiple size=\"6\">";
										for ($iMarca = 0; $iMarca < count($marcas); $iMarca++){
											echo "<option value='".$marcas[$iMarca]."'>".$marcas[$iMarca]."</option>";
										}
										echo "</select></td></tr>";
										
										?>
										<tr>
											<td align="right"> Precio Pre-pago: </td>
											<td> Desde <input type="text" name="precio_prepago_min" size="4"> hasta <input size="4" type="text" name="precio_prepago_max"></td>
										</tr>
										<tr>
											<td align="right"> Precio Post-pago: </td>
											<td> Desde <input type="text" name="precio_postpago_min" size="4"> hasta <input size="4" type="text" name="precio_postpago_max"></td>
										</tr>
										<?php
						
										$query = "select distinct ca.atr_id,a.name,a.tipo,ca.value from atributos a, celulares_atributos ca 
												  where a.atr_id=ca.atr_id 
												  and filter=1 
												  and trim(ca.value) != ''
												  and a.tipo != '".ATTR_TYPE_IMAGE."'
												  and a.status='A'
												  order by a.peso, a.name, ca.value";
										$result = doSelect($query);
										//Valida el cambio de type para abrir o cerrar el select
										$isCombo=false;
										//Nombre del ultimo checkbox utilizado
										$lastCheckbox = "";
										//Nomber del �ltimo combo utilizado
										$lastMultiple = "";
										$multivalue = "";
										while ($res = mysql_fetch_array($result)) {											
											//Este $nextType vale 2
											if ($res['tipo']==ATTR_TYPE_CHECKBOX) {
												if ($isCombo) {
													echo "</select></td></tr>";
													$isCombo=false;
												}
												if ($lastCheckbox!=$res['name']) {
													echo "<tr><td align='right' width='100'>".$res['name'].":</td><td align='left'><input type='checkbox' name='atr".$res['atr_id']."'></td></tr>";
													$lastCheckbox = $res['name'];
												}
												
												$lastMultiple = "";
											//} if ($res['tipo']==ATTR_TYPE_TEXT || $res['tipo']==ATTR_TYPE_SELECT || $res['tipo']==ATTR_TYPE_NUMBER || $res['tipo']==ATTR_TYPE_MULTIPLE) {
											//Este $nextType vale 1
											} else {
												if ($lastMultiple != "" && $lastMultiple != $res['name']){
													echo "</select></td></tr>";
													$isCombo=false;
												}
											
												if (!$isCombo) {
													echo "<tr><td align='right' width='100'>".$res['name'].":</td><td align='left'><select name='atr".$res['atr_id']."[]' multiple size=\"6\">";
													$isCombo=true;
												}
												/*if ($res['tipo']==ATTR_TYPE_MULTIPLE) {
													$tok = strtok($res['value'], ",");
													while ($tok !== false) {
						//							echo "[".strpos($multivalue,"#".$tok."#")."]";
														if (!is_numeric(strpos($multivalue,"#".$tok."#"))) {
															echo "<option value='".$tok."'>".$tok."</option>";
															$multivalue = $multivalue."#".$tok."#";
														}
														$tok = strtok(" ,");
													}
												} else {*/
													echo "<option value='".$res['value']."'>".$res['value']."</option>";
												//}
												$lastMultiple = $res['name'];
											}
										}
										if ($isCombo)
											echo "</select></td></tr>";
											
										SetCookie("compare1_".$_COOKIE['user_active'],"-1");
										SetCookie("compare2_".$_COOKIE['user_active'],"-1");
										SetCookie("compare3_".$_COOKIE['user_active'],"-1");
										SetCookie("compare4_".$_COOKIE['user_active'],"-1");
										SetCookie("compare_cant_".$_COOKIE['user_active'],"0");
										setcookie("celulares_".$_COOKIE['user_active'],"");
										setcookie("list_".$_COOKIE['user_active'],"");
										//setcookie("compare_".$_COOKIE['user_active'],"",time()*365*24*60*60,"/");
										?>
										<tr>
											<td height="10"></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2" valign="top" align="right">
												<input type="submit" name="consultar" value="Buscar">
											</td>
										</tr>
										
										</table>
									</td>
								</tr>
							</table>
						</td>
						<td background="imgs/right_bg.jpg" width="11"></td>
					</tr>
					<tr>
						<td valign="top"><img src="imgs/c1.jpg" border="0" width="8" height="9" alt=""></td>
						<td valign="top" background="imgs/bottom_bg.jpg"></td>
						<td valign="top"><img src="imgs/c2.jpg" border="0" width="11" height="9" alt=""></td>
					</tr>			
		
				</table>
	
			</td>
			<td width="184" valign="top">
				<table width="184" align="center">
					<?php for (;$rows < count($marcas); $rows++){?>
						<tr>
							<td width="184" height="30" align="center"> <a href="javascript:searchMarca('<?php echo $marcas[$rows] ?>')"> <img border="0" src="imgs/<?php echo $marcas[$rows] ?>.gif"> </a> 
							</td>
						</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
	</table>
</form>
<form action="index.php?lbl=<?php echo MENU_CELULARES_LISTADO ?>&list=1" name="frmSubMarca" method="post">
	<input type="hidden" name="marcas[]" id="marcaSola" value="">
	<input type="hidden" name="sucursal" id="sucursal" value="">
</form>
<form action="index.php" name="frmSubCelus" method="get" target="_blank">
	<input type="hidden" name="lbl" value="<?php echo MENU_VPP ?>" />
	<input type="hidden" name="celu_id" />
</form>

<script language="javascript">
	document.frmSubMarca.sucursal.value=document.frmMain.sucursal.options[document.frmMain.sucursal.selectedIndex].value;
</script>