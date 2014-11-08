<?php


require_once("../inc/init.inc.php");

//-------------------------AUTORISATION ADMIN

if(!utilisateurEstConnecteEtEstAdmin())
{
	header("location:../connexion.php");
	exit(); // permet d'arrêter l'exécution du script dans le cas où qqn pourrait tenter des injections de code via l'url
	// le reste du code n'est pas exécuté
}

// ------------------------Suppresion de photo --------------------------------------------------------------SUPPRESSION--PHOTO--ARTICLE SQL

if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{

	$resultat = executeRequete("SELECT * FROM article WHERE id_article=$_GET[id_article]");
	$article_a_supprimer = $resultat->fetch_assoc();
	$chemin_photo_a_supprimer = RACINE_SERVER .$article_a_supprimer['photo'];

	if(!empty($article_a_supprimer['photo']) && file_exists($chemin_photo_a_supprimer))  // si chemin photo, file exist verifdi si le fichier existe à l'endroit indique
	{
	 unlink($chemin_photo_a_supprimer); // unlik cxl le ficier cible
	}

	executeRequete("DELETE FROM article WHERE id_article = '$_GET[id_article]'");
	$_GET['action'] = 'affichage';

}

//----------------------------------ENREGISTREMENT D'ARTICLES

/* if(isset($_POST['enregistrement']))
{
	debug($_POST);
	debug($_FILES);
} */

if(isset($_POST['enregistrement']))
{
    debug($_POST);
	// VERIFICATION DE LA REFERENCE (unique ou non)
	$reference = executeRequete("SELECT * FROM article WHERE reference = '$_POST[reference]'");
	//debug($reference);
	if($reference->num_rows > 0 && isset($_GET['action']) && $_GET['action'] == 'ajout')// EXISTE DEJA DANS LA BDD. ne vérifie pas en cas de modification.
	{
		$msg .= "<div class='bg-danger' style='padding:15px'><p>Cette référence existe déjà dans la BDD.</p></div>";
	}
 	else // REFERENCE UNIQUE
	{
		$photo_bdd = "";// éviter les undefined, car sera forcément présent dans la requête d'insertion

		if(isset($_GET['action']) && $_GET['action'] == 'modification')
		{
			$photo_bdd = $_POST['photo_actuelle'];
		}

		if(!empty($_FILES['photo']['name']))
		{
			if(verificationExtensionPhoto())// si la fonction retourne true, on traite la photo (il faut lui donner un nom unique)
			{
				$nom_photo = $_POST['reference'].'-'.$_FILES['photo']['name']; // on ajoute la référence (unique) au nom du fichier, pour ne pas écraser un fichier qui aurait déjà le même nom.
				//attention, la BDD enregistre un LIEN de photo. une BDD ne contient que du texte.

				//récupération du lien SRC :
				$photo_bdd = RACINE_SITE."photos/$nom_photo"; //chemin src enregistré dans la bdd.
				$photo_dossier = RACINE_SERVER.RACINE_SITE."photos/$nom_photo";//chemin pour l'enregistrment dans le dossier qui va servir à la fonction copy()

				copy($_FILES['photo']['tmp_name'], $photo_dossier);// deux arguments : l'endroit où est pour l'instant le fichier (temporairement dans tmp_name), l'endroit où il doit aller. attention, si le chargement de l'upload photo est infini, le pb peut venir de firefox - voir avec les modules complémentaires.

			}

			else // Format invalide
			{
				$msg .= "<div class='bg-danger' style='padding:15px'><p>Format de photo invalide (extensions possibles : gif, jpg, jpeg, png).</p></div>";
			}
		}
	}
//--------------------------------------Modif articles--------------------------------------MODIF ARTICLE SQL
	if(empty($msg))// si $msg est vide, c'est que toutes les vérifs sont passées, on peut faire l'insertion.
	{
		foreach($_POST AS $indice => $valeur)
		{
			$_POST[$indice] = htmlentities($valeur, ENT_QUOTES); // prévenir les injections de code
		}

		extract($_POST);
		executeRequete("REPLACE INTO article (id_article, reference, categorie, titre, description, couleur, taille, sexe, photo, prix, stock) VALUES ('$id_article', '$reference', '$categorie', '$titre', '$description', '$couleur', '$taille', '$sexe', '$photo_bdd', '$prix', '$stock')");//attention, le champ photo reçoit le chemin src contenu dans la variable $photo_bdd

		$_GET['action'] = "affichage"; // ou header("location:../gestion_boutique.php?action=affichage");

	}
}

//------------------------------------------------------------
require_once("../inc/haut_de_site.inc.php");
require_once("../inc/menu.inc.php");

echo $msg; //affichage des messages (erreur, validation, etc.)





echo
	'<div class="page-header">
		<h1><i class="glyphicon glyphicon-tags"></i> Gestion Boutique</h1>
	</div>

	<a href="gestion_boutique.php?action=affichage">Afficher les articles</a><br />
	<a href="gestion_boutique.php?action=ajout">Saisir un article</a>
	<hr />';

// -----------------AFFICHAGE DES ARTICLES
if(isset($_GET['action']) && $_GET['action'] == 'affichage')// Si on clik sur le lien
{
	$resultat = executeRequete("SELECT * FROM article ORDER BY categorie");//alors on fait une requete pr recup tt les articles
	$nbcol = $resultat->field_count;// recup le nbre d'articles
	echo "<pre>Nombre d'articles : ".$resultat->num_rows."</pre>";// affiche le nbre d'rticles
	echo '<ul class="pagination hide-if-no-paging"></ul>';
	echo '<table class="table footable" border="1" data-page-size="10">';
	echo '<tr>';
//------------------recup ss form de tableau
	for($i = 0; $i < $nbcol; $i++) // tant que $i est inférieur au nb de colonnes récupéré plus haut
	{
		$colonne = $resultat->fetch_field(); // la méthode fetch_field() de l'objet Mysqli_result permet de récupérer le NOM des colonnes sous forme d'OBJET
		echo '<th style="text-align:center; padding: 5px;">'.$colonne->name .'</th>'; // on appele le name (propriété publique) qu'on affiche dans un th.
	}

	echo '<th>Edit</th>';
	echo '<th>Suppr.</th>';

	echo '</tr>';

	while($liste = $resultat->fetch_assoc()) //agit comme un foreach
	{
		echo '<tr>';

			foreach($liste AS $indice=>$info)
			{
				if($indice == 'photo')
				{
					if(!empty($info))
					{
						echo '<td><img src="'.$info.'" width="40" height="40" /></td>';
					}
					else
					{
						echo "<td style='text-align:center;'> - </td>";
					}
				}
				else
				{
					echo '<td style="padding: 5px;">'.$info.'</td>';
				}
			}

			echo '<td style="text-align:center;"><a class="glyphicon glyphicon-pencil" href="gestion_boutique.php?action=modification&id_article='.$liste['id_article'].'"></a></td>';
			echo '<td style="text-align:center;"><a class="glyphicon glyphicon-remove" href="gestion_boutique.php?action=suppression&id_article='.$liste['id_article'].'" OnClick="return(confirm(\'Etes-vous sûr(e) de vouloir supprimer cette ligne ?\'));"></a></td>';

		echo '</tr>';
	}
}
elseif(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')) // EN CAS D'AJOUT D'UN ARTICLE
{
	if(isset($_GET['id_article']))// si un id_article est passé dans le get, il s'agit d'une modification
	{
		$resultat = executeRequete("SELECT * FROM article WHERE id_article = '$_GET[id_article]' ");
		$article_actuel = $resultat->fetch_assoc();
		//debug($article_actuel);

	}


?>

<!-----------------FORMULAIRE DE MODIFICATION ARTICLE------------------------------------------------>


	<div class="col-md-6 col-md-offset-3"> <!-- on demande une largeur de 6 colonnes avec 3 colonnes de marge.-->

		<form method="post" action="gestion_boutique.php?action=<?php echo $_GET['action'] ?>" enctype="multipart/form-data" >

			<input type="hidden" name='id_article' id='id_article' value="<?php if(isset($article_actuel['id_article'])) echo $article_actuel['id_article']?>" /><!-- champ caché, le user ne doit pas voir accès au champ ID -->

			<label for="reference">Référence</label>
				<input class="form-control" type="text" id="reference" name="reference" value="<?php if(isset($_POST['reference'])) echo $_POST['reference']?><?php if(isset($article_actuel['reference'])) echo $article_actuel['reference']?>" placeholder="Référence..." required maxlength="15" <?php if(isset($_GET['action']) && $_GET['action'] == 'modification') { echo "readonly";} ?>/>

			<label for="categorie">Catégorie</label>
				<input class="form-control" type="text" id="categorie" name="categorie" value="<?php if(isset($_POST['categorie'])) echo $_POST['categorie']?><?php if(isset($article_actuel['categorie'])) echo $article_actuel['categorie']?>" placeholder="Catégorie..." maxlength="50" />

			<label for="titre">Titre</label>
				<input class="form-control" type="text" id="titre" name="titre" value="<?php if(isset($_POST['titre'])) echo $_POST['titre']?><?php if(isset($article_actuel['titre'])) echo $article_actuel['titre']?>" placeholder="Titre..." required maxlength="100" />

			<label for="description">Description</label>
				<textarea class="form-control" id="description" name="description" placeholder="Description..." ><?php if(isset($_POST['description'])) echo $_POST['description']?><?php if(isset($article_actuel['description'])) echo $article_actuel['description']?></textarea>

			<label for="couleur">Couleur</label>
				<input class="form-control" type="text" id="couleur" name="couleur" value="<?php if(isset($_POST['couleur'])) echo $_POST['couleur']?><?php if(isset($article_actuel['couleur'])) echo $article_actuel['couleur']?>" placeholder="Couleur..." maxlength="10" />

			<label for="taille">Taille</label>
				<select name="taille" id="taille" class="form-control">
					<option value="S" <?php if(isset($_POST['taille']) && $_POST['taille']=='S') echo 'selected'?><?php if(isset($article_actuel['taille']) && $article_actuel['taille']=='S') echo 'selected'?> >S</option>
					<option value="M" <?php if(isset($_POST['taille']) && $_POST['taille']=='M') echo 'selected'?><?php if(isset($article_actuel['taille']) && $article_actuel['taille']=='M') echo 'selected'?> >M</option>
					<option value="L" <?php if(isset($_POST['taille']) && $_POST['taille']=='L') echo 'selected'?><?php if(isset($article_actuel['taille']) && $article_actuel['taille']=='L') echo 'selected'?> >L</option>
					<option value="XL" <?php if(isset($_POST['taille']) && $_POST['taille']=='XL') echo 'selected'?><?php if(isset($article_actuel['taille']) && $article_actuel['taille']=='XL') echo 'selected'?> >XL</option>
				</select>

			<label for="sexe">Sexe</label><br />
				<input class="radio-inline" type="radio" id="sexe" name="sexe" value="m" <?php if((isset($article_actuel['sexe']) && $article_actuel['sexe'] == 'm') || (isset($_POST['sexe']) && $_POST['sexe'] == 'm')) { echo 'checked'; } elseif(!isset($_POST['sexe']) && !isset($article_actuel['sexe'])) { echo 'checked'; } ?> />&nbsp;Homme
				<input class="radio-inline" type="radio" id="sexe" name="sexe" value="f" <?php if(isset($_POST['sexe']) && $_POST['sexe'] == 'f') echo 'checked'; ?><?php if(isset($article_actuel['sexe']) && $article_actuel['sexe'] == 'f') echo 'checked'; ?> />&nbsp;Femme
			<br/>

			<label for="photo">Photo</label>
				<input class="form-control" type="file" id="photo" name="photo" />

			<?php

			if(isset($article_actuel))
			{
				echo '<strong>Photo actuelle : </strong><br /><img src="'.$article_actuel['photo'].'" width="100" height="100" /><br />';
				echo '<input type="hidden" name="photo_actuelle" value="'.$article_actuel['photo'].'" />';
			}

			?>

			<label for="prix">Prix</label>
				<input class="form-control" type="text" id="prix" name="prix" value="<?php if(isset($_POST['prix'])) echo $_POST['prix']?><?php if(isset($article_actuel['prix'])) echo $article_actuel['prix']?>" placeholder="Prix..." maxlength="5" />

			<label for="stock">Stock</label>
				<input class="form-control" type="text" id="stock" name="stock" value="<?php if(isset($_POST['stock'])) echo $_POST['stock']?><?php if(isset($article_actuel['stock'])) echo $article_actuel['stock']?>" placeholder="Stock..." maxlength="4" />

			<br />

			<input class="btn btn-warning col-md-3" type="submit" name="enregistrement" value="<?php echo ucfirst($_GET['action']) //ucfirst() met en majuscule la première lettre ?>" id="enregistrement" />


		</form>

	</div> <!-- fermeture div 6 colonnes formulaire -->




<?php
}
require_once("../inc/bas_de_site.inc.php");

?>

















