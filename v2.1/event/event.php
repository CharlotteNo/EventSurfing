<?php
require('../modele/bdd.php');
require('../vue/view.php');

/*Initialisation des messages HTML*/
$titre = 'Mes events';

//Ouverture de la session
session_start();

	$mail = $_SESSION['login'];

	//récupère l'id de l'utilisateur logger
	$row = Database::get()->prepare_execute("SELECT id FROM user WHERE mail='$mail'");
	$id = $row[0]['id'];

	//sélectionne les events ou l'id de l'hote = $_SESSION
	$req = Database::get()->prepare_execute("SELECT * FROM event WHERE host='$id'");

	if ( !empty($req) ) {
		$body = new View('event.html');
		$body->setLoop(['LISTE' => $req]);
		}

	else $message = 'Aucun évènement.';

/*Traitements*/
$affichage = new View('event.html');

/* Affichage */
$affichage->display();
if ( !empty($body) )
	$body->display();
?>