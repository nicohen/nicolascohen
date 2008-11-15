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
	do_header_lower("Administrador de logos para Claro");
$docRoot = getDocumentRoot();	
if ($_REQUEST['act'] == "grabar"){
	//$dirBase = "";

	$origFile = $_FILES['logo']['name'];
	$ext = substr($origFile,strpos($origFile,"."));
	
	if ($ext != ".gif"){ 
		$error = true;
	} else {
		$tmpFoto = $_FILES['logo']['tmp_name'];

		$fileTo = $docRoot."/imgs/".$_REQUEST['marca'].$ext;
						
		move_uploaded_file($tmpFoto,$fileTo);
	}
}

?>

<body>
<?php if ($error){ ?>
	<red> No se ha subido el logo. El formato tiene que ser ".gif" </red>
<?php }?>
<table align="center" border="0" width="768" cellpadding="0" cellspacing="0">
<tr height="20">
	<td>&nbsp;  </td>
</tr>
<tr>
	<td> Las siguientes marcas no tienen asignado un logo </td>
</tr>
<table align="center" border="0" width="768" cellpadding="0" cellspacing="0">
<tr height="20">
	<td>&nbsp;  </td>
</tr>
<?php 

	$resMarca = doSelect("select distinct marca from celulares where status = 'A'");
	while ($marca = mysql_fetch_array($resMarca)){
		$filename = $docRoot."/imgs/".$marca['marca'].".gif";
		if (!file_exists($filename)){
?>
<tr>
<td valign="top">
<form name="frmMain<?php echo $marca['marca']?>" action="logoUploader.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="act" value="grabar">
	<input type="hidden" name="marca" value="<?php echo $marca['marca'];?>">
	Ingrese un Logo para <?php echo $marca['marca']?>.<br>
	<input type="file" name="logo">
	<input type="submit" value="subir">
</form>
</td>
</tr>
<?php } //Cierro IF

} //Cierro while?>
</table>
</body>
<?php
close_html();
?>