<?php
function requireRole($allowedRoles) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $isAuthenticated = isset($_SESSION['user_id']) && isset($_SESSION['role']);
    $hasPermission = $isAuthenticated && in_array($_SESSION['role'], (array)$allowedRoles);
    
    if (!$isAuthenticated || !$hasPermission) {
        session_unset();
        session_destroy();
        
        $_SESSION['login_error'] = "Acceso denegado. No tienes permisos para acceder a esta página.";
        header("Location: " . dirname($_SERVER['PHP_SELF']) . "/index.php");
        exit();
    }
}
?>