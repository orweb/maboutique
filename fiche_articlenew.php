<?php
require_once("inc/init.inc.php");
creationDuPanier();
if(!empty($_GET['id_article'])) // si un id est passé via l'url
{
    $resultat = executeRequete("SELECT * FROM article WHERE id_article=$_GET[id_article]");
    $produit = $resultat->fetch_assoc();
}

else

{
    header("location:boutique.php");
    exit();
}
if($resultat->num_rows <= 0)
{
    header("location:boutique.php");
    exit();
}
require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");
echo $msg;

echo '<div class="page-header">
		<h1 class="bigh1"><i class="glyphicon glyphicon-thumbs-up bigh1"></i> Fiche Article</h1>
		</div>';
//debug($produit);

echo '<div class="col-md-8 col-md-offset-2" style="text-align:center">';
echo '<div class="panel panel-warning">
		<div class="panel-heading"><h4>'.$produit['titre'].'</h4></div>
		<div class="panel-body">';
echo '<p><strong>Description : </strong>'.$produit['description'].'</p><hr />';
echo '<p><strong>Catégorie : </strong>'.$produit['categorie'].'</p><hr />';
echo '<p><strong>Taille : </strong>'.$produit['taille'].'</p><hr />';
echo '<p><strong>Couleur : </strong>'.$produit['couleur'].'</p><hr />';
echo '<img src="'.$produit['photo'].'" /><hr />';
echo '<p><strong>Prix HT : </strong>'.$produit['prix'].' €</p><hr />';
echo '<p><strong>Montant Tva : </strong>'.appliqueTva($produit['prix']).' €</p><hr />';
echo '<p><strong>Prix TTC : </strong>'.appliqueTtc($produit['prix']).' €</p><hr />';

//if($produit['stock'] > 0)
//{
//    echo '<p><strong>Stock : </strong>'.$produit['stock'].' </p><hr />';
//    echo '<form method="post" class="form-inline" action="panier.php">';
//    echo '<input type="hidden" name="id_article" value="'.$produit['id_article'].'" />'; // on récupère l'id pour l'envoyer au panier
//    echo '<input type="hidden" name="prix" value="'.$produit['prix'].'" />'; // on récupère l'id pour l'envoyer au panier
//    echo '<label for="quantite">Quantité</label>&nbsp;&nbsp;';
//    echo '<select id="quantite" name="quantite" class="form-control">';
//    for($i = 1; $i <= $produit['stock'] && $i <=5 ; $i++)
//    {
//        echo "<option>$i</option>";
//    }
//    echo '</select>&nbsp;&nbsp;';
//    echo '<input class="btn btn-success" type="submit" name="ajout_panier" value="Ajouter au panier" />';
//    echo '</form><hr />';
//}
//else
//{
//    echo '<p>Rupture de stock pour ce produit ! </p><hr />';
//}



//---------------------------------------Affiche le nombre d'article

for($i=0 ; $i < count($_SESSION['panier']['id_article']); $i++)
{
    echo "Jai l'article".$_SESSION['panier']['id_article'][$i]."Dans mon panier en ".$_SESSION['panier']['quantite'][$i]."exemplaires.";
}


//----------------------------------------Verification du stock---------------------------------------------------
if($produit['stock']> 0)//si le stock bdd le permet
{

    $position_article = array_search($_GET['id_article'],$_SESSION['panier']['id_article']);
    if($position_article !== FALSE)
    {
        $quantite_deja_dans_panier = $_SESSION['panier']["quantite"][$position_article];
    }

    else{
            $quantite_deja_dans_panier = 0;
        }
    $stock_restant = $produit['stock']- $quantite_deja_dans_panier;// stock restant => le stock de la bdd la quantite choisi pa rl'utilisateur



    if($stock_restant > 0)
    {
        echo 'Nombre d\'articles disponibles : '.$stock_restant.'<br />';
        echo '<form method="post" class="form-inline" action="panier.php">';
        echo '<input type="hidden" name="id_article" value="'.$produit['id_article'].'" />'; // on récupère l'id pour l'envoyer au panier
        echo '<input type="hidden" name="prix" value="'.$produit['prix'].'" />'; // on récupère l'id pour l'envoyer au panier
        echo '<label for="quantite">Quantité</label>&nbsp;&nbsp;';
        echo '<select id="quantite" name="quantite" class="form-control">';
        for($i = 1; $i <= $stock_restant && $i <=5 ; $i++)
        {
            echo "<option>$i</option>";
        }
        echo '</select>&nbsp;&nbsp;';
        echo '<input class="btn btn-success" type="submit" name="ajout_panier" value="Ajouter au panier" />';
        echo '</form><hr />';
    }
    else

    {
        echo 'Vous avez deja les '.$quantite_deja_dans_panier.'derniers examplaires de cet artcle dans votre panier';
    }
}

else {
    echo '<p>Rupture de stock pour ce produit ! </p><hr />';
}
echo '<a href="boutique.php?categorie='.$produit['categorie'].'" class="btn btn-info">Retour vers la sélection</a>';

echo '</div>';
echo '</div>';
echo '</div>';

require_once("inc/bas_de_site.inc.php");



//array mysql_fetch_assoc ( resource $result )
