<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
ini_set('memory_limit', '1024M');

$con = mysqli_connect("190.57.171.26:3306", "gadmpc", "112233*", "AQUA") or die("could not connect DB"); 

$data=array(); 
$res;

$q=mysqli_query($con, "SELECT * FROM causanolectura");

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
?>