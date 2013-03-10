<?php
require_once("core/coredb.php");
$datos = new data;
$result = $datos->insertCliente($_GET);
echo $_GET['callback'];
echo "(";
print_r($result);
echo ")";	
?>