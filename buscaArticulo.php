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
    loadGrid();
});
function loadGrid(){
	var sharedDataSource = new kendo.data.DataSource({
		transport:{
			read:{
				url: "getArticulos.php",
				dataType: "json"
			},
			create:{
				url: "createArticulo.php",
				dataType: "jsonp"
			},
			update:{
				url: "updateArticulos.php",
				dataType: "jsonp"
			},
			destroy:{
				url: "deleteArticulos.php",
				dataType: "jsonp"
			}
		},
		batch:true,
		schema:{
			model:{
				id:"id",
				fields:{
					id:{ editable: false, nullable: true },
					unidad: { validation: { required: true } },
					clave: { validation: { required: true } },
					descripcion: { validation: { required: true } },
					precio: {type:"number", format:"{0:c4}",validation: { required: true } }
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
			{ title: "UNIDAD", field: "unidad", width: 100},
			{ title: "CLAVE", field: "clave", width: 100},
			{ title: "DESCRIPCION", field: "descripcion", width: 300},
			{ title: "PRECIO", field: "precio", width: 100, format:"{0:c4}"}
		],
		toolbar:[{name:"selection", template: '#= selecteds()#'},{name:"create",text:"Agregar Articulo"}],
		selectable: true,
		sortable: true,
		filterable:true,
		resizable:true,
		pageable:true,
		editable: "inline"
	});	
}
function selecteds(e){
	return '<a class="k-button k-button-icontext" href="#" onclick="selects()"><span class="k-icon k-i-tick"></span>Seleccionar</a>';
	
}
function selects(){
var grid = $("#grid").data("kendoGrid");
    var selectedRow = grid.select();
	var itemx = grid.dataItem(selectedRow);
    console.log(itemx.id);
	
	window.parent.$("#returns").val(itemx.id);
	
	window.parent.$("#BuscaArt").data("kendoWindow").close();
}
</script>
<style type="text/css">
.k-block{
	font-size:9px;	
}
</style>
</head>

<body>

<div id="content" class="k-block">
<div id="grid"></div>
</div>


</body>
</html>