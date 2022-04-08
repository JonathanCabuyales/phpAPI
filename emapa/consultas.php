<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
ini_set('memory_limit', '1024M');


$json = file_get_contents('php://input');

if (empty($_GET["idMedidor"])) {
    exit("No hay id del medidor");
}
$idMedidor = $_GET["idMedidor"];


$con = mysqli_connect("190.57.171.26:3306", "gadmpc", "112233*", "AQUA") or die("could not connect DB"); 

$data=array(); 
$res;

$q=mysqli_query($con, "SELECT codigo_mic, numerocuenta_ins, ciruc_cli,descripcion_eme, fechainstalmed_conag, numerocuenta_ins, cat.manzana_cat, cat.secuencia_cat,
cat.piso_cat, cat.departamento_cat, cat.apellido1_cat, cat.apellido2_cat, cat.nombre1_cat, cat.nombre2_cat, cat.direccion_cat, cat.interseccion_cat,
cat.numerofamilias_cat, clavecatastral_cat, cat.numeropersonas_cat, cat.disponeconexionagua_cat, cat.disponeconexionalc_cat, taa.descripcion_taa,
descripcion_tal, clp.codigo_clp, clp.descripcion_clp, bar.descripcion_bar, rut.descripcion_rut, sec.descripcion_sec,
cat.numeracion_cat, cat.recoleccionbasura_cat, cat.disponecisternatanque_cat, cat.disponemicromedidor_cat, cat.tipoclienteagua_cat, 
cat.tipoclientealcantarillado_cat
FROM micromedidor mic, estadomedidor eme, conexionagua conag, instalacion ins, catastros cat,
tipoabastecimientoagua taa, tipoalcantarillado tal, clasepredio clp, barrio bar, ruta rut, sector sec,
cliente cli
where mic.serial_eme = eme.serial_eme
AND conag.serial_mic = mic.serial_mic
AND ins.numerocuenta_ins = '$idMedidor'
AND conag.serial_ins = ins.serial_ins
AND ins.serial_cli = cli.serial_cli
AND cat.serial_cat = ins.serial_cat
AND cat.serial_taa = taa.serial_taa
AND cat.serial_tal = tal.serial_tal
AND cat.serial_clp = clp.serial_clp
AND cat.serial_rut = rut.serial_rut
AND rut.serial_sec = sec.serial_sec
AND bar.serial_bar = cat.serial_bar");


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

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header('Content-Type: application/json');
// ini_set('memory_limit', '1024M');


// $json = file_get_contents('php://input');

// if (empty($_GET["idMedidor"])) {
//     exit("No hay id del medidor");
// }
// $idMedidor = $_GET["idMedidor"];


// $con = mysqli_connect("190.57.171.26:3306", "gadmpc", "112233*", "AQUA") or die("could not connect DB"); 

// $data=array(); 
// $res;

// $q=mysqli_query($con, "SELECT codigo_mic, numerocuenta_ins, descripcion_eme, fechainstalmed_conag, numerocuenta_ins, cat.manzana_cat, cat.secuencia_cat,
// cat.piso_cat, cat.departamento_cat, cat.apellido1_cat, cat.apellido2_cat, cat.nombre1_cat, cat.nombre2_cat, cat.direccion_cat, cat.interseccion_cat,
// cat.numerofamilias_cat, cat.numeropersonas_cat, cat.disponeconexionagua_cat, cat.disponeconexionalc_cat, taa.descripcion_taa,
// descripcion_tal, clp.codigo_clp, clp.descripcion_clp, bar.descripcion_bar, rut.descripcion_rut, sec.descripcion_sec,
// cat.numeracion_cat, cat.recoleccionbasura_cat, cat.disponecisternatanque_cat, cat.disponemicromedidor_cat, cat.tipoclienteagua_cat, 
// cat.tipoclientealcantarillado_cat
// FROM micromedidor mic, estadomedidor eme, conexionagua conag, instalacion ins, catastros cat,
// tipoabastecimientoagua taa, tipoalcantarillado tal, clasepredio clp, barrio bar, ruta rut, sector sec
// where mic.serial_eme = eme.serial_eme
// AND conag.serial_mic = mic.serial_mic
// AND mic.codigo_mic = '$idMedidor'
// AND conag.serial_ins = ins.serial_ins
// AND cat.serial_cat = ins.serial_cat
// AND cat.serial_taa = taa.serial_taa
// AND cat.serial_tal = tal.serial_tal
// AND cat.serial_clp = clp.serial_clp
// AND cat.serial_rut = rut.serial_rut
// AND rut.serial_sec = sec.serial_sec
// AND bar.serial_bar = cat.serial_bar");


// if ($q) {
//     $array = array();
//     while ($fila = mysqli_fetch_assoc($q)) {	
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