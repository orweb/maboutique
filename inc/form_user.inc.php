<?php
require_once("init.inc.php");

?>
<!--pseudo-->
<label for="pseudo">Pseudo</label>
<input class="form-control" type="text" id="pseudo" name="pseudo" value="
<?php if(isset($_POST['pseudo']))
            echo $_POST['pseudo'] ;
      elseif
          (isset($_SESSION['utilisateur']))
            echo $_SESSION['utilisateur']['pseudo'];
if(isset($_GET['action']) && $_GET['action'] == 'modification') { echo "readonly";}
    ?>
" placeholder="Pseudo..." required maxlength="14" />


<!--nom-->
<label for="nom">Nom</label>
<input class="form-control" type="text" id="nom" name="nom" value="
<?php if(isset($_POST['nom'])) echo $_POST['nom'];

    elseif
        (isset($_SESSION['utilisateur']))
        echo $_SESSION['utilisateur']['nom'];
?>
" placeholder="Nom..." />
<!--prenom-->
<label for="prenom">Prénom</label>
<input class="form-control" type="text" id="prenom" name="prenom" value="
<?php if(isset($_POST['prenom']))
    echo $_POST['prenom'];

elseif (isset($_SESSION['utilisateur']))
    echo $_SESSION['utilisateur']['prenom'];

?>" placeholder="Prénom..." />
<!--email-->
<label for="email">Email</label>
<input class="form-control" type="email" id="email" name="email" value="
<?php if(isset($_POST['email']))
    echo $_POST['email'];

    elseif (isset($_SESSION['utilisateur']))
    echo $_SESSION['utilisateur']['email'];

?>" placeholder="Email..." />

<!--sexe-->
<label for="sexe">Sexe</label><br />
<input class="radio-inline" type="radio" id="sexe" name="sexe" value="m"
    <?php if(isset($_POST['sexe']) && $_POST['sexe'] == 'm')
    {
        echo 'checked';
    }


    elseif(isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 'm')
    {
        echo $_SESSION['utilisateur']['sexe'];
    }
    ?> />&nbsp;Homme
<input class="radio-inline" type="radio" id="sexe" name="sexe" value="f"

    <?php if(isset($_POST['sexe']) && $_POST['sexe'] == 'f')

        echo 'checked';

    elseif(isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 'f')

    {
        echo $_SESSION['utilisateur']['sexe'];
    }
    ?> />&nbsp;Femme

<br />

<!--ville-->
<label for="ville">Ville</label>
<input class="form-control" type="text" id="ville" name="ville" value="
<?php if(isset($_POST['ville']))
    echo $_POST['ville'];

elseif(isset($_SESSION['utilisateur']))

{
    echo $_SESSION['utilisateur']['ville'];
}
?>
" placeholder="Ville..." />

<!--code postal-->
<label for="cp">Code postal</label>
<input class="form-control" type="text" id="cp" name="cp" maxlength="5" value="
<?php if(isset($_POST['cp'])) echo $_POST['cp'];

elseif(isset($_SESSION['utilisateur']))

{

    echo $_SESSION['utilisateur']['cp'];
}
?>
" placeholder="Code postal..." />

<!--adresse-->
<label for="adresse">Adresse</label>
<textarea class="form-control" id="adresse" name="adresse" placeholder="Adresse..." >
    <?php if(isset($_POST['adresse']))
        echo $_POST['adresse'];

    elseif(isset($_SESSION['utilisateur']))

    {
        echo $_SESSION['utilisateur']['adresse'];
    }
    ?>
</textarea><br />
