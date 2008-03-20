
<?php
$query="select name,last_name,nickname,pwd,super,status from usuarios where user_id=".$_REQUEST['user_id'];
$result = doSelect($query);
while ($res=mysql_fetch_array($result)) {
	$name=$res['name'];
	$last_name=$res['last_name'];
	$nickname=$res['nickname'];
	$password=$res['pwd'];
	$super=$res['super'];
	$status=$res['status'];
}
?>

<script language='javascript'>
function validarCampos() {
	if (document.modifUsuario.name.value!='' && document.modifUsuario.last_name.value!=''
	&& document.modifUsuario.nickname.value!='' && document.modifUsuario.password.value!='')
		document.modifUsuario.submit();
	else 
		alert('Debe completar todos los campos.');
}
</script>

<form action="index.php?lbl=<?php echo MENU_USUARIOS; ?>&modifUser=Y&user_id=<?php echo $_REQUEST['user_id']; ?>" method="post" name="modifUsuario">
	<br><br>
	<center><u>NUEVO USUARIO</u></center>
	<br>
	<table border="0" cellpadding="3" cellspacing="5" align="center">
		<tr>
			<td>Nombre</td>
			<td><input type="text" name="name" value="<?php echo $name ?>" maxlength="20"></td>
		</tr>
		<tr>
			<td>Apellido</td>
			<td><input type="text" name="last_name" value="<?php echo $last_name ?>" maxlength="30"></td>
		</tr>
		<tr>
			<td>Apodo</td>
			<td><input type="text" name="nickname" value="<?php echo $nickname ?>" maxlength="20"></td>
		</tr>
		<tr>
			<td>Contraseña</td>
			<td><input type="text" name="password" value="<?php echo $password ?>" maxlength="20"></td>
		</tr>
		<tr>
			<td>Supervisor</td>
			<td><input type="checkbox" name="super" <?php if ($super) echo "checked"; ?>></td>
		</tr>
		<tr>
			<td>Estado</td>
			<td>
				<select name="status">
					<option value="A" <?php if ($status == 'A') echo "selected"; ?>>Activo</option>
					<option value="I" <?php if ($status == 'I') echo "selected"; ?>>Inactivo</option>
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
