

<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td align="center"> Ofertas actuales </td>
	</tr>
<?php
	$resOfe = doSelect("select ofe_id, titulo, filename from ofertas where status = 'A' order by fecha desc");
	while ($ofe = mysql_fetch_array($resOfe)){
	?>
		<tr>
			<td>
				<a href="../ofertas_files/<?php echo $ofe['filename']?>"><?php echo $ofe['titulo'] ?></a>
			</td>
		</tr>
	<?php
	}
?>
</table>