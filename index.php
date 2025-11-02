<?php
require_once __DIR__ . '/app/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logged_in = false;

if (isset($_GET["code"])) {
    try {
        $userData = loginWithGoogle();

        if ($userData) {
            if (isset($userData['estado']) && $userData['estado'] !== 'activo') {
                $_SESSION['login_error'] = 'Tu cuenta está inactiva. Contacta al administrador.';
                header('Location: /BibliotecaCEDHI/index.php');
                exit;
            }

            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['user_first_name'] = $userData['first_name'];
            $_SESSION['user_last_name'] = $userData['last_name'];
            $_SESSION['user_email_address'] = $userData['email'];
            $_SESSION['role'] = $userData['role'];
            $logged_in = true;
        }

    } catch (Exception $e) {
        $_SESSION['login_error'] = $e->getMessage();
    }
}

$logged_in = $logged_in || (isset($_SESSION['access_token']) && isset($_SESSION['user_id']));

if ($logged_in) {
    require_once __DIR__ . '/app/paths.php';
    switch ($_SESSION['role']) {
        case 'owner':         redirect_to('/views/owner/dashboard.php'); break;
        case 'admin':         redirect_to('/views/admin/dashboard.php'); break;
        case 'bibliotecario': redirect_to('/views/bibliotecario/dashboard.php'); break;
        case 'tutor':         redirect_to('/views/tutor/dashboard.php'); break;
        case 'general_user':  redirect_to('/views/general_user/dashboard.php'); break;
    }
}

include __DIR__ . '/views/login.php';
?>
