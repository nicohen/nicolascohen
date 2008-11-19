<?php 
//Funciones para operar con archivos y otros
require_once("funciones.php"); 
require_once("funcionesDB.php");

	$qryCel = "select marca,modelo,tecnologia,precio_prepago,precio_postpago from celulares where celu_id = ".$_REQUEST['celu_id'];
	$resCel = doSelect($qryCel);
	$celular = mysql_fetch_array($resCel);// or die  ("El celular que esta buscando no existe");
	
	do_header($celular['marca']." ".$celular['modelo']);
?>
<body>
<script language="javascript">
	function imprimirCelu(){
		document.getElementById("divButtons").style.display = "none";
		window.print();
		setInterval('100000');
		document.getElementById("divButtons").style.display = "";
	}
	
	function verImg(img){
		document.getElementById("imgGrande").src = img;
		document.getElementById("divImg").style.display = '';
	}
	
	function closeImg(){
		document.getElementById("divImg").style.display = 'none';
	}
</script>
<br>
<br>
<table width="768" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray;font:Arial, Helvetica, sans-serif; font-size:13px;" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="2" align="center"><table width="100%">
		<tr>
			<td width="98">&nbsp;</td>
			<td align="center"><h1> <?php echo $celular['marca']." ".$celular['modelo'] ?> </h1></td>
			<td width="98"> <img src="./imgs/logot.jpg"></td>
		</tr>
		</table>  </td>
	</tr>
	<tr>
		<td colspan="2"> 
			<table width="100%">
			<tr>
			<?php 
				$qryImages = "select c.value from atributos a, celulares_atributos c
							  where a.atr_id = c.atr_id 
							  and a.tipo = '".ATTR_TYPE_IMAGE."' 
							  and a.status = 'A'
							  and c.celu_id = ".$_REQUEST['celu_id'];
				//print_r($qryImages);
				$resImages = doSelect($qryImages);
				$i = 0;
				while ($img = mysql_fetch_array($resImages)){
					$i++;
					if ($img[0] != ""){
					?>
					<td align="center" valign="middle">
						<table cellpadding="0" cellspacing="0" border="0" width="100">
							<tbody>
								<tr>
									<td align="center"> 
										<a href="javascript:verImg('<?php echo getLinkImage("/img/".$img[0],295,350) ?>');">
											<img border="0" height="100" id="img<?php echo $i ?>" src="<?php echo getLinkImage("/img/".$img[0],100,100) ?>">
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<?php
					}
				}
			?>
			</tr></table>
			<div id="divImg" style="border:1;border-bottom-style:solid;position:absolute; display:none; z-index:1; left: 352px; top: 89px; width: 295px">
				<table width="295" border="2" style="border-collapse:collapse;border-color:black;background-color:#FFFFFF;  ">
					<tr>
						<td align="center"><img id="imgGrande"><br>
						<a href="javascript:closeImg()">Cerrar</a>
				</td></tr></table>
			</div>
		</td>
	</tr>
	<tr>
		<td width="30%"> Tecnología: </td>
		<td> <?php echo $celular['tecnologia'] ?> </td>
	</tr>
	<tr>
		<td> Precio Pre-Pago: </td>
		<td> $<?php echo $celular['precio_prepago']?> </td>
	</tr>
	<tr>
		<td> Precio Post-Pago: </td>
		<td> $<?php echo $celular['precio_postpago']?> </td>
	</tr>
	<?php 
		$qryAtribs = "select a.name, c.value, a.tipo, a.atr_id from atributos a, celulares_atributos c
					  where a.atr_id = c.atr_id
					  and a.tipo != '".ATTR_TYPE_IMAGE."'
					  and a.status = 'A'
					  and a.publico = 1
					  and c.celu_id = ".$_REQUEST['celu_id']."
					  order by a.peso";
		$resAtribs = doSelect($qryAtribs);
		while ($atrib = mysql_fetch_array($resAtribs)){
		?>
			<tr>
				<td> <?php echo $atrib['name'] ?> </td>
				<td> <?php
						if ($atrib['tipo'] == ATTR_TYPE_CHECKBOX){
							echo $atrib['value']?"Si":"No";
						} else {
							if ($atrib['tipo'] == ATTR_TYPE_MONEY)
								echo "$"; 
							echo $atrib['value'];
							if ($atrib['tipo'] == ATTR_TYPE_SUFIX)
								echo getSufijo($atrib['atr_id']);
						} ?> </td>
			</tr>
		<?php
		}			  
					  
	?>
	<tr>
		<td colspan="2">
			Lo podés ubicar en las siguientes sucursales: <br>
			<table border="0" width="80%" align="center" cellpadding="0" cellspacing="0">
				<?php  
					$qrySuc = "select s.nombre, s.direccion from sucursales s, celulares_sucursales sc
							   where sc.celu_id = ".$_REQUEST['celu_id']."
							   and sc.suc_id = s.suc_id";
					$resSuc = doSelect($qrySuc);
					while ($suc = mysql_fetch_array($resSuc)){
				?>
					<tr><td>
					<font size="-1"><li> <?php echo $suc['nombre'] ?>(<?php echo $suc['direccion'] ?>)  </li></font>
					</td></tr>
				<?php } ?>
				</table>
		</td>
	</tr>
</table>
<div id="divButtons">
	<table width="768" align="center">
		<tr>
			<td align="center"> 
				<input type="button" value="Imprimir" onClick="imprimirCelu()">
				<input type="button" value="Cerrar" onClick="javascript:window.close();">
			</td>
		</tr>
	</table>
</div>
</body>
<?php
close_html();
?>