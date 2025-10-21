<?php
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['general_user', 'admin', 'owner']);

// Solo la plantilla base inteligente
include __DIR__ . '/../templates/dashboard_base.php';
?>