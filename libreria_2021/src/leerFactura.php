<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../jwt/src/BeforeValidException.php';
require_once '../../jwt/src/ExpiredException.php';
require_once '../../jwt/src/SignatureInvalidException.php';
require_once '../../jwt/src/JWT.php';
use \Firebase\JWT\JWT;

define ('SECRET_KEY', '4956andres.'); // la clave secreta puede ser una cadena aleatoria y mantenerse en secreto para cualquier persona
define ('ALGORITMO', 'HS256'); // Algoritmo utilizado para firmar el token

$json = file_get_contents('php://input');



$jwt = $_POST['token'];
try {
        JWT::$leeway = 10;
        $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

session_start();
$ruta = $_POST['ruta_factura'];
$myxmlfilecontent = file_get_contents($ruta);


$text = trim(preg_replace('/\s+/', ' ', $myxmlfilecontent));
$text = preg_replace("/(?<=\>)(\r?\n)|(\r?\n)(?=\<\/)/", '', $text);
$text = trim(str_replace('> <', '><', $text));
$text = utf8_encode($text);

$xml = simplexml_load_string($text);
$text = utf8_decode($text);
if ($xml->attributes()->version) {
    $version = $xml->attributes()->version;
    $id = $xml->attributes()->id;

    // Agregar Encabezados
    $text = trim(preg_replace('/<factura version="' . $version . '" id="' . $id . '">/', '<factura id="' . $id . '" version="' . $version . '">', $text));
    $text = trim(preg_replace('/<notaCredito version="' . $version . '" id="' . $id . '">/', '<notaCredito id="' . $id . '" version="' . $version . '">', $text));
    $text = trim(preg_replace('/<notaDebito version="' . $version . '" id="' . $id . '">/', '<notaDebito id="' . $id . '" version="' . $version . '">', $text));
    $text = trim(preg_replace('/<comprobanteRetencion version="' . $version . '" id="' . $id . '">/', '<comprobanteRetencion id="' . $id . '" version="' . $version . '">', $text));
    $text = trim(preg_replace('/<guiaRemision version="' . $version . '" id="' . $id . '">/', '<guiaRemision id="' . $id . '" version="' . $version . '">', $text));

    $text = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $text
    );

    $text = str_replace(
            array('é', 'è', 'ë', 'ê', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E'), $text);

    $text = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $text);

    $text = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $text);

    $text = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $text);

    $text = str_replace(
            array('ç', 'Ç'), array('c', 'C'), $text
    );
    
}

$data_insert=array(
        "data" => array(
            'text' => $text
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