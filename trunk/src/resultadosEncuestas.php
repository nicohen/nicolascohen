<br>
<form action="/cti/src/index.php?lbl=<?php echo MENU_RES_RESULTADOS ?>" method="post" name="frmMain">
<table width="100%">
	<tr>
		<td align="center"> Sección resultados de las encuestas <br> </td>
	</tr>
	<tr> <td> &nbsp; </td></tr>
	<tr>
		<td> Seleccione una encuesta:  
			 <select name="enc_id" id="enc_id">
			 <?php 
			 	$qryEncuestas = "select enc_id, titulo from encuestas where status = 'A'";
				$resEnc = doSelect($qryEncuestas);
				while ($enc = mysql_fetch_array($resEnc)){
					?>
					<option value="<?php echo $enc['enc_id'] ?>"> <?php echo $enc['titulo'] ?> </option>
					<?php
				}
			 ?>
			 </select>
		</td>
	</tr>
	<tr height="8">
		<td>  </td>
	</tr>
	<tr>
		<td> Seleccione un vendedor: 
			<select name="vend_id" id="vend_id">
				<option value="0"> Todos </option>
				<?php
				 $qryVendedores = "select user_id, nickname from usuarios where status = 'A'";
				 $resVend = doSelect($qryVendedores);
				 while ($vend = mysql_fetch_array($resVend)){
				 ?>
				 	<option value="<?php echo $vend['user_id'] ?>"> <?php echo $vend['nickname'] ?> </option>
				 <?php }
				 ?>
			</select>
		</td>
	</tr>
	<tr>
		<td> <font size="-2">(o deje todos en el caso que sea necesario) </font> </td>
	</tr>
	<tr height="8">
		<td>  </td>
	</tr>
	<tr>
		<td> Ingrese un día (opcional): <input type="text" name="dia" value=""></td>
	</tr>	
	<tr>
		<td align="center"> <font size="-2">Formato: YYYY-MM-DD Ejemplo: 2003-12-31 </font> </td>
	</tr>
	<tr height="15">
		<td>  </td>
	</tr>
	<tr>
		<td align="center">
			<input type="submit" value="Ver resultados">
		</td>
	</tr>
</table>
</form>