<?php

@session_start();
$redirect = $_GET['redirect'];
$session_name = $_GET['session_name'];
$_SESSION[$session_name] = $_POST;
header("Location: $redirect");

?>