<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include ("../conexion/bd.php");

$json = file_get_contents('php://input');

if (empty($_GET["id_dep"])) {
    exit("No hay el id");
}
$id_dep = $_GET["id_dep"];

$query = "DELETE FROM depreciacion WHERE id_dep = $id_dep";

$delete = mysqli_query($con, $query);

class Result {}

$response = new Result();
$response->resultado = 'OK';

echo json_encode($response);