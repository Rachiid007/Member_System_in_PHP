<?php
session_start();

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

    // Si le membre n'est pas connecter
if (!empty($_SESSION['pseudo'])) 
{ ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Mes paramètres</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container-fluid">
    <div class="container">
      <div class="row">
      	<h2>Mes paramètres</h2>
		      <p>Bienvenue <strong><?php echo $_SESSION['pseudo']; ?></strong></p>

      		<a href="modifier_email.php">Modifier mon adresse email</a><br>
      		<a href="modifier_mdp.php">Modifier mon mot de passe</a><br>
          <a href="desinscription.php">Me désinscrire</a>
      </div>
    </div>
  </div>
</body>
</html>

<?php
}else {
	header('Location: connexion.php');
}
?>