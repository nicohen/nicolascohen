<?php 
	$qryCel = "select marca,modelo,tecnologia,precio_prepago,precio_postpago from celulares where celu_id = ".$_REQUEST['celu_id'];
	$resCel = doSelect($qryCel);
	$celular = mysql_fetch_array($resCel);// or die  ("El celular que esta buscando no existe");
?>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="2" align="center"> <?php echo $celular['marca']." ".$celular['modelo'] ?> </td>
	</tr>
	<tr>
		<td colspan="2"> 
			<table width="100%"><tr>
			<?php 
				$qryImages = "select c.value from atributos a, celulares_atributos c
							  where a.atr_id = c.atr_id 
							  and a.tipo = '".ATTR_TYPE_IMAGE."' 
							  and a.status = 'A'
							  and c.celu_id = ".$_REQUEST['celu_id'];
				//print_r($qryImages);
				$resImages = doSelect($qryImages);
				while ($img = mysql_fetch_array($resImages)){
					if ($img[0] != ""){
					?>
					<td align="center"><img src="/cti/src/img/<?php echo $img[0] ?>" width="100" height="100"></td>
					<?php
					}
				}
			?>
			</tr></table>
		</td>
	</tr>
	<tr>
		<td width="30%"> Tecnología: </td>
		<td> <?php echo $celular['tecnologia'] ?> </td>
	</tr>
	<tr>
		<td> Precio Pre-Pago: </td>
		<td> <?php echo $celular['precio_prepago']?> </td>
	</tr>
	<tr>
		<td> Precio Post-Pago: </td>
		<td> <?php echo $celular['precio_postpago']?> </td>
	</tr>
	<?php 
		$qryAtribs = "select a.name, c.value, a.tipo from atributos a, celulares_atributos c
					  where a.atr_id = c.atr_id
					  and a.tipo != '".ATTR_TYPE_IMAGE."'
					  and a.status = 'A'
					  and c.celu_id = ".$_REQUEST['celu_id']."
					  order by a.peso";
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
</table>