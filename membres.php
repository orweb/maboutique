<?php

require_once("inc/init.inc.php");






require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");
echo $msg;

//------------------------------------Verification si utilisateur connecte

if (!utilisateurEstConnecte()) // si user non connecté, il ne doit pas avoir accès à sa page profil. on l'envoie sur la page connexion.
{
	header("location:connexion.php");
}


//-------------------------------------Grand titre
?>
<div class="page-header">
<h1 class="bigh1"><i class="glyphicon glyphicon-cog bigh1"></i> Modification</h1>
</div>
<div class="row">
	<div class="centered">

		<div class="col-md-4 col-md-offset-2">

<?php


//----------------------------------------ajout avatar
//------------------------------------Upload avatar----------------------------------------
$id_membre = ($_SESSION['utilisateur']['id_membre']);

if(!empty($_FILES['photo']['name']))
{
	$taille_maxi = 100000;
	$taille= filesize($_FILES['photo']['tmp_name']);
	if ($taille<=$taille_maxi)
	{

		if(verificationExtensionPhoto())// si la fonction retourne true, on traite la photo (il faut lui donner un nom unique)
		{




			$nom_photo = $_FILES['photo']['name']; // on ajoute la référence (unique) au nom du fichier, pour ne pas écraser un fichier qui aurait déjà le même nom.
			//attention, la BDD enregistre un LIEN de photo. une BDD ne contient que du texte.

			$nom_photo = strtr($nom_photo,
				'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
				'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
			$nom_photo = preg_replace('/([^.a-z0-9]+)/i', '-', $nom_photo);


			//récupération du lien SRC :
			$photo_bdd = RACINE_SITE."avatar/$nom_photo"; //chemin src enregistré dans la bdd.
			$photo_dossier = RACINE_SERVER.RACINE_SITE."avatar/$nom_photo";//chemin pour l'enregistrment dans le dossier qui va servir à la fonction copy()

			copy($_FILES['photo']['tmp_name'], $photo_dossier);// deux arguments : l'endroit où est pour l'instant le fichier (temporairement dans tmp_name), l'endroit où il doit aller. attention, si le chargement de l'upload photo est infini, le pb peut venir de firefox - voir avec les modules complémentaires.


			$idmembre =$_SESSION['utilisateur']['id_membre'];
			executeRequete("UPDATE  membre SET avatar = '$photo_bdd'  where id_membre = $id_membre ");//debug($reference);


			echo "<div class='bg-success' style='padding:15px'>Upload terminé !</div>";


		}
		else // Format invalide
		{
			echo "<div class='bg-danger' style='padding:15px'><p>Format de photo invalide (extensions possibles : gif, jpg, jpeg, png).</p></div>";
		}
	}
	else {
		echo 'Le fichier est trop volimineux ... 10mo Max';

	}

}

//----------------------------affichage photo-Avatar
$resultat = executeRequete("SELECT avatar FROM membre WHERE id_membre=$id_membre");

while($liste = $resultat->fetch_assoc())
{
	echo '<img src="'.$liste['avatar'].'" width="140" height="140 class="img-circle" />';
}




//------------------------------Titre Modification-----------------------------------------
?>

			<!--           Formulaire modification infos perso------------------------------------------MODIF INFOS FORM -->


			<form method="post" action="membres.php" enctype="multipart/form-data">

				<!--avatar-->
								<p>
									Choisissez votre avatar : (Taille max :
									100Ko)<br/>

									<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
									<input type="file" name="photo" id="avatar"/><br/>
									<input type="submit" name="envoyer" value="Envoyer le fichier"/>
								</p>

				<!--pseudo-->

				<label for="pseudo">Pseudo</label>
				<input class="form-control" type="text" id="pseudo" name="pseudo" value="
<?php


				if (isset($_SESSION['utilisateur']))
					echo $_SESSION['utilisateur']['pseudo'];



				?>
" placeholder="Pseudo..." required maxlength="14" readonly/>


				<!--nom-->
				<label for="nom">Nom</label>
				<input class="form-control" type="text" id="nom" name="nom" value="
<?php


				if (isset($_SESSION['utilisateur']))
					echo $_SESSION['utilisateur']['nom'];
				?>
" placeholder="Nom..." readonly/>
				<!--prenom-->
				<label for="prenom">Prénom</label>
				<input class="form-control" type="text" id="prenom" name="prenom" value="
<?php if (isset($_POST['prenom']))
					echo $_POST['prenom'];

				elseif (isset($_SESSION['utilisateur']))
					echo $_SESSION['utilisateur']['prenom'];

				?>" placeholder="Prénom..." readonly/>
				<!--email-->
				<label for="email">Email</label>
				<input class="form-control" type="email" id="email" name="email" value="
<?php if (isset($_POST['email']))
					echo $_POST['email'];

				elseif (isset($_SESSION['utilisateur']))
					echo $_SESSION['utilisateur']['email'];

				?>" placeholder="Email..."/>

				<!--sexe-->
				<label for="sexe">Sexe</label><br/>
				<input class="radio-inline" type="radio" id="sexe" name="sexe" value="m"
					<?php if (isset($_POST['sexe']) && $_POST['sexe'] == 'm')
					{
						echo 'checked';
					} elseif (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 'm')
					{
						echo $_SESSION['utilisateur']['sexe'];
					}
					?> />&nbsp;Homme

				<input class="radio-inline" type="radio" id="sexe" name="sexe" value="f"

					<?php if (isset($_POST['sexe']) && $_POST['sexe'] == 'f')

						echo 'checked';

					elseif (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 'f')

					{
						echo $_SESSION['utilisateur']['sexe'];
					}
					?> />&nbsp;Femme

				<br/>

				<!--ville-->
				<label for="ville">Ville</label>
				<input class="form-control" type="text" id="ville" name="ville" value="
<?php if (isset($_POST['ville']))
					echo $_POST['ville'];

				elseif (isset($_SESSION['utilisateur']))

				{
					echo $_SESSION['utilisateur']['ville'];
				}
				?>
" placeholder="Ville..."/>

				<!--code postal-->
				<label for="cp">Code postal</label>
				<input class="form-control" type="text" id="cp" name="cp" maxlength="5" value="
<?php if (isset($_POST['cp'])) echo $_POST['cp'];

				elseif (isset($_SESSION['utilisateur']))

				{

					echo $_SESSION['utilisateur']['cp'];
				}
				?>
" placeholder="Code postal..."/>

				<!--adresse-->
				<label for="adresse">Adresse</label>
				<textarea class="form-control" id="adresse" name="adresse" placeholder="Adresse...">
					<?php if (isset($_POST['adresse']))
						echo $_POST['adresse'];

					elseif (isset($_SESSION['utilisateur']))

					{
						echo $_SESSION['utilisateur']['adresse'];
					}
					?>
				</textarea><br/>
<div>




				<input class="btn btn-success col-md-3" type="submit" name="modifier" value="Valider"/>
</div>
			</form>


		</div>
		<!--        formulaire modification de mot de passe----------------------------------MODIF MDP FORM-->

		<div class="col-md-4 col-md-offset-1">


			<button id="btnmodif" class="btn btn-primary">Modifier mot de passe</button>

			<div id="changemdp">

				<form method="post" action="membres.php">


					Ancien mot de passe<input class="form-control" type="password" name="amdp" value="amdp"
											  placeholder="Ancien mot de passe..."
											  required maxlength="14"/>

					Nouveau mot de passe<input class="form-control" type="password" name="nmdp" value="nmdp"
											   placeholder="Nouveau Mot de passe..."
											   required maxlength="14"/>

					Confirmation nouveau mot de passe<input class="form-control" type="password" name="vmdp"
															value="vmdp"
															placeholder="Vérification de mot de passe..." required
															maxlength="14"/> <br/>

					<input class="btn btn-success col-md-3" type="submit" name="submitmdp" value="Valider"/>

				</form>
				<button id="btncancel" class="btn btn-warning">Annuler</button><br />


			</div><br />

		</div><br />
		<br /><br /><br />




		<br /></div>

	<!--Formulaire de mot de passe perdu -------------------------------------------------------------MDP LOST-->


	<button  class="col-md-2 col-md-offset-1 btn btn-primary " id="btnlost">Mot de passe perdu</button>
	<div  class="col-md-5 col-md-offset-1" id="lostmdp">

		<form method="post" action="membres.php">

			<br /><br />Veuillez inscrire votre mail, un nouveau mot de passe vous sera envoyé par mail
			<input class="form-control " type="email" name="email" value="email" placeholder="Votre adresse mail..." required maxlength="30"/>


			<input class="btn btn-success col-md-5" type="submit" name="submitmail" value="Valider votre mail"/>

		</form>
		<button id="btncxllost" class="btn btn-warning">Annuler la demande</button>
	</div>


<?php

//-----------------------------------script mot de passe perdu----------------------------------------MDP LOST SCRIPT

if (isset($_SESSION['utilisateur']))

{
	if (isset($_POST['submitmail']))
	{
		if(!empty($_POST['email']))
			$email = $_POST['email'];
		else
			exit("mail vide.");

		global $mysqli;
		$req = executeRequete("SELECT email FROM membre WHERE email = '".$email."' ");



		if($req->num_rows != 1)//si le nombre de lignes retourne par la requete != 1
			exit("mail inconnu.");
		else
		{

			$mdp = executeRequete("SELECT mdp FROM membre WHERE email = '".$email."' ");


		}
		mail($_SESSION['utilisateur']['email'], 'Confirmation de votre commande',"Merci pour votre commande , votre mot de passe est le : $email", "From: manuoval@gmail.com");
	}


}


//--------------------------------Suppression mdp------------------------------------------------CXL MDP

if (isset($_GET['supprimer']) && $_GET['supprimer'] == 'supprimer')
{

	$idmembre = $_SESSION['utilisateur']['id_membre'];
	executeRequete("DELETE FROM membre WHERE id_membre = '$idmembre' ");

	$_SESSION = array();
	session_destroy();
	session_start();
	writeFlashMessage('Vous avez bien été désabonné','success');
	header('location: boutique.php');


}
//a faire SUPRESSION PAR VERIFICATION MDP

//------------------------------------------Verification mdp---------------------------


if (isset($_SESSION['utilisateur'])) //    debug($_SESSION['pseudo']);
{

	$id_membre = ($_SESSION['utilisateur']['id_membre']);

	if (isset($_POST['modifier']))
	{
		extract($_POST);
		executeRequete("UPDATE membre m SET m.nom='$nom', m.prenom='$prenom', m.email='$email', m.ville='$ville', m.cp='cp', m.adresse='adresse' WHERE m.id_membre='$id_membre'");
	}

	if (isset($_POST['submitmdp']))
	{

		$amdp = $_POST['amdp'];
		$nmdp = $_POST['nmdp'];
		$vmdp = $_POST['vmdp'];

		if (verif_old_mdp($amdp, $id_membre))
		{

			if ($nmdp == $vmdp)
			{
				if (pregMatch($nmdp) && isSizeValid($nmdp, 4, 14))
				{
					$nmdp = ($nmdp);
					executeRequete("UPDATE membre  m SET m.mdp='" . $nmdp . "' WHERE m.id_membre='" . $id_membre . "'");
					echo 'mdp mdifié';

				} else
				{
					echo 'Caractères acceptés : A à Z et de 0 à 9., choisissez un mot de passe entre 4 et 14 caractères inclus';
				}
			} else
			{
				echo 'Merci de saisir deux mot de passe identique';
			}
		} else
		{
			echo 'Mot de passe erroné';
		}
	}

}



require_once("inc/bas_de_site.inc.php");
?>
	<td><a href="membres.php?supprimer=supprimer"
		   onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"
			>Vous désabonner</a></td>

<script>
	$('#changemdp').hide();
	$(document).ready()
	{
		$('#btnmodif').click(function ()
		{
			$('#changemdp').show();
		});
		$('#btncancel').click(function ()
		{
			$('#changemdp').hide();
		});
	}
	;
</script>


	<script>
	$('#lostmdp').hide();
	$(document).ready()
	{
		$('#btnlost').click(function ()
		{
			$('#lostmdp').show();
		});
		$('#btncxllost').click(function ()
		{
			$('#lostmdp').hide();
		});
	}
	;
</script>





