<?php
$host = "localhost";
$user = "root"; // tu usuario de MySQL
$pass = "";     // tu contraseña de MySQL (por defecto en XAMPP está vacía)
$db   = "biblioteca_cedhi";// nombre de la bd

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error en la conexión: " . $e->getMessage());
}
