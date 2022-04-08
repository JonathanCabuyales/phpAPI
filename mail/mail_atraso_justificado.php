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
 
$jsonEmpleado = json_decode($json);

if (!$jsonEmpleado) {
    exit("No hay datos para registrar");
}

$jwt = $jsonEmpleado->token;

$nombreUsuario = 'VT PROYECTOS';
$telefonoUsuario = '0962871530';
$mensajeUsuario = 'mensaje de prueba con html';
$correoUsuario = 'contabilidad@vt-proyectos.com.ec';

try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($jwt, SECRET_KEY, array(ALGORITMO));

    // Access is granted. Add code of the operation here 

    // Las 2 primeras lineas habilitan el informe de errores
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
    
    // de quien es el mensaje
    $from = $correoUsuario;
    
    // para quien es el mensaje
    $to = $jsonEmpleado->correo;
    
    // asunto del mensaje
    $subject = "AVISO DE ATRASO";
    
    // cual es el mensaje
  
    //   echo json_encode($mailsocio);

    $empleado = $jsonEmpleado->nombre;
    $fecha_atr = $jsonEmpleado->fecha_atr;
    $descripcion_atr = $jsonEmpleado->descripcion_atr;
    $tiempo_atr = $jsonEmpleado->tiempo_atr;
  
    $mensaje = "
    <!DOCTYPE html>
    <html lang='es'>
    
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Atraso</title>
    
      <style>
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
    
        .container {
          max-width: 1000px;
          width: 90%;
          margin: 0 auto;
        }
    
        .bg-dark {
          background: #8b8484;
          margin-top: 40px;
          padding: 20px 0;
        }
    
        .alert {
          font-size: 1.5em;
          position: relative;
          padding: .75rem 1.25rem;
          margin-bottom: 2rem;
          border: 1px solid transparent;
          border-radius: .25rem;
          display: flex;
          align-items: center;
        }
    
        .alert-primary {
          color: #fff;
          background-color: #1d1d24;
          border-color: #1d1d24;
        }
    
        .img-fluid {
          max-width: 100%;
          height: auto;
        }
    
        .mensaje {
          width: 80%;
          font-size: 20px;
          margin: 0 auto 40px;
          color: #eee;
        }
    
        .texto {
          margin-top: 20px;
          color: #fff;
        }
    
        .footer {
          width: 100%;
          background: #1d1d24;
          text-align: center;
          color: #ddd;
          padding: 10px;
          font-size: 14px;
        }
    
        .footer span {
          text-decoration: underline;
        }
      </style>
    </head>
    
    <body>
      <div class='container'>
        <div class='bg-dark'>
          <div class='alert alert-primary'>
            <img src='https://www.vt-proyectos.com.ec/assets/img/Logo vt blanco.png' alt='VT PROYECTOS'
              style='width: 40px; height: 50px; margin-right: 10px;'>
            <span style='text-align: center;'>VT PROYECTOS</span>
          </div>
    
          <div class='mensaje'>
            <h3 style='text-align: center; color: #ffffff;'>Justificación De Atraso</h3>
            <div class='texto'>
              Estimado/a nombre empelado, nos ponemos en contacto con usted para informarle que se ha justificado
              el atraso con la siguiente información:
              <br>
              <br>
              <ul>
                <li><b>Fecha: </b> fecha del atraso</li>
                <li><b>Motivo:</b> descripcion</li>
                <li><b>Tiempo de atraso:</b> tiempo min(s)</li>
              </ul>
              <br>
              <br>
            </div>
          </div>
    
          <div class='footer'>
            www.vt-proyectos.com.ec
          </div>
        </div>
      </div>
    </body>
    
    </html>
    ";

    //para el envío en formato HTML 
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // More headers
    $headers .= "From: <$correoUsuario>" . "\r\n";
    $headers .= "Cc: $to" .", victor.tipan@vt-proyectos.com.ec". "\r\n";

    // esta funcion ejecuta el correo PHP
    $sendMail = mail($to, $subject, $mensaje, $headers);

    if( $sendMail ) {
      $data_insert=array(
        "data" => true,
        "status" => "success",
        "message" => "Request authorized"
      ); 
    } else {
      $data_insert=array(
        "data" => false,
        "status" => "success",
        "message" => "Request authorized"
      ); 
    } 

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