<?php
require('bdd.php');
require('view.php');


if( isset($_POST['nouvel_utilisateur']) )
{
	$mail = $_POST['mail'];
	$password = $_POST['password'];
	$name = $_POST['name'];
	$lastname = $_POST['lastname'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$bio = $_POST['bio'];
	
	Database::get()->insert_update("INSERT INTO user( mail, password, type ) VALUES ('$mail', '$password', 'user')");
	
	$row = Database::get()->prepare_execute("SELECT id FROM user WHERE mail='$mail'" );
	$id = $row[0]['id'];
	
	Database::get()->insert_update("INSERT INTO user_profile( name, lastname, address, city, bio, user_id ) VALUES ('$name', '$lastname', '$address', '$city', '$bio', '$id')");

	$message = "Merci $name !";
}
else 
{
	$message = 'Veuillez saisir vos informations:';
}

/* traitements */
$titre = 'Sign Up';

/* Affichage */
$vue = new View('signup.html');
$vue->set(['TITRE' => $titre, 'MESSAGE' => $message]);
$vue->display();
?>