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

$id_sueldos = $jsonSueldo->id_sueldos;
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
$aprobado = $jsonSueldo->aprobado;
$contrato = $jsonSueldo->contrato;
$actafiniquito = $jsonSueldo->actafiniquito;


try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $data=array();
    
    $query = "UPDATE sueldos SET
    id_usuario = '$id_usuario', 
    sueldo = '$sueldo', 
    diastrabajados= '$diastrabajados',
    horasextras = '$horasextras',
    calculo_horas = '$calculo_horas',
    tipohoras = '$tipohoras',
    bonostransporte = '$bonostransporte', 
    bonosalimentacion = '$bonosalimentacion',
    otrosingresos = '$otrosingresos',
    decimotercer = '$decimotercer', 
    decimocuarto = '$decimocuarto',
    totalingresos = '$totalingresos',
    iessindividual = '$iessindividual',
    iesspatronal = '$iesspatronal',
    iesstotal = '$iesstotal',
    anticipos = '$anticipos',
    prestamos_oficina = '$prestamos_oficina',
    prestamo_hipotecario = '$prestamo_hipotecario',
    prestamo_quirografario = '$prestamo_quirografario',
    otrosegresos = '$otrosegresos', 
    total_egresos= '$total_egresos', 
    neto_recibir= '$neto_recibir', 
    contrato = '$contrato', 
    aprobado = '$aprobado', 
    actafiniquito = '$actafiniquito'
    WHERE id_sueldos = '$id_sueldos'";

    
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