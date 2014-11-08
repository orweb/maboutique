
<!--					<label for="pseudo">Pseudo</label>-->
<!--						<input class="form-control" type="text" id="pseudo" name="pseudo" value="--><?php //if(isset($_POST['pseudo'])) echo $_POST['pseudo']?><!--" placeholder="Pseudo..." required maxlength="14" />-->
<!--					-->
<!--					<label for="mdp">Mot de passe</label>-->
<!--						<input class="form-control" type="password" id="mdp" name="mdp" value="--><?php //if(isset($_POST['mdp'])) echo $_POST['mdp']?><!--" placeholder="Mot de passe..." required maxlength="14" />-->
<!--					-->
<!--					<label for="nom">Nom</label>-->
<!--						<input class="form-control" type="text" id="nom" name="nom" value="--><?php //if(isset($_POST['nom'])) echo $_POST['nom']?><!--" placeholder="Nom..." />-->
<!--						-->
<!--					<label for="prenom">Prénom</label>-->
<!--						<input class="form-control" type="text" id="prenom" name="prenom" value="--><?php //if(isset($_POST['prenom'])) echo $_POST['prenom']?><!--" placeholder="Prénom..." />-->
<!--					-->
<!--					<label for="email">Email</label>-->
<!--						<input class="form-control" type="email" id="email" name="email" value="--><?php //if(isset($_POST['email'])) echo $_POST['email']?><!--" placeholder="Email..." />-->
<!--					-->
<!--					-->
<!--					<label for="sexe">Sexe</label><br />-->
<!--						<input class="radio-inline" type="radio" id="sexe" name="sexe" value="m" --><?php //if(isset($_POST['sexe']) && $_POST['sexe'] == 'm') { echo 'checked'; } elseif(!isset($_POST['sexe'])) { echo 'checked'; } ?><!-- />&nbsp;Homme-->
<!--						<input class="radio-inline" type="radio" id="sexe" name="sexe" value="f" --><?php //if(isset($_POST['sexe']) && $_POST['sexe'] == 'f') echo 'checked'; ?><!-- />&nbsp;Femme-->
<!--						-->
<!--					<br />-->
<!--						-->
<!--					<label for="ville">Ville</label>-->
<!--						<input class="form-control" type="text" id="ville" name="ville" value="--><?php //if(isset($_POST['ville'])) echo $_POST['ville']?><!--" placeholder="Ville..." />-->
<!--						-->
<!--					<label for="cp">Code postal</label>-->
<!--						<input class="form-control" type="text" id="cp" name="cp" maxlength="5" value="--><?php //if(isset($_POST['cp'])) echo $_POST['cp']?><!--" placeholder="Code postal..." />-->
<!--					-->
<!--					<label for="adresse">Adresse</label>-->
<!--						<textarea class="form-control" id="adresse" name="adresse" placeholder="Adresse..." >--><?php //if(isset($_POST['adresse'])) echo $_POST['adresse']?><!--</textarea><br />-->





<!---->
<!--$selection_membre = executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");-->
<!--if($selection_membre->num_rows != 0) //si il y a bien au moins 1 enregistrement retourné par la requête-->
<!--{-->
<!--$membre = $selection_membre->fetch_assoc();-->
<!--$msg .= "<div class='bg-success' style='padding:15px'><p>Bonjour ".$membre['prenom']."</p></div>";-->
<!---->
<!--if($membre['mdp'] == ($_POST['mdp']))-->
<!--{-->
<!--foreach($membre AS $indice => $element)-->
<!--{-->
<!--if($indice != 'mdp')// on n'inscrit pas le mdp dans la session.-->
<!--{-->
<!--$_SESSION['utilisateur'][$indice] = $element;// déclaration d'un ARRAY multidim. 1er indice de session 'utilisateur'. pour cette indice, on déclare une valeur qui sera un deuxième ARRAY. Boucle pour déclarer chaque $indice de cet array, attribution de la valeur correspondante (récupération des indices et valeurs de l'array $membre.-->
<!--}-->
<!--}-->
<!--header("location:profil.php");//envoie sur la page profil.-->
<!--}-->
<!--else-->
<!--{-->
<!--$msg .= "<div class='bg-danger' style='padding:15px'><p>Mot de passe invalide</p></div>";-->
<!--}-->
<!--}-->
<!--else-->
<!--{-->
<!--$msg .= "<div class='bg-danger' style='padding:15px'><p>Erreur de pseudo</p></div>";-->
<!--}-->
<!--}-->


if($_POST['change']);
{
$_SESSION['panier']['quantite'][$i] = $_POST['change'];
$selected = 'selected';
}
echo "<option $selected value=" . $j . ">$j</option>";