<?php
include("./class/becas.php");
include("./class/envloader.php");
include("./class/hcaptcha.php");
loadEnv(__DIR__ . '/../.env'); 
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secret_key = $_ENV['ACCOUNT_SECRET'];
    $curp = strtoupper(trim($_POST['CURP']));
    if (empty($curp)) {
        echo json_encode([
            'status' => 400,
            'error' => 'CURP no proporcionado'
        ]);
        exit();
    }
    $token = $_POST['h-captcha-response'] ?? '';
    if (empty($token)) {
        echo json_encode([
            'status' => 400,
            'error' => 'CAPTCHA no resuelto'
        ]);
        exit();
    }
    if(!validarHCaptcha($token, $secret_key)) {
        echo json_encode([
            'status' => 400,
            'error' => 'CAPTCHA sin validación'
        ]);
        exit();  
    }
    $data = getwrapperJSON($curp);
    echo json_encode($data);
} else {
    echo json_encode([
        'status' => 400,
        'error' => 'Método no aceptado'
    ]);
    exit();
}
?>
