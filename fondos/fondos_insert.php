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

// $jwt = $_GET['token'];

$jsonFondo = json_decode($json);

if (!$jsonFondo) {
    exit("No hay datos para registrar");
}

$jwt = $jsonFondo->token;

$id_usuario = $jsonFondo->id_usuario;
$monto_fon = $jsonFondo->monto_fon;
$descripcion_fon = $jsonFondo->descripcion_fon;


try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array();
    
    $query = "INSERT INTO fondos (id_usuario, monto_fon, descripcion_fon) 
            VALUES ('$id_usuario', '$monto_fon', '$descripcion_fon')";

    $insert = mysqli_query($con, $query);

    if($insert == true){
        $id_fon = mysqli_insert_id($con); 
        
    }else{
        $id_fon = 0;
    }

    $data_insert=array(
        "data" => $id_fon,
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