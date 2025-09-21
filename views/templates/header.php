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
    <title>Dashboard Admin - Biblioteca CEDHI</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <i class="fas fa-book-reader mr-2"></i>Biblioteca CEDHI
        </a>
        
        <div class="navbar-nav ml-auto">
            <div class="d-flex align-items-center">
                <img src="<?php echo $_SESSION['user_image'] ?? 'https://via.placeholder.com/40'; ?>" 
                     class="user-avatar mr-2" 
                     alt="Avatar">
                <div class="text-white">
                    <small>Hola,</small>
                    <div><?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?></div>
                    <small class="text-muted">(<?php echo ucfirst($_SESSION['role']); ?>)</small>
                </div>
                <a class="btn btn-outline-light btn-sm ml-3" href="../../logout.php">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="px-4 py-4">
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>