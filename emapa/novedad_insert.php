<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');
 
$jsonNovedad = json_decode($json);

if (!$jsonNovedad) {
    exit("No hay datos para registrar");
}

$numeroCuenta = $jsonNovedad->numeroCuenta;
$clavecatastral= $jsonNovedad->clavecatastral;
$tarifa= $jsonNovedad->tarifa;
$cliente= $jsonNovedad->cliente;
$direccion= $jsonNovedad->direccion;
$interseccion= $jsonNovedad->interseccion;
$medidor= $jsonNovedad->medidor;
$mesesAtraso= $jsonNovedad->mesesAtraso;
$tipoCorte= $jsonNovedad->tipoCorte;
$cuadrilla= $jsonNovedad->cuadrilla;
$lecturaCorte= $jsonNovedad->lecturaCorte;
$novedadCorte= $jsonNovedad->novedadCorte;
$observacion= $jsonNovedad->observacion;
$fotoAntes= $jsonNovedad->fotoAntes;
$fotoDurante= $jsonNovedad->fotoDurante;
$fotoDespues= $jsonNovedad->fotoDespues;
$fotoPredio= $jsonNovedad->fotoPredio;
$totalafacturar = $jsonNovedad->totalafacturar;
$grupoCorte= $jsonNovedad->grupoCorte;

$query = "INSERT INTO novedad (numeroCuenta, clavecatastral, tarifa, 
cliente, direccion, interseccion, 
medidor, mesesAtraso, tipoCorte, cuadrilla,
lecturaCorte, novedadCorte, observacion,
fotoAntes, fotoDurante, fotoDespues, fotoPredio, totalafacturar, grupoCorte) 
VALUES ('$numeroCuenta', '$clavecatastral', '$tarifa', 
'$cliente', '$direccion', '$interseccion', 
'$medidor', '$mesesAtraso', '$tipoCorte', '$cuadrilla', 
'$lecturaCorte', '$novedadCorte', '$observacion', 
'$fotoAntes', '$fotoDurante', '$fotoDespues', '$fotoPredio', '$totalafacturar', '$grupoCorte')";

$insert = mysqli_query($con, $query);

class Result {}

$response = new Result();
$response->resultado = 'OK';

header('Content-Type: application/json');
echo json_encode($response);