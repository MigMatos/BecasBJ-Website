<?php

/*  Si has llegado hasta aquí, felicidades! descubriste nuestro secreto JAJA
    No te preocupes, esta API por pura suerte no devuelve datos bancarios, pero consideraría 
    agregar un Rate Limit por IP o no sé, por CURPs erroneos enviados.
    Si eres del gobiernito querido, contratame porque está algo desastroso la API y no entendible
    PD: No entiendo porqué el gobierno deja abierto algunas vulnerabilidades que no expondré aquí 
        como en un sitio que es sumamente importante, en serio, pongan a trabajar al encargado de 
        seguridad de los sitios web para que revise exactamente cual y no, poner cualquier burrada
        o validaciones en todos los sitios para aumentar su "seguridad" no les va a servir si no 
        saben exactamente a que sitio web estoy hablando y si escuchan a su comunidad sobre quejas
        y reportes existentes en el stio web para mejorarlos.
*/

function getBecasJSON($curp) {
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
        // Validacion por si acaso ksks
        return(json_decode($response, true) !== null || $response === 'null') ? json_decode($response, true) : false;
    } else {return ["status" => 400];}
}

?>