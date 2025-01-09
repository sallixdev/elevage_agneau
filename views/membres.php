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
	<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Afficher les membres</title>
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
	
	<!-- Afficher tous les membres -->
		<?php
			$recupUsers = $bdd->query('SELECT * FROM membres');
	while($user = $recupUsers->fetch()){
		?>
			<p><?= $user['pseudo'] ?></p>
		<?php
	}
		?>
	<!-- Fin afficher tous les membres -->
	<script src="../js/index.js?version=1.0.4"></script>
</body>
</html>