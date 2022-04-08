<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');

if (empty($_GET["id_cli"])) {
    exit("No hay el id");
}
$id_cli = $_GET["id_cli"];

$query = "DELETE FROM clientes WHERE id_cli = $id_cli";

$delete = mysqli_query($con, $query);

class Result {}

$response = new Result();
$response->resultado = 'OK';

echo json_encode($response);