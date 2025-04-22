<?php
function loadEnv($path)
{
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);
        // Ignorar comentarios y líneas sin '='
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$name, $value] = explode('=', $line, 2);
        $name = sanitizeEnvKey(trim($name));
        $value = sanitizeEnvValue(trim($value));
        // Quitar comillas si las tiene
        $value = trim($value, "\"'");
        // Guardar en entorno
        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

function sanitizeEnvKey(string $key): string
{
    return preg_replace('/[^A-Z0-9_]/i', '', $key);
}

function sanitizeEnvValue(string $value): string
{
    $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
    $value = trim($value);
    return $value;
}
?>