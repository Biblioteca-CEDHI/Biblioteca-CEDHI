<?php
require_once __DIR__ . '/../../app/database.php';
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['owner']);

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, CONCAT(first_name, ' ', last_name) AS nombre, email
    FROM users
    WHERE role != 'owner' AND (first_name LIKE :q OR last_name LIKE :q OR email LIKE :q)
    AND id NOT IN (SELECT user_id FROM module_admins)
    LIMIT 10
");
$stmt->execute([':q' => "%$q%"]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($usuarios);