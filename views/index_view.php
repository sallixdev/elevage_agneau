<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link href="style2.css" rel="stylesheet" type="text/css">
    <meta name="google-site-verification" content="pLkh6iXDoyAn15Hqn4SXil6vcXPPjsXbmWi4gzs_zlQ" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L17QZRH9VP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-L17QZRH9VP');
    </script>
</head>
<body>
    <section id="sectionIndex">
        <div id="imgIndex">
            <?php require('menu.php'); ?>
            <div>
                <h1 align="center">Bienvenu sur le site de gestion de votre élevage</h1>
            </div>
        </div>
        <div>
            <div>
                <h2 align="center">
                    Créez ici des fiches pour chaque animal de votre élevage afin de suivre au mieux leur évolution, leurs traitements, âge, naissances...
                </h2>
            </div>
            <br>
            <p align="center">
                Vous allez pouvoir suivre l'évolution de vos animaux en ajoutant toutes les informations nécessaires comme :
                <br>- Un nom ou identifiant,
                <br>- La date de naissance,
                <br>- La date de leur dernier traitement,
                <br>- Une description pour noter tout événement survenu comme une blessure, gestation...
            </p>
            <div id="imgIndex2">
                <img src="../img/accueil1.png" alt="Image 1">
                <img src="../img/accueil2.png" alt="Image 2">
            </div>
        </div>
    </section>
    <script src="../js/index.js?version=1.0.4"></script>
</body>
</html>
