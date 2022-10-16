<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;','root','');
if(isset($_POST['envoi'])){
	if(!empty($_POST['pseudo']) AND !empty($_POST['mdp']) AND !empty($_POST['email'])){

		
		$pseudo = htmlspecialchars($_POST['pseudo']) ;
		$mdp = sha1($_POST['mdp']);
		$email = htmlspecialchars($_POST['email']);
		$insertUser = $bdd->prepare('INSERT INTO membres(pseudo, mdp, email) VALUES(?,?,?)');
		$insertUser->execute(array($pseudo,$mdp,$email));
		
		$recupUser = $bdd->prepare('SELECT * FROM membres WHERE pseudo = ? AND mdp = ? AND email = ?');
		$recupUser->execute(array($pseudo,$mdp,$email));
		if($recupUser->rowCount() > 0){
		
		$_SESSION['pseudo'] = $pseudo;
		$_SESSION['mdp'] = $mdp;
		$_SESSION['email'] = $email;
		$_SESSION['id'] = $recupUser->fetch()['id'];
		
			header('Location: votre_espace.php');
		}
	}else{
		echo "Veuillez compléter tous les champs...";
	}
}


?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<link href="style2.css" rel="stylesheet" type="text/css">
<title>Inscription</title>
</head>
	
	<?php require('menu.php'); ?>

<body>
	
	<h2 align="center" >Veuillez remplir le formulaire.</h2>
	<div class="formGeneral">
		<div>
			<form method="post" action="" align="center">

				Votre pseudo :<br><input type="text" name="pseudo">
				<br/><br/>
				Votre mot de passe :<br><input type="password" name="mdp">
				<br/><br/>
				Votre E-mail :<br><input type="email" name="email">
				<br/><br/>
				<input type="submit" name="envoi">
			</form>
		</div>
	</div>
	<script src="../js/index.js?version=1.0.4"></script>
</body>
</html>