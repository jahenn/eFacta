<?php
	require_once("getTotal.php");
	@session_start();
	$de = $_SESSION['empresa'];
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
var rets = 0;
$(document).ready(function(e) {
	load_datos();
	$("#tipoCombo").kendoDropDownList();
	$("#comboMoneda").kendoDropDownList();
	$("#retenciones").click(function(e){
		var rIva = $("input[name='rIva']");
		var rISR = $("input[name='rISR']");
		if(this.checked){
			console.log("cheked");
			rIva.removeAttr("readonly");
			rIva.css({
				"background-color":"orange"
			});
			
			rISR.removeAttr("readonly");
			rISR.css({
				"background-color":"orange"
			});
			rets = 1;
		}else{
			console.log("un cheked");
			rIva.attr("readonly","readonly");
			rIva.css({
				"background-color":"#ccc"
			});
			rISR.attr("readonly","readonly");
			rISR.css({
				"background-color":"#ccc"
			});
			
			rets = 0;
		}
		console.log(rets);
		calcula();
	});
	calcula();
});
function resizeMe() {
var doc = document.getElementById("content");
var he = doc.offsetHeight + 50;
parent.resizeIframe(he,0);      
}

function load_datos(){
	$("input[name='lugarExp']").val("<?php echo($de->lugar_exp); ?>");
	$("input[name='regimenFiscal']").val("<?php echo($de->regimen); ?>");
	$("input[name='formaPago']").val("<?php echo($de->forma_pago); ?>");
	$("input[name='metodoPago']").val("<?php echo($de->metodo_pago); ?>");
	$("input[name='tipoCambio']").val("<?php echo($de->tipo_cambio); ?>");
	
}

function calcula(){
	var risr = $("input[name='rISR']").val()/100;
	var riva = $("input[name='rIva']").val()/100;
	var iva = $("input[name='pIva']").val()/100;
	var xTotal = $("input[name='subtotal']").val();
	
	if(rets == 0){
		risr = 0;
		riva = 0;
	}
	
	risr = xTotal * risr;
	riva = xTotal * riva;
	iva = xTotal * iva;
	
	var impuestos = Number(iva) - Number(risr) - Number(riva);
	
	xTotal = Number(xTotal) + Number(impuestos);
	
	$("input[name='total']").val(xTotal);
	$("input[name='xiva']").val(iva);
	$("input[name='xriva']").val(riva);
	$("input[name='xrisr']").val(risr);
}

</script>
<style type="text/css">
input[name='rIva'], input[name='rISR']{
	background-color:#ccc;
	color:#FFF;
}
</style>
</head>
<body onload="<?php echo ($_GET['r']==0)?'':'resizeMe();';?>">
<div id="content" class="k-block">
<form action="setSession.php?session_name=documento&redirect=procesa.php" method="post">
<fieldset>
<legend>Datos del Documento</legend>
<fieldset>
	<legend>Impuestos</legend>
    <div id="impContent">
    <p>Aplicar Retenciones</p><input type="checkbox" id="retenciones" name="retenciones" />
    <ul class="listForm">
    <li><p><label>SubTotal: </label></p><p><input type="number" required="required" name="subtotal" class="k-textbox" readonly="readonly" value="<?php echo $total; ?>" /></p></li>
    <li><p><label>IVA %</label></p><p><input type="number" required="required" name="pIva" class="k-textbox" value="<?php echo $de->iva; ?>" onkeyup="calcula();" /></p></li>
    <li><p><label>Ret. IVA %</label></p><p><input type="number" required="required" name="rIva" class="k-textbox" readonly="readonly" value="<?php echo $de->ret_iva; ?>" onkeyup="calcula();"/></p></li>
    <li><p><label>Ret. ISR %</label></p><p><input type="number" required="required" name="rISR" class="k-textbox" readonly="readonly" value="<?php echo $de->ret_isr; ?>" onkeyup="calcula();"/></p></li>
    <li><p><label>Total: </label></p><p><input type="number" required="required" name="total" class="k-textbox" readonly="readonly" /></p></li>
    </ul>
    </div>
    <input type="number" name="xriva" style="visibility:hidden" />
    <input type="number" name="xrisr" style="visibility:hidden" />
    <input type="number" name="xiva" style="visibility:hidden" />
</fieldset>
<fieldset>
	<legend>Datos del Documento</legend>
    <div id="datosCont">
    <ul class="listForm">
    <li><p><label>Tipo de Documento: </label></p>
    <select id="tipoCombo" name="tipoCombo">
    <option>ingreso</option>
    <option>egreso</option>
    <option>traslado</option>
    </select>
    </li>
    <li><p><label>Serie: </label></p><p><input type="text" name="serie" class="k-textbox" /></p></li>
    <li><p><label>Folio: </label></p><p><input type="text" name="folio" class="k-textbox" /></p></li>
    </ul>
    </div>
</fieldset>
<fieldset>
	<legend>Datos Adicionales</legend>
    <div id="datosCont2">
    
    <ul class="listForm">
    <li><p><label>Lugar de Expedicion: </label></p><p><input type="text" required="required" name="lugarExp" class="k-textbox" /></p></li>
    <li><p><label>Regimen Fiscal: </label></p><p><input type="text" required="required" name="regimenFiscal" class="k-textbox" /></li>
    <li><p><label>Forma de Pago: </label></p><p><input type="text" required="required" name="formaPago" class="k-textbox" value="PAGO EN UNA SOLA EXHIBICION"/></li>
    <li><p><label>Metodo de Pago: </label></p><p><input type="text" required="required" name="metodoPago" class="k-textbox" /></li>
    <li><p><label>Tipo de Cambio: </label></p><p><input type="text" required="required" name="tipoCambio" class="k-textbox" /></li>
    <li><p><label>Moneda</label></p><p>
    <select id="comboMoneda" name="comboMoneda">
    <option>MXN</option>
    <option>USD</option>
    </select>
    </p></li>
    <!--<li><p><label>Condiciones de Pago: </label></p><p><input type="text" required="required" name="condicionesPago" class="k-textbox" /></li>-->
    <li><p><label>Numero Cta. Pago: </label></p><p><input type="text" required="required" name="noCuenta" class="k-textbox" /></li>
    <li><p><p><input type="submit"  class="k-button width-200" value="ACEPTAR" /></p></p></li>
    </ul>
    </div>
</fieldset>
</fieldset>
</form>
</div>
</body>
</html>