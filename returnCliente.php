<?php
if(!isset($_GET['id'])){
	exit();
};
//header("Content-type: application/json");
require_once("core/coredb.php");
$data = new data;
$result = $data->get_cliente($_GET['id']);
echo $result;
?>