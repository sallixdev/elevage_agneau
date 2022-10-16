<?php
	session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;','root','');
	if(!$_SESSION['mdp']){
		header('Location: connexion.php');
	}

if(isset($_POST['envoi'])){
	if(!empty($_POST['titre']) and !empty($_POST['description'])){
		
		
		$image = $_FILES['image'];
		
		$imageName = $_FILES['image']['name'];
		$imageTmpName = $_FILES['image']['tmp_name'];
		$imageSize = $_FILES['image']['size'];
		$imageError = $_FILES['image']['error'];
		$imageType = $_FILES['image']['type'];
		
		$imageExt = explode('.',$imageName);
		$imageActualExt = strtolower(end($imageExt));
		
		$allowed = array('jpg','jpeg','png');
		
		if(in_array($imageActualExt,$allowed)){
			if($imageError === 0){
				if($imageSize < 2000000){
					$ImageNameNew = uniqid('', true).".".$imageActualExt;
					$imageDestination = 'picture/'.$ImageNameNew;
					$titre = htmlspecialchars($_POST['titre']);
					

					$description = nl2br(htmlspecialchars($_POST['description']));
					
					
					move_uploaded_file($imageTmpName, $imageDestination);
					$insererArticle = $bdd->prepare('INSERT INTO articlesbat(titre, description,image,id_user) VALUES(?,?,?,?)');
		$insererArticle->execute(array($titre,$description,$ImageNameNew,$_SESSION['id']));
					header("location: articlesBat.php?uploadsuccess");
					
					
				}else {
					echo("Votre fichier est trop volumineux...");
				}
			} else{
				echo("Il y a eu une erreur lors du téléchargement du fichier...");
			}
		} else {
			echo("Ce fichier n'est pas une image...");
		}
	}else{
		echo("veuillez compléter tous les champs...");
	}
}

?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<link href="style2.css" rel="stylesheet" type="text/css">
<title>Publier un article</title>
</head>
<?php require('menu2.php'); ?>
	
<body>
	<section>
		<div class="formGeneral">
			
			<div>
				<form method="POST" action="" align="center" enctype="multipart/form-data" >

					Titre <div></div><input type="text" name="titre">
					<br><br>
					Description<div></div><textarea name="description"></textarea>
					<br><br>
					<input type="file" name="image">
					</br><br>
					<input type="submit" name="envoi">
					
					


				</form>
			</div>
		</div>
	</section>
	<script src="../js/index.js?version=1.0.4"></script>
 </body>
</html>