<?php require('menu2.php'); ?>

<?php

require('database/db_connect.php');

// Vérification de la session
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit();
}

// Détection du type d'article
$type = isset($_GET['type']) ? $_GET['type'] : 'general';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

switch ($type) {
    case 'bat':
        $table = 'articlesbat';
        $title = 'Modifier un bâtiment';
        $extraFields = []; // Pas de champs supplémentaires spécifiques
        break;
    case 'equip':
        $table = 'articlesequip';
        $title = 'Modifier un équipement';
        $extraFields = ['date_achat', 'date_derniere_rep'];
        break;
    default:
        $table = 'articles';
        $title = 'Modifier un animal';
        $extraFields = ['dateNaissance', 'dateTraitement'];
        break;
}

// Récupération de l'article à modifier
$article = null;
if ($id > 0) {
    $stmt = $bdd->prepare("SELECT * FROM $table WHERE id = ? AND id_user = ?");
    $stmt->execute([$id, $_SESSION['id']]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        echo "Article introuvable ou vous n'avez pas les droits pour le modifier.";
        exit();
    }
}

// Mise à jour de l'article
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = nl2br(htmlspecialchars($_POST['description']));
    $extraValues = [];

    // Gestion des champs supplémentaires (dates)
    foreach ($extraFields as $field) {
        $extraValues[$field] = !empty($_POST[$field]) ? $_POST[$field] : null;
    }

    // Préparation de la requête de mise à jour
    $fieldsToUpdate = "titre = ?, description = ?";
    $values = [$titre, $description];

    foreach ($extraFields as $field) {
        $fieldsToUpdate .= ", $field = ?";
        $values[] = $extraValues[$field];
    }

    $values[] = $id; // ID de l'article
    $values[] = $_SESSION['id']; // ID de l'utilisateur

    $stmt = $bdd->prepare("UPDATE $table SET $fieldsToUpdate WHERE id = ? AND id_user = ?");
    $stmt->execute($values);

    $successMessage = "Article mis à jour avec succès.";
    header("Location: gestion_articles.php?type=$type");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>


<section>
    <h1><?= htmlspecialchars($title) ?></h1>
    
    <?php if (isset($successMessage)): ?>
        <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre'] ?? '') ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($article['description'] ?? '') ?></textarea>

        <!-- Champs supplémentaires (dates simplifiées) -->
        <?php foreach ($extraFields as $field): ?>
            <label for="<?= $field ?>"><?= ucfirst(str_replace('_', ' ', $field)) ?> :</label>
            <input type="date" id="<?= $field ?>" name="<?= $field ?>" value="<?= htmlspecialchars($article[$field] ?? '') ?>">
        <?php endforeach; ?>

        <button type="submit">Enregistrer</button>
    </form>
</section>
</body>
</html>
