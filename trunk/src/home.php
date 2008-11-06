<?php 
if ($_COOKIE['user_active']=='' || $_REQUEST['login']=='Y') { 
?>
<br><br><br><br><br><br>

<table width="328" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr><td width="328" colspan="3"><img src="imgs/top4.png" border="0" width="328" height="27" alt=""></td></tr>
	<tr>
		<td background="imgs/left_bg.jpg" width="8"></td>
		<td width="409" valign="top" style="font-family:arial" bgcolor="white" style="color: black; font-size: 7pt; font-family: verdana">
			<form action="login.php" method="post">
				<?php 
					if ($_REQUEST['from'] == 'ADMIN'){
						?>
						<input type="hidden" name="urlTo" value="<?php echo $_REQUEST['urlTo'] ?>">
						<input type="hidden" name="from" value="<?php echo $_REQUEST['from'] ?>">
						<?php						
					}
				?>
				<table border="0" cellpadding="0" cellspacing="4" align="center">
					<tr><td height="10"></td></tr>
					<tr>
						<td>Usuario:
							<?php 
								if ($_REQUEST['from'] == 'ADMIN'){
									?>
									<br>
									<i><font size="-4">Para entrar debe ser administrador</font></i>
									<?
								}
							?>
						</td>
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
			</form>
		</td>

		<td background="imgs/right_bg.jpg" width="11"></td>
	</tr>
	<tr>
		<td valign="top"><img src="imgs/c1.jpg" border="0" width="8" height="9" alt=""></td>
		<td valign="top" background="imgs/bottom_bg.jpg"></td>
		<td valign="top"><img src="imgs/c2.jpg" border="0" width="11" height="9" alt=""></td>
	</tr>			

</table>
	
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