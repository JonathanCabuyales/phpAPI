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
$jsonDepreciacion = json_decode($json);

if (!$jsonDepreciacion) {
    exit("No hay datos para registrar");
}

    $depreciacion_activo = $jsonDepreciacion->depreciacion;
    $descripcion = $jsonDepreciacion->descripcion;
    $fecha_compra = $jsonDepreciacion->fecha_compra;
    $id_cuentas = $jsonDepreciacion->id_cuentas;
    $id_usuario = $jsonDepreciacion->id_usuario;
    $jwt = $jsonDepreciacion->token;
    $valor_inicial = $jsonDepreciacion->valor_inicial;
    $id_dep = $jsonDepreciacion->id_dep;

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");
    
    
    $query = "UPDATE depreciacion SET id_usuario = '$id_usuario', descripcion = '$descripcion', fecha_compra = '$fecha_compra', valor_inicial = '$valor_inicial', depreciacion_activo = '$depreciacion_activo', id_cuentas = '$id_cuentas' 
        WHERE id_dep = $id_dep";
        
    $update = mysqli_query($con, $query);

    $data_insert=array(
        "data" => $update,
        "status" => "success update",
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