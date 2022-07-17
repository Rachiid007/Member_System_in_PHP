<?php
session_start();

if (!empty($_SESSION['pseudo'])) {

	unset($_SESSION['pseudo']);

	session_unset();

	session_destroy();

	header ('Location:connexion.php');
}else {
	header('Location: connexion.php');
}
?>