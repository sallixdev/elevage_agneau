<?php
$bdd = new PDO('mysql:host=127.0.0.1:3306;dbname=u164330380_espaceAdmin;','u164330380_gererelevage','Sallix33620.');


if(isset($_GET['id']) AND !empty($_GET['id'])){
	$getid = $_GET['id'];
	$recupArticle = $bdd->prepare('SELECT * FROM articles WHERE id=?');
	$recupArticle->execute(array($getid));
	if($recupArticle->rowCount() > 0){
		$deleteArticle = $bdd->prepare('DELETE FROM articles WHERE id = ?');
		$deleteArticle->execute(array($getid));
		header('location: articles.php');
	}else{
		echo("Aucun article trouvé.");
	}
}else{
	echo("Aucun identifiiants trouvés.");
}
?>