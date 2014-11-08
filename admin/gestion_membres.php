<?php

require_once("../inc/init.inc.php");

//----------------------------Si l'utilisateur est connecté et est admin
if(!utilisateurEstConnecteEtEstAdmin())
{
    header("location:../connexion.php");
    exit(); // permet d'arrêter l'exécution du script dans le cas où qqn pourrait tenter des injections de code via l'url
    // le reste du code n'est pas exécuté
}
//-----------------------------------------Modif statut membre-----------------------------------------------MODIF MEMBRE SQL
if(isset($_POST['modifier_etat']))
{

    switch($_POST['statut'])
    {
        case "admin":
            executeRequete("UPDATE membre SET statut = 1 WHERE id_membre = $_POST[id_membre]");
            break;

        case "membre":
            executeRequete("UPDATE membre SET statut = 0 WHERE id_membre = $_POST[id_membre]");
            break;


    }
}
//----------------------------------------Modif statut membre-----------------------------------------------MODIF MEMBRE SQL
if(isset($_POST['supprimer']))


{
            executeRequete("DELETE membre  FROM membre WHERE id_membre = $_POST[id_membre]");




}
//-------------------------------------------------------REQUIRE--------
require_once("../inc/haut_de_site.inc.php");
require_once("../inc/menu.inc.php");
echo $msg;

?>
<div class="page-header">
<h1 class="bigh1"><i class="glyphicon glyphicon-eye-open bigh1"></i> Gestion membre</h1>
</div>
<?php
//-------------------------------------------------------REQUIRE--------//-

//------------------------------------------Affichage liste membre-------------------------------------------------------------

echo '<ul class="pagination hide-if-no-paging"></ul>';
echo '<table class="table footable" border="1" data-page-size="5">';



echo '<tr><th colspan="11" style="text-align:center; background-color: grey;">Liste des membres</th></tr>';

echo '<tr>';
echo '<th>ID membre</th>';
echo '<th>Pseudo</th>';
echo '<th>Adresse</th>';
echo '<th>Ville</th>';
echo '<th>cp</th>';
echo '<th>Statut</th>';
echo '<th>Modification</th>';
echo '<th>Suppression</th>';
echo '</tr>';


//-----------------------------------------Recup tous les membres------------------------------------------RECUP MEMBRE SQL

$resultat = executeRequete("SELECT * FROM membre ");

while($info = $resultat->fetch_assoc())
{
    echo '<tr><td>'.$info['id_membre'].'</td>';
    echo '<td>'.$info['pseudo'].'</td>';
    echo '<td>'.$info['adresse'].'</td>';
    echo '<td>'.$info['ville'].'</td>';
    echo '<td>'.$info['cp'].'</td>';
    echo '<td>'.$info['statut'].'</td>';
    echo '<td><a href="gestion_membres.php?modifier=modifier_etat & id_membre='.$info['id_membre'].'" class="btn btn-warning">Modifier statut du membre</a></td>';
    echo '<td><a href="gestion_membres.php?supprimer=supprimer & id_membre='.$info['id_membre'].'" class="btn btn-danger">Supprimer membre</a></td>';
    echo '</tr>';
}


//---------------------------------------Modif statut membre-------------------------------------------------MODIF statut MEMBRE FORMULAIRE
if(isset($_GET['modifier']))
{

    echo '<form method="post" action="gestion_membres.php" >
                    <input type="hidden" name="id_membre" value="'.$_GET['id_membre'].'"/>
                    <label for="statut">Modifier le statut</label>
                        <select name="statut" id="statut">
                            <option value="membre">Membre</option>
                            <option value="admin">Admin</option>
                        </select>
                    <input type="submit" name="modifier_etat" value="ok" class="btn btn-success"/>

                </form>';

}

//---------------------------------------Supprimer membre-------------------------------------------------cxl MEMBRE FORMULAIRE
if(isset($_GET['supprimer']))
{

    echo '<form method="post" action="gestion_membres.php" >
                    <input type="hidden" name="id_membre" value="'.$_GET['id_membre'].'"/>
                    <label for="statut">Supprimer</label>

                    <input type="submit" name="supprimer" value="ok" class="btn btn-danger"/>

                </form>';

}

?>







<?php

require_once("../inc/bas_de_site.inc.php");

?>