<?php
header("Content-type: application/json");
@session_start();
require_once("core/coredb.php");

$empresa = new data;
echo $empresa->get_clientes();

?>