<?php
session_start();
require '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['email']); // Email ou username
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE email = ? OR username = ?
        LIMIT 1
    ");
    $stmt->execute([$login, $login]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['PASSWORD'])) {

        $_SESSION['user_id'] = $user['id'];

        header("Location: ../home/dashboard.php");
        exit;

    } else {

        $error = "Email, nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - JINA carte numérique</title>

    <link rel="shortcut icon" href="../assets/img/logo-jina.ico" type="image/x-icon">

    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
    :root {
        --jina-blue: #0f2256;
        --jina-yellow: #ffcc00;
        --light-bg: #f5f7fa;
        --text-dark: #1e293b;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--light-bg);
        color: var(--text-dark);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        width: 100%;
        max-width: 550px;
        padding: 15px;
    }

    .login-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(15, 34, 86, 0.08);
        padding: 40px 35px;
        position: relative;
        overflow: hidden;
    }

    /* Petite ligne décorative supérieure reprenant l'identité */
    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--jina-blue) 0%, var(--jina-yellow) 100%);
    }

    .login-logo {
        max-height: 70px;
        width: auto;
        object-fit: contain;
        margin-bottom: 20px;
    }

    .brand-title {
        color: var(--jina-blue);
        font-weight: 800;
        font-size: 1.75rem;
        margin-bottom: 5px;
    }

    .brand-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 30px;
    }

    .form-label {
        font-weight: 600;
        color: var(--jina-blue);
        font-size: 0.85rem;
        margin-bottom: 8px;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--jina-blue);
        box-shadow: 0 0 0 3px rgba(15, 34, 86, 0.1);
        color: var(--text-dark);
    }

    /* --- Style pour Hide/Show Password --- */
    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #64748b;
        transition: color 0.2s;
        z-index: 10;
    }

    .password-toggle:hover {
        color: var(--jina-blue);
    }

    .form-links {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .form-links a {
        color: var(--jina-blue);
        text-decoration: none;
        transition: color 0.2s;
    }

    .form-links a:hover {
        color: var(--jina-yellow);
        text-decoration: underline;
    }

    .btn-jina-login {
        background-color: var(--jina-blue);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-jina-login:hover {
        background-color: #07112c;
        color: var(--jina-yellow);
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(15, 34, 86, 0.2);
    }

    .alert-custom {
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 12px;
        border: none;
        background-color: #fee2e2;
        color: #991b1b;
    }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">

            <div class="text-center">
                <!-- Emplacement du logo JINA -->
                <img src="../assets/img/logo jina.jpeg" alt="Logo JINA" class="login-logo rounded">
                <h3 class="brand-title">Connexion JINA</h3>
                <p class="brand-subtitle">Accédez à votre espace d'administration</p>
            </div>

            <?php if(isset($error)): ?>
            <div class="alert alert-custom mb-4 text-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nom d'utilisateur/Adresse Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Chris Mbenza" required
                        autocomplete="email">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="passwordInput" class="form-control"
                            placeholder="••••••••" required autocomplete="current-password"
                            style="padding-right: 45px;">
                        <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 form-links">
                    <a href="register.php">Créer votre compte</a>
                    <a href="forgot-password.php">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn btn-jina-login w-100">
                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                </button>
            </form>

        </div>
    </div>

    <!-- Script JavaScript pour le Hide/Show du mot de passe -->
    <script>
    document.getElementById('passwordToggle').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
    </script>

</body>

</html>