<?php
require('../modele/bdd.php');
require('../vue/view.php');

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
		header("Location: ../profil/profil.php?id=$id" );
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

/* Affichage */
$vue = new View('signin.html');
$vue->set(['TITRE' => $titre, 'MESSAGE' => $message]);
$vue->display();
?>