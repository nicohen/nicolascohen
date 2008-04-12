<?php  
	$qryCel = "select srv_id, tecnologia,codigo_plan,descripcion from servicios where status = 'A'";
	$resCel = doSelect($qryCel);
	while ($celular = mysql_fetch_array($resCel)){
?>
<table width="768" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="2" align="center"> <a href="index.php?lbl=<?php echo MENU_SERVICIOS ?>&srv_id=<?php echo $celular['srv_id']?>"><?php echo $celular['descripcion'] ?></a> </td>
	</tr>
	<tr>
		<td> Codigo Plan: </td>
		<td> <?php echo $celular['codigo_plan']?> </td>
	</tr>
	<tr>
		<td width="30%"> Tecnología: </td>
		<td> <?php echo $celular['tecnologia'] ?> </td>
	</tr>
	<?php 
		$qryAtribs = "select a.name, c.value, a.tipo from atributos_servicios a, servicios_atributos c
					  where a.atr_id = c.atr_id
					  and a.tipo != '".ATTR_TYPE_IMAGE."'
					  and a.status = 'A'
					  and a.importante = 1
					  and c.srv_id = ".$celular['srv_id'];
		$resAtribs = doSelect($qryAtribs);
		while ($atrib = mysql_fetch_array($resAtribs)){
		?>
			<tr>
				<td> <?php echo $atrib['name'] ?> </td>
				<td> <?php
						if ($atrib['tipo'] == ATTR_TYPE_MONEY)
							echo "$"; 
						echo $atrib['value'] ?> </td>
			</tr>
		<?php
		}			  
					  
	?>
</table><br>
<?php } ?>