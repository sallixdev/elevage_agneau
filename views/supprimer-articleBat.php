<?php
$bdd = new PDO('mysql:host=127.0.0.1:3306;dbname=elevage;','root','');


if(isset($_GET['id']) AND !empty($_GET['id'])){
	$getid = $_GET['id'];
	$recupArticle = $bdd->prepare('SELECT * FROM articlesbat WHERE id=?');
	$recupArticle->execute(array($getid));
	if($recupArticle->rowCount() > 0){
		$deleteArticle = $bdd->prepare('DELETE FROM articlesbat WHERE id = ?');
		$deleteArticle->execute(array($getid));
		header('location: articlesBat.php');
	}else{
		echo("Aucun article trouvé.");
	}
}else{
	echo("Aucun identifiiants trouvés.");
}
?>