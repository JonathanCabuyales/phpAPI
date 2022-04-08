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

$jsonProdServ = json_decode($json);

if (!$jsonProdServ) {
    exit("No hay datos para actualizar");
}


$jwt = $jsonProdServ->token;

$id_proser = $jsonProdServ->id_proser;
$codigo_proser = $jsonProdServ->codigo_proser;
$categoria_proser = $jsonProdServ->categoria_proser;
$nombre_proser = $jsonProdServ->nombre_proser;
$descripcion_proser = $jsonProdServ->descripcion_proser;
$precio_proser = $jsonProdServ->precio_proser;
$cantidad_proser = $jsonProdServ->cantidad_proser;
$cantidadfinal_proser = $jsonProdServ->cantidadfinal_proser;
$preciosugerido_proser = $jsonProdServ->preciosugerido_proser;
$lote_proser = $jsonProdServ->lote_proser;
$IVA_proser = $jsonProdServ->IVA_proser;

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $query = "UPDATE productos_servicios SET 
    codigo_proser = '$codigo_proser', 
    categoria_proser = '$categoria_proser', 
    nombre_proser = '$nombre_proser', 
    descripcion_proser = '$descripcion_proser', 
    precio_proser = '$precio_proser',
    preciosugerido_proser = '$preciosugerido_proser',
    cantidad_proser = '$cantidad_proser',
    cantidadfinal_proser = '$cantidadfinal_proser',
    lote_proser = '$lote_proser',
    IVA_proser = '$IVA_proser'
    WHERE id_proser = $id_proser ";

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