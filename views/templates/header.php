<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = '/Biblioteca-CEDHI';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CEDHI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    </style>
</head>

<body>
    <header class="flex justify-between items-center p-4 sm:px-8 bg-blue-950 text-white shadow-lg">
        <div class="flex items-center space-x-2 sm:space-x-4">
            <img src="<?php echo $base_url; ?>/img/logo_cedhi_recortado.png" alt="logo"
                class="h-12 sm:h-14 w-auto drop-shadow-sm" />
            <h1 class="text-lg sm:text-2xl font-extrabold tracking-wide">
                Biblioteca <span class="text-blue-300">CEDHI</span>
            </h1>
        </div>
        <div class="flex items-center space-x-2 sm:space-x-4">
            <img src="<?php echo $_SESSION['user_image'] ?? $base_url . '/img/default-avatar.png'; ?>"
                class="h-10 w-10 rounded-full object-cover" alt="Avatar">

            <div class="text-right hidden sm:block">
                <span class="block text-sm text-blue-200">Hola,</span>
                <span class="block font-semibold text-lg">
                    <?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?>
                </span>
                <span class="block text-sm text-blue-200">
                    (<?php echo ucfirst($_SESSION['role']); ?>)
                </span>
            </div>
            <button
                class="flex items-center space-x-1 sm:space-x-2 bg-blue-700 border-blue-600 text-white py-2 px-3 sm:py-2 sm:px-5 rounded-lg hover:bg-blue-600 hover:border-blue-500 transition-colors duration-200 outline-none"
                onclick="window.location.href='../../logout.php'">
                <i class="fa-solid fa-right-from-bracket text-sm sm:text-base"></i>
                <span class="hidden sm:block">Cerrar sesión</span>
            </button>
        </div>
    </header>
</body>

</html>