<?php 
	function isSelected($modifing, $celuAtrib, $valor, $type){
		if ($modifing){ 
			if ($type == ATTR_TYPE_SELECT){
				if($celuAtrib['value'] == $valor['valor'] ) echo "selected";
			} else {
				if (strpos($celuAtrib['value'],$valor['valor']) >= 0 ) echo "selected";
			}
		}		
	}
	
	
	if ($_REQUEST['subAct'] == DELETE_IMG_CEL){
		unlink("img\\".$_REQUEST['imgFile']);
		
		$qryUpd = "update servicios_atributos set value = '' where atr_id = ".$_REQUEST['foto_id']. " and celu_id = ".$_REQUEST['srv_id'];
		doInsert($qryUpd);
	}
?>

<script language="javascript">
function checkForInt(evt) {
evt = ( evt ) ? evt : window.event;
var charCode = ( evt.which ) ? evt.which : evt.keyCode
return (charCode <= 31 || charCode == 190 || (charCode >= 48 && charCode <= 57))
}

function grabar(){
/*	if (document.getElementById("marca").value == ""){
		alert("El campo marca no puede estar vacío");
		return;
	}
	if (document.getElementById("modelo").value == ""){
		alert("El campo modelo no puede estar vacío");
		return;
	}*/
	
	document.frmMain.submit();
}
</script>
<form name="frmMain" action="index.php?lbl=<?php echo MENU_ABM_SERVICIOS ?>" method="post" enctype="multipart/form-data">
<?php 
$modifing = ($_REQUEST['act'] == MODIF_CEL);
if ($modifing){
	$qryCelu = "select tecnologia, codigo_plan, descripcion from servicios where srv_id = ".$_REQUEST['srv_id'];
	$resCelu = doSelect($qryCelu);
	$celu = mysql_fetch_array($resCelu);
}
?>
<input type="hidden" name="act" value="<?php echo $modifing?UPDATE_CEL:SAVE_CEL ?>">
<input type="hidden" name="srv_id" value="<?php echo $_REQUEST['srv_id'] ?>">
<table  width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="2" align="center"> Dar de alta un servicio </td>
	</tr>
	<tr>
		<td> Codigo Plan (*) </td>
		<td> <input type="text" value="<?php if ($modifing) echo $celu['codigo_plan'] ?>" id="codigo_plan" name="codigo_plan" maxlength="6"></td>
	</tr>
	<tr>
		<td> Descripcion (*) </td>
		<td> <input type="text" value="<?php if ($modifing) echo $celu['descripcion'] ?>" id="descripcion" name="descripcion" maxlength="50"> </td>
	</tr>
	<tr>
		<td> Tecnología (*) </td>
		<td> <select name="tecnologia">
				<option value="Todas" <?php if ($modifing && 'Todas' == $celu['tecnologia']) echo "selected"?>> Todas </option>
			<?php 
				$qryTecno = "select valor from atributos_values where atr_id = ".ATTR_TYPE_TECNOLOGIA;
				$resTecno = doSelect($qryTecno);
				while ($tecno = mysql_fetch_array($resTecno)){
					?>
					<option value="<?php echo $tecno['valor']?>" <?php if ($modifing && $tecno['valor'] == $celu['tecnologia']) echo "selected"?>> <?php echo $tecno['valor'] ?></option>
					<?php
				}
			?>
		</select> </td>
	</tr>
<?php
$qryAtribs = "select atr_id,name,tipo,largo from atributos_servicios where status = 'A' order by name";
$resAtribs = doSelect($qryAtribs);
$i = 0;
while ($atrib = mysql_fetch_array($resAtribs)){
	$i++;
	
	if ($modifing){
		$qryCeluAtrib = "select value from servicios_atributos where srv_id = ".$_REQUEST['srv_id']." and atr_id = ".$atrib['atr_id'];
		$resCeluAtrib = doSelect($qryCeluAtrib);
		$celuAtrib = mysql_fetch_array($resCeluAtrib);
	}
	?>
	<tr>
	<td width="30%"> <?php echo $atrib['name'] ?> <input type="hidden" name="atrName<?php echo $i ?>" value="<?php echo $atrib['atr_id'] ?>">
		<input type="hidden" name="atrType<?php echo $i ?>" value="<?php echo $atrib['tipo'] ?>">
	</td>
	<td>
	<?php
	switch ($atrib['tipo']){
		case ATTR_TYPE_TEXT: 
		case ATTR_TYPE_NUMBER: 
		case ATTR_TYPE_MONEY: if ($atrib['tipo'] == ATTR_TYPE_MONEY) echo "$ " ?> 
								  <input type="text" name="atrValue<?php echo $i ?>" value="<?php if ($modifing) echo $celuAtrib['value'] ?>" 
								   <?php if ($atrib['largo'] != NULL) echo "maxlength=".$atrib['largo'] ?>
								   <?php if ($atrib['tipo'] == ATTR_TYPE_NUMBER || $atrib['tipo'] == ATTR_TYPE_MONEY) echo "onKeyDown=\"return checkForInt(event)\""; ?>> <?php break;
		case ATTR_TYPE_SELECT: 
		case ATTR_TYPE_MULTIPLE: ?> <select name="atrValue<?php echo $i; if ($atrib['tipo'] == ATTR_TYPE_MULTIPLE) echo "[]" ?>" <?php if ($atrib['tipo'] == ATTR_TYPE_MULTIPLE) echo "multiple" ?>>
									<?php 
										if ($atrib['tipo'] == ATTR_TYPE_MULTIPLE){
										?>
										<option value=""> Ninguno </option>
										<?php
										}
										//print_r("entre");
										$qryValores = "select valor from atributos_values where atr_id = ".$atrib['atr_id'];
										//print_r($qryValores);
										$resValores = doSelect($qryValores);
										while ($valor = mysql_fetch_array($resValores)){ ?>
											<option value="<?php echo $valor['valor'] ?>" <?php isSelected($modifing, $celuAtrib, $valor, $atrib['tipo']) ?>> <?php echo $valor['valor'] ?> </option>										
										<?php }
									?>
									</select>
									<?php break;
									
		case ATTR_TYPE_CHECKBOX: ?> <input type="checkbox" name="atrValue<?php echo $i ?>" value="Si" <?php if($modifing && $celuAtrib['value']) echo "checked" ?>> <?php break;
		case ATTR_TYPE_IMAGE: 
								if (!$modifing || $celuAtrib['value'] == "") {?> <input type="file" accept="image/*" name="atrValue<?php echo $i ?>"> 								
								<?php 
								} else {
									?>
									<img src="img/<?php echo $celuAtrib['value'] ?>" width="50" height="50">
									<a href="index.php?lbl=<?php echo MENU_ALTA_CELULARES ?>&act=<?php echo MODIF_CEL ?>&celu_id=<?php echo $_REQUEST['celu_id'] ?>&subAct=<?php echo DELETE_IMG_CEL ?>&foto_id=<?php echo $atrib['atr_id'] ?>&imgFile=<?php echo $celuAtrib['value'] ?>"> Borrar </a>
									<?php
								}
								
								break;
	
	
	}	
	echo "</td></tr>";
}
?>
<tr>
	<td colspan="2" align="center"><input type="hidden" name="cantAtrib" value="<?php echo $i ?>">
								   <input type="button" value="Grabar" onClick="javascript:grabar()">
								   <input type="button" value="Cancelar" onClick="javascrpit:location.href = 'index.php?lbl=<?php echo MENU_ABM_SERVICIOS ?>'" </td>
</tr>
</table>