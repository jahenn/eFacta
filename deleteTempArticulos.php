<?php
require_once("core/coredb.php");


$callback = $_GET['callback'];
$models = $_GET['models'];
$models = $models[0];

$datos = new data;
$models = json_encode($datos->deleteTempArticulos($models));
echo $callback;
echo "(";
print_r($models);
echo ")";
?>