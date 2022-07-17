<?php
session_start();

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

    // Si le membre est bien connecter
if (!empty($_SESSION['pseudo']))
{
	if (!empty($_POST['submit'])) 
	{
		$mdp = htmlspecialchars($_POST['mdp']);

		if (!empty($mdp))
		{
			if (strlen($mdp)>=6 AND strlen($mdp)<=24) 
			{
				if (preg_match('#^([a-zA-Z0-9-_~\'\#$@%*+!]{6,30})$#', $mdp))
				{
					if (!empty($_POST['accepte']))
					{
						$mdp = sha1($mdp);
						if ($mdp == $_SESSION['mdp']) 
						{
							$delete_account = $bdd->prepare('DELETE FROM user WHERE id = :id');
							$delete_account->execute(array('id' => $_SESSION['id']));
							$delete_account->closeCursor();

							header('Location: inscription.php');
							
						}else {
							$erreur = 'Le mot de passe est incorrect !';
						}
					}else {
						$erreur = 'Vous devez cocher la case "Je souhaite supprimer mon compte".';
					}
				}else {
					$erreur = 'Le mot de passe est incorrect !';
					}
			}else {
				$erreur = 'Le mot de passe est incorrect !';
				}
		}else {
			$erreur = 'Veillez remplir tout les champs ';
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Désinscription</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container-fluid">
    <div class="container">
      <div class="row">

      	<h2>Désinscription</h2>

			<p>Bienvenue <strong><?php echo $_SESSION['pseudo']; ?></strong></p>

			<form class="col-xs-12" action="" method="POST">

		        <div class="form-group">
		            <label for="mdp">Mot de passe actuel :</label>
		            <input class="form-control" type="password" id="mdp" name="mdp" placeholder="Entrée votre mot de passe ..." />
		        </div>

		        <div class="form-group checkbox">
	                <label>
	                <input type="checkbox" name="accepte">Je souhaite supprimer mon compte.
	                </label>
	            </div>

	            <div>
                	<input type="submit" class="btn btn-danger" name="submit" value="Supprimer mon compte !">
            	</div>

	        </form>

	        <div>
                <p class="text-danger"><?php if (!empty($erreur)) { echo $erreur; } ?><p/>
            </div>


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