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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="kendoui/js/jquery.min.js"></script>
<script src="kendoui/js/kendo.web.min.js"></script>
<script src="jquery.watermarkinput.js"></script>
<link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="kendoui/styles/kendo.default.min.css" rel="stylesheet" />
<style>
body{
	background-color:#ff9600;
	font-family:Verdana, Geneva, sans-serif;
}
.contents{
	margin-top:100px;
	margin-left:auto;
	margin-right:auto;
	width:500px;
}
input{
	width:280;
}
.k-textbox{
	width:280px;
}
</style>
<script>
$(document).ready(function(e) {
	$("#txtPassword").hide();
    $("#txtUser").Watermark("User RFC");
	$("#fakePassword").Watermark("Password");
	$("#txtPassword").focusout(function(e){
		if($("#txtPassword").val().length <=0){
			$("#txtPassword").hide();
			$("#fakePassword").show();
			$("#fakePassword").Watermark("Passwordxxx");
		}
	});
});
function setPasswordType(clear){
	$("#fakePassword").hide();
	$("#txtPassword").show();
	$("#txtPassword").focus();
}
</script>
</head>
<body>

<div class=" k-block contents">
<table>
<tr>
<td><img src="images/logo.jpg" width="200"></td>
<td>
<form action="" method="post">
<input type="text" name="txtUser" required="required" id="txtUser" class="k-textbox" />
<input type="password" name="txtPassword" required="required" id="txtPassword" class="k-textbox" />
<input type="text" id="fakePassword" class="k-textbox" onFocus="setPasswordType();" />
<input type="submit" value="Entrar" class="k-button" onclick="$.Watermark.HideAll(); setPasswordType();"  name="send"/>
</form>
</td>
</tr>
</table>

</div>
<div style="text-align:center;">
<a href="#nueva" onclick="location.href='nuevaEmpresa.html'">Registra Tu Empresa</a>
</div>
</body>
</html>