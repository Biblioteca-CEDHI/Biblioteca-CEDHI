<?php
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['owner']);

// Incluir la plantilla base que muestra los módulos según rol
include __DIR__ . '/../templates/dashboard_base.php';

// Y LUEGO agregar el panel especial de administración
include __DIR__ . '/../templates/admin_panel.php';