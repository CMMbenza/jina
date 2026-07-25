<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - JINA carte numérique</title>

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
        --border-color: #e2e8f0;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--light-bg);
        color: var(--text-dark);
        min-height: 100vh;
    }

    .register-card {
        background: white;
        border: none;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(15, 34, 86, 0.06);
        padding: 40px 35px;
        position: relative;
        overflow: hidden;
    }

    @media (max-width: 576px) {
        .register-card {
            padding: 25px 20px;
        }
    }

    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--jina-blue) 0%, var(--jina-yellow) 100%);
    }

    .login-logo {
        max-height: 65px;
        width: auto;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .brand-title {
        color: var(--jina-blue);
        font-weight: 800;
    }

    .section-step {
        color: var(--jina-blue);
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 25px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-block {
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
        margin-bottom: 6px;
        margin-top: 8px;
    }

    .form-control {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 11px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: white;
    }

    .form-control:focus {
        border-color: var(--jina-blue);
        box-shadow: 0 0 0 3px rgba(15, 34, 86, 0.1);
    }

    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #64748b;
        z-index: 10;
    }

    .btn-jina-primary {
        background-color: var(--jina-blue);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .btn-jina-primary:hover {
        background-color: #07112c;
        color: var(--jina-yellow);
    }

    .btn-add-field {
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 8px;
        color: var(--jina-blue);
        border-color: var(--jina-blue);
    }

    .btn-add-field:hover {
        background-color: var(--jina-blue);
        color: white;
    }

    /* .social-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    } */

    .type-selector-box {
        background: #fffbfa;
        border: 2px dashed #cbd5e1;
        transition: all 0.3s ease;
    }

    .type-selector-box:has(input:checked) {
        border-color: var(--jina-blue);
        background: rgba(15, 34, 86, 0.03);
    }

    /* @media (max-width: 576px) {
        .social-grid {
            grid-template-columns: 1fr;
        }
    } */

    .d-none {
        display: none !important;
    }

    .fade-in {
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="register-card mx-auto" style="max-width: 850px;">

            <div class="text-center mb-4">
                <img src="../assets/img/logo jina.jpeg" alt="Logo JINA" class="login-logo rounded">
                <h2 class="brand-title">Créer votre profil</h2>
                <p class="text-muted">Rejoignez l'écosystème JINA en quelques instants</p>
            </div>

            <form action="../service/save_user.php" method="POST" enctype="multipart/form-data">

                <h4 class="section-step"><i class="fas fa-key text-muted fs-5"></i> 0. Identifiants de connexion</h4>
                <div class="form-block">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur"
                            required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Adresse email" required>
                    </div>
                    <div class="mb-2">
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Mot de passe" required style="padding-right: 45px;">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                        </div>
                    </div>
                </div>

                <h4 class="section-step"><i class="fas fa-user text-muted fs-5"></i> 1. Profil Personnel</h4>
                <div class="form-block">
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Photo Profil *</label>
                            <input type="file" name="photo_profil" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Photo Couverture *</label>
                            <input type="file" name="photo_couverture" class="form-control" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <input type="text" name="nom" class="form-control" placeholder="Nom *" required>
                        </div>
                        <!-- <div class="d-none col-sm-4">
                            <input type="text" name="post_nom" class="form-control" placeholder="Post-nom *" required>
                        </div> -->
                        <div class="col-sm-6">
                            <input type="text" name="prenom" class="form-control" placeholder="Prénom *" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="titre" class="form-control"
                            placeholder="Titre (ex: Dr, Ir, Maître...) [Optionnel]">
                    </div>
                    <div class="mb-3">
                        <textarea name="bio" class="form-control" placeholder="Votre biographie courte..."
                            rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="activites_competences" class="form-control"
                            placeholder="Vos activités / compétences *" required>
                        <small>Veuillez séparér par des virgules <b>virgule (,)</b></small>
                    </div>

                    <div class="mb-3">
                        <input type="tel" name="tel_perso" class="form-control" placeholder="Téléphone personnel *"
                            required>
                    </div>

                    <label class="form-label small text-muted">Réseaux Sociaux & Internet Personnels (Optionnel)</label>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i
                                        class="fab fa-facebook-f"></i></span>
                                <input type="url" name="social_perso[facebook]" class="form-control border-start-0 ps-1"
                                    placeholder="Facebook">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i class="fab fa-x-twitter"></i></span>
                                <input type="url" name="social_perso[x]" class="form-control border-start-0 ps-1"
                                    placeholder="X (Twitter)">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i class="fab fa-instagram"></i></span>
                                <input type="url" name="social_perso[instagram]"
                                    class="form-control border-start-0 ps-1" placeholder="Instagram">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i
                                        class="fab fa-linkedin-in"></i></span>
                                <input type="url" name="social_perso[linkedin]" class="form-control border-start-0 ps-1"
                                    placeholder="LinkedIn">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i class="fab fa-youtube"></i></span>
                                <input type="url" name="social_perso[youtube]" class="form-control border-start-0 ps-1"
                                    placeholder="YouTube">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i class="fab fa-tiktok"></i></span>
                                <input type="url" name="social_perso[tiktok]" class="form-control border-start-0 ps-1"
                                    placeholder="TikTok">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-group" style="grid-column: span 2;">
                            <span class="input-group-text bg-white text-muted"
                                style="width:40px; justify-content:center;">
                                <i class="fas fa-fingerprint"></i>
                            </span>

                            <!-- Identifiant -->
                            <input type="hidden" name="identify" id="identify" class="form-control border-start-0 ps-1"
                                readonly>

                            <!-- URL générée -->
                            <input type="text" name="social_perso[site]" id="social_site"
                                class="form-control border-start-0 ps-1" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-block type-selector-box text-center p-4 my-4 rounded-3">
                    <h5 class="fw-bold mb-3" style="color: var(--jina-blue);">Quel est votre statut actuel ?</h5>
                    <div class="d-flex justify-content-center gap-4">
                        <div class="form-check form-check-inline m-0">
                            <input class="form-check-input" type="radio" name="account_type" id="typeEmployer"
                                value="employer" onchange="handleTypeChange()" required>
                            <label class="form-check-label fw-semibold" for="typeEmployer">Salarié en Entreprise</label>
                        </div>
                        <div class="form-check form-check-inline m-0">
                            <input class="form-check-input" type="radio" name="account_type" id="typeFreelance"
                                value="freelance" onchange="handleTypeChange()">
                            <label class="form-check-label fw-semibold" for="typeFreelance">Entrepreneur /
                                Freelance</label>
                        </div>
                    </div>
                </div>

                <div id="section-employer" class="d-none fade-in">
                    <h4 class="section-step"><i class="fas fa-building text-muted fs-5"></i> 2. Profil Entreprise
                        (Salarié)</h4>
                    <div class="form-block">
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <input type="text" name="nom_entreprise" class="form-control"
                                    placeholder="Nom de l'entreprise *">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="poste_actuel" class="form-control"
                                    placeholder="Votre poste actuel *">
                            </div>
                        </div>

                        <div class="mb-3">
                            <textarea name="apropos_entreprise" class="form-control"
                                placeholder="À propos de l'entreprise (max 250caractères) *" rows="2"
                                maxlength="250"></textarea>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="adress_bureau" class="form-control"
                                    placeholder="Adresse du bureau *">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Logo de l'entreprise [Optionnel]</label>
                            <input type="file" name="logo_entreprise" class="form-control">
                        </div>

                        <label class="form-label small text-muted mb-2">Réseaux Sociaux & Internet de l'Entreprise
                            (Optionnel)</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-facebook-f"></i></span>
                                    <input type="url" name="social_entreprise[facebook]"
                                        class="form-control border-start-0 ps-1" placeholder="Facebook de l'entreprise">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-x-twitter"></i></span>
                                    <input type="url" name="social_entreprise[x]"
                                        class="form-control border-start-0 ps-1"
                                        placeholder="X / Twitter de l'entreprise">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-instagram"></i></span>
                                    <input type="url" name="social_entreprise[instagram]"
                                        class="form-control border-start-0 ps-1"
                                        placeholder="Instagram de l'entreprise">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-linkedin-in"></i></span>
                                    <input type="url" name="social_entreprise[linkedin]"
                                        class="form-control border-start-0 ps-1" placeholder="LinkedIn de l'entreprise">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-youtube"></i></span>
                                    <input type="url" name="social_entreprise[youtube]"
                                        class="form-control border-start-0 ps-1" placeholder="YouTube de l'entreprise">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-tiktok"></i></span>
                                    <input type="url" name="social_entreprise[tiktok]"
                                        class="form-control border-start-0 ps-1" placeholder="TikTok de l'entreprise">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group" style="grid-column: span 2;">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i class="fas fa-globe"></i></span>
                                <input type="text" name="social_entreprise[site]"
                                    class="form-control border-start-0 ps-1"
                                    placeholder="Site Internet de l'entreprise">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-freelance" class="d-none fade-in">
                    <h4 class="section-step"><i class="fas fa-briefcase text-muted fs-5"></i> 3. Entrepreneur &
                        Freelance</h4>
                    <div class="form-block">

                        <!-- Infos Entreprise pour Freelance -->
                        <div class="mb-3">
                            <input type="text" name="nom_entreprise_free" class="form-control"
                                placeholder="Nom de votre entreprise / marque *">
                        </div>
                        <div class="mb-3">
                            <textarea name="desc_entreprise_free" class="form-control"
                                placeholder="Description de l'entreprise (max 250caractères) *" rows="2"
                                maxlength="250"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Logo de votre entreprise / marque [Optionnel]</label>
                            <input type="file" name="logo_entreprise_free" class="form-control">
                        </div>

                        <!-- Réseaux Sociaux Entreprise pour Freelance -->
                        <label class="form-label small text-muted mb-2">Réseaux Sociaux & Internet de votre
                            Entreprise
                            (Optionnel)</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-facebook-f"></i></span>
                                    <input type="url" name="social_entreprise_free[facebook]"
                                        class="form-control border-start-0 ps-1" placeholder="Facebook pro">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-x-twitter"></i></span>
                                    <input type="url" name="social_entreprise_free[x]"
                                        class="form-control border-start-0 ps-1" placeholder="X / Twitter pro">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-instagram"></i></span>
                                    <input type="url" name="social_entreprise_free[instagram]"
                                        class="form-control border-start-0 ps-1" placeholder="Instagram pro">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-linkedin-in"></i></span>
                                    <input type="url" name="social_entreprise_free[linkedin]"
                                        class="form-control border-start-0 ps-1" placeholder="LinkedIn pro">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-youtube"></i></span>
                                    <input type="url" name="social_entreprise_free[youtube]"
                                        class="form-control border-start-0 ps-1" placeholder="YouTube pro">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"
                                        style="width:40px; justify-content: center;"><i
                                            class="fab fa-tiktok"></i></span>
                                    <input type="url" name="social_entreprise_free[tiktok]"
                                        class="form-control border-start-0 ps-1" placeholder="TikTok pro">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group mb-1" style="grid-column: span 2;">
                                <span class="input-group-text bg-white text-muted"
                                    style="width:40px; justify-content: center;"><i class="fas fa-globe"></i></span>
                                <input type="text" name="social_entreprise_free[site]"
                                    class="form-control border-start-0 ps-1" placeholder="Site Internet pro">
                            </div>
                        </div>

                        <hr class="text-muted opacity-25 my-4">

                        <div id="services-area" class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label m-0 fw-bold">Services proposés :</label>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-add-field"
                                    onclick="addServiceField()">
                                    <i class="fas fa-plus me-1"></i> Ajouter un Service
                                </button>
                            </div>
                        </div>

                        <div id="catalogue-area" class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label m-0 fw-bold">Catalogues / Produits :</label>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-add-field"
                                    onclick="addCatalogueField()">
                                    <i class="fas fa-plus me-1"></i> Ajouter au Catalogue
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="tel" name="tel_bureau" class="form-control" placeholder="Téléphone bureau">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="adresse_bureau" class="form-control" placeholder="Adresse bureau">
                        </div>

                    </div>
                </div>

                <button type="submit" name="submit" id="submit" class="btn btn-jina-primary w-100 mt-2 shadow-sm">
                    <i class="fas fa-check-circle me-2"></i> Enregistrer et créer mon profil
                </button>
            </form>
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4 form-links">
                <a href="login.php">Se connecter à votre compte</a>
                <a href="forgot-password.php">Mot de passe oublié ?</a>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        const p = document.getElementById("password");
        const icon = document.querySelector(".password-toggle");
        if (p.type === "password") {
            p.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            p.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    // function handleTypeChange() {
    //     const isEmployer = document.getElementById("typeEmployer").checked;
    //     const isFreelance = document.getElementById("typeFreelance").checked;

    //     const secEmployer = document.getElementById("section-employer");
    //     const secFreelance = document.getElementById("section-freelance");

    //     if (isEmployer) {
    //         secEmployer.classList.remove("d-none");
    //         secFreelance.classList.add("d-none");
    //         toggleRequiredFields('employer');
    //     } else if (isFreelance) {
    //         secFreelance.classList.remove("d-none");
    //         secEmployer.classList.add("d-none");
    //         toggleRequiredFields('freelance');
    //     }
    // }

    function handleTypeChange() {
        const isEmployer = document.getElementById('typeEmployer').checked;
        const isFreelance = document.getElementById('typeFreelance').checked;

        const sectionEmployer = document.getElementById('section-employer');
        const sectionFreelance = document.getElementById('section-freelance');

        if (isEmployer) {
            sectionEmployer.classList.remove('d-none');
            sectionFreelance.classList.add('d-none');

            // Activer le required pour l'employé, désactiver pour le freelance
            toggleRequired(sectionEmployer, true);
            toggleRequired(sectionFreelance, false);
        } else if (isFreelance) {
            sectionFreelance.classList.remove('d-none');
            sectionEmployer.classList.add('d-none');

            // Activer le required pour le freelance, désactiver pour l'employé
            toggleRequired(sectionFreelance, true);
            toggleRequired(sectionEmployer, false);
        }
    }

    // Fonction utilitaire pour basculer les attributs requis sur les éléments visibles
    function toggleRequired(parentSection, shouldBeRequired) {
        // On cible les inputs et textareas qui ont une classe ou une structure nécessitant une validation
        const inputs = parentSection.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            // Optionnel : N'applique le requis que si le placeholder contient une étoile '*'
            if (input.placeholder && input.placeholder.includes('*')) {
                if (shouldBeRequired) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            }
        });
    }

    function toggleRequiredFields(type) {
        // Inputs Salarié
        const nomEntreprise = document.querySelector('input[name="nom_entreprise"]');
        const posteActuel = document.querySelector('input[name="poste_actuel"]');
        const aproposEntreprise = document.querySelector('textarea[name="apropos_entreprise"]');

        // Inputs Freelance
        const nomEntrepriseFree = document.querySelector('input[name="nom_entreprise_free"]');
        const descEntrepriseFree = document.querySelector('textarea[name="desc_entreprise_free"]');

        if (type === 'employer') {
            nomEntreprise.required = true;
            posteActuel.required = true;
            aproposEntreprise.required = true;

            nomEntrepriseFree.required = false;
            descEntrepriseFree.required = false;
        } else {
            nomEntreprise.required = false;
            posteActuel.required = false;
            aproposEntreprise.required = false;

            nomEntrepriseFree.required = true;
            descEntrepriseFree.required = true;
        }
    }

    function addServiceField() {
        const area = document.getElementById('services-area');
        const div = document.createElement('div');
        div.className = "p-3 border rounded bg-white mb-2 position-relative animate-fade-in";
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-sm-6">
                    <input type="text" name="service_titre[]" class="form-control form-control-sm" placeholder="Titre du service" required>
                </div>
                <div class="col-sm-6">
                    <input type="text" name="service_desc[]" maxlength="250" class="form-control form-control-sm" placeholder="Description (max 250car.)" required>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="padding: 2px 6px;" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        area.appendChild(div);
    }

    function addCatalogueField() {
        const area = document.getElementById('catalogue-area');
        const div = document.createElement('div');
        div.className = "p-3 border rounded bg-white mb-2 position-relative animate-fade-in";
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-sm-6">
                    <input type="text" name="catalogue_nom[]" class="form-control form-control-sm" placeholder="Nom du produit" required>
                </div>
                <div class="col-sm-6">
                    <input type="file" name="catalogue_image[]" class="form-control">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="padding: 2px 6px;" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        area.appendChild(div);
    }

    function generateIdentify(length = 10) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';

        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        return result;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const identify = generateIdentify();

        document.getElementById('identify').value = identify;
        document.getElementById('social_site').value =
            'https://jina.notechgroup.com/consult-card-visit/?' + identify;
    });
    </script>
</body>

</html>