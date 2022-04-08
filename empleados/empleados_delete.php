<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');

if (empty($_GET["id_usuario"])) {
    exit("No hay id del medidor");
}
$id_usuario = $_GET["id_usuario"];


mysqli_query($con,"DELETE FROM usuarios WHERE id_usuario = $id_usuario");

class Result {}

$response = new Result();
$response->resultado = 'OK';

echo json_encode($response);