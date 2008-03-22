<center><h4><u>Consulta de celulares</u></h4></center>
<form action="index.php?lbl=<?php echo MENU_CELULARES_LISTADO; ?>" method="post">
	<table border="1" align="center" cellpadding="3" cellspacing="0">
	<?php
	$query = "select distinct ca.atr_id,a.name,a.tipo,ca.value from atributos a, celulares_atributos ca where a.atr_id=ca.atr_id and filter=1 order by a.name";
	$result = doSelect($query);
	
	//Valida el cambio de type para abrir o cerrar el select
	$isCombo=false;
	while ($res = mysql_fetch_array($result)) {
		//Este $nextType vale 1
		if ($res['tipo']==ATTR_TYPE_TEXT || $res['tipo']==ATTR_TYPE_SELECT || $res['tipo']==ATTR_TYPE_NUMBER || $res['tipo']==ATTR_TYPE_MULTIPLE) {
			if (!$isCombo) {
				echo "<tr><td align='right'>".$res['name'].":</td><td align='left'><select name='atr".$res['atr_id']."' multiple>";
				$isCombo=true;
			}
			echo "<option value='".$res['value']."'>".$res['value']."</option>";
		//Este $nextType vale 2
		} else if ($res['tipo']==ATTR_TYPE_CHECKBOX) {
			if ($isCombo) {
				echo "</select></td></tr>";
				$isCombo=false;
			}
			echo "<tr><td align='right'>".$res['name'].":</td><td align='left'><input type='checkbox' name='atr".$res['atr_id']."'></td></tr>";
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
</form>