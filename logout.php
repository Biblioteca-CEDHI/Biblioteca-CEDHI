<?php
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/paths.php';

session_start();
logout();

header("Location: " . url('index.php?logout=success'));
exit();
?>