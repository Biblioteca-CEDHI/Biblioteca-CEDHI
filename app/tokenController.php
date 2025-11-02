<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateToken($expSeconds = 3600) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $payload = [
        'userId'   => $_SESSION['user_id'] ?? null,
        'email'    => $_SESSION['user_email_address'] ?? null,
        'nombre'   => $_SESSION['user_first_name'] ?? null,
        'apellido' => $_SESSION['user_last_name'] ?? null,
        'rol'      => $_SESSION['role'] ?? null,
        'iat'      => time(),
        'exp'      => time() + $expSeconds
    ];

    $key = 'cedhi2024biblio';
    return Firebase\JWT\JWT::encode($payload, $key, 'HS256');
}

?>