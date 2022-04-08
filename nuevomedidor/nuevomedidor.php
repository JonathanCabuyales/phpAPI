<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');
 
$jsonNuevoMedidor = json_decode($json);

if (!$jsonNuevoMedidor) {
    exit("No hay datos para registrar");
}

// Valor nueva instalacion revibe un booleano 0 es falso y 1 es verdadero
// estado_ins verifica si esta activa la cuenta o no recibe una letra A activo N no activo

$id_cat = 0;
$id_cli = $jsonNuevoMedidor;
$fechacreacion_ins = date('Y-m-d H:i:s');
$fechabaja_ins = '';
$nueva_ins = 1;
$estado_ins = 'A';


$query = "INSERT INTO instalacion (id_cat, id_cli, fechacreacion_ins, fechabaja_ins, nueva_ins, estado_ins) 
VALUES ('$id_cat', '$id_cli', '$fechacreacion_ins', '', '$nueva_ins', '$estado_ins')";

$insert = mysqli_query($con, $query);

if($insert == true){
    $id_instalacion = mysqli_insert_id($con); 
    echo json_encode($id_instalacion);
}else{
    echo json_encode("No se pudo insertar el registro");
}