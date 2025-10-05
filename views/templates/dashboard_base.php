<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/paths.php';
require_once __DIR__ . '/../../app/tokenController.php';
//error_log("DEBUG - Antes de generar token: " . print_r($_SESSION, true));
$tokenPlanes = generateToken();
$tokenSala = generateToken();
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
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .welcome-header {
            background-color: #2C3E50;
            color: white;
            border-radius: 15px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .module-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cedhi: {
                            primary: "#2C3E50",
                            secondary: "#34495E",
                            accent: "#1ABC9C",
                            light: "#ECF0F1",
                            success: "#27AE60",
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-cedhi-light min-h-screen">
    <?php include __DIR__ . '/header.php'; ?>
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-12">

        <div class="welcome-header mb-8 rounded-xl shadow-xl">
            <h1 class="text-3xl font-extrabold mb-1">
                ¡Hola,
                <?php echo htmlspecialchars($_SESSION['user_first_name']); ?>!
            </h1>
            <p class="text-xl font-light opacity-90">
                Selecciona un módulo para acceder a los recursos de la Biblioteca.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Biblioteca Virtual -->
            <div class="module-card bg-white rounded-xl p-8 flex flex-col items-center text-center border-b-4 border-cedhi-accent">
                <div class="h-16 w-16 flex items-center justify-center rounded-full bg-cedhi-light text-cedhi-accent mb-4 text-3xl shadow-md">
                    <i class="fa-solid fa-book"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Biblioteca Virtual</h2>
                <p class="text-base text-gray-500 mb-6">
                    Accede a libros digitales y recursos en línea.
                </p>
                <a href="https://elibro.net/es/lc/cedhinuevaarequipa/login_usuario/"
                    class="w-full py-3 px-4 rounded-lg font-semibold text-white bg-cedhi-primary hover:bg-cedhi-secondary transition shadow-lg hover:shadow-xl">
                    <i class="fa-solid fa-book-open-reader mr-2"></i> Entrar
                </a>
            </div>

            <!-- ✅ Planes de Negocio -->
            <div class="module-card bg-white rounded-xl p-8 flex flex-col items-center text-center border-b-4 border-cedhi-accent">
                <div class="h-16 w-16 flex items-center justify-center rounded-full bg-cedhi-light text-cedhi-accent mb-4 text-3xl shadow-md">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Planes de Negocio</h2>
                <p class="text-base text-gray-500 mb-6">
                    Consulta repositorios de planes y proyectos de la institución.
                </p>
                <button id="IrPlanesNegocio"
                    class="w-full py-3 px-4 rounded-lg font-semibold text-white bg-cedhi-primary hover:bg-cedhi-secondary transition shadow-lg hover:shadow-xl">
                    <i class="fa-solid fa-folder-open mr-2"></i> Entrar
                </button>
            </div>

            <!-- Repositorios Externos -->
            <div class="module-card bg-white rounded-xl p-8 flex flex-col items-center text-center border-b-4 border-cedhi-accent">
                <div class="h-16 w-16 flex items-center justify-center rounded-full bg-cedhi-light text-cedhi-accent mb-4 text-3xl shadow-md">
                    <i class="fa-solid fa-link"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Repositorios Externos</h2>
                <p class="text-base text-gray-500 mb-6">
                    Accede a enlaces directos a recursos gratuitos.
                </p>
                <a href="<?php echo url('pages/repositorios.php') ?>"
                    class="w-full py-3 px-4 rounded-lg font-semibold text-white bg-cedhi-primary hover:bg-cedhi-secondary transition shadow-lg hover:shadow-xl">
                    <i class="fa-solid fa-external-link-alt mr-2"></i> Entrar
                </a>
            </div>

            <!-- Sala de Lectura (solo roles permitidos) -->
            <?php
            $allowedRolesForSala = ['admin', 'owner', 'bibliotecario', 'tutor'];
            if (in_array($_SESSION['role'], $allowedRolesForSala)) {
            ?>
                <div class="module-card bg-white rounded-xl p-8 flex flex-col items-center text-center border-b-4 border-cedhi-success">
                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-cedhi-light text-cedhi-success mb-4 text-3xl shadow-md">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Sala de Lectura</h2>
                    <p class="text-base text-gray-500 mb-6">
                        Gestión y control de préstamos de libros físicos.
                    </p>
                    <button id="IrSalaLectura"
                        class="w-full py-3 px-4 rounded-lg font-semibold text-white bg-cedhi-success hover:bg-green-700 transition shadow-lg hover:shadow-xl">
                        <i class="fa-solid fa-clipboard-list mr-2"></i> Entrar
                    </button>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

<script>
    const tokenPlanes = '<?php echo $tokenPlanes; ?>';
    const tokenSala = '<?php echo $tokenSala; ?>';

    document.getElementById('IrPlanesNegocio').addEventListener('click', () => {
        window.location.href = 'http://localhost/PlanesTrabajo/index.php?token=' + tokenPlanes;
    });

    document.getElementById('IrSalaLectura').addEventListener('click', () => {
        window.location.href = 'http://localhost:3010/sistema-biblioteca/token-login?token=' + tokenSala;
    });
</script>

</html>
