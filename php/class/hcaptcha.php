<?php
function validarHCaptcha(string $token, string $secret): bool {
    if (empty($token)) return false;

    $remoteip = $_SERVER['REMOTE_ADDR'];
    $data = [
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $remoteip
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents("https://hcaptcha.com/siteverify", false, $context);

    if ($result === false) return false;

    $response = json_decode($result);
    return $response->success ?? false;
}
?>