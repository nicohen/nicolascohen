<?php
if (isset($_REQUEST['pos']))
  $inicio=$_REQUEST['pos'];
else
  $inicio=0;
?>

<table border="1" align="center" cellpadding="3" cellspacing="0">
<?php
$query = "select * from (select c.celu_id, c.marca, c.modelo, a.name, ca.value from celulares as c, atributos as a, celulares_atributos ca where c.status='A' and a.status='A' and c.celu_id=ca.celu_id and a.atr_id=ca.atr_id order by name) z limit ".$inicio.",4";
$result = doSelect($query);
while ($res = mysql_fetch_array($result)) {
	
}
?>
</table>

<?php
if ($inicio==0)
  echo "anteriores ";
else
{
  $anterior=$inicio-2;
  echo "<a href=\"pagina1.php?pos=$anterior\">Anteriores </a>";
}
if ($impresos==2)
{
  $proximo=$inicio+2;
  echo "<a href=\"pagina1.php?pos=$proximo\">Siguientes</a>";
}
else
  echo "siguientes";

?>
