<?php
require_once("inc/init.inc.php");
creationDuPanier(); // fonction qui prepare le panier
//***********************VIDER LE PANIER*********
if(isset($_POST['action']) && $_POST['action'] == 'Vider le panier')
{
	unset($_SESSION['panier']);
	header("location:panier.php");
}

//***** PAYER

if(isset($_POST['payer']))
{
    // Vérification des stocks en BDD
    for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++)//achaque tour requete qui rechercher info donneee (indice)
    {
        $resultat = executeRequete("SELECT * FROM article WHERE id_article = ".$_SESSION['panier']['id_article'][$i]); // pour chaque article on récupère les infos en BDD
        $article = $resultat->fetch_assoc();

        if($article['stock'] < $_SESSION['panier']['quantite'][$i]) // si le stock restant est inférieur à la quantité demandé
        {
            $msg .= "<div class='bg-danger' style='padding:15px'><p>Stock restant pour l'article ".$_SESSION['panier']['titre'][$i]." : ".$article['stock']." exemplaire(s).</p></div>";

            if($article['stock'] > 0) // il reste quand même des articles (mais moins que la quantité demandée)
            {
                $msg .= "<div class='bg-danger' style='padding:15px'><p>La quantité de l'article ".$_SESSION['panier']['id_article'][$i]." a été réduite car le stock était insuffisant.<br /><strong> Merci de vérifier votre commande</strong></p></div>";
                $_SESSION['panier']['quantite'][$i] = $article['stock'];
            }
            else // il ne reste plus du tout de stock
            {
                $msg .= "<div class='bg-danger' style='padding:15px'><p>L'article ".$_SESSION['panier']['id_article'][$i]." a été retiré du panier car il est en rupture de stock.<br /><strong> Merci de vérifier votre commande</strong></p></div>";
                retirerArticleDuPanier($_SESSION['panier']['id_article'][$i]);// retirer l'article
                $i--; // DECREMENTATION, car la fonction retirerArticleDuPanier() réordonne les indices quand il enlève une ligne (on enlève la ligne 2, l'indice 3 toujours présent passe à 2.
            }
            $erreur = TRUE;
        }
    }

    if(!isset($erreur))// on sort de la boucle et on vérifie si la variable de controle $erreur n'existe pas, si elle n'est pas définie pas d'erreur, on peut lancer le traitement et enregistrer la commande.
    {
       executeRequete("INSERT INTO commande (id_membre, montant, date) VALUES (".$_SESSION['utilisateur']['id_membre'].",".montantTotal().", now())");

        $id_commande= $mysqli->insert_id;// propriete de l'objet mysqli qui nous renvoie le dernier ID crée
        for($i=0 ; $i < count($_SESSION['panier']['titre']); $i++)
        {
                executeRequete("INSERT INTO details_commande (id_commande,id_article, quantite , prix) VALUES ($id_commande,".$_SESSION['panier']['id_article'][$i].",".$_SESSION['panier']['quantite'][$i].",".$_SESSION['panier']['prix'][$i].")");
                executeRequete("UPDATE article SET stock= stock-".$_SESSION['panier']['quantite'][$i]." WHERE id_article =".$_SESSION['panier']['id_article'][$i]);
        }

            unset ($_SESSION['panier']);
        mail($_SESSION['utilisateur']['email'], 'Confirmation de votre commande',"Merci pour votre commande , votre numéro de suivi est le : $id_commande", "From: lyfeor@gmail.com");
//        header("location:boutique.php"); a faire Rafraichir en ajax
    }
}

//***********************RETIRER UN ARTICLE AU PANIER
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
	retirerArticleDuPanier($_GET['id_article']);
	header("location:panier.php");
}


//***********************AJOUT ARTICLE AU PANIER
if(isset($_POST['ajout_panier'])) // ce post provient de la page fiche_article.php
{
	$resultat = executeRequete("SELECT * FROM article WHERE id_article=$_POST[id_article]"); // on récupère les informations en BDD grace à l'id article
	$article = $resultat->fetch_assoc();
	 // on rajoute la TVA sur le prix
	ajouterArticleDansPanier($article['titre'], $_POST['id_article'], $_POST['quantite'], $article['prix']);
	header("location:panier.php");
}

require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");
echo $msg;
echo '<div class="page-header">
		<h1><i class="glyphicon glyphicon-shopping-cart "></i> Panier</h1>
		</div>';

//debug($_SESSION['panier']);

echo '<table class="table" border="1" cellpadding="7" style="text-align:center;">';
echo '<tr><th colspan="7" style ="background-color: grey" ; >PANIER</th></tr>';
echo '<tr><th>Titre</th><th>Id_article</th><th>Quantité</th><th>Prix unitaire HT</th><th>Montant TVA</th><th>Prix unitaire TTC</th><th>Supprimer</th></tr>';
if(empty($_SESSION['panier']['id_article']))
{
	echo '<tr><th colspan="7"><h4>Votre panier est vide</h4></th></tr>';
}
else
{
	for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++)
	{


		echo '<tr>';
		echo '<td>'.$_SESSION['panier']['titre'][$i].'</td>';
		echo '<td>'.$_SESSION['panier']['id_article'][$i].'</td>';

		echo '<td>

		<form id="formquantite" action="" method="post">
		<div align="center">
		<select  id="quantite" name="change" class="form-control" onchange="this.form.submit()">';
//		mis ds le select mais ne fonctionne po :( onchange="this.form.submit()"
		for($j = 1;  $j <=5 ; $j++)
		{
			if(isset($_POST['change']))
			{
				$_SESSION['panier']['quantite'][$i] = $_POST['change'];
			}

			if($_SESSION['panier']['quantite'][$i] == $j)
			{
				$selected = 'selected';
			}
			else
			{
				$selected ='';
			}
			echo "<option $selected value=".$j.">$j</option>";

		}


		echo '</select>';
		?>

		</div>
<?php
//		echo '<input class="btn btn-success" type="submit" name="modif" value="Mettre à jour la quantité" />';
echo '</form>';



		echo '</td>';
        echo '<td>'.$_SESSION['panier']['prix'][$i].'</td>';
        echo '<td>'.appliqueTva($_SESSION['panier']['prix'][$i]).'</td>';
        echo '<td>'.appliqueTtc($_SESSION['panier']['prix'][$i]).'</td>';
		echo '<td><a href="?action=suppression&id_article='.$_SESSION['panier']['id_article'][$i].'" OnClick="return(confirm(\'Etes-vous certain ?\'));" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>';
//		echo '<select id="quantite" name="quantite" class="form-control">';
//for($i = 1;  $i <=5 ; $i++)
//{
//	echo "<option>$i</option>";
//}
//
//		echo '<input class="btn btn-success" type="submit" name="reduction" value="ok" />';
//echo '</select>&nbsp;&nbsp;';

//		a faire rajout select 1/2/3 pour diminuer articel
		echo '</tr>';
	}
	echo '<tr><th colspan="3" style="text-align: right">Montant Total HT : </th><th colspan="2" style="text-align: left">'.montantTotal().'</th></tr>';
    echo '<tr><th colspan="3" style="text-align: right">Montant Total TTC: </th><th colspan="2" style="text-align: left">'.montantTotalTtc().'</th></tr>';

	if(utilisateurEstConnecte())
	{
		echo '<tr><td colspan="7">';
		echo '<form method="post" action="">';
		echo '<input class="btn btn-success col-md-2" type="submit" name="payer" value="payer" />';
	echo '</form>';

		echo '</td></tr>';
	}
	else
	{
		echo '<tr><td colspan="6">Veuillez vous <a href="connexion.php">connecter</a> ou vous <a href="inscription.php">inscrire</a> pour valider votre commande</td></tr>';
	}

	echo '<tr><th colspan="7">';
	echo '<form method="post" action="">';
	echo '<input class="btn btn-danger col-md-2" type="submit" name="action" value="Vider le panier" />';
	echo '</form>';
	echo '</th></tr>';
}
echo '</table>';


//----------------------Coordonnées Utilisateur--------------------------------SESSION USER ADRESS
if(utilisateurEstConnecte())
{
//	echo '<address><strong>Votre adresse est : </strong><br />'.$_SESSION['utilisateur']['adresse'].'<br />'.$_SESSION['utilisateur']['cp'].'<br />'.$_SESSION['utilisateur']['ville'].'</address>';

	echo '<div class="panel panel-info">
	<div class="panel-heading">Coordonnées </div>
		<div class="panel-body">
<dl class="dl-horizontal">
			<dt>Votre adresse est : </dt>
			<dd>'.$_SESSION['utilisateur']['adresse'].'<br />'.$_SESSION['utilisateur']['cp'].'<br />'.$_SESSION['utilisateur']['ville'].'</address></dd></dl>
			</div>

			</div>';

//--------------------Modification User--------------------------------------------MODIF USER
	echo'<a href="membres.php" class="btn btn-info col-md-2 col-md-offset-">Modifier vos coordonnées</a>';
}
echo '<br /><hr />


<div class="panel panel-danger">
	<div class="panel-heading">IMPORTANT </div>
		<div class="panel-body">
<dl class="dl-horizontal">
			<dt>Conditions de réglement :</dt>
			<dd><address>Règlement par chèque uniquement ! <br />A l\'adresse : 3 rue du caducée <br />94550 Rungis</address></dd></dl>
			</div>

			</div>';





// echo $_SESSION['panier']['titre'][0];


require_once("inc/bas_de_site.inc.php");

?>
