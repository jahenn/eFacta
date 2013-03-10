<?php

$datos = file_get_contents("http://query.yahooapis.com/v1/public/yql?q=select%20item%20from%20weather.forecast%20where%20location%3D%2248907%22&format=json");
$datos = json_decode($datos);

print_r($datos->query->created);


?>