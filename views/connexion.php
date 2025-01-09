<?php require('menu.php'); ?>

<?php

require('database/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $mdp = $_POST['mdp'];

    if (!empty($email) && !empty($mdp)) {
        $stmt = $bdd->prepare('SELECT * FROM membres WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($mdp, $user['mdp'])) {
                // Stocker les informations utilisateur dans la session
                $_SESSION['id'] = $user['id'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['role'] = $user['role'];

                // Rediriger vers la page de l'espace utilisateur
                header('Location: votre_espace.php');
                exit();
            } else {
                $error = 'Mot de passe incorrect.';
            }
        } else {
            $error = 'Adresse email non trouvée.';
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
    <title>Connexion</title>
</head>

<body>
    <h1>Connexion</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mdp" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
