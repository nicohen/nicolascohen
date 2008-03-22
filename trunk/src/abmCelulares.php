<?php 

if ($_REQUEST['act'] == SAVE_CEL){
	$qryInsCel = "insert into celulares (marca, modelo, last_mod_dt,last_mod_user,create_dt,status) 
				  values ('".$_REQUEST['marca']."','".$_REQUEST['modelo']."',sysdate(),".$_COOKIE['user_active'].
				 ",sysdate(),'A')";
	//print_r($qryInsCel."<br>");
	$celuID = doInsertAndGetLast($qryInsCel);	
	//$celuID = 0;
	
	for ($i = 1; $i <= $_REQUEST['cantAtrib']; $i++){
		$tipo = $_REQUEST['atrType'.$i];
		$atrID = $_REQUEST['atrName'.$i];
		$valor = "";
		//print_r($atrID."->".$tipo."<br>");
		if ($tipo == ATTR_TYPE_MULTIPLE){			
			foreach ($_REQUEST['atrValue'.$i] as $opcion) {
  				if ($valor != "") $valor = $valor.", ";
				$valor = $valor.trim($opcion);
			} 
		} else if ($tipo == ATTR_TYPE_CHECKBOX){
			$valor = ($_REQUEST['atrValue'.$i]?"1":"0");
		} else if ($tipo == ATTR_TYPE_IMAGE){
			
			$tmpFoto = $_FILES['atrValue'.$i]['tmp_name'];
			//$dirBase = "";
			$dirTo = "img\\".$celuID."_";
			$fotoID = 1;
			while (file_exists($dirTo.$fotoID)){
				$fotoID++;
			}
			
			//mkdir(dirname($dirTo),0755,true);
			//recur_mkdirs($dirTo);
			
			$origFile = $_FILES['atrValue'.$i]['name'];
			$ext = substr($origFile,strpos($origFile,"."));
			
			$fileTo = $dirTo.$fotoID.$ext;
			
			//print_r($origFile);
			//print_r($tmpFoto."->".$fileTo);
			if (move_uploaded_file($tmpFoto,$fileTo)){
				//NO hago nada
			}
			
			
			
			
			//copy($tmpFoto,$fileTo);
			$valor = $celuID."_".$fotoID.$ext;
		} else {
			$valor = $_REQUEST['atrValue'.$i];
		}
		
		$qryInsAtr = "insert into celulares_atributos (celu_id, atr_id, value) values (".$celuID.", ".$atrID.",'".$valor."')";
		//print_r($qryInsAtr."<br>");
		doInsert($qryInsAtr);
	}
}
?>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="2"> Celulares actuales </td>
	</tr>
	<?php 
	$qryCelus = "select celu_id, marca, modelo, status from celulares";
	$resCelus = doSelect($qryCelus);
	while ($celu = mysql_fetch_array($resCelus)){
		?>
		<tr>
			<td><?php echo $celu['marca']." ".$celu['modelo'] ?></td>
			<td> Acciones para <?php echo $celu['celu_id'] ?> </td>
		</tr>
		<?php
	}
	
	?>
	<tr>
		<td colspan="2" align="center"> <input type="button" value="Nuevo celular" onClick="javascript:location.href = 'index.php?lbl=<?php echo MENU_ALTA_CELULARES ?>'"> </td>
	</tr>
</table>