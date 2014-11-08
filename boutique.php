<?php
require_once("inc/init.inc.php");


require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");
echo $msg;
//readFlashMessage();
//debug($_SESSION);
echo '<div class="page-header">

		<h1 class="bigh1"><i class="glyphicon glyphicon-home bigh1 "></i> Boutique</h1>
		</div>';
?>
		<div class="row">
			<div class="col-md-3">
				<h3><i class="glyphicon glyphicon-star"></i> Catégorie</h3>
				<ul>
				<?php
					$categorie_article = executeRequete("SELECT DISTINCT categorie FROM article");	//-- affichage des categorie en liste
					while($cat = $categorie_article->fetch_assoc())
					{
						echo '<li><a href="?categorie='.$cat['categorie'].'">'.ucfirst($cat['categorie']).'</a></li>';// uc first maj 1ere lette
					}
				?>
				</ul>
			</div>
			<div class="col-md-9">
                <form method="post" action="boutique.php" >
                    <label for="recherche par prix">recherche par prix</label>
                    <select name="prix_max" id="prix_max">
                        <option value="50€">article < 50 €</option>
                        <option value="150€">article <150 €</option>
                    </select>
                    <input type="submit" name="recherche prix" value="recherche" class="btn btn-success"/>

                </form>
                <hr/>
                <?php
                if(isset($_GET['prix']) || isset($_POST['recherche_prix']))
                {
                    if (isset($_GET['categorie']))
                    {
                        $donnees = executeRequete("SELECT * FROM article WHERE categorie='$_GET[categorie]'");
                    }
                    if(isset($_POST['recherche_prix']))
                    {
                        $donnees = executeRequete("SELECT * FROM article WHERE prix <='$_POST[prix_max]'");
                    }

					echo'<ul>';
                    while($article = $donnees->fetch_assoc())
                    {


                        echo '<div class="col-md-4" style="text-align:center;">';
                        echo '	<div class="panel panel">
                        <div class="panel-heading"><img src="photos/boutique.jpg"  width="140"/></div>
                        <div class="panel-body">';
                        echo '<img src="'.$article['photo'].'" width="140" />';
                        echo '<h3>'.$article['titre'].'</h3>';
                        echo '<p>Prix : '.$article['prix'].' €</p>';
                        echo '<a href="fiche_article.php?id_article='.$article['id_article'].'" class="btn btn-warning">Voir la fiche</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
					echo'</ul>';


                }


				if(isset($_GET['categorie']))
				{
					$donnees = executeRequete("SELECT * FROM article WHERE categorie='$_GET[categorie]'");

					while($article = $donnees->fetch_assoc())
					{
						echo '<div class="col-md-4" style="text-align:center;">';
						echo '	<div class="panel panel">
									<div class="panel-heading"><img src="photos/boutique.jpg"  width="140"/></div>
									<div class="panel-body">';
						echo '<img src="'.$article['photo'].'" width="140" />';
						echo '<h3>'.$article['titre'].'</h3>';
						echo '<p>Prix : '.appliqueTtc($article['prix']).' €</p>';
                        echo '<a href="fiche_article.php?id_article='.$article['id_article'].'" class="btn btn-warning">Voir la fiche</a>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
					}
				}

			?>
			</div>

		</div>

<?php
require_once("inc/bas_de_site.inc.php");
