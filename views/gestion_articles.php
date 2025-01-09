<?php require('menu2.php'); ?>

<?php

require('database/db_connect.php'); // Connexion à la base

// Vérification de la session
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit();
}

// Détection du type d'article
$type = isset($_GET['type']) ? $_GET['type'] : 'general';
switch ($type) {
    case 'bat':
        $table = 'articlesbat';
        $title = 'Bâtiments';
        $extraFields = [];
        break;
    case 'equip':
        $table = 'articlesequip';
        $title = 'Équipements';
        $extraFields = ['date_achat', 'date_derniere_rep'];
        break;
    default:
        $table = 'articles';
        $title = 'Animaux';
        $extraFields = ['dateNaissance', 'dateTraitement'];
        break;
}

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $bdd->prepare("DELETE FROM $table WHERE id = ? AND id_user = ?");
        $stmt->execute([$id, $_SESSION['id']]);
        $message = "Article supprimé avec succès.";
    }
}

// Récupérer les articles
$stmt = $bdd->prepare("SELECT * FROM $table WHERE id_user = ?");
$stmt->execute([$_SESSION['id']]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour formater les dates en français
function formatDateToFrench($date) {
    if (!empty($date)) {
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        if ($dateTime) {
            return $dateTime->format('d/m/Y'); // Format français
        }
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css">
    <title>Gestion des <?= htmlspecialchars($title) ?></title>
</head>
<body>


<section id="sectionArticles">
    <h1>Gestion des <?= htmlspecialchars($title) ?></h1>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="sectionArticles">
        <?php foreach ($articles as $article): ?>
            <div class="articles">
                <h2><?= htmlspecialchars($article['titre']) ?></h2>
                <p><?= nl2br(html_entity_decode($article['description'])) ?></p>
                <?php if (!empty($article['image'])): ?>
                    <img src="picture/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['titre']) ?>" style="max-width: 100%; border-radius: 10px;">
                <?php endif; ?>

                <!-- Champs spécifiques -->
                <?php foreach ($extraFields as $field): ?>
                    <?php if (isset($article[$field])): ?>
                        <p><?= ucfirst(str_replace('_', ' ', $field)) ?> : <?= htmlspecialchars(formatDateToFrench($article[$field])) ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Bouton Modifier -->
                <a href="modifier-article.php?type=<?= htmlspecialchars($type) ?>&id=<?= $article['id'] ?>" class="btn" style="margin-right: 10px;">Modifier</a>

                <!-- Bouton Supprimer -->
                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $article['id'] ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>
</body>
</html>
