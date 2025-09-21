<?php
// app/paths.php

function get_base_path() {
    // Usar SCRIPT_NAME en lugar de PHP_SELF para obtener la ruta base real
    $script_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    return rtrim($script_path, '/');
}

function url($path = '') {
    $path = ltrim($path, '/');
    $base_path = get_base_path();
    return $base_path . '/' . $path;
}

function redirect_to($route) {
    header("Location: " . url($route));
    exit;
}
?>