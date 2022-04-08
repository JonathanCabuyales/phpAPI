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

$jwt = $_GET['token'];
$id_usuario = $_GET['id_usuario'];

// $jsonPermiso = json_decode($json);

// if (!$jsonPermiso) {
//     exit("No hay datos para registrar");
// }

// $jwt = $jsonPermiso->token;

// $id_usuario = $jsonPermiso->id_usuario;


try {

    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array(); 

    $query = "SELECT usu.nombres, usu.apellidos, usu.email, usu.usuario, pro.id_pro, 
    pro.id_usuario, pro.descripcion_pro, pro.estado_pro, pro.tiempo_pro, pro.fechas_pro, 
    pro.viabilidad_pro, pro.valores_pro, pro.empleados_pro, pro.equipo_pro, pro.insumos_pro, 
    pro.rendimiento_pro
    FROM proyeccion pro, usuarios usu
    WHERE pro.id_usuario = '$id_usuario' 
    AND pro.id_usuario = usu.id_usuario
    ORDER BY pro.create_at DESC";

    $get = mysqli_query($con, $query);

    $data = array();

    if ($get) {
        $array = array();
        while ($fila = mysqli_fetch_assoc($get)) {	
        // echo json_encode($fila);
        $data[] = array_map('utf8_encode', $fila);
        }
    }else{
        $data = array();
    }

    $res = $data;


    $data_insert=array(
        "data" => $res,
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