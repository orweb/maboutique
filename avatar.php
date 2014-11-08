<?php

require_once("inc/init.inc.php");

//zone réservée aux traitements php
//dans les bonnes pratiques, on effectue les traitements php avant l'affichage du site


require_once("inc/haut_de_site.inc.php");
require_once("inc/menu.inc.php");

echo $msg; //affichage des messages (erreur, validation, etc.)

?>
	<script >
		<!--
		function move_avatar($avatar)
		{
			<?php
			$extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
			$name = time();
			$namepicture = str_replace(' ','',$name).".".$extension_upload;
			$name = "avatar/".str_replace(' ','',$name).".".$extension_upload;
			move_uploaded_file($avatar['tmp_name'],$name);
			return $namepicture;
			?>
		}
		//-->
	</script>

<?php

// on se connecte à MySQL
$db = mysql_connect('localhost', 'root', '');

// on sélectionne la base
mysql_select_db('maboutique',$db);

?>
	<div id="menu">
		<?php

		/*AVATAR*/
		$id_membre=$_SESSION["id_membre"];

		$query1=mysql_query("SELECT * FROM membre WHERE id_membre like '$id_membre'");
		$result1=mysql_fetch_array($query1);

		//Vérification de l'avatar :
		if ($result1["avatar"]=="")
		{
			?>
			<form enctype="multipart/form-data" action="uploadpicture.php" method="post">
				<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
				Send this file :<br>
				<input type="file" name="imgfile" /><br>
				<input type="submit" value="Send file"  />
			</form>
		<?php


		}
		else
		{
			?>
			<img src="avatar/<?php echo $result1['avatar'];?>" width="120">
		<?php
		}
		/*END AVATAR*/

		echo '<br>';
		?>
		<a href="membres.php"><h3>Profile</h3></a>
		<?php

		echo '<br>';
		?>
		<a href="membres.php"><input type="image" id="bouton-submit" ></a>

	</div>

<?php

require_once("inc/bas_de_site.inc.php");

?>