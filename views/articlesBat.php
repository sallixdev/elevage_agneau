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
	<link href="style2.css" rel="stylesheet" type="text/css">
<title>Afficher tous les articles</title>
</head>

	
	
<body>
	<?php require('menu2.php'); ?>
	
	<section id="batiment" >
		<h2 align="center">Vos fiches renseignements.</h2>
		<div class="sectionArticles">
		<?php
		
		$recupArticles = $bdd->prepare('SELECT * FROM articlesbat WHERE id_user = ? ORDER BY date_time_publication ');
		
		$recupArticles->execute( [$_SESSION['id']]);
		
	while($article = $recupArticles->fetch()){
		?>
			
			<div class="articles" >
				<h2><?= $article['titre']; ?></h2>
				
				<p><?= $article['description'];?></p>
				<img src="picture/<?=$article['image'];  ?>" style="height: 170px; max-width: 215px; ">
				<br/>
        		
				<a href="supprimer-articleBat.php?id=<?= $article['id']; ?> " >
					<button style="color: white; background-color: red; margin-bottom: 10px; ">Supprimer 
					</button>
				</a>
				<a href="modifier-articleBat.php?id=<?= $article['id']; ?> " >
					<button style="color: white; background-color: darkblue; margin-bottom: 10px; ">Modifier 
					</button>
				</a>
			</div>
			
	<?php
	}
	?>
	</div>
	</section>
	<script src="../js/index.js?version=1.0.4"></script>
</body>
</html>