<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

include ("../conexion/bd.php");

// // Consulta con el primer y ultimo dia del mes anterior
// $query = "SELECT fechalecant_lec, lecturaant_lec, fechalecact_lec, lecturaact_lec, codigo_med, estado_ins, nombres_cli, apellidos_cli, ciruc_cli, direccion_cli, email_cli, telefono_cli 
// FROM lecturas lec, medidor med, instalacion ins, clientes cli 
// where med.id_med = lec.id_med 
// and med.estado_med = 'ACTIVO' 
// and med.codigo_med = '1234567890' 
// and ins.id_ins = med.id_ins 
// and ins.id_cli = cli.id_cli 
// and fechalecact_lec BETWEEN (SELECT DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01 00:00:00')) AND (SELECT DATE_FORMAT(LAST_DAY(CURDATE()-INTERVAL 1 MONTH),'%Y-%m-%d 23:59:59'))";

// 1742903162

// // Consulta con el primer y ultimo día del mes actual
// $query = "SELECT fechalecant_lec, lecturaant_lec, fechalecact_lec, lecturaact_lec, codigo_med, estado_ins, nombres_cli, apellidos_cli, ciruc_cli, direccion_cli, email_cli, telefono_cli 
// FROM lecturas lec, medidor med, instalacion ins, clientes cli 
// where med.id_med = lec.id_med 
// and med.estado_med = 'ACTIVO' 
// and med.codigo_med = '1234567890' 
// and ins.id_ins = med.id_ins 
// and ins.id_cli = cli.id_cli 
// and fechalecact_lec BETWEEN (SELECT DATE_FORMAT(CURDATE(),'%Y-%m-01 00:00:00')) AND (SELECT DATE_FORMAT(LAST_DAY(CURDATE()),'%Y-%m-%d 23:59:59'))";

$DateAndTime = date('m-d-Y h:i:s', time());  
echo "The current date and time are $DateAndTime.";

// $query = "Select * FROM medidor med, instalacion ins, clientes cli 
// WHERE med.codigo_med = 1751803162 
// AND ins.id_ins = med.id_ins 
// AND ins.id_cli = cli.id_cli";

// $get = mysqli_query($con, $query);

// if ($get) {

//     $array = array();
//     while ($fila = mysqli_fetch_assoc($get) ) {	
//         // echo json_encode($fila);
//         $data[] = array_map('utf8_encode', $fila);
//     }
    
// }else{

//     echo "fallo no hay nada";
//     $res = null;
//     echo mysqli_error($con);

// }

// $res = $data;

// echo json_encode($res);
// echo mysqli_error($con);