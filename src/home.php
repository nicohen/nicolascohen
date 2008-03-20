<?php 
if ($_COOKIE['user_active']=='') { 
?>
<br>
<form action="login.php" method="post">
	<table border="1" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="4">
					<tr>
						<td>Usuario:</td>
						<td><input type="text" name="usr" value="" /></td>
					</tr>
					<tr>
						<td>Contraseña:</td>
						<td><input type="password" name="pwd" value="" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" name="login" value="Continuar"></td>
					</tr>
				</table>
				
			</td>
		</tr>
	</table>
</form>
<?php } ?>