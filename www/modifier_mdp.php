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
		$nvMdp = htmlspecialchars($_POST['nvMdp']);
		$mdp2 = htmlspecialchars($_POST['mdp2']);

		if (!empty($mdp) AND !empty($nvMdp) AND !empty($mdp2)) 
		{
			// si le mdp est entre 6 et 24 caractére
			if (strlen($mdp)>=6 AND strlen($mdp)<=24) 
			{	
				// si le mdp comporte des bon caractére
				if (preg_match('#^([a-zA-Z0-9-_~\'\#$@%*+!]{6,30})$#', $mdp))
				{
                    $mdp = sha1($mdp);
                    // si le mdp est egal aux mdp de la bdd
                    if ($mdp == $_SESSION['mdp'])
                    {
                    	// si le nvMdp est entre 6 et 24 caractére
                    	if (strlen($nvMdp)>=6 AND strlen($nvMdp)<=24) 
                    	{
                    		//si le nouveau mdp contien des bon caractére 
                    		if (preg_match('#^([a-zA-Z0-9-_~\'\#$@%*+!]{6,30})$#', $nvMdp)) {
                    			
                                // si le les deux mdp sont identique
                                if ($nvMdp == $mdp2) {

                    				// chifrer le nvMdp en sha1
                    				$nvMdp = sha1($nvMdp);

                                    // mettre a jour le mdp dans la bdd
                    				$update_nvMdp = $bdd->prepare('UPDATE user SET mdp =:nvMdp WHERE id=:id');
                    				$update_nvMdp->execute(array(
                    					'nvMdp' => $nvMdp,
                    					'id' => $_SESSION['id']
                                        ));
                    				$update_nvMdp->closeCursor();

                                    // affecter la variable $_SESSION['mdp']
                                    $_SESSION['mdp'] = $nvMdp;

                    				$succes = 'Votre mot de passe a été mis à jour !';

                                        // msg d'erreur !
                    			}else {
                    				$erreur = 'Les deux mot de passe doivent étre identiques !';
                    			 }
                    		}else {
                    			$erreur = 'Le mot de passe comporte des caractères invalide !';
                    		  }
                    	}else {
                    		$erreur = 'Le nouveau mot de passe doit contenir entre 6 et 24 caractères !';
                    	   }
                    }else {
                    	$erreur = 'Le mot de passe actuel est incorrect !';
                        }
				}else {
					$erreur = 'Le mot de passe actuel est incorrect !';
				    }
			}else {
				$erreur = 'Le mot de passe actuel est incorrect !';
                }
		}else {
			$erreur = 'Veuillez remplir tous les champs !';
            }
	}

}else {
	header('Location: connexion.php');
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Modifier vos données</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container-fluid">
    <div class="container">
      <div class="row">

      <h2>Modifier votre mot de passe</h2>
    <p>Bienvenue <strong><?php echo $_SESSION['pseudo']; ?></strong></p>

    <form class="col-xs-12" action="" method="POST">

        <div class="form-group">
            <label for="mdp">Mot de passe actuel :</label>
            <input class="form-control" type="password" id="mdp" name="mdp" placeholder="Entrée votre mot de passe actuel ..." />
        </div>

        <div class="form-group">
            <label for="nvMdp">Nouveau mot de passe :</label>
            <input class="form-control" type="password" id="nvMdp" name="nvMdp" placeholder="Choisissez un nouveau mot de passe ..." />
        </div>

        <div class="form-group">
            <label for="mdp2">Confirmer le mot de passe :</label>
            <input class="form-control" type="password" id="mdp2" name="mdp2" placeholder="Retaper votre mot de passe ..." />
        </div>

        <div>
            <input type="submit" class="btn btn-primary" name="submit" value="Changez mon mot de passe">
        </div>

    </form>

            <div>
                <p class="text-danger"><?php if (!empty($erreur)) { echo $erreur; } ?><p/>
                <p class="text-success"><?php if (!empty($succes)) { echo $succes; } ?><p/>
            </div>
        
      </div>
    </div>
  </div>
</body>
</html>