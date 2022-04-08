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
 
$jsonProdServ = json_decode($json);

if (!$jsonProdServ) {
    exit("No hay datos para registrar");
}

    $id_prove = $jsonProdServ->id_prove;
    $codigo_proser = $jsonProdServ->codigo_proser;
    $categoria_proser = $jsonProdServ->categoria_proser;
    $nombre_proser = $jsonProdServ->nombre_proser;
    $descripcion_proser = $jsonProdServ->descripcion_proser;
    $precio_proser = $jsonProdServ->precio_proser;
    $cantidadfinal_proser = $jsonProdServ->cantidadfinal_proser;
    $cantidad_proser = $jsonProdServ->cantidad_proser;
    $preciosugerido_proser = $jsonProdServ->preciosugerido_proser;
    $lote_proser = $jsonProdServ->lote_proser;
    $IVA_proser = $jsonProdServ->IVA_proser;
    $jwt = $jsonProdServ->token;

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $query = "INSERT INTO productos_servicios 
    (id_prove,
    codigo_proser, 
    categoria_proser, 
    nombre_proser, 
    descripcion_proser, 
    precio_proser, 
    preciosugerido_proser,
    cantidad_proser, 
    cantidadfinal_proser,
    lote_proser,
    IVA_proser)
    VALUES('$id_prove',
    '$codigo_proser',
    '$categoria_proser', 
    '$nombre_proser', 
    '$descripcion_proser', 
    '$precio_proser',
    '$preciosugerido_proser',
    '$cantidad_proser', 
    '$cantidadfinal_proser',
    '$lote_proser',
    '$IVA_proser')";

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