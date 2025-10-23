<?php
require_once __DIR__ . '/../../app/database.php';
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['owner']);

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    $stmt = $pdo->query("
        SELECT id, CONCAT(first_name, ' ', last_name) AS nombre, email
        FROM users
        WHERE role = 'admin'
        ORDER BY first_name ASC
        LIMIT 50
    ");
}

$stmt = $pdo->prepare("
    SELECT id, CONCAT(first_name, ' ', last_name) AS nombre, email
    FROM users
    WHERE role = 'admin' AND (first_name LIKE :q OR last_name LIKE :q OR email LIKE :q)
    LIMIT 10
");
$stmt->execute([':q' => "%$q%"]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($usuarios);