<?php
require_once("core/coredb.php");
$datos = new data;

$callback = $_GET['callback'];
$models = $_GET['models'];
$models = json_decode($models);
$models = $models[0];
$result = $datos->updateCliente($models);
$result = json_encode($result);
echo $callback;
echo "(";
print_r($result);
echo ")";

?>