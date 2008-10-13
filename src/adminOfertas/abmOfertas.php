<?php 
include("../constantes.php");
require("../funcionesDB.php");
require("../funciones.php");

if (!isSupervisor($_COOKIE['user_active'])){
	/*echo "<br><br><center><b>Acceso denegado.</b><br><br>";
	echo "<i>Para poder entrar debe loggearse en el sitio normal y volver a entrar.</i><br><br>";
	echo "<a href='../index.php'>Continuar</a></center>";*/
	header( "Location: ../index.php?lbl=".MNU_HOME."&from=ADMIN&urlTo=adminOfertas" );
	return;
}

if ($_REQUEST['act'] == SAVE_SUC){
	$docRoot = getDocumentRoot();	
	$tmpFoto = $_FILES['ofeFile']['tmp_name'];
	//$dirBase = "";

	$dirTo = $docRoot."/ofertas_files/";
				
	//mkdir(dirname($dirTo),0755,true);
	//recur_mkdirs($dirTo);
	
	$origFile = $_FILES['ofeFile']['name'];
	$ext = substr($origFile,strpos($origFile,"."));
	
	$insOfe = "insert into ofertas (titulo, filename, sup_id) values ('".$_REQUEST['titulo']."','".$nameTo."',".$_COOKIE['user_active'].")";
	$ofeID = doInsertAndGetLast($insOfe);
	
	$fileTo = $dirTo.$ofeID.$ext;
	
	doInsert("update ofertas set filename = '".$ofeID.$ext."' where ofe_id = ".$ofeID);
//	echo $fileTo;
	
	//print_r($fileTo);			
	//print_r($origFile);
	//print_r($tmpFoto."->".$fileTo);
	move_uploaded_file($tmpFoto,$fileTo);			

	//print_r($insAttr);
} else if ($_REQUEST['act'] == INACTIVATE_SUC || $_REQUEST['act'] == ACTIVATE_SUC){
	$qryDel = "update ofertas set status = '".($_REQUEST['act'] == INACTIVATE_SUC?"I":"A")."' where ofe_id = ".$_REQUEST['ofe_id'];
	doInsert($qryDel);
} else if ($_REQUEST['act'] == DELETE_SUC){
	$qryFile = "select filename from ofertas where ofe_id = ".$_REQUEST['ofe_id'];
	$resFile = doSelect($qryFile);
	$file = mysql_fetch_array($resFile);
	
	$docRoot	= getDocumentRoot();
	$fileName = $docRoot."/ofertas_files/".$file['filename'];
	if (file_exists($fileName)){
		unlink($fileName);
	}

	$qryDel = "delete from ofertas where ofe_id = ".$_REQUEST['ofe_id'];
	doInsert($qryDel);
	
}
	do_header_lower("Administrador de ofertas para Claro");

?>

<body>
<script language="javascript">
	
	function borrar(lnk){
			if (confirm("Está seguro que quiere borrar definitivamente la oferta? \n Se van a borrar los archivos asociados")){
				window.location.href = lnk;
			}
	}
	
	function enviar(){
		if (document.getElementById("titulo").value == ''){
			alert('El titulo no puede estar vacío');
			return;
		}
		document.frmMain.submit();
	}
</script>
<table align="center" border="0" width="768" cellpadding="0" cellspacing="0">
<tr height="20">
	<td>&nbsp;  </td>
</tr>
<tr>
<td valign="top">
<form action="abmOfertas.php" name="frmMain" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="<?php echo SAVE_SUC ?>" />
<table width="80%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td align="center" colspan="2"> Dar de alta una oferta</td>
</tr>
<tr>
	<td> Título:</td>
	 <td>
		<input type="text" size="50" maxlength="255" name="titulo" id="titulo">
	</td>
</tr>
<tr>
	<td> Ingrese un archivo asociado: </td>
	<td>
		<input type="file" name="ofeFile" id="ofeFile">
	</td>
</tr>
<tr>
	<td colspan="2" align="center"> <input type="button" onClick="javascript:enviar();" value="Grabar"> </td>
</tr>
</table>
</form>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
<tr bgcolor="#FFCC99">
	<td colspan="2" align="center"> Ofertas actuales </td>
</tr>
<?php
	$resOfe = doSelect("select ofe_id, titulo, status, filename from ofertas order by fecha desc");
	while ($ofe = mysql_fetch_array($resOfe)){
	?>
		<tr>
			<td>
				<?php echo $ofe['titulo'] ?>
			</td>
			<td>
				<a href="../ofertas_files/<?php echo $ofe['filename']?>">Ver Archivo</a>
				<?php if ($ofe['status'] == 'A'){ ?>
				<a href="abmOfertas.php?act=<?php echo INACTIVATE_SUC ?>&ofe_id=<?php echo $ofe['ofe_id'] ?>">Desactivar</a>
				<?php } else { ?>
				<a href="abmOfertas.php?act=<?php echo ACTIVATE_SUC ?>&ofe_id=<?php echo $ofe['ofe_id'] ?>">Activar</a>
				<?php } ?>
				<a href="javascript:borrar('abmOfertas.php?act=<?php echo DELETE_SUC ?>&ofe_id=<?php echo $ofe['ofe_id'] ?>')">Borrar</a>
			</td>
		</tr>
	<?php
	}
?>
</table><br>
</td></tr></table>
</body>
<?php
close_html();
?>