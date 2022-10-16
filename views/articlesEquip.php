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
	
	<section id="garage" >
		<h2 align="center">Vos fiches renseignements.</h2>
		<div class="sectionArticles">
		<?php
		
		$recupArticles = $bdd->prepare('SELECT * FROM articlesequip WHERE id_user = ? ');
		
		$recupArticles->execute( [$_SESSION['id']]);
		
	while($article = $recupArticles->fetch()){
		?>
			
			<div class="articles" >
				<h2><?= $article['titre']; ?></h2>
				<p>Date d'achat :<?= $article['date_achat'];?></p>
				<p>Date dernière réparation :<?= $article['date_derniere_rep'];?></p>
				<p><?= $article['description'];?></p>
				<img src="picture/<?=$article['image'];  ?>" style="height: 170px; max-width: 215px; ">
				<br/>
        		
				<a href="supprimer-articleEquip.php?id=<?= $article['id']; ?> " >
					<button style="color: white; background-color: red; margin-bottom: 10px; ">Supprimer 
					</button>
				</a>
				<a href="modifier-articleEquip.php?id=<?= $article['id']; ?> " >
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