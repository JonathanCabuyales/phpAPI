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

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array(); 

    $query = "SELECT u.nombres, u.apellidos, u.ciruc ,s.id_usuario,s.id_sueldos, 
    s.sueldo, s.diastrabajados, s.horasextras, s.calculo_horas, s.tipohoras,
    s.bonostransporte, s.bonosalimentacion, s.otrosingresos, s.decimotercer, 
    s.decimocuarto, s.totalingresos, s.iessindividual, s.iesspatronal, 
    s.iesstotal, s.anticipos, s.prestamos_oficina, s.prestamo_hipotecario, 
    s.prestamo_quirografario, s.otrosegresos, s.total_egresos, s.neto_recibir, 
    s.contrato, s.aprobado, s.actafiniquito, s.create_at, s.mes_rol
    FROM sueldos s, usuarios u 
    WHERE s.id_usuario = u.id_usuario
    AND s.id_usuario = '$id_usuario'";

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