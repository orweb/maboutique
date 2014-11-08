<?php

/*********************************************/
function executeRequete($req)
{
	global $mysqli; // permet d'inclure $mysqli depuis l'environnement global (script) dans l'environnement local de notre fonction.
	$resultat = $mysqli->query($req);
	if (!$resultat) // si cela renvoi FALSE // c'est qu'il y a une erreur
	{
		die ("Erreur sur la requête SQL.<br />Message : " . $mysqli->error . "<br />Code : " . $req);
	}
	return $resultat; // s'il n'y pas d'erreur on renvoi $resultat
}

//-------------------------------------
function debug($var, $mode = 1) // $mode est un argument facultatif, si l'on ne fourni qu'un seul argument à la fonction debug(), le deuxième argument aura la valeur 1 par défaut.
{
	echo '<div style="background: #' . rand(100000, 999999) . '">';
	if ($mode === 1)
	{
		print '<pre>';
		print_r($var);
		print '</pre>';
	} else
	{
		print '<pre>';
		var_dump($var);
		print '</pre>';
	}
	echo '<hr />';
	echo '</div>';
	return;
}
//function findInTable($string,$table,$field)
//{
//	$requete = executeRequete("SELECT * FROM". $table." WHERE".$field."='$string'");
//	if($requete->num_rows != 0)
//		return $requete->fetch_assoc();
//
//	return false;
//}
//---------------------------------------------
//--- FONCTION UTILISATEUR EST CONNECTE
function utilisateurEstConnecte()
{
	if (!isset($_SESSION['utilisateur']))
	{
		return FALSE;
	} else
	{
		return TRUE;
	}
}

function utilisateurEstConnecteEtEstAdmin()
{
	if (utilisateurEstConnecte() && $_SESSION['utilisateur']['statut'] == 1)
	{
		return TRUE;
	}
	return FALSE;
}

//-----------------------------------------------------
//-------------FONCTION VERIFICATION PHOTO-------------

function verificationExtensionPhoto()
{
	$extension = strrchr($_FILES['photo']['name'], '.'); // permet de retourner le texte contenu après le '.' (en partant de la fin du fichier)
	$extension = strtolower(substr($extension, 1)); // strtolower nous permet de tout passer en minuscule si ce n'est pas déjà le cas. Et substr() nous permet d'enlever le '.' pour n'avoir plus que jpg, png ect ect...
	$tab_extension_valide = array('gif', 'jpg', 'jpeg', 'png'); // nous préparons un tableau ARRAY qui contient les extensions autorisées

	$verif_extension = in_array($extension, $tab_extension_valide); // in_array() vérifie s'il trouve le premier argument comme étant une des valeurs du tableau ARRAY fourni en deuxième argument.
	return $verif_extension; // retourne TRUE ou FALSE
}

//********************FONCTIONS PANIER*****************

function creationDuPanier()
{
	if (!isset($_SESSION['panier']))
	{
		$_SESSION['panier'] = array();
		$_SESSION['panier']['titre'] = array();
		$_SESSION['panier']['id_article'] = array();
		$_SESSION['panier']['quantite'] = array();
		$_SESSION['panier']['prix'] = array();
	}
	return TRUE;
}

function ajouterArticleDansPanier($titre, $id_article, $quantite, $prix) // réception d'arguments en provenance de panier.php
{
	$position_article = array_search($id_article, $_SESSION['panier']['id_article']); // La fonction array_search() nous donne l'index où se trouve l'article que l'on cherche
	if ($position_article !== FALSE) // si l'indice a été trouvé grace à array_search()
	{
		$_SESSION['panier']['quantite'][$position_article] += $quantite; // nous allons chercher la quantité de l'article déjà présent et nous lui rajoutons la quantité supplémentaire.
	} else // sinon c'est un nouvel article, nous mettons donc les informations dans le panier
	{
		$_SESSION['panier']['titre'][] = $titre;
		$_SESSION['panier']['id_article'][] = $id_article;
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['prix'][] = $prix;
	}
}

// MONTANT TOTAL DU PANIER

function montantTotal()
{
	$total = 0;
	for ($i = 0; $i < count($_SESSION['panier']['quantite']); $i++)
	{
		$total += $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i];
	}
	return round($total, 2);
}
function montantTotalTtc()
{
	$total = 0;
	for ($i = 0; $i < count($_SESSION['panier']['quantite']); $i++)
	{
		$total += appliqueTtc($_SESSION['panier']['prix'][$i]) * $_SESSION['panier']['quantite'][$i];
	}
	return round($total, 2);
}

// RETIRER UN ARTICLE DU PANIER

function retirerArticleDuPanier($id_article)
{
	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);

	if ($position_article !== FALSE)
	{
		array_splice($_SESSION['panier']['id_article'], $position_article, 1);
		array_splice($_SESSION['panier']['titre'], $position_article, 1);
		array_splice($_SESSION['panier']['quantite'], $position_article, 1);
		array_splice($_SESSION['panier']['prix'], $position_article, 1);
		// array_splice() permet de retirer un élément d'un tableau ARRAY et de réordonner le tableau pour ne pas avoir de décalage dans les indices
	}

}

////a faire verifier si la fonction est ok via modif article dans panier
//function reduireArticleDuPanier($id_article)
//{
//	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);
//	if ($position_article !== FALSE)
//	{
//		array_reduce($_SESSION['panier']['id_article'], $position_article, 1);
//		array_reduce($_SESSION['panier']['titre'], $position_article, 1);
//		array_reduce($_SESSION['panier']['quantite'], $position_article, 1);
//		array_reduce($_SESSION['panier']['prix'], $position_article, 1);
//		// array_reduce() permet de reduire un élément d'un tableau ARRAY
//	}
//
//}

function verif_old_mdp($amdp, $id_membre)
{
	$amdp = ($amdp);

	$resultat = executeRequete("SELECT * FROM membre WHERE id_membre='$id_membre' AND mdp='$amdp'");


	if ($resultat->num_rows != 0)
	{
		return true;
	}
	return false;
}


function isSizeValid($string, $min, $max)
{
	if (strlen($string) >= $min && strlen($string) < $max)
		return true;
	return false;
}

// preg_match() va vérifier les caractères contenus dans le pseudo selon ceux que nous avons déclarés. -> retourne 0 si caractères interdits sinon retour 1
/*[] contiennent les caract. autorisés a ltr min, zA ltr maj. les # marquent le début et la fin nous permettant de préciser les options :
    ^ indique le pseudo va devoir forcément commencer par l'un des caractères cités.
    $ indique le pseudo va devoir forcément finir par l'un des caractères cités.
    ^ et $ utilisés ensemble indiquent que le pseudo ne contiendra que les caractères cités.
    le + autorisera la répétition de ces caractères.
*/
function pregMatch($string)
{
	if (preg_match('#^[a-zA-Z0-9._-]+$#', $string) && !empty($string))
		return true;
	return false;
}




function appliqueTtc($nombre)
{
    return $nombre + appliqueTva($nombre);
}

function appliqueTva($nombre)
{
    return $nombre*(20/100);
}



































