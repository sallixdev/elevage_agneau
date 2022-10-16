<?php
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;','root','');
if(isset($_GET['id']) AND !empty($_GET['id'])){
	$getid = $_GET['id'];
	$recupArticle = $bdd->prepare('SELECT * FROM articlesequip WHERE id=?');
	$recupArticle->execute(array($getid));
	if($recupArticle->rowCount() > 0){
		$deleteArticle = $bdd->prepare('DELETE FROM articlesequip WHERE id = ?');
		$deleteArticle->execute(array($getid));
		header('location: articlesEquip.php');
	}else{
		echo("Aucun article trouvé.");
	}
}else{
	echo("Aucun identifiiants trouvés.");
}
?>