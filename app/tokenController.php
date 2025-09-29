<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateToken() {
    $payload = [
        'userId' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email_address'],
        'nombre' => $_SESSION['user_first_name'],
        'apellido' => $_SESSION['user_last_name'],
        'rol' => $_SESSION['role'],
        'iat' => time(),
        'exp' => time() + 3600 
    ];
    $key = 'cedhi2024biblio'; 
    $token = JWT::encode($payload, $key, 'HS256');
    return $token;
}

?>
