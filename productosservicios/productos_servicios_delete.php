<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');


include ("../conexion/bd.php");

$json = file_get_contents('php://input');

if (empty($_GET["id_proser"])) {
    exit("No hay id del medidor");
}
$id_proser = $_GET["id_proser"];


mysqli_query($con,"DELETE FROM productos_servicios WHERE id_proser = $id_proser");

class Result {}

$response = new Result();
$response->resultado = 'OK';

echo json_encode($response);