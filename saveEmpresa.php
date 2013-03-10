<?php
@session_start();



require_once("core/coredb.php");
$datos = new data;
$datos->updateEmpresa($_SESSION['miEmpresa']) or die("Error Al Actualizar");
echo "Cambios Realizados Correctamente, cierra tu sesion e inicia nuevamente para que se actualize tu registro..";

session_regenerate_id();
session_destroy();

?>