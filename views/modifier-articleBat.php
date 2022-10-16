
<?php
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;','root','');
if(isset($_GET['id']) AND !empty($_GET['id'])){
	$getid = $_GET['id'];
	$recupArticle = $bdd->prepare('SELECT * FROM articlesbat WHERE id=?');
	$recupArticle->execute(array($getid));
	if($recupArticle->rowCount() > 0){
		$articleInfos = $recupArticle->fetch();
		$titre = $articleInfos['titre'];
		
		$description = str_replace('<br />','',$articleInfos['description']);
		if(isset($_POST['valider'])){
		$titre_saisi = htmlspecialchars($_POST['titre']);
		$description_saisie = nl2br(htmlspecialchars($_POST[description]));

		
		$updateArticle = $bdd->prepare('UPDATE articlesbat SET titre = ? , description = ? WHERE id = ?');
		$updateArticle->execute(array($titre_saisi,$description_saisie,$getid));
		
		header('location:articlesBat.php');
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
</head>

<body>
	<?php require('menu2.php'); ?>

	<div class="formGeneral">
		<form method="POST" action="">
			Nom <div></div><input type="text" name="titre" value="<?= $titre; ?>">
			<br><br>
			
			Description<div></div><textarea name="description">  <?= $description; ?></textarea>
			<br>
			<input type="submit" name="valider">
			
			
		</form>
	</div>
	<script src="../js/index.js?version=1.0.4"></script>
 </body>
</html>