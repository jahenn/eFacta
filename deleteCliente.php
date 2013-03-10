<?php
require_once("core/coredb.php");

$callback = $_GET['callback'];

$model = $_GET;
$model = $model['models'];
$model = json_decode($model);
$model = $model[0];

$datos = new data;
$model = $datos->deleteCliente($model);

$model = json_encode($model);


echo $callback;
echo "(";
print_r($model);
echo ")";
?>