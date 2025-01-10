<?php
require('menu2.php');
require('database/db_connect.php');

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
        $title = 'Gestion des bâtiments';
        $defaultFields = ['type_batiment', 'capacite_maximale', 'localisation', 'date_construction', 'dernier_entretien', 'prochain_entretien', 'statut', 'animaux_actuels'];
        break;
    case 'equip':
        $table = 'articlesequip';
        $title = 'Gestion des équipements';
        $defaultFields = ['modele', 'marque', 'numero_serie', 'dernier_entretien', 'prochain_entretien', 'statut', 'utilisateur_principal', 'localisation'];
        break;
    default:
        $table = 'articles';
        $title = 'Gestion des animaux';
        $defaultFields = ['identification', 'type_animal', 'race', 'sexe', 'vaccinations', 'traitements', 'statut_reproductif', 'date_mise_bas', 'descendants', 'pere_id', 'mere_id', 'date_entree', 'date_sortie', 'motif_sortie'];
        break;
}

// Étape 1 : Charger les champs dynamiques
$stmt = $bdd->prepare("
    SELECT field_name, is_active 
    FROM user_fields 
    WHERE user_id = ? AND type = ?
");
$stmt->execute([$_SESSION['id'], $type]);
$userFields = $stmt->fetchAll(PDO::FETCH_ASSOC);

$extraFields = [];
$inactiveFields = [];
$existingFields = [];
foreach ($userFields as $field) {
    $existingFields[] = $field['field_name'];
    if ($field['is_active']) {
        $extraFields[] = $field['field_name'];
    } else {
        $inactiveFields[] = $field['field_name'];
    }
}

// Étape 2 : Ajouter les champs par défaut s'ils n'existent pas
foreach ($defaultFields as $defaultField) {
    if (!in_array($defaultField, $existingFields)) {
        $stmt = $bdd->prepare("
            INSERT INTO user_fields (user_id, field_name, is_active, type) 
            VALUES (?, ?, 1, ?)
        ");
        $stmt->execute([$_SESSION['id'], $defaultField, $type]);
        $extraFields[] = $defaultField;
    }
}

// Étape 3 : Gestion des actions dynamiques (ajout/suppression/réactivation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $newField = trim($_POST['new_field'] ?? '');
    $manageField = trim($_POST['manage_field'] ?? '');
    $reactivateField = trim($_POST['reactivate_field'] ?? '');
    $action = $_POST['action'];

    if ($action === 'add' && !empty($newField)) {
        // Ajouter un nouveau champ
        $stmt = $bdd->prepare("
            SELECT COUNT(*) 
            FROM user_fields 
            WHERE user_id = ? AND field_name = ? AND type = ?
        ");
        $stmt->execute([$_SESSION['id'], $newField, $type]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $bdd->prepare("
                INSERT INTO user_fields (user_id, field_name, is_active, type) 
                VALUES (?, ?, 1, ?)
            ");
            $stmt->execute([$_SESSION['id'], $newField, $type]);
            $extraFields[] = $newField;
        }
    }

    if ($action === 'remove' && !empty($manageField)) {
        // Désactiver un champ existant
        $stmt = $bdd->prepare("
            UPDATE user_fields 
            SET is_active = 0 
            WHERE user_id = ? AND field_name = ? AND type = ?
        ");
        $stmt->execute([$_SESSION['id'], $manageField, $type]);
    }

    if ($action === 'reactivate' && !empty($reactivateField)) {
        // Réactiver un champ désactivé
        $stmt = $bdd->prepare("
            UPDATE user_fields 
            SET is_active = 1 
            WHERE user_id = ? AND field_name = ? AND type = ?
        ");
        $stmt->execute([$_SESSION['id'], $reactivateField, $type]);
        $extraFields[] = $reactivateField;
    }

    // Actualisation de la page
    header("Location: gestion_articles.php?type=$type");
    exit();
}

// Étape 4 : Charger les articles
$query = "SELECT * FROM $table WHERE id_user = ?";
$params = [$_SESSION['id']];

$stmt = $bdd->prepare($query);
$stmt->execute($params);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Gestion des actions (supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $bdd->prepare("DELETE FROM $table WHERE id = ? AND id_user = ?");
        $stmt->execute([$id, $_SESSION['id']]);
        $message = "Article supprimé avec succès.";
    }
}

// Récupération des filtres soumis
$filterTitre = isset($_GET['titre']) ? trim($_GET['titre']) : '';
$filterStatut = isset($_GET['statut']) ? $_GET['statut'] : '';

// Construction de la requête SQL avec les filtres
$query = "SELECT * FROM $table WHERE id_user = ?";
$params = [$_SESSION['id']];

if (!empty($filterTitre)) {
    $query .= " AND titre LIKE ?";
    $params[] = "%$filterTitre%";
}

if (!empty($filterStatut)) {
    $query .= " AND statut = ?";
    $params[] = $filterStatut;
}

// Exécution de la requête SQL
$stmt = $bdd->prepare($query);
$stmt->execute($params);
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
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        /* Masquer le formulaire par défaut */
        #dynamicForm {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<section>
    <h1><?= htmlspecialchars($title) ?></h1><br>

    <!-- Bouton pour afficher/masquer le formulaire -->
    <button id="toggleFormButton" >Ajouter ou retirer colonnes </button>


    <!-- Formulaire masqué par défaut -->
    <div id="dynamicForm">
        <form method="POST" action="" class="style_form">
            <label for="new_field">Ajouter un champ :</label>
            <input type="text" id="new_field" name="new_field" placeholder="Nom du champ">
            <button type="submit" name="action" value="add" class="btn btn-primary">Ajouter</button>

            <label for="manage_field">Retirer un champ :</label>
            <select id="manage_field" name="manage_field">
                <option value="">-- Sélectionner un champ --</option>
                <?php foreach ($extraFields as $field): ?>
                    <option value="<?= htmlspecialchars($field) ?>"><?= ucfirst(str_replace('_', ' ', $field)) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="action" value="remove" class="btn btn-danger">Retirer</button>

            <label for="reactivate_field">Réactiver un champ :</label>
            <select id="reactivate_field" name="reactivate_field">
                <option value="">-- Sélectionner un champ --</option>
                <?php foreach ($inactiveFields as $field): ?>
                    <option value="<?= htmlspecialchars($field) ?>"><?= ucfirst(str_replace('_', ' ', $field)) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="action" value="reactivate" class="btn btn-success">Réactiver</button>
        </form>
    </div><br>
    <!-- Formulaire de filtre -->
    <form method="GET" action="" class="filtre" >
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($filterTitre) ?>" placeholder="Rechercher par titre">

        <?php if ($type !== 'general'): ?>
            <label for="statut">Statut :</label>
            <select id="statut" name="statut">AC
                <option value="">-- Tous --</option>
                <option value="en service" <?= $filterStatut === 'en service' ? 'selected' : '' ?>>En service</option>
                <option value="hors service" <?= $filterStatut === 'hors service' ? 'selected' : '' ?>>Hors service</option>
                <option value="en réparation" <?= $filterStatut === 'en réparation' ? 'selected' : '' ?>>En réparation</option>
            </select>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="?type=<?= htmlspecialchars($type) ?>" class="btn btn-secondary">Réinitialiser</a>
    </form>
    <?php if (isset($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Tableau de gestion des articles -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>image</th>
                <?php foreach ($extraFields as $field): ?>
                    <th><?= ucfirst(str_replace('_', ' ', $field)) ?></th>
                <?php endforeach; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= htmlspecialchars($article['id']) ?></td>
                    <td><?= htmlspecialchars($article['titre']) ?></td>
                    <td><?= nl2br(htmlspecialchars_decode($article['description'])) ?></td>
                    <td>
                        <?php if (!empty($article['image'])): ?>
                            <button  onclick="showImage('<?= htmlspecialchars($article['image']) ?>')">Afficher l'image</button>
                        <?php else: ?>
                            <span>Pas d'image</span>
                        <?php endif; ?>
                    </td>
                    <?php foreach ($extraFields as $field): ?>
                        <td><?= htmlspecialchars($article[$field] ?? '') ?></td>
                    <?php endforeach; ?>
                    <td>
                        <a href="modifier-article.php?type=<?= htmlspecialchars($type) ?>&id=<?= $article['id'] ?>" class="btn btn-primary">Modifier</a>
                        <!-- Bouton Supprimer -->
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<!-- Modale pour afficher l'image -->
<div id="imageModal" class="image-modal">
    <span class="close-modal" onclick="closeImage()">&times;</span>
    <img class="modal-content" id="modalImage" alt="Image">
</div>

<!-- Script JavaScript pour afficher/masquer le formulaire -->
<script>
    const toggleFormButton = document.getElementById('toggleFormButton');
    const dynamicForm = document.getElementById('dynamicForm');

    toggleFormButton.addEventListener('click', () => {
        // Basculer la visibilité du formulaire
        if (dynamicForm.style.display === 'none' || dynamicForm.style.display === '') {
            dynamicForm.style.display = 'block';
            toggleFormButton.textContent = 'Masquer le formulaire';
        } else {
            dynamicForm.style.display = 'none';
            toggleFormButton.textContent = 'Ajouter ou retirer colonnes';
        }
    });

    function showImage(imagePath) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        // Définir le chemin de l'image
        modalImage.src = `picture/${imagePath}`;
        modal.style.display = 'block';
    }

    function closeImage() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
    }
</script>
</body>
</html>
