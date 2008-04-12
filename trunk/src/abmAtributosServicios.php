<?php 
function deleteAtributosValores(){
	$qryDelValores = "delete from atributos_values where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDelValores);
}

function insertAtributosValores($attrID){
	$valores = explode(",",$_REQUEST['values']);
		
	for ($i = 0 ; $i < count($valores); $i++){
		$query = "insert into atributos_values (atr_id, valor) values (".$attrID.",'".trim($valores[$i])."')";
		doInsert($query);
	}
}

function esMultiple($type){
	return $type == ATTR_TYPE_SELECT || $type == ATTR_TYPE_MULTIPLE;
}

if ($_REQUEST['act'] == SAVE_ATTR){
	$insAttr = "insert into atributos_servicios (name, tipo, importante".(($_REQUEST['largo'] != '')?", largo)":")").
				"values ('".$_REQUEST['atr_name']."',
				'".$_REQUEST['type']."', 
				".($_REQUEST['importante']?1:0)
				.($_REQUEST['largo']==''?"":",".$_REQUEST['largo']).")";
	//print_r($insAttr);
	$attrID = doInsertAndGetLast($insAttr);

	if (esMultiple($_REQUEST['type'])){
		insertAtributosValores($attrID);
	}
} else if ($_REQUEST['act'] == INACTIVATE_ATTR || $_REQUEST['act'] == ACTIVATE_ATTR){
	$qryDel = "update atributos_servicios set status = '".($_REQUEST['act'] == INACTIVATE_ATTR?"I":"A")."' where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDel);
} else if ($_REQUEST['act'] == DELETE_ATTR){
	$qryDel = "delete from atributos_servicios where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDel);
	
	$qryDelCelus = "delete from servicios_atributos where atr_id = ".$_REQUEST['atr_id'];
	doInsert($qryDelCelus);
	
	deleteAtributosValores();
	
} else if ($_REQUEST['act'] == MODIF_ATTR){
	$qrySel = "select name, tipo, importante, largo from atributos_servicios where atr_id = ".$_REQUEST['atr_id'];
	$resSel = doSelect($qrySel);
	$atributo = mysql_fetch_array($resSel);
	$commaValores = "";
	if (esMultiple($atributo['tipo'])){
		$qryValores = "select valor from atributos_values where atr_id = ".$_REQUEST['atr_id'];
		$resValores = doSelect($qryValores) or die("error en select ".mysql_error());
		while ($valor = mysql_fetch_array($resValores)){
			if ($commaValores != ""){
				$commaValores = $commaValores.", ";
			}
			$commaValores = $commaValores.$valor['valor'];
		} 
	}
} else if ($_REQUEST['act'] == UPDATE_ATTR){
	$qryUpd = "update atributos_servicios 
			   set name='".$_REQUEST['atr_name']."', 
			   tipo='".$_REQUEST['type']."',
			   importante=".($_REQUEST['importante']?1:0).
			   ($_REQUEST['largo']==NULL?"":", largo=".$_REQUEST['largo']).
			   " where atr_id = ".$_REQUEST['atr_id'];
	//print_r($qryUpd);
	doInsert($qryUpd);
	
	if (esMultiple($_REQUEST['type'])){
		deleteAtributosValores();
		insertAtributosValores($_REQUEST['atr_id']);
	}
}


?>


<script language="javascript">
	function cambiar(combo){
		if (combo.options[combo.selectedIndex].value == 'S' || combo.options[combo.selectedIndex].value == 'SM' ){
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
	
	function enviar(){
		if (document.getElementById("atr_name").value == ''){
			alert('El nombre no puede estar vacío');
			return;
		}
		document.frmMain.submit();
	}
</script>

<form name="frmMain" action="index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS_SERVICIOS ?>" method="post">
<input type="hidden" id="act" name="act" value="<?php echo ($_REQUEST['act'] != MODIF_ATTR)?SAVE_ATTR:UPDATE_ATTR ?>">
<?php if ($_REQUEST['act'] != MODIF_ATTR){ ?>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td colspan="2" align="center"> Atributos disponibles </td>
</tr>
<?php
	$qryAttr = "select atr_id, name, status from atributos_servicios order by name";
	$resAttr = doSelect($qryAttr);
	while ($attr = mysql_fetch_array($resAttr)){
	?>
		<tr>
			<td>
				<?php echo $attr['name'] ?>
			</td>
			<td>
				<?php if ($attr['status'] == 'A'){ ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS_SERVICIOS ?>&act=<?php echo INACTIVATE_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>">Desactivar</a>
				<?php } else { ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS_SERVICIOS ?>&act=<?php echo ACTIVATE_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>">Activar</a>
				<?php } ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS_SERVICIOS ?>&act=<?php echo MODIF_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>">Editar</a>
				<a href="javascript:borrar('index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS_SERVICIOS ?>&act=<?php echo DELETE_ATTR ?>&atr_id=<?php echo $attr['atr_id'] ?>')">Borrar</a>
			</td>
		</tr>
	<?php
	}
?>
</table><br>
<?php } ?>
<table width="80%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td colspan="2" align="center"> <?php  echo ($_REQUEST['act'] == MODIF_ATTR)? "Modificar atributo":"Nuevo atributo" ?> </td>
</tr>
<tr>
	<td colspan="2"> Nombre: 
		<input type="text" size="60" name="atr_name" id="atr_name" value="<?php  if ($_REQUEST['act'] == MODIF_ATTR) echo $atributo['name']?>">
	</td>
</tr>
<tr>
	<td> Tipo de atributo: <select id="type" name="type" onChange="javascript:cambiar(this);">
						<option value="<?php echo ATTR_TYPE_TEXT ?>" <?php if ($_REQUEST['act'] == MODIF_ATTR && $atributo['tipo'] == ATTR_TYPE_TEXT) echo "selected" ?> > Texto </option>
						<option value="<?php echo ATTR_TYPE_SELECT ?>" <?php if ($_REQUEST['act'] == MODIF_ATTR && $atributo['tipo'] == ATTR_TYPE_SELECT) echo "selected" ?> > Combo </option>
						<option value="<?php echo ATTR_TYPE_NUMBER ?>" <?php if ($_REQUEST['act'] == MODIF_ATTR && $atributo['tipo'] == ATTR_TYPE_NUMBER) echo "selected" ?> > Número </option>
						<option value="<?php echo ATTR_TYPE_CHECKBOX ?>" <?php if ($_REQUEST['act'] == MODIF_ATTR && $atributo['tipo'] == ATTR_TYPE_CHECKBOX) echo "selected" ?>> Check </option>
						<option value="<?php echo ATTR_TYPE_MULTIPLE ?>" <?php if ($_REQUEST['act'] == MODIF_ATTR && $atributo['tipo'] == ATTR_TYPE_MULTIPLE) echo "selected" ?>> Multiple </option>
						<option value="<?php echo ATTR_TYPE_MONEY ?>" <?php if ($_REQUEST['act'] == MODIF_ATTR && $atributo['tipo'] == ATTR_TYPE_MONEY) echo "selected" ?>> Moneda </option>
					</select> 
					<div style=" <?php if ($_REQUEST['act'] != MODIF_ATTR || $commaValores == "") echo "display:none" ?> " id="divVal"> Ingrese los posibles valores separados por coma (,): <input type="text" name="values" id="values" value="<?php if ($_REQUEST['act'] == MODIF_ATTR) echo $commaValores ?>"> </div></td>
	<td>
		<input type="checkbox" name="importante" <?php if ($_REQUEST['act'] == MODIF_ATTR && $atributo['importante']) echo "checked"; ?>> Importante
	</td>
</tr>
<tr>
	<td colspan="2"> Largo (opcional): <input type="text" name="largo" value="<?php if ($_REQUEST['act'] == MODIF_ATTR) echo $atributos['largo'] ?>"></td></tr>
<tr>
	<td colspan="2" align="center"> <input type="button" onClick="javascript:enviar();" value="<?php echo ($_REQUEST['act'] == MODIF_ATTR)?"Grabar":"Agregar" ?>"> 
					<?php if ($_REQUEST['act'] == MODIF_ATTR){ ?>
						<input type="button" value="Cancelar" onClick="javascript:location.href = 'index.php?lbl=<?php echo MENU_ABM_ATRIBUTOS_SERVICIOS ?>'">
						<input type="hidden" id="atr_id" name="atr_id" value="<?php echo $_REQUEST['atr_id'] ?>">
					<?php } ?>
	</td>
</tr>
</table>
</form>