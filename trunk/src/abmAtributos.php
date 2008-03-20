<?php 

if ($_REQUEST['act'] == SAVE_ATTR){
	$insAttr = "insert into atributos (name, filter, tipo".(($_REQUEST['largo'] != '')?", largo)":")").
				"values ('".$_REQUEST['atr_name']."',".($_REQUEST['filter']?1:0).",'".$_REQUEST['type']."'".($_REQUEST['largo']==''?"":",".$_REQUEST['largo']).")";
	//print_r($insAttr);
	$attrID = doInsertAndGetLast($insAttr);

	if ($_REQUEST['type'] == ATTR_TYPE_SELECT){
		$valores = explode(",",$_REQUEST['values']);
		
		for ($i = 0 ; $i < count($valores); $i++){
			$query = "insert into atributos_values (atr_id, valor) values (".$attrID.",'".$valores[$i]."')";
			doInsert($query);
		}
	}
} else if ($_REQUEST['act'] == INACTIVATE_ATTR){
	$qryDel = "update atributos set status = 'I' where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDel);
} else if ($_REQUEST['act'] == DELETE_ATTR){
	$qryDel = "delete from atributos where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDel);
	
	$qryDelCelus = "delete from celulares_atributos where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDelCelus);
	
	$qryDelValores = "delete from atributos_values where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDelValores);
} else if ($_REQUEST['act'] == MODIF_ATTR){
	$qrtSel = "select name, filter, type, largo from atributos where atr_id = ".$_REQUEST['atr_id'];
	//do
}


?>


<script language="javascript">
	function cambiar(combo){
		if (combo.options[combo.selectedIndex].value == 'S'){
			document.getElementById("divVal").style.display="";
		} else {
			document.getElementById("values").value = "";
			document.getElementById("divVal").style.display="none";
		}
	}
	
	function borrar(lnk){
			if (confirm("Está seguro que quiere borrar definitivamente el atributo? \n Se van a borrar las dependencias de celulares")){
				window.location.href = lnk;
			}
	}
</script>

<form name="frmMain" action="/cti/src/index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS ?>&act=<?php echo SAVE_ATTR ?>" method="post">
<?php if ($_REQUEST['act'] != MODIF_ATTR){ ?>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td colspan="2" align="center"> Atributos disponibles </td>
</tr>
<?php
	$qryAttr = "select atr_id, name, status from atributos";
	$resAttr = doSelect($qryAttr);
	while ($attr = mysql_fetch_array($resAttr)){
	?>
		<tr>
			<td>
				<?php echo $attr['name'] ?>
			</td>
			<td>
				<?php if ($attr['status'] == 'A'){ ?>
				<a href="/cti/src/index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS ?>&act=<?php echo INACTIVATE_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>">Desactivar</a>
				<?php } else { ?>
				<a href="/cti/src/index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS ?>&act=<?php echo ACTIVATE_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>">Activar</a>
				<?php } ?>
				<a href="/cti/src/index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS ?>&act=<?php echo MODIF_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>">Editar</a>
				<a href="javascript:borrar('/cti/src/index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS ?>&act=<?php echo DELETE_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>')">Borrar</a>
			</td>
		</tr>
	<?php
	}
?>
</table><br>
<?php } ?>
<table width="80%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td colspan="2" align="center"> Nuevo atributo </td>
</tr>
<tr>
	<td colspan="2"> Nombre: <input type="text" name="atr_name" id="atr_name" value=""></td></tr>
<tr>
	<td colspan="2"> <input type="checkbox" name="filter" value="1"> Filtro</td></tr>
<tr>
	<td colspan="2"> Tipo de atributo: <select id="type" name="type" onChange="javascript:cambiar(this);">
						<option value="<?php echo ATTR_TYPE_TEXT ?>"> Texto </option>
						<option value="<?php echo ATTR_TYPE_SELECT ?>"> Combo </option>
						<option value="<?php echo ATTR_TYPE_NUMBER ?>"> Número </option>
						<option value="<?php echo ATTR_TYPE_CHECKBOX ?>"> Check </option>
					</select> 
					<div style="display:none" id="divVal"> Ingrese los posibles valores separados por coma (,): <input type="text" name="values" value=""> </div></td>
</tr>
<tr>
	<td colspan="2"> Largo (opcional): <input type="text" name="largo" value=""></td></tr>
<tr>
	<td colspan="2"> <input type="submit" value="agregar"> </td>
</tr>
</table>
</form>