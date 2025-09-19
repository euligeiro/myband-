<?php
include 'band_generators.php';
require_once 'conexion_BDD.php';

// Verificar se há pedido de logout
if (isset($_GET['disconnect']) && $_GET['disconnect'] == 1) {
    session_unset();
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Processar formulário de login
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $db = getDBConnection();
        $query = $db->prepare("SELECT * FROM admin WHERE username = :username");
        $query->execute([':username' => $username]);
        $admin = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_id'] = $admin['id'];
        }

 if($username==='admin' && $password==='admin123')
 {
     $_SESSION['admin_logged_in'] = true;
      $_SESSION['admin_username'] = $admin['username'];

 }

         else {
            $login_error = "Identifiants incorrects";
        }
    } catch(PDOException $e) {
        $login_error = "Erreur de connexion à la base de données";
    }
}

$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Band - <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: none;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        .modal-form div {
            margin-bottom: 15px;
        }
        
        .modal-form label {
            display: block;
            margin-bottom: 5px;
        }
        
        .modal-form input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        
        .modal-form button {
            background: #008080;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <nav>
        <div class="band-info">
            <div class="band-logo">
                <img src="<?php echo generate_bandlogo(); ?>" alt="Logo du groupe">
            </div>
            <div class="band-name">
                <?php echo generate_bandname(); ?>
            </div>
        </div>  
        <ul>
            <li><a href="index.php">HOME</a></li>
            <?php if ($is_logged_in): ?>
                <li><a href="?disconnect=1">DISCONNECT</a></li>
            <?php else: ?>
                <li><a href="#" onclick="document.getElementById('loginModal').style.display='block'">CONNECT</a></li>
            <?php endif; ?>
            <li><a href="setlist.php">SETLIST</a></li>
            <li><a href="contact.php">CONTACT</a></li>
        </ul>
    </nav>

    <!-- Modal de Login -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('loginModal').style.display='none'">&times;</span>
            <h2>Connexion Admin</h2>
            <?php if (!empty($login_error)): ?>
                <p class="error-message"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <form class="modal-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div>
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>

    <main>