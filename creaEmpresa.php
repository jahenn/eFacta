<?php
require_once("core/coredb.php");
$datos = new data;
$datos->creaEmpresa($_POST['nRFC'],$_POST['nPassword']);


?>

