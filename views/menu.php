<?php
session_start();
?>

<header>
    <!-- Menu latéral -->
    <nav id="sideNav" class="sidenav">
        <!-- Bouton pour fermer le menu -->
        <a id="closeBtn" href="#" class="close" aria-label="Fermer le menu">&times;</a>

        <ul id="menu">
            <!-- Lien vers l'accueil -->
            <li><a href="index_view.php">Accueil</a></li>

            <!-- Connexion ou espace utilisateur -->
            <?php if (isset($_SESSION['id'])): ?>
                <li><a href="votre_espace.php">Mon espace</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php">Inscription</a></li>
            <?php endif; ?>

            <!-- Lien vers les informations -->
            <li><a href="viewsAccueilHtml/infos.html">Infos</a></li>
        </ul>
    </nav>

    <!-- Bouton pour ouvrir le menu -->
    <a href="#" id="openBtn" aria-label="Ouvrir le menu">
        <span class="burger-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>
</header>

<script>
    const sideNav = document.getElementById('sideNav');
    const openBtn = document.getElementById('openBtn');
    const closeBtn = document.getElementById('closeBtn');

    openBtn.addEventListener('click', (e) => {
        e.preventDefault();
        sideNav.classList.add('active');
    });

    closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        sideNav.classList.remove('active');
    });

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', (e) => {
        if (!sideNav.contains(e.target) && !openBtn.contains(e.target)) {
            sideNav.classList.remove('active');
        }
    });
</script>
