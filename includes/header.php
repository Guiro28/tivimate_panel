<?php
ini_set('display_errors', 0);
include(__DIR__ . '/functions.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$log_check = $db->select('user', '*', 'id = :id', '', [':id' => 1]);
$loggedinuser = !empty($log_check) ? $log_check[0]['username'] : null;

if (!isset($_SESSION['name']) == $loggedinuser) {
    header("location:"."index.php");
    exit();
}

if (isset($_REQUEST['logout'])) {
    session_destroy();
    setcookie("auth", "");
    header("Location: index.php");
    exit;
}

$time = $_SERVER['REQUEST_TIME'];

$timeout_duration = 900;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACTIVITY'] = $time;

function sanitize($data) {
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES );
    $data = SQLite3::escapeString($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tivimate Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="RTX">
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="./img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/favicon-16x16.png">
    <link rel="manifest" href="./img/site.webmanifest">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="css/themes/darkly/bootstrap.css" rel="stylesheet" title="main">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #181828;
            color: #fff;
            min-height: 100vh;
            padding-top: 70px;
        }

        /* Navbar principal */
        .navbar-main {
            background: linear-gradient(45deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        /* Logo */
        .navbar-brand img {
            height: 40px;
            margin-right: 15px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        /* Menú de navegación */
        .navbar-nav .nav-item {
            margin: 0 5px;
        }

        .nav-link {
            color: #a8b2d1 !important;
            padding: 0.8rem 1.2rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #fff !important;
            background: rgba(76, 110, 245, 0.1);
            transform: translateY(-2px);
        }

        .nav-link i {
            margin-right: 8px;
            color: #4C6EF5;
        }

        /* Botones */
        .btn-custom {
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(45deg, #4C6EF5 0%, #6282FF 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 110, 245, 0.3);
            background: linear-gradient(45deg, #3b5ef0 0%, #4f6fff 100%);
        }

        .btn-danger {
            background: linear-gradient(45deg, #FF3366 0%, #FF6B6B 100%);
            border: none;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 51, 102, 0.3);
        }

        /* Contenedor principal */
        .container-fluid {
            padding: 20px;
        }

        /* Mensajes */
        #pageMessages {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1040;
            width: 60%;
            text-align: center;
        }

        /* Efecto hover en los links */
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #4C6EF5;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        @media (max-width: 992px) {
            .navbar-collapse {
                background: rgba(26, 26, 46, 0.98);
                padding: 1rem;
                border-radius: 0 0 10px 10px;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div id="net-canvas"></div>
    
    <!-- Navbar Principal -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-main">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="./img/login_logo.png" alt="logo">
            </a>
            
            <!-- Botón toggle para móvil -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Menú de navegación -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">
                            <i class="fa fa-home"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="main.php">
                            <i class="fa fa-globe"></i>DNS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="download.php">
                            <i class="fa fa-download"></i>Téléchargements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user.php">
                            <i class="fa fa-user"></i>Utilisateur
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">
                            <i class="fa fa-cogs"></i>Pramètres
                        </a>
                    </li>
                </ul>
                
                <!-- Botón de logout -->
                <a href="<?=basename($_SERVER["SCRIPT_NAME"]).'?logout'?>" class="btn btn-danger btn-custom">
                    <i class="fa fa-sign-out"></i>Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <!-- Mensajes -->
    <div id="pageMessages"></div>

    <!-- Contenido principal -->
    <div class="container-fluid">
        <!-- Aquí va el contenido de la página -->
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="./js/custom.js"></script>
    <script src="./js/three.min.js"></script>
    <script src="./js/vanta.net.min.js"></script>
</body>
</html>