<?php require('menu2.php'); ?>

<?php

require('database/db_connect.php');

// Vérification de la session utilisateur
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit();
}

// Détection du type d'article et de l'article à modifier
$type = isset($_GET['type']) ? $_GET['type'] : 'general';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

switch ($type) {
    case 'bat':
        $table = 'articlesbat';
        $title = 'Modifier un bâtiment';
        $extraFields = [
            'type_batiment', 'capacite_maximale', 'localisation',
            'date_construction', 'dernier_entretien', 'prochain_entretien',
            'statut', 'animaux_actuels'
        ];
        break;
    case 'equip':
        $table = 'articlesequip';
        $title = 'Modifier un équipement';
        $extraFields = [
            'modele', 'marque', 'numero_serie',
            'dernier_entretien', 'prochain_entretien', 'statut',
            'utilisateur_principal', 'localisation'
        ];
        break;
    default:
        $table = 'articles';
        $title = 'Modifier un animal';
        $extraFields = [
            'identification', 'type_animal', 'race', 'sexe',
            'vaccinations', 'traitements', 'statut_reproductif',
            'date_mise_bas', 'descendants', 'pere_id', 'mere_id',
            'date_entree', 'date_sortie', 'motif_sortie'
        ];
        break;
}

// Récupération des informations de l'article
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

    // Gestion des champs supplémentaires
    foreach ($extraFields as $field) {
        $extraValues[$field] = !empty($_POST[$field]) ? $_POST[$field] : null;
    }

    // Gestion de l'image
    $image = $article['image']; // Conserver l'image existante par défaut
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileInfo = pathinfo($_FILES['image']['name']);
        $fileExtension = strtolower($fileInfo['extension']);

        if (in_array($fileExtension, $allowedExtensions)) {
            $image = uniqid() . '.' . $fileExtension;
            move_uploaded_file($_FILES['image']['tmp_name'], "picture/$image");
        } else {
            $error = 'Format d\'image non supporté.';
        }
    }

    if (!isset($error)) {
        // Préparation de la requête SQL
        $fieldsToUpdate = "titre = ?, description = ?, image = ?";
        $values = [$titre, $description, $image];

        foreach ($extraFields as $field) {
            $fieldsToUpdate .= ", $field = ?";
            $values[] = $extraValues[$field];
        }

        $values[] = $id; // ID de l'article
        $values[] = $_SESSION['id']; // ID de l'utilisateur

        // Exécution de la mise à jour
        $stmt = $bdd->prepare("UPDATE $table SET $fieldsToUpdate WHERE id = ? AND id_user = ?");
        $stmt->execute($values);

        $successMessage = "Article mis à jour avec succès.";
    }
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

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="style_form">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($article['description']) ?></textarea>

        <!-- Champs supplémentaires -->
        <?php foreach ($extraFields as $field): ?>
            <label for="<?= $field ?>"><?= ucfirst(str_replace('_', ' ', $field)) ?> :</label>
            <?php if (strpos($field, 'date') !== false): ?>
                <input type="date" id="<?= $field ?>" name="<?= $field ?>" value="<?= htmlspecialchars($article[$field]) ?>">
            <?php elseif ($field === 'sexe'): ?>
                <select id="<?= $field ?>" name="<?= $field ?>">
                    <option value="male" <?= $article[$field] === 'male' ? 'selected' : '' ?>>Mâle</option>
                    <option value="femelle" <?= $article[$field] === 'femelle' ? 'selected' : '' ?>>Femelle</option>
                </select>
            <?php elseif ($field === 'statut' || $field === 'statut_reproductif'): ?>
                <select id="<?= $field ?>" name="<?= $field ?>">
                    <option value="en service" <?= $article[$field] === 'en service' ? 'selected' : '' ?>>En service</option>
                    <option value="hors service" <?= $article[$field] === 'hors service' ? 'selected' : '' ?>>Hors service</option>
                    <option value="en réparation" <?= $article[$field] === 'en réparation' ? 'selected' : '' ?>>En réparation</option>
                </select>
            <?php else: ?>
                <input type="text" id="<?= $field ?>" name="<?= $field ?>" value="<?= htmlspecialchars($article[$field]) ?>">
            <?php endif; ?>
        <?php endforeach; ?>

        <label for="image">Image :</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit">Enregistrer</button>
    </form>
</section>
</body>
</html>
