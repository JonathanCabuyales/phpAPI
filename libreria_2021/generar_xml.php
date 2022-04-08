<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include('modulo11.php');
// include('libreria/src/services/class/generarPDF.php');
include ('ejecutar.php');

$empresa = 'JUNTA ADMINISTRADORA DE AGUA POTABLE Y SANEAMIENTO DE SAN JUAN DE AMAGUAÑA';
$rucempresa = '1792919320001';
$dirempresa = 'RICARDO ALVAREZ S3-51 Y NELA MARTINEZ (SAN JUAN)';
$fechaemision = date("d/m/Y");
$cliente = 'CONSUMIDOR FINAL';
$ciruccliente = '9999999999';
$dircliente = 'QUITO';

// añado ceros al numero de la factura secuencial se queda en un tamaño de 9 incluidos 0
$number = 747;
$length = 9;
$secuencial = substr(str_repeat(0, $length).$number, - $length);

$fechaactual = date("dmY");
// 01 factura, 04 nota de credito, 05 nota de debito, 06 guia remision, comprobante de retencion, 07
$tipocomprobante = '01';
// 1 pruebas, 2 produccion
$tipoambiente = '1';
// sacar de la base de datos
$serie = '001001';
// sacar de la base de datos
// codigo cualquiera de 8 numeros
$codigonumerico = '12345678';
$tipoemision = '1';

// orden de la clave de acceso
// fechaactual ("dmY"), tipo comprobante, ruc, tipoambiente, serie, secuencial, codigonumerico, tipoemision
// y finalmente digito verificador con modulo 11

$mod = new Modulo();
$codigo = $fechaactual.$tipocomprobante.$rucempresa.$tipoambiente.$serie.$secuencial.$codigonumerico.$tipoemision;
$claveacceso = $codigo.$mod->getmodulo11($codigo);

// primero se extrae la informacion de la prefactura para cargar los datos en el xml

$xmlfactura = new XMLWriter();
$xmlfactura->openURI($claveacceso.".xml"); //creacion del archivo xml con ese nombre

$xmlfactura->setIndent(true); //valor booleano para establecer niveles de nodos xml y que queden identados
$xmlfactura->setIndentString("\t"); //corresponde a una tabulacion

$xmlfactura->startDocument('1.0', 'UTF-8'); //inicio del documento
$xmlfactura->startElement("factura");
    $xmlfactura->writeAttribute('id', 'comprobante');//atributos de la etiqueta factura
    $xmlfactura->writeAttribute('version', '1.0.0');

    // seccion de informacion tributaria
    $xmlfactura->startElement("infoTributaria"); //inicio de la etiqueta raiz
        $xmlfactura->writeElement("ambiente", "1");
        $xmlfactura->writeElement("tipoEmision", "1");
        $xmlfactura->writeElement("razonSocial", $empresa);
        $xmlfactura->writeElement("nombreComercial", $empresa);
        $xmlfactura->writeElement("ruc", $rucempresa);
        $xmlfactura->writeElement("claveAcceso", $claveacceso);
        $xmlfactura->writeElement("codDoc", '01');
        $xmlfactura->writeElement("estab", '001');
        $xmlfactura->writeElement("ptoEmi", '001');
        $xmlfactura->writeElement("secuencial", '000000746');
        $xmlfactura->writeElement("dirMatriz", $dirempresa);
    $xmlfactura->endElement(); // fin de la etiqueta raiz


    // inicio de la seccion infofactura
    $xmlfactura->startElement("infoFactura");
        $xmlfactura->writeElement("fechaEmision", $fechaemision);
        $xmlfactura->writeElement("dirEstablecimiento", $dirempresa);
        $xmlfactura->writeELement("obligadoContabilidad", "SI");
        $xmlfactura->writeElement("tipoIdentificacionComprador", '05');
        $xmlfactura->writeElement("razonSocialComprador", $cliente);
        $xmlfactura->writeElement("identificacionComprador", $ciruccliente);
        $xmlfactura->writeElement("direccionComprador", $dircliente);
        $xmlfactura->writeELement("totalSinImpuestos", '1.00');
        $xmlfactura->writeElement("totalDescuento", '0.00');

        // inicio de la seccion total con impuestos
        $xmlfactura->startElement("totalConImpuestos");
            $xmlfactura->startElement("totalImpuesto");
                $xmlfactura->writeElement("codigo", '2');
                $xmlfactura->writeElement("codigoPorcentaje", '0');
                $xmlfactura->writeELement("baseImponible", '1.00');
                $xmlfactura->writeElement("valor", '0');
            $xmlfactura->endELement();
        $xmlfactura->endELement();

        $xmlfactura->writeElement("propina", '0.00');
        // el importe total es el valor final de la factura debes colocar tambien
        $xmlfactura->writeElement("importeTotal", '1.00');
        $xmlfactura->writeElement("moneda", "DOLAR");

        // inicion de la seccion pagos
        $xmlfactura->startElement("pagos");
            $xmlfactura->startElement("pago");
                $xmlfactura->writeElement("formaPago", "01");
                $xmlfactura->writeElement("total", "1.00");
                $xmlfactura->writeElement("plazo", "30");
                $xmlfactura->writeElement("unidadTiempo", "dias");
            $xmlfactura->endElement();
        $xmlfactura->endElement();
    $xmlfactura->endElement();

    // inicio seccion detalles de la factura
    $xmlfactura->startElement("detalles");
        // Aqui realiza un bucle para colocar todos los detalles que tenfas 
        $xmlfactura->startElement("detalle");
            $xmlfactura->writeElement("codigoPrincipal", 'SERV001');
            $xmlfactura->writeElement("descripcion", 'SERV001');
            $xmlfactura->writeElement("cantidad", '1');
            $xmlfactura->writeElement("precioUnitario", '1.00');
            $xmlfactura->writeElement("descuento", '0.00');
            $xmlfactura->writeElement("precioTotalSinImpuesto", '1.00');

            // inicio de seccion impuestos
            $xmlfactura->startElement("impuestos");
                $xmlfactura->startElement("impuesto");
                    $xmlfactura->writeElement("codigo", "2");
                    $xmlfactura->writeElement("codigoPorcentaje", "0");
                    $xmlfactura->writeElement("tarifa", "0.00");
                    $xmlfactura->writeElement("baseImponible", "1.00");
                    $xmlfactura->writeElement("valor", "0.00");
                $xmlfactura->endElement();
            $xmlfactura->endElement();
        $xmlfactura->endElement();
    $xmlfactura->endElement();

    // inicio de seccion para la informacion adicional 
    // usa para poner fondo social 
    // consumo del agua etc etc
    $xmlfactura->startElement("infoAdicional");

        $xmlfactura->startElement("campoAdicional");
            $xmlfactura->writeAttribute("nombre", "email");
            $xmlfactura->text("andresalquinga@hotmail.com");
        $xmlfactura->endElement();

        $xmlfactura->startElement("campoAdicional");
            $xmlfactura->writeAttribute("nombre", "consumo");
            $xmlfactura->text("63");
        $xmlfactura->endElement();

        $xmlfactura->startElement("campoAdicional");
            $xmlfactura->writeAttribute("nombre", "lecturaActual");
            $xmlfactura->text("153");
        $xmlfactura->endElement();

        $xmlfactura->startElement("campoAdicional");
            $xmlfactura->writeAttribute("nombre", "lecturaAnterior");
            $xmlfactura->text("90");
        $xmlfactura->endElement();

    $xmlfactura->endElement();
    

$xmlfactura->endElement(); // fin de la etiqueta raiz
$xmlfactura->endDocument(); // fin del documento

// nombre del archivo primero y luego la ruta
rename($claveacceso.".xml", "xmlgenerados/$claveacceso".".xml");

// $pdf = new generarPDF();
// $pdf->facturaPDF("xmlgenerados/$claveacceso".".xml", $claveacceso);

// $ruta_factura= 'http://localhost/vt/APIVTPROYECTOS/libreria_2021/xmlgenerados/'.$claveacceso.".xml";
// $ruta_certificado = 'http://localhost/vt/APIVTPROYECTOS/libreria_2021/jose_alberto_loachamin_chalco.p12';
// $contraseña= 'Sanjuan2018';
// $ruta_respuesta= 'http://localhost/vt/APIVTPROYECTOS/libreria_2021/generar_xml.php';


// $ejecutar = new ejecutar();
// $domain_dir = $_SERVER['SERVER_NAME'];

// //Firmar Factura y enviar a SRI
// $ejecutar->firmarFactura($ruta_factura,$ruta_certificado,$contraseña,$ruta_respuesta);

?>