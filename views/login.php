<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca CEDHI</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 2rem;
            border-bottom: none;
        }
        .logo-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        .card-body {
            padding: 2rem;
        }
        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }
        .welcome-text h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .welcome-text p {
            color: #7f8c8d;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .btn-google {
            background: #ffffff;
            color: #757575;
            border: 2px solid #f1f1f1;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            font-size: 1rem;
        }
        .btn-google:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: #424242;
            border-color: #e0e0e0;
        }
        .btn-google:active {
            transform: translateY(0);
        }
        .google-icon {
            width: 20px;
            height: 20px;
            background: conic-gradient(from -45deg, #ea4335 110deg, #4285f4 90deg 180deg, #34a853 180deg 270deg, #fbbc05 270deg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 16px;
        }
        .institution-info {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #ecf0f1;
        }
        .institution-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }
        .institution-sub {
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        .alert-danger {
            border-radius: 12px;
            border: none;
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .alert-danger i {
            margin-right: 0.5rem;
        }
        .security-note {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #95a5a6;
        }
        .security-note i {
            margin-right: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Encabezado con logo -->
            <div class="card-header">
                <i class="fas fa-book-reader logo-icon"></i>
                <h3 class="mb-0">Biblioteca CEDHI</h3>
            </div>

            <div class="card-body">
                <!-- Mensajes de error -->
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['login_error']; ?>
                        <?php unset($_SESSION['login_error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Texto de bienvenida -->
                <div class="welcome-text">
                    <h4>Bienvenido al Sistema</h4>
                    <p>Inicia sesión con tu cuenta institucional para acceder a todos los recursos</p>
                </div>

                <!-- Botón de Google mejorado -->
                <a href="<?php echo $google_client->createAuthUrl(); ?>" class="btn btn-google">
                    <div class="google-icon">G</div>
                    <span>Iniciar sesión con Google</span>
                </a>

                <!-- Información de seguridad -->
                <div class="security-note">
                    <i class="fas fa-lock"></i>Sistema seguro - Solo cuentas institucionales
                </div>

                <!-- Información de la institución -->
                <div class="institution-info">
                    <div class="institution-name">
                        Instituto de Educación Superior Tecnológico Privado
                    </div>
                    <div class="institution-sub">
                        Nueva Arequipa
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Efectos de hover mejorados
        document.addEventListener('DOMContentLoaded', function() {
            const googleBtn = document.querySelector('.btn-google');
            
            googleBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
            });
            
            googleBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
            });
        });
    </script>
</body>
</html>