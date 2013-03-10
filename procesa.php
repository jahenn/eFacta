
<?php
@session_start();
require_once("core/coredb.php");
include_once 'ecodex/lib/nusoap.php';
include_once 'ecodex/class/Seguridad.class.php';
include_once 'ecodex/class/Comprobantes.class.php';
include_once 'ecodex/class/Timbrado.class.php';
require_once("dompdf/dompdf_config.inc.php");

require_once("letras.php");

$datos = json_encode($_SESSION);
$datos = json_decode($datos);
$integrador = "6da8d0d2-cce2-43a5-ae9a-9e739ca5a46f";

if($_SESSION['facta'] == 'AAA010101AAA'){
	$integrador = "2b3a8764-d586-4543-9b7e-82834443f219";
}

$doc = new generaCFDI;

//$doc->rfc = $datos->facta;
$miXML = $doc->creaXML($datos);

$token = new Seguridad;
$trsID = rand(1, 10000);
$generaToken = $token->setToken($datos->facta, $trsID, $integrador);
$getToken = $token->getToken();

$ComprobanteXML = $miXML;                
	 //echo "<p><TEXTAREA ID='cadenaXML' cols='100' rows='15'>".$miXML."</TEXTAREA></p>";
	 //exit();			
	$SellaTimbra = new Comprobantes();
	$trsID = rand(1, 10000);
	$sellar = $SellaTimbra->setXMLSellado( $ComprobanteXML, $datos->facta, $trsID, $getToken);                              
	$getXmlSellado = $SellaTimbra->getXMLSellado();
	
	//echo "<p><TEXTAREA ID='cadenaXML' cols='100' rows='15'>".$getXmlSellado."</TEXTAREA></p>";
	//exit();
	 
	 
	
//$getXmlSellado = file_get_contents("cfdi.xml");
$timbre = $doc->procesaXML($getXmlSellado);

//$qr = $doc->getQR($datos->facta,$integrador,$timbre['uuid']);

if($timbre == false){
	echo "<textarea>$getXmlSellado</textarea>";
	exit();
}

$file_xml = "cfdis/".$timbre['uuid'].".xml";
$file_QRCode = "cfdis/".$timbre['uuid'].".png";
$file_PDF = "cfdis/".$timbre['uuid'].".pdf";

file_put_contents($file_xml,$getXmlSellado);

//QR

//file_put_contents($file_QRCode,base64_decode($qr));
include "phpqrcode/qrlib.php"; 
$total = $datos->documento->total;
$total = number_format($total,2,".","");
$total = explode(".",$total);

//print_r($total);

$total[0] = str_pad($total[0], 10, "0", STR_PAD_LEFT);
$total[1] = str_pad($total[1], 6, "0", STR_PAD_RIGHT);

//$total = ()

$datoQR = "?re=".$datos->facta."&rr=".$datos->cliente->clienteRFC."&tt=".$total[0].".".$total[1]."&id=".$timbre['uuid'];
QRcode::png($datoQR,$file_QRCode,'L',8,2);
$templateArticulos = "";

$templateArt = '
<tr>
<td class="conBorder" width="60">@cantidad</td>
<td class="conBorder" width="60">@unidad</td>
<td class="conBorder" width="60">@clave</td>
<td class="conBorder" width="260">@descripcion</td>
<td class="conBorder" width="65">$@precio</td>
<td class="conBorder" width="65">$@importe</td>
</tr>
';

$template = file_get_contents("facturaTemplate.html");
$template = str_replace("@imagen",$datos->miEmpresa->miLogo,$template);
$template = str_replace("@qr",$file_QRCode,$template);

$template = str_replace("@empresa",$datos->miEmpresa->miNombre,$template);
$template = str_replace("@uuid",$timbre['uuid'],$template);
$template = str_replace("@rfc",$datos->facta,$template);
$template = str_replace("@certificado",$timbre['cert'],$template);

$base = new data;
$domicilio = $base->get_domicilio($datos->empresa);
$productos = $base->get_temp_prods();
$productos = json_decode($productos);



foreach($productos as $value){
	
	$temp = $templateArt;
	$temp = str_replace("@cantidad",$value->cantidad,$temp);
	$temp = str_replace("@unidad",$value->unidad,$temp);
	$temp = str_replace("@clave",$value->clave,$temp);
	$temp = str_replace("@descripcion",$value->descripcion,$temp);
	$temp = str_replace("@precio",$value->precio,$temp);
	$temp = str_replace("@importe",$value->subtotal,$temp);
	$templateArticulos .= $temp;
}
$template = str_replace("@articulos",$templateArticulos,$template);

$template = str_replace("@domicilio",$domicilio,$template);
$template = str_replace("@lugarFecha",$timbre['LugarExpedicion'].", ".$timbre['fecha'],$template);

$template = str_replace("@cNombre",$datos->cliente->clienteNombre,$template);
$template = str_replace("@cRFC",$datos->cliente->clienteRFC,$template);


$domicilio = $base->get_domicilioC($datos->cliente);
$template = str_replace("@cDomicilio",$domicilio,$template);

$template = str_replace("@Regimen",$datos->documento->regimenFiscal,$template);

$template = str_replace("@subtotal",number_format($datos->documento->subtotal,2),$template);
$template = str_replace("@iva",number_format($datos->documento->xiva,2),$template);
$template = str_replace("@riva",number_format($datos->documento->xriva,2),$template);
$template = str_replace("@risr",number_format($datos->documento->xrisr,2),$template);
$template = str_replace("@total",number_format($datos->documento->total,2),$template);

$template = str_replace("@efectivo",$timbre['metodoDePago'],$template);


$sello = $timbre['selloSAT'];
if(strlen($sello) >= 100 ){
	$sello = substr($sello,0,99)."<br/>".substr($sello,50,1000);
}
$template = str_replace("@selloSAT",$sello, $template);

$sello = null;
$sello = $timbre['sello'];
if(strlen($sello) >= 100 ){
	$sello = substr($sello,0,99)."<br/>".substr($sello,50,1000);
}
$template = str_replace("@sello",$sello, $template);

$cadena = "||1.0|@uid|@fecha|@sello|@certificado||";
$cadena = str_replace("@uid",$timbre['uuid'],$cadena);
$cadena = str_replace("@fecha",$timbre['fechaSAT']."<br/>",$cadena);
$cadena = str_replace("@sello",$sello."<br/>",$cadena);
$cadena = str_replace("@certificado",$timbre['certificado'],$cadena);

$template = str_replace("@cadena",$cadena,$template);

$template = str_replace("@certSAT",$timbre['certificado'],$template);
$template = str_replace("@fechiaSAT",$timbre['fechaSAT'],$template);

$enletra = new EnLetras;
$letra = $enletra->ValorEnLetras($datos->documento->total,"Pesos");

$template = str_replace("@letras",$letra,$template);

$dompdf = new DOMPDF();

$dompdf->load_html($template);
@$dompdf->render();


file_put_contents($file_PDF,$dompdf->output());

class TypeOfDocument
{
    public $Comprobante = 0;
    public $Emisor = 1;
	public $DomFiscal = 2;
	public $Regimen = 3;
	public $Receptor = 4;
	public $Conceptos = 5;
	public $Concepto = 6;
	public $Impuestos= 7;
	public $Traslados = 8;
	public $Traslado = 9;
	public $Domicilio = 10;
}

class generaCFDI{
	private $doc = null;
	private $Comprobante = null;
	private $emisor = null;
	private $domiFiscal = null;
	private $regimen = null;
	private $receptor = null;
	private $conceptos = null;
	private $concepto = null;
	private $impuestos = null;
	private $traslados = null;
	private $traslado = null;
	
	private $domicilio_rec = null;
	
	private $retenciones = null;
	private $retencion = null;
	
	//private $integrador = "6da8d0d2-cce2-43a5-ae9a-9e739ca5a46f";
	//public $rfc = "";//"RUMN780525F1A";
	function getQR($rfc, $integrador,$uid){
		@session_start();
		
		$trsID = rand(1,10000);
		$token = new Seguridad;
		$token->setToken($rfc,$trsID,$integrador);
		$mitoken = $token->getToken();
		
		$timbre_x = new Timbrado;
		$timbre_x->setQR($rfc,$mitoken,$trsID,$uid);

		return $timbre_x->getQR();	
	}
	function procesaXML($getXmlSellado){
		
		$origen = $getXmlSellado;
		
		$getXmlSellado = str_replace("cfdi:","cfdi_",$getXmlSellado);
		$getXmlSellado = str_replace("tfd:","tfd_",$getXmlSellado);
		
		$getXmlSellado = @simplexml_load_string($getXmlSellado);
		
		$getXmlSellado = json_encode($getXmlSellado); // convierte de objeto xml a string json
		
		$getXmlSellado = str_replace("@attributes","atributo",$getXmlSellado);
		$getXmlSellado = json_decode($getXmlSellado);	
		
		
		if(!is_object($getXmlSellado)){
			return false;
		}
		
		
		$results = array();
		$results['uuid'] = $getXmlSellado->cfdi_Complemento->tfd_TimbreFiscalDigital->atributo->UUID;
		$results['fecha'] = $getXmlSellado->atributo->fecha;
		$results['LugarExpedicion'] = $getXmlSellado->atributo->LugarExpedicion;
		$results['metodoDePago'] = $getXmlSellado->atributo->metodoDePago;
		$results['sello'] = $getXmlSellado->atributo->sello;
		$results['selloSAT'] = $getXmlSellado->cfdi_Complemento->tfd_TimbreFiscalDigital->atributo->selloSAT;
		$results['fechaSAT'] = $getXmlSellado->cfdi_Complemento->tfd_TimbreFiscalDigital->atributo->FechaTimbrado;
		$results['certificado'] = $getXmlSellado->cfdi_Complemento->tfd_TimbreFiscalDigital->atributo->noCertificadoSAT;
		$results['cert'] = $getXmlSellado->atributo->noCertificado;
		return $results;
	}
	function __construct(){
		$this->doc = new DomDocument("1.0","utf-8");
		$this->Comprobante = $this->doc->createElement("cfdi:Comprobante"); //0
		$this->emisor = $this->doc->createElement("cfdi:Emisor");//1
		$this->domiFiscal = $this->doc->createElement("cfdi:DomicilioFiscal"); //2
		$this->regimen = $this->doc->createElement("cfdi:RegimenFiscal");//3
		$this->receptor = $this->doc->createElement("cfdi:Receptor"); //4
		$this->conceptos = $this->doc->createElement("cfdi:Conceptos"); //5
		$this->concepto = $this->doc->createElement("cfdi:Concepto"); // 6
		$this->impuestos = $this->doc->createElement("cfdi:Impuestos"); //7
		$this->traslados = $this->doc->createElement("cfdi:Traslados"); // 8
		$this->traslado = $this->doc->createElement("cfdi:Traslado"); // 9
		$this->domicilio_rec = $this->doc->createElement("cfdi:Domicilio"); // 10
		
		$this->retenciones = $this->doc->createElement("cfdi:Retenciones"); // 11
		$this->retencion = $this->doc->createElement("cfdi:Retencion"); // 12
		
	}	
	public function creaAtributo($elemento,$valor,$cual){
		if(($valor == null) OR ($valor == "")){
			return 0;
		}
		$att = $this->doc->createAttribute($elemento);
		$att->value = $valor;
		if($cual == 0){
			$this->Comprobante->appendChild($att);
		}elseif($cual == 1){
			$this->emisor->appendChild($att);
		}elseif($cual == 2){
			$this->domiFiscal->appendChild($att);
		}elseif($cual == 3){
			$this->regimen->appendChild($att);
		}elseif($cual == 4){
			$this->receptor->appendChild($att);
		}elseif($cual == 5){
			$this->concepto->appendChild($att);
		}elseif($cual == 7){
			$this->impuestos->appendChild($att);
		}elseif($cual == 9){
			$this->traslado->appendChild($att);
		}elseif($cual == 10){
			$this->domicilio_rec->appendChild($att);
		}elseif($cual == 12){
			$this->retencion->appendChild($att);
		}
		
	}
	
	public function creaXML($datos){
		
		$base = new data;
		$prods =  $base->get_temp_prods();
		$prods = json_decode($prods);
		
		$tb = new TypeOfDocument;
		$this->creaAtributo("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance",$tb->Comprobante);
		$this->creaAtributo("xsi:schemaLocation","http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd",0);
		$this->creaAtributo("xmlns:cfdi","http://www.sat.gob.mx/cfd/3",0);
		$this->creaAtributo("version","3.2",0);
		
		$fecha = date("Y-m-d")."T".date("H:i:s");
		
		$this->creaAtributo("fecha",$fecha,0);
		$this->creaAtributo("formaDePago",$datos->documento->formaPago,0);
		$this->creaAtributo("noCertificado",$datos->miEmpresa->miCer,0);  //esto falta
		$this->creaAtributo("subTotal",number_format($datos->documento->subtotal,2,".",""),0);
		$this->creaAtributo("TipoCambio",number_format($datos->documento->tipoCambio,2,".",""),0);
		$this->creaAtributo("Moneda",$datos->documento->comboMoneda,0);
		$this->creaAtributo("total",number_format($datos->documento->total,2,".",""),0);
		$this->creaAtributo("tipoDeComprobante",$datos->documento->tipoCombo,0);
		$this->creaAtributo("metodoDePago",$datos->documento->metodoPago,0);
		$this->creaAtributo("LugarExpedicion",$datos->documento->lugarExp,0);
		$this->creaAtributo("NumCtaPago",$datos->documento->noCuenta,0);
		
		//Emisor
		$this->creaAtributo("rfc",$datos->empresa->rfc,1);
		$this->creaAtributo("nombre",$datos->empresa->nombre,1);
		
		$this->creaAtributo("calle",$datos->empresa->calle,2);
		$this->creaAtributo("noExterior",$datos->empresa->exterior,2);
		$this->creaAtributo("noInterior",$datos->empresa->interior,2);
		$this->creaAtributo("colonia",$datos->empresa->colonia,2);
		$this->creaAtributo("localidad",$datos->empresa->localidad,2);
		$this->creaAtributo("municipio",$datos->empresa->municipio,2);
		$this->creaAtributo("estado",$datos->empresa->estado,2);
		$this->creaAtributo("pais",$datos->empresa->pais,2);
		$this->creaAtributo("codigoPostal",$datos->empresa->cp,2);
		
		$this->emisor->appendChild($this->domiFiscal);
		
		//Regimen
		
		$this->creaAtributo("Regimen",$datos->documento->regimenFiscal,3);
		$this->emisor->appendChild($this->regimen);
		
		//REceptor
		$this->creaAtributo("rfc",$datos->cliente->clienteRFC,4);
		$this->creaAtributo("nombre",$datos->cliente->clienteNombre,4);
		
		
		if(isset($datos->cliente->clienteFiscales)){
		
		$this->creaAtributo("calle",$datos->cliente->clienteCalle,10);
		$this->creaAtributo("noExterior",$datos->cliente->clienteExterior,10);
		$this->creaAtributo("noInterior",$datos->cliente->clienteInterior,10);
		$this->creaAtributo("colonia",$datos->cliente->clienteColonia,10);
		$this->creaAtributo("localidad",$datos->cliente->clienteLocalidad,10);
		$this->creaAtributo("municipio",$datos->cliente->clienteMunicipio,10);
		$this->creaAtributo("estado",$datos->cliente->clienteEstado,10);
		$this->creaAtributo("pais",$datos->cliente->clientePais,10);
		$this->creaAtributo("codigoPostal",$datos->cliente->clienteCP,10);
		
		$this->receptor->appendChild($this->domicilio_rec);
		}
		
		foreach($prods as $value){
			$this->concepto = null;
			$this->concepto = $this->doc->createElement("cfdi:Concepto");
			
			$this->creaAtributo("cantidad",$value->cantidad,5);
			$this->creaAtributo("unidad",$value->unidad,5);
			$this->creaAtributo("noIdentificacion",$value->clave,5);
			$this->creaAtributo("descripcion",$value->descripcion,5);
			$this->creaAtributo("valorUnitario",number_format((double)$value->precio,2,'.',''),5);
			$this->creaAtributo("importe",number_format($value->subtotal,2,'.',''),5);
			
			$this->conceptos->appendChild($this->concepto);
			
		}
		
		
		if(isset($datos->documento->retenciones)){
			$this->creaAtributo("totalImpuestosRetenidos",number_format($datos->documento->xriva + $datos->documento->xrisr,2,".",""),7);
			$this->retencion = null;
			$this->retencion = $this->doc->createElement("cfdi:Retencion"); 
			$this->creaAtributo("impuesto","IVA",12);
			//$this->creaAtributo("tasa",$datos->documento->rIva,12);
			$this->creaAtributo("importe",number_format($datos->documento->xriva,2,".",""),12);
			$this->retenciones->appendChild($this->retencion);
			
			$this->retencion = null;
			$this->retencion = $this->doc->createElement("cfdi:Retencion"); 
			$this->creaAtributo("impuesto","ISR",12);
			//$this->creaAtributo("tasa",$datos->documento->rISR,12);
			$this->creaAtributo("importe",number_format($datos->documento->xrisr,2,".",""),12);
			$this->retenciones->appendChild($this->retencion);
			
			$this->impuestos->appendChild($this->retenciones);
		}
		
		$this->creaAtributo("totalImpuestosTrasladados",(number_format($datos->documento->xiva,2,".","")),7);
		$this->creaAtributo("impuesto","IVA",9);
		$this->creaAtributo("tasa",$datos->documento->pIva,9);
		$this->creaAtributo("importe",number_format($datos->documento->xiva,2,".",""),9);
		
		$this->traslados->appendChild($this->traslado);
		$this->impuestos->appendChild($this->traslados);
		
		$this->Comprobante->appendChild($this->emisor);
		$this->Comprobante->appendChild($this->receptor);
		$this->Comprobante->appendChild($this->conceptos);
		$this->Comprobante->appendChild($this->impuestos);
		$this->doc->appendChild($this->Comprobante);
		
		return $this->doc->saveXML();
	}

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CFDi Generado</title>
<?php
include("includes.html");
?>
</head>
<body>
<button onClick="window.open('<?php echo $file_xml ?>','_blank');" class="k-button width-200"><a class="k-icon k-i-arrow-s"></a>Descarga XML</button>
<button onClick="window.open('<?php echo $file_PDF ?>','_blank');" class="k-button width-200"><a class="k-icon k-i-arrow-s"></a>Descarga PDF</button>
<p>
<img src="<?php echo $file_QRCode; ?>" alt="qr code" style="width:2.75cm; height:2.75cm;"/>

</p>
<p>
</p>
</body>
</html>
