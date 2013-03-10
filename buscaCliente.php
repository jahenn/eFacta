<?php
@session_start();

require_once("core/coredb.php");
$datos = new data;
$datos = $datos->get_clientes();
$datos = json_decode($datos);

?>


<html>
<head>
<script type="text/javascript" language="javascript" src="TableFilter/tablefilter.js"></script> 
<script language="javascript" type="text/javascript">  
        var tf = setFilterGrid("table1");  
</script> 
<script language="javascript">
function setValue(values){
	$("#resultado").val(values);
	$("#buscaCliente").data("kendoWindow").close();
}
</script> 
</head>
<body> 
    <table id="table1" cellspacing="0" class="table1 filterable">
    	<tr>
        	<th>RFC</th>
            <th>Nombre/Razon Social</th>
            <th>C.P.</th>
            <th>Select</th>
        </tr>

		<?php
        	foreach($datos as $r){
				echo "<tr>";
					echo "<td>$r->rfc</td>";
					echo "<td>$r->nombre</td>";
					echo "<td>$r->cp</td>";
					//echo "<td width='100'><a href='returnCliente.php?id=$r->id'>Selecciona</a></td>";
					echo '<td width="100"><button onclick="setValue('.$r->id.');">SELECCIONAR</button>';
				echo "</tr>";
			}
        ?>
    </table>
    <div id="resultado">
    </div>
</body>
</html>