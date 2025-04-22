<?php

function getwrapperJSON($curp) {
    $url = 'https://buscador.becasbenitojuarez.gob.mx/consulta/metodos/wrapper.php';

    $data = [
        'CURP' => $curp,
        'habilitar' => 1
    ];

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HEADER => false,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response !== false) {
        return json_decode($response, true);
    } else {
        return ["status" => 400];
    }
}

?>