<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca CEDHI</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .module-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.2);
        }
        .module-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .module-disabled {
            opacity: 0.6;
            filter: grayscale(100%);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="welcome-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4">¡Bienvenido, <?php echo $_SESSION['user_first_name']; ?>!</h1>
                    <p class="lead">Sistema Integral de Biblioteca CEDHI</p>
                    <span class="badge badge-light badge-pill p-2">
                        <i class="fas fa-user-shield mr-1"></i>
                        <?php echo ucfirst($_SESSION['role']); ?>
                    </span>
                </div>
                <div class="col-md-4 text-center">
                    <img src="<?php echo $_SESSION['user_image']; ?>" 
                         class="img-fluid rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover;" 
                         alt="Avatar">
                </div>
            </div>
        </div>

        <div class="row">
            <?php
            $userRole = $_SESSION['role'];
            
            if (true) { // Todos los roles tienen acceso
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card module-card">
                    <div class="card-body text-center">
                        <div class="module-icon text-primary">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h5 class="card-title">Biblioteca Virtual</h5>
                        <p class="card-text">Accede al catálogo completo de libros digitales</p>
                        <a href="https://elibro.net/es/lc/cedhinuevaarequipa/login_usuario/" 
                           target="_blank" 
                           class="btn btn-primary btn-block">
                            <i class="fas fa-external-link-alt mr-2"></i>Ingresar
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php
            if (true) { // Todos los roles tienen acceso
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card module-card">
                    <div class="card-body text-center">
                        <div class="module-icon text-success">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="card-title">Planes de Negocio</h5>
                        <p class="card-text">Repositorio de planes y proyectos empresariales</p>
                        <a href="/modulo-planes-negocio/" 
                           class="btn btn-success btn-block">
                            <i class="fas fa-search mr-2"></i>Explorar
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php
            if (true) { // Todos los roles tienen acceso
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card module-card">
                    <div class="card-body text-center">
                        <div class="module-icon text-info">
                            <i class="fas fa-external-link-square-alt"></i>
                        </div>
                        <h5 class="card-title">Recursos Externos</h5>
                        <p class="card-text">Enlaces a bibliotecas y recursos gratuitos</p>
                        <a href="/repositorio-externo/" 
                           class="btn btn-info btn-block">
                            <i class="fas fa-link mr-2"></i>Acceder
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php
            $allowedRolesForSala = ['admin', 'owner', 'bibliotecario', 'tutor'];
            if (in_array($userRole, $allowedRolesForSala)) {
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card module-card">
                    <div class="card-body text-center">
                        <div class="module-icon text-warning">
                            <i class="fas fa-readme"></i>
                        </div>
                        <h5 class="card-title">Sala de Lectura</h5>
                        <p class="card-text">Espacio interactivo para lectura y estudio</p>
                        <a href="/sala-lectura/" 
                           class="btn btn-warning btn-block">
                            <i class="fas fa-door-open mr-2"></i>Entrar
                        </a>
                    </div>
                </div>
            </div>
            <?php } else { ?>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card module-card module-disabled">
                    <div class="card-body text-center">
                        <div class="module-icon text-muted">
                            <i class="fas fa-readme"></i>
                        </div>
                        <h5 class="card-title text-muted">Sala de Lectura</h5>
                        <p class="card-text text-muted">Acceso restringido</p>
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-lock mr-2"></i>No disponible
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle mr-2"></i>Información importante
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-clock mr-2"></i>Horario de atención</h6>
                                <p class="text-muted">Lunes a Viernes: 8:00 AM - 8:00 PM<br>
                                Sábados: 9:00 AM - 1:00 PM</p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-phone mr-2"></i>Contacto</h6>
                                <p class="text-muted">Teléfono: (054) 123456<br>
                                Email: biblioteca@cedhinuevaarequipa.edu.pe</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>