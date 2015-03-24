<?php
require('bdd.php');
require('view.php');

if( isset($_GET['id']) && is_numeric($_GET['id']) )
{
	$id = $_GET['id'];
	$row = Database::get()->prepare_execute("SELECT * FROM user_profile INNER JOIN user ON user.id = user_profile.user_id WHERE user.id = $id" );
	
	if ( !empty($row) )
	{
		$name = $row[0]['name'];
		$lastname = $row[0]['lastname'];
		$address = $row[0]['address'];
		$city = $row[0]['city'];
		$bio = $row[0]['bio'];
	
		$message = "Profil de $name";
	}
	else
	{
		$message = 'Ce profil n&#39existe pas.';
	}
}
else 
{
	$message = 'Aucun profil a afficher.';
}

$titre = 'Profil';

/* Affichage */
$vue = new View('profil.html');
$vue->set(['TITRE' => $titre, 'MESSAGE' => $message, 'NAME' => $name, 'LASTNAME' => $lastname, 'ADDRESS' => $address,'CITY' => $city,'BIO' => $bio]);
$vue->display();
?>