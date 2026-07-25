<?php
require '../config/config.php';

// Utilisation de l'opérateur de fusion nulle (PHP 7+)
$identify = $_GET['identify'] ?? '';

// Si aucun identify n'est fourni, redirection
if (empty($identify)) {
    header("Location: index.php");
    exit;
}

// CORRECTION : Sélection par p.identify à la place de u.id car $identify est une chaîne unique (token/slug)
$stmt = $pdo->prepare("SELECT u.id, u.username, u.email, u.account_type, p.* FROM users u 
                    JOIN profiles p ON u.id = p.user_id 
                    WHERE p.identify = ?");
$stmt->execute([$identify]);
$user = $stmt->fetch();

if (!$user) {
    // Utilisation de la syntaxe HEREDOC pour éviter les problèmes de guillemets
    echo <<<HTML
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="../assets/img/logo-jina.ico" type="image/x-icon">
        <title>Profil introuvable | JINA carte numérique</title>
    </head>
    <body class='bg-light'>
        <div class='container mt-5 text-center'>
            <div class='card p-5 shadow-sm border-0 mx-auto' style='max-width: 400px; margin-top: 100px;'>
                <h3 class='fw-bold text-secondary'>Profil introuvable</h3>
                <p class='text-muted small'>L'utilisateur demandé n'existe pas.</p>
                <a href='index.php' class='btn btn-primary rounded-3 mt-2'>Retour à l'accueil</a>
            </div>
        </div>
    </body>
    </html>
    HTML;
    exit;
}

$user_id = $user['id'];
$account_type = $user['account_type'];

// Initialisation des variables
$details = []; $services = []; $catalogues = []; $socials = []; $competences = [];

// Récupération des compétences (valable pour tous les types de comptes)
$compStmt = $pdo->prepare("SELECT competence FROM user_competences WHERE user_id = ?");
$compStmt->execute([$user_id]);
$competences = $compStmt->fetchAll(PDO::FETCH_COLUMN);

if ($account_type === 'employer') {
    $stmt = $pdo->prepare("SELECT * FROM employment_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $details = $stmt->fetch() ?: [];
} else {
    $stmt = $pdo->prepare("SELECT * FROM freelance_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $details = $stmt->fetch() ?: [];
    
    // CORRECTION : La table s'appelle 'user_services' (et non 'services') d'après votre structure SQL
    // CORRECTION : Les colonnes disponibles sont 'titre' et 'description'
    $servicesStmt = $pdo->prepare("SELECT titre, description FROM user_services WHERE user_id = ?");
    $servicesStmt->execute([$user_id]);
    $services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // CORRECTION : La table s'appelle 'user_catalogues' (et non 'catalogues')
    $cataloguesStmt = $pdo->prepare("SELECT nom_produit, image_produit FROM user_catalogues WHERE user_id = ?");
    $cataloguesStmt->execute([$user_id]);
    $catalogues = $cataloguesStmt->fetchAll(PDO::FETCH_ASSOC);
}

// CORRECTION : La table s'appelle 'user_socials' (et non 'social_networks')
// CORRECTION : Les colonnes réelles sont 'plateforme' et 'url'
$socialsStmt = $pdo->prepare("SELECT plateforme, type_reseau, url FROM user_socials WHERE user_id = ?");
$socialsStmt->execute([$user_id]);
$socials = $socialsStmt->fetchAll(PDO::FETCH_ASSOC);

// Nettoyage et formatage des variables utiles
$whatsapp_number = preg_replace('/[^0-9]/', '', $user['tel_perso'] ?? '');
$full_name = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
$titre_pro = !empty($user['titre']) ? htmlspecialchars($user['titre']) : 'Professionnel';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($full_name); ?> | JINA carte numérique</title>
    <meta name="google-adsense-account" content="ca-pub-5378843584978086">
    <meta name="description" content="Profil professionnel de <?php echo htmlspecialchars($full_name); ?> sur JINA.">

    <link rel="shortcut icon" href="../assets/img/logo-jina.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
    /* --- CHARTE GRAPHIQUE JINA --- */
    :root {
        --jina-white: #ffffff;
        --jina-blue: #0f2256;
        --jina-yellow: #ffcc00;
        --light-bg: #f5f7fa;
        --text-dark: #1e293b;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--light-bg);
        color: var(--text-dark);
        overflow-x: hidden;
        /* Empêche tout défilement horizontal */
        scroll-behavior: smooth;
        width: 100%;
    }

    /* Correction globale des conteneurs Bootstrap pour éviter l'overflow */
    .container {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .text-jina-blue {
        color: var(--jina-white);
    }

    .text-jina-blue-titre {
        color: var(--jina-blue);
        font-size: 20px;
    }

    .text-jina-yellow {
        color: var(--jina-yellow);
    }

    /* --- ANIMATION DES BADGES (COMPÉTENCES) CONFIGURATION FORTE --- */
    .bg-jina-blue.badge-animated {
        background-color: var(--jina-white);
        color: var(--jina-blue);
        font-size: 16px;
        border: 1px solid rgb(201, 201, 201);
        display: inline-block;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275),
            background-color 0.25s ease,
            color 0.25s ease,
            box-shadow 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .bg-jina-blue.badge-animated:hover {
        background-color: var(--jina-yellow) !important;
        color: var(--jina-blue) !important;
        border-color: var(--jina-yellow);
        transform: translateY(-5px) scale(1.08);
        /* Soulèvement et zoom visible */
        box-shadow: 0 8px 15px rgba(255, 204, 0, 0.3);
        /* Ombre portée lumineuse jaune */
    }

    .bg-jina-yellow {
        background-color: var(--jina-yellow);
    }

    /* --- NAVBAR --- */
    .navbar {
        background-color: rgba(15, 34, 86, 0.98) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .navbar-logo {
        max-height: 40px;
        width: auto;
        object-fit: contain;
    }

    /* --- HERO SECTION --- */
    .hero-section {
        background: linear-gradient(135deg, var(--jina-blue) 0%, #07112c 100%);
        color: white;
        padding: 140px 0 90px 0;
        position: relative;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40px;
        background: var(--light-bg);
        clip-path: polygon(0 100%, 100% 100%, 100% 0);
    }

    .profile-frame {
        position: relative;
        display: inline-block;
        margin-top: 25px;
    }

    .profile-frame::before {
        content: '';
        position: absolute;
        top: -12px;
        left: -12px;
        width: 80px;
        height: 80px;
        border-top: 5px solid var(--jina-yellow);
        border-left: 5px solid var(--jina-yellow);
    }

    .profile-frame::after {
        content: '';
        position: absolute;
        bottom: -12px;
        right: -12px;
        width: 80px;
        height: 80px;
        border-bottom: 5px solid var(--jina-yellow);
        border-right: 5px solid var(--jina-yellow);
    }

    .profile-img {
        width: 270px;
        height: 270px;
        object-fit: cover;
        border: 6px solid rgba(255, 255, 255, 0.15);
        position: relative;
        z-index: 2;
    }

    .profile-avatar-fallback {
        width: 270px;
        height: 270px;
        background: #ffcc00;
        color: #0f2256;
        font-size: 84px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 6px solid rgba(255, 255, 255, 0.15);
        position: relative;
        z-index: 2;
    }

    /* --- SECTION TITLES --- */
    .section-title {
        font-weight: 800;
        color: var(--jina-blue);
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 40px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 4px;
        background-color: var(--jina-yellow);
        border-radius: 2px;
    }

    .section-title.center::after {
        left: 50%;
        transform: translateX(-50%);
    }

    /* --- SERVICES CARDS --- */
    .card-service {
        border: none;
        border-radius: 16px;
        background: white;
        padding: 35px;
        height: 100%;
        box-shadow: 0 10px 30px rgba(15, 34, 86, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-top: 4px solid transparent;
    }

    .card-service:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(15, 34, 86, 0.1);
        border-top: 4px solid var(--jina-yellow);
    }

    .icon-box {
        width: 60px;
        height: 60px;
        background-color: rgba(15, 34, 86, 0.05);
        color: var(--jina-blue);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 25px;
        transition: all 0.3s;
    }

    .card-service:hover .icon-box {
        background-color: var(--jina-blue);
        color: var(--jina-yellow);
    }

    .text-jina-service {
        color: var(--jina-blue);
    }

    /* Conteneur principal de la carte */
    .product-card-wrapper {
        background-color: #fff;
        height: 280px;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card-wrapper:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .product-image-container {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .no-image-box {
        width: 100%;
        height: 100%;
    }

    .product-card-wrapper:hover .product-img {
        transform: scale(1.1);
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(2px);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .product-card-wrapper:hover .product-overlay {
        opacity: 1;
    }

    .spec-title {
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }

    .product-card-wrapper:hover .spec-title {
        transform: translateY(0);
    }

    .lightbox-img {
        max-height: 70vh;
        object-fit: contain;
        width: auto;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        padding: 20px;
        border-radius: 50%;
    }

    /* --- FOOTER & CONTACTS --- */
    footer {
        background-color: #07112c;
        color: #94a3b8;
    }

    .footer-link {
        color: #94a3b8;
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-link:hover {
        color: var(--jina-yellow);
    }

    .social-btn {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        color: white;
        margin-right: 8px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .social-btn:hover {
        background: var(--jina-yellow);
        color: var(--jina-blue) !important;
        transform: translateY(-3px);
    }

    /* --- BUTTONS --- */
    .btn-jina-primary {
        background-color: var(--jina-yellow);
        color: var(--jina-blue);
        font-weight: 700;
        border: 2px solid var(--jina-yellow);
        border-radius: 10px;
        padding: 12px 28px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-jina-primary:hover {
        background-color: var(--jina-white);
        color: var(--jina-blue);
    }

    .competence-title {
        font-weight: 800;
        color: var(--jina-blue);
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 30px;
        font-size: 25px;
    }

    /* --- ZONE STRUCTURE OFFICIELLE MISE EN PAGE --- */
    .bg-jina-entreprise {
        background: linear-gradient(135deg, #0f2256 0%, #0a173d 100%);
        color: #ffffff;
    }

    .logo-container-box {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100px;
        height: 100px;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .navbar-logo {
        border-radius: 5px;
    }

    .company-logo-img {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .company-info-item {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 10px 15px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
    }

    .company-info-item i {
        color: var(--jina-yellow);
    }

    /* =========================================
       --- MEDIA QUERIES (RESPONSIVITÉ) ---
       ========================================= */

    /* Petits mobiles et moins (xs) */
    @media (max-width: 575.98px) {
        .navbar-brand span {
            font-size: 1.2rem;
        }

        .ms-auto .btn {
            padding: 8px 15px;
            font-size: 0.8rem;
        }

        .hero-section {
            padding: 100px 0 60px 0;
        }

        .profile-img,
        .profile-avatar-fallback {
            width: 180px;
            height: 180px;
        }

        .profile-frame::before,
        .profile-frame::after {
            width: 50px;
            height: 50px;
        }

        .display-4 {
            font-size: 2rem;
        }

        .section-title {
            font-size: 1.6rem;
            margin-bottom: 30px;
        }

        .competence-title {
            font-size: 1.3rem;
            text-align: center;
        }

        .d-flex.flex-wrap {
            justify-content: center;
        }

        .card-service {
            padding: 20px;
        }

        .logo-container-box {
            width: 70px;
            height: 70px;
        }

        .bg-jina-entreprise h2 {
            font-size: 1.4rem;
        }
    }

    /* Mobiles en paysage à Tablettes (sm à md) */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .hero-section {
            padding: 120px 0 70px 0;
        }

        .profile-img,
        .profile-avatar-fallback {
            width: 220px;
            height: 220px;
        }

        .display-4 {
            font-size: 2.5rem;
        }
    }

    /* Tablettes à Desktops (md à lg) */
    @media (max-width: 991.98px) {

        /* Force les éléments en colonne sur mobile/tablette si nécessaire */
        .row.align-items-center.g-5 {
            flex-direction: column;
            text-align: center;
        }

        .hero-section .text-md-start {
            text-align: center !important;
        }

        .hero-section .d-flex {
            justify-content: center;
        }

        /* Ajustement espacement infos entreprise */
        .company-info-item {
            width: 100%;
            justify-content: center;
        }
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center"
                href="https://jina.notechgroup.com/consult-card-visit/">
                <img src="../assets/img/logo jina.jpeg" alt="Logo JINA" class="navbar-logo me-2">
                <span>JINA</span>
            </a>
            <div class="ms-autow" style="max-height: auto; width: auto; font-size:10px;">
                <a href="../auth/register.php" class="btn btn-warning nav-btn fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-id-card me-2"></i>Créer <span class="d-sm-inline">votre carte</span>
                </a>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center text-md-start overflow-hidden">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-5 col-lg-4 text-center">
                    <div class="profile-frame">
                        <?php if(!empty($user['photo_profil'])): ?>
                        <img src="../<?php echo htmlspecialchars($user['photo_profil']); ?>"
                            alt="<?php echo htmlspecialchars($full_name); ?>" class="profile-img shadow-lg img-fluid"
                            data-bs-toggle="modal" data-bs-target="#profilePhotoModal"
                            style="cursor: pointer; transition: transform 0.2s;"
                            onmouseover="this.style.transform='scale(1.03)'"
                            onmouseout="this.style.transform='scale(1)'">
                        <?php else: ?>
                        <div class="profile-avatar-fallback shadow-lg img-fluid">
                            <?php echo strtoupper(substr($user['prenom'] ?? $user['nom'] ?? 'U', 0, 1)); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-7 col-lg-8">
                    <span
                        class="badge bg-jina-yellow text-jina-blue-titre mb-3 fw-bold px-3 py-2 text-uppercase tracking-wider">
                        <?php echo $titre_pro; ?>
                    </span>
                    <h1 class="display-4 fw-extrabold text-white mb-2"><?php echo htmlspecialchars($full_name); ?></h1>

                    <?php if($account_type === 'employer'): ?>
                    <p class="fs-4 text-white-50 mb-4">
                        <?php echo htmlspecialchars($details['poste'] ?? 'Recruteur'); ?> chez
                        <?php echo htmlspecialchars($details['nom_entreprise'] ?? 'son entreprise'); ?>
                    </p>
                    <?php else: ?>
                    <p class="fs-4 text-white-50 mb-4">Professionnel Freelance/Entrepreneur disponible pour vos projets
                        et collaborations.</p>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3">
                        <?php if(!empty($whatsapp_number)): ?>
                        <a href="https://wa.me/<?php echo $whatsapp_number; ?>" target="_blank"
                            class="btn btn-jina-primary shadow">
                            <i class="fab fa-whatsapp me-2"></i>Contacter sur WhatsApp
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5" id="about">
        <div class="container py-4">
            <div class="row g-5">
                <div class="col-lg-8">
                    <h2 class="section-title">👤 À propos de moi</h2>
                    <p class="text-secondary mb-4 text-justify">
                        <?php echo nl2br(htmlspecialchars($user['bio'] ?? "Aucune biographie rédigée pour le moment.")); ?>
                    </p>
                </div>

                <div class="col-lg-4">
                    <?php if(!empty($competences)): ?>
                    <h5 class="fw-bold text-jina-blue mb-3 competence-title">
                        <i class="fas fa-brain me-2 text-jina-yellow"></i>Compétences
                    </h5>
                    <div class="d-flex flex-wrap gap-2 badge-container">
                        <?php foreach($competences as $competence): ?>
                        <span class="badge bg-jina-blue badge-animated p-2 px-3 rounded-pill font-weight-normal">
                            <?php echo htmlspecialchars($competence); ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-jina-entreprise overflow-hidden">
        <div class="container py-4">
            <div class="row">
                <div class="col-12">
                    <h6 class="text-jina-yellow fw-bold text-uppercase mb-3 tracking-wide text-center text-sm-start">
                        STRUCTURE OFFICIELLE</h6>

                    <?php if(!empty($details['nom_entreprise'])): ?>
                    <div class="card bg-transparent border-0 text-white">
                        <div class="card-body p-0">
                            <div
                                class="d-flex align-items-center gap-3 mb-4 flex-column flex-sm-row text-center text-sm-start">
                                <?php if(!empty($details['logo_entreprise'])): ?>
                                <div class="logo-container-box shadow-sm flex-shrink-0">
                                    <img src="../<?php echo htmlspecialchars($details['logo_entreprise']); ?>"
                                        class="company-logo-img img-fluid" alt="Logo de l'entreprise">
                                </div>
                                <?php else: ?>
                                <div class="logo-container-box shadow-sm flex-shrink-0">
                                    <i class="fas fa-building fa-2x text-white-50"></i>
                                </div>
                                <?php endif; ?>

                                <div>
                                    <small
                                        class="text-white-50 d-block text-uppercase small"><?php echo ($account_type === 'employer') ? 'Entreprise' : ' '; ?></small>
                                    <h2 class="h1 fw-bold text-white m-0">
                                        <?php echo htmlspecialchars($details['nom_entreprise']); ?></h2>
                                </div>
                            </div>

                            <?php if(!empty($details['desc_entreprise'])): ?>
                            <p class="fs-5 text-white-50 mb-4 lh-base text-justify" style="max-width: 800px;">
                                <?php echo nl2br(htmlspecialchars($details['desc_entreprise'])); ?>
                            </p>
                            <?php endif; ?>

                            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-sm-start">
                                <?php if(!empty($details['tel_bureau'])): ?>
                                <div class="company-info-item">
                                    <i class="fas fa-phone"></i>
                                    <span><strong>Tél Bureau :</strong>
                                        <?php echo htmlspecialchars($details['tel_bureau']); ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if(!empty($details['adresse_bureau'])): ?>
                                <div class="company-info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><strong>Adresse :</strong>
                                        <?php echo htmlspecialchars($details['adresse_bureau']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="row mt-3">
                                <?php if(!empty($socials)): ?>
                                <div class="mb-4 d-flex flex-wrap justify-content-center justify-content-sm-start">

                                    <?php 
            foreach($socials as $social): 
                // CONDITION : On ne garde que le type_reseau 'entreprise'
                // (Le strtolower et trim gèrent les majuscules et espaces en BDD)
               if (
                    isset($social['type_reseau']) &&
                    in_array(strtolower(trim($social['type_reseau'])), ['entreprise', 'freelance'])     
                ):

                    // Conversion en minuscules pour éviter les problèmes de casse
                    $platform = strtolower(trim($social['plateforme'])); 
                    
                    // Tableau de correspondance entre le nom en BD et les classes Font Awesome
                    $icon_class = 'fas fa-globe'; // Icône par défaut si aucune ne correspond
                    
                    if (strpos($platform, 'facebook') !== false) {
                        $icon_class = 'fab fa-facebook-f';
                    } elseif (strpos($platform, 'linkedin') !== false) {
                        $icon_class = 'fab fa-linkedin-in';
                    } elseif (strpos($platform, 'twitter') !== false || strpos($platform, ' x') !== false || $platform === 'x') {
                        $icon_class = 'fab fa-x-twitter';
                    } elseif (strpos($platform, 'instagram') !== false) {
                        $icon_class = 'fab fa-instagram';
                    } elseif (strpos($platform, 'youtube') !== false) {
                        $icon_class = 'fab fa-youtube';
                    } elseif (strpos($platform, 'whatsapp') !== false) {
                        $icon_class = 'fab fa-whatsapp';
                    } elseif (strpos($platform, 'tiktok') !== false) {
                        $icon_class = 'fab fa-tiktok';
                    } elseif (strpos($platform, 'github') !== false) {
                        $icon_class = 'fab fa-github';
                    }
                ?>
                                    <?php
                                    $url = trim($social['url']);

                                    if (!preg_match('#^https?://#i', $url)) {
                                        $url = 'https://' . $url;
                                    }
                                    ?>
                                    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="social-btn"
                                        title="<?php echo htmlspecialchars($social['plateforme']); ?>">
                                        <i class="<?php echo $icon_class; ?>"></i>
                                    </a>
                                    <?php 
                endif; // Fin de la condition de filtrage 'entreprise'
            endforeach; 
            ?>
                                </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                    <?php else: ?>
                    <p class="text-white-50 m-0 text-center text-sm-start">Aucune structure officielle renseignée.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if($account_type !== 'employer' && !empty($services)): ?>
    <section class="py-5" id="services">
        <div class="container py-5">
            <div class="text-center">
                <h2 class="section-title center text-center">🛠 Services</h2>
                <p class="text-muted max-w-2xl mx-auto mb-5">Des prestations sur-mesure adaptées à vos besoins
                    professionnels.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <?php foreach($services as $service): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service shadow-sm">
                        <div class="icon-box"><i class="fas fa-star"></i></div>
                        <h4 class="fw-bold h5 text-jina-service"><?php echo htmlspecialchars($service['titre']); ?></h4>
                        <p class="text-muted small mb-0 text-justify">
                            <?php echo nl2br(htmlspecialchars($service['description'] ?? 'Prestation de quality délivrée par un expert.')); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if($account_type !== 'employer' && !empty($catalogues)): ?>
    <section class="py-5 bg-light" id="catalogue">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="section-title center">📂 Catalogue & Produits</h2>
                <p class="text-muted">Aperçu ou liste des produits disponibles.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <?php foreach($catalogues as $index => $item): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card-wrapper position-relative overflow-hidden rounded-4 shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#lightboxModal" data-bs-slide-to="<?php echo $index; ?>">
                        <div class="product-image-container">
                            <?php if(!empty($item['image_produit'])): ?>
                            <img src="../<?php echo htmlspecialchars($item['image_produit']); ?>"
                                class="img-fluid product-img"
                                alt="<?php echo htmlspecialchars($item['nom_produit']); ?>">
                            <?php else: ?>
                            <div
                                class="bg-secondary text-white d-flex align-items-center justify-content-center no-image-box">
                                <i class="fas fa-box fa-3x"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-overlay d-flex align-items-center justify-content-center p-3">
                            <h5 class="fw-bold text-white text-center m-0 spec-title">
                                <?php echo htmlspecialchars($item['nom_produit']); ?>
                            </h5>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    <div id="lightboxCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner">
                            <?php foreach($catalogues as $index => $item): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="d-flex flex-column align-items-center">
                                    <?php if(!empty($item['image_produit'])): ?>
                                    <img src="../<?php echo htmlspecialchars($item['image_produit']); ?>"
                                        class="d-block img-fluid rounded-4 lightbox-img" alt="Produit">
                                    <?php else: ?>
                                    <div
                                        class="bg-secondary text-white d-flex align-items-center justify-content-center rounded-4 lightbox-img">
                                        <i class="fas fa-box fa-5x"></i>
                                    </div>
                                    <?php endif; ?>
                                    <h4 class="text-white mt-3 text-center px-3 fw-semibold">
                                        <?php echo htmlspecialchars($item['nom_produit']); ?></h4>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="modal fade" id="profilePhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 position-relative text-center">

                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>

                    <img src="../<?php echo htmlspecialchars($user['photo_profil']); ?>"
                        alt="<?php echo htmlspecialchars($full_name); ?>" class="img-fluid rounded-4 shadow-lg"
                        style="max-height: 80vh; object-fit: contain; border: 4px solid rgba(255, 255, 255, 0.2);">

                    <h4 class="text-white mt-3 fw-bold"><?php echo htmlspecialchars($full_name); ?></h4>

                </div>
            </div>
        </div>
    </div>

    <footer class="pt-5 pb-4" id="contacts">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <h3 class="fw-bold text-white mb-3 text-center text-lg-start">
                        <?php echo htmlspecialchars($full_name); ?></h3>
                    <p class="small text-white-50 pe-lg-4 mb-4 text-center text-lg-start text-justify-mobile">Retrouvez
                        toutes mes activités ainsi que mes moyens de
                        contact officiels configurés sur JINA.</p>
                    <?php if(!empty($socials)): ?>
                    <h6 class="text-jina-yellow fw-bold small text-uppercase tracking-wider mb-3 d-none d-lg-block">
                        SUIVRE MA PRÉSENCE
                        EN LIGNE</h6>
                    <div class="mb-4 d-flex justify-content-center justify-content-lg-start">
                        <?php 
                        foreach($socials as $social): 
                            if (isset($social['type_reseau']) && strtolower(trim($social['type_reseau'])) === 'perso'):
                            // Conversion en minuscules pour éviter les problèmes de casse (ex: LinkedIn vs linkedin)
                            $platform = strtolower(trim($social['plateforme'])); 
                            
                            // Tableau de correspondance entre le nom en BD et les classes Font Awesome
                            $icon_class = 'fas fa-globe'; // Icône par défaut si aucune ne correspond
                            
                            if (strpos($platform, 'facebook') !== false) {
                                $icon_class = 'fab fa-facebook-f';
                            } elseif (strpos($platform, 'linkedin') !== false) {
                                $icon_class = 'fab fa-linkedin-in';
                            } elseif (strpos($platform, 'twitter') !== false || strpos($platform, ' x') !== false || $platform === 'x') {
                                $icon_class = 'fab fa-x-twitter';
                            } elseif (strpos($platform, 'instagram') !== false) {
                                $icon_class = 'fab fa-instagram';
                            } elseif (strpos($platform, 'youtube') !== false) {
                                $icon_class = 'fab fa-youtube';
                            } elseif (strpos($platform, 'whatsapp') !== false) {
                                $icon_class = 'fab fa-whatsapp';
                            } elseif (strpos($platform, 'tiktok') !== false) {
                                $icon_class = 'fab fa-tiktok';
                            } elseif (strpos($platform, 'github') !== false) {
                                $icon_class = 'fab fa-github';
                            }
                        ?>
                        <a href="<?php echo htmlspecialchars($social['url']); ?>" target="_blank" class="social-btn"
                            title="<?php echo htmlspecialchars($social['plateforme']); ?>">
                            <i class="<?php echo $icon_class; ?>"></i>
                        </a>
                        <?php 
                endif; // Fin de la condition de filtrage 'entreprise'
            endforeach; 
            ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-7">
                    <h4 class="fw-bold text-white h5 mb-4 text-center text-lg-start">📞 Coordonnées Professionnelles
                    </h4>
                    <div class="row g-3 text-center text-sm-start">
                        <div class="col-sm-6">
                            <?php if(!empty($user['tel_perso'])): ?>
                            <div
                                class="d-flex align-items-start mb-3 flex-column flex-sm-row align-items-center align-items-sm-start">
                                <div class="text-jina-yellow mb-2 mb-sm-0 me-sm-3"><i class="fas fa-phone-alt fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block text-white fw-semibold small">Téléphone / WhatsApp :</span>
                                    <a href="tel:<?php echo htmlspecialchars($user['tel_perso']); ?>"
                                        class="footer-link small"><?php echo htmlspecialchars($user['tel_perso']); ?></a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <?php if(!empty($details['adresse_bureau'])): ?>
                            <div
                                class="d-flex align-items-start mb-3 flex-column flex-sm-row align-items-center align-items-sm-start">
                                <div class="text-jina-yellow mb-2 mb-sm-0 me-sm-3"><i
                                        class="fas fa-map-marker-alt fs-5"></i></div>
                                <div>
                                    <span class="d-block text-white fw-semibold small">Adresse Bureau :</span>
                                    <span
                                        class="text-white-50 small text-justify-mobile"><?php echo htmlspecialchars($details['adresse_bureau']); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row g-3 text-center text-sm-start">
                        <div class="col-sm-6">
                            <div
                                class="d-flex align-items-start mb-3 flex-column flex-sm-row align-items-center align-items-sm-start">
                                <div class="text-jina-yellow mb-2 mb-sm-0 me-sm-3"><i class="fas fa-envelope fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block text-white fw-semibold small">E-mail :</span>
                                    <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>"
                                        class="footer-link small"><?php echo htmlspecialchars($user['email']); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary opacity-20">
            <div class="row">
                <div class="col text-center text-white-50 small">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> JINA. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
            } else {
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
            }
        });

        // Gestionnaire Lightbox
        const lightboxModal = document.getElementById('lightboxModal');
        if (lightboxModal) {
            const carouselElement = document.getElementById('lightboxCarousel');
            const bootstrapCarousel = new bootstrap.Carousel(carouselElement, {
                ride: false,
                wrap: true
            });
            lightboxModal.addEventListener('show.bs.modal', function(event) {
                const triggerButton = event.relatedTarget;
                const slideIndex = triggerButton.getAttribute('data-bs-slide-to');
                bootstrapCarousel.to(parseInt(slideIndex));
            });
        }
    });
    </script>
</body>

</html>