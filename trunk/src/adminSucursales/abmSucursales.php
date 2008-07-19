<?php 
include("../constantes.php");
require("../funcionesDB.php");
require("../funciones.php");

if (!isSupervisor($_COOKIE['user_active'])){
	/*echo "<br><br><center><b>Acceso denegado.</b><br><br>";
	echo "<i>Para poder entrar debe loggearse en el sitio normal y volver a entrar.</i><br><br>";
	echo "<a href='../index.php'>Continuar</a></center>";*/
	header( "Location: ../index.php?lbl=".MNU_HOME."&from=ADMIN&urlTo=adminSucursales" );
	return;
}

if ($_REQUEST['act'] == SAVE_SUC){
	$insAttr = "insert into sucursales (nombre, direccion) values ('".$_REQUEST['suc_name']."','".$_REQUEST['suc_dir']."')";
	//print_r($insAttr);
	$attrID = doInsertAndGetLast($insAttr);
} else if ($_REQUEST['act'] == INACTIVATE_SUC || $_REQUEST['act'] == ACTIVATE_SUC){
	$qryDel = "update sucursales set status = '".($_REQUEST['act'] == INACTIVATE_SUC?"I":"A")."' where suc_id = ".$_REQUEST['suc_id'];
	doInsert($qryDel);
} else if ($_REQUEST['act'] == DELETE_SUC){
	$qryDel = "delete from sucursales where suc_id = ".$_REQUEST['suc_id'];
	doInsert($qryDel);
	
	$qryDelCelus = "delete from celulares_sucursales where suc_id = ".$_REQUEST['suc_id'];
	doInsert($qryDelCelus);
	
} else if ($_REQUEST['act'] == MODIF_SUC){
	$qrySel = "select nombre, direccion from sucursales where suc_id = ".$_REQUEST['suc_id'];
	$resSel = doSelect($qrySel);
	$sucursal = mysql_fetch_array($resSel);
} else if ($_REQUEST['act'] == UPDATE_SUC){
	$qryUpd = "update sucursales
			   set nombre='".$_REQUEST['suc_name']."',".
			   "direccion='".$_REQUEST['suc_dir']."' where suc_id = ".$_REQUEST['suc_id'];
	//print_r($qryUpd);
	doInsert($qryUpd);
}


	do_header_lower("Administrador de sucursales para Claro");

?>

<body>

<script language="javascript">
	
	function borrar(lnk){
			if (confirm("Está seguro que quiere borrar definitivamente la sucursal? \n Se van a borrar las dependencias de celulares")){
				window.location.href = lnk;
			}
	}
	
	function enviar(){
		if (document.getElementById("suc_name").value == ''){
			alert('El nombre no puede estar vacío');
			return;
		}
		document.frmMain.submit();
	}
</script>
<table align="center" border="0" width="768" cellpadding="0" cellspacing="0">
<tr height="20">
	<td> &nbsp; </td>
</tr>
<tr>
<td valign="top">
<form name="frmMain" action="abmSucursales.php" method="post">
<input type="hidden" id="act" name="act" value="<?php echo ($_REQUEST['act'] != MODIF_SUC)?SAVE_SUC:UPDATE_SUC ?>">
<?php if ($_REQUEST['act'] != MODIF_SUC){ ?>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td colspan="2" align="center"> Sucursales actuales </td>
</tr>
<?php
	$qrySuc = "select suc_id, nombre, status from sucursales order by nombre";
	$resSuc = doSelect($qrySuc);
	while ($suc = mysql_fetch_array($resSuc)){
	?>
		<tr>
			<td>
				<?php echo $suc['nombre'] ?>
			</td>
			<td>
				<?php if ($suc['status'] == 'A'){ ?>
				<a href="abmSucursales.php?act=<?php echo INACTIVATE_SUC ?>&suc_id=<?php echo $suc['suc_id'] ?>">Desactivar</a>
				<?php } else { ?>
				<a href="abmSucursales.php?act=<?php echo ACTIVATE_SUC ?>&suc_id=<?php echo $suc['suc_id'] ?>">Activar</a>
				<?php } ?>
				<a href="abmSucursales.php?act=<?php echo MODIF_SUC ?>&suc_id=<?php echo $suc['suc_id'] ?>">Editar</a>
				<a href="javascript:borrar('abmSucursales.php?act=<?php echo DELETE_SUC ?>&suc_id=<?php echo $suc['suc_id'] ?>')">Borrar</a>
			</td>
		</tr>
	<?php
	}
?>
</table><br>
<?php } ?>
<table width="80%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td align="center" colspan="2"> <?php  echo ($_REQUEST['act'] == MODIF_SUC)? "Modificar sucursal":"Nueva sucursal" ?> </td>
</tr>
<tr>
	<td> Nombre:</td>
	 <td>
		<input type="text" size="50" maxlength="50" name="suc_name" id="suc_name" value="<?php  if ($_REQUEST['act'] == MODIF_SUC) echo $sucursal['nombre']?>">
	</td>
</tr>
<tr>
	<td> Dirección: </td>
	<td>
		<input type="text" size="50" maxlength="100" name="suc_dir" id="suc_dir" value="<?php  if ($_REQUEST['act'] == MODIF_SUC) echo $sucursal['direccion']?>">
	</td>
</tr>
<tr>
	<td colspan="2" align="center"> <input type="button" onClick="javascript:enviar();" value="<?php echo ($_REQUEST['act'] == MODIF_SUC)?"Grabar":"Agregar" ?>"> 
					<?php if ($_REQUEST['act'] == MODIF_SUC){ ?>
						<input type="button" value="Cancelar" onClick="javascript:location.href = 'abmSucursales.php'">
						<input type="hidden" id="suc_id" name="suc_id" value="<?php echo $_REQUEST['suc_id'] ?>">
					<?php } ?>
	</td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</body>
<?php
close_html();
?>