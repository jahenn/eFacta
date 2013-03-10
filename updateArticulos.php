<?php
require_once("core/coredb.php");
$callback = $_GET['callback'];
$model = $_GET;
$model = $model['models'];

$model = $model[0];

$datos = new data;
$model = $datos->updateArticulos($model);

echo $callback;
echo "(";
print_r($model);
echo ")";
?>