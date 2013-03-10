<?php
header("Content-type: application/json");
require_once("core/coredb.php");

$datos = new data;
echo $datos->get_temp_prods();
?>