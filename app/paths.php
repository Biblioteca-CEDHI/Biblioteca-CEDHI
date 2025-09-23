<?php
// app/paths.php

function get_base_path()
{
    // Esto devuelve algo como "/Biblioteca-CEDHI" o simplemente "/" si está en raíz
    return '/' . trim(explode('/', trim($_SERVER['SCRIPT_NAME'], '/'))[0], '/');
}

function url($path = '')
{
    $path = ltrim($path, '/');
    return get_base_path() . '/' . $path;
}

function redirect_to($route)
{
    header("Location: " . url($route));
    exit;
}
