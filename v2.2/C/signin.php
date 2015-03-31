<?php
require('../M/bdd.php');
require('../V/view.php');

//Début
$titre = 'Sign Up';

//Ouverture de la session.
session_start();

if( isset($_POST['identification']) )
{
	$mail = $_POST['mail'];
	
	$row = Database::get()->prepare_execute("SELECT id, password FROM user WHERE mail='$mail'" );
	$password = $row[0]['password'];
	$id = $row[0]['id'];
	
	if ( $password==$_POST['password'] )
	{
		//Enregistrement de la session
    $_SESSION['login'] = $_POST['mail'];
    $_SESSION['pwd'] = $_POST['password'];
		$message = "Vous etes bien loggé sous l'addresse: $mail";
		header("Location: ../C/profil_display.php?id=$id" );
	}
	else
	{
		//Authentification échouée
		$message = "Erreur de connexion.";
	}
}
else if( isset($_POST['deconnexion']) )
{
	//Suppression de la session
	session_unset();
	session_destroy();
	$message = "Deconnecté.";
}
else 
{
	if (!empty($_SESSION['login']))
	{
		$mail = $_SESSION['login'];
		$message = "Vous etes loggé sous l'addresse: $mail";
	}
	else 
	{
		$message = 'Veuillez saisir vos identfiants:';
	}
}
/* Traitement */
$header = new View('../V/header.html');
$header->set(['TITRE' => $titre]);
$body = new View('../V/signin.html');
$body->set(['MESSAGE' => $message]);
$footer = new View('../V/footer.html');

/* Affichage */
$header->display();
$body->display();
$footer->display();
?>