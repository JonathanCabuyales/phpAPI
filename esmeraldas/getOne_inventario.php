<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');

if (empty($_GET["idcodigo"])) {
    exit("No hay Codigo");
}
$codigo = $_GET["idcodigo"];

$query = "SELECT * FROM info_ubicacion 
WHERE codigo = '$codigo'";

$get = mysqli_query($con, $query);


if ($get) {
    $array = array();
    while ($fila = mysqli_fetch_assoc($get) ) {	
        $data[] = array_map('utf8_encode', $fila);
    }
}else{
    echo "fallo no hay nada";
    $res = null;
    echo mysqli_error($con);
}

$res = $data;

echo json_encode($res); 
echo mysqli_error($con);