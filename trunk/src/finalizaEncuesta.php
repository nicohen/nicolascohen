<?php 
//Funciones para operar con DB y otros
require("funcionesDB.php"); 

$qryIns = "insert into encuestas_realiadas (enc_id,vendedor,timestamp) values (".$_REQUEST['enc_id'].",0,sysdate())";


?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

</body>
</html>
