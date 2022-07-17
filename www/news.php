<?php
session_start();

// tout en français (time)
setlocale(LC_ALL,'French');

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

// Si le membre est connecter
if (!empty($_SESSION['pseudo'])) 
{
  setlocale(LC_ALL,'French');

  // ON SELECT TOUT LES CHAMPS DE LA TABLE billets ET ONT LES ORDONNE PAR ORDRE DECROISSANT !
  $recherche_billet = $bdd->query('SELECT id,titre,contenu,date_creation FROM billets ORDER BY date_creation DESC');

  ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Les dernières News</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container-fluid">
    <div class="container">
        <div class="row">

          <h2>Les dernières News</h2>

      <?php while ($donnees = $recherche_billet->fetch()) { ?>
        <p">
          <strong> <?php echo $donnees['titre']; ?> : </strong>
          
          Publié le <?php   
          $date_formater = utf8_encode(strftime('%d %B &agrave %Hh%M',strtotime($donnees['date_creation'])));
          echo $date_formater; ?><br>

          <?php echo nl2br(htmlspecialchars($donnees['contenu'])); ?><br>

          <a href='commentaire.php?billet=<?php echo $donnees['id']; ?>'>Les commentaires</a>
        </p>
      <?php } $recherche_billet->closeCursor(); ?>
      
    </div>
    </div>
  </div>
</body>
</html>

<?php
}else {
    header('Location: profil.php');
}
?>