<?php 

include("constantes.php");

if ($_COOKIE['user_super_'.$_COOKIE['user_active']]==USER_SUPERVISOR) { ?>
<br>
<form action="index.php" method="post">
	<table border="1" cellpadding="3" cellspacing="0" align="center" style="border-collapse:collapse;border-color:gray">
		<tr bgcolor="#FFCC99">
			<td align='center'>Nombre</td>
			<td align='center'>Apellido</td>
			<td align='center'>Usuario</td>
			<td align='center'>Rol</td>
			<td align='center'>Estado</td>
			<td align='center'>Fecha de creacion</td>
			<td align='center'>Acciones</td>
		</tr>
		<?php
		$query = "select user_id, name, last_name, nickname, super, status, ins_dt from usuarios";
		$result = doSelect($query) or die("Error en select ".mysql_error());
		while ($res = mysql_fetch_array($result)) {
			echo "<tr>";
			echo "<td>".$res['name']."</td>";
			echo "<td>".$res['last_name']."</td>";
			echo "<td>".$res['nickname']."</td>";
			if ($res['super'])
				echo "<td><b>Supervisor</b></td>";
			else
				echo "<td>Empleado</td>";
			if($res['status']=='A')
				echo "<td><font color='#009900'>Activo</font></td>";
			else
				echo "<td><font color='#FF0000'>Inactivo</font></td>";
			echo "<td>".$res['ins_dt']."</td>";
			echo "<td align='center	'><a href='index.php?lbl=usuariosModificar&user_id=".$res['user_id']."'><img src='imgs/b_edit.png' alt='Modificar' border='0'></a> <a href='index.php?lbl=usuariosEliminar&user_id=".$res['user_id']."'><img src='imgs/b_drop.png' alt='Eliminar' border='0'></a></td>";
			echo "</tr>";
		}
		?>
	</table>
	<br>
	<center><a href="index.php?lbl=usuariosAgregar">Agregar nuevo usuario</a></center>
</form>
<?php } else echo "<br><br><center><b>Acceso denegado</b></center>";?>