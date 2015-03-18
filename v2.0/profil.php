<?php
require('bdd.php');
require('view.php');

if( isset($_POST['nouvel_utilisateur']) )
{

	$row = Database::get()->prepare_execute("SELECT * FROM user_profile INNER JOIN user ON user.id = user_profile.user_id WHERE user.id = 3" );
	
	$name = $row[0]['name'];
	$lastname = $row[0]['lastname'];
	$address = $row[0]['address'];
	$city = $row[0]['city'];
	$bio = $row[0]['bio'];
}
else 
{
	$message = 'Mon profil';
}

/* traitements */
$titre = 'Profil';

/* Affichage */
$vue = new View('profil.html');
$vue->set(['TITRE' => $titre, 'NAME' => $name, 'LASTNAME' => $lastname, 'ADDRESS' => $address,'CITY' => $city,'BIO' => $bio]);
$vue->display();
?>