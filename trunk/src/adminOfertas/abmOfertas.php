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

	$folder = $_REQUEST['folder'];
	if ($folder == "OT"){
		$folder = $_REQUEST['folder_other'];
	}

	$dirTo = $docRoot."/ofertas_files/".$folder."/";
	
	if (!file_exists($dirTo)){
		//mkdir($dirTo);
		
		$ftp =ftp_connect("200.110.145.5");
		if (ftp_login($ftp,"z28030806014530","UPQLVAGE")){
			//Me muevo al directorio correcto			
			ftp_chdir($ftp,"/public_html/ofertas_files/");
			
			//Pruebo crear el directorio
			if (ftp_mkdir($ftp, $folder)){
				//Cambio el modo para que todos puedan modificarlo y subir archivos
				if (ftp_site($ftp,"CHMOD 0777 /public_html/ofertas_files/".$folder."/")){
					   echo "Se ha creado la carpeta satisfactoriamente.\n";
				} else {
//				   die('No se pue');
				}
			}
		} else {
//			die ("No me pude conectar");
		}
		
	}
				
	//mkdir(dirname($dirTo),0755,true);
	//recur_mkdirs($dirTo);
	
	$origFile = $_FILES['ofeFile']['name'];
	$ext = substr($origFile,strpos($origFile,"."));
	
	$insOfe = "insert into ofertas (titulo, folder, filename, sup_id, tipo) values ('".$_REQUEST['titulo']."','".$folder."','".$nameTo."',".$_COOKIE['user_active'].",'".$_REQUEST['tipo']."')";
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
	$qryFile = "select folder, filename from ofertas where ofe_id = ".$_REQUEST['ofe_id'];
	$resFile = doSelect($qryFile);
	$file = mysql_fetch_array($resFile);
	
	$docRoot	= getDocumentRoot();
	$fileName = $docRoot."/ofertas_files/".$file['folder']."/".$file['filename'];
	if (file_exists($fileName)){
		unlink($fileName);
	}

	$qryDel = "delete from ofertas where ofe_id = ".$_REQUEST['ofe_id'];
	doInsert($qryDel);
	
} else if ($_REQUEST['act'] == PASAR_A_NOV || $_REQUEST['act'] == PASAR_A_OC){
	$qryUpd = "update ofertas set tipo = '".($_REQUEST['act']==PASAR_A_NOV ? NOVEDAD : OFERTA_COMERCIAL). "' 
			   where ofe_id = ".$_REQUEST['ofe_id'];
			   
	doInsert($qryUpd);
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
	
	function folderChange(combo){
		if (combo.options[combo.selectedIndex].value == "OT"){
			document.getElementById("divOther").style.display = "";
		} else {
			document.getElementById("divOther").style.display = "none";
		}
		
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
	<td> Elija una subcarpeta: </td>
	<td>
		<select name="folder" onChange="javascript:folderChange(this)">
			<option> Elije una opcion </option>
			<?php 
				$docRoot = getDocumentRoot();	
				$ruta = $docRoot."/ofertas_files/";
			   // abrir un directorio y listarlo recursivo
			   if (is_dir($ruta)) {
				  if ($dh = opendir($ruta)) {
					 while (($file = readdir($dh)) !== false) {
						//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
						//mostraría tanto archivos como directorios
						//echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
						if (is_dir($ruta . $file) && $file!="." && $file!=".."){
						   //solo si el archivo es un directorio, distinto que "." y ".."
						   ?>
						   <option value="<?php echo $file ?>" > <?php echo $file ?></option>
						   <?
						}
					 }
				  closedir($dh);
				  }
			   }
			 ?>
			 <option value="OT">Otra</option>
		</select>
		<div style="display:none" id="divOther">
			<font size="-3">Elija el nombre de la carpeta </font><br>
			<input type="text" name="folder_other">
		</div>
	</td>
</tr>
<tr>
	<td> Tipo:</td>
	 <td>
		<select name="tipo">
			<option value="<?php echo NOVEDAD ?>"> Novedad </option>
			<option value="<?php echo OFERTA_COMERCIAL ?>"> Oferta comercial </option>
		</select>
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
	$resOfe = doSelect("select ofe_id, titulo, status, filename, tipo, folder from ofertas order by fecha desc");
	while ($ofe = mysql_fetch_array($resOfe)){
	?>
		<tr>
			<td>
				<?php echo $ofe['titulo'] ?>
			</td>
			<td>
				<a href="../ofertas_files/<?php echo $ofe['folder']."/".$ofe['filename']?>">Ver Archivo</a>
				<?php if ($ofe['tipo'] == OFERTA_COMERCIAL) { ?>
					<a href="abmOfertas.php?act=<?php echo PASAR_A_NOV ?>&ofe_id=<?php echo $ofe['ofe_id'] ?>">Pasar a Novedad</a>
				<?php } else if($ofe['tipo'] == NOVEDAD) {?>
				<a href="abmOfertas.php?act=<?php echo PASAR_A_OC ?>&ofe_id=<?php echo $ofe['ofe_id'] ?>">Pasar a Oferta comercial</a>		
				<?php } ?>
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