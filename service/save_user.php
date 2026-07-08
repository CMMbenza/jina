<?php
// service/save_user.php
// 1. FORCE L'AFFICHAGE DES ERREURS (Indispensable en ligne pour diagnostiquer la page 500)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Définition de la fonction de réponse visuelle
    function displayResponse($isSuccess, $message) {
        $title = $isSuccess ? "Félicitations !" : "Oups ! Une erreur est survenue";
        $icon = $isSuccess ? "fa-check-circle" : "fa-exclamation-triangle";
        $iconColor = $isSuccess ? "#10b981" : "#ef4444";
        $loginUrl = "../auth/login.php"; 
        $retryUrl = "javascript:history.back()";
        ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --jina-blue: #0f2256;
        --jina-yellow: #ffcc00;
        --light-bg: #f5f7fa;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--light-bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .response-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(15, 34, 86, 0.06);
        padding: 40px;
        max-width: 500px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .response-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--jina-blue) 0%, var(--jina-yellow) 100%);
    }

    .btn-jina-primary {
        background-color: var(--jina-blue);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-jina-primary:hover {
        background-color: #07112c;
        color: var(--jina-yellow);
    }

    .btn-jina-secondary {
        background-color: #cbd5e1;
        color: #334155;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-jina-secondary:hover {
        background-color: #94a3b8;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center">
        <div class="response-card">
            <div class="mb-4">
                <i class="fas <?php echo $icon; ?> fa-4x" style="color: <?php echo $iconColor; ?>;"></i>
            </div>
            <h3 class="fw-bold mb-3" style="color: var(--jina-blue);"><?php echo $title; ?></h3>
            <p class="text-muted mb-4"><?php echo $message; ?></p>
            <?php if ($isSuccess): ?>
            <a href="<?php echo $loginUrl; ?>" class="btn btn-jina-primary shadow-sm">
                <i class="fas fa-sign-in-alt me-2"></i> Se connecter
            </a>
            <?php else: ?>
            <a href="<?php echo $retryUrl; ?>" class="btn btn-jina-secondary">
                <i class="fas fa-arrow-left me-2"></i> Réessayer
            </a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
<?php
    }

    // Fonction de téléversement des fichiers
    function uploadFile($fileField, $targetDir) {
        if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileTmpPath = $_FILES[$fileField]['tmp_name'];
        $fileName = $_FILES[$fileField]['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            return null;
        }

        $newFileName = md5(time() . mt_rand()) . '.' . $fileExtension;
        $destPath = $targetDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            return "uploads/" . $newFileName;
        }
        return null;
    }

    try {
        // Utilisation d'un chemin absolu basé sur le dossier actuel pour éviter les erreurs d'inclusion en production
        $configPath = dirname(__DIR__) . '/config/config.php';
        if (!file_exists($configPath)) {
            throw new Exception("Le fichier de configuration n'a pas été trouvé à l'emplacement : " . $configPath);
        }
        require_once $configPath;
        
        if (!isset($pdo) || !$pdo) {
            throw new Exception("La connexion à la base de données n'a pas pu être établie.");
        }

        $pdo->beginTransaction();

        $rawPassword = $_POST['password']; // On garde le mot de passe en clair pour l'envoyer par mail
        $passwordHash = password_hash($rawPassword, PASSWORD_BCRYPT);

        // Chemin absolu pour le dossier des uploads
        $uploadTargetDir = dirname(__DIR__) . "/uploads/";

        $photoProfil = uploadFile('photo_profil', $uploadTargetDir);
        $photoCouverture = uploadFile('photo_couverture', $uploadTargetDir);

        if (!$photoProfil || !$photoCouverture) {
            throw new Exception("La photo de profil et la photo de couverture sont obligatoires.");
        }

        $accountType = $_POST['account_type'];

        // --- 1. INSERTION DANS LA TABLE `users` ---
        $sqlUser = "INSERT INTO users (username, email, password, account_type) 
                    VALUES (:username, :email, :password, :account_type)";
        
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([
            ':username'     => $_POST['username'],
            ':email'        => $_POST['email'],
            ':password'     => $passwordHash,
            ':account_type' => $accountType
        ]);

        $userId = $pdo->lastInsertId();

        // --- 2. INSERTION DANS LA TABLE `profiles` ---
        $sqlProfile = "INSERT INTO profiles (
            user_id, nom, prenom, titre, bio, tel_perso, photo_profil, photo_couverture, identify
        ) VALUES (
            :user_id, :nom, :prenom, :titre, :bio, :tel_perso, :photo_profil, :photo_couverture, :identify
        )";

        $stmtProfile = $pdo->prepare($sqlProfile);
        $stmtProfile->execute([
            ':user_id'          => $userId,
            ':nom'              => $_POST['nom'],
            ':prenom'           => $_POST['prenom'],
            ':titre'            => $_POST['titre'] ?? null,
            ':bio'              => $_POST['bio'] ?? null,
            ':tel_perso'        => $_POST['tel_perso'],
            ':photo_profil'     => $photoProfil,
            ':photo_couverture' => $photoCouverture,
            ':identify'         => $_POST['identify']
        ]);

        // --- INSERTION DES COMPÉTENCES ---
        if (!empty($_POST['activites_competences'])) {
            $stmtCompetence = $pdo->prepare("INSERT INTO user_competences (user_id, competence) VALUES (?, ?)");
            $competences = explode(',', $_POST['activites_competences']);
            foreach ($competences as $competence) {
                $competence = trim($competence);
                if ($competence != '') {
                    $stmtCompetence->execute([$userId, $competence]);
                }
            }
        }

        // --- 3. INSERTION DANS LES TABLES SPECIFIQUES ---
        if ($accountType === 'employer') {
            $sqlEmployment = "INSERT INTO employment_details (user_id, nom_entreprise, poste, about_entreprise) 
                              VALUES (:user_id, :nom_entreprise, :poste, :about_entreprise)";
            
            $stmtEmployment = $pdo->prepare($sqlEmployment);
            $stmtEmployment->execute([
                ':user_id'          => $userId,
                ':nom_entreprise'   => $_POST['nom_entreprise'] ?? null,
                ':poste'            => $_POST['poste_actuel'] ?? null,
                ':about_entreprise' => $_POST['apropos_entreprise'] ?? null
            ]);
        } 
        elseif ($accountType === 'freelance') {
            $logoEntrepriseFree = uploadFile('logo_entreprise_free', $uploadTargetDir);

            $sqlFreelance = "INSERT INTO freelance_details (user_id, nom_entreprise, desc_entreprise, tel_bureau, adresse_bureau, logo_entreprise) 
                             VALUES (:user_id, :nom_entreprise, :desc_entreprise, :tel_bureau, :adresse_bureau, :logo_entreprise)";
            
            $stmtFreelance = $pdo->prepare($sqlFreelance);
            $stmtFreelance->execute([
                ':user_id'         => $userId,
                ':nom_entreprise'  => $_POST['nom_entreprise_free'] ?? null,
                ':desc_entreprise' => $_POST['desc_entreprise_free'] ?? null,
                ':tel_bureau'      => $_POST['tel_bureau'] ?? null,
                ':adresse_bureau'  => $_POST['adresse_bureau'] ?? null,
                ':logo_entreprise' => $logoEntrepriseFree
            ]);
        }

        // --- 4. GESTION DES RÉSEAUX SOCIAUX ---
        if (!empty($_POST['social_perso'])) {
            $stmtSocial = $pdo->prepare("INSERT INTO user_socials (user_id, type_reseau, plateforme, url) VALUES (?, 'perso', ?, ?)");
            foreach ($_POST['social_perso'] as $plateforme => $url) {
                if (!empty($url)) {
                    $stmtSocial->execute([$userId, $plateforme, $url]);
                }
            }
        }

        if ($accountType === 'employer' && !empty($_POST['social_entreprise'])) {
            $stmtSocial = $pdo->prepare("INSERT INTO user_socials (user_id, type_reseau, plateforme, url) VALUES (?, 'entreprise', ?, ?)");
            foreach ($_POST['social_entreprise'] as $plateforme => $url) {
                if (!empty($url)) {
                    $stmtSocial->execute([$userId, $plateforme, $url]);
                }
            }
        } elseif ($accountType === 'freelance' && !empty($_POST['social_entreprise_free'])) {
            $stmtSocial = $pdo->prepare("INSERT INTO user_socials (user_id, type_reseau, plateforme, url) VALUES (?, 'freelance', ?, ?)");
            foreach ($_POST['social_entreprise_free'] as $plateforme => $url) {
                if (!empty($url)) {
                    $stmtSocial->execute([$userId, $plateforme, $url]);
                }
            }
        }

        // --- 5. GESTION DES SERVICES & CATALOGUES ---
        if ($accountType === 'freelance') {
            if (!empty($_POST['service_titre'])) {
                $stmtService = $pdo->prepare("INSERT INTO user_services (user_id, titre, description) VALUES (?, ?, ?)");
                foreach ($_POST['service_titre'] as $index => $titre) {
                    $desc = $_POST['service_desc'][$index] ?? '';
                    if (!empty($titre)) {
                        $stmtService->execute([$userId, $titre, $desc]);
                    }
                }
            }

            if (!empty($_POST['catalogue_nom']) && isset($_FILES['catalogue_image'])) {
                $stmtCat = $pdo->prepare("INSERT INTO user_catalogues (user_id, nom_produit, image_produit) VALUES (?, ?, ?)");
                foreach ($_POST['catalogue_nom'] as $index => $nomProduit) {
                    if (empty($nomProduit)) continue;
                    
                    if ($_FILES['catalogue_image']['error'][$index] === UPLOAD_ERR_OK) {
                        $fileExtension = strtolower(pathinfo($_FILES['catalogue_image']['name'][$index], PATHINFO_EXTENSION));
                        $newFileName = md5(time() . mt_rand()) . '.' . $fileExtension;
                        $destPath = $uploadTargetDir . $newFileName;
                        
                        if (move_uploaded_file($_FILES['catalogue_image']['tmp_name'][$index], $destPath)) {
                            $stmtCat->execute([$userId, $nomProduit, "uploads/" . $newFileName]);
                        }
                    }
                }
            }
        }

        $pdo->commit();

        // --- 6. ENVOI DE L'EMAIL DE BIENVENUE (CODE PUR PHP) ---
        $to = trim($_POST['email']);
        $username = trim($_POST['username']);
        $siteUrl = !empty($_POST['social_perso']['site']) ? $_POST['social_perso']['site'] : 'https://ntcard.notechgroup.com/' . $_POST['identify'];

        $subject = "Bienvenue chez JINA - Vos identifiants de connexion";

        // Structure HTML de l'email reprenant la charte graphique de JINA
        $messageHtml = "
        <html>
        <head>
            <title>Bienvenue chez JINA</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f5f7fa; color: #1e293b; padding: 20px; margin: 0;'>
            <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #0f2256;'>
                <div style='padding: 30px; text-align: center; background-color: #0f2256;'>
                    <h2 style='color: #ffcc00; margin: 0; font-size: 24px; font-weight: bold;'>Félicitations !</h2>
                    <p style='color: white; margin: 5px 0 0 0;'>Votre profil JINA a été créé avec succès.</p>
                </div>
                <div style='padding: 30px;'>
                    <p>Bonjour <strong>$username</strong>,</p>
                    <p>Nous sommes ravis de vous compter parmi les membres de la communauté <strong>JINA</strong>. Voici vos accès de connexion à conserver précieusement :</p>
                    
                    <div style='background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0;'>
                        <p style='margin: 0 0 10px 0;'><strong>Nom d'utilisateur / Email :</strong> $to</p>
                        <p style='margin: 0 0 10px 0;'><strong>Mot de passe :</strong> <span style='font-family: monospace; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;'>$rawPassword</span></p>
                        <p style='margin: 0;'><strong>Votre site personnel JINA :</strong> <a href='$siteUrl' style='color: #0f2256; font-weight: bold;'>$siteUrl</a></p>
                    </div>

                    <p style='text-align: center; margin-top: 30px;'>
                        <a href='https://ntcard.notechgroup.com/auth/login.php' style='background-color: #0f2256; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;'>Se connecter à mon espace</a>
                    </p>
                </div>
                <div style='background-color: #f1f5f9; padding: 15px; text-align: center; font-size: 12px; color: #64748b;'>
                    © " . date('Y') . " JINA. Tous droits réservés.
                </div>
            </div>
        </body>
        </html>
        ";

        // En-têtes obligatoires pour un mail HTML propre et sécurisé
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: JINA <no-reply@notechgroup.com>" . "\r\n";
        $headers .= "Reply-To: support@notechgroup.com" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Envoi effectif de l'email
        @mail($to, $subject, $messageHtml, $headers);

        displayResponse(true, "Profil créé avec succès ! Votre compte JINA est désormais actif et vos identifiants viennent de vous être envoyés par email.");

    } catch (Exception $e) {
        if (isset($pdo) && $pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        displayResponse(false, "Erreur lors de l'enregistrement : " . $e->getMessage());
    }
} else {
    displayResponse(false, "Méthode non autorisée.");
}
?>