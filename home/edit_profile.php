<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// 1. CHARGEMENT INITIAL DES DONNÉES DE L'UTILISATEUR
$user_stmt = $pdo->prepare("SELECT username, account_type, email FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user_data = $user_stmt->fetch();

$profile_stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
$profile_stmt->execute([$user_id]);
$profile = $profile_stmt->fetch();

// Compétences existantes
$comp_stmt = $pdo->prepare("SELECT competence FROM user_competences WHERE user_id = ?");
$comp_stmt->execute([$user_id]);
$current_competences = $comp_stmt->fetchAll(PDO::FETCH_COLUMN);

// Réseaux sociaux existants
$socials_stmt = $pdo->prepare("SELECT plateforme, url, type_reseau FROM user_socials WHERE user_id = ?");
$socials_stmt->execute([$user_id]);
$current_socials = $socials_stmt->fetchAll(PDO::FETCH_ASSOC);

$social_mapped = [];
foreach ($current_socials as $s) {
    $social_mapped[$s['type_reseau']][$s['plateforme']] = $s['url'];
}

$details = [];
$current_services = [];
$current_catalogues = [];

if ($user_data['account_type'] === 'employer') {
    $stmt = $pdo->prepare("SELECT * FROM employment_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $details = $stmt->fetch();
} else {
    $stmt = $pdo->prepare("SELECT * FROM freelance_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $details = $stmt->fetch();

    // Récupération complète des services pour les afficher dans le formulaire
    $services_stmt = $pdo->prepare("SELECT titre, description FROM user_services WHERE user_id = ?");
    $services_stmt->execute([$user_id]);
    $current_services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupération complète du catalogue produits
    $catalogues_stmt = $pdo->prepare("SELECT nom_produit, image_produit FROM user_catalogues WHERE user_id = ?");
    $catalogues_stmt->execute([$user_id]);
    $current_catalogues = $catalogues_stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 2. TRAITEMENT DE LA MISE À JOUR (SOUMISSION DU FORMULAIRE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Données du profil personnel
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $titre = $_POST['titre'] ?? null;
        $bio = $_POST['bio'] ?? null;
        $tel_perso = $_POST['tel_perso'] ?? null;

        $photo_profil = $profile['photo_profil'] ?? null;
        $photo_couverture = $profile['photo_couverture'] ?? null;

        // Upload Photo Profil
        if (!empty($_FILES['photo_profil']['name'])) {
            $target = "uploads/profil_" . $user_id . "_" . time() . "_" . basename($_FILES['photo_profil']['name']);
            if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $target)) {
                $photo_profil = $target;
            }
        }
        // Upload Photo Couverture
        if (!empty($_FILES['photo_couverture']['name'])) {
            $target = "uploads/cover_" . $user_id . "_" . time() . "_" . basename($_FILES['photo_couverture']['name']);
            if (move_uploaded_file($_FILES['photo_couverture']['tmp_name'], $target)) {
                $photo_couverture = $target;
            }
        }

        // Insertion / Mise à jour Table `profiles`
        if ($profile) {
            $up_prof = $pdo->prepare("UPDATE profiles SET nom = ?, prenom = ?, titre = ?, bio = ?, tel_perso = ?, photo_profil = ?, photo_couverture = ? WHERE user_id = ?");
            $up_prof->execute([$nom, $prenom, $titre, $bio, $tel_perso, $photo_profil, $photo_couverture, $user_id]);
        } else {
            $in_prof = $pdo->prepare("INSERT INTO profiles (user_id, nom, prenom, titre, bio, tel_perso, photo_profil, photo_couverture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $in_prof->execute([$user_id, $nom, $prenom, $titre, $bio, $tel_perso, $photo_profil, $photo_couverture]);
        }

        // Nettoyage et insertion des compétences
        $del_comp = $pdo->prepare("DELETE FROM user_competences WHERE user_id = ?");
        $del_comp->execute([$user_id]);
        if (!empty($_POST['activites_competences'])) {
            $comps = array_map('trim', explode(',', $_POST['activites_competences']));
            $in_comp = $pdo->prepare("INSERT INTO user_competences (user_id, competence) VALUES (?, ?)");
            foreach ($comps as $c) {
                if ($c !== '') $in_comp->execute([$user_id, $c]);
            }
        }

        // Nettoyage et réinsertion des Réseaux Sociaux
        $del_soc = $pdo->prepare("DELETE FROM user_socials WHERE user_id = ?");
        $del_soc->execute([$user_id]);
        $in_soc = $pdo->prepare("INSERT INTO user_socials (user_id, type_reseau, plateforme, url) VALUES (?, ?, ?, ?)");

        if (!empty($_POST['social_perso'])) {
            foreach ($_POST['social_perso'] as $plateforme => $url) {
                if (trim($url) !== '') $in_soc->execute([$user_id, 'perso', $plateforme, trim($url)]);
            }
        }

        // TRAITEMENT DYNAMIQUE SELON LE TYPE DE COMPTE
        if ($user_data['account_type'] === 'employer') {
            $nom_entreprise = $_POST['nom_entreprise'] ?? null;
            $poste_actuel = $_POST['poste_actuel'] ?? null;
            $apropos_entreprise = $_POST['apropos_entreprise'] ?? null;

            if ($details) {
                $up_emp = $pdo->prepare("UPDATE employment_details SET nom_entreprise = ?, poste = ?, about_entreprise = ? WHERE user_id = ?");
                $up_emp->execute([$nom_entreprise, $poste_actuel, $apropos_entreprise, $user_id]);
            } else {
                $in_emp = $pdo->prepare("INSERT INTO employment_details (user_id, nom_entreprise, poste, about_entreprise) VALUES (?, ?, ?, ?)");
                $in_emp->execute([$user_id, $nom_entreprise, $poste_actuel, $apropos_entreprise]);
            }

            if (!empty($_POST['social_entreprise'])) {
                foreach ($_POST['social_entreprise'] as $plateforme => $url) {
                    if (trim($url) !== '') $in_soc->execute([$user_id, 'entreprise', $plateforme, trim($url)]);
                }
            }
        } else {
            // Section Freelance / Entrepreneur
            $nom_entreprise_free = $_POST['nom_entreprise_free'] ?? null;
            $desc_entreprise_free = $_POST['desc_entreprise_free'] ?? null;
            $tel_bureau = $_POST['tel_bureau'] ?? null;
            $adresse_bureau = $_POST['adresse_bureau'] ?? null;
            $logo_entreprise = $details['logo_entreprise'] ?? null;

            if (!empty($_FILES['logo_entreprise_free']['name'])) {
                $target = "uploads/logo_" . $user_id . "_" . time() . "_" . basename($_FILES['logo_entreprise_free']['name']);
                if (move_uploaded_file($_FILES['logo_entreprise_free']['tmp_name'], $target)) {
                    $logo_entreprise = $target;
                }
            }

            if ($details) {
                $up_free = $pdo->prepare("UPDATE freelance_details SET nom_entreprise = ?, desc_entreprise = ?, tel_bureau = ?, adresse_bureau = ?, logo_entreprise = ? WHERE user_id = ?");
                $up_free->execute([$nom_entreprise_free, $desc_entreprise_free, $tel_bureau, $adresse_bureau, $logo_entreprise, $user_id]);
            } else {
                $in_free = $pdo->prepare("INSERT INTO freelance_details (user_id, nom_entreprise, desc_entreprise, tel_bureau, adresse_bureau, logo_entreprise) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $in_free->execute([$user_id, $nom_entreprise_free, $desc_entreprise_free, $tel_bureau, $adresse_bureau, $logo_entreprise]);
            }

            if (!empty($_POST['social_entreprise_free'])) {
                foreach ($_POST['social_entreprise_free'] as $plateforme => $url) {
                    if (trim($url) !== '') $in_soc->execute([$user_id, 'freelance', $plateforme, trim($url)]);
                }
            }

            // Gestion Unifiée des Services (Suppression globale puis réécriture complète)
            $del_serv = $pdo->prepare("DELETE FROM user_services WHERE user_id = ?");
            $del_serv->execute([$user_id]);
            if (!empty($_POST['service_titre'])) {
                $in_serv = $pdo->prepare("INSERT INTO user_services (user_id, titre, description) VALUES (?, ?, ?)");
                foreach ($_POST['service_titre'] as $index => $titre_service) {
                    $desc_service = $_POST['service_desc'][$index] ?? '';
                    if (trim($titre_service) !== '') {
                        $in_serv->execute([$user_id, trim($titre_service), trim($desc_service)]);
                    }
                }
            }

            // Gestion Unifiée du Catalogue Produits
            $del_cat = $pdo->prepare("DELETE FROM user_catalogues WHERE user_id = ?");
            $del_cat->execute([$user_id]);
            if (!empty($_POST['catalogue_nom'])) {
                $in_cat = $pdo->prepare("INSERT INTO user_catalogues (user_id, nom_produit, image_produit) VALUES (?, ?, ?)");
                foreach ($_POST['catalogue_nom'] as $index => $nom_produit) {
                    
                    // On récupère l'ancienne image stockée dans un input hidden s'il n'y a pas d'upload frais
                    $img_produit = $_POST['old_catalogue_image'][$index] ?? '';
                    
                    if (!empty($_FILES['catalogue_image']['name'][$index])) {
                        $target = "uploads/prod_" . $user_id . "_" . time() . "_" . basename($_FILES['catalogue_image']['name'][$index]);
                        if (move_uploaded_file($_FILES['catalogue_image']['tmp_name'][$index], $target)) {
                            $img_produit = $target;
                        }
                    }
                    if (trim($nom_produit) !== '') {
                        $in_cat->execute([$user_id, trim($nom_produit), $img_produit]);
                    }
                }
            }
        }

        $pdo->commit();
        header("Location: edit_profile.php?success=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_msg = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}

if (isset($_GET['success'])) {
    $success_msg = "Votre profil a été mis à jour avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon Profil - JINA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
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
        color: #1e293b;
    }

    .edit-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(15, 34, 86, 0.06);
        padding: 35px;
        margin-top: 30px;
    }

    .section-title {
        color: var(--jina-blue);
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 8px;
        margin-top: 25px;
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
    }

    .btn-jina {
        background-color: var(--jina-blue);
        color: white;
        font-weight: 700;
        border-radius: 10px;
        padding: 12px;
    }

    .btn-jina:hover {
        background-color: #07112c;
        color: var(--jina-yellow);
    }

    .preview-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
    }
    </style>
</head>

<body>

    <?php require_once '../includes/navbar.php'; ?>

    <div class="container my-5">
        <div class="edit-card mx-auto" style="max-width: 1250px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-user-edit me-2 text-primary"></i>Modifier mon profil
                </h2>
                <a href="dashboard.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>
                    Retour dashboard</a>
            </div>

            <?php if(!empty($success_msg)): ?>
            <div class="alert alert-success border-0 shadow-sm mb-4"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            <?php if(!empty($error_msg)): ?>
            <div class="alert alert-danger border-0 shadow-sm mb-4"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="edit_profile.php" method="POST" enctype="multipart/form-data">

                <h5 class="section-title"><i class="fas fa-id-card me-2"></i>1. Informations Personnelles</h5>

                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <label class="form-label small fw-bold">Nom *</label>
                        <input type="text" name="nom" class="form-control"
                            value="<?php echo htmlspecialchars($profile['nom'] ?? ''); ?>" required>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-bold">Prénom *</label>
                        <input type="text" name="prenom" class="form-control"
                            value="<?php echo htmlspecialchars($profile['prenom'] ?? ''); ?>" required>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-bold">Titre (ex: Ir, Dr, Freelance Dev)</label>
                        <input type="text" name="titre" class="form-control"
                            value="<?php echo htmlspecialchars($profile['titre'] ?? ''); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Biographie courte</label>
                    <textarea name="bio" class="form-control"
                        rows="2"><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
                </div>

                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Téléphone Personnel *</label>
                        <input type="tel" name="tel_perso" class="form-control"
                            value="<?php echo htmlspecialchars($profile['tel_perso'] ?? ''); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Activités / Compétences (séparées par des virgules)
                            *</label>
                        <input type="text" name="activites_competences" class="form-control"
                            value="<?php echo htmlspecialchars(implode(', ', $current_competences)); ?>"
                            placeholder="PHP, UI/UX, Dev..." required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Photo de Profil</label>
                        <div class="d-flex gap-2 align-items-center">
                            <?php if(!empty($profile['photo_profil'])): ?><img
                                src="<?php echo $profile['photo_profil']; ?>" class="preview-img"><?php endif; ?>
                            <input type="file" name="photo_profil" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Photo de Couverture</label>
                        <div class="d-flex gap-2 align-items-center">
                            <?php if(!empty($profile['photo_couverture'])): ?><img
                                src="<?php echo $profile['photo_couverture']; ?>" class="preview-img"><?php endif; ?>
                            <input type="file" name="photo_couverture" class="form-control">
                        </div>
                    </div>
                </div>

                <h5 class="section-title"><i class="fas fa-hashtag me-2"></i>2. Liens Sociaux Personnels</h5>
                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <input type="url" name="social_perso[facebook]" class="form-control form-control-sm"
                            placeholder="URL Facebook"
                            value="<?php echo htmlspecialchars($social_mapped['perso']['facebook'] ?? ''); ?>">
                    </div>
                    <div class="col-sm-6">
                        <input type="url" name="social_perso[instagram]" class="form-control form-control-sm"
                            placeholder="URL Instagram"
                            value="<?php echo htmlspecialchars($social_mapped['perso']['instagram'] ?? ''); ?>">
                    </div>
                    <div class="col-sm-6">
                        <input type="url" name="social_perso[linkedin]" class="form-control form-control-sm"
                            placeholder="URL LinkedIn"
                            value="<?php echo htmlspecialchars($social_mapped['perso']['linkedin'] ?? ''); ?>">
                    </div>
                    <div class="col-sm-6">
                        <input type="url" name="social_perso[whatsapp]" class="form-control form-control-sm"
                            placeholder="Lien d'API WhatsApp"
                            value="<?php echo htmlspecialchars($social_mapped['perso']['whatsapp'] ?? ''); ?>">
                    </div>
                </div>

                <?php if($user_data['account_type'] === 'employer'): ?>
                <h5 class="section-title"><i class="fas fa-building me-2"></i>3. Informations d'Entreprise (Salarié)
                </h5>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Nom de l'entreprise *</label>
                        <input type="text" name="nom_entreprise" class="form-control"
                            value="<?php echo htmlspecialchars($details['nom_entreprise'] ?? ''); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Votre poste actuel *</label>
                        <input type="text" name="poste_actuel" class="form-control"
                            value="<?php echo htmlspecialchars($details['poste'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">À propos de l'entreprise *</label>
                    <textarea name="apropos_entreprise" class="form-control" rows="2"
                        required><?php echo htmlspecialchars($details['about_entreprise'] ?? ''); ?></textarea>
                </div>

                <label class="form-label small fw-bold text-muted">Réseaux Sociaux de l'Entreprise</label>
                <div class="row g-2 mb-3">
                    <div class="col-sm-6"><input type="url" name="social_entreprise[facebook]"
                            class="form-control form-control-sm" placeholder="Facebook pro"
                            value="<?php echo htmlspecialchars($social_mapped['entreprise']['facebook'] ?? ''); ?>">
                    </div>
                    <div class="col-sm-6"><input type="url" name="social_entreprise[linkedin]"
                            class="form-control form-control-sm" placeholder="LinkedIn pro"
                            value="<?php echo htmlspecialchars($social_mapped['entreprise']['linkedin'] ?? ''); ?>">
                    </div>
                </div>

                <?php else: ?>
                <h5 class="section-title"><i class="fas fa-briefcase me-2"></i>3. Informations d'Activité Pro
                    (Freelance)</h5>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nom de votre marque / établissement *</label>
                    <input type="text" name="nom_entreprise_free" class="form-control"
                        value="<?php echo htmlspecialchars($details['nom_entreprise'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Description courte d'activité *</label>
                    <textarea name="desc_entreprise_free" class="form-control" rows="2"
                        required><?php echo htmlspecialchars($details['desc_entreprise'] ?? ''); ?></textarea>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Téléphone Bureau</label>
                        <input type="tel" name="tel_bureau" class="form-control"
                            value="<?php echo htmlspecialchars($details['tel_bureau'] ?? ''); ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Adresse Bureau</label>
                        <input type="text" name="adresse_bureau" class="form-control"
                            value="<?php echo htmlspecialchars($details['adresse_bureau'] ?? ''); ?>">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Logo de la marque</label>
                    <div class="d-flex gap-2 align-items-center">
                        <?php if(!empty($details['logo_entreprise'])): ?><img
                            src="<?php echo $details['logo_entreprise']; ?>" class="preview-img"><?php endif; ?>
                        <input type="file" name="logo_entreprise_free" class="form-control">
                    </div>
                </div>

                <label class="form-label small fw-bold text-muted">Réseaux Sociaux de l'Activité</label>
                <div class="row g-2 mb-4">
                    <div class="col-sm-6"><input type="url" name="social_entreprise_free[facebook]"
                            class="form-control form-control-sm" placeholder="Facebook Établissement"
                            value="<?php echo htmlspecialchars($social_mapped['freelance']['facebook'] ?? ''); ?>">
                    </div>
                    <div class="col-sm-6"><input type="url" name="social_entreprise_free[linkedin]"
                            class="form-control form-control-sm" placeholder="LinkedIn Établissement"
                            value="<?php echo htmlspecialchars($social_mapped['freelance']['linkedin'] ?? ''); ?>">
                    </div>
                </div>

                <div class="mb-4" id="services-area">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold small m-0 text-dark"><i class="fas fa-concierge-bell me-1"></i> Mes
                            Services :</label>
                        <button type="button" class="btn btn-xs btn-outline-primary btn-sm"
                            onclick="addServiceField()"><i class="fas fa-plus"></i> Ajouter</button>
                    </div>

                    <?php foreach($current_services as $srv): ?>
                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-sm-5"><input type="text" name="service_titre[]"
                                class="form-control form-control-sm"
                                value="<?php echo htmlspecialchars($srv['titre']); ?>" required></div>
                        <div class="col-sm-6"><input type="text" name="service_desc[]"
                                class="form-control form-control-sm"
                                value="<?php echo htmlspecialchars($srv['description']); ?>"></div>
                        <div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger shadow-sm"
                                onclick="this.parentElement.parentElement.remove()"><i
                                    class="fas fa-times"></i></button></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4" id="catalogue-area">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold small m-0 text-dark"><i class="fas fa-box-open me-1"></i> Mon Catalogue
                            Produits :</label>
                        <button type="button" class="btn btn-xs btn-outline-primary btn-sm"
                            onclick="addCatalogueField()"><i class="fas fa-plus"></i> Ajouter</button>
                    </div>

                    <?php foreach($current_catalogues as $index => $cat): ?>
                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-sm-4">
                            <input type="text" name="catalogue_nom[]" class="form-control form-control-sm"
                                value="<?php echo htmlspecialchars($cat['nom_produit']); ?>" required>
                        </div>
                        <div class="col-sm-5">
                            <input type="file" name="catalogue_image[]" class="form-control form-control-sm">
                            <input type="hidden" name="old_catalogue_image[]"
                                value="<?php echo $cat['image_produit']; ?>">
                        </div>
                        <div class="col-sm-2 text-center">
                            <?php if(!empty($cat['image_produit'])): ?><img src="<?php echo $cat['image_produit']; ?>"
                                class="preview-img" style="width:35px;height:35px;"><?php endif; ?>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-sm btn-danger shadow-sm"
                                onclick="this.parentElement.parentElement.remove()"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-jina w-100 mt-4 shadow-sm">
                    <i class="fas fa-save me-2"></i>Sauvegarder les modifications
                </button>
            </form>
        </div>
    </div>

    <script>
    function addServiceField() {
        const area = document.getElementById('services-area');
        const div = document.createElement('div');
        div.className = "row g-2 mb-2 align-items-center";
        div.innerHTML = `
            <div class="col-sm-5"><input type="text" name="service_titre[]" class="form-control form-control-sm" placeholder="Nom du service" required></div>
            <div class="col-sm-6"><input type="text" name="service_desc[]" class="form-control form-control-sm" placeholder="Description courte"></div>
            <div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times"></i></button></div>
        `;
        area.appendChild(div);
    }

    function addCatalogueField() {
        const area = document.getElementById('catalogue-area');
        const div = document.createElement('div');
        div.className = "row g-2 mb-2 align-items-center";
        div.innerHTML = `
            <div class="col-sm-4"><input type="text" name="catalogue_nom[]" class="form-control form-control-sm" placeholder="Nom du produit" required></div>
            <div class="col-sm-6"><input type="file" name="catalogue_image[]" class="form-control form-control-sm" required></div>
            <input type="hidden" name="old_catalogue_image[]" value="">
            <div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times"></i></button></div>
        `;
        area.appendChild(div);
    }
    </script>
    <?php require_once '../includes/footer.php'; ?>
</body>

</html>