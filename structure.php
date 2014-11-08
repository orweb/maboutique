<?php 

require_once("inc/init.inc.php");

//zone réservée aux traitements php
//dans les bonnes pratiques, on effectue les traitements php avant l'affichage du site


require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");

echo $msg; //affichage des messages (erreur, validation, etc.)

?>

<?php

require_once("inc/bas_de_site.inc.php");

?>