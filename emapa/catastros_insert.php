<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

include ("../conexion/bd.php");

$json = file_get_contents('php://input');
 
$jsonNovedad = json_decode($json);

if (!$jsonNovedad) {
    exit("No hay datos para registrar");
}

$altura= $jsonNovedad->altura;
$apellido1_cat= $jsonNovedad->apellido1_cat;
$apellido2_cat= $jsonNovedad->apellido2_cat;
$codigo_clp= $jsonNovedad->codigo_clp;
$codigo_mic= $jsonNovedad->codigo_mic;
$departamento_cat= $jsonNovedad->departamento_cat;
$descripcion_bar= $jsonNovedad->descripcion_bar;
$descripcion_clp= $jsonNovedad->descripcion_clp;
$descripcion_eme= $jsonNovedad->descripcion_eme;
$descripcion_rut= $jsonNovedad->descripcion_rut;
$descripcion_sec= $jsonNovedad->descripcion_sec;
$descripcion_taa= $jsonNovedad->descripcion_taa;
$descripcion_tal= $jsonNovedad->descripcion_tal;
$direccion_cat= $jsonNovedad->direccion_cat;
$disponecisternatanque_cat= $jsonNovedad->disponecisternatanque_cat;
$disponeconexionagua_cat= $jsonNovedad->disponeconexionagua_cat;
$disponeconexionalc_cat= $jsonNovedad->disponeconexionalc_cat;
$disponemicromedidor_cat= $jsonNovedad->disponemicromedidor_cat;
$fechainstalmed_conag= $jsonNovedad->fechainstalmed_conag;
$imageponchada= $jsonNovedad->imageponchada;
$imagepredio= $jsonNovedad->imagepredio;
$interseccion_cat= $jsonNovedad->interseccion_cat;
$latitude= $jsonNovedad->latitude;
$longitude= $jsonNovedad->longitude;
$manzana_cat= $jsonNovedad->manzana_cat;
$nombre1_cat= $jsonNovedad->nombre1_cat;
$nombre2_cat= $jsonNovedad->nombre2_cat;
$numeracion_cat= $jsonNovedad->numeracion_cat;
$numerocuenta_ins= $jsonNovedad->numerocuenta_ins;
$numerofamilias_cat= $jsonNovedad->numerofamilias_cat;
$numeropersonas_cat= $jsonNovedad->numeropersonas_cat;
$piso_cat= $jsonNovedad->piso_cat;
$recoleccionbasura_cat= $jsonNovedad->recoleccionbasura_cat;
$secuencia_cat= $jsonNovedad->secuencia_cat;
$tipoclienteagua_cat= $jsonNovedad->tipoclienteagua_cat;
$tipoclientealcantarillado_cat= $jsonNovedad->tipoclientealcantarillado_cat;
$fechados = $jsonNovedad->fechados;

$query = "INSERT INTO catastros 
(nombre1_cat, nombre2_cat,
apellido1_cat,
apellido2_cat,
codigo_clp,
codigo_mic,
departamento_cat,
descripcion_bar,
descripcion_clp,
descripcion_eme,
descripcion_rut,
descripcion_sec,
descripcion_taa,
descripcion_tal,
direccion_cat,
disponecisternatanque_cat,
disponeconexionagua_cat,
disponeconexionalc_cat,
disponemicromedidor_cat,
fechainstalmed_conag,
interseccion_cat,
manzana_cat,
numeracion_cat,
numerocuenta_ins,
numerofamilias_cat,
numeropersonas_cat,
piso_cat,
recoleccionbasura_cat,
secuencia_cat,
tipoclienteagua_cat,
tipoclientealcantarillado_cat,
imageponchada,
imagepredio,
latitude,
longitude,
altura,
fechados) 
VALUES ('$nombre1_cat', '$nombre2_cat',
'$apellido1_cat',
'$apellido2_cat',
'$codigo_clp',
'$codigo_mic',
'$departamento_cat',
'$descripcion_bar',
'$descripcion_clp',
'$descripcion_eme',
'$descripcion_rut',
'$descripcion_sec',
'$descripcion_taa',
'$descripcion_tal',
'$direccion_cat',
'$disponecisternatanque_cat',
'$disponeconexionagua_cat',
'$disponeconexionalc_cat',
'$disponemicromedidor_cat',
'$fechainstalmed_conag',
'$interseccion_cat',
'$manzana_cat',
'$numeracion_cat',
'$numerocuenta_ins',
'$numerofamilias_cat',
'$numeropersonas_cat',
'$piso_cat',
'$recoleccionbasura_cat',
'$secuencia_cat',
'$tipoclienteagua_cat',
'$tipoclientealcantarillado_cat',
'$imageponchada',
'$imagepredio',
'$latitude',
'$longitude',
'$altura',
'$fechados')";

$insert = mysqli_query($con, $query);

class Result {}

$response = new Result();
$response->resultado = 'OK';

header('Content-Type: application/json');
echo json_encode($response);