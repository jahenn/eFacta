<?php
@session_start();
@session_regenerate_id();
@session_start();
require_once("core/coredb.php");
$data = new data;
$empresa = $_SESSION['empresa'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
<script src="kendoui/js/jquery.min.js"></script>
<script src="kendoui/js/kendo.web.min.js"></script>


<link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="kendoui/styles/kendo.default.min.css" rel="stylesheet" />
<link href="css/estilo.css" rel="stylesheet" />
<script>
$(document).ready(function(e) {
	$("#buscaCliente").hide();
    $("#panelbar").kendoPanelBar();
	
	
	$("#clienteFiscales").attr('checked',false);
	$("#clientePais").removeAttr("required");
	hidePanel("#clienteFiscales","#receptor-opcionales-content");
	
	$("#clienteFiscales").click(function(e){
		if(this.checked){
			$("#clientePais").attr("required","required");
			showPanel("#clienteFiscales","#receptor-opcionales-content");
		}else{
			$("#clientePais").removeAttr("required");
			hidePanel("#clienteFiscales","#receptor-opcionales-content");
		}
		resizeMe();
	});
	
	function hidePanel(object, content){
		$(content).hide();
		$(object).height(10);
	};
	function showPanel(object, content){
		$(object).height("auto");
		$(content).show();
	};
});
</script>
<script>
function resizeMe() {
var doc = document.getElementById("content");
var he = doc.offsetHeight + 50;

/*
var txtTotal = document.getElementById("txtSubTotal").value;
*/
parent.resizeIframe(he,0);      
}
</script>
<script>
function buscaEmpresa(){
	var onclose = function(){
		$("#buscaCliente").hide();
		var valores = $("#returns").val();
		$.getJSON("returnCliente.php?id="+valores,function(json){
			$("#clienteRFC").val(json[0].rfc);
			$("#clienteNombre").val(json[0].nombre);
			$("#clienteCalle").val(json[0].calle);
			$("#clienteExterior").val(json[0].exterior);
			$("#clienteInterior").val(json[0].interior);
			$("#clienteColonia").val(json[0].colonia);
			$("#clienteLocalidad").val(json[0].localidad);
			$("#clienteMunicipio").val(json[0].municipio);
			$("#clienteEstado").val(json[0].estado);
			$("#clientePais").val(json[0].pais);
			$("#clienteCP").val(json[0].cp);
		});
	}
	var Dwindow = $("#buscaCliente");
	if(!Dwindow.data("kendoWindow")){
		Dwindow.kendoWindow({
			top: 0,
			width: "80%",
			height:500,
			content: "ClientesList.php",
			modal: true,
			iframe: true,
			close: onclose
		});
	}
	Dwindow.data("kendoWindow").center().open();
	$("#buscaCliente").show();
}
</script>
</head>

<body onload="resizeMe();">
<div id="returns">
</div>
<div id="buscaCliente" style="background-color:#FFF;" >
</div>
<div class="k-block" id="content">
<fieldset id="miEmpresa">
	<legend>Datos de Mi Empresa</legend>
    <ul class="listForm">
    	<li><a>RFC:</a><span><?php echo $empresa->rfc; ?></span></li>
    	<li><a>Nombre:</a><span><?php echo $empresa->nombre; ?></span></li>
    </ul>
    <br />
    <ul class="listForm">
    	<li><a>Domicilio:</a><span><?php echo $data->get_domicilio($empresa); ?></span></li>
        <li><a>Codigo Postal:</a><span><?php echo $empresa->cp; ?></span></li>
    </ul>
</fieldset>
<fieldset id="receptor">
<form action="setSession.php?session_name=cliente&redirect=CapturaArticulos.php" method="post">
	<legend>Datos del Receptor</legend>
    <div id="receptor-Content" class="receptor-Content">
      <ul class="listForm">
      <li>
      	<input type="button" value="Buscar Empresa" class="k-button" onclick="buscaEmpresa();" />
      </li>
      <li><label>RFC :</label><input class=" k-textbox width-200" type="text" id="clienteRFC" required="required" name="clienteRFC" /></li>
      <li><label>Nombre/Razon Social :</label><input class="k-textbox width-600" type="text" id="clienteNombre" name="clienteNombre" /></li>
      </ul>
    </div>
    <fieldset id="receptor-opcionales">
    	<legend><input type="checkbox" class="k-checkbox" id="clienteFiscales" name="clienteFiscales" /><span>Datos Fiscales (opcionales)</span></legend>
        <div id="receptor-opcionales-content">
        	<ul class="listForm">
            	<li><p><label>CALLE :</label></p><input id="clienteCalle" type="text" name="clienteCalle" class="k-textbox width-300" /></li>
                <li><p><label>NO. EXTERIOR:</label></p><input id="clienteExterior" type="text" name="clienteExterior" class="k-textbox width-200" /></li>
                <li><p><label>NO. INTERIOR :</label></p><input id="clienteInterior" type="text" name="clienteInterior" class="k-textbox width-200" /></li>
                <li><p><label>COLONIA :</label></p><input id="clienteColonia" type="text" name="clienteColonia" class="k-textbox width-300" /></li>
                <li><p><label>LOCALIDAD :</label></p><input id="clienteLocalidad" type="text" name="clienteLocalidad" class="k-textbox width-300" /></li>
                <li><p><label>MUNICIPIO :</label></p><input id="clienteMunicipio" type="text" name="clienteMunicipio" class="k-textbox width-300" /></li>
                <li><p><label>ESTADO :</label></p><input id="clienteEstado" type="text" name="clienteEstado" class="k-textbox width-300" /></li>
                <li><p><label>PAIS :</label></p><input id="clientePais" type="text" name="clientePais" class="k-textbox width-200" required="required" /></li>
                <li><p><label>CP :</label></p><input id="clienteCP" type="text" name="clienteCP" class="k-textbox width-200" /></li>
                <li><p><label>REFERENCIA :</label></p><input type="text" name="clienteReferencia" class="k-textbox width-300" /></li>
            </ul>    
        </div>
    </fieldset>
    <div class="k-block padding-20 alignRight">
    <input type="submit" value="Siguiente" class="k-button"/>
    </div>
    </form>
</fieldset>

<!--
<textarea style="width:100%; height:500px;">
<?php
print_r($_SESSION);
print_r($empresa);
?>
</textarea>
-->
</div>
</body>
</html>