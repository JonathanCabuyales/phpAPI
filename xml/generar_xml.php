<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$empresa = 'JUNTA ADMINISTRADORA DE AGUA POTABLE Y SANEAMIENTO DE SAN JUAN DE AMAGUAÑA';
$rucempresa = '1792919320001';
$dirempresa = 'RICARDO ALVAREZ S3-51 Y NELA MARTINEZ (SAN JUAN)';

    $xml = new DOMDocument('1.0', 'utf-8');
    $xml->formatOutput = true;
    
    $xml_fac = $xml->createElement('factura');
    $cabecera = $xml->createAttribute('id');
    $cabecera->value='comprobante';
    
    $cabecerav = $xml->createAttribute('version');
    $cabecerav->value = '1.0.0';
    $xml_inf = $xml->createElement('infoTributaria');
    $xml_ambiente = $xml->createElement('ambiente', '1');
    $xml_tipoemision = $xml->createElement('tipoEmision', '1');
    $xml_razonsocial = $xml->createElement('razonSocial', $empresa);
    $xml_nombrecomer = $xml->createElement('nombreComercial', $empresa);
    $xml_ruc = $xml->createElement('ruc', $rucempresa);
    
    $xml_claveacceso = $xml->createElement('claveAcceso', '21313456213134562131345621313456213134562131345621313456213134561');
    $xml_doc = $xml->createElement('codDoc', '01');
    $xml_estable = $xml->createElement('estab', '001');
    $xml_emision = $xml->createElement('ptoEmi', '001');
    $xml_secuencial = $xml->createElement('secuencial', '000001234');
    $xml_dirmatriz = $xml->createElement('dirMatriz', 'Direccion de la empresa');
    
    $xml_def = $xml->createElement('infoFactura');
    $xml_fechaemision = $xml->createElement('fechaEmision', '21/09/2021');
    $xml_direstable = $xml->createElement('dirEstablecimiento', 'Direccion establecimiento');
    $xml_obligadocontabilidad = $xml->createElement('obligadoContabilidad', 'SI');
    $xml_tipoidcomprador = $xml->createElement('tipoIdentificacionComprador', '05');
    $xml_razonsocialcomprador = $xml->createElement('razonSocialComprador', 'Nombre del comprador');
    $xml_identicomprador = $xml->createElement('identificacionComprador', '1234567891');
    $xml_totalsinimpuestos = $xml->createElement('totalSinImpuestos', '1.00');
    $xml_totaldesc = $xml->createElement('totalDescuento', '0.00');
    
    $xml_conimpuesto = $xml->createElement('totalConImpuestos');
    $xml_totalimpuesto = $xml->createElement('totalImpuesto');
    $xml_tcodigo = $xml->createElement('codigo', '2');
    $xml_codigoporcen = $xml->createElement('codigoPorcentaje', '0');
    $xml_baseimpoimp = $xml->createElement('baseImponible', '1.00');
    $xml_tarifaconimpuesto = $xml->createElement('tarifa', '12.00');
    $xml_valor = $xml->createElement('valor', '0');
    
    $xml_propina = $xml->createElement('propina', '0.00');
    $xml_importetotal = $xml->createElement('importeTotal', '0.00');
    $xml_moneda = $xml->createElement('moneda', 'DOLAR');
    
    $xml_pagos = $xml->createElement('pagos');
    $xml_pago = $xml->createElement('pago');
    $xml_formapago = $xml->createElement('formaPago', '01');
    $xml_total = $xml->createElement('total', '1.00');
    $xml_plazo = $xml->createElement('plazo', '30');
    // $xml_unidad = $xml->createElement('unidadTiempo', 'Dias');
    
    // aqui debes crear un bucle para añadir mas de un detalle en la factura
    $xml_detalles = $xml->createElement('detalles');
    $xml_detalle = $xml->createElement('detalle');
    $xml_codigoprod = $xml->createElement('codigoPrincipal', 'PRO001');
    $xml_descripcion = $xml->createElement('descripcion', 'descripcion del producto');
    $xml_cantidad = $xml->createElement('cantidad', '1');
    $xml_preciounitario = $xml->createElement('precioUnitario', '1.00');
    $xml_descuento = $xml->createElement('descuento', '0.00');
    $xml_preciototalsinimp = $xml->createElement('precioTotalSinImpuesto', '1.00');
    
    $xml_impuestos = $xml->createElement('impuestos');
    $xml_impuesto = $xml->createElement('impuesto');
    $xml_codigo = $xml->createElement('codigo', '2');
    $xml_codigopor = $xml->createElement('codigoPorcentaje', '2');
    $xml_tarifa = $xml->createElement('tarifa', '0.00');
    $xml_baseimpo = $xml->createElement('baseImponible', '1.00');
    $xml_valorimp = $xml->createElement('valor', '0.00');
    
    $xml_infoadicional = $xml->createElement('infoAdicional');
    $xml_codigoadicional = $xml->createElement('campoAdicional', 'andresalquinga@hotmail.com');
    $atributo = $xml->createAttribute('nombre');
    $atributo->value = 'email';
    
    $xml_inf->appendChild($xml_ambiente);
    $xml_inf->appendChild($xml_tipoemision);
    $xml_inf->appendChild($xml_razonsocial);
    $xml_inf->appendChild($xml_nombrecomer);
    $xml_inf->appendChild($xml_ruc);
    $xml_inf->appendChild($xml_claveacceso);
    $xml_inf->appendChild($xml_doc);
    $xml_inf->appendChild($xml_estable);
    $xml_inf->appendChild($xml_emision);
    $xml_inf->appendChild($xml_secuencial);
    $xml_inf->appendChild($xml_dirmatriz);
    $xml_fac->appendChild($xml_inf);
    
    
    $xml_def->appendChild($xml_fechaemision);
    $xml_def->appendChild($xml_direstable);
    $xml_def->appendChild($xml_obligadocontabilidad);
    $xml_def->appendChild($xml_tipoidcomprador);
    $xml_def->appendChild($xml_razonsocialcomprador);
    $xml_def->appendChild($xml_identicomprador);
    $xml_def->appendChild($xml_totalsinimpuestos);
    $xml_def->appendChild($xml_totaldesc);
    
    $xml_def->appendChild($xml_conimpuesto);
    $xml_conimpuesto->appendChild($xml_totalimpuesto);
    $xml_totalimpuesto->appendChild($xml_tcodigo);
    $xml_totalimpuesto->appendChild($xml_codigoporcen);
    $xml_totalimpuesto->appendChild($xml_baseimpoimp);
    $xml_totalimpuesto->appendchild($xml_tarifaconimpuesto);
    $xml_totalimpuesto->appendChild($xml_valor);
    $xml_fac->appendChild($xml_def);
    
    $xml_def->appendChild($xml_propina);
    $xml_def->appendChild($xml_importetotal);
    $xml_def->appendChild($xml_moneda);
    
    
    $xml_def->appendChild($xml_pagos);
    $xml_pagos->appendChild($xml_pago);
    $xml_pago->appendChild($xml_formapago);
    $xml_pago->appendChild($xml_total);
    $xml_pago->appendChild($xml_plazo);
    $xml_pago->appendChild($xml_unidad);
    
    
    $xml_fac->appendChild($xml_detalles);
    $xml_detalles->appendChild($xml_detalle);
    $xml_detalle->appendChild($xml_codigoprod);
    $xml_detalle->appendChild($xml_descripcion);
    $xml_detalle->appendChild($xml_cantidad);
    $xml_detalle->appendChild($xml_preciounitario);
    $xml_detalle->appendChild($xml_descuento);
    $xml_detalle->appendChild($xml_preciototalsinimp);
    
    $xml_detalle->appendChild($xml_impuestos);
    $xml_impuestos->appendChild($xml_impuesto);
    $xml_impuesto->appendChild($xml_codigo);
    $xml_impuesto->appendChild($xml_codigopor);
    $xml_impuesto->appendChild($xml_tarifa);
    $xml_impuesto->appendChild($xml_baseimpo);
    $xml_impuesto->appendChild($xml_valorimp);
    
    $xml_fac->appendChild($xml_infoadicional);
    $xml_fac->appendChild($xml_codigoadicional);
    $xml_codigoadicional->appendChild($atributo);
    
    $xml_fac->appendChild($cabecera);
    $xml_fac->appendChild($cabecerav);
    $xml->appendChild($xml_fac);
    
    echo 'CREADO: ' .$xml->save('ejemplo_xml.xml') .'bytes';
?>