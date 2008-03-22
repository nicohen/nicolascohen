<?php
if (isset($_REQUEST['pos']))
  $inicio=$_REQUEST['pos'];
else
  $inicio=0;
?>

<?php

$query = "";
$result = doSelect($query);
while ($res = mysql_fetch_array($result)) {

}

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
