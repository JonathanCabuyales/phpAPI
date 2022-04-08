<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../jwt/src/BeforeValidException.php';
require_once '../jwt/src/ExpiredException.php';
require_once '../jwt/src/SignatureInvalidException.php';
require_once '../jwt/src/JWT.php';
use \Firebase\JWT\JWT;

define ('SECRET_KEY', '4956andres.'); // la clave secreta puede ser una cadena aleatoria y mantenerse en secreto para cualquier persona
define ('ALGORITMO', 'HS256'); // Algoritmo utilizado para firmar el token

$json = file_get_contents('php://input');
$jsonCliente = json_decode($json);

if (!$jsonCliente) {
    exit("No hay datos para registrar");
}

$id = $jsonCliente->id_cli;
$nombres = $jsonCliente->nombres_cli;
$apellidos = $jsonCliente->apellidos_cli;
$ciruc = $jsonCliente->ciruc_cli;
$direccion = $jsonCliente->direccion_cli;
$email = $jsonCliente->email_cli;
$telefono = $jsonCliente->telefono_cli;

$jwt = $jsonCliente->token;

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");
    
    
    $query = "UPDATE clientes set nombres_cli = '$nombres', apellidos_cli = '$apellidos', ciruc_cli = '$ciruc', direccion_cli = '$direccion', email_cli = '$email', telefono_cli = '$telefono' 
        WHERE id_cli = $id";
        
    $update = mysqli_query($con, $query);

    $data_insert=array(
        "data" => $update,
        "status" => "success",
        "message" => "Request authorized"
    );  

}catch (Exception $e){

    http_response_code(401);

    $data_insert=array(
        //"data" => $data_from_server,
        "jwt" => $jwt,
        "status" => "error",
        "message" => $e->getMessage()
    );
    
}

echo json_encode($data_insert);