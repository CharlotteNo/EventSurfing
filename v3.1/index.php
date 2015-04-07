<?php
// Ouverture de la session.
session_start();

if( isset($_POST['deconnect']) )
{
	/////////////////////////////////// DECONNEXION ////////////////////////////////////
	session_unset();
	session_destroy();
}

if ( !empty($_SESSION) )
	header( "Location: dashboard.php" );
else 
{
	require('Model/bdd.php');
	require('View/view.php');

	// Début
	$title = 'Sign Up';
	$errormessage = '';
	$message = 'Please fill in the fields:';

	////////////////////////////////////// LOGIN ////////////////////////////////////////

	if( isset($_POST['signin']) && !empty($_POST['mail']) && !empty($_POST['password']) )
	{
		$mail = $_POST['mail'];
	
		$row = Database::get()->prepare_execute("SELECT id, password FROM user WHERE mail='$mail'" );
		$password = $row[0]['password'];
		$id = $row[0]['id'];
	
		if ( $password==$_POST['password'] )
		{
			//Enregistrement de la session
	    $_SESSION['login'] = $mail;
	    $_SESSION['password'] = $_POST['password'];
			$_SESSION['id'] = $id;
			header("Location: dashboard.php" );
		}
		else
		{
			//Authentification échouée
			$errormessage = "Connection failed.";
		}
	}

	////////////////////////////////// ENREGISTREMENT ///////////////////////////////////

	else if( isset($_POST['signup']) )
	{
		foreach( $_POST as $cle=>$val )
		{
			if( empty($val) )
					$errormessage = 'All the fields. <i>Please.</i> <br />';
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
				$createtime = date("Y-m-d");
				$address = $_POST['address'];
				$city = $_POST['city'];
				$bio = $_POST['bio'];
	
				Database::get()->insert_update("INSERT INTO user( mail, password, type ) VALUES ('$mail', '$password', 'user')");
	
				$row = Database::get()->prepare_execute("SELECT id FROM user WHERE mail='$mail'" );
				$id = $row[0]['id'];
	
				Database::get()->insert_update("INSERT INTO user_profile( name, lastname, createtime, address, city, bio, user_id )
																				VALUES ('$name', '$lastname', '$createtime', '$address', '$city', '$bio', '$id')");

				$message = "Thanks $name, you are now able to log in.";
			}
			else
			{
				$errormessage = 'Make sure your Email is valid. Passwords must be at least 6 characters long. <br />';
			}
		}	
	}
	

	// Traitement
	$header = new View('View/header.html');
	$header->set(['TITLE' => $title, 'MESSAGE' => $message]);
	$signin = new View('View/form_signin.html');
	$signin->set(['MESSAGE' => $errormessage]);
	$signup = new View('View/form_signup.html');
	$footer = new View('View/footer.html');

	// Affichage
	$header->display();
	$signin->display();
	$signup->display();
	$footer->display();
}
?>