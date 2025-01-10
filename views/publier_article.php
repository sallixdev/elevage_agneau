<?php require('menu2.php'); ?>

<?php
require('database/db_connect.php'); // Connexion à la base

// Vérification de la session utilisateur
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit();
}

// Détection du type d'article
$type = isset($_GET['type']) ? $_GET['type'] : 'general';

switch ($type) {
    case 'bat':
        $table = 'articlesbat';
        $title = 'Publier un bâtiment';
        $extraFields = [
            'type_batiment', 'capacite_maximale', 'localisation', 
            'date_construction', 'dernier_entretien', 'prochain_entretien', 
            'statut', 'animaux_actuels'
        ];
        break;
    case 'equip':
        $table = 'articlesequip';
        $title = 'Publier un équipement';
        $extraFields = [
            'modele', 'marque', 'numero_serie', 
            'dernier_entretien', 'prochain_entretien', 'statut', 
            'utilisateur_principal', 'localisation'
        ];
        break;
    default:
        $table = 'articles';
        $title = 'Publier un animal';
        $extraFields = [
            'identification', 'type_animal', 'race', 'sexe', 
            'vaccinations', 'traitements', 'statut_reproductif', 
            'date_mise_bas', 'descendants', 'pere_id', 'mere_id', 
            'date_entree', 'date_sortie', 'motif_sortie'
        ];
        break;
}

// Gestion de la publication
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = nl2br(htmlspecialchars($_POST['description']));
    $id_user = $_SESSION['id'];
    $extraValues = [];

    // Récupération des champs supplémentaires
    foreach ($extraFields as $field) {
        $extraValues[$field] = !empty($_POST[$field]) ? $_POST[$field] : null;
    }

    // Gestion de l'image
    $image = null;
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
        // Construction de la requête SQL
        $columns = 'titre, description, id_user';
        $placeholders = '?, ?, ?';
        $values = [$titre, $description, $id_user];

        if ($image) {
            $columns .= ', image';
            $placeholders .= ', ?';
            $values[] = $image;
        }

        foreach ($extraFields as $field) {
            $columns .= ", $field";
            $placeholders .= ', ?';
            $values[] = $extraValues[$field];
        }

        // Exécution de l'insertion
        $stmt = $bdd->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        $stmt->execute($values);

        $successMessage = "Article publié avec succès.";
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
        <input type="text" id="titre" name="titre" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>

        <!-- Champs supplémentaires -->
        <?php foreach ($extraFields as $field): ?>
            <label for="<?= $field ?>"><?= ucfirst(str_replace('_', ' ', $field)) ?> :</label>
            <?php if (strpos($field, 'date') !== false): ?>
                <input type="date" id="<?= $field ?>" name="<?= $field ?>">
            <?php elseif ($field === 'sexe'): ?>
                <select id="<?= $field ?>" name="<?= $field ?>">
                    <option value="male">Mâle</option>
                    <option value="femelle">Femelle</option>
                </select>
            <?php elseif ($field === 'statut' || $field === 'statut_reproductif'): ?>
                <select id="<?= $field ?>" name="<?= $field ?>">
                    <option value="en service">En service</option>
                    <option value="hors service">Hors service</option>
                    <option value="en réparation">En réparation</option>
                </select>
            <?php else: ?>
                <input type="text" id="<?= $field ?>" name="<?= $field ?>">
            <?php endif; ?>
        <?php endforeach; ?>

        <label for="image">Image :</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit">Publier</button>
    </form>
</section>
</body>
</html>
