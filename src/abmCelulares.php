<?php 
$fotoID = 0;
function insertAttr($celuID){
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
			$dirTo = "img\\".$celuID."_";
						
			//mkdir(dirname($dirTo),0755,true);
			//recur_mkdirs($dirTo);
			
			$origFile = $_FILES['atrValue'.$i]['name'];
			$ext = substr($origFile,strpos($origFile,"."));
			
			$fileTo = $dirTo.$fotoID.$ext;
			
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
	$qryDelAtribs = "DELETE c.* FROM celulares_atributos c";
	if ($_REQUEST['act']== UPDATE_CEL){
		$qryDelAtribs = $qryDelAtribs." LEFT JOIN atributos a USING ( atr_id ) WHERE a.tipo != '".ATTR_TYPE_IMAGE."' and " ;
	} else {
		$qryDelAtribs = $qryDelAtribs." where ";
	}
	$qryDelAtribs = $qryDelAtribs."c.celu_id = ".$_REQUEST['celu_id'];
	
	//echo $qryDelAtribs. "<br>";
	doInsert($qryDelAtribs);
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
			<td width="50%"><a target="_blank" href="vpp.php?celu_id=<?php echo $celu['celu_id'] ?>"><?php echo $celu['marca']." ".$celu['modelo'] ?></a></td>
			<td>
				<?php if (!isPriceLoader($_COOKIE['user_active'])){?>
				<?php if($celu['status'] == 'A'){ ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>&act=<?php echo INACTIVE_CEL ?>&celu_id=<?php echo $celu['celu_id'] ?>">Inactivar</a>
				<?php } else { ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>&act=<?php echo ACTIVE_CEL ?>&celu_id=<?php echo $celu['celu_id'] ?>">Activar</a>
				<?php } 
					}?>
				<a href="index.php?lbl=<?php echo MENU_ALTA_CELULARES ?>&act=<?php echo MODIF_CEL?>&celu_id=<?php echo $celu['celu_id']?>">Editar</a>
				<?php if (!isPriceLoader($_COOKIE['user_active'])){?>
					<a href="index.php?lbl=<?php echo MENU_ABM_CELULARES ?>&act=<?php echo DELETE_CEL?>&celu_id=<?php echo $celu['celu_id']?>">Borrar</a>
				<?php } ?>
			</td>
		</tr>
		<?php
	}
	
	?>
	<?php if (!isPriceLoader($_COOKIE['user_active'])){?>
	<tr>
		<td colspan="2" align="center"> <input type="button" value="Nuevo celular" onClick="javascript:location.href = 'index.php?lbl=<?php echo MENU_ALTA_CELULARES ?>'"> </td>
	</tr>
	<? } ?>
</table>