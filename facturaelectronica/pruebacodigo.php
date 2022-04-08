<?php

$privateKey = openssl_pkey_new(array(
        'private_key_bits' => 2048,      // Tamaño de la llave
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ));
    // Guardar la llave privada en el archivo private.key. No compartir este archivo con nadie
openssl_pkey_export_to_file($privateKey, 'private.key');

    // Generar la llave pública para la llave privada
$a_key = openssl_pkey_get_details($privateKey);

    // Guardar la llave pública en un archivo public.key.
    // Envía este archivo a cualquiera que quiera enviarte datos encriptados
file_put_contents('public.key', $a_key['key']);

    // Libera la llave privada
openssl_free_key($privateKey);

$texto = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eleifend vestibulum nunc sit amet mattis. Nulla at volutpat nulla. Pellentesque sodales vel ligula quis consequat. Suspendisse dapibus dolor nec viverra venenatis. Pellentesque blandit vehicula eleifend.';
    echo 'Texto plano: ' . $texto;
    // Comprimir los datos a enviar
    $texto = gzcompress($texto);
    // Obtener la llave pública
    $publicKey = openssl_pkey_get_public('file:///path/to/public.key');
    $a_key = openssl_pkey_get_details($publicKey);
    // Encriptar los datos en porciones pequeñas, combinarlos y enviarlo
    $chunkSize = ceil($a_key['bits'] / 8) - 11;
    $output = '';
    while ($texto)
    {
        $chunk = substr($texto, 0, $chunkSize);
        $texto = substr($texto, $chunkSize);
        $encrypted = '';
        if (!openssl_public_encrypt($chunk, $encrypted, $publicKey))
        {
            die('Ha habido un error al encriptar');
        }
        $output .= $encrypted;
    }
    openssl_free_key($publicKey);
    // Estos son los datos encriptados finales a enviar:
    $encrypted = $output;

    echo $output;

?>