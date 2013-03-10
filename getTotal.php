<?php
require_once("core/coredb.php");
$rdatos = new data;
$datosJSON = $rdatos->get_temp_prods();
$total = 0;
$datosJSON = json_decode($datosJSON);
foreach($datosJSON as $dato){
	$total += $dato->subtotal;
}
?>