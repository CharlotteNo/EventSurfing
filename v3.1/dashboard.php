<?php
// Ouverture de la session.
session_start();

if ( empty($_SESSION) )
	header( "Location: index.php" );
else 
{
	require('Model/bdd.php');
	require('View/view.php');

	// Début
	$title = 'Dashboard';
	$sessionmail = $_SESSION['login'];
	$sessionid = $_SESSION['id'];
	$message = "Welcome, Here are the last event entries.";
	
	// Récupération des derniers events publiés
	$row = Database::get()->prepare_execute("SELECT * FROM event ORDER BY id DESC LIMIT 15" );
	if ( !empty($row) ) {
		$lastevents = new View('View/display_listEvent.html');
		$lastevents->setLoop(['LIST' => $row]);
	}	

	// Traitements
	$header = new View('View/header.html');
	$header->set(['TITLE' => $title, 'MESSAGE' => $message]);
	$nav = new View('View/navbar.html');
	$nav->set(['ID' => $sessionid, 'MAIL' => $sessionmail]);
	$search = new View('View/form_search.html');
	$deconnection = new View('View/form_deconnect.html');
	$footer = new View('View/footer.html');

	// Affichages
	$header->display();
	$nav->display();
	$search->display();
	if ( isset($lastevents) )
		$lastevents->display();
	$deconnection->display();
	$footer->display();
	
}
?>