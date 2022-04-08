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

$numerocuenta_ins = $jsonNovedad->numerocuenta_ins;
$ciruc_cli = $jsonNovedad->ciruc_cli;
$nombres = $jsonNovedad->nombres;
$direccion_cat = $jsonNovedad->direccion_cat;
$clavecatastral = $jsonNovedad->clavecatastral;
$medidor = $jsonNovedad->medidor;
$tarifa = $jsonNovedad->tarifa;
$mesesdeuda = $jsonNovedad->mesesdeuda;
$fotoPredio = $jsonNovedad->fotoPredio;
$fotoNotificacion = $jsonNovedad->fotoNotificacion;
$grupoCorte = $jsonNovedad->grupoCorte;


$query = "INSERT INTO notificaciones 
(numerocuenta_ins, ciruc_cli, nombres, direccion_cat, clavecatastral,
medidor, tarifa, mesesdeuda, fotoPredio, fotoNotificacion, grupoCorte) 
VALUES ('$numerocuenta_ins', '$ciruc_cli', '$nombres', '$direccion_cat', '$clavecatastral', 
'$medidor', '$tarifa', '$mesesdeuda', '$fotoPredio', '$fotoNotificacion', '$grupoCorte')";

$insert = mysqli_query($con, $query);

class Result {}

$response = new Result();
$response->resultado = 'OK';

header('Content-Type: application/json');
echo json_encode($response);