<?php 
$fotoID = 0;
function insertAttr($celuID){
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
				$qryUpdImg = "update servicios_atributos set value = '".$valor."'
							  where srv_id = ".$_REQUEST['srv_id']."
							  and atr_id = ".$atrID;
				if (doExecuteAndGetCount($qryUpdImg) > 0){
					continue;
				}
			}
		} else {
			$valor = $_REQUEST['atrValue'.$i];
		}
		
		$qryInsAtr = "insert into servicios_atributos (srv_id, atr_id, value) values (".$celuID.", ".$atrID.",'".$valor."')";
		//print_r($qryInsAtr."<br>");
		doInsert($qryInsAtr);
	}

}

function deleteAtribs(){
//	$qryDelAtribs = "delete from servicios_atributos c where srv_id = ".$_REQUEST['srv_id'];
	$qryDelAtribs = "DELETE servicios_atributos.* FROM servicios_atributos s";
	if ($_REQUEST['act']== UPDATE_CEL){
		$qryDelAtribs = $qryDelAtribs." LEFT JOIN atributos_servicios a USING ( atr_id ) WHERE a.tipo != '".ATTR_TYPE_IMAGE."' and " ;
	} else {
		$qryDelAtribs = $qryDelAtribs." where ";
	}
	$qryDelAtribs = $qryDelAtribs."srv_id = ".$_REQUEST['srv_id'];
	
	//echo $qryDelAtribs;
	echo doInsert($qryDelAtribs);
}

if ($_REQUEST['act'] == SAVE_CEL){
	$qryInsCel = "insert into servicios (tecnologia, codigo_plan, descripcion, last_mod_dt,last_mod_user,create_dt,status) 
				  values ('".$_REQUEST['tecnologia']."','".$_REQUEST['codigo_plan']."',
				  '".$_REQUEST['descripcion']."',sysdate(),".$_COOKIE['user_active'].
				 ",sysdate(),'A')";
	//print_r($qryInsCel."<br>");
	$celuID = doInsertAndGetLast($qryInsCel);	
	//$celuID = 0;
	insertAttr($celuID);
	
} else if ($_REQUEST['act'] == INACTIVE_CEL || $_REQUEST['act'] == ACTIVE_CEL){
	$qryUpd = "update servicios set status = '".($_REQUEST['act'] == INACTIVE_CEL?"I":"A")."' where srv_id = ".$_REQUEST['srv_id'];
	doInsert($qryUpd);
} else if ($_REQUEST['act'] == UPDATE_CEL){
	$qryUpd = "update servicios 
			   set tecnologia = '".$_REQUEST['tecnologia']."',
			   codigo_plan = '".$_REQUEST['codigo_plan']."',
			   descripcion = '".$_REQUEST['descripcion']."',			   
			   last_mod_dt = sysdate(), 
			   last_mod_user = ".$_COOKIE['user_active']." 
			   where srv_id = ".$_REQUEST['srv_id'];
	doInsert($qryUpd);
	
	deleteAtribs();
	
	insertAttr($_REQUEST['srv_id']);

} else if ($_REQUEST['act'] == DELETE_CEL){
//print_r("estoy borrando");
	$qryDel = "delete from servicios where celu_id = ".$_REQUEST['srv_id'];
	doInsert($qryDel);
	deleteAtribs();
}
?>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99" align="center">
		<td colspan="3"> Servicios actuales </td>
	</tr>
	<tr bgcolor="#FFCC99">
		<td width="10%"> Codigo Plan </td>
		<td width="50%"> Descripción </td>
		<td> Acciones </td>
	</tr>
	<?php 
	$qryCelus = "select srv_id, codigo_plan, descripcion, status from servicios";
	$resCelus = doSelect($qryCelus);
	while ($celu = mysql_fetch_array($resCelus)){
		?>
		<tr>
			<td> <?php echo $celu['codigo_plan'] ?> </td>
			<td><a href="index.php?lbl=<?php echo MENU_SERVICIOS ?>&srv_id=<?php echo $celu['srv_id'] ?>"><?php echo $celu['descripcion'] ?></a></td>
			<td>
				<?php if($celu['status'] == 'A'){ ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_SERVICIOS ?>&act=<?php echo INACTIVE_CEL ?>&srv_id=<?php echo $celu['srv_id'] ?>">Inactivar</a>
				<?php } else { ?>
				<a href="index.php?lbl=<?php echo MENU_ABM_SERVICIOS ?>&act=<?php echo ACTIVE_CEL ?>&srv_id=<?php echo $celu['srv_id'] ?>">Activar</a>
				<?php } ?>
				<a href="index.php?lbl=<?php echo MENU_ALTA_SERVICIOS ?>&act=<?php echo MODIF_CEL?>&srv_id=<?php echo $celu['srv_id']?>">Editar</a>
				<a href="index.php?lbl=<?php echo MENU_ABM_SERVICIOS ?>&act=<?php echo DELETE_CEL?>&srv_id=<?php echo $celu['srv_id']?>">Borrar</a>
			</td>
		</tr>
		<?php
	}
	
	?>
	<tr>
		<td colspan="3" align="center"> <input type="button" value="Nuevo servicio" onClick="javascript:location.href = 'index.php?lbl=<?php echo MENU_ALTA_SERVICIOS ?>'"> </td>
	</tr>
</table>