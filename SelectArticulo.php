<?php
require_once("core/coredb.php");
$id = $_GET['id'];
$datos = new data;

$arts = $datos->get_articulo($id);

$arts = json_decode($arts);
$arts = $arts[0];

$models = array("clave"=>$arts->clave, "unidad"=>$arts->unidad,"descripcion"=>$arts->descripcion,"precio"=>$arts->precio);
$models = array(0=>$models);
$models = array("models"=>$models);

$result = $datos->insertTmpArticulo($models);

print_r($result);
?>