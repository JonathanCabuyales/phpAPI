<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../../jwt/src/BeforeValidException.php';
require_once '../../../jwt/src/ExpiredException.php';
require_once '../../../jwt/src/SignatureInvalidException.php';
require_once '../../../jwt/src/JWT.php';
use \Firebase\JWT\JWT;

define ('SECRET_KEY', '4956andres.'); // la clave secreta puede ser una cadena aleatoria y mantenerse en secreto para cualquier persona
define ('ALGORITMO', 'HS256'); // Algoritmo utilizado para firmar el token

$json = file_get_contents('php://input');



$jwt = $_POST['token'];
try {
        JWT::$leeway = 10;
        $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));
session_start();
require_once('../../lib/nusoap.php');

header("Content-Type: text/plain");

$content = file_get_contents("../../facturaFirmada.xml");
$mensaje = base64_encode($content);

$claveAcceso = $_POST['claveAcceso'];
$service = $_POST['service'];

//EndPoint
//Endpoint de pruebas
$servicio = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl"; //url del servicio
$parametros = array(); //parametros de la llamada
$parametros['xml'] = $mensaje;

$client = new nusoap_client($servicio);


$client->soap_defencoding = 'utf-8';


$result = $client->call("validarComprobante", $parametros, "http://ec.gob.sri.ws.recepcion");
$response = array();

$file = fopen("../../log.txt", "a+");
fwrite($file, "Servicio: " . $service . PHP_EOL);
fwrite($file, "Clave Acceso: " . $claveAcceso . PHP_EOL);

//var_dump($client->getError());die;


$_SESSION['validarComprobante']=$result;

if ($client->fault) {  
    
    $file_error = fopen('../../errores/'.$claveAcceso.".txt", "w");
    fwrite($file_error, "Servicio: " . $service . PHP_EOL);
    fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
    fwrite($file_error, "Respuesta: " . print_r($result,true) . PHP_EOL);
    fwrite($file_error, "\n__________________________________________________________________\n". PHP_EOL);
    fclose($file_error);
    fwrite($file, "Respuesta: " . print_r($result,true) . PHP_EOL);
    echo serialize($result);
    
} else {
    $error = $client->getError();
    if ($error) {
        fwrite($file, "Respuesta: " . print_r($error,true) . PHP_EOL);
        $file_error = fopen('../../errores/'.$claveAcceso.".txt", "w");
        fwrite($file_error, "Servicio: " . $service . PHP_EOL);
        fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
        fwrite($file_error, "Respuesta: " . print_r($error,true) . PHP_EOL);
        fwrite($file_error, "\n__________________________________________________________________\n". PHP_EOL);
        fclose($file_error);
        echo serialize($error);
    } else {
        if ($result['estado']=='RECIBIDA'){
            fwrite($file, "Respuesta: " . print_r($result,true) . PHP_EOL);
        }else {
            fwrite($file, "Respuesta: " . print_r($result,true) . PHP_EOL);
            $file_error = fopen('../../errores/'.$claveAcceso.".txt", "w");
            fwrite($file_error, "Servicio: " . $service . PHP_EOL);
            fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);            
            fwrite($file_error, "Respuesta: " . print_r($result,true) . PHP_EOL);
            fwrite($file_error, "\n__________________________________________________________________\n". PHP_EOL);
            fclose($file_error);
        }
        echo serialize($result);
        
    }
}
fwrite($file, "\n__________________________________________________________________\n". PHP_EOL);
fclose($file);

$data_insert=array(
    "data" => array(
        'text' =>  serialize($result)
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
echo json_encode($data_insert);



