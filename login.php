<?php
require_once("core/coredb.php");
$data = new data;
if(isset($_POST['send'])){
	$data->login();
}else{
	@session_start();
	@session_regenerate_id();
	@session_destroy();
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js Background-Naranja"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/login.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

        <script src="kendoui/js/jquery.min.js"></script>
        <script src="kendoui/js/kendo.web.min.js"></script>
        <link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
        <link href="kendoui/styles/kendo.default.min.css" rel="stylesheet" />

        <script type="text/javascript">
        var visiblePass = false;
        	$(document).ready(function() {
                // Stuff to do as soon as the DOM is ready;
                $(".resetPasswd").hide("fast",function(){
                    $(".resetPasswd").css("width","450px");
                    $(".resetPasswd").css("margin","auto");
                    $(".resetPasswd").css("background","#ddd");
                    $(".resetPasswd").css("padding","10px");
                    visiblePass = false;
                });
                $("#plus_btn").click(function(){
                    window.location.href = "http://localhost/eFacta/nuevaEmpresa.html";
                });              //resetPasswd();
            });
        	function resetPasswd(){
                if(visiblePass){
                    $(".resetPasswd").hide("fast", function(){
                        visiblePass = false;
                    })
                }else{
                    $(".resetPasswd").show("fast",function(){
                    visiblePass = true;
                });
                }
        	}
        </script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="main-container">
            <div class="main wrapper clearfix">
                <div class="clearfix logo-container">
                    <img class="floats" src="img/logo.png">
                    <div class="floats">
                        <form action="" method="post">
                            <label>ID Usuario (RFC):</label>
                            <p>
                                <input name="txtUser" class="k-textbox" type="text">
                            </p>
                            <label>Contraseña de acceso:</label>
                            <p>
                                <input name="txtPassword" class="k-textbox" type="password">
                            </p>
                            <p>
                                <img src="img/plus_orange.png" title="Agregar Empresa" id="plus_btn" class="k-button">
                                <input class="k-button" type="submit" name="send" value="Ingresar" title="Iniciar Sesion">
                            </p>
                            <a href="#" onclick="resetPasswd();">Recuperar Contraseña</a>
                            
                        </form>
                    </div>
                </div>
                <div class="resetPasswd">
                    <p>
                        <h2>Recuperacion de Contraseña</h2>
                    </p>
                    <form action="" method="post" id="formReset">
                        <label>@e-mail:</label>
                        <input class="k-textbox" type="text" name="txtEmail">
                        <input class="k-button" type="submit" name="resetPasswd" value="Enviar @e-mail">
                    </form>
                    <span>Si no cuenta con esta info, contacte a su administrador.</span>
                </div>
            </div>
        </div>
    </body>
</html>