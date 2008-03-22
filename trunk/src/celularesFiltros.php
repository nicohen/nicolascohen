<center><h4><u>Consulta de celulares</u></h4></center>

<form action="index.php?lbl=<?php echo MENU_CELULARES_LISTADO ?>" method="post">
	<table border="1" align="center" cellpadding="3" cellspacing="0" width="400">
		<tr><td><b>Elija los criterios:</b></td></tr>
		<tr>
			<td>
				<table border="0" align="center" cellpadding="3" cellspacing="0">
				
				<?php
				
				$query = "select marca from celulares where status='A' order by marca";
				$result = doSelect($query);
				echo "<tr><td align='right' width='100'>Marca:</td><td align='left'><select name='marca' multiple>";
				while ($res = mysql_fetch_array($result)) {
					echo "<option value='".$res['marca']."'>".$res['marca']."</option>";
				}
				echo "</select></td></tr>";

				$query = "select distinct ca.atr_id,a.name,a.tipo,ca.value from atributos a, celulares_atributos ca where a.atr_id=ca.atr_id and filter=1 order by a.name";
				$result = doSelect($query);
				//Valida el cambio de type para abrir o cerrar el select
				$isCombo=false;
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
								echo "<option value='".$tok."'>".$tok."</option>";
								$tok = strtok(" \n\t");
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
						echo "<tr><td align='right' width='100'>".$res['name'].":</td><td align='left'><input type='checkbox' name='atr".$res['atr_id']."'></td></tr>";
					}
				}
				if ($isCombo)
					echo "</select></td></tr>";
				?>
				<tr>
					<td height="10"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="consultar" value="Buscar"></td>
				</tr>
				
				</table>
			</td>
		</tr>
	</table>
</form>