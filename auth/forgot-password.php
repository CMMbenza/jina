<?php
session_start();
require '../config/config.php';

$error = null;
$success = null;
$userExists = false;
$emailSubmitted = '';

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ÉTAPE 1 : Vérification de l'existence de l'email
    if (isset($_POST['action']) && $_POST['action'] === 'check_email') {
        $emailSubmitted = trim($_POST['email']);

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$emailSubmitted]);
        $user = $stmt->fetch();

        if ($user) {
            $userExists = true;
            $_SESSION['reset_email'] = $emailSubmitted; // Stockage temporaire en session sécurisée
        } else {
            $error = "Cette adresse email n'existe pas dans notre base de données.";
        }
    }

    // ÉTAPE 2 : Enregistrement du nouveau mot de passe
    if (isset($_POST['action']) && $_POST['action'] === 'reset_password') {
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $email = $_SESSION['reset_email'] ?? '';

        if (empty($email)) {
            $error = "Une erreur est survenue. Veuillez recommencer la procédure.";
        } elseif (strlen($password) < 6) {
            $error = "Le mot de passe doit contenir au moins 6 caractères.";
            $userExists = true; // Permet de rester sur les champs de saisie du mot de passe
        } elseif ($password !== $password_confirm) {
            $error = "Les deux mots de passe ne sont pas identiques.";
            $userExists = true; // Permet de rester sur les champs de saisie du mot de passe
        } else {
            // Hachage du mot de passe (Même logique que l'inscription)
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($stmt->execute([$passwordHash, $email])) {
                $success = "Votre mot de passe a été réinitialisé avec succès !";
                unset($_SESSION['reset_email']); // Nettoyage de la session
            } else {
                $error = "Impossible de mettre à jour le mot de passe. Veuillez réessayer.";
                $userExists = true;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - JINA carte numérique</title>

    <link rel="shortcut icon" href="../assets/img/logo-jina.ico" type="image/x-icon">
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
        max-width: 420px;
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

    .alert-custom-error {
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 12px;
        border: none;
        background-color: #fee2e2;
        color: #991b1b;
    }

    .alert-custom-success {
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 12px;
        border: none;
        background-color: #d1fae5;
        color: #065f46;
    }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">

            <div class="text-center">
                <img src="../assets/img/logo jina.jpeg" alt="Logo JINA" class="login-logo rounded">
                <h3 class="brand-title">Mot de passe oublié</h3>
                <p class="brand-subtitle">Sécurisez et réinitialisez vos accès de compte</p>
            </div>

            <?php if($error): ?>
            <div class="alert alert-custom-error mb-4 text-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <?php if($success): ?>
            <div class="alert alert-custom-success mb-4 text-center" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
            </div>
            <div class="text-center">
                <a href="login.php" class="btn btn-jina-login w-100">
                    <i class="fas fa-arrow-left me-2"></i> Retour à la connexion
                </a>
            </div>

            <?php else: ?>

            <?php if(!$userExists): ?>
            <form method="POST">
                <input type="hidden" name="action" value="check_email">

                <div class="mb-4">
                    <label class="form-label">Entrez votre adresse Email</label>
                    <input type="email" name="email" class="form-control" placeholder="chrismbenza@jina.com" required
                        autocomplete="email">
                </div>

                <button type="submit" class="btn btn-jina-login w-100">
                    <i class="fas fa-search me-2"></i> Vérifier mon adresse
                </button>

                <div class="text-center mt-4 form-links">
                    <a href="login.php"><i class="fas fa-chevron-left me-1"></i> Revenir à la page de connexion</a>
                </div>
            </form>
            <?php endif; ?>

            <?php if($userExists): ?>
            <form method="POST">
                <input type="hidden" name="action" value="reset_password">

                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="passwordInput" class="form-control"
                            placeholder="Minimum 4 caractères" required style="padding-right: 45px;">
                        <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirmez le nouveau mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirm" id="passwordConfirmInput" class="form-control"
                            placeholder="••••••••" required style="padding-right: 45px;">
                        <i class="fas fa-eye password-toggle" id="passwordConfirmToggle"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-jina-login w-100">
                    <i class="fas fa-save me-2"></i> Enregistrer le mot de passe
                </button>
            </form>
            <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>

    <script>
    function togglePasswordVisibility(toggleId, inputId) {
        const toggleElement = document.getElementById(toggleId);
        if (toggleElement) {
            toggleElement.addEventListener('click', function() {
                const inputElement = document.getElementById(inputId);
                if (inputElement.type === 'password') {
                    inputElement.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    inputElement.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }
    }

    // Activation du toggle pour les deux champs de saisie
    togglePasswordVisibility('passwordToggle', 'passwordInput');
    togglePasswordVisibility('passwordConfirmToggle', 'passwordConfirmInput');
    </script>

</body>

</html>