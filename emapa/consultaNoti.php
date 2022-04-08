<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

include ("../conexion/bd.php");

$id_medidor = $_GET["id_medidor"];

$fechaInicio = new DateTime();
$fechaInicio->modify('first day of this month');
$fechaInicio->format('Y-m-d'); // imprime por ejemplo: 01/12/2012
$fecha1 = date_format($fechaInicio,"Y-m-d");

$fechaFin = new DateTime();
$fechaFin->modify('last day of this month');
$fechaFin->format('Y-m-d'); // imprime por ejemplo: 31/12/2012
$fecha2 = date_format($fechaFin,"Y-m-d");


$query = "SELECT * FROM notificaciones 
WHERE numerocuenta_ins = '$id_medidor'
AND create_at BETWEEN '$fecha1 00:00:00' AND '$fecha2 23:59:59'";

$data=array(); 

$get = mysqli_query($con, $query);
// // WHERE create_at BETWEEN '$fechaActual 00:00:00' AND '$fechaActual 23:59:59'
if ($get) {
    $array = array();
    while ($fila = mysqli_fetch_assoc($get) ) {	
        // echo json_encode($fila);
        $data[] = array_map('utf8_encode', $fila);
    }
}else{
    $data = null;
}

$res = $data;

echo json_encode($res); 
echo mysqli_error($con);