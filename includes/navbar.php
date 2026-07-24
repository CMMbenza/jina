<style>
    
.jina-navbar {
    background-color: var(--jina-blue) !important;
    border-bottom: 3px solid var(--jina-yellow);
    padding: 15px 0;
}

.jina-navbar .navbar-brand {
    font-weight: 800;
    letter-spacing: 0.5px;
}

/* Logo */
.navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.navbar-brand img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 12px;
    transition: all 0.4s ease;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.navbar-brand span {
    color: var(--jina-white);
    font-size: 1.5rem;
    font-weight: 800;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

/* Animation au survol */
.navbar-brand:hover img {
    transform: rotate(-8deg) scale(1.1);
    box-shadow: 0 12px 30px rgba(255, 204, 0, 0.4);
}

.navbar-brand:hover span {
    color: var(--jina-yellow);
    transform: translateX(4px);
}

/* Animation au clic */
.navbar-brand:active img {
    transform: scale(0.95);
}

@keyframes floatingLogo {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-4px);
    }
    100% {
        transform: translateY(0px);
    }
}

.navbar-brand img {
    animation: floatingLogo 3s ease-in-out infinite;
}

</style>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark jina-navbar mb-4">
    <div class="container">
        <!-- <a class="navbar-brand" href="#"><i class="fas fa-id-card me-2 text-warning"></i>JINA DASHBOARD</a> -->
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
            <!-- Remplace par ton image si disponible -->
            <img src="../assets/img/logo jina.jpeg" alt="Logo" onerror="this.style.display='none'">
            <span style="color: var(--jina-white); font-size: 1.5rem; font-weight: 800;">JINA</span>
        </a>
        <div class="d-flex gap-2">
            <a href="<?php echo $public_url; ?>" class="btn btn-sm btn-light fw-bold px-3 py-2 rounded-3"
                target="_blank">
                <i class="fas fa-external-link-alt me-1"></i> Voir Public
            </a>
            <a href="../auth/logout.php" class="btn btn-sm btn-danger fw-bold px-3 py-2 rounded-3">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</nav>
<link rel="shortcut icon" href="../assets/img/logo-jina.ico" type="image/x-icon">