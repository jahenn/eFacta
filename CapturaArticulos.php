<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
<script src="kendoui/js/jquery.min.js"></script>
<script src="kendoui/js/kendo.web.min.js"></script>
<script src="jquery.watermarkinput.js"></script>
<link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="kendoui/styles/kendo.default.min.css" rel="stylesheet" />
<link href="css/estilo.css" rel="stylesheet" />
<style type="text/css">
.k-grid{
	font-size:9px;	
}
</style>
</head>
<script language="javascript">
$(document).ready(function(e) {
    loadGrid();
});
function loadGrid(){
	var sharedDataSource = new kendo.data.DataSource({
		transport:{
			read:{
				url: "getTempArtculos.php",
				dataType: "json"
			},
			update:{
				url: "updateTempArticulos.php",
				dataType: "jsonp"
			},
			destroy:{
				url: "deleteTempArticulos.php",
				dataType: "jsonp"
			}
		},
		batch:true,
		schema:{
			model:{
				id:"id",
				fields:{
					id:{ editable: false, nullable: true },
					cantidad: {type:"number", validation: { required: true } },
					unidad: { validation: { required: true } },
					clave: { validation: { required: true } },
					descripcion: { validation: { required: true } },
					precio: {type:"number", validation: { required: true } } ,
					subtotal:{editable:false, nullable:true, type:"number"}
				}
			}
		},
		pageSize: 9,
		aggregate:[
			{field:"subtotal", aggregate: "sum"}
		]
	});
	$("#grid").kendoGrid({
		dataSource: sharedDataSource,
		columns: [
			{command: ["edit", "destroy"], title: "&nbsp;", width: "150px" },
			{ title: "ID", field: "id", width: 50, filterable:false},
			{ title: "CANTIDAD", field: "cantidad", width: 80, filterable:false},
			{ title: "UNIDAD", field: "unidad", width: 100},
			{ title: "CLAVE", field: "clave", width: 100},
			{ title: "DESCRIPCION", field: "descripcion", width: 300},
			{ title: "PRECIO", field: "precio", width: 100, format:"{0:c}"},
			/*{ title: "SUBTOTAL", field: "subtotal", width: 100, format:"{0:c}"},*/
			{field:"subtotal", width:100, footerTemplate: "<div style='font-size:16px; color:red;'>#= kendo.toString(sum, 'c') #</div>", title:"SUBTOTAL", format:"{0:c}"}
		],
		toolbar:[{name:"Agregar", template:"#=addArt()#"}],
		selectable: true,
		sortable: true,
		filterable:true,
		resizable:true,
		pageable:true,
		editable: "inline"
	});	
}
function addArt(){
	return '<a class="k-button k-button-icontext" href="#" onclick="addArtFunc()"><span class="k-icon k-i-plus"></span>Agregar</a>';
}

function addArtFunc(){
	var onClose = function(){
		var ids = $("#returns").val();
		$.ajax({
			type: "GET",
			data: "id="+ids,
			url: "SelectArticulo.php"
		}).done(function(data){
			loadGrid();
		});
	}
	var kw = $("#BuscaArt");
	if(!kw.data("kendoWindow")){
		kw.kendoWindow({
			top: 0,
			width: "80%",
			height: "80%",
			modal: true,
			iframe: true,
			content: "buscaArticulo.php",
			close: onClose
		})
	}
	kw.data("kendoWindow").center().refresh();
	kw.data("kendoWindow").center().open();
	resizeMe();
}
function resizeMe(){
	var doc = document.getElementById("content");
	var he = doc.offsetHeight + 50;
	parent.resizeIframe(he,0); 
}
</script>
<body onload="resizeMe();" onresize="resizeMe();">
<div id="returns"></div>
<div class="k-block" id="content">
<div id="BuscaArt"></div>
<div id="grid" style=" height:400px;">
</div>
<div id="footer" class=" k-block alignRight padding-20">
<button class="k-button" onclick="location.href='DatosEmpresa.php?r=1'">Siguiente</button>
</div>
</div>
</body>
</html>