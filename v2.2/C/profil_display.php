<?php
require('../M/bdd.php');
require('../V/view.php');

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

/* Traitement */
$header = new View('../V/header.html');
$header->set(['TITRE' => $titre]);
$body = new View('../V/profil_display.html');
$body->set(['MESSAGE' => $message, 'NAME' => $name, 'LASTNAME' => $lastname, 'ADDRESS' => $address,'CITY' => $city,'BIO' => $bio]);
$footer = new View('../V/footer.html');

/* Affichage */
$header->display();
$body->display();
$footer->display();
?>