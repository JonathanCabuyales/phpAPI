<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
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
 
$jsonProveedor = json_decode($json);

if (!$jsonProveedor) {
    exit("No hay datos para registrar");
}

    $razonsocial_prove = $jsonProveedor->razonsocial_prove;
    $ciruc_prove = $jsonProveedor->ciruc_prove;
    $direccion_prove = $jsonProveedor->direccion_prove;
    $email_prove = $jsonProveedor->email_prove;
    $telefono_prove = $jsonProveedor->telefono_prove;
    $descripcion_prove = $jsonProveedor->descripcion_prove;


    $jwt = $jsonProveedor->token;

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $query = "INSERT INTO proveedores 
    (razonsocial_prove, 
    ciruc_prove, 
    direccion_prove, 
    email_prove, 
    telefono_prove, 
    descripcion_prove)
    VALUES('$razonsocial_prove', 
    '$ciruc_prove', 
    '$direccion_prove', 
    '$email_prove', 
    '$telefono_prove', 
    '$descripcion_prove')";

    $insert = mysqli_query($con, $query);


    $data_insert=array(
        "data" => $insert,
        "status" => "success",
        "message" => "Request authorized"
    );  

}catch (Throwable $e){

    http_response_code(401);

    $data_insert=array(
        //"data" => $data_from_server,
        "jwt" => $jwt,
        "status" => "error",
        "message" => $e->getMessage()
    );
    
}

echo json_encode($data_insert);