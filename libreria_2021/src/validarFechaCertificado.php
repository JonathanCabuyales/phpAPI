<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../jwt/src/BeforeValidException.php';
require_once '../../jwt/src/ExpiredException.php';
require_once '../../jwt/src/SignatureInvalidException.php';
require_once '../../jwt/src/JWT.php';
use \Firebase\JWT\JWT;


define ('SECRET_KEY', '4956andres.'); // la clave secreta puede ser una cadena aleatoria y mantenerse en secreto para cualquier persona
define ('ALGORITMO', 'HS256'); // Algoritmo utilizado para firmar el token

$json = file_get_contents('php://input');



$jwt = $_POST['token'];
try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));
$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];

$fechaInicio = substr($fechaInicio,4, 11);
$fechaInicio = date('Y-m-d',strtotime($fechaInicio));


$fechaFin = substr($fechaFin,4, 11);
$fechaFin= date('Y-m-d',strtotime($fechaFin));


$fechaActual=date('Y-m-d');

if($fechaActual <= $fechaFin){
    $res =  "Certificado Vigente";
}else{
    $file = fopen("../error_log", "a+");
    $date = date('m/d/Y h:i:s a', time());
    fwrite($file, "Error: " .$date. ' Fecha vencimiento del certificado excedida'. PHP_EOL);
    $res =  "Valide las fechas de vencimiento del certificado";
}


$data_insert=array(
    "data" => array(
        'text' => $res
    ),
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