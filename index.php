<?php
require_once __DIR__ . '/app/auth.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logged_in = false;

// Intenta el login solo si se recibe el código de Google
if (isset($_GET["code"])) {
    try {
        $userData = loginWithGoogle(); // Esta función ahora puede lanzar una excepción

        if ($userData) {
            // Guardar en sesión los datos DEVUELTOS por la función (que vienen de la BD)
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['user_first_name'] = $userData['first_name'];
            $_SESSION['user_last_name'] = $userData['last_name'];
            $_SESSION['user_email_address'] = $userData['email'];
            $_SESSION['role'] = $userData['role']; // Este es el rol VERDADERO desde la BD
            $logged_in = true;
        }
    } catch (Exception $e) {
        // Manejar el error (ej: mostrar un mensaje al usuario)
        $error_message = $e->getMessage();
        // Puedes guardar este error en una variable de sesión para mostrarlo en la vista login.php
        $_SESSION['login_error'] = $error_message;
    }
}

// Verificar si ya está logueado (por la sesión, no solo por el token de Google)
$logged_in = $logged_in || (isset($_SESSION['access_token']) && isset($_SESSION['user_id']));

// Redirigir si está logueado
if ($logged_in) {
    require_once __DIR__ . '/app/paths.php';
    switch ($_SESSION['role']) {
        case 'owner':        redirect_to('/views/owner/dashboard.php'); break;
        case 'admin':        redirect_to('/views/admin/dashboard.php'); break;
        case 'bibliotecario': redirect_to('/views/bibliotecario/dashboard.php'); break;
        case 'tutor':        redirect_to('/views/tutor/dashboard.php'); break;
        case 'general_user': redirect_to('/views/general_user/dashboard.php'); break;
    }
}

// Si NO está logueado, mostrar la página de login
include __DIR__ . '/views/login.php';
?>