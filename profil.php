<?php

require_once("inc/init.inc.php");


if (!utilisateurEstConnecte()) // si user non connecté, il ne doit pas avoir accès à sa page profil. on l'envoie sur la page connexion.
{
	header("location:connexion.php");
}

//zone réservée aux traitements php
//dans les bonnes pratiques, on effectue les traitements php avant l'affichage du site


require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");


echo $msg; //affichage des messages (erreur, validation, etc.)

?>


	<div class="page-header">

		<h1 class="bigh1"><i class="glyphicon glyphicon-user bigh1"></i>
			Bonjour <?php echo $_SESSION['utilisateur']['pseudo'] ?></h1>
		<?php
		//---------------------------------------------------------affichage statut------------------------------------------
		if (utilisateurEstConnecteEtEstAdmin())
		{
			echo "<h4>Vous êtes administrateur.</h4>";
		} else
		{
			echo "<h4>Vous êtes membre du site sans accès administrateur</h4>.";
		}


		//--------------------------------------------------------Modifier coordonnées------------------------------------------------
		// bouton modif

		?>
	</div>
	<div class="row">

		<div class="col-md-3 col-md-offset-0">
			<h3>Vos informations</h3>

			<div class="list-group">
				<a class="list-group-item ">
					<h4 class="list-group-item-heading">Nom et Prénom</h4>

					<p class="list-group-item-text"><?php echo $_SESSION['utilisateur']['prenom'] . " " . $_SESSION['utilisateur']['nom'] ?></p>
				</a>
				<a class="list-group-item">
					<h4 class="list-group-item-heading">Email</h4>

					<p class="list-group-item-text"><?php echo $_SESSION['utilisateur']['email'] ?></p>
				</a>
				<a class="list-group-item">
					<h4 class="list-group-item-heading">Adresse</h4>

					<p class="list-group-item-text"><?php echo $_SESSION['utilisateur']['adresse'] . '<br />' . $_SESSION['utilisateur']['cp'] . " " . $_SESSION['utilisateur']['ville'] ?></p>
				</a>

				<!--        //---------------------------------ajout bouton liens vers changer informations----------------------------------------------------->
				<div>
					<a href="membres.php" class="btn btn-info col-md-12 col-md-offset-">liens vers vos informations</a>
				</div>


			</div>
		</div>
	</div>
	<br/>




<?php


//-----------------------------------------Liste de commande en cours-------------LISTE COMMANDE--TABLEAU
echo $msg; //affichage des messages (erreur, validation, etc.)

$id_membre = ($_SESSION['utilisateur']['id_membre']);
$resultat = executeRequete("SELECT * FROM details_commande dc , article a, commande c WHERE dc.id_article = a.id_article AND dc.id_commande = c.id_commande AND id_membre=$id_membre ORDER BY c.date");


echo '<h3>Vos commandes</h3>';
echo '<ul class="pagination hide-if-no-paging"></ul>';
echo '<table class="table footable"  border="1" data-page-size="5" style="background: #b4b4b4;">';
echo '<tr>';
echo '<th>ID article</th>';
echo '<th>ID commande</th>';
echo '<th>Référence</th>';
echo '<th>Titre</th>';
echo '<th>Photo</th>';
echo '<th>Quantité</th>';
echo '<th>Prix Ht</th>';
echo '<th>Prix Ttc</th>';
echo '<th>Taille</th>';
echo '<th>Etat</th>';
echo '</tr>';

echo '<tbody>';
while ($info = $resultat->fetch_assoc())
{

	echo '</tr>';

	echo '<td>' . $info['id_article'] . '</td>';
	echo '<td>' . $info['id_commande'] . '</td>';
	echo '<td>' . $info['reference'] . '</td>';
	echo '<td>' . $info['titre'] . '</td>';
	echo '<td><img src="' . $info['photo'] . '" width="70" height="70" /></td>';
	echo '<td>' . $info['quantite'] . '</td>';
	echo '<td>' . $info['prix'] . '</td>';
    echo '<td>' . appliqueTtc($info['prix']) . '</td>';
	echo '<td>' . $info['taille'] . '</td>';
	echo '<td>' . $info['etat'] . '</td>';


	echo '</tr>';
}






echo '</table>';




?>
	</div>

<?php require_once("inc/bas_de_site.inc.php");

?>