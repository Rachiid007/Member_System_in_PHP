<?php
session_start();

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

    // Si le membre est connecter
if (!empty($_SESSION['pseudo'])) 
{	
	// si on appuis sur boutton submit
	if (!empty($_POST['submit'])) {
		
		// bien sécuriser les variable !!!!
		$nvEmail = htmlspecialchars($_POST['nvEmail']);
		$email2 = htmlspecialchars($_POST['email2']);
		$mdp = htmlspecialchars($_POST['mdp']);
		
		// si les variable sont bien remplie
		if (!empty($nvEmail) AND !empty($email2) AND !empty($mdp)) 
		{
			// si le format de l'email est valide
			if (filter_var($nvEmail, FILTER_VALIDATE_EMAIL)) 
			{
				// si l'email contient mon de 50 caractére
				if (strlen($nvEmail)<=50) 
				{
					// rechercher l'email dans la bdd
					$rechercher_email = $bdd->prepare('SELECT COUNT(email) AS nbr_email FROM user WHERE email = :email');
                    $rechercher_email->execute(array(
                        'email' => $nvEmail
                        ));
                    // AFFICHER LA VALEUR !(email)(elle renvoie 0 ou 1 ( ya ou ya pas !)
                    $donnees_email = $rechercher_email->fetch();
                    if ($donnees_email['nbr_email'] == 0)
                    {
                    $rechercher_email->closeCursor();

						// si les deux email sont bien identique !
						if ($nvEmail == $email2) 
						{	
							// si le mdp est entre 6 et 24 caractére
							if (strlen($mdp)>=6 AND strlen($mdp)<=24) 
							{
								// si le mdp comporte les bon caractére
								if (preg_match('#^([a-zA-Z0-9-_~\'\#$@%*+!]{6,30})$#', $mdp)) 
								{	
									// chiffrer le mdp en sha1
									$mdp = sha1($mdp);
									
									// si le mdp est égal au mdp saisie par l'utilisateur
									if ($mdp == $_SESSION['mdp'])
									{
										// modifier l'email dans la bdd
										$update_email = $bdd->prepare('UPDATE user SET email =:nvEmail WHERE id =:id');
										$update_email->execute(array(
											'nvEmail' =>$nvEmail,
											'id' => $_SESSION['id']
											));
										$update_email->closeCursor();

										$_SESSION['email'] = $nvEmail;

										$succes = 'Votre adresse email a été mise à jour !';

											// les msg d'erreur !
									}else {
										$erreur = 'Le mot de passe est incorrect !';
									}
								}else {
									$erreur = 'Le mot de passe est incorrect !';
								}
							}else {
								$erreur = 'Le mot de passe est incorrect !';
							}
						}else {
							$erreur = 'Les deux adresse email doivent étre identiques !';
						}
					}else {
						$erreur = 'Cette adresse email est déjà utiliser !';
						}
				}else {
					$erreur = 'Votre adresse est trop longue !';
					}
			}else {
				$erreur = 'Le format de l\'adresse email n\'est pas valide !';
				}	
		}else {
			$erreur = 'Veillez remplir tout les champs ';
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

        <h2>Modifier votre adresse email</h2>
          <p>Bienvenue <strong><?php echo $_SESSION['pseudo']; ?></strong></p>
  
		    <form class="col-xs-12" action="" method="POST">

		      <div class="form-group">
		      	<label for="email_default">Adresse email actuel :</label>
		      	<input class="form-control" id="email_default" type="text" value="<?php echo $_SESSION['email']; ?>" disabled>
		      </div>

		      <div class="form-group">
		        <label for="nvEmail">Nouvelle adresse email :</label>
		        <input class="form-control" type="text" id="nvEmail" name="nvEmail" placeholder="Votre adresse email ..." value="<?php if (!empty($_POST['nvEmail'])) { echo $_POST['nvEmail']; } ?>"/>
		      </div>

		      <div class="form-group">
		        <label for="email2">Confirmer votre une nouvelle adresse email :</label>
		        <input class="form-control" type="text" id="email2" name="email2" placeholder="Retaper votre une nouvelle adresse email ..." />
		      </div>

		      <div class="form-group">
		        <label for="mdp">Mot de passe :</label>
		            <input class="form-control" type="password" id="mdp" name="mdp" placeholder="Entrée mot de passe ..." />
		      </div>

		      <div>
		      	<input type="submit" class="btn btn-primary" name="submit" value="Changez mon adresse email"/>
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