<?php
session_start();

// Fichier de connexion a la Base de donnees .
include_once('include/connexion_bdd.php');

if (empty($_SESSION['pseudo']))
{
    // Si ont appuie sur le bouton submit .
    if (!empty($_POST['submit']))
    {
        // sécurise bien les variables .
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mdp = htmlspecialchars($_POST['mdp']);

        // Si tout les champs sont bien remplie .
        if (!empty($pseudo) AND !empty($mdp))
        {
            // si le pseudo est entre 6 et 24 caractére
            if (strlen($pseudo)>=6 AND strlen($pseudo)<=24)
            {
                // Si le pseudo comporte des bon caractére
                if (preg_match('#^([a-zA-Z0-9-_.]{6,24})$#i', $pseudo))
                {
                    // rechercher le pseudo dans la bdd
                    $rechercher_pseudo = $bdd->prepare('SELECT COUNT(*) AS nbr_pseudo FROM user WHERE pseudo = :pseudo');
                    $rechercher_pseudo->execute(array(
                        'pseudo' => $pseudo
                        ));
                    // si il le trouve
                    $donnees_pseudo = $rechercher_pseudo->fetch();
                    if ($donnees_pseudo['nbr_pseudo'] == 1)
                    {
                    $rechercher_pseudo->closeCursor();

                        if (strlen($mdp)>=6 AND strlen($mdp)<=24)
                        {
                            if (preg_match('#^([a-zA-Z0-9-_~\'\#$@%*+!]{6,30})$#', $mdp))
                            {
                                // rechercher le mdp dans la bdd
                                $rechercher_mdp = $bdd->prepare('SELECT mdp FROM user WHERE pseudo = :pseudo');
                                $rechercher_mdp->execute(array(
                                    'pseudo' => $pseudo
                                    ));
                                // recuperer le mdp de la bdd
                                $donnees_mdp = $rechercher_mdp->fetch();
                                // chiffre le mdp
                                $mdp = sha1($mdp);

                                // si le mdp est égal au mdp saisie par l'utilisateur
                                if ($mdp == $donnees_mdp['mdp'])
                                {
                                $rechercher_mdp->closeCursor();

                                    // je selectionne tout la ligne dans la bdd
                                    $recuperer_tout_donnees = $bdd->prepare('SELECT * from user WHERE pseudo = :pseudo');
                                    $recuperer_tout_donnees->execute(array(
                                        'pseudo' => $pseudo
                                        ));
                                    // je recupere toute les donnees
                                    $donnees_bdd = $recuperer_tout_donnees->fetch();

                                    $_SESSION['id'] = $donnees_bdd['id'];
                                    $_SESSION['pseudo'] = $donnees_bdd['pseudo'];
                                    $_SESSION['email'] = $donnees_bdd['email'];
                                    $_SESSION['mdp'] = $donnees_bdd['mdp'];

                                    $recuperer_tout_donnees->closeCursor();

                                    header('Location: profil.php');

                                    // Les msg d'erreur
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
                        $erreur = 'Aucun compte n\'est relié a ce nom d\'utilisateur !';
                        }
                }else{
                    $erreur = 'Aucun compte n\'est relié a ce nom d\'utilisateur !';
                    }
            }else {
                $erreur = 'Aucun compte n\'est relié a ce nom d\'utilisateur !';
                }
        }else{
            $erreur = 'Veuillez remplir tous les champs !';
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
	  <title>La page de connexion</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="container">
            <div class="row">

                <h2>Connexion a l'espace membres</h2>

                    <form class="col-xs-12" action="" method="POST">
                        <div class="form-group">
                            <label for="pseudo">Nom d'utilisateur :</label>
                            <input type="text" id="pseudo" name="pseudo" class="form-control" placeholder="Votre nom d'utilisateur ..."
                            value="<?php if (!empty($_POST['pseudo'])) { echo $_POST['pseudo']; } ?>"/ >
                        </div>
                        <div class="form-group">
                            <label for="mdp">Mot de passe :</label>
                            <input type="password" id="mdp" name="mdp" class="form-control" placeholder="Votre mot de passe ..."/>
                        </div>

                        <div>
                            <input type="submit" class="btn btn-primary" name="submit" value="Se connecter">
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