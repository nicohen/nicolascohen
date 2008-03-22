<?php if ($_COOKIE['user_super_'.$_COOKIE['user_active']]==USER_SUPERVISOR) { ?>
<br>
<center>
	<h4><u>Registro de Acciones</u></h4>
	Seleccione el usuario: 
	<select name="usuarios" onChange="window.location.href=this.value">
		<?php 
			$query = "select user_id, nickname, super, status from usuarios order by status, super, nickname";
			$result = doSelect($query);
			while ($res = mysql_fetch_array($result)) {
				echo "<option value='index.php?lbl=".MENU_REGISTROS."&user_id=".$res['user_id']."'".(($res['user_id']==$_REQUEST['user_id'])?"selected":"").">".$res['nickname']." (".(($res['super']=='1')?'Supervisor':'Vendedor')." ".(($res['status']=='A')?'Activo':'Inactivo').")</option>";
			}
		?>
	</select>
	<br><br>
	<?php if ($_REQUEST['user_id']!='') {?>
	<table border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse;border-color:gray">
		<tr bgcolor="#FFCC99">
			<td align="center">Fecha</td>
			<td align="center">Detalle 1</td>
			<td align="center">Detalle 2</td>
		</tr>
		<?php 
			$query = "select l.ins_dt as fecha,a.descr as desc1,l.descr as desc2 from logs as l,acciones as a, usuarios as u where l.act_id=a.act_id and l.user_id=u.user_id and l.user_id=".$_REQUEST['user_id']." order by fecha desc";
			$result = doSelect($query);
			while ($res = mysql_fetch_array($result)) {
				echo "<tr>";
				echo "<td>".$res['fecha']."</td>";
				echo "<td>".$res['desc1']."</td>";
				echo "<td>".$res['desc2']."</td>";
				echo "</tr>";
			}
		?>
	</table>
	<?php } ?>
</center>
<?php } else echo "<br><br><center><b>Acceso denegado.</b><br><br><a href='index.php'>Continuar</a></center>";?>