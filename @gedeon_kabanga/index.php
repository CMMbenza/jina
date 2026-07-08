<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr Gédéon KABANGA MALULA | PDG NOTECH Group</title>
    <meta name="description" content="Site professionnel du Dr Gédéon KABANGA MALULA - Médecin, Entrepreneur, Innovateur et PDG de NOTECH Group à Kinshasa, RDC.">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* --- CHARTE GRAPHIQUE JINA & NOTECH --- */
        :root {
            --jina-blue: #0f2256;      /* Bleu nuit profond du logo */
            --jina-yellow: #ffcc00;    /* Jaune Or lumineux du logo */
            --light-bg: #f5f7fa;       /* Fond clair moderne pour la prod */
            --text-dark: #1e293b;      /* Couleur de texte lisible */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* --- UTILITIES & ACCENTS --- */
        .text-jina-blue { color: var(--jina-blue); }
        .text-jina-yellow { color: var(--jina-yellow); }
        .bg-jina-blue { background-color: var(--jina-blue); }
        .bg-jina-yellow { background-color: var(--jina-yellow); }

        /* --- NAVBAR --- */
        .navbar {
            background-color: rgba(15, 34, 86, 0.98) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
            margin-top: 25px; /* Déplace la photo et son cadre vers le bas */
        }
        /* Cadre jaune inspiré du logo JINA autour de la photo */
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

        /* --- ACTIVITES / BADGES --- */
        .badge-activity {
            background-color: white;
            color: var(--jina-blue);
            border: 1px solid rgba(15, 34, 86, 0.15);
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 6px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
        }
        .badge-activity:hover {
            background-color: var(--jina-blue);
            color: white;
            transform: translateY(-2px);
        }
        .badge-activity i {
            color: var(--jina-yellow);
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

        /* --- CATALOGUE & AFFICHES --- */
        .catalogue-item {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .catalogue-img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .catalogue-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 34, 86, 0.95), rgba(15, 34, 86, 0.4));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 25px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .catalogue-item:hover .catalogue-img {
            transform: scale(1.08);
        }
        .catalogue-item:hover .catalogue-overlay {
            opacity: 1;
        }

        /* --- AVIS CLIENTS --- */
        .review-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            position: relative;
        }
        .review-card::before {
            content: '“';
            position: absolute;
            top: 10px;
            left: 25px;
            font-size: 80px;
            color: rgba(15, 34, 86, 0.07);
            font-family: serif;
            line-height: 1;
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
        }
        .btn-jina-primary:hover {
            background-color: transparent;
            color: var(--jina-yellow);
        }
        .btn-jina-outline {
            background-color: transparent;
            color: white;
            font-weight: 600;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            padding: 12px 28px;
            transition: all 0.3s;
        }
        .btn-jina-outline:hover {
            border-color: var(--jina-yellow);
            color: var(--jina-yellow);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="#">
                <img src="asset/WhatsApp Image 2026-06-17 at 18.18.44.jpeg" alt="Logo JINA" class="navbar-logo me-2 rounded">
                JINA
            </a>
        </div>
    </nav>

    <header class="hero-section text-center text-md-start">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-5 col-lg-4 text-center">
                    <div class="profile-frame">
                        <img src="asset/66e27e9c-bc78-43ec-90da-c6d12b58951b.jpg" alt="Dr Gédéon KABANGA MALULA" class="profile-img shadow-lg">
                    </div>
                </div>
                <div class="col-md-7 col-lg-8">
                    <span class="badge bg-jina-yellow text-jina-blue mb-3 fw-bold px-3 py-2 uppercase tracking-wider">MÉDECIN • ENTREPRENEUR • INNOVATEUR</span>
                    <h1 class="display-4 fw-extrabold text-white mb-2">Dr Gédéon KABANGA MALULA</h1>
                    <p class="fs-4 text-white-50 mb-4">PDG NOTECH Group – Accompagnement stratégique, transformation numérique & éducation en santé.</p>
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3">
                        <a href="https://wa.me/243818230009" target="_blank" class="btn btn-jina-primary shadow"><i class="fab fa-whatsapp me-2"></i>Contacter sur WhatsApp</a>
                        <a href="#services" class="btn btn-jina-outline">Découvrir nos services</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5" id="about">
        <div class="container py-4">
            <div class="row g-5">
                <div class="col-lg-7">
                    <h2 class="section-title">👤 À propos de moi</h2>
                    <p class="lead text-jina-blue fw-medium mb-3">Dr Gédéon KABANGA MALULA est médecin généraliste, entrepreneur et innovateur congolais.</p>
                    <p class="text-secondary mb-3">En tant que PDG de <strong>NOTECH Group</strong>, il accompagne activement les particuliers et les structures corporate dans la transformation numérique, le marketing digital, le branding de haut niveau et la vulgarisation de l’éducation en santé.</p>
                    <p class="text-secondary">Auteur engagé, formateur aguerri et coach business & numérique, il consacre ses compétences à mettre la technologie au service direct de la santé, de l’entrepreneuriat à fort impact et du leadership en Afrique.</p>
                </div>
                <div class="col-lg-5">
                    <h2 class="section-title">🎯 Domaines d'activités</h2>
                    <div class="mt-4">
                        <span class="badge-activity"><i class="fas fa-stethoscope me-2"></i>Médecin généraliste</span>
                        <span class="badge-activity"><i class="fas fa-lightbulb me-2"></i>Entrepreneur & innovateur</span>
                        <span class="badge-activity"><i class="fas fa-pen-nib me-2"></i>Auteur</span>
                        <span class="badge-activity"><i class="fas fa-chart-pie me-2"></i>Formateur & coach business</span>
                        <span class="badge-activity"><i class="fas fa-display me-2"></i>Formateur & coach numérique</span>
                        <span class="badge-activity"><i class="fas fa-bullhorn me-2"></i>Expert en marketing digital</span>
                        <span class="badge-activity"><i class="fas fa-id-card me-2"></i>Branding & identité visuelle</span>
                        <span class="badge-activity"><i class="fas fa-heartbeat me-2"></i>Créateur de contenu santé</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-jina-blue text-white" id="entreprise">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <h6 class="text-jina-yellow fw-bold uppercase mb-2">STRUCTURE OFFICIELLE</h6>
                    <h2 class="display-6 fw-bold text-white mb-3">🏢 Mon entreprise : NOTECH Group</h2>
                    <p class="lead text-white-50 mb-0">NOTECH Group est une entreprise de pointe spécialisée dans la transformation digitale, le développement d’applications, le marketing digital avancé et la création d’écosystèmes numériques sur-mesure adaptés au contexte africain.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="p-4 bg-white-5 rounded-4 border border-secondary text-center" style="background: rgba(255,255,255,0.05)">
                        <small class="text-jina-yellow d-block fw-bold tracking-wider mb-2">IDENTIFICATION OFFICIELLE</small>
                        <span class="font-monospace fs-6 text-white bg-dark px-3 py-2 rounded d-inline-block">RCCM : CD/KNG/RCCM/25-A-07626</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" id="services">
        <div class="container py-5">
            <div class="text-center">
                <h2 class="section-title center text-center">🛠 Services NOTECH</h2>
                <p class="text-muted max-w-2xl mx-auto mb-5">Des solutions technologiques de pointe pour booster votre croissance et digitaliser vos processus métier.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service shadow-sm">
                        <div class="icon-box"><i class="fas fa-fingerprint"></i></div>
                        <h4 class="fw-bold h5 text-jina-blue">Transformation & intégration numérique</h4>
                        <p class="text-muted small mb-0">Accompagnement des entreprises et institutions dans leur digitalisation globale : audit technique, conseil stratégique, et déploiement d’outils numériques calibrés pour le marché congolais.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service shadow-sm">
                        <div class="icon-box"><i class="fas fa-code"></i></div>
                        <h4 class="fw-bold h5 text-jina-blue">Développement d’applications & solutions de gestion</h4>
                        <p class="text-muted small mb-0">Conception complète de solutions web et applications mobiles professionnelles. Intégration de <strong>N-Gestionnaire</strong> pour piloter la facturation, la comptabilité, le stock et la gestion de vos établissements.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service shadow-sm">
                        <div class="icon-box"><i class="fas fa-server"></i></div>
                        <h4 class="fw-bold h5 text-jina-blue">Hébergement web & noms de domaine (NOTEC-HOST)</h4>
                        <p class="text-muted small mb-0">Infrastructures d'hébergement sécurisées pour sites professionnels, achat/vente de noms de domaine, configuration d’e-mails professionnels sécurisés et support complet à la mise en ligne.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service shadow-sm">
                        <div class="icon-box"><i class="fas fa-share-alt"></i></div>
                        <h4 class="fw-bold h5 text-jina-blue">Marketing digital & réseaux sociaux</h4>
                        <p class="text-muted small mb-0">Définition de votre stratégie digitale globale, création et planification de contenus percutants, campagnes publicitaires ciblées (Ads), optimisation de la visibilité web et acquisition de clients qualifiés.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service shadow-sm">
                        <div class="icon-box"><i class="fas fa-bezier-curve"></i></div>
                        <h4 class="fw-bold h5 text-jina-blue">Branding & identité visuelle</h4>
                        <p class="text-muted small mb-0">Création sur-mesure de logos haut de gamme, chartes graphiques complètes, supports visuels institutionnels et kits de marque professionnels pour entrepreneurs ambitieux.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service shadow-sm">
                        <div class="icon-box"><i class="fas fa-graduation-cap"></i></div>
                        <h4 class="fw-bold h5 text-jina-blue">Formations & coaching</h4>
                        <p class="text-muted small mb-0">Formations hautement pratiques en marketing digital, création de contenus, personal branding et maîtrise des outils web. Formules de coaching individuel et d'ateliers pour équipes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light" id="catalogue">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="section-title center">📂 Catalogue</h2>
                <p class="text-muted">Aperçu visuel de nos solutions, projets et événements récents.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="catalogue-item">
                        <img src="asset/WhatsApp Image 2026-06-26 at 09.30.56.jpeg" alt="Transformation numérique NOTECH" class="catalogue-img">
                        <div class="catalogue-overlay">
                            <span class="badge bg-jina-yellow text-jina-blue align-self-start mb-2 fw-bold">PROJET DIGITAUX</span>
                            <h5 class="text-white fw-bold mb-1">Écosystèmes Numériques</h5>
                            <p class="text-white-50 small mb-0">Solutions intégrées pour PME.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="catalogue-item">
                        <img src="asset/WhatsApp Image 2026-06-26 at 09.30.56 (1).jpeg" alt="Coaching et formation NOTECH" class="catalogue-img">
                        <div class="catalogue-overlay">
                            <span class="badge bg-jina-yellow text-jina-blue align-self-start mb-2 fw-bold">FORMATIONS</span>
                            <h5 class="text-white fw-bold mb-1">Business & Leadership</h5>
                            <p class="text-white-50 small mb-0">Ateliers pratiques à Kinshasa.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="catalogue-item">
                        <img src="asset/WhatsApp Image 2026-06-26 at 09.30.57.jpeg" alt="Branding NOTECH" class="catalogue-img">
                        <div class="catalogue-overlay">
                            <span class="badge bg-jina-yellow text-jina-blue align-self-start mb-2 fw-bold">BRANDING</span>
                            <h5 class="text-white fw-bold mb-1">Identités Visuelles</h5>
                            <p class="text-white-50 small mb-0">Créations de kits de marque professionnels.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white" id="avis">
        <div class="container py-4">
            <h2 class="section-title center text-center mb-5">⭐ Témoignages & Avis</h2>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="review-card text-center border">
                        <div class="star-rating mb-3 fs-5 text-jina-yellow">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic text-secondary fs-5 lh-base">"L'expertise du Dr Gédéon et des équipes de NOTECH Group a complètement restructuré notre visibilité en ligne. Leurs solutions s'adaptent idéalement au contexte et réalités business d'Afrique Centrale. Nous recommandons vivement !"</p>
                        <h6 class="fw-bold text-jina-blue mb-0 mt-4">— Partenaire Institutionnel / Client NOTECH Group</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="pt-5 pb-4" id="contacts">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <h3 class="fw-bold text-white mb-3">Dr Gédéon KABANGA MALULA</h3>
                    <p class="small text-white-50 pe-lg-4 mb-4">Mettre avec excellence la technologie au service de la santé, de l’entrepreneuriat et du leadership transformationnel en Afrique.</p>
                    <div class="mb-4">
                        <h6 class="text-jina-yellow fw-bold small uppercase tracking-wider mb-3">SUIVRE NOTRE PRÉSENCE EN LIGNE</h6>
                        <a href="https://facebook.com" target="_blank" class="social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://instagram.com" target="_blank" class="social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://tiktok.com" target="_blank" class="social-btn" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="https://linkedin.com" target="_blank" class="social-btn" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://youtube.com" target="_blank" class="social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="https://x.com" target="_blank" class="social-btn" title="X (Twitter)"><i class="fab fa-x-twitter"></i></a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <h4 class="fw-bold text-white h5 mb-4">📞 Coordonnées Professionnelles</h4>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="text-jina-yellow mt-1 me-3"><i class="fas fa-phone-alt fs-5"></i></div>
                                <div>
                                    <span class="d-block text-white fw-semibold small">Téléphone / WhatsApp :</span>
                                    <a href="tel:+243818230009" class="footer-link small">+243 81 823 0009</a>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="text-jina-yellow mt-1 me-3"><i class="fas fa-envelope fs-5"></i></div>
                                <div>
                                    <span class="d-block text-white fw-semibold small">E-mail :</span>
                                    <a href="mailto:gedeonkabanga@notechgroup.com" class="footer-link small">gedeonkabanga@notechgroup.com</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="text-jina-yellow mt-1 me-3"><i class="fas fa-map-marker-alt fs-5"></i></div>
                                <div>
                                    <span class="d-block text-white fw-semibold small">Adresse :</span>
                                    <span class="small text-white-50">18, Avenue Nguzu, Q. Matadi-Kibala, C. Mont-Ngafula, Kinshasa, RDC</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="text-jina-yellow mt-1 me-3"><i class="fas fa-globe fs-5"></i></div>
                                <div>
                                    <span class="d-block text-white fw-semibold small">Web & Secrétariat direct :</span>
                                    <a href="http://www.notechgroup.com" target="_blank" class="footer-link small">www.notechgroup.com</a>
                                    <span class="text-muted d-block small"><a href="https://wa.me/243893047095" target="_blank" class="text-jina-yellow text-decoration-none">WhatsApp Secrétariat 💬</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary opacity-20">
            <div class="row">
                <div class="col text-center text-white-50 small">
                    <p class="mb-0">&copy; 2026 JINA. Tous droits réservés.</p>
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
        });
    </script>
</body>
</html>