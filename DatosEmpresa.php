<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
<?php
$url = "setSession.php?session_name=miEmpresa&redirect=datosCfdi.php?r=1";
if(isset($_GET['save'])){
$url = "setSession.php?session_name=miEmpresa&redirect=saveEmpresa.php";	
}
include("includes.html");
?>
<script>
function resizeMe() {
var doc = document.getElementById("content");
var he = doc.offsetHeight + 50;
parent.resizeIframe(he,0);      
}
</script>
<script>
$(document).ready(function(e) {
	$.getJSON("getEmpresa.php",function(data){
		$("input[name='miRFC']").val(data.rfc);
		$("input[name='miNombre']").val(data.nombre);
		$("input[name='miCalle']").val(data.calle);
		$("input[name='miExt']").val(data.exterior);
		$("input[name='miInt']").val(data.interior);
		$("input[name='miColonia']").val(data.colonia);
		$("input[name='miLocalidad']").val(data.localidad);
		$("input[name='miMunicipio']").val(data.municipio);
		$("input[name='miEstado']").val(data.estado);
		$("input[name='miPais']").val(data.pais);
		$("input[name='miCP']").val(data.cp);
		$("input[name='miLogo']").val(data.logo);
		$("input[name='miCer']").val(data.certificado);
	});
});
</script>
</head>

<body onload="<?php echo ($_GET['r']==0)?'':'resizeMe();';?>">
<div id="content" class="k-block">
<form action="<?php echo ($url); ?>" method="post">
<fieldset>
	<legend>Datos de Mi Empresa</legend>
    <div id="itemsFloat" style="overflow:hidden;">
    <ul class="listForm">
    	<li><p><label>RFC:</label></p><p><input type="text" class="k-textbox width-200" required="required" name="miRFC" /></p></li>
        <li><p><label>Nombre/R. Social:</label></p><p><input type="text" class="k-textbox width-600" required="required" name="miNombre" /></p></li>
      	<li><p><label>Calle:</label></p><p><input type="text" class="k-textbox width-200" name="miCalle" /></p></li>
        <li><p><label>Num. Ext:</label></p><p><input type="text" class="k-textbox width-100" name="miExt" /></p></li>
        <li><p><label>Num. Int:</label></p><p><input type="text" class="k-textbox width-100" name="miInt" /></p></li>
        <li><p><label>Colonia:</label></p><p><input type="text" class="k-textbox width-200" name="miColonia" /></p></li>
        <li><p><label>Localidad:</label></p><p><input type="text" class="k-textbox width-200" name="miLocalidad" /></p></li>
        <li><p><label>Municipio:</label></p><p><input type="text" class="k-textbox width-200" name="miMunicipio" /></p></li>
        <li><p><label>Estado:</label></p><p><input type="text" class="k-textbox width-200" name="miEstado" /></p></li>
        <li><p><label>Pais:</label></p><p><input type="text" class="k-textbox width-200" name="miPais" required="required" /></p></li>
        <li><p><label>C.P:</label></p><p><input type="text" class="k-textbox width-200" name="miCP" /></p></li>
    </ul>
    </div>
    <div style="overflow:hidden; margin-top:30px;">
    <fieldset>
        	<legend>Datos De Sellado</legend>
            <ul class=" listForm">
            <li><p><label>No. de Serie Certificado:</label></p><p><input type="text" maxlength="20" min="20" class="k-textbox width-200" name="miCer" required="required" /></p></li>
            <li><p><label>Url Logotipo:</label></p><p><input type="url" class="k-textbox width-200" name="miLogo" required="required" /></p></li>
            <li><p></p><p><input type="submit" value="ACEPTAR" class="k-button width-200" /></p></li>
            </ul>
    </fieldset>
    </div>
</fieldset>
</form>

</div>
</body>
</html>