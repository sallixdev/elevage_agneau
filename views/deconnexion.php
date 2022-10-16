<?php
	session_start();
$_SESSION = array();
session_destroy();
header('location: http://localhost/sites/elevage_agneau/index.php');
?>
