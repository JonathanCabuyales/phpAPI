<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');


include ("../conexion/bd.php");

$json = file_get_contents('php://input');

if (empty($_GET["id_proser"])) {
    exit("No hay id del medidor");
}

$id_proser = $_GET["id_proser"];
$id_usuario = $_GET["id_usuario"];

$query = "SELECT usu.nombres, usu.apellidos, descripcion_proser, stockasignado_inv 
FROM inventario_asignado inv, usuarios usu, productos_servicios proser
WHERE usu.id_usuario = '$id_usuario'
AND proser.id_proser = '$id_proser'";

$get = mysqli_query($con,$query);

if ($get) {
    $array = array();
    while ($fila = mysqli_fetch_assoc($get)) {	
        // echo json_encode($fila);
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