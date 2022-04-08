<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');
 
$jsonInv = json_decode($json);

if (!$jsonInv) {
    exit("No hay datos para registrar");
}

$codigo = $jsonInv->codigo;
$codigo_ant = $jsonInv->codigo_ant;
$descripcion = $jsonInv->descripcion;
$cantidadmano = $jsonInv->cantidadmano;
$udm = $jsonInv->udm;
$ubicacion_ant = $jsonInv->ubicacion_ant;
$ubicacion_act = $jsonInv->ubicacion_act;
$latitude = $jsonInv->latitude;
$longitude = $jsonInv->longitude;
$foto = $jsonInv->foto;
$conteo = $jsonInv->conteo;
$fabricante = $jsonInv->fabricante;
$noparte = $jsonInv->noparte;


$query = "INSERT INTO inv_esmeraldas (codigo, codigo_ant, descripcion, cantidadmano, udm, ubicacion_ant, ubicacion_act, latitude, longitude, foto, conteo, fabricante, noparte) 
VALUES ('$codigo', '$codigo_ant', '$descripcion', '$cantidadmano', '$udm', '$ubicacion_ant', '$ubicacion_act', '$latitude', '$longitude', '$foto', '$conteo', '$fabricante', '$noparte')";

$insert = mysqli_query($con, $query);

class Result {}

$response = new Result();
$response->resultado = 'OK';

header('Content-Type: application/json');
echo json_encode($response);