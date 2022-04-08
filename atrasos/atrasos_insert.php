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

$jsonAtraso = json_decode($json);

if (!$jsonAtraso) {
    exit("No hay datos para registrar");
}

$jwt = $jsonAtraso->token;

$id_usuario = $jsonAtraso->id_usu;
$fecha_atr = $jsonAtraso->fecha_atr;
$tiempo_atr = $jsonAtraso->tiempo_atr;
$descripcion_atr = $jsonAtraso->descripcion_atr;
$justificado_atr = $jsonAtraso->justificado_atr;
$justificacion_atr = $jsonAtraso->justificacion_atr;
$fechajusti_atr = $jsonAtraso->fechajusti_atr;


try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array();
    
    $query = "INSERT INTO atrasos (
    id_usuario, 
    fecha_atr,
    tiempo_atr,
    descripcion_atr,
    justificado_atr,
    justificacion_atr,
    fechajusti_atr) 
    VALUES ('$id_usuario', '$fecha_atr', '$tiempo_atr', '$descripcion_atr',
    '$justificado_atr', '$justificacion_atr', '$fechajusti_atr')";

    $insert = mysqli_query($con, $query);

    $data_insert=array(
        "data" => $insert,
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