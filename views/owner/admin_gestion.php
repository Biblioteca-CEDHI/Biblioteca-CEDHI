<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['owner']);
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header('Location: dashboard.php');
    exit;
}

function url($path)
{
    global $base_url;
    return $base_url . '/' . $path;
}

try {
    require_once __DIR__ . '/../../app/database.php';
} catch (Exception $e) {
    error_log("Error al incluir la BD: " . $e->getMessage());
    $pdo = null;
}

$message = '';
$message_type = '';
$modulos_asignables = [];
$module_map_name_to_id = [];

if (isset($pdo)) {
    try {
        $stmt_mods = $pdo->prepare("
            SELECT id, module_name 
            FROM modules 
            WHERE module_name IN ('Sala de Lectura', 'Repositorio de Planes de Negocios')
            ORDER BY id ASC
        ");
        $stmt_mods->execute();
        $modules = $stmt_mods->fetchAll(PDO::FETCH_ASSOC);

        foreach ($modules as $mod) {
            $modulos_asignables[$mod['module_name']] = $mod['module_name'];
            $module_map_name_to_id[$mod['module_name']] = $mod['id'];
        }
    } catch (PDOException $e) {
        $message = "Error al cargar los módulos: " . $e->getMessage();
        $message_type = 'danger';
    }
}

function get_badge_classes($modulo)
{
    return match ($modulo) {
        'Owner' => 'bg-cedhi-primary text-white',
        'Planes de Negocio' => 'bg-cedhi-accent text-white',
        'Sala de Lectura' => 'bg-yellow-100 text-yellow-800',
        default => 'bg-gray-100 text-gray-800'
    };
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($pdo)) {
        $message = "No se puede procesar. La conexión a la BD no está disponible.";
        $message_type = 'danger';
    } elseif ($_POST['action'] === 'delete_admin') {
        $user_id_to_delete = $_POST['user_id_to_delete'] ?? null;
        if ($user_id_to_delete) {
            try {
                $stmt_delete = $pdo->prepare("DELETE FROM module_admins WHERE user_id = :user_id AND module_id = :module_id");
                $stmt_delete->execute([':user_id' => $user_id_to_delete, ':module_id' => $_POST['module_id_to_delete']]);

                $message = "Administrador eliminado correctamente.";
                $message_type = "success";
            } catch (PDOException $e) {
                $message = "Error al eliminar administrador: " . $e->getMessage();
                $message_type = "danger";
            }
        } else {
            $message = "ID de usuario inválido.";
            $message_type = "danger";
        }
    } elseif ($_POST['action'] === 'assign_admin') {
        $usuario_id = $_POST['usuario'] ?? null;
        $modulo = $_POST['modulo'] ?? null;

        if (!$usuario_id || !$modulo) {
            $message = "Debes seleccionar un usuario y un módulo.";
            $message_type = 'danger';
        } elseif (!array_key_exists($modulo, $modulos_asignables)) {
            $message = "Módulo asignado inválido.";
            $message_type = 'danger';
        } else {
            try {
                $pdo->beginTransaction();
                $module_id = $module_map_name_to_id[$modulo];

                $check = $pdo->prepare("SELECT 1 FROM module_admins WHERE user_id = :user_id AND module_id = :module_id");
                $check->execute([':user_id' => $usuario_id, ':module_id' => $module_id]);

                if ($check->rowCount() > 0) {
                    $message = "El usuario ya es administrador de este módulo.";
                    $message_type = "warning";
                } else {
                    $stmt_admin = $pdo->prepare("INSERT INTO module_admins (user_id, module_id) VALUES (:user_id, :module_id)");
                    $stmt_admin->execute([':user_id' => $usuario_id, ':module_id' => $module_id]);

                    $update_role = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = :id");
                    $update_role->execute([':id' => $usuario_id]);

                    $pdo->commit();
                    $message = "Administrador asignado correctamente.";
                    $message_type = "success";
                }
            } catch (PDOException $e) {
                $pdo->rollBack();
                $message = "Error al asignar administrador: " . $e->getMessage();
                $message_type = "danger";
            }
        }
    }
}

$usuarios_registrados = [];
if (isset($pdo)) {
    try {
        $sql_fetch = "
            SELECT u.id AS user_id, u.first_name AS nombre, u.last_name AS apellido, u.email AS correo, m.module_name AS modulo
            FROM users u
            JOIN module_admins ma ON u.id = ma.user_id
            JOIN modules m ON ma.module_id = m.id
            ORDER BY u.id ASC
        ";
        $stmt_fetch = $pdo->query($sql_fetch);
        $usuarios_registrados = $stmt_fetch->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = "Error al cargar administradores: " . $e->getMessage();
        $message_type = 'danger';
        error_log("Error al cargar lista: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Administradores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
                        danger: "#E74C3C"
                    }
                }
            }
        }
    };

    function openUserModal() {
        document.getElementById('userModal').classList.replace('hidden', 'flex');
        searchUsers('');
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.replace('flex', 'hidden');
    }

    function searchUsers(query) {
        fetch("buscar_usuario.php?q=" + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                let html = data.length === 0 ?
                    "<p class='text-gray-400 text-sm italic text-center py-4'>No se encontraron usuarios.</p>" :
                    data.map(u => `<div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectUser(${u.id},'${u.nombre}')">
                <p class="font-medium text-gray-800">${u.nombre}</p>
                <p class="text-sm text-gray-500">${u.email}</p>
            </div>`).join('');
                document.getElementById('searchResults').innerHTML = html;
            });
    }

    function selectUser(id, nombre) {
        document.getElementById('usuario').value = id;
        document.getElementById('usuario_nombre').value = nombre;
        closeUserModal();
    }

    function confirmDelete(userId, userName) {
        document.getElementById('adminName').textContent = userName;
        document.getElementById('userIdToDelete').value = userId;
        document.getElementById('deleteModal').classList.replace('hidden', 'flex');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.replace('flex', 'hidden');
    }
    document.getElementById('deleteModal')?.addEventListener('click', e => {
        if (e.target === e.currentTarget) closeModal();
    });
    </script>
    <style>
    body {
        background-color: #ECF0F1;
    }

    .input-focus:focus {
        border-color: #1ABC9C;
        box-shadow: 0 0 0 2px rgba(26, 188, 156, 0.5);
    }
    </style>
</head>

<body class="bg-cedhi-light min-h-screen">

    <header
        class="flex justify-between items-center p-4 sm:px-8 bg-cedhi-primary text-white shadow-xl sticky top-0 z-10">
        <div class="flex items-center space-x-2 sm:space-x-4">
            <a href="dashboard.php" class="text-cedhi-accent hover:text-white transition duration-200">
                <i class="fas fa-arrow-left text-xl mr-2"></i>
            </a>
            <h1 class="text-lg sm:text-2xl font-extrabold tracking-wide">
                Gestión de <span class="text-cedhi-accent">Administradores</span>
            </h1>
        </div>
        <div class="flex items-center">
            <span class="text-sm font-light mr-4 hidden sm:block">
                <?php echo htmlspecialchars($_SESSION['user_first_name']); ?> (Owner)
            </span>
            <button
                class="flex items-center space-x-1 bg-cedhi-secondary text-white py-2 px-3 rounded-lg hover:bg-cedhi-accent transition-colors"
                onclick="window.location.href='../../logout.php'">
                <i class="fa-solid fa-right-from-bracket text-sm"></i>
            </button>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-12">

        <?php if (!empty($message)): ?>
        <div class="p-4 rounded-xl shadow-md mb-6 
                <?php echo ($message_type === 'success' ? 'bg-cedhi-success/10 text-cedhi-success border border-cedhi-success' : 'bg-cedhi-danger/10 text-cedhi-danger border border-cedhi-danger'); ?> 
                flex items-center">
            <i
                class="fas <?php echo ($message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'); ?> mr-3"></i>
            <span class="font-medium"><?php echo htmlspecialchars($message); ?></span>
        </div>
        <?php endif; ?>

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg mb-10 border-t-4 border-cedhi-primary">
            <h2 class="text-2xl font-bold text-cedhi-primary mb-6 flex items-center">
                <i class="fas fa-user-plus text-cedhi-accent mr-3"></i>
                Registrar Administrador de Módulo
            </h2>

            <form action="admin_gestion.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario Seleccionado</label>
                    <div class="flex items-center gap-2">
                        <input type="hidden" id="usuario" name="usuario"> <!-- aquí se guarda el id -->
                        <input type="text" id="usuario_nombre" class="w-full px-4 py-2 border rounded-lg bg-gray-100"
                            placeholder="Ningún usuario seleccionado" readonly>
                        <button type="button" onclick="openUserModal()"
                            class="px-3 py-2 bg-cedhi-accent text-white rounded-lg hover:bg-cedhi-primary transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="modulo" class="block text-sm font-medium text-gray-700">Asignar Módulo a
                        Administrar</label>
                    <select id="modulo" name="modulo" class="w-full px-4 py-2 border rounded-lg bg-white" required>
                        <?php foreach ($modulos_asignables as $value => $label): ?>
                        <option value="<?php echo htmlspecialchars($value); ?>">
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <button type="submit" name="action" value="assign_admin"
                    class="w-full py-3 mt-4 rounded-lg font-semibold text-white bg-cedhi-accent hover:bg-cedhi-primary transition shadow-md flex justify-center items-center gap-2">
                    <i class="fas fa-user-check"></i> Asignar Administrador
                </button>
            </form>

        </div>
        <!-- Modal -->
        <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
                <h3 class="text-lg font-bold mb-4">Buscar Usuario</h3>
                <input type="text" id="searchInput" placeholder="Escribe un nombre o correo..."
                    class="w-full px-4 py-2 border rounded-lg mb-4" onkeyup="searchUsers(this.value)">

                <div id="searchResults" class="max-h-60 overflow-y-auto divide-y">
                    <p class="text-gray-400 text-sm italic text-center py-4">Empieza a escribir para buscar...</p>
                </div>

                <div class="flex justify-end mt-4">
                    <button onclick="closeUserModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cerrar</button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border-t-4 border-cedhi-accent">
            <h2 class="text-2xl font-bold text-cedhi-primary mb-6 flex items-center">
                <i class="fas fa-table text-cedhi-accent mr-3"></i>
                Administradores por Módulo
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-cedhi-light">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-cedhi-primary uppercase tracking-wider rounded-tl-lg">
                                Nombre Completo</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-cedhi-primary uppercase tracking-wider">
                                Correo</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-cedhi-primary uppercase tracking-wider">
                                Módulo Asignado</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-cedhi-primary uppercase tracking-wider rounded-tr-lg">
                                Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($usuarios_registrados)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No hay administradores
                                registrados.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($usuarios_registrados as $usuario): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo htmlspecialchars($usuario['correo']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo get_badge_classes($usuario['modulo']); ?>">
                                    <?php echo htmlspecialchars($usuario['modulo']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <?php if ($usuario['modulo'] !== 'Owner'): ?>
                                <button
                                    class="text-white bg-cedhi-danger hover:bg-red-700 py-1 px-3 rounded-md shadow-sm transition flex items-center justify-center mx-auto"
                                    onclick="confirmDelete(<?php echo $usuario['user_id']; ?>, '<?php echo htmlspecialchars($usuario['nombre']); ?>', <?php echo $module_map_name_to_id[$usuario['modulo']]; ?>)">
                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                </button>
                                <?php else: ?>
                                <span class="text-gray-400 font-light text-xs">No permitido</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="deleteModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden items-center justify-center p-4 z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm">
            <h3 class="text-xl font-bold text-cedhi-primary mb-3 flex items-center">
                <i class="fas fa-exclamation-triangle text-cedhi-danger mr-2"></i> Confirmar Eliminación
            </h3>
            <p class="text-gray-600 mb-4">¿Estás seguro de que deseas eliminar al administrador <span id="adminName"
                    class="font-semibold text-cedhi-danger"></span> del módulo asignado? Esta acción es irreversible.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancelar</button>
                <form id="deleteForm" action="admin_gestion.php" method="POST" class="inline">
                    <input type="hidden" name="action" value="delete_admin">
                    <input type="hidden" name="user_id_to_delete" id="userIdToDelete">
                    <input type="hidden" name="module_id_to_delete" id="moduleIdToDelete">

                    <button type="submit"
                        class="px-4 py-2 bg-cedhi-danger text-white rounded-lg hover:bg-red-700 transition flex items-center">
                        <i class="fas fa-trash-alt mr-1"></i> Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function togglePasswordVisibility(id, iconElement) {
        const input = document.getElementById(id);
        const icon = iconElement.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    let currentUserId = null;

    function confirmDelete(userId, userName, moduleId) {
        currentUserId = userId;
        document.getElementById('adminName').textContent = userName;
        document.getElementById('userIdToDelete').value = userId;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
        document.getElementById('moduleIdToDelete').value = moduleId;
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    </script>

</body>

</html>