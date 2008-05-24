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
</script>

<center><h4><u>Consulta de celulares</u></h4></center>

<form action="index.php?lbl=<?php echo MENU_CELULARES_LISTADO ?>&list=1" method="post">
	<table border="0" align="left" width="100%">
	<tr>
		<td width="184" valign="top">
			<table width="184" align="center">
				<?php
					$queryCountMarcas = "select count(1) from (
												select distinct marca from celulares c where status='A') t";
					$resultCountMarcas = doSelect($queryCountMarcas);
					$countRes = mysql_fetch_array($resultCountMarcas);
					$cantMarcas = $countRes[0];
					
					//echo "Cantidad de marcas: ".$cantMarcas;
					
					$queryMarcas = "select distinct marca from celulares where status='A' order by marca";
					$resultMarcas = doSelect($queryMarcas); 
					$rows = 1;
					while (($rows <= $cantMarcas / 2) && ($marcaRes = mysql_fetch_array($resultMarcas))) {?>
						<tr>
							<td width="184" height="30" align="center"> <a href="javascript:searchMarca('<?php echo $marcaRes['marca'] ?>')"> <img border="0" src="imgs/<?php echo $marcaRes['marca'] ?>.gif"> </a> 
							</td>
						</tr>
					<?php $rows++; } ?>
			</table>
		</td>
		<td>
		<table border="1" align="center" cellpadding="3" cellspacing="0" width="400" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
			<tr><td>
				<table width="100%" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
					<tr> <td> <b>Elija los criterios:</b> </td> <td align="right"> <input type="submit" name="consultar" value="Buscar"> </td> </tr>
				</table>
			</td></tr>
			<tr>
				<td>
					<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" style="font:Arial, Helvetica, sans-serif; font-size:13px;">
					
					<?php
					
					$query = "select distinct marca from celulares where status='A' order by marca";
					$result = doSelect($query);
					echo "<tr><td align='right' width='100'>Marca:</td><td align='left'><select name='marcas[]' multiple>";
					while ($res = mysql_fetch_array($result)) {
						echo "<option value='".$res['marca']."'>".$res['marca']."</option>";
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
							  order by a.peso, a.name, ca.value";
					$result = doSelect($query);
					//Valida el cambio de type para abrir o cerrar el select
					$isCombo=false;
					//Nombre del ultimo checkbox utilizado
					$lastCheckbox = "";
					$multivalue = "";
					while ($res = mysql_fetch_array($result)) {
						//Este $nextType vale 1
						if ($res['tipo']==ATTR_TYPE_TEXT || $res['tipo']==ATTR_TYPE_SELECT || $res['tipo']==ATTR_TYPE_NUMBER || $res['tipo']==ATTR_TYPE_MULTIPLE) {
							if (!$isCombo) {
								echo "<tr><td align='right' width='100'>".$res['name'].":</td><td align='left'><select name='atr".$res['atr_id']."' multiple>";
								$isCombo=true;
							}
							if ($res['tipo']==ATTR_TYPE_MULTIPLE) {
								$tok = strtok($res['value'], ",");
								while ($tok !== false) {
	//							echo "[".strpos($multivalue,"#".$tok."#")."]";
									if (!is_numeric(strpos($multivalue,"#".$tok."#"))) {
										echo "<option value='".$tok."'>".$tok."</option>";
										$multivalue = $multivalue."#".$tok."#";
									}
									$tok = strtok(" ,");
								}
							} else {
								echo "<option value='".$res['value']."'>".$res['value']."</option>";
							}
						//Este $nextType vale 2
						} else if ($res['tipo']==ATTR_TYPE_CHECKBOX) {
							if ($isCombo) {
								echo "</select></td></tr>";
								$isCombo=false;
							}
							if ($lastCheckbox!=$res['name']) {
								echo "<tr><td align='right' width='100'>".$res['name'].":</td><td align='left'><input type='checkbox' name='atr".$res['atr_id']."'></td></tr>";
								$lastCheckbox = $res['name'];
							}
						}
					}
					if ($isCombo)
						echo "</select></td></tr>";
						
					SetCookie("compare1_".$_COOKIE['user_active'],"-1");
					SetCookie("compare2_".$_COOKIE['user_active'],"-1");
					SetCookie("compare3_".$_COOKIE['user_active'],"-1");
					SetCookie("compare4_".$_COOKIE['user_active'],"-1");
					SetCookie("compare_cant_".$_COOKIE['user_active'],"0");
					setcookie("celulares_".$_COOKIE['user_active'],"",time()*365*24*60*60,"/");
					setcookie("list_".$_COOKIE['user_active'],"",time()*365*24*60*60,"/");
					//setcookie("compare_".$_COOKIE['user_active'],"",time()*365*24*60*60,"/");
	
					?>
					<tr>
						<td height="10"></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="2" valign="top" align="center">
						<input type="submit" name="consultar" value="Buscar">
						</td>
					</tr>
					
					</table>
				</td>
			</tr>
		</table>
	</td>
	<td width="184" valign="top">
		<table width="184" align="center">
			<?php while ($marcaRes = mysql_fetch_array($resultMarcas)){?>
				<tr>
					<td width="184" height="30" align="center"> <a href="javascript:searchMarca('<?php echo $marcaRes['marca'] ?>')"> <img border="0" src="imgs/<?php echo $marcaRes['marca'] ?>.gif"> </a> 
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
</form>