<?php
session_start();

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

    // Si le membre est Connecter
if (!empty($_SESSION['pseudo'])) 
{
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Espace membre</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container-fluid">
    <div class="container">
      <div class="row">
      
        <h2>Bienvenue sur PountoKDO</h2>
        <p>Bienvenue <strong><?php echo $_SESSION['pseudo']; ?></strong></p>
        <p>id : <?php echo $_SESSION['id']; ?></p>
        <p>Email : <?php echo $_SESSION['email']; ?></p>
        <p>Mot de passe (chiffré en sha1) : <?php echo $_SESSION['mdp']; ?></p>
        <a href='parametres.php'>Paramétres</a><br>
        <a href="news.php">Les news</a><br>
        <a href='deconnexion.php'>Me déconnecter</a>

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