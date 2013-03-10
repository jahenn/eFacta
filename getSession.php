<?php
@session_start();
$datos = $_SESSION;

echo json_encode($datos);

?>