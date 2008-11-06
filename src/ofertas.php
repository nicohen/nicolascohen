<script language="javascript">
	function showDiv(folder){
		if (document.getElementById('div'+folder).style.display == ""){
			document.getElementById('div'+folder).style.display = "none";
		} else {
			document.getElementById('div'+folder).style.display = "";
		}
	}
</script>

<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;border-color:gray" align="center">
	<tr bgcolor="#FFCC99">
		<td align="center"> Ofertas actuales </td>
	</tr>
<?php
	$tipo = $_REQUEST['tipo'];
	$resOfe = doSelect("select ofe_id, titulo, folder, filename from ofertas where status = 'A' and tipo = '".$tipo."' order by folder, filename, fecha desc");
	$lastFolder = "";

	while ($ofe = mysql_fetch_array($resOfe)){
		if ($lastFolder != $ofe['folder']){
		$first = true;
			if ($lastFolder != ""){
				echo "</td></tr></table></div></tr></td>";
			}
			$lastFolder = $ofe['folder'];	
			echo "<tr><td> <a href=\"javascript:showDiv('".$ofe['folder']."')\" >".$ofe['folder']."</a>";
			echo "<div id='div".$ofe['folder']."' style='display:none'><table widht='80%' border=0><tr><td width='20'> &nbsp; </td><td align='left'>";
		}
		
		if (!$first){ echo "<br>"; $first = false;}
	?>	
	<li>		<a style="color:#009900; font-style:italic" href="ofertas_files/<?php echo $ofe['folder']."/".$ofe['filename']?>"><?php echo $ofe['titulo'] ?></a></li>
		
	<?php
	}
?>
</table>