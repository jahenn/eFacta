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
</script>
<script>
function loadGrid(){
		var xdataSource = new kendo.data.DataSource({
			transport:{
				create: {
					url: "createCliente.php",
					dataType: "jsonp"	
				},
				read: {
					url: "getClientes.php",
					dataType: "json"
					},
				update:{
					url: "updateClientes.php",
					dataType: "jsonp"
				},
				destroy:{
					url: "deleteCliente.php",
					dataType: "jsonp"	
				},
				parameterMap: function(options, operation) {
					if (operation !== "read" && options.models) {
						return {models: kendo.stringify(options.models)};
					}
			  	}
			},
			batch: true,
			schema: {
			  model: {
				  id: "id",
				  fields: {
					  id: { editable: false, nullable: true },
					  rfc: { validation: { required: true } },
					  nombre: { validation: {required: true } }, 
					  calle: { validation: { required: false }},
					  exterior: { validation: { required: false }},
					  interior: { validation: { required: false }},
					  colonia: { validation: { required: false }},
					  localidad: { validation: { required: false }},
					  municipio: { validation: { required: false }},
					  estado: { validation: { required: false }},
					  pais: { validation: { required: true }},
					  cp: { validation: { required: false }},
					  referencia: { validation: { required: false }}
				  }
			  }
		  },
		  pageSize:9
		});
		$("#grids").kendoGrid({
			dataSource: xdataSource,
			columns: [
				{command: ["edit", "destroy"], title: "&nbsp;", width: "210px" },
				{title:"id",field:"id", width:50},
				{title:"RFC",field: "rfc", width:100},
				{title:"Nombre",field: "nombre", width:100},
				{title:"Calle",field: "calle",width:100},
				{title:"No. Ext.",field: "exterior",width:100},
				{title:"No. Int.",field: "interior",width:100},
				{title:"Colonia",field: "colonia",width:100},
				{title:"Localidad",field: "localidad",width:100},
				{title:"Municipio",field: "municipio",width:100},
				{title:"Estado",field: "estado",width:100},
				{title:"Pais",field: "pais",width:100},
				{title:"C.P.",field: "cp",width:100},
				{title:"Referencia",field: "referencia",width:100}
			],
			pageable: true,
			filterable: true,
			selectable: true,
			toolbar: [{name:"selection", template: '#= selecteds()#'},
						{name:"create",text:"Agregar Cliente"}],
			editable: "inline",
			scrollable: true,
			sortable: true,
			selectable: true
		});	
}
function selects(){
	var grid = $("#grids").data("kendoGrid");
    var selectedRow = grid.select();
	var itemx = grid.dataItem(selectedRow);
    console.log(itemx.rfc);
	
	window.parent.$("#returns").val(itemx.id);
	window.parent.$("#buscaCliente").data("kendoWindow").close();
}
function selecteds(e){
	return '<a class="k-button k-button-icontext" href="#" onclick="selects()"><span class="k-icon k-i-tick"></span>Seleccionar</a>';
}

</script>
<style type="text/css">
.k-grid{
	font-size:9px;
	min-height:450px;
}
</style>
</head>
<body>
<div id="grids"></div>
</body>
</html>