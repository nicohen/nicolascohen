
<script language='javascript'>
function validarCampos() {
	if (document.altaUsuario.name.value!='' && document.altaUsuario.last_name.value!=''
	&& document.altaUsuario.nickname.value!='' && document.altaUsuario.password.value!='')
		document.altaUsuario.submit();
	else 
		alert('Debe completar todos los campos.');
}
</script>

<form action="index.php?lbl=<?php echo MENU_USUARIOS ?>&newUser=Y" method="post" name="altaUsuario">
	<br><br>
	<center><u>NUEVO USUARIO</u></center>
	<br>
	<table border="0" cellpadding="3" cellspacing="5" align="center">
		<tr>
			<td>Nombre</td>
			<td><input type="text" name="name" value="<?php echo $_REQUEST['name'] ?>" maxlength="20"></td>
		</tr>
		<tr>
			<td>Apellido</td>
			<td><input type="text" name="last_name" value="<?php echo $_REQUEST['last_name'] ?>" maxlength="30"></td>
		</tr>
		<tr>
			<td>Apodo</td>
			<td><input type="text" name="nickname" value="<?php echo $_REQUEST['nickname'] ?>" maxlength="20"></td>
		</tr>
		<tr>
			<td>Contraseña</td>
			<td><input type="text" name="password" value="<?php echo $_REQUEST['password'] ?>" maxlength="20"></td>
		</tr>
		<tr>
			<td>Supervisor</td>
			<td><input type="checkbox" name="super" <?php if ($_REQUEST['super']) echo "checked"; ?>></td>
		</tr>
		<tr>
			<td>Estado</td>
			<td>
				<select name="status">
					<option value="A" <?php if ($_REQUEST['status'] == 'A') echo "selected"; ?>>Activo</option>
					<option value="I" <?php if ($_REQUEST['status'] == 'I') echo "selected"; ?>>Inactivo</option>
				</select>
			</td>
		</tr>
		<tr><td height="10"></td><td></td></tr>
		<tr>
			<td align="center"><input type="button" value="Continuar" onclick="javascript:validarCampos();"></td>
			<td align="center"><input type="button" value="Cancelar" onclick="javascript:window.location.href='index.php?lbl=<?php echo MENU_USUARIOS ?>'"></td>
		</tr>
	
	</table>
</form>

<?php 
echo "<script>";
if ($_REQUEST['err']=='1')
	echo "alert('El apodo ya existe, ingrese otro.');";
echo "</script>";
?>
