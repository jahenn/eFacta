<?php


class data{
	private $conn = null;
	function __construct(){
		$this->conn = pg_connect("dbname=dbname user=user password=password host=host");
	}
	function creaEmpresa($rfc, $pass){
		$result = pg_select($this->conn,"empresas",array("rfc"=>$rfc));
		$result = $result[0];
		
		if($result['rfc'] == $rfc){
		echo "La Empresa No puede ser Registrada, Consulte a su Administrador";
		}else{
		$result = pg_insert($this->conn,"empresas",array("rfc"=>$rfc,"password"=>md5($pass)));
		echo "Empresa Registrada.. puedes Iniciar Sesion";
		echo "<p><a href='index.php'>Iniciar</a></p>";	
		}
	}
	function updateEmpresa($datos){
		$prepare = pg_prepare($this->conn,"updateEmpresa","
		update empresas set rfc = $1, nombre = $2, calle = $3, exterior = $4, interior = $5, 
		colonia = $6, localidad = $7, municipio = $8, estado = $9, pais = $10, cp= $11, logo = $12, 
		certificado = $13 where rfc = $14;");
		
		$result = pg_execute($this->conn,"updateEmpresa",
		array($datos['miRFC'],$datos['miNombre'], $datos['miCalle'],$datos['miExt'],$datos['miInt'],
		$datos['miColonia'], $datos['miLocalidad'], $datos['miMunicipio'], $datos['miEstado'], $datos['miPais'],
		$datos['miCP'], $datos['miLogo'], $datos['miCer'], $datos['miRFC']));
		
		return true;
		
		
		/*
		[miRFC] => RUMN780525F1A
            [miNombre] => EMPRESA FICTICIA SA DE CV
            [miCalle] => san angel
            [miExt] => 77
            [miInt] => 12
            [miColonia] => La Negreta
            [miLocalidad] =>  El Pueblito
            [miMunicipio] => Corregidora
            [miEstado] => Queretaro
            [miPais] => Mexico
            [miCP] => 76920
            [miCer] => 000001
            [miLogo] => http://go.c/logo.jpg
		
		*/
	}
	function updateArticulos($datos){
		$datos = array($datos['unidad'],
						$datos['clave'],
						$datos['descripcion'],
						$datos['precio'],
						$datos['id']);
		$prepare = pg_prepare($this->conn,"updateArts","update articulos set unidad = $1,clave = $2, 
											descripcion = $3, precio = $4 where id= $5 returning *");
		$result = pg_execute($this->conn,"updateArts",$datos);
		$result = pg_fetch_assoc($result);
		//$result = $result[0];
		$result = json_encode($result);
		return $result;
	}
	function deleteArticulos($datos){
		$prepare = pg_prepare($this->conn,"deleteArticulos","delete from articulos where id = $1");
		$result = pg_execute($this->conn,"deleteArticulos",array($datos['id']));
		return $datos;	
	}
	function insertArticulo($datos){
		@session_start();
		$datos = $datos['models'];
		//$datos = json_decode($datos);
		$datos = $datos[0];
		
		$datos = array($datos['unidad'],
						$datos['clave'],
						$datos['descripcion'],
						$datos['precio'],
						$_SESSION['facta']);
	
		$prepare = pg_prepare($this->conn,"createArticulo",
				"insert into articulos (unidad, clave, descripcion, precio, empresa) 
				values($1,$2,$3,$4,$5) returning *");
				
		$result = pg_execute($this->conn,"createArticulo",$datos);
		$result = pg_fetch_assoc($result);
		$result =json_encode($result);
		
		return $result;
	}
	
	function deleteTempArticulos($datos){
		$prepare = pg_prepare($this->conn,"deleteTemp","delete from tmp_prods where id = $1");
		$result = pg_execute($this->conn,"deleteTemp",array($datos['id']));
		
		return $datos;	
	}
	function updateTempArticulos($datos){
		$datos = array($datos['cantidad'],
						$datos['unidad'],
						$datos['clave'],
						$datos['descripcion'],
						$datos['precio'],
						$datos['id']);
		$prepare = pg_prepare($this->conn,"updateTemp","update tmp_prods set cantidad = $1, unidad = $2,clave = $3, 
											descripcion = $4, precio = $5 where id= $6 returning *, (precio * cantidad) as subtotal");
		$result = pg_execute($this->conn,"updateTemp",$datos);
		$result = pg_fetch_assoc($result);
		//$result = $result[0];
		$result = json_encode($result);
		return $result;
	}
	function insertTmpArticulo($datos){
		@session_start();
		$datos = $datos['models'];
		//$datos = json_decode($datos);
		$datos = $datos[0];
		
		$datos = array($datos['unidad'],
						$datos['clave'],
						$datos['descripcion'],
						$datos['precio'],
						session_id());
	
		$prepare = pg_prepare($this->conn,"createTempArticulo",
				"insert into tmp_prods (unidad, clave, descripcion, precio, sesion, cantidad) 
				values($1,$2,$3,$4,$5,1) returning *");
				
		$result = pg_execute($this->conn,"createTempArticulo",$datos);
		$result = pg_fetch_assoc($result);
		$result =json_encode($result);
		
		return $result;
	}
	function deleteCliente($datos){
		$prepare = pg_prepare($this->conn,"deleteCliente","delete from clientes where id = $1");
		$result = pg_execute($this->conn,"deleteCliente",array($datos->id));
		return $datos;	
	}
	function updateCliente($datos){
		$datos = array($datos->rfc,
						$datos->nombre,
						$datos->calle,
						$datos->exterior,
						$datos->interior,
						$datos->colonia,
						$datos->localidad,
						$datos->municipio,
						$datos->estado,
						$datos->pais,
						$datos->cp,
						$datos->referencia,
						$datos->id);
		$prepare = pg_prepare($this->conn,"updateClientes","update clientes set 
											rfc = $1,
											nombre = $2,
											calle = $3,
											exterior = $4,
											interior = $5,
											colonia = $6,
											localidad = $7,
											municipio = $8,
											estado = $9,
											pais = $10,
											cp = $11,
											referencia = $12 where id = $13 returning *");
	$result = pg_execute($this->conn,"updateClientes",$datos);
	$result = pg_fetch_assoc($result);
	return $result;
	}
	function insertCliente($datos){
		@session_start();
		$datos = $_GET["models"];
		$datos = json_decode($datos);
		$datos = $datos[0];
		
		$datos = array($datos->rfc,
						$datos->nombre,
						$datos->cp,
						$datos->calle,
						$datos->exterior,
						$datos->interior,
						$datos->colonia,
						$datos->localidad,
						$datos->municipio,
						$datos->estado,
						$datos->pais,
						$datos->referencia,
						$_SESSION['facta']);
	
		$prepare = pg_prepare($this->conn,"createCliente",
				"insert into clientes (empresa, rfc, fecha, nombre, cp, calle, exterior, interior, colonia, 
				localidad, municipio, estado, pais, referencia) 
				values($13,$1,current_date,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12) returning *");
				
		$result = pg_execute($this->conn,"createCliente",$datos);
		$result = pg_fetch_assoc($result);
		$result =json_encode($result);
		
		return $result;
	}
	function login(){
		$result = pg_select($this->conn,"empresas",array("rfc"=>$_POST['txtUser'],"password"=>md5($_POST['txtPassword'])));
		$result = $result[0];
		if($result['rfc'] == $_POST['txtUser']){
			@session_start();
			$_SESSION['facta'] = $_POST['txtUser'];
			header("Location: ./");
		}
	}
	function get_articulo($id){
		@session_start();
		
		$result = pg_select($this->conn,"articulos",array("id"=>$id));
		return json_encode($result);
	}
	function get_articulos(){
		@session_start();
		$empresa = $_SESSION['facta'];
		
		$result = pg_select($this->conn,"articulos",array("empresa"=>$empresa));
		return json_encode($result);
		
	}
	function get_clientes(){
	@session_start();
	$result = pg_select($this->conn,"clientes",array("empresa"=>$_SESSION['facta']));
	return json_encode($result);
		
	}
	function get_cliente($id){
	@session_start();
	
	$result = pg_select($this->conn,"clientes",array("id"=>$id));
	return json_encode($result);
		
	}
	function get_empresa(){
		@session_start();
	  $result = pg_select($this->conn,"empresas",array("rfc"=>$_SESSION['facta']));
	  $result = $result[0];
	  $result =json_encode($result);
	  return $result;
	}
	function get_domicilio($data,$prefix = ""){
		
		$domi = ($data->calle == null)?"":"Calle ".$data->calle;
		$domi.= ($data->exterior == null)?"":" ".$data->exterior;
		$domi .= ($data->interior == null)?"":"-".$data->interior;
		$domi .= ($data->colonia == null)?"":" Colonia ".$data->colonia;
		$domi .= ($data->localidad == null)?"":", ".$data->localidad;
		$domi .= ($data->municipio == null)?"":", ".$data->municipio;
		$domi .= ($data->estado == null)?"":", ".$data->estado;
		$domi .= ($data->pais == null)?"":", ".$data->pais.".";
		
		$domi = str_replace("  "," ",$domi);
		$domi = str_replace(",,",",",$domi);
		$domi = str_replace("..",".",$domi);
		return $domi;
	}
	function get_domicilioC($data,$prefix = ""){
		
		$domi = ($data->clienteCalle == null)?"":"Calle ".$data->clienteCalle;
		$domi.= ($data->clienteExterior == null)?"":" ".$data->clienteExterior;
		$domi .= ($data->clienteInterior == null)?"":"-".$data->clienteInterior;
		$domi .= ($data->clienteColonia == null)?"":" Colonia ".$data->clienteColonia;
		$domi .= ($data->clienteLocalidad == null)?"":", ".$data->clienteLocalidad;
		$domi .= ($data->clienteMunicipio == null)?"":", ".$data->clienteMunicipio;
		$domi .= ($data->clienteEstado == null)?"":", ".$data->clienteEstado;
		$domi .= ($data->clientePais == null)?"":", ".$data->clientePais.".";
		
		$domi = str_replace("  "," ",$domi);
		$domi = str_replace(",,",",",$domi);
		$domi = str_replace("..",".",$domi);
		return $domi;
	}
	function insert_producto($datos){
		session_start();
		$insert_data = array(
			"cantidad"=>(int)$datos['prod_cant'],
			"unidad"=>$datos['prod_unit'],
			"clave"=>$datos['prod_clave'],
			"descripcion"=>$datos['prod_desc'],
			"precio"=>$datos['prod_precio'],
			"sesion"=>session_id()
		);
		
		$result = pg_insert($this->conn,"tmp_prods", $insert_data);
	}
	function insert_prueba($data){
		$result = pg_insert($this->conn,"prueba",array("nombre"=>$data));
	}
	
	function get_temp_prods(){
		@session_start();
		$result = pg_prepare($this->conn,"get_tmp_prods","select *, (cantidad * precio) as subtotal from tmp_prods where sesion = $1");
		$result = pg_execute($this->conn,"get_tmp_prods",array(session_id()));
		$datos = array();
		while($r = pg_fetch_assoc($result)){
			$datos[] = $r;
		}
		$result = json_encode($datos);
		return $result;
	}
	function get_prods(){
		@session_start();
		$result = pg_prepare($this->conn,"get_prods","select * from productos where empresa = $1");
		$result = pg_execute($this->conn,"get_prods",array($_SESSION['facta']));
		$datos = array();
		while($r = pg_fetch_assoc($result)){
			$datos[] = $r;
		}
		$result = json_encode($datos);
		return $result;
	}
	function get_prueba(){
		$result = pg_query($this->conn,"select * from prueba");
		$datos = array();
		while($r = pg_fetch_assoc($result)){
			$datos[] = $r;
		}
		$result = json_encode($datos);
		return $result;
	}
}

?>