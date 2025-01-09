<?php require('menu2.php'); ?>
<?php

require('database/db_connect.php');

// Vérification de la session utilisateur
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit();
}

// Fonction pour récupérer les articles d'une table donnée
function getArticles($bdd, $table, $userId) {
    try {
        $stmt = $bdd->prepare("SELECT * FROM $table WHERE id_user = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Récupérer les articles
$articles = getArticles($bdd, 'articles', $_SESSION['id']);
$articlesBat = getArticles($bdd, 'articlesbat', $_SESSION['id']);
$articlesEquip = getArticles($bdd, 'articlesequip', $_SESSION['id']);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="style2.css" rel="stylesheet" type="text/css">
    <title>Votre espace</title>
    <!-- Google Tag -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L17QZRH9VP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-L17QZRH9VP');
    </script>
</head>
<body>
    

    <section id="sectionEspace">
        <h2 align="center" style="margin: 0; padding-top: 20px; font-size: 3em;">
            Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] ?? $_SESSION['pseudo']) ?> !
        </h2>

        <article id="articleEspace">
            <!-- Animaux -->
            <a href="articles.php" class="divEspace">
                <h2>Vos animaux :</h2>
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <div>
                            <ul>
                                <li><?= htmlspecialchars($article['titre']) ?></li>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun animal ajouté pour le moment.</p>
                <?php endif; ?>
            </a>

            <!-- Bâtiments -->
            <a href="articlesBat.php" class="divEspace">
                <h2>Vos bâtiments :</h2>
                <?php if (!empty($articlesBat)): ?>
                    <?php foreach ($articlesBat as $article): ?>
                        <div>
                            <ul>
                                <li><?= htmlspecialchars($article['titre']) ?></li>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun bâtiment ajouté pour le moment.</p>
                <?php endif; ?>
            </a>

            <!-- Équipements -->
            <a href="articlesEquip.php" class="divEspace">
                <h2>Vos équipements :</h2>
                <?php if (!empty($articlesEquip)): ?>
                    <?php foreach ($articlesEquip as $article): ?>
                        <div>
                            <ul>
                                <li><?= htmlspecialchars($article['titre']) ?></li>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun équipement ajouté pour le moment.</p>
                <?php endif; ?>
            </a>
        </article>
    </section>

    <script src="../js/index.js?version=1.0.4"></script>
</body>
</html>
