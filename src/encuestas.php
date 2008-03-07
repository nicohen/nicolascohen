<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php 
	$query = "select enc_id,titulo from encuestas where status = 'A'";
	$registros = doSelect($query) or die ("Problemas en select".mysql_error());
	while ($reg = mysql_fetch_array($registros)){
		echo "ID: ".$reg['enc_id']."<br>";
		echo "Título: ".$reg['titulo']."<br>";
	}
?>
</body>
</html>
