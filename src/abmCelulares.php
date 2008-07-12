<?php 
$fotoID = 0;
function todosCeros($valores){
	foreach ($valores as $valor){
		if ($valor > 0){
			return false;
		}
	}
	return true;
}

function getBackGroundColor($prepago, $postpago, $valores, $lastModDt){
	if ($prepago == 0 && $postpago == 0){
		//Rojo
		return "#FF4A4A";
	} else if (todosCeros($valores,$atributos)){
		//Naranja
		return "#CCCC00";
	} else if ($prepago == 0 || $postpago == 0){
		//Amarillo
		return "#FFFF00";
	} else if ($lastModDt - strtotime("-1 week") >= 0){
		//Verde
		return "#66FF33";
	}	

	echo "La fechaes:".date("d-m-y", $lastModDt);
	//Blanco
	return "#FFFFFF";
}

function getIns($atributos){
	$ret = "";
	for ($i = 0; $i < count($atributos); $i++){
		if ($i > 0){
			$ret = $ret.", ";
		}
		$ret = $ret.$atributos[$i]['id'];
	}
	return $ret;
}

function getWidth($cantCells){
//	echo $cantCells;
	return round((747 - 170) / ($cantCells + 2));
}

function insertAttr($celuID){
	$docRoot = getDocumentRoot();	
	for ($i = 1; $i <= $_REQUEST['cantAtrib']; $i++){
		$tipo = $_REQUEST['atrType'.$i];
		$atrID = $_REQUEST['atrName'.$i];
		$valor = "";
		//print_r($atrID."->".$tipo."<br>");
		if ($tipo == ATTR_TYPE_MULTIPLE){
			if (isPriceLoader($_COOKIE['user_active'])){
				$valor =  $_REQUEST['atrValue'.$i];
		    } else if ($_REQUEST['atrValue'.$i]){			
				foreach ($_REQUEST['atrValue'.$i] as $opcion) {
					if ($valor != "") $valor = $valor.", ";
					$valor = $valor.trim($opcion);
				} 
			}
			//echo $valor;
		} else if ($tipo == ATTR_TYPE_CHECKBOX){
			$valor = ($_REQUEST['atrValue'.$i]?"1":"0");
		} else if ($tipo == ATTR_TYPE_IMAGE && $_FILES['atrValue'.$i]['name'] != ''){
			
			//print_r($_FILES['atrValue'.$i]['name']);
			$fotoID++;
			
			$tmpFoto = $_FILES['atrValue'.$i]['tmp_name'];
			//$dirBase = "";

			$dirTo = $docRoot."/img/".$celuID."_";
						
			//mkdir(dirname($dirTo),0755,true);
			//recur_mkdirs($dirTo);
			
			$origFile = $_FILES['atrValue'.$i]['name'];
			$ext = substr($origFile,strpos($origFile,"."));
			
			$fileTo = $dirTo.$fotoID.$ext;
			
//			echo $fileTo;
			
			while (file_exists($fileTo)){
				$fotoID++;
				$fileTo = $dirTo.$fotoID.$ext;
			}
			
			//print_r($fileTo);			
			//print_r($origFile);
			//print_r($tmpFoto."->".$fileTo);
			move_uploaded_file($tmpFoto,$fileTo);			
			//print_r("pase el movimiento");
			//copy($tmpFoto,$fileTo);
			$valor = $celuID."_".$fotoID.$ext;
			//print_r($valor);
			
			if ($_REQUEST['act']==UPDATE_CEL){
				$qryUpdImg = "update celulares_atributos set value = '".$valor."'
							  where celu_id = ".$_REQUEST['celu_id']."
							  and atr_id = ".$atrID;
				if (doExecuteAndGetCount($qryUpdImg) > 0){
					continue;
				}
			}
		} else {
			$valor = $_REQUEST['atrValue'.$i];
		}
		
		$qryInsAtr = "insert into celulares_atributos (celu_id, atr_id, value) values (".$celuID.", ".$atrID.",'".$valor."')";
		//echo $qryInsAtr.";<br>";
		//print_r($qryInsAtr."<br>");
		doInsert($qryInsAtr);
	}

}

function deleteAtribs(){
/*	$qryDelAtribs = "delete from celulares_atributos c where celu_id = ".$_REQUEST['celu_id'];
	if ($_REQUEST['act']== UPDATE_CEL){
		$qryDelAtribs = $qryDelAtribs." and (select tipo from atributos where atr_id = c.atr_id) != '".ATTR_TYPE_IMAGE."'";
	}
	*/
	
	if ($_REQUEST['act']== UPDATE_CEL){
		$qryDelAtribs = "select atr_id from atributos a WHERE a.tipo != '".ATTR_TYPE_IMAGE."'" ;
		$resAtrib = doSelect($qryDelAtribs);
		while ($atrib = mysql_fetch_array($resAtrib)){
			$qryDel = "delete from celulares_atributos where celu_id = ".$_REQUEST['celu_id']." and atr_id = ".$atrib['atr_id'];
			doInsert($qryDel);
		}
		
	} else {
		$qryDelAtribs = "DELETE c.* FROM celulares_atributos c";
		$qryDelAtribs = $qryDelAtribs." where ";
		$qryDelAtribs = $qryDelAtribs."c.celu_id = ".$_REQUEST['celu_id'];
		doInsert($qryDelAtribs);
	}

	
	
	
	//echo $qryDelAtribs. "<br>";
	
}

$isPrice = isPriceLoader($_COOKIE['user_active']);
if ($isPrice){ 
	//LLenado de los atributos con precios
	$queryAtribs = "select atr_id, name from atributos
					where status = 'A'
					and tipo='".ATTR_TYPE_MONEY."'";
	$resAtribs = doSelect($queryAtribs);
	$i = 0; 
	while ($atribValue = mysql_fetch_array($resAtribs)){	
		$atributos[$i]['id'] = $atribValue['atr_id']; 
		$atributos[$i++]['name'] = $atribValue['name'];
	}
}

if ($_REQUEST['act'] == SAVE_CEL){
	$qryInsCel = "insert into celulares (marca, modelo, tecnologia, precio_prepago, precio_postpago, last_mod_dt,last_mod_user,create_dt,status) 
				  values ('".$_REQUEST['marca']."','".$_REQUEST['modelo']."',
				  '".$_REQUEST['tecnologia']."',".$_REQUEST['precio_prepago'].",".$_REQUEST['precio_postpago'].",
				  sysdate(),".$_COOKIE['user_active'].
				 ",sysdate(),'A')";
	//print_r($qryInsCel."<br>");
	$celuID = doInsertAndGetLast($qryInsCel);	
	//$celuID = 0;
	insertAttr($celuID);
	
} else if ($_REQUEST['act'] == INACTIVE_CEL || $_REQUEST['act'] == ACTIVE_CEL){
	$qryUpd = "update celulares set status = '".($_REQUEST['act'] == INACTIVE_CEL?"I":"A")."' where celu_id = ".$_REQUEST['celu_id'];
	doInsert($qryUpd);
} else if ($_REQUEST['act'] == UPDATE_CEL){
	$qryUpd = "update celulares 
			   set marca = '".$_REQUEST['marca']."', 
			   modelo = '".$_REQUEST['modelo']."',
			   tecnologia = '".$_REQUEST['tecnologia']."',
			   precio_prepago = ".$_REQUEST['precio_prepago'].",
			   precio_postpago = ".$_REQUEST['precio_postpago'].", 
			   last_mod_dt = sysdate(), 
			   last_mod_user = ".$_COOKIE['user_active']." 
			   where celu_id = ".$_REQUEST['celu_id'];
	doInsert($qryUpd);
	
	deleteAtribs();
	
	insertAttr($_REQUEST['celu_id']);

} else if ($_REQUEST['act'] == DELETE_CEL){
//print_r("estoy borrando");
	$qryDel = "delete from celulares where celu_id = ".$_REQUEST['celu_id'];
	doInsert($qryDel);
	deleteAtribs();
} else if ($_REQUEST['act'] == UPDATE_PRICE_CEL){
	$qryUpd = "update celulares 
		   set precio_prepago = ".$_REQUEST['precio_prepago'].",
		   precio_postpago = ".$_REQUEST['precio_postpago'].", 
		   last_mod_dt = sysdate(), 
		   last_mod_user = ".$_COOKIE['user_active']." 
		   where celu_id = ".$_REQUEST['celu_id'];
	doInsert($qryUpd);
	
	for ($i = 0; $i < count ($atributos); $i++){
		$qryUpdAtr = "update celulares_atributos
					  set VALUE = '".$_REQUEST[$atributos[$i]['id']]."'
					  where celu_id = ".$_REQUEST['celu_id']."
					  and atr_id = ".$atributos[$i]['id'];
		doInsert($qryUpdAtr);
	}
}
?>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td colspan="<?php echo $isPrice?count($atributos) + 4:2; ?>"> Celulares actuales </td>
	</tr>
	<?php if ($isPrice){ ?>	
	<tr bgcolor="#FFCC99">
		<td width="120"> &nbsp; <?php //echo count($atributos); ?> </td>
		<td align="center" width="<?php echo getWidth(count($atributos)); ?>"> Precio Prepago </td>
		<td align="center" width="<?php echo getWidth(count($atributos)); ?>"> Precio Postpago </td>						
		<?php for($i = 0; $i < count($atributos); $i++){ ?>
		<td align="center" width="<?php echo getWidth(count($atributos)); ?>"> <?php echo $atributos[$i]['name']; ?> </td>
		<?php } ?>
		<td width="50">&nbsp;  </td>
	</tr>
	<?php } ?>
	<?php 
	$qryCelus = "select celu_id, marca, modelo, status, precio_prepago, precio_postpago, UNIX_TIMESTAMP(last_mod_dt) as last_mod_dt from celulares";
	$resCelus = doSelect($qryCelus);
	while ($celu = mysql_fetch_array($resCelus)){
		if ($isPrice){
			//Precarga de los valores
			$qryAtribVal = "select atr_id, value from celulares_atributos
							where celu_id = ".$celu['celu_id']."
							and atr_id in (".getIns($atributos).")";
			$resAtribVal = doSelect($qryAtribVal);
			while ($atribValue = mysql_fetch_array($resAtribVal)){
				$values["".$atribValue['atr_id']] = $atribValue['value'];
			}
			//Armado de la fila para un usuario cargador de precios?>
		<tr style="background-color:<?php echo getBackGroundColor($celu['precio_prepago'],$celu['precio_postpago'],$values, $celu['last_mod_dt']); ?> ">
			<form method="post" name="frm<?php echo $celu['celu_id'] ?>" action="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>&act=<?php echo UPDATE_PRICE_CEL ?>">
			<input type="hidden" name="celu_id" value="<?php echo $celu['celu_id'] ?>">
			<td nowrap width="120"><?php echo $celu['marca']." ".$celu['modelo'] ?></td>
			<td width="<?php echo getWidth(count($atributos)); ?>"><input type="text" name="precio_prepago" value="<?php echo $celu['precio_prepago']; ?>"></td>
			<td width="<?php echo getWidth(count($atributos)); ?>"><input type="text" name="precio_postpago" value="<?php echo $celu['precio_postpago']; ?>"></td>
			<?php for($i = 0; $i < count($atributos); $i++){ ?>
			<td width="<?php echo getWidth(count($atributos)); ?>"><input type="text" name="<?php echo $atributos[$i]['id'] ?>" value="<?php echo $values[$atributos[$i]['id'].""]?>"></td>
			<?php } ?>
			<td width="50"><input type="submit" value="Grabar" width="50"></td>
			</form>
		</tr>
		<?php }else{ //Armado de la fila para un usuario normal?>
		<tr>
			<td width="50%"><a target="_blank" href="vpp.php?celu_id=<?php echo $celu['celu_id'] ?>"><?php echo $celu['marca']." ".$celu['modelo'] ?></a></td>
			<td>
				<?php if($celu['status'] == 'A'){ ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>&act=<?php echo INACTIVE_CEL ?>&celu_id=<?php echo $celu['celu_id'] ?>">Inactivar</a>
				<?php } else { ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>&act=<?php echo ACTIVE_CEL ?>&celu_id=<?php echo $celu['celu_id'] ?>">Activar</a>
				<?php } ?>
				<a href="index.php?lbl=<?php echo MENU_ALTA_CELULARES ?>&act=<?php echo MODIF_CEL?>&celu_id=<?php echo $celu['celu_id']?>">Editar</a>
				<a href="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>&act=<?php echo DELETE_CEL?>&celu_id=<?php echo $celu['celu_id']?>">Borrar</a>
			</td>
		</tr>
		<?php } 
	}
	
	?>
	<?php if (!isPriceLoader($_COOKIE['user_active'])){?>
	<tr>
		<td colspan="2" align="center"> <input type="button" value="Nuevo celular" onClick="javascript:location.href = 'index.php?lbl=<?php echo MENU_ALTA_CELULARES ?>'"> </td>
	</tr>
	<? } ?>
</table>