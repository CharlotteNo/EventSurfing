<?php
require('../M/bdd.php');
require('../V/view.php');

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
		$body = new View('../V/event_displayList.html');
		$body->setLoop(['LISTE' => $req]);
		}

	else $message = 'Aucun évènement.';

/*Traitements*/
$header = new View('../V/header.html');
$footer = new View('../V/footer.html');

/* Affichage */
$header->display();
if ( !empty($body) )
	$body->display();
$footer->display();
?>