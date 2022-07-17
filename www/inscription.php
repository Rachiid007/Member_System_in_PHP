<?php
session_start();

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

// Si il n'y a pas de session actif.
if (empty($_SESSION['pseudo']))
{
    // Si ont appuie sur le bouton .
    if (!empty($_POST['submit'])) 
    {
        // Bien sécurise les variables .
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);
        $mdp = htmlspecialchars($_POST['mdp']);
        $mdp2 = htmlspecialchars($_POST['mdp2']);

        // Si tout les champs sont remplie .
        if (!empty($pseudo) AND !empty($email) AND !empty($mdp) AND !empty($mdp2))
        {
            // Si le pseudo est entre 6 et 24 caractére .
            if (strlen($pseudo)>=6 AND strlen($pseudo)<=24)
            {
                // Si le pseudo ccomporte des bon caractére
                if (preg_match('#^([a-zA-Z0-9-_.]{6,24})$#i', $pseudo))
                {
                    // Si l'adresse email contien un format valide .
                    if (filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        // Si l'adresse email est inferieur a 50 caractére .
                        if (strlen($email)<=50)
                        {
                            // Si le mot de passe est entre 6 et 24 caractére .
                            if (strlen($mdp)>=6 AND strlen($mdp)<=24)
                            {
                                // Si le mot de passe contien des des letre de A->Z majuscule ou minuscule ou des chifre de 0->9 .
                                if (preg_match('#^([a-zA-Z0-9-_~\'\#$@%*+!]{6,30})$#', $mdp))
                                {
                                    // Si les deux mot de passe sont identiques .
                                    if ($mdp == $mdp2) 
                                    {
                                        // Si il appuie sur la checkbox .
                                        if(!empty($_POST['ccu']))
                                        {
                                            // Verifier si Le nom d'utilisateur est déjà dans la base de donnees .
                                            $seach_pseudo = $bdd->prepare('SELECT COUNT(*) AS nbr_pseudo FROM user WHERE pseudo = :pseudo');
                                            $seach_pseudo->execute(array(
                                                'pseudo' => $pseudo
                                                ));

                                            // AFFICHER LA VALEUR ! (pseudo)( elle renvoie 0 ou 1 ( ya ou ya pas !)
                                            $data_pseudo = $seach_pseudo->fetch();
                                            if ($data_pseudo['nbr_pseudo'] == 0) 
                                            {
                                            $seach_pseudo->closeCursor();
                                                    
                                                // Verifier si l'adresse email est dans la base de donnees .
                                                $seach_email = $bdd->prepare('SELECT COUNT(email) AS nbr_email FROM user WHERE email = :email');
                                                $seach_email->execute(array(
                                                    'email' => $email
                                                    ));
                                                // AFFICHER LA VALEUR !(email)(elle renvoie 0 ou 1 ( ya ou ya pas !)
                                                $data_email = $seach_email->fetch();
                                                if ($data_email['nbr_email'] == 0)
                                                {
                                                $seach_email->closeCursor();

                                                	// chiffrer le mdp en sha1
                                                    $mdp = sha1($mdp);

                        	// Si l'utilisateur n'a fait aucunne erreur ,donc ont l'insert dans la Bdd !!

                                                    // Insertion des ligne dans la base de donnees .
                                                    $insert_membre = $bdd->prepare('INSERT INTO user(pseudo, email, mdp, date_inscription) VALUES(:pseudo, :email, :mdp, NOW())');
                                                    $insert_membre->execute(array(
                                                        'pseudo' => $pseudo,
                                                        'email' => $email,
                                                        'mdp' => $mdp));
                                                    $insert_membre->closeCursor();

                                                    // message de succès !!
                                                    $succes ='Vous êtes correctement inscrit !!!';

                                                // AFICHER LES msg d'erreur !!!
                                                    
                                                }else{
                                                    $erreur ='Cette adresse email est déjà utiliser !';
                                                    }
                                            }else{
                                                $erreur ='Ce nom d\'utilisateur est déjà utiliser !';
                                                }
                                        }else{
                                            $erreur ='Vous devez accepté Les Condition d\'utilisation !';
                                            }
                                    }else{
                                        $erreur ='Les deux mot de passe doivent étre identiques !';
                                        }
                                }else{
                                    $erreur ='Le mot de passe contient des caractères invalide !';
                                }
                            }else{
                                $erreur ='Le mot de passe doit contenir entre 6 et 24 caractères !';
                            }
                        }else{
                            $erreur ='Votre adresse email est trop longue !';
                            }
                    }else{
                        $erreur ='Le format de l\'adresse email n\'est pas valide !'; 
                        }
                }else{
                    $erreur ='Votre nom d\'utilisateur contient des caractére invalide !'; 
                    }
            }else{
                $erreur ='Le nom d\'utilisateur doit contenir entre 6 et 24 caractères !'; 
                }
        }else{
            $erreur ='Veuillez remplir tous les champs !'; 
            }
    }

}else {
    header('Location: profil.php');
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>La page d'inscription</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <h2>Inscription a l'espace membres</h2>

                <!-- Le formulaire d'inscription a l'espace membre -->

        <form class="col-xs-12" action="" method="post">
            <div class="form-group">
                <label for="pseudo">Nom d'utilisateur :</label>
                <input class="form-control" type="text" id="pseudo" name="pseudo" placeholder="Choisissez un nom d'utilisateur ..." value="<?php if (!empty($_POST['pseudo'])) { echo $_POST['pseudo']; } ?>"/>
            </div>

            <div class="form-group">
                <label for="email">Adresse email :</label>
                <input class="form-control" type="text" id="email" name="email" placeholder="Votre adresse email ..." value="<?php if (!empty($_POST['email'])) { echo $_POST['email']; } ?>"/>
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe :</label>
                <input class="form-control" type="password" id="mdp" name="mdp" placeholder="Choisissez un mot de passe ..." />
            </div>

            <div class="form-group">
                <label for="mdp2">Confirmer le mot de passe :</label>
                <input class="form-control" type="password" id="mdp2" name="mdp2" placeholder="Retaper votre mot de passe ..." />
            </div>

            <div class="form-group checkbox">
                <label>
                <input type="checkbox" name="ccu">J'accepte les <a href="#">Condition d'utilisation</a>.
                </label>
            </div>

            <div>
                <input type="submit" class="btn btn-primary" name="submit" value="M'inscrire !">
            </div>

        </form>

    <p class="text-danger"><?php if (!empty($erreur)) { echo $erreur; } ?><p/>
    <p class="text-success"><?php if (!empty($succes)) { echo $succes; } ?><p/>
        
      </div>
    </div>
  </div>
</body>
</html>