<?php 
	$qryCel = "select tecnologia,codigo_plan,descripcion from servicios where srv_id = ".$_REQUEST['srv_id'];
	$resCel = doSelect($qryCel);
	$celular = mysql_fetch_array($resCel);// or die  ("El celular que esta buscando no existe");

?>
<script language="javascript">
	function imprimirCelu(){
		document.getElementById("divButtons").style.display = "none";
		window.print();
		setInterval('100000');
		document.getElementById("divButtons").style.display = "";
	}
	
	function verImg(img){
		document.getElementById("imgGrande").src = document.getElementById("img"+img).src;
		document.getElementById("divImg").style.display = '';
	}
	
	function closeImg(){
		document.getElementById("divImg").style.display = 'none';
	}
</script>
<br>
<br>
<table width="768" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="2" align="center"> <?php echo $celular['descripcion'] ?> </td>
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
					  and c.srv_id = ".$_REQUEST['srv_id'];
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
<div id="divButtons">
	<table width="768" align="center">
		<tr>
			<td align="center"> 
				<input type="button" value="Volver" onClick="window.location.href='index.php?lbl=<?php echo MENU_ABM_SERVICIOS ?>'">
			</td>
		</tr>
	</table>
</div>