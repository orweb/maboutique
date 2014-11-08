<?php 

require_once("inc/init.inc.php");

if($_POST)
{

   //---------------------VERIF CARACT ------------------------------------------------------------------VERIF CARACT--  PREG MATCH------


if( !pregMatch($_POST['pseudo'] ) )
{
    $msg .= "<div class='bg-danger' style='padding:15px'><p>Pseudo : Caractères acceptés : A à Z et de 0 à 9.</p></div>";
}
    // ATTENTION si on autorise les caractères spéciaux dans le champ mdp, la taille en octet sera supérieur au nombre de caractères (incohérence). on pourra mettre une taille plus grande.
if( !isSizeValid($_POST['pseudo'], 4,12))
{
    $msg .= "<div class='bg-danger' style='padding:15px'><p>Choisissez un pseudo entre 4 et 12 caractères inclus.</p></div>";
}

if( !isSizeValid($_POST['mdp'],4,14))
{
    $msg .= "<div class='bg-danger' style='padding:15px'><p>Choisissez un mdp entre 4 et 14 caractères inclus.</p></div>";
}



 //------------------------------SI PAS ERREUR Inscription---------------------------------------------N--ERROR-INSCRIPTION-MYSQL--MD5



    if(empty($msg))// si la variable $msg est vide, c'est qu'il n'y a pas eu d'erreur, on peut donc lancer la requête d'inscription
	{
		$membre = executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");// vérif de l'existence du pseudo dans la base
		if($membre->num_rows > 0) //si num rows est sup à zéro, c'est qu'il retourne un enregistrement, donc le pseudo existe
		{
			$msg .= "<div class='bg-danger' style='padding:15px'><p>Ce pseudo existe déjà.</p></div>";
		}
		else
		{
			foreach($_POST AS $indice => $valeur)
			{
				$_POST[$indice] = htmlentities($valeur, ENT_QUOTES); // prévenir les injections de code
			}

            $mdp = $_POST['mdp'];

            $password = password_hash($mdp, PASSWORD_DEFAULT);
//            var_dump($hash);

            //extract() est une fonction prédéfinie qui transforme les indices d'un tableau array en variables qui contiennent leurs valeurs
            //attention, ne fonctionne pas sur un tableau qui a des indices numériques, car une variable ne peut pas commencer par un chiffre
            extract($_POST);

            executeRequete("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse) VALUES ('$pseudo', '$password', '$nom', '$prenom', '$email', '$sexe', '$ville', '$cp', '$adresse')");
            $msg .= "<div class='bg-success' style='padding:15px'><p>Inscription OK</p></div>";
		}
	}
}

//--------------------------------------REQUIRE-----------
require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");

//--------------------------------------REQUIRE-----------


echo $msg;

//-------------------------------Formulaire incription------------------------------------------------FORMULAIRE INSCRIPTION--$_POST----
?>

		<div class="page-header">
		<h1 class="bigh1"><i class="glyphicon glyphicon-ok bigh1"></i> Inscription</h1>
		</div>
		
		<div class="col-md-6 col-md-offset-3"> <!-- on demande une largeur de 6 colonnes avec 3 colonnes de marge.-->
		
			<form method="post" action="inscription.php">

<?php require_once("inc/form_user.inc.php")?>
                <!--mot de passe-->
                <label for="mdp">Mot de passe</label>
                <input class="form-control" type="password" id="mdp" name="mdp" value="
<?php if(isset($_POST['mdp'])) echo $_POST['mdp'];

                ?>
" placeholder="Mot de passe..." required maxlength="14" /><br/>

					<input class="btn btn-warning col-md-3" type="submit" name="inscription" value="inscription" id="submit" />


		
		</div> <!-- fermeture div 6 colonnes formulaire --> 
		
<?php

require_once("inc/bas_de_site.inc.php");

?>
		
		
		