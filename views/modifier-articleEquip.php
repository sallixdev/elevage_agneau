
<?php
$bdd = new PDO('mysql:host=127.0.0.1:3306;dbname=u164330380_espaceAdmin;','u164330380_gererelevage','Sallix33620.');


if(isset($_GET['id']) AND !empty($_GET['id'])){
	$getid = $_GET['id'];
	$recupArticle = $bdd->prepare('SELECT * FROM articlesequip WHERE id=?');
	$recupArticle->execute(array($getid));
	if($recupArticle->rowCount() > 0){
		$articleInfos = $recupArticle->fetch();
		$titre = $articleInfos['titre'];
		$dateR = $articleInfos['date_derniere_rep'];
		$description = str_replace('<br />','',$articleInfos['description']);
		if(isset($_POST['valider'])){
		$titre_saisi = htmlspecialchars($_POST['titre']);
		$description_saisie = nl2br(htmlspecialchars($_POST[description]));

		$dateRSaisie = date('Y-m-d', strtotime($_POST['dateRep']));
		
		$updateArticle = $bdd->prepare('UPDATE articlesequip SET titre = ? ,date_derniere_rep = ?, description = ? WHERE id = ?');
		$updateArticle->execute(array($titre_saisi,$dateRSaisie,$description_saisie,$getid));
		
		header('location:articlesEquip.php');
		}
	}else{
		echo("Aucun article trouvé.");
	}
}else{
	echo("Aucun identifiants trouvés.");
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<link href="style2.css" rel="stylesheet" type="text/css">
<title>Modifier un article</title>
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

	<div class="formGeneral">
		<form method="POST" action="">
			Nom <div></div><input type="text" name="titre" value="<?= $titre; ?>">
			<br><br>
			Date dernière réparation<div></div><input type="date" name="dateRep" value="<?= $dateR ?>">
			<br><br>
			Description<div></div><textarea name="description">  <?= $description; ?></textarea>
			<br>
			<input type="submit" name="valider">
			
			
		</form>
	</div>
	<script src="../js/index.js?version=1.0.4"></script>
 </body>
</html>