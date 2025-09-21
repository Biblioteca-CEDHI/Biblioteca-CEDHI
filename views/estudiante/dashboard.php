<?php
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['estudiante', 'admin', 'owner']);

// Solo la plantilla base inteligente
include __DIR__ . '/../templates/dashboard_base.php';
?>