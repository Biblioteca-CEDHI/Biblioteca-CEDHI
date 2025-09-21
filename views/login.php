<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Biblioteca CEDHI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen w-screen bg-cover bg-center flex items-center justify-center relative" style="
      background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1600&q=80');
    ">
    <!-- Capa borrosa encima del fondo -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm z-0"></div>

    <div class="relative z-10 bg-white bg-opacity-90 shadow-xl rounded-2xl p-10 w-full max-w-md">
        <!-- Mensajes de error -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['login_error']; ?>
                <?php unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <!-- Título -->
        <h1 class="text-3xl font-bold text-center text-blue-900 mb-5">
            BIBLIOTECA CEDHI
        </h1>
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="img/logo_cedhi.png" alt="Logo" class="h-32" />
        </div>

        <!-- Subtítulo -->
        <p class="text-center text-gray-700 mb-7">
            Accede a la Biblioteca Virtual CEDHI para explorar nuestros recursos
            digitales
        </p>

        <div class="flex items-center my-6">
            <hr class="flex-grow border-gray-300" />
            <span class="px-2 text-gray-400 text-sm">Accede con</span>
            <hr class="flex-grow border-gray-300" />
        </div>
        <!-- Botón Google -->
        <a href="<?php echo $google_client->createAuthUrl(); ?>">
            <button
                class="w-full flex items-center justify-center gap-2 border border-gray-300 py-3 rounded-lg shadow-md hover:bg-white transition font-medium">
                <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="h-5 w-5 mr-2" />
                Iniciar sesión con Google
            </button>
        </a>


        <p class="text-center text-xs text-gray-500 mt-6">
            Solo se permite el acceso con cuentas institucionales.<br />
            Si eres administrador, ingresa con tu correo designado.
        </p>
    </div>
    </div>
    </div>

    <script>
        // Efectos de hover mejorados
        document.addEventListener('DOMContentLoaded', function () {
            const googleBtn = document.querySelector('.btn-google');

            googleBtn.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
            });

            googleBtn.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
            });
        });
    </script>
</body>

</html>