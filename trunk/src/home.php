<?php 
if ($_COOKIE['user_active']=='' || $_REQUEST['login']=='Y') { 
?>
<br><br><br><br><br><br>
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
<?php } else {?>
<table width="100%" height="100%">
<tr>
	<td align="center" width="50%"> <img src="imgs/mapa_claro.gif"> </td>
	<td align="center" width="50%">  <object width="182" height="210" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
										<param value="imgs/prddrande.swf" name="movie"/>
										<param value="high" name="quality"/>
										<param value="transparent" name="wmode"/>
										<embed width="182" height="210" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" quality="high" src="imgs/prddrande.swf"/>
</object> </td>
	
</tr>

</table>
<?php } ?>