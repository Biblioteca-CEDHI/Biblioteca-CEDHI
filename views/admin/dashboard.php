<?php
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['admin', 'owner']);

// Incluir la plantilla base que muestra los módulos según rol
include __DIR__ . '/../templates/dashboard_base.php';