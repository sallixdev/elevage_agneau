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
	
	<section id="sectionEspace">
		<h2 align="center" style="margin: 0; padding-top: 20px; font-size: 3em;">Bienvenue dans votre espace !</h2>
		<article id="articleEspace" >
		<a href="articles.php" class="divEspace">

			<h2>Vos animaux :</h2>
		<?php
		
		$recupArticles = $bdd->prepare('SELECT * FROM articles WHERE id_user = ? ');
		
		$recupArticles->execute(array[$_SESSION['id']]);
		
	while($article = $recupArticles->fetch()){
		?>
			
			<div>
				<ul>
					<li><?= $article['titre']; ?></li>
				</ul>
			</div>
		
		<?php
		} ?>
		</a>
		<a href="articlesBat.php" class="divEspace" >
		<h2>Vos bâtiments :</h2>
	<?php
	
	$recupArticles2 = $bdd->prepare('SELECT * FROM articlesbat WHERE id_user = ? ');
		
		$recupArticles2->execute( [$_SESSION['id']]);
		
	while($article2 = $recupArticles2->fetch()){
		?>
			
			<div>
				
				<ul>
					<li><?= $article2['titre']; ?></li>
				</ul>
				
				
			</div>

			
			<?php
	}
	?>

		</a>
		<a href="articlesEquip.php" class="divEspace" >
		<h2>Vos équipements :</h2>
	<?php
	
	$recupArticles3 = $bdd->prepare('SELECT * FROM articlesequip WHERE id_user = ? ');
		
		$recupArticles3->execute( [$_SESSION['id']]);
		
	while($article3 = $recupArticles3->fetch()){
		?>
			
			<div>
				
				<ul>
					<li><?= $article3['titre']; ?></li>
				</ul>
				
				
			</div>

			
			<?php
	}
	?>
	</a>
	</article>
	</section>
	<script src="../js/index.js?version=1.0.4"></script>
</body>
</html>