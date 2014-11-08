<?php

$mysqli = @new Mysqli('localhost', 'root', '', 'diw05_site'); // le @ devant le new permet d'éviter le message d'erreur généré par php afin de le gérer proprement.
// ATTENTION : on ne met le @ que si on utilise le IF qui suit : (pour gérer les erreurs proprement)
if($mysqli->connect_error)
{
	die ("Un problème est survenu lors de la tentative de connexion à la BDD : ".$mysqli->error);
}

$mysqli->set_charset("utf-8");// à utiliser en cas de pb d'encodage.



































