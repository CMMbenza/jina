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
</style>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark jina-navbar mb-4">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fas fa-id-card me-2 text-warning"></i>JINA DASHBOARD</a>
        <div class="d-flex gap-2">
            <a href="<?php echo $public_url; ?>"
                class="btn btn-sm btn-light fw-bold px-3 py-2 rounded-3" target="_blank">
                <i class="fas fa-external-link-alt me-1"></i> Voir Public
            </a>
            <a href="../auth/logout.php" class="btn btn-sm btn-danger fw-bold px-3 py-2 rounded-3">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</nav>
<link rel="shortcut icon" href="../assets/img/logo-jina.ico" type="image/x-icon">