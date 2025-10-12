<?php
$host = "sql108.infinityfree.com";
$user = "if0_40148208";
$pass = "Uswok43PHAk";    
$db   = "if0_40148208_biblioteca_cedhi";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
