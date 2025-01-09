<?php 
session_start();
$bdd = new PDO('mysql:host=127.0.0.1:3306;dbname=u164330380_espaceAdmin;','u164330380_gererelevage','Sallix33620.');

if(isset($_POST['valider'])){
	if(!empty($_POST['pseudo']) AND !empty($_POST['mdp'])){
		$pseudo = htmlspecialchars($_POST['pseudo']) ;
		$mdp = sha1($_POST['mdp']);
		
		
		$recupUser = $bdd->prepare('SELECT * FROM membres WHERE pseudo = ? AND mdp = ? ');
		$recupUser->execute(array($pseudo,$mdp));
		if($recupUser->rowCount() > 0){
		
		$_SESSION['pseudo'] = $pseudo;
		$_SESSION['mdp'] = $mdp;
		$_SESSION['email'] = $email;
		$_SESSION['id'] = $recupUser->fetch()['id'];
		
			header('Location: votre_espace.php');
		}else{
			echo"Votre mot de passe ou pseudo est incorect";
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
	<link href="http://gererelevage.com/views/style2.css" rel="stylesheet" type="text/css">
<title>Espace connexion</title>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-L17QZRH9VP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-L17QZRH9VP');
</script>
</head>

	<?php require('menu.php'); ?>

<body>
	
	<h2 align="center" >Veuillez compléter vos identifiants.</h2>
	<div class="formGeneral">
		
		<div>
			<form method="post" action="" align="center" >
				Identifiant :<br><input type="text" name="pseudo" autocomplete="off" >
				<br><br/>
				Mot de passe :<br><input type="password" name="mdp" >
				<br><br>
				<input type="submit" name="valider" >
		</form>
		</div>
	</div>
	<script src="../js/index.js?version=1.0.4"></script>
</body>
</html>