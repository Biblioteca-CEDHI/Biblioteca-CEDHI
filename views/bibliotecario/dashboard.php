<?php
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['bibliotecario', 'admin', 'owner']); // Bibliotecarios y admins pueden ver

// Incluir la plantilla base que muestra los 4 módulos
include __DIR__ . '/../templates/dashboard_base.php';
?>