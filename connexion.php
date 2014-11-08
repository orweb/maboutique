<?php

require_once( "inc/init.inc.php" );

if(isset( $_GET['action'] ) && $_GET['action'] == 'deconnexion')
{
    //unset($_SESSION['utilisateur']); ici, permettrait de vider tt ce kil y a dans utilisateur
    $_SESSION = array();
    session_destroy(); // supprime la session
}

if(utilisateurEstConnecte())
{
    header("location:profil.php");
}

if($_POST)

{
    if(strpos($_POST['pseudo'], '@') !== false)
    {
        $selection_membre = executeRequete("SELECT * FROM membre WHERE email='$_POST[pseudo]'");
        if($selection_membre->num_rows != 0) //si il y a bien au moins 1 enregistrement retourné par la requête
        {
            $membre = $selection_membre->fetch_assoc();
            $msg .= "<div class='bg-success' style='padding:15px'><p>Bonjour " . $membre['prenom'] . "</p></div>";
            $ok = 1;
        }
        else
        {
            $msg .= "<div class='bg-danger' style='padding:15px'><p>Erreur de mail</p></div>";
            $ok = 0;
        }

    }
    else
    {
        $selection_membre = executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
        if($selection_membre->num_rows != 0) //si il y a bien au moins 1 enregistrement retourné par la requête
        {
            $membre = $selection_membre->fetch_assoc();
            $msg .= "<div class='bg-success' style='padding:15px'><p>Bonjour " . $membre['prenom'] . "</p></div>";
            $ok = 1;

        }
        else
        {
            $msg .= "<div class='bg-danger' style='padding:15px'><p>Erreur de pseudo</p></div>";
            $ok = 0;
        }
    }

    if($ok == 1)
    {
        $hash = $membre['mdp'];


        if (password_verify($_POST['mdp'] , $hash))
        {
            echo 'Le mot de passe est valide !';



                foreach($membre AS $indice => $element)
                {
                    if($indice != 'mdp') // on n'inscrit pas le mdp dans la session.
                    {
                        $_SESSION['utilisateur'][$indice] = $element; // déclaration d'un ARRAY multidim. 1er indice de session 'utilisateur'. pour cette indice, on déclare une valeur qui sera un deuxième ARRAY. Boucle pour déclarer chaque $indice de cet array, attribution de la valeur correspondante (récupération des indices et valeurs de l'array $membre.
                    }
                }
                header("location:profil.php"); //envoie sur la page profil.
            }
            else
            {
                $msg .= "<div class='bg-danger' style='padding:15px'><p>Mot de passe invalide</p></div>";

                debug($_POST['mdp']);
            }

    }

}


require_once( "inc/haut_de_site.inc.php" );
require_once( "inc/menu.inc.php" );

echo $msg; //affichage des messages (erreur, validation, etc

//debug($_SESSION);

?>

    <div class="page-header">
        <h1 class="bigh1"><i class="glyphicon glyphicon-off bigh1"></i> Connexion</h1>
    </div>

    <div class="col-md-4 col-md-offset-4">

        <form method="post" action="connexion.php">

            <label for="pseudo">Pseudo ou mail</label>
            <input class="form-control" type="text" id="pseudo" name="pseudo" value="" placeholder="Pseudo..." required
                   maxlength="35"/>

            <label for="mdp">Mot de passe</label>
            <input class="form-control" type="password" id="mdp" name="mdp" value="" placeholder="Mot de passe..."
                   required maxlength="35"/>

				<br/>

            <input class="btn btn-warning col-md-3" type="submit" name="connexion" value="connexion" id="submit"/>

        </form>

    </div>

<?php

require_once( "inc/bas_de_site.inc.php" );

?>