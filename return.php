<?php

@session_start();
echo "nuevo";
$regresar = rand(1,15);
$regresar = array("fecha"=>"hoy","nombre"=>$regresar);

$_SESSION['returnBuscaEmpresa'] = $regresar;
print_r($_SESSION);
?>