<?php
session_start();

// tout en français (time)
setlocale(LC_ALL,'French');

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

// Si le membre est connecter
if (!empty($_SESSION['pseudo'])) 
{

	// VERIFIER SI LE id(du billet) QU'ON DEMANDE EST BIEN DANS LA TABLE billet
	if (!empty($_GET['billet'])) 
	{
		$verifier_billet = $bdd->prepare('SELECT COUNT(id) AS nbr_billet FROM billets WHERE id = :id_billet');
		$verifier_billet->execute(array(
			'id_billet' => $_GET['billet']
			));
		$donnees_billet = $verifier_billet->fetch();

		// SI IL EST DEDANS EST BIEN ONT CHARGE LE billet DEMANDER ET LES commentaires
		if ($donnees_billet['nbr_billet']==1) 
		{
	 		$recherche_billet = $bdd->prepare('SELECT titre,contenu,date_creation FROM billets 
	 			WHERE id =:id_billet ORDER BY date_creation DESC');
			$recherche_billet->execute(array(
				'id_billet' => $_GET['billet']
				));
			$donnees = $recherche_billet->fetch();

			$date_formater_billet = utf8_encode(strftime('%d %B &agrave %Hh%M',strtotime($donnees['date_creation'])));

	 		// SELECTIONNER LES DONNER DANS LA TABLE commentaire
			$recherche_commentaires = $bdd->prepare('SELECT commentaires.commentaire AS le_commentaire, commentaires.date_commentaire, user.pseudo AS pseudo_user
				FROM commentaires
				INNER JOIN user
				ON commentaires.id_user = user.id
				INNER JOIN billets
				ON commentaires.id_billet = billets.id
				WHERE id_billet = :id_billet
				ORDER BY date_commentaire DESC');
			$recherche_commentaires->execute(array(
				'id_billet' => $_GET['billet']
				));
		}else {
	 		header('Location: news.php');
	 		}

	}else {
		header('Location: news.php');
		}


		// TESTER LE FORMULAIRE
	if (!empty($_POST['submit'])) 
	{
		$commentaire = htmlspecialchars($_POST['commentaire']);

		// inserer le commentaire
		if (!empty($commentaire)) {
			$inserer_commentaire = $bdd->prepare('
			INSERT INTO commentaires(id_billet,id_user,commentaire,date_commentaire) 
			VALUES(:id_billet,:id_user,:commentaire,NOW())
			');
			$inserer_commentaire->execute(array(
				'id_billet' =>$_GET['billet'],
				'id_user' =>$_SESSION['id'],
				'commentaire' =>$commentaire
			));
			$inserer_commentaire->closeCursor();
			header('Location:commentaire.php?billet='.$_GET['billet']);
		}else {
			$erreur = 'Les champs sont vide !';
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Les commentaires</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="container-fluid">
		<div class="container">
			<div class="row">

						<h2>Les News</h2>

			<!-- LE BILLET EN QUESTION QUI A ETE DEMANDE PAR L'UTILISATEUR !-->
					<a href='news.php'>Retour à la liste des billets</a>

					<p>
						<strong><?php echo $donnees['titre']; ?> : </strong>
						Publié le <?php echo $date_formater_billet; ?> <br>
          				<?php echo nl2br(htmlspecialchars($donnees['contenu']));

						$recherche_billet->closeCursor();
						?><br><br>
					</p>


			<!-- LES COMMENTAIRES -->
					<h2>Les commentaires</h2>

				<?php while ($donnees_comment = $recherche_commentaires->fetch()) { ?>

				<p>
					<strong><?php echo $donnees_comment['pseudo_user']; ?> : </strong>
					Commenté le 
					<?php $date_formater_commentaire = utf8_encode(strftime('%d %B &agrave %Hh%M',strtotime($donnees_comment['date_commentaire'])));
					echo $date_formater_commentaire; ?><br>

					<?php echo nl2br(htmlspecialchars($donnees_comment['le_commentaire'])); ?><br>
				</p>

				<?php } $recherche_commentaires->closeCursor(); ?>

				<form class="col-xs-12" action="" method="POST">
					<div class="form-group">
						<label for='commentaire'>Commentaire :</label>
						<textarea class="form-control" id="commentaire" name='commentaire' rows="5"></textarea>
					</div>

				    <div>
				        <input type="submit" class="btn btn-primary" name="submit" value="Publier">
				    </div>
				</form>
					<div>
                        <p class="text-danger"><?php if (!empty($erreur)) { echo "$erreur"; } ?><p/>
                    </div>

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