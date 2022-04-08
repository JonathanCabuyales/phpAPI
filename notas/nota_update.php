<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: text/html;charset=utf-8");

require_once '../jwt/src/BeforeValidException.php';
require_once '../jwt/src/ExpiredException.php';
require_once '../jwt/src/SignatureInvalidException.php';
require_once '../jwt/src/JWT.php';
use \Firebase\JWT\JWT;

define ('SECRET_KEY', '4956andres.'); // la clave secreta puede ser una cadena aleatoria y mantenerse en secreto para cualquier persona
define ('ALGORITMO', 'HS256'); // Algoritmo utilizado para firmar el token

$json = file_get_contents('php://input');

// $jwt = $_GET['token'];

$jsonNota = json_decode($json);

if (!$jsonNota) {
    exit("No hay datos para registrar");
}

$jwt = $jsonNota->token;

$nota = $jsonNota->descripcion_nota;
$id_nota = $jsonNota->id_nota;

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array();

    // // para que pueda captar las tildes
    // $acentos = $con->query("SET NAMES 'utf8'");

    // $nota = html_entity_decode($nota, ENT_QUOTES | ENT_HTML401, "UTF-8");
    
    $query = "UPDATE notas SET
    descripcion_nota = '$nota'
    WHERE id_nota = '$id_nota'";

    
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