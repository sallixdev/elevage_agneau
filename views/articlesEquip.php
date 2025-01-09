<?php
	session_start();
$bdd = new PDO('mysql:host=127.0.0.1:3306;dbname=u164330380_espaceAdmin;','u164330380_gererelevage','Sallix33620.');

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
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-L17QZRH9VP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-L17QZRH9VP');
</script>
<link rel="icon" type="image/png" sizes="16x16" href="https://gererelevage.com/img/mon_logo.svg">
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