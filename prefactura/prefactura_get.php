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

$jwt = $_GET['token'];


try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    include ("../conexion/bd.php");

    $query = "SELECT cli.id_cli, cli.nombres_cli, cli.apellidos_cli, cli.ciruc_cli, cli.direccion_cli, 
    cli.email_cli, cli.telefono_cli, id_prefac, id_usuario, servicios_prefac, impuesto_prefac, neto_prefac, total_prefac,
    metodo_prefac, convenio, mesesatraso_prefac, monto_con, numerospagos_con, valorpagos_con, cuotasporpagar_con
    FROM prefactura prefac, clientes cli
    where prefac.id_cli = cli.id_cli
    AND facturagenerada_prefac = 'N'";

    $get = mysqli_query($con, $query);

    $data = array();

    if ($get) {
        $array = array();
        while ($fila = mysqli_fetch_assoc($get) ) {	
            // echo json_encode($fila);
            $data[] = array_map('utf8_encode', $fila);
        }
    }else{
        $res = array();
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