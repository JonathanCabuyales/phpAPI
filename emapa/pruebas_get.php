<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

// $json = file_get_contents('php://input');

// if (empty($_GET["idMedidor"])) {
//     exit("No hay id del medidor");
// }
// $idMedidor = $_GET["idMedidor"];


$fechaFin = new DateTime();
$fechaFin->modify('last day of this month');
$fechaFin->format('Y-m-d'); // imprime por ejemplo: 31/12/2012
$fecha2 = date_format($fechaFin,"Y-m-d");

$con = mysqli_connect("190.57.171.26:3306", "gadmpc", "112233*", "AQUA") or die("could not connect DB"); 

$data=array(); 
$res;

$q=mysqli_query($con, "SELECT numerocuenta_ins, ciruc_cli, clavecatastral_cat, nombre1_cli, nombre2_cli,
apellido1_cli, apellido2_cli, direccion_cat, interseccion_cat, fechalecturaant_lco, lecturaant_lco,
fechalecturaact_lco, lecturaact_lco, consumo_lco, tipocalculo_lco, promediomensual_lco, mesesdeuda_caf, 
descripcion_clc, codigo_tca, def.prioridad_def, ite.codigo_ite, def.valorapagar_def, codigo_mic
FROM instalacion ins, cliente cli, catastros cat, lecturaconsumo lco, conexionagua conag,
cabecerafactura cf, clasecliente clc, tipoconsumoagua tca, detallefactura def, items ite,
micromedidor mic
WHERE ins.serial_cli = cli.serial_cli 
AND cat.serial_cat = ins.serial_cat
AND conag.serial_ins = ins.serial_ins
AND lco.serial_conag = conag.serial_conag
AND cf.serial_lco = lco.serial_lco
AND ins.serial_clc = clc.serial_clc
AND ins.serial_tca = tca.serial_tca
AND cf.serial_caf = def.serial_caf
AND def.serial_ite = ite.serial_ite
AND lco.serial_mic = mic.serial_mic
AND mic.codigo_mic = 'C17RA007895'
AND lco.fechalecturaact_lco BETWEEN '2021-05-01 00:00:00' AND '$fecha2 00:00:00'
ORDER BY fechalecturaact_lco ASC");


// AND lco.fechalecturaact_lco BETWEEN '2021-05-01 00:00:00' AND '2021-05-31 00:00:00'

// 10006 ----- C17RA005367
// 2222  ----- 17007843


if ($q) {
    $array = array();
    while ($fila = mysqli_fetch_assoc($q)) {	
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