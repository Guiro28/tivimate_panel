<?php
session_start();
include('includes/functions.php');
$table_name = "user";

// Insérer l'utilisateur admin si la table est vide, avec mot de passe haché
$log_check = $db->select($table_name, '*', 'id = :id', '', [':id' => 1]);
$loggedinuser = !empty($log_check) ? $log_check[0]['username'] : null;

if (!empty($loggedinuser) && isset($_SESSION['name']) && $_SESSION['name'] === $loggedinuser) {
    header("Location: home.php");
    exit;
}

$data = [
    'id' => '1',
    'username' => 'admin',
    'password' => password_hash('admin', PASSWORD_DEFAULT),
];
$db->insertIfEmpty($table_name, $data);

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $userData = $db->select($table_name, '*', 'username = :username', '', [':username' => $username]);

    if ($userData) {
        $storedHash = $userData[0]['password'];
        $enteredPassword = $_POST["password"];

        if (password_verify($enteredPassword, $storedHash)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $username;
			$_SESSION['id'] = $userData[0]['id'];

            if ($username == 'admin') {
                header('Location: home.php');
            } else {
                header('Location: home.php');
            }
            exit;
        } else {
            $error_message = "Échec de la connexion. Veuillez vérifier vos identifiants et réessayer.";
        }
    } else {
        $error_message = "Échec de la connexion. Veuillez vérifier vos identifiants et réessayer.";
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tivimate Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #111111;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .login-section {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }

        .visual-section {
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(45deg, #FF3366, #FF6B6B);
            filter: blur(30px);
            opacity: 0.3;
            top: 20%;
            left: 20%;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, #4E54C8, #8F94FB);
            filter: blur(25px);
            opacity: 0.2;
            bottom: 30%;
            right: 20%;
        }

        .brand {
            margin-bottom: 3rem;
        }

        .brand h1 {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .brand p {
            color: #666666;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #888888;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #444444;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 2.5rem;
            border: 2px solid #222222;
            border-radius: 12px;
            background: #1a1a1a;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #FF3366;
            outline: none;
            background: #222222;
        }

        .form-control::placeholder {
            color: #444444;
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(45deg, #FF3366, #FF6B6B);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 51, 102, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .footer-text {
            margin-top: 2rem;
            text-align: center;
            color: #444444;
            font-size: 0.9rem;
        }

        .footer-text a {
            color: #FF3366;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }

            .visual-section {
                display: none;
            }

            .login-section {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var errorBox = document.getElementById("login-error");
    if (errorBox) {
        setTimeout(function(){
            errorBox.style.transition = "opacity 0.6s";
            errorBox.style.opacity = "0";
            setTimeout(function(){
                errorBox.remove();
            }, 600);
        }, 3500); // 3500 ms = 3,5 secondes (adapte si tu veux plus/moins)
    }
});
</script>
<body>
    <div class="login-section">
        <div class="login-container">
            <div class="brand">
                <h1>Tivimate</h1>
                <p>Admin Management System</p>
				 <?php if (!empty($error_message)) : ?>
    <div id="login-error" style="
        background: linear-gradient(90deg, #ff5c70 0%, #ff7c90 100%);
        color: #fff;
        font-weight: 500;
        border-radius: 12px;
        padding: 16px 24px;
        text-align: center;
        margin-top: 16px;
        margin-bottom: 22px;
        font-size: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.07);
        max-width: 350px;
        margin-left: auto;
        margin-right: auto;">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>
            </div>
            <form method="post">
                <div class="form-group">
                    <label>Nom d'utilisateur</label>
                    <div class="input-group">
                        <!-- icône utilisateur -->
                        <input type="text" class="form-control" name="username" placeholder="Entrez le nom d'utilisateur" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-group">
                        <!-- icône mot de passe -->
                        <input type="password" class="form-control" name="password" placeholder="Entrez le mot de passe" required>
                    </div>
                </div>
                <button type="submit" name="login" class="login-btn">Se connecter</button>
            </form>
            <p class="footer-text">Powered by <a href="#">Tivimate</a></p>
        </div>
    </div>
    <div class="visual-section">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
    </div>
</body>
</html>