<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CEDHI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
    <main class="min-h-screen bg-gray-100 p-6 flex items-center justify-center">
        <div class="flex flex-wrap justify-center gap-12 p-4 sm:p-8">
            <?php
            $userRole = $_SESSION['role'];
            
            if (true) { // Todos los roles tienen acceso
            ?>
            <div
                class="bg-white p-8 rounded-2xl shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-2xl min-w-[280px]">
                <div class="flex items-center justify-center h-20 w-20 bg-blue-400 rounded-full mb-6 mx-auto">
                    <i class="fa-solid fa-globe text-white text-4xl"></i>
                </div>
                <h2 class="text-gray-800 text-2xl font-bold text-center mb-3">Biblioteca Virtual</h2>
                <p class="text-gray-600 text-base text-center mb-4">Accede a los libros digitales</p>
                <a href="https://elibro.net/es/lc/cedhinuevaarequipa/login_usuario/"
                    class="flex items-center justify-center space-x-2 bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors duration-200">
                    <i class="fa-solid fa-door-open text-lg"></i>
                    <span>Entrar</span>
                </a>
            </div>

            <?php } ?>

            <?php
            if (true) { // Todos los roles tienen acceso
            ?>
            <div
                class="bg-white p-8 rounded-2xl shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-2xl min-w-[280px]">
                <div class="flex items-center justify-center h-20 w-20 bg-yellow-400 rounded-full mb-6 mx-auto">
                    <i class="fa-solid fa-chart-line text-white text-4xl"></i>
                </div>
                <h2 class="text-gray-800 text-2xl font-bold text-center mb-3">Planes de Negocio</h2>
                <p class="text-gray-600 text-base text-center mb-4">Consulta planes y proyectos</p>
                <a href="https://repositorio-planes.cedhinuevaarequipa.edu.pe/"
                    class="flex items-center justify-center space-x-2 bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition-colors duration-200">
                    <i class="fa-solid fa-door-open text-lg"></i>
                    <span>Entrar</span>
                </a>
            </div>
            <?php } ?>

            <?php
            if (true) { // Todos los roles tienen acceso
            ?>
            <div
                class="bg-white p-8 rounded-2xl shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-2xl min-w-[280px]">
                <div class="flex items-center justify-center h-20 w-20 bg-[#57bbc9] rounded-full mb-6 mx-auto">
                    <i class="fa-solid fa-link text-white text-4xl"></i>
                </div>
                <h2 class="text-gray-800 text-2xl font-bold text-center mb-3">Repositorios Externos</h2>
                <p class="text-gray-600 text-base text-center mb-4">Enlaces a recursos gratuitos</p>
                <a href="/estudiante/dashboard.php"
                    class="flex items-center justify-center space-x-2 bg-[#34a2b8] text-white font-semibold py-3 px-6 rounded-lg hover:bg-[#57bbc9] transition-colors duration-200">
                    <i class="fa-solid fa-door-open text-lg"></i>
                    <span>Entrar</span>
                </a>
            </div>
            <?php } ?>

            <?php
            $allowedRolesForSala = ['admin', 'owner', 'bibliotecario', 'tutor'];
            if (in_array($userRole, $allowedRolesForSala)) {
            ?>
            <div
                class="bg-white p-8 rounded-2xl shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-2xl min-w-[280px]">
                <div class="flex items-center justify-center h-20 w-20 bg-green-500 rounded-full mb-6 mx-auto">
                    <i class="fa-solid fa-book-open text-white text-4xl"></i>
                </div>
                <h2 class="text-gray-800 text-2xl font-bold text-center mb-3">Sala de Lectura</h2>
                <p class="text-gray-600 text-base text-center mb-4">Gestión de préstamos de libros</p>
                <a href="/bibliotecario/dashboard.php"
                    class="flex items-center justify-center space-x-2 bg-green-700 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-600 transition-colors duration-200">
                    <i class="fa-solid fa-door-open text-lg"></i>
                    <span>Entrar</span>
                </a>
            </div>
            <?php }?>
        </div>
    </main>
    <footer class="bg-blue-950 text-white">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div class="text-center sm:text-left mb-4 sm:mb-0">
                    <h3 class="font-semibold text-lg">Biblioteca CEDHI</h3>
                    <p class="text-sm text-blue-200">Teléfono: (054) 123456</p>
                    <p class="text-sm text-blue-200">Email: biblioteca@cedhinuevaarequipa.edu.pe</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-blue-300 transition-colors duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="hover:text-blue-300 transition-colors duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="hover:text-blue-300 transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            <div class="mt-4 text-center text-sm text-blue-200">
                &copy; 2025 Biblioteca CEDHI. Todos los derechos reservados.
            </div>
        </div>
    </footer>
</body>

</html>