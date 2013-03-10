<?php
include_once 'ecodex/lib/nusoap.php';
include_once 'ecodex/class/Seguridad.class.php';
include_once 'ecodex/class/Comprobantes.class.php';
include_once 'ecodex/class/Timbrado.class.php';
//private $integrador = "2b3a8764-d586-4543-9b7e-82834443f219";
$integrador = "6da8d0d2-cce2-43a5-ae9a-9e739ca5a46f"; 
$rfc = "RUMN780525F1A";
$xml = '<?xml version="1.0" encoding="utf-8"?><cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" fecha="2013-01-08T10:24:09" version="3.2" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd" serie="8" folio="3" formaDePago="PAGO EN UNA SOLA EXHIBICION" metodoDePago="Efectivo" noCertificado="00001000000201812255" subTotal="2000.00" TipoCambio="1" Moneda="PESO" total="2320.00" tipoDeComprobante="ingreso" NumCtaPago="123456"  LugarExpedicion="manzanillo"><cfdi:Emisor rfc="RUMN780525F1A" nombre="INTEGRADORA ADUANAL Y DE SERVICIOS WOODWARD S.C."><cfdi:DomicilioFiscal calle="AV. TENIENTE AZUETA" noExterior="25-1" colonia="BUROCRATA" localidad="MANZANILLO" municipio="MANZANILLO" estado="COLIMA" pais="MEXICO" codigoPostal="28250"/><cfdi:RegimenFiscal Regimen="regimen 1" /><cfdi:RegimenFiscal Regimen="regimen 2" /></cfdi:Emisor><cfdi:Receptor rfc="XEXX010101000" nombre="TRADING SERVICES CORPORATION."><cfdi:Domicilio calle="1705 E HILLSIDE RD STE." noExterior="1" localidad="LAREDO" referencia="LAREDO" municipio="LAREDO" estado="TEXAS" pais="USA" codigoPostal="87654"/></cfdi:Receptor><cfdi:Conceptos><cfdi:Concepto cantidad="1" unidad="SERVICIO" descripcion="inicio SERVICIOS COMPLEMENTARIOS" valorUnitario="2000.00" importe="2000.00"/></cfdi:Conceptos><cfdi:Impuestos totalImpuestosTrasladados="320.00"><cfdi:Traslados><cfdi:Traslado impuesto="IVA" tasa="16" importe="320.00"/></cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>';

$token = new Seguridad();
$trsID = rand(1, 10000);
$generaToken = $token->setToken($rfc, $trsID, $integrador);
$getToken = $token->getToken();

$SellaTimbra = new Comprobantes();
$trsID = rand(1, 10000);
$sellar = $SellaTimbra->setXMLSellado( $xml, $rfc, $trsID, $getToken);                              
$getXmlSellado = $SellaTimbra->getXMLSellado();



?>


<textarea>
<?php
print_r($getXmlSellado);
?>
</textarea>