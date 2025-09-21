<?php
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['tutor', 'admin', 'owner']); // Tutores y admins pueden ver

include __DIR__ . '/../templates/dashboard_base.php';
?>