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

$json = file_get_contents('php://input');
 
$jsonEmpleado = json_decode($json);

if (!$jsonEmpleado) {
    exit("No hay datos para registrar");
}

$id_usuario = $jsonEmpleado->id_usuario;
$fotoperfil = $jsonEmpleado->fotoperfil;
$user = $jsonEmpleado->usuario;
$password = $jsonEmpleado->contrasenia;
$nombres = $jsonEmpleado->nombres;
$apellidos = $jsonEmpleado->apellidos;
$cedula = $jsonEmpleado->ciruc;
$direccion = $jsonEmpleado->direccion;
$telefono = $jsonEmpleado->telefono;
$email = $jsonEmpleado->email;

$jwt = $jsonEmpleado->token;

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array();
    
    $query = "UPDATE usuarios set usuario = '$user',
    contrasenia = '$password', 
    nombres = '$nombres', 
    apellidos = '$apellidos', 
    ciruc = '$cedula', 
    direccion = '$direccion',
    telefono = '$telefono'
    WHERE id_usuario = '$id_usuario'";
        
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