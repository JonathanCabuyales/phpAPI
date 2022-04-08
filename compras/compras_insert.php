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

$jsonCompra = json_decode($json);

if (!$jsonCompra) {
    exit("No hay datos para registrar");
}

$id_prove = $jsonCompra->id_prove;
$tipocomprobante_com = $jsonCompra->tipocomprobante_com;
$emsion_com = $jsonCompra->emsion_com;
$registro_com = $jsonCompra->registro_com;
$serie_com = $jsonCompra->serie_com;
$autorizacionSRI_com = $jsonCompra->autorizacionSRI_com;
$vencimiento_com = $jsonCompra->vencimiento_com;
$comceptos_com = $jsonCompra->comceptos_com;
$formapago_com = $jsonCompra->formapago_com;
$iva_com = $jsonCompra->iva_com;
$ICE_com = $jsonCompra->ICE_com;
$devolucionIVA = $jsonCompra->devolucionIVA;
$costogasto_com = $jsonCompra->costogasto_com;
$jwt = $jsonCompra->token;


try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $query = "INSERT INTO compras (
        id_prove, 
        tipocomprobante_com, 
        emsion_com, 
        registro_com, 
        serie_com, 
        autorizacionSRI_com, 
        vencimiento_com, 
        comceptos_com, 
        formapago_com, 
        iva_com, 
        ICE_com, 
        devolucionIVA, 
        costogasto_com) 
    VALUES ('$id_prove', 
        '$tipocomprobante_com',  
        '$emsion_com', 
        '$registro_com', 
        '$serie_com', 
        '$autorizacionSRI_com', 
        '$vencimiento_com', 
        '$comceptos_com',
        '$formapago_com', 
        '$iva_com', 
        '$ICE_com', 
        '$devolucionIVA', 
        '$costogasto_com')";

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