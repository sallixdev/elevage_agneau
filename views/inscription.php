<?php

require('database/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = htmlspecialchars($_POST['prenom']);
    $nom = htmlspecialchars($_POST['nom']);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $mdp = password_hash($_POST['mdp'], PASSWORD_BCRYPT); // Hash sécurisé

    // Validation des champs
    if (!empty($prenom) && !empty($nom) && !empty($pseudo) && !empty($email) && !empty($_POST['mdp'])) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $bdd->prepare('SELECT id FROM membres WHERE email = ?');
            $stmt->execute([$email]);

            if ($stmt->rowCount() === 0) {
                $insert = $bdd->prepare('INSERT INTO membres (prenom, nom, pseudo, email, mdp) VALUES (?, ?, ?, ?, ?)');
                $insert->execute([$prenom, $nom, $pseudo, $email, $mdp]);

                $_SESSION['success'] = 'Inscription réussie. Vous pouvez maintenant vous connecter.';
                header('Location: connexion.php');
                exit();
            } else {
                $error = 'Cette adresse email est déjà utilisée.';
            }
        } else {
            $error = 'Adresse email invalide.';
        }
    } else {
        $error = 'Tous les champs doivent être remplis.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css">
    <title>Inscription</title>
</head>
<?php require('menu.php'); ?>
<body>
    <h1>Inscription</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="pseudo" placeholder="Nom de l'élevage" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mdp" placeholder="Mot de passe" required>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
