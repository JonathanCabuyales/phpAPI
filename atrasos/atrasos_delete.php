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

$id_atr = $jsonAtraso->id_delete;
$id_usuario = $jsonAtraso->id_usuario;
$detalle_reel = $jsonAtraso->detalle_reel;


try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array();
    
    $query = "DELETE FROM atrasos WHERE id_atr = '$id_atr'";

    $delete = mysqli_query($con, $query);

    if($delete){
        $queryInsert = "INSERT INTO registros_eliminados (
            id_usuario,
            detalle_reel
        )
        VALUES (
            '$id_usuario',
            '$detalle_reel'
        )";

        $insert = mysqli_query($con, $queryInsert);
        
        $data_insert=array(
            "data" => $insert,
            "status" => "success",
            "message" => "Request authorized"
        ); 
    } else {

        $data_insert=array(
            "data" => false,
            "status" => "success",
            "message" => "Request authorized"
        ); 

    }

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