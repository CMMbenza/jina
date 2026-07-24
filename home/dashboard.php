<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Informations globales de l'utilisateur (Compte et Profil)
$user_stmt = $pdo->prepare("SELECT username, account_type, email FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user_data = $user_stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

// Génération de l'URL publique pour le QR Code
$public_url = "https://jina.notechgroup.com/consult-card-visit/?identify=" . $profile['identify'];
$qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($public_url);

// 2. Récupération des données communes (Réseaux sociaux & Compétences)
$socials_stmt = $pdo->prepare("SELECT plateforme, url, type_reseau FROM user_socials WHERE user_id = ?");
$socials_stmt->execute([$user_id]);
$socials = $socials_stmt->fetchAll(PDO::FETCH_ASSOC);

$comp_stmt = $pdo->prepare("SELECT competence FROM user_competences WHERE user_id = ?");
$comp_stmt->execute([$user_id]);
$competences = $comp_stmt->fetchAll(PDO::FETCH_COLUMN);

// Initialisation des variables spécifiques
$details = [];
$services = [];
$catalogues = [];

// 3. Récupération des données spécifiques selon le type de compte
if ($user_data['account_type'] === 'employer') {
    // Profil Employeur
    $stmt = $pdo->prepare("SELECT * FROM employment_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $details = $stmt->fetch();
} else {
    // Profil Freelance
    $stmt = $pdo->prepare("SELECT * FROM freelance_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $details = $stmt->fetch();
    
    // Services du Freelance
    $services_stmt = $pdo->prepare("SELECT titre FROM user_services WHERE user_id = ?");
    $services_stmt->execute([$user_id]);
    $services = $services_stmt->fetchAll(PDO::FETCH_COLUMN);

    // Produits / Catalogue du Freelance
    $catalogues_stmt = $pdo->prepare("SELECT nom_produit FROM user_catalogues WHERE user_id = ?");
    $catalogues_stmt->execute([$user_id]);
    $catalogues = $catalogues_stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Détermination dynamique de l'adresse pour la carte NT-Card
if ($user_data['account_type'] === 'employer') {
    $card_address = $details['adresse_entreprise'] ?? $details['adresse_bureau'] ?? 'Adresse non configurée';
} else {
    $card_address = $details['adresse_bureau'] ?? 'Adresse non configurée';
}

// Fonction d'aide pour générer l'icône selon la plateforme
function getSocialIcon($plateforme) {
    $platform = strtolower($plateforme);
    switch ($platform) {
        case 'facebook': return '<i class="fab fa-facebook-f" style="color: #1877F2;"></i>';
        case 'instagram': return '<i class="fab fa-instagram" style="color: #E1306C;"></i>';
        case 'twitter':
        case 'x': return '<i class="fab fa-x-twitter" style="color: #000000;"></i>';
        case 'linkedin': return '<i class="fab fa-linkedin-in" style="color: #0A66C2;"></i>';
        case 'github': return '<i class="fab fa-github" style="color: #333;"></i>';
        case 'youtube': return '<i class="fab fa-youtube" style="color: #FF0000;"></i>';
        case 'whatsapp': return '<i class="fab fa-whatsapp" style="color: #25D366;"></i>';
        default: return '<i class="fas fa-link" style="color: #0f2256;"></i>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | JINA</title>

    <link rel="shortcut icon" href="../assets/img/logo-jina.ico" type="image/x-icon">
    <!-- Bootstrap 5, FontAwesome & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- html2canvas pour l'export d'image -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
    :root {
        --jina-blue: #0f2256;
        --jina-yellow: #ffcc00;
        --light-bg: #f4f6f9;
        --text-dark: #1e293b;
        --border-color: #e2e8f0;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--light-bg);
        color: var(--text-dark);
    }

    .profile-header-wrapper {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 34, 86, 0.05);
        margin-bottom: 30px;
    }

    .cover-photo {
        height: 220px;
        background-image: url('<?php echo !empty($profile['photo_couverture']) ? '../' .$profile['photo_couverture'] : "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1200"; ?>');
        background-size: cover;
        background-position: center;
    }

    .profile-avatar-area {
        padding: 25px 30px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        border-top: 1px solid var(--border-color);
    }

    .profile-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-top: -75px;
        background-color: white;
    }

    .info-card {
        background: white;
        border: none;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(15, 34, 86, 0.04);
        height: 100%;
    }

    .info-card-title {
        color: var(--jina-blue);
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-row {
        display: flex;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .info-label {
        font-weight: 600;
        color: #64748b;
        width: 120px;
        flex-shrink: 0;
    }

    .info-value {
        word-break: break-word;
    }

    .jina-badge {
        background-color: #f1f5f9;
        color: var(--jina-blue);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin: 4px;
        border: 1px solid var(--border-color);
    }

    .social-link-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background-color: rgba(15, 34, 86, 0.05);
        color: var(--jina-blue);
        margin-right: 8px;
        text-decoration: none;
        font-size: 1.1rem;
        transition: all 0.2s;
    }

    .social-link-btn:hover {
        background-color: var(--jina-blue);
        color: white;
    }

    /* DESIGN DE LA CARTE VIRTUELLE INTEGRÉE */
    /* .physical-card-container {
        max-width: 340px;
        margin: 0 auto;
    } */

    /* DESIGN DE LA CARTE VIRTUELLE INTEGRÉE AVEC DIMENSIONS RÉELLES (1650x2550) */
    .physical-card-container {
        max-width: 400px;
        margin: 0 auto;
    }

    /* Format proportionnel d'affichage à l'écran (Ratio 1650 / 2550 = 0.647) */
    .nt-card {
        width: 100%;
        aspect-ratio: 1650 / 2550;
        background: #e9ecf5;
        border-radius: 28px;
        padding: 35px 30px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        color: #1a2238;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-sizing: border-box;
    }

    .nt-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nt-card-avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .nt-card-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
    }

    .nt-card-verified {
        position: absolute;
        bottom: 2px;
        right: 2px;
        background: #2563eb;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e9ecf5;
    }

    .nt-card-identity {
        flex: 1;
        padding-left: 14px;
        text-align: left;
    }

    .nt-card-name {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.2;
        color: #0f2256;
        margin-bottom: 2px;
        text-transform: uppercase;
    }

    .nt-card-sub {
        font-size: 0.85rem;
        font-weight: 700;
        color: #4b5563;
        font-style: italic;
    }

    .nt-card-logo img {
        max-width: 55px;
        height: auto;
        border-radius: 6px;
    }

    .nt-card-body-details {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(5px);
        border-radius: 16px;
        padding: 16px 18px;
        font-size: 0.9rem;
    }

    .nt-card-qr-area {
        position: relative;
        display: inline-block;
        background: white;
        padding: 16px;
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        margin: 0 auto;
    }

    .nt-card-qr-area img.qr-main {
        width: 140px;
        height: 140px;
        display: block;
    }

    .nt-card-qr-logo-centered {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 36px;
        height: 36px;
        background-color: #ffffff;
        padding: 3px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nt-card-qr-logo-centered img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 4px;
    }

    .nt-details-row {
        display: flex;
        align-items: flex-start;
        /* Aligne l'icône au début au lieu du centre */
        gap: 10px;
        padding: 6px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    .nt-details-row i {
        color: #475569;
        width: 16px;
        text-align: center;
        margin-top: 3px;
        /* Aligne parfaitement l'icône avec la première ligne */
    }

    .nt-details-row:last-child {
        border-bottom: none;
    }

    .nt-details-text {
        font-weight: 600;
        color: #334155;
        white-space: normal;
        /* Autorise le retour à la ligne */
        word-break: break-word;
        /* Casse les e-mails / adresses trop longs */
        overflow-wrap: anywhere;
        font-size: 0.82rem;
        /* Légèrement réduit si nécessaire */
    }

    .nt-card-socials-row {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    .nt-mini-social-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #0f2256;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        text-decoration: none;
    }

    .nt-card-footer-tag {
        font-size: 0.75rem;
        font-weight: 800;
        color: #94a3b8;
        letter-spacing: 2px;
        text-align: center;
    }
    </style>
</head>

<body>

    <?php require_once '../includes/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        <!-- HEADER PROFILE -->
        <div class="profile-header-wrapper">
            <div class="cover-photo"></div>
            <div class="profile-avatar-area">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <img src="<?php echo !empty($profile['photo_profil'])
                        ? '../' . $profile['photo_profil']
                        : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200'; ?>" alt="Avatar"
                        class="profile-avatar">
                    <div>
                        <h2 class="fw-bold m-0 text-dark">
                            <?php echo htmlspecialchars(($profile['prenom'] ?? '') . ' ' . ($profile['nom'] ?? '')); ?>
                        </h2>
                        <span class="badge bg-dark mt-1">
                            <?php echo $user_data['account_type'] === 'employer' ? "Employé d'entreprise" : "Freelance / Entrepreneur"; ?>
                        </span>
                    </div>
                </div>
                <div>
                    <a href="edit_profile.php" class="btn btn-warning btn-sm fw-bold px-3 py-2 rounded-3 text-dark">
                        <i class="fas fa-edit me-1"></i> Modifier mes infos
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- SECTION DETAILS (GAUCHE) -->
            <div class="col-md-8">
                <div class="info-card">
                    <h4 class="info-card-title"><i class="fas fa-user-circle"></i> Informations Personnelles</h4>

                    <div class="info-row">
                        <div class="info-label">Titre</div>
                        <div class="info-value fw-bold text-primary">
                            <?php echo !empty($profile['titre']) ? htmlspecialchars($profile['titre']) : 'Non renseigné'; ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Biographie</div>
                        <div class="info-value text-muted">
                            <?php echo !empty($profile['bio']) ? nl2br(htmlspecialchars($profile['bio'])) : 'Aucune biographie...'; ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($profile['tel_perso'] ?? 'Non renseigné'); ?>
                        </div>
                    </div>

                    <!-- Liste des compétences -->
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Compétences :</h6>
                        <?php if(!empty($competences)): ?>
                        <?php foreach($competences as $comp): ?>
                        <span class="badge bg-secondary me-1"><?php echo htmlspecialchars($comp); ?></span>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <span class="text-muted small">Aucune compétence enregistrée.</span>
                        <?php endif; ?>
                    </div>

                    <!-- BLOC SPÉCIFIQUE EMPLOYER -->
                    <?php if ($user_data['account_type'] === 'employer'): ?>
                    <h4 class="info-card-title mt-4"><i class="fas fa-building"></i> Profil Entreprise</h4>
                    <div class="info-row">
                        <div class="info-label">Entreprise</div>
                        <div class="info-value fw-bold">
                            <?php echo htmlspecialchars($details['nom_entreprise'] ?? 'Non renseigné'); ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Poste</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($details['poste'] ?? 'Non renseigné'); ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">À-propos</div>
                        <div class="info-value text-muted">
                            <?php echo htmlspecialchars($details['about_entreprise'] ?? 'Aucune description'); ?>
                        </div>
                    </div>

                    <!-- BLOC SPÉCIFIQUE FREELANCE -->
                    <?php else: ?>
                    <h4 class="info-card-title mt-4"><i class="fas fa-briefcase"></i> Détails d'Activité</h4>

                    <div class="info-row">
                        <div class="info-label">Nom Labo/Ent.</div>
                        <div class="info-value fw-bold">
                            <?php echo htmlspecialchars($details['nom_entreprise'] ?? 'Non spécifié'); ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Bureau</div>
                        <div class="info-value">
                            <?php echo !empty($details['adresse_bureau']) ? htmlspecialchars($details['adresse_bureau']) : 'Non spécifié'; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tél Bureau</div>
                        <div class="info-value">
                            <?php echo !empty($details['tel_bureau']) ? htmlspecialchars($details['tel_bureau']) : 'Non spécifié'; ?>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Services proposés :</h6>
                        <?php if(!empty($services)): ?>
                        <?php foreach($services as $service): ?>
                        <span class="jina-badge"><?php echo htmlspecialchars($service); ?></span>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <span class="text-muted">Aucun service renseigné.</span>
                        <?php endif; ?>
                    </div>

                    <!-- Catalogues / Produits -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">Mon Catalogue / Produits :</h6>
                        <?php if(!empty($catalogues)): ?>
                        <?php foreach($catalogues as $catalogue): ?>
                        <span class="jina-badge bg-white text-dark border-secondary">
                            <i class="fas fa-box-open me-1 text-secondary"></i>
                            <?php echo htmlspecialchars($catalogue); ?>
                        </span>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <span class="text-muted">Aucun catalogue ou produit ajouté.</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- SECTION DES RÉSEAUX SOCIAUX -->
                    <h4 class="info-card-title mt-4"><i class="fas fa-share-nodes"></i> Réseaux Sociaux</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary mb-2 small text-uppercase tracking-wider">
                                <i class="fas fa-user text-muted me-1"></i> Personnels
                            </h6>
                            <div class="d-flex flex-wrap gap-1">
                                <?php 
                                $has_perso = false;
                                foreach ($socials as $social) {
                                    if ($social['type_reseau'] === 'perso') {
                                        $has_perso = true;
                                        echo '<a href="'.htmlspecialchars($social['url']).'" class="social-link-btn" target="_blank">';
                                        echo getSocialIcon($social['plateforme']);
                                        echo '</a>';
                                    }
                                }
                                if (!$has_perso): echo '<span class="text-muted small italic">Aucun lien personnel</span>'; endif;
                                ?>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary mb-2 small text-uppercase tracking-wider">
                                <i class="fas fa-briefcase text-muted me-1"></i> Professionnels / Labo
                            </h6>
                            <div class="d-flex flex-wrap gap-1">
                                <?php 
                                $has_pro = false;
                                foreach ($socials as $social) {
                                    if ($social['type_reseau'] === 'entreprise' || $social['type_reseau'] === 'freelance') {
                                        $has_pro = true;
                                        echo '<a href="'.htmlspecialchars($social['url']).'" class="social-link-btn" target="_blank">';
                                        echo getSocialIcon($social['plateforme']);
                                        echo '</a>';
                                    }
                                }
                                if (!$has_pro): echo '<span class="text-muted small italic">Aucun lien professionnel</span>'; endif;
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SECTION SIDEBAR (DROITE) - CARTE NT-CARD -->
            <div class="col-md-4">
                <div class="physical-card-container text-center">

                    <!-- DÉBUT DE LA CARTE VIRTUELLE -->
                    <div class="nt-card mb-3" id="ntCardExport">
                        <div class="nt-card-header">
                            <div class="d-flex align-items-center">
                                <div class="nt-card-avatar-wrapper">
                                    <img src="<?php echo !empty($profile['photo_profil']) ? '../' .$profile['photo_profil'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200'; ?>"
                                        alt="Avatar" class="nt-card-avatar">
                                    <div class="nt-card-verified"><i class="fas fa-check"></i></div>
                                </div>
                                <div class="nt-card-identity">
                                    <div class="nt-card-name">
                                        <?php echo htmlspecialchars(($profile['prenom'] ?? '') . ' ' . ($profile['nom'] ?? '')); ?>
                                    </div>
                                    <div class="nt-card-sub">
                                        <?php echo htmlspecialchars($profile['titre'] ?? 'Membre JINA'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="nt-card-logo">
                                <img src="../assets/img/logo jina.jpeg" alt="Logo">
                            </div>
                        </div>

                        <!-- Bloc des Coordonnées Textuelles -->
                        <div class="nt-card-body-details text-start">
                            <div class="nt-details-row">
                                <i class="fas fa-phone-alt"></i>
                                <div class="nt-details-text">
                                    <?php echo htmlspecialchars($profile['tel_perso'] ?? '+243 000 000 000'); ?>
                                </div>
                            </div>
                            <div class="nt-details-row">
                                <i class="fas fa-envelope"></i>
                                <div class="nt-details-text"><?php echo htmlspecialchars($user_data['email']); ?></div>
                            </div>
                            <div class="nt-details-row">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="nt-details-text">
                                    <?php echo htmlspecialchars($card_address); ?>
                                </div>
                            </div>

                            <!-- Mini icônes horizontales - FILTRÉES UNIQUEMENT SUR 'perso' -->
                            <div class="nt-card-socials-row">
                                <?php 
                                $count_perso = 0;
                                foreach ($socials as $social): 
                                    if ($social['type_reseau'] === 'perso' && $count_perso < 8): 
                                        $count_perso++;
                                ?>
                                <a href="<?php echo htmlspecialchars($social['url']); ?>" class="nt-mini-social-icon"
                                    target="_blank">
                                    <?php 
                                    $plat = strtolower($social['plateforme']);
                                    if($plat == 'facebook') echo '<i class="fab fa-facebook-f"></i>';
                                    elseif($plat == 'instagram') echo '<i class="fab fa-instagram"></i>';
                                    elseif($plat == 'linkedin') echo '<i class="fab fa-linkedin-in"></i>';
                                    elseif($plat == 'whatsapp') echo '<i class="fab fa-whatsapp"></i>';
                                    elseif($plat == 'twitter' || $plat == 'x') echo '<i class="fab fa-x-twitter"></i>';
                                    else echo '<i class="fas fa-globe"></i>';
                                    ?>
                                </a>
                                <?php 
                                    endif; 
                                endforeach; 
                                if($count_perso === 0): 
                                ?>
                                <span class="text-muted" style="font-size:10px;">Aucun réseau personnel associé</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Zone QR Code Dynamique -->
                        <div class="nt-card-qr-area">
                            <img src="<?php echo $qr_code_url; ?>" alt="QR Code JINA" class="qr-main">
                            <div class="nt-card-qr-logo-centered">
                                <img src="../assets/img/logo jina.jpeg" alt="Logo QR">
                            </div>
                        </div>

                        <div class="nt-card-footer-tag">PRODUIT DE NOTECH</div>
                    </div>
                    <!-- FIN DE LA CARTE -->

                    <!-- Actions sous la carte -->
                    <button onclick="downloadCard()" class="btn btn-primary btn-sm w-100 rounded-3 mb-2 fw-bold">
                        <i class="fas fa-download me-1"></i> Télécharger ma Carte (PNG)
                    </button>
                    <a href="<?php echo $public_url; ?>" target="_blank"
                        class="btn btn-outline-secondary btn-sm w-100 rounded-3">
                        <i class="fas fa-link me-1"></i> Ouvrir le lien public
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../includes/footer.php'; ?>

    <!-- Script de capture d'image -->
    <script>
    function downloadCard() {
        const card = document.getElementById('ntCardExport');

        html2canvas(card, {
            width: card.offsetWidth,
            height: card.offsetHeight,
            scale: 1650 / card.offsetWidth,
            useCORS: true,
            logging: false
        }).then(canvas => {
            const outputCanvas = document.createElement('canvas');
            outputCanvas.width = 1650;
            outputCanvas.height = 2550;

            const ctx = outputCanvas.getContext('2d');
            ctx.drawImage(canvas, 0, 0, 1650, 2550);

            // Nom dynamique généré avec PHP
            <?php 
                $filename = 'Carte-JINA-' . preg_replace('/[^a-zA-Z0-9_-]/', '', ($profile['prenom'] ?? '') . '-' . ($profile['nom'] ?? 'User'));
            ?>
            const filename = "<?php echo $filename; ?>.png";

            const link = document.createElement('a');
            link.download = filename;
            link.href = outputCanvas.toDataURL('image/png', 1.0);
            link.click();
        });
    }
    </script>
</body>

</html>