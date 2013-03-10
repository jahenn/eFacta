<?php
@session_start();
if(!isset($_SESSION['facta'])){
	header("Location: ./login.php");
}
require_once("core/coredb.php");
$data = new data;
$empresa = $data->get_empresa();
$empresa = json_decode($empresa);
$_SESSION['empresa'] = $empresa;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
<!--
include styles
-->
<?php
include("includes.html");

?>
<script>
$(document).ready(function(e) {
    $("#mainMenu").kendoMenu();
	$("#NewCliente").hide();
});
</script>
<script>
function resizeIframe(h,txtTotal) {
    var iframe = document.getElementById("mainFrame");
	iframe.style.height = (h+100) + "px";    
	console.log(h);
	/*
	var subTotal = document.getElementById("txtSubTotal");
	var numSubTotal = parseFloat(txtTotal);    
	subTotal.value = numSubTotal.toFixed(2); 
	
	calculaIva();
	*/
}
function WindowNewCliente(){
	var Dwindow = $("#NewCliente");
	if(!Dwindow.data("kendoWindow")){
		Dwindow.kendoWindow({
			top: 0,
			width: "80%",
			height: 550,
			title: "BUSCAR CLIENTE",
			modal: true,
			content: "ClientesList.php",
			iframe: true
		});
	}
	Dwindow.data("kendoWindow").refresh();
	Dwindow.data("kendoWindow").center().open();
	$("#NewCliente").show();	
}
function WindowNewArticulo(){
	var Dwindow = $("#NewArticulo");
	if(!Dwindow.data("kendoWindow")){
		Dwindow.kendoWindow({
			top: 0,
			width: "80%",
			height: 550,
			title: "BUSCAR ARTICULO",
			modal: true,
			content: "buscaArticulo.php",
			iframe: true
		});
	}
	Dwindow.data("kendoWindow").refresh();
	Dwindow.data("kendoWindow").center().open();
	$("#NewCliente").show();	
}
function WindowEmpresa(){
	var Dwindow = $("#NewEmpresa");
	if(!Dwindow.data("kendoWindow")){
		Dwindow.kendoWindow({
			top: 0,
			width: "80%",
			height: 550,
			title: "Datos De MI Empresa",
			modal: true,
			content: "DatosEmpresa.php?r=0&save=1",
			iframe: true
		});
	}
	Dwindow.data("kendoWindow").refresh();
	Dwindow.data("kendoWindow").center().open();
	$("#NewCliente").show();	
}
</script>
</head>
<body>
<div class="k-content">
<div class="logoHeader">
<table id="tableLogo">
<tr>
<td><img src="images/logo.jpg" width="80" /></td>
<td><h1>facta-CFDi</h1></td>
</tr>
</table>
</div>
<div>	
<ul id="mainMenu">
<li><a href="./">Home</a></li>
<li>Opciones</li>
<li><a href="#" onclick="WindowNewCliente();">Clientes</a></li>
<li><a href="#" onclick="WindowNewArticulo();">Productos</a></li>
<li><a href="#" onclick="WindowEmpresa();">Empresa</a></li>
<li><a href="login.php">Salir</a></li>		
</ul>
</div>
<p></p>
<iframe src="CapturaDocumento.php" width="100%" frameborder="0" id="mainFrame">
</iframe>
</div>

<div id="NewCliente"></div>
<div id="NewArticulo"></div>
<div id="NewEmpresa"></div>
</body>
</html>
