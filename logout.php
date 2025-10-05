<?php
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/paths.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

logout();

header("Location: " . url('index.php?logout=success'));
exit();
?>