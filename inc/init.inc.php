<?php

// ce fichier permet l'initialisation du site. il sera inclus sur toute les pages de notre site avec les fichiers minimums requis pour que le site puisse s'afficher dans de bonnes conditions.

require_once("connexion_bdd.inc.php");
require_once("fonction.inc.php");

session_start();


define('RACINE_SITE', '/maboutique/'); // à adapter une fois en ligne !
define('RACINE_SERVER', $_SERVER['DOCUMENT_ROOT']); // valable peu importe l'environnement

$msg = ""; // les messages à échanger avec l'internaute seront contenus dans cette variable. Respect Architecture et norme MVC : pas d'html avant le php.
// nécessaire de déclarer $msg pour pouvoir concaténer les messages d'erreur.
//tous les traitements php sont faits en haut de page.































