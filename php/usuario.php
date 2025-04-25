<?php
include("./class/becas.php");
include("./class/envloader.php");
include("./class/hcaptcha.php");
loadEnv(__DIR__ . '/../.env'); 
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secret_key = $_ENV['ACCOUNT_SECRET'];
    $curp = strtoupper(trim($_POST['CURP'] ?? ''));
    $token = $_POST['h-captcha-response'] ?? '';
    $data = [];

    if (empty($curp)) {
        echo json_encode([
            'status' => 400,
            'error' => 'No has proporcionado una CURP'
        ]);
        exit();
    }
    if (empty($token)) {
        echo json_encode([
            'status' => 400,
            'error' => 'Porfavor, resuelve el Captcha'
        ]);
        exit();
    }
    if(!validarHCaptcha($token, $secret_key)) {
        echo json_encode([
            'status' => 400,
            'error' => 'Actualiza la página y resuelve el Captcha'
        ]);
        exit();  
    }
    // Lo dejamos de ultimo, asi evitamos sobrecargar la API y que el Captcha haga su trabajo jsjs
    $data = getBecasJSON($curp);
    if(!$data || empty($data)){
        echo json_encode([
            'status' => 400,
            'error' => 'No hay datos o no está funcionando actualmente el sitio web para obtener tu información.'
        ]);
        exit();  
    }

    echo json_encode($data);
} else {
    echo json_encode([
        'status' => 400,
        'error' => 'Método no aceptado',
        'alt' => "No sé que intentas pero ¡HOLA!", // Esto no debería verse, che webscrippers
    ]);
    exit();
}
?>
