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

$jsonSueldo = json_decode($json);

if (!$jsonSueldo) {
    exit("No hay datos para registrar");
}

$jwt = $jsonSueldo->token;

$id_usuario = $jsonSueldo->id_usuario;
$sueldo = $jsonSueldo->sueldo;
$diastrabajados = $jsonSueldo->diastrabajados;
$horasextras = $jsonSueldo->horasextras;
$calculo_horas = $jsonSueldo->calculo_horas;
$tipohoras = $jsonSueldo->tipohoras;
$bonostransporte = $jsonSueldo->bonostransporte;
$bonosalimentacion = $jsonSueldo->bonosalimentacion;
$otrosingresos = $jsonSueldo->otrosingresos;
$decimotercer = $jsonSueldo->decimotercer;
$decimocuarto = $jsonSueldo->decimocuarto;
$totalingresos = $jsonSueldo->totalingresos;
$iessindividual = $jsonSueldo->iessindividual;
$iesspatronal = $jsonSueldo->iesspatronal;
$iesstotal = $jsonSueldo->iesstotal;
$anticipos = $jsonSueldo->anticipos;
$prestamos_oficina = $jsonSueldo->prestamos_oficina;
$prestamo_hipotecario = $jsonSueldo->prestamo_hipotecario;
$prestamo_quirografario = $jsonSueldo->prestamo_quirografario;
$otrosegresos = $jsonSueldo->otrosegresos;
$total_egresos = $jsonSueldo->total_egresos;
$neto_recibir = $jsonSueldo->neto_recibir;
$descripcion = $jsonSueldo->descripcion;
$contrato = $jsonSueldo->contrato;
$actafiniquito = $jsonSueldo->actafiniquito;
$mes_rol = $jsonSueldo->mes_rol;


try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array();
    
    $query = "INSERT INTO sueldos (
                id_usuario, 
                sueldo, 
                diastrabajados,
                horasextras,
                calculo_horas,
                tipohoras,
                bonostransporte, 
                bonosalimentacion,
                otrosingresos,
                decimotercer, 
                decimocuarto, 
                totalingresos, 
                iessindividual, 
                iesspatronal, 
                iesstotal, 
                anticipos, 
                prestamos_oficina, 
                prestamo_hipotecario, 
                prestamo_quirografario,
                otrosegresos, 
                total_egresos, 
                neto_recibir, 
                contrato, 
                descripcion, 
                actafiniquito, 
                mes_rol) 
VALUES ('$id_usuario', '$sueldo', '$diastrabajados', '$horasextras', '$calculo_horas', '$tipohoras','$bonostransporte', '$bonosalimentacion', '$otrosingresos','$decimotercer', '$decimocuarto', '$totalingresos', 
'$iessindividual', '$iesspatronal', '$iesstotal', '$anticipos', '$prestamos_oficina', '$prestamo_hipotecario', '$prestamo_quirografario',
'$otrosegresos','$total_egresos','$neto_recibir','$contrato', '$descripcion', '$actafiniquito', '$mes_rol')";

$insert = mysqli_query($con, $query);

    $data_insert=array(
        "data" => $insert,
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