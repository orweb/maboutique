
<?php

require_once("../inc/init.inc.php");


//-------------------------------------------------------REQUIRE--------
require_once("../inc/haut_de_site.inc.php");
require_once("../inc/menu.inc.php");
//-----------------------------------------Liste de commande--------------------------------------------------LISTE COMMANDE--TABLEAU
echo $msg; //affichage des messages (erreur, validation, etc.)

?>

<div class="page-header">
	<h1 class="bigh1"><i class="glyphicon glyphicon-barcode bigh1"></i> Gestion commande</h1>
</div>
<?php

//-----------------------------------------------Si l'utilisateur est connecté et est admin------------------
if(!utilisateurEstConnecteEtEstAdmin())
{
    header("location:../connexion.php");
    exit(); // permet d'arrêter l'exécution du script dans le cas où qqn pourrait tenter des injections de code via l'url
    // le reste du code n'est pas exécuté
}
//----------------------------------------------Modification de l'état --&--Envoi mail client--------------ETAT--MAIL--GET--REQUETE SQL-----

if(isset($_POST['modifier_etat']))
{

    switch($_POST['etat'])
    {
        case 'traitement':
            executeRequete("UPDATE commande SET etat = 'en cours de traitement' WHERE id_commande = $_POST[id_commande]");
            break;

        case 'envoye':
            executeRequete("UPDATE commande SET etat = 'envoye' WHERE id_commande = $_POST[id_commande]");
            break;

        case 'livre':
            executeRequete("UPDATE commande SET etat = 'livre' WHERE id_commande = $_POST[id_commande]");
            break;

    }

    $req_email = executeRequete("SELECT email FROM membre WHERE id_membre = (SELECT id_membre FROM commande WHERE id_commande = ".$_POST['id_commande'].")");
    $email = $req_email->fetch_assoc();
//    debug($email);
//
//    mail($_SESSION['utilisateur']['email'], 'Confirmation de votre commande',"Merci pour votre commande , votre numéro de suivi est le : $id_commande", "From: lyfeor@gmail.com");
//    header("location:gestion_commande.php");
//

}


echo '<ul class="pagination hide-if-no-paging"></ul>';
echo '<table class="table footable" border="1" data-page-size="5">';




echo '<tr><th colspan="11" style="text-align:center; background-color: grey;">Liste des commandes</th></tr>';

    echo '<tr>';
        echo '<th>ID commande</th>';
        echo '<th>Détail commande</th>';
        echo '<th>Date</th>';
        echo '<th>Montant TTC</th>';
        echo '<th>ID membre</th>';
        echo '<th>pseudo</th>';
        echo '<th>adresse</th>';
        echo '<th>ville</th>';
        echo '<th>cp</th>';
        echo '<th>Etat</th>';
        echo '<th>Modif.</th>';
    echo '</tr>';



//----------------------------------------Liste commande en cours------------------------------------------FORMULAIRE--SQL--FETCH ASSOC-



$resultat = executeRequete("SELECT * FROM commande c LEFT JOIN membre m ON c.id_membre = m.id_membre");

while($info = $resultat->fetch_assoc())
{
    echo '<tr><td>'.$info['id_commande'].'</td>';
    echo '<td><a href="gestion_commande.php?detail=details_commande&id_commande='.$info['id_commande'].'" class="btn btn-success">Détail commande</a></td>';
    echo '<td>'.$info['date'].'</td>';
    echo '<td>'.$info['montant'].'</td>';
    echo '<td>'.$info['id_membre'].'</td>';
    echo '<td>'.$info['pseudo'].'</td>';
    echo '<td>'.$info['adresse'].'</td>';
    echo '<td>'.$info['ville'].'</td>';
    echo '<td>'.$info['cp'].'</td>';
    echo '<td>'.$info['etat'].'</td>';
    echo '<td><a href="gestion_commande.php?modifier=modifier_etat&id_commande='.$info['id_commande'].'" class="btn btn-warning">Modifier l\'état</a></td>';
    echo '</tr>';
}

//LEFT JOIN details_commande dc ON dc.id_commande = c.id_commande LEFT JOIN article a ON a.id_article=dc.id_article

echo '</table>';

//------------------------------- Chiffre d'affaire--& -- Nombre commande------------------------------------CA NBRE COMMANDE---SQL

echo '<div><pre>';

$resultat = executeRequete("SELECT * FROM commande"); // Requete pour afficher le nombre commande
$nbcol = $resultat->field_count;

echo '<h5 class="col-md-3 col-md-offset-0"  >Nombre articles : '.$resultat->num_rows.'</h5>';

$resultat = executeRequete("SELECT SUM(montant) AS total FROM commande"); // requete chiffre d'affaires
$montant = $resultat->fetch_assoc();

echo '<h5 class="col-md-3 col-md-offset-6">Montant total :'.$montant['total'].'</h5>';


echo '</pre></div>';


//-----------------------------------Modification Etat -----------------------------------------------------------ETAT---GET--SELECT--SUBMIT
if(isset($_GET['modifier']))
{

    echo '<form method="post" action="gestion_commande.php" >
                    <input type="hidden" name="id_commande" value="'.$_GET['id_commande'].'"/>
                    <label for="etat">Modifier l\'état</label>
                        <select name="etat" id="etat">
                            <option value="traitement">En cours de traitement</option>
                            <option value="envoye">Envoyé</option>
                            <option value="livre">Livré</option>
                        </select>
                    <input type="submit" name="modifier_etat" value="ok" class="btn btn-success"/>

                </form>';

}

//----------------detail commande---------------------------------------------------------------------------DETAIL--SQL--FETCH.ASS--TABLEAU
if(isset($_GET['detail']))
{
        $resultat = executeRequete("SELECT * FROM details_commande dc, article a WHERE dc.id_article = a.id_article AND dc.id_commande = $_GET[id_commande]");

    echo '<table class="table">';

    echo '<tr><th colspan="8" style="text-align:center; background-color: grey;">Détails Commande '.$_GET['id_commande'].'</th></tr>';

    echo '<tr>';
        echo '<th>ID article</th>';
        echo '<th>Référence</th>';
        echo '<th>Titre</th>';
        echo '<th>Photo</th>';
        echo '<th>Quantité</th>';
        echo '<th>Prix HT</th>';
        echo '<th>Prix TTC</th>';
        echo '<th>Taille</th>';
        echo '<th>Stock</th>';
    echo '</tr>';


    while($info = $resultat->fetch_assoc())
    {
        echo '<tr><td>'.$info['id_article'].'</td>';
        echo '<td>'.$info['reference'].'</td>';
        echo '<td>'.$info['titre'].'</td>';
        echo '<td><img src="'.$info['photo'].'" width="70" height="70" /></td>';
        echo '<td>'.$info['quantite'].'</td>';
        echo '<td>'.$info['prix'].'</td>';
        echo '<td>'.appliqueTtc($info['prix']).'</td>';
        echo '<td>'.$info['taille'].'</td>';
        echo '<td>'.$info['stock'].'</td>';

        echo '</tr>';
    }

    echo '</table>';
}
?>

<!-------------------------------------------------selection de recherche-->
<!--<div class="col-md-9">-->
<!--                <form method="post" action="gestion_commande.php.php" >-->
<!--                    <label for="recherche par ">Sélection de recherche</label>-->
<!--                    <select class="selectpicker" multiple data-max-options="2">-->
<!--                        <option>Mustard</option>-->
<!--                        <option>Ketchup</option>-->
<!--                        <option>Relish</option>-->
<!--                    </select>-->
<!---->
<!--                    <select class="selectpicker" multiple  data-max-options="2">-->
<!--                        <optgroup label="Date" data-max-options="2">-->
<!--                            <option><7 jours</option>-->
<!--                            <option><15 jours</option>-->
<!--                            <option>Relish</option>-->
<!--                        </optgroup>-->
<!--                        <optgroup label="Breads" data-max-options="2">-->
<!--                            <option>Plain</option>-->
<!--                            <option>Steamed</option>-->
<!--                            <option>Toasted</option>-->
<!--                        </optgroup>-->
<!--                    </select>-->
<!--                    <input type="submit" name="recherche" value="recherche" class="btn btn-success"/>-->
<!---->
<!--                </form>-->
<!--                <hr/>-->
<!--                --><?php
//
//$count = count($_SESSION['utilisateur']['id_article']);
//
//  for($i = 0; $i <$count ; $i++)//recherche des données id dans la bdd a cahque tout
//  {
//      $resultat = executeRequete("SELECT * FROM commande c , details_commande dc WHERE c.id_article = dc.article AND dc.article =".$_SESSION['utilisateur']['id_article'][$i]); // pour chaque article on récupère les infos en BDD
//      $article = $resultat->fetch_assoc();
//
//                        echo '<div class="col-md-10" style="text-align:center;">';
//                        echo '	<div class="panel panel">';
//
//                        echo '<h3>'.$article['titre'].'</h3>';
//                        echo '<p>Prix : '.$article['prix'].' €</p>';
//                        echo '<a href="fiche_article.php?id_article='.$article['id_article'].'" class="btn btn-warning">Voir la fiche</a>';
//                        echo '</div>';
//                        echo '</div>';
//                        echo '</div>';
//
//
//
//
//
//
//  }

require_once("../inc/bas_de_site.inc.php");

