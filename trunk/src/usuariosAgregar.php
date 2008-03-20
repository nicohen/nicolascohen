<?php
include ("constantes.php");
?>

<form action="index.php?lbl=<?php MENU_USUARIOS_ALTA ?>" method="post">
	<br><br><br>
	<center><u>NUEVO USUARIO</u></center>
	<br>
	<table border="0" cellpadding="3" cellspacing="5" align="center">
		<tr>
			<td>Nombre</td>
			<td><input type="text" name="name" value="" maxlength="20"></td>
		</tr>
		<tr>
			<td>Apellido</td>
			<td><input type="text" name="last_name" value="" maxlength="30"></td>
		</tr>
		<tr>
			<td>Apodo</td>
			<td><input type="text" name="nickname" value="" maxlength="20"></td>
		</tr>
		<tr>
			<td>Contraseña</td>
			<td><input type="password" name="password" value="" maxlength="20"></td>
		</tr>
		<tr>
			<td>Supervisor</td>
			<td><input type="checkbox" name="super"></td>
		</tr>
		<tr>
			<td>Estado</td>
			<td>
				<select name="status">
					<option value="A">Activo</option>
					<option value="I">Inactivo</option>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Continuar"></td>
		</tr>
	
	</table>
</form>