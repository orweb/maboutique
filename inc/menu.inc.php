    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
              <span class="icon-bar"></span>

          </button>
          <a class="navbar-brand" href="#">Ma boutique</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
			<?php
				if(utilisateurEstConnecte())
				{
					echo '<li class="active"><a  href="'.RACINE_SITE.'boutique.php"><i class="glyphicon glyphicon-home"></i>Accès à la boutique</a></li>';
					echo '<li><a  href="'.RACINE_SITE.'profil.php"><i class="glyphicon glyphicon-user"></i>Profil</a></li>';
						echo '<li><a  href="'.RACINE_SITE.'connexion.php?action=deconnexion"><i class="glyphicon glyphicon-off"></i>Se déconnecter</a></li>';
					echo '<li><a  href="'.RACINE_SITE.'panier.php"><i class="glyphicon glyphicon-shopping-cart"></i>Voir le panier</a></li>';
                    echo '<li><a  href="'.RACINE_SITE.'membres.php"><i class="glyphicon glyphicon-cog"></i>Membre</a></li>';
				}
				else
				{
					echo '<li class="active"><a href="'.RACINE_SITE.'boutique.php"><i></i>Accès à la boutique</a></li>';
					echo '<li><a href="'.RACINE_SITE.'inscription.php"><i class="glyphicon glyphicon-ok"></i>Inscription</a></li>';
					echo '<li><a href="'.RACINE_SITE.'connexion.php"><i class="glyphicon glyphicon-off"></i>Connexion</a></li>';
					echo '<li><a href="'.RACINE_SITE.'panier.php"><i class="glyphicon glyphicon-shopping-cart"></i>Voir le panier</a></li>';
                    echo '<li><a href="'.RACINE_SITE.'membres.php"><i class="glyphicon glyphicon-eye-open"></i>Membre</a></li>';
				}
				
				if(utilisateurEstConnecteEtEstAdmin())
				{
					echo '<li><a  href="'.RACINE_SITE.'admin/gestion_boutique.php"><i class="glyphicon glyphicon-tags"></i>Gestion Boutique</a></li>';
					echo '<li ><a  href="'.RACINE_SITE.'admin/gestion_membres.php" ><i class="glyphicon glyphicon-eye-open"></i>Gestion Membres</a></li>';
					echo '<li><a  href="'.RACINE_SITE.'admin/gestion_commande.php"><i class="glyphicon glyphicon-barcode"></i>Gestion Commandes</a></li>';


				}
			?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

