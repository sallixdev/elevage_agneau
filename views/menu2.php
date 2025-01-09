<?php
session_start();
?>

<header>
    <!-- Menu latéral -->
    <nav id="sideNav" class="sidenav">
        <!-- Bouton pour fermer le menu -->
        <a id="closeBtn" href="#" class="close" aria-label="Fermer le menu">&times;</a>

        <ul id="menu">
            <!-- Lien vers l'espace personnel -->
            <li>
                <a href="votre_espace.php">Votre espace</a>
            </li>

            <!-- Section Animaux -->
            <li>
                <a href="#">Animaux</a>
                <ul class="sous">
                    <li><a href="publier_article.php?type=general">Ajouter un animal</a></li>
                    <li><a href="gestion_articles.php?type=general">Afficher tous les animaux</a></li>
                </ul>
            </li>

            <!-- Section Bâtiments -->
            <li>
                <a href="#">Bâtiments</a>
                <ul class="sous">
                    <li><a href="publier_article.php?type=bat">Ajouter un bâtiment</a></li>
                    <li><a href="gestion_articles.php?type=bat">Afficher tous les bâtiments</a></li>
                </ul>
            </li>

            <!-- Section Équipements -->
            <li>
                <a href="#">Équipements</a>
                <ul class="sous">
                    <li><a href="publier_article.php?type=equip">Ajouter un équipement</a></li>
                    <li><a href="gestion_articles.php?type=equip">Afficher tous les équipements</a></li>
                </ul>
            </li>

            <!-- Déconnexion -->
            <li>
                <a href="deconnexion.php" class="deconect">Se déconnecter</a>
            </li>

            <!-- Informations utilisateur -->
            <?php if (isset($_SESSION['pseudo'], $_SESSION['id'])): ?>
                <li>
                    <a href="votre_espace.php">
                        <?= htmlspecialchars($_SESSION['pseudo']) ?>
                    </a>
                    <a style="font-size: 0.8em; position: absolute; top: 3px; right: 15px;">
                        ID : <?= htmlspecialchars($_SESSION['id']) ?>
                    </a>
                </li>
            <?php endif; ?>
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
