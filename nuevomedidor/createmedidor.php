<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');

$id_ins = $_GET["id_ins"];
$codigo_med = $_GET["codigo_med"];
$estado_med = "ACTIVO";

$query = "INSERT INTO medidor (id_ins, codigo_med, estado_med, latitud_med, longitud_med) VALUES ('$id_ins', '$codigo_med', '$estado_med' , '000000000000000', '000000000000000')";

$insert = mysqli_query($con, $query);

class Result {}

$response = new Result();
$response->resultado = 'OK';

header('Content-Type: application/json');
echo json_encode($response);