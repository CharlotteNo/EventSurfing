<?php
require('../modele/bdd.php');
require('../vue/view.php');

/*Initialisation des messages HTML*/
$titre = 'Sign Up';
$message = 'Veuillez saisir vos informations:';
$errormessage = '';

if( isset($_POST['nouvel_utilisateur']) && !empty($_POST) )
{
	
	foreach( $_POST as $cle=>$val )
	{
		if( empty($val) )
				$errormessage = 'Merci de renseigner tous les champs. <br />';
	}
	
	if ( empty($errormessage) )
	{
		if ( filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) && strlen($_POST['password']) > 5 ) 
		{
			/* Requetes si tous les champs sont valides */
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
	
			Database::get()->insert_update("INSERT INTO user_profile( name, lastname, address, city, bio, user_id )
																			VALUES ('$name', '$lastname', '$address', '$city', '$bio', '$id')");

			$message = "Merci $name !";
		}
		else
		{
			$errormessage = 'Merci de donner une addresse mail valide, et un mot de passe de plus de 5 caractères. <br />';
		}
	}	
}

/* Affichage */
$vue = new View('signup.html');
$vue->set([ 'TITRE' => $titre, 'MESSAGE' => $message, 'ERRORMESSAGE' => $errormessage ]);
$vue->display();
?>