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
 
$jsonProyecto = json_decode($json);

if (!$jsonProyecto) {
    exit("No hay datos para registrar");
}

// para iterar con objetos se usa flechas ->
// para iterar en arreglos se usa corchetes []

$jwt = $jsonProyecto->token;
$email = $jsonProyecto->email;
$nombre = $jsonProyecto->nombres.' '.$jsonProyecto->apellidos;
$analisis = $jsonProyecto->analisis->analisis;
$totalequipo = $jsonProyecto->analisis->totalequipo;
$totalinsumos = $jsonProyecto->analisis->totalinsumos;
$totalpersonal = $jsonProyecto->analisis->totalpersonal;
$valoraCobrar = $jsonProyecto->analisis->valoraCobrar;
$descripcion = $jsonProyecto->descripcion_pro;

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
    $to = $email;
    
    // asunto del mensaje
    $subject = "SOLICITUD DE PERMISO";
    
    // cual es el mensaje
  
    $mensaje = "<!DOCTYPE html>
    <html lang='es'>

  <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Proyecto Aprobado</title>

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
      background: #fff;
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
      color: #000;
    }

    .texto {
      margin-top: 20px;
      color: #000;
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
        <img src='https://www.vt-proyectos.com.ec/assets/img/Logo vt blanco.png' alt='VT PROYECTOS' style='width: 40px; height: 50px; margin-right: 10px;'>
        <span style='text-align: center;'>VT PROYECTOS</span>
      </div>

      <div class='mensaje'>
        <h3 style='text-align: center; color: #000;'>Proyecto Aprobado</h3>
        <div class='texto'>
          Por medio de la presente estiamdo/a: $nombre, damos a conocer que se ha aprobado el proyecto 
          <b>$descripcion</b>, con los siguientes detalles: 
        <br>
        <br>
        <b>Analisis:</b> 

            <ul style='list-style: none;'>
              <li><b>Valor a Cobrar: </b> $valoraCobrar $</li>
              <li><b>Total Personal:</b> $totalpersonal $</li>
              <li><b>Total Equipo Minimo:</b> $totalequipo $</li>
              <li><b>Total Insumos:</b> $totalinsumos $ </li>
              <li><b>Analisis:</b> $analis $</li>
            </ul>

        <br>
        <br>
        <b>Nota:</b> Recuerde que el proyecto <b>$descripcion</b> esta bajo su responsabilidad, por favor ingrese 
         las fechas para el proyecto.
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
    
    // debes aumentar el destinatario 
    // .", victor.tipan@vt-proyectos.com.ec"
    $headers .= "Cc: $to"."\r\n";

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