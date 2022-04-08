<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
header('Access-Control-Max-Age: 86400');

include ("../conexion/bd.php");

require_once '../jwt/src/BeforeValidException.php';
require_once '../jwt/src/ExpiredException.php';
require_once '../jwt/src/SignatureInvalidException.php';
require_once '../jwt/src/JWT.php';

use \Firebase\JWT\JWT; 

$usuario = $_GET["usuario"];
$password2 = $_GET["password"];

define ('SECRET_KEY', '4956andres.'); // la clave secreta puede ser una cadena aleatoria y mantenerse en secreto para cualquier persona
define ('ALGORITMO', 'HS256'); // Algoritmo utilizado para firmar el token

// 4956andres.
$email = $usuario;
$password = $password2;

$data=array(); 

$query = "SELECT usu.nombres, usu.apellidos, usu.rol, usu.usuario, usu.id_usuario, usu.email, usu.fotoperfil 
FROM usuarios usu, estado_usuario esusu
WHERE usu.usuario = '$usuario'
AND usu.contrasenia = '$password'
AND esusu.descripcion_esusu = 'ACTIVO'";

$get = mysqli_query($con, $query);
$data = array();
if ($get) {
    $array = array();
    while ($fila = mysqli_fetch_assoc($get) ) {	
        // echo json_encode($fila);
        $data[] = array_map('utf8_encode', $fila);
    }
}else{
    echo "fallo no hay nada";
    $res = null;
    echo mysqli_error($con);
}

$res = $data;

if ($res!=[]) {
    $array = array();
    while ($fila = mysqli_fetch_assoc($get) ) {	
        // echo json_encode($fila);
        $data[] = array_map('utf8_encode', $fila);
    }

	$res = $data;

	// echo json_encode($res);
	$usuarioid = $res[0]["id_usuario"];
	$usuarionombres = $res[0]["nombres"];
	$usuarioapellidos = $res[0]["apellidos"];
	$usuariousu = $res[0]["usuario"];
	$usuariorol = $res[0]["rol"];
	$usuarioemail = $res[0]["email"];
	$usuariofoto = $res[0]["fotoperfil"];

	$iat = time(); // time of token issued at
	$nbf = $iat + 10; //not before in seconds
	$exp = $iat + 43200; // expire time of token in seconds

	$token = array(
		"iss" => "https://contable.vt-proyectos.com.ec",
		"aud" => "https://contable.vt-proyectos.com.ec",
		"iat" => $iat,
		"nbf" => $nbf,
		"exp" => $exp,
		"data" => array(
			"id" => $usuarioid,
			"usuario" => $usuariousu,
			"rol" => $usuariorol,
			"email" => $usuarioemail,
			"foto" => $usuariofoto
			)
		);

	http_response_code(200);

	$jwt = JWT::encode($token, SECRET_KEY);
		
		
	$data_insert=array(
		'access_token' => $jwt,									
		'name' => $usuarionombres,
		'time' => time(),
		'username' => $usuariousu, 
		'status' => "success",
		'message' => "Successfully Logged In"
	);

}else{
	$data_insert=array(
		"data" => "0",
		"status" => "invalid",
		"message" => "Invalid Request"
	);
}

echo json_encode ($data_insert);