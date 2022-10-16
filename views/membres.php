<?php
	session_start();
	$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;','root','');
	if(!$_SESSION['mdp']){
	header('Location: connexion.php');
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Afficher les membres</title>
</head>

<body>
	
	<!-- Afficher tous les membres -->
		<?php
			$recupUsers = $bdd->query('SELECT * FROM membres');
	while($user = $recupUsers->fetch()){
		?>
			<p><?= $user['pseudo'] ?></p>
		<?php
	}
		?>
	<!-- Fin afficher tous les membres -->
	<script src="../js/index.js?version=1.0.4"></script>
</body>
</html>