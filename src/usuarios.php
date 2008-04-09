<?php 

if ($_REQUEST['newUser']=='Y') {
	$query_select = "select 1 from usuarios where nickname='".$_REQUEST['nickname']."'";
	$result = doSelect($query_select) or die("Error en select ".mysql_error());
	if ($res = mysql_fetch_array($result)) {
		header("Location: index.php?lbl=".MENU_USUARIOS_ALTA."&name=".$_REQUEST['name']."&last_name=".$_REQUEST['last_name']."&nickname=".$_REQUEST['nickname']."&password=".$_REQUEST['password']."&super=".(($_REQUEST['super'])?1:0)."&status=".$_REQUEST['status']."&err=1");
	} else {
		$query = "insert into usuarios (name,last_name,nickname,pwd,super,status) values ('".$_REQUEST['name']."',
				 '".$_REQUEST['last_name']."','".$_REQUEST['nickname']."','".$_REQUEST['password']."',".(($_REQUEST['super'])?1:0).",
				 '".$_REQUEST['status']."')";
		doInsert($query);
	}
}

if ($_REQUEST['modifUser']=='Y') {
	$query_select = "select 1 from usuarios where nickname='".$_REQUEST['nickname']."' and user_id!=".$_REQUEST['user_id']."";
	$result = doSelect($query_select) or die("Error en select ".mysql_error());
	if ($res = mysql_fetch_array($result)) {
		header("Location: index.php?lbl=".MENU_USUARIOS_MODIFICAR."&name=".$_REQUEST['name']."&last_name=".$_REQUEST['last_name']."&nickname=".$_REQUEST['nickname']."&password=".$_REQUEST['password']."&super=".(($_REQUEST['super'])?1:0)."&status=".$_REQUEST['status']."&err=1");
	} else {
	
		$query = "update usuarios set name='".$_REQUEST['name']."', last_name='".$_REQUEST['last_name']."', 
				  nickname='".$_REQUEST['nickname']."', pwd='".$_REQUEST['password']."', super=".(($_REQUEST['super'])?1:0).",
				  status='".$_REQUEST['status']."' where user_id=".$_REQUEST['user_id']."";
		doInsert($query);
	}
}

if ($_REQUEST['inactUser']=='Y') {
	$query = "update usuarios set status='I' where user_id=".$_REQUEST['user_id']."";
	doInsert($query);
}

if ($_REQUEST['actUser']=='Y') {
	$query = "update usuarios set status='A' where user_id=".$_REQUEST['user_id']."";
	doInsert($query);
}


if ($_COOKIE['user_super_'.$_COOKIE['user_active']]==USER_SUPERVISOR) { ?>
<br>
<form action="index.php" method="post">
	<center><h4><u>Administrador de Usuarios</u></h4></center>
	<table border="1" cellpadding="3" cellspacing="0" align="center" style="border-collapse:collapse;border-color:gray">
		<tr bgcolor="#FFCC99">
			<td align='center'>Usuario</td>
			<td align='center'>Password</td>
			<td align='center'>Nombre</td>
			<td align='center'>Apellido</td>
			<td align='center'>Cargo</td>
			<td align='center'>Estado</td>
			<td align='center'>Fecha de creacion</td>
			<td align='center'>Acciones</td>
		</tr>
		<?php
		$query = "select user_id, name, last_name, nickname, pwd, super, status, DATE_FORMAT(ins_dt,'%d/%m/%Y') as fecha from usuarios order by nickname";
		$result = doSelect($query) or die("Error en select ".mysql_error());
		while ($res = mysql_fetch_array($result)) {
			echo "<tr>";
			echo "<td><b>".$res['nickname']."</b></td>";
			echo "<td>".$res['pwd']."</td>";
			echo "<td>".$res['name']."</td>";
			echo "<td>".$res['last_name']."</td>";
			if ($res['super'])
				echo "<td><i>Supervisor</i></td>";
			else
				echo "<td>Empleado</td>";
			if($res['status']=='A')
				echo "<td><font color='#009900'>Activo</font></td>";
			else
				echo "<td><font color='#FF0000'>Inactivo</font></td>";
				
			echo "<td>".$res['fecha']."</td>";
			echo "<td align='center	'><a href='index.php?lbl=usuariosModificar&user_id=".$res['user_id']."'><img src='imgs/b_edit.png' alt='Modificar' border='0'></a>";
//			if (!$res['super'] || $_COOKIE['user_active']==$res['user_id']) {
				if ($res['status']=='A')
					echo " <a href='index.php?lbl=".MENU_USUARIOS."&inactUser=Y&user_id=".$res['user_id']."'><img src='imgs/b_usrdrop.png' alt='Inactivar' border='0'></a></td>";
				else
					echo " <a href='index.php?lbl=".MENU_USUARIOS."&actUser=Y&user_id=".$res['user_id']."'><img src='imgs/b_usrcheck.png' alt='Activar' border='0'></a></td>";
			echo "</tr>";
		}
		?>
	</table>
	<br>
	<center><a href="index.php?lbl=usuariosAgregar">Agregar nuevo usuario</a></center>
</form>
<?php } else echo "<br><br><center><b>Acceso denegado.</b><br><br><a href='index.php'>Continuar</a></center>"; ?>