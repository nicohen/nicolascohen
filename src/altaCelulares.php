<script language="javascript">
function checkForInt(evt) {
evt = ( evt ) ? evt : window.event;
var charCode = ( evt.which ) ? evt.which : evt.keyCode
return (charCode <= 31 || charCode == 190 || (charCode >= 48 && charCode <= 57))
}

function grabar(){
	if (document.getElementById("marca").value == ""){
		alert("El campo marca no puede estar vacío");
		return;
	}
	if (document.getElementById("modelo").value == ""){
		alert("El campo modelo no puede estar vacío");
		return;
	}
	
	document.frmMain.submit();
}
</script>
<form name="frmMain" action="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="<?php echo SAVE_CEL ?>">
<table  width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="2" align="center"> Dar de alta un celular </td>
	</tr>
	<tr>
		<td> Marca </td>
		<td> <input type="text" value="" id="marca" name="marca" maxlength="20"></td>
	</tr>
	<tr>
		<td> Modelo </td>
		<td> <input type="text" value="" id="modelo" name="modelo" maxlength="30"> </td>
	</tr>

<?php
$qryAtribs = "select atr_id,name,tipo,largo from atributos where status = 'A'";
$resAtribs = doSelect($qryAtribs);
$i = 0;
$modifing = ($_REQUEST['act'] == MODIF_CELL);
while ($atrib = mysql_fetch_array($resAtribs)){
	$i++;
	?>
	<tr>
	<td width="30%"> <?php echo $atrib['name'] ?> <input type="hidden" name="atrName<?php echo $i ?>" value="<?php echo $atrib['atr_id'] ?>">
		<input type="hidden" name="atrType<?php echo $i ?>" value="<?php echo $atrib['tipo'] ?>">
	</td>
	<td>
	<?php
	switch ($atrib['tipo']){
		case ATTR_TYPE_TEXT: 
		case ATTR_TYPE_NUMBER: ?> <input type="text" name="atrValue<?php echo $i ?>" value="" <?php if ($atrib['largo'] != NULL) echo "maxlength=".$atrib['largo'] ?>
								   <?php if ($atrib['tipo'] == ATTR_TYPE_NUMBER) echo "onKeyDown=\"return checkForInt(event)\""; ?>> <?php break;
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
											<option value="<?php echo $valor['valor'] ?>"> <?php echo $valor['valor'] ?> </option>										
										<?php }
									?>
									</select>
									<?php break;
									
		case ATTR_TYPE_CHECKBOX: ?> <input type="checkbox" name="atrValue<?php echo $i ?>" value="Si"> <?php break;
		case ATTR_TYPE_IMAGE: ?> <input type="file" accept="image/*" name="atrValue<?php echo $i ?>"> <?php break;
	
	
	}	
	echo "</td></tr>";
}
?>
<tr>
	<td colspan="2" align="center"><input type="hidden" name="cantAtrib" value="<?php echo $i ?>"> <input type="button" value="Grabar" onClick="javascript:grabar()"> </td>
</tr>
</table>