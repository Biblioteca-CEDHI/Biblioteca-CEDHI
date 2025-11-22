<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../app/middleware.php';
requireRole(['owner']);

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

$usuarios_registrados = [];
if (isset($pdo)) {
    try {
        $sql_fetch = "
            SELECT 
            u.id AS user_id, 
            u.first_name AS nombre, 
            u.last_name AS apellido, 
            u.email AS correo,
            GROUP_CONCAT(DISTINCT COALESCE(m.module_name, 'Sin módulo asignado') SEPARATOR ', ') AS modulos
        FROM users u
        LEFT JOIN module_admins ma ON u.id = ma.user_id
        LEFT JOIN modules m ON ma.module_id = m.id
        WHERE u.role = 'admin'
        GROUP BY u.id
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_modulos') {
    $user_id = intval($_POST['user_id']);
    $modulos = isset($_POST['module_ids']) ? explode(',', $_POST['module_ids']) : [];

    try {
        $stmt = $pdo->prepare("DELETE FROM module_admins WHERE user_id = ?");
        $stmt->execute([$user_id]);

        if (!empty($modulos)) {
            $stmt = $pdo->prepare("INSERT INTO module_admins (user_id, module_id) VALUES (?, ?)");
            foreach ($modulos as $mod_id) {
                if (is_numeric($mod_id) && $mod_id > 0) {
                    $stmt->execute([$user_id, intval($mod_id)]);
                }
            }
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar_estado') {
    $user_id = intval($_POST['user_id']);
    $nuevo_estado = $_POST['estado'] === 'activo' ? 'activo' : 'inactivo';
    try {
        $stmt = $pdo->prepare("UPDATE users SET estado = ? WHERE id = ?");
        $stmt->execute([$nuevo_estado, $user_id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
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
    </script>
    <style>
    body {
        background-color: #ECF0F1;
    }

    .input-focus:focus {
        border-color: #1ABC9C;
        box-shadow: 0 0 0 2px rgba(26, 188, 156, 0.5);
    }

    input[type="checkbox"].form-checkbox {
        appearance: none;
        -webkit-appearance: none;
        background-color: #fff;
        border: 2px solid #1ABC9C;
        width: 18px;
        height: 18px;
        border-radius: 0.25rem;
        display: inline-block;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        vertical-align: middle;
    }

    input[type="checkbox"].form-checkbox:checked {
        background-color: #1ABC9C;
        border-color: #1ABC9C;
    }

    input[type="checkbox"].form-checkbox:checked::after {
        content: "";
        position: absolute;
        top: 2px;
        left: 5px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    </style>
</head>

<body class="bg-cedhi-light min-h-screen">

    <header
        class="flex justify-between items-center p-4 sm:px-8 bg-cedhi-primary text-white shadow-xl sticky top-0 z-10">
        <div class="flex items-center space-x-2 sm:space-x-4">
            <a href="../dashboard.php" class="text-cedhi-accent hover:text-white transition duration-200">
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

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-12">

        <?php if (!empty($message)): ?>
        <div class="p-4 rounded-xl shadow-md mb-6 
                <?php echo ($message_type === 'success' ? 'bg-cedhi-success/10 text-cedhi-success border border-cedhi-success' : 'bg-cedhi-danger/10 text-cedhi-danger border border-cedhi-danger'); ?> 
                flex items-center">
            <i
                class="fas <?php echo ($message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'); ?> mr-3"></i>
            <span class="font-medium"><?php echo htmlspecialchars($message); ?></span>
        </div>
        <?php endif; ?>


        <!-- Modal -->
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
                                class="px-6 py-3 text-left text-xs font-semibold text-cedhi-primary uppercase tracking-wider rounded-tr-lg">
                                Estado</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex flex-col space-y-1">
                                    <?php
                                    $stmt_mods = $pdo->prepare("SELECT module_id FROM module_admins WHERE user_id = ?");
                                    $stmt_mods->execute([$usuario['user_id']]);
                                    $modulos_asignados = $stmt_mods->fetchAll(PDO::FETCH_COLUMN);
                                    ?>

                                    <?php foreach ($modules as $mod): ?>
                                    <label class="inline-flex items-center space-x-2">
                                        <input type="checkbox"
                                            class="form-checkbox h-4 w-4 text-cedhi-accent focus:ring-cedhi-accent cursor-pointer"
                                            value="<?php echo $mod['id']; ?>"
                                            <?php echo in_array($mod['id'], $modulos_asignados) ? 'checked' : ''; ?>
                                            onchange="actualizarCheckboxModulos(<?php echo $usuario['user_id']; ?>)">
                                        <span
                                            class="text-sm text-gray-800"><?php echo htmlspecialchars($mod['module_name']); ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php
                                    $stmt_estado = $pdo->prepare("SELECT estado FROM users WHERE id = ?");
                                    $stmt_estado->execute([$usuario['user_id']]);
                                    $estado_actual = $stmt_estado->fetchColumn() ?: 'inactivo';
                                ?>
                                <select
                                    onchange="actualizarEstadoUsuario(<?php echo $usuario['user_id']; ?>, this.value)"
                                    class="px-3 py-1 rounded-lg text-sm font-medium border border-gray-300 
                                        focus:ring-2 focus:ring-cedhi-accent focus:outline-none cursor-pointer transition
                                        <?php echo $estado_actual === 'activo' 
                                            ? 'bg-cedhi-accent/10 text-cedhi-accent border-cedhi-accent/30' 
                                            : 'bg-cedhi-light text-gray-700 border-gray-300'; ?>">
                                    <option value="activo" class="text-cedhi-accent"
                                        <?php echo $estado_actual === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                    <option value="inactivo" class="text-gray-700"
                                        <?php echo $estado_actual === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
    function actualizarCheckboxModulos(userId) {
        const row = event.target.closest('tr');
        const checkboxes = row.querySelectorAll('input[type="checkbox"]');
        const seleccionados = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `accion=actualizar_modulos&user_id=${encodeURIComponent(userId)}&module_ids=${encodeURIComponent(seleccionados.join(','))}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Módulos actualizados correctamente', 'success');
                } else {
                    mostrarToast('Error al actualizar: ' + (data.error || 'Error desconocido'), 'error');
                }
            })
            .catch(err => {
                mostrarToast('Error de conexión: ' + err.message, 'error');
            });
    }

    function mostrarToast(mensaje, tipo) {
        const colores = tipo === 'success' ?
            'bg-green-500 text-white' :
            'bg-red-500 text-white';
        const toast = document.createElement('div');
        toast.className =
            `${colores} fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg transition-all duration-500 opacity-0`;
        toast.textContent = mensaje;
        document.body.appendChild(toast);
        setTimeout(() => toast.style.opacity = 1, 50);
        setTimeout(() => {
            toast.style.opacity = 0;
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    function actualizarEstadoUsuario(userId, nuevoEstado) {
        fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `accion=actualizar_estado&user_id=${encodeURIComponent(userId)}&estado=${encodeURIComponent(nuevoEstado)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Estado actualizado correctamente', 'success');
                } else {
                    mostrarToast('Error al actualizar estado: ' + (data.error || 'Error desconocido'), 'error');
                }
            })
            .catch(err => {
                mostrarToast('Error de conexión: ' + err.message, 'error');
            });
    }
    </script>
</body>

</html>