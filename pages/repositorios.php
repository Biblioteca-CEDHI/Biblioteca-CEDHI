<?php
require_once __DIR__ . '/../app/paths.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Repositorios Externos</title>
    <link rel="icon" href="<?php echo url('img/logoprinci.jpg') ?>" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: #2c3e50 !important;
            padding: 15px 30px;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            max-height: 120px;
            object-fit: contain;
            padding: 15px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .btn-repo {
            background: #6a1b9a;
            color: white;
            border-radius: 30px;
            padding: 8px 18px;
            transition: background 0.3s ease;
        }

        .btn-repo:hover {
            background: #9c27b0;
            color: white;
        }

        footer {
            margin-top: 50px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="<?php echo url('img/logotipo3.png') ?>" alt="CEDHI"> 
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="repositorios.php">Repositorios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Repositorios Externos Gratuitos</h2>
        <div class="row g-4">
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    <img src="https://dialnet.unirioja.es/imagen/dialnet_mg.png" class="card-img-top" alt="Dialnet">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Dialnet</h5>
                        <p class="card-text">Repositorio que tiene como objetivo dar mayor visibilidad a la literatura científica hispana en Internet, recopilando y facilitando el acceso a contenidos científicos.</p>
                        <div class="mt-auto">
                            <a href="https://dialnet.unirioja.es/" target="_blank" class="btn btn-repo">
                                <i class="fas fa-external-link-alt"></i> Visitar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    <img src="https://enfocatss.com/wp-content/uploads/2021/01/logo_scielo-1.jpg" class="card-img-top" alt="SciELO">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">SciELO</h5>
                        <p class="card-text">Biblioteca electrónica que incluye una colección seleccionada de revistas científicas en acceso abierto de Iberoamérica.</p>
                        <div class="mt-auto">
                            <a href="https://scielo.org/" target="_blank" class="btn btn-repo">
                                <i class="fas fa-external-link-alt"></i> Visitar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    <img src="https://jasolutions.com.co/wp-content/uploads/2017/02/redalyc.png" class="card-img-top" alt="Redalyc">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Redalyc</h5>
                        <p class="card-text">Red de Revistas Científicas de América Latina y el Caribe, España y Portugal, de acceso abierto.</p>
                        <div class="mt-auto">
                            <a href="https://www.redalyc.org/" target="_blank" class="btn btn-repo">
                                <i class="fas fa-external-link-alt"></i> Visitar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    <img src="https://blogs.iadb.org/conocimiento-abierto/wp-content/uploads/sites/10/2023/03/DOAJ.jpg" class="card-img-top" alt="DOAJ">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">DOAJ</h5>
                        <p class="card-text">Directory of Open Access Journals, indexa revistas científicas de alta calidad, de libre acceso.</p>
                        <div class="mt-auto">
                            <a href="https://doaj.org/" target="_blank" class="btn btn-repo">
                                <i class="fas fa-external-link-alt"></i> Visitar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    <img src="https://universoabierto.org/wp-content/uploads/2018/10/base_logo.png" class="card-img-top" alt="BASE">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">BASE</h5>
                        <p class="card-text">Bielefeld Academic Search Engine, uno de los motores de búsqueda más voluminosos de recursos académicos de acceso abierto.</p>
                        <div class="mt-auto">
                            <a href="https://www.base-search.net/" target="_blank" class="btn btn-repo">
                                <i class="fas fa-external-link-alt"></i> Visitar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    <img src="https://images.seeklogo.com/logo-png/48/1/google-scholar-logo-png_seeklogo-484488.png" class="card-img-top" alt="Google Scholar">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Google Scholar</h5>
                        <p class="card-text">Buscador especializado en literatura académica, artículos, tesis y libros de múltiples disciplinas.</p>
                        <div class="mt-auto">
                            <a href="https://scholar.google.com/" target="_blank" class="btn btn-repo">
                                <i class="fas fa-external-link-alt"></i> Visitar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Zenodo-gradient-square.svg/1200px-Zenodo-gradient-square.svg.png" class="card-img-top" alt="Zenodo">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Zenodo</h5>
                        <p class="card-text">Repositorio de acceso abierto desarrollado por CERN y OpenAIRE, para compartir artículos, datasets y software.</p>
                        <div class="mt-auto">
                            <a href="https://zenodo.org/" target="_blank" class="btn btn-repo">
                                <i class="fas fa-external-link-alt"></i> Visitar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© <?= date('Y') ?> CEDHI - Repositorios Externos</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>