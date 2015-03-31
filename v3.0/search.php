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
	$title = 'Search';
	$sessionmail = $_SESSION['login'];
	$sessionid = $_SESSION['id'];
	$message = '';
	
	if( isset($_GET['search']) )
	{
		$name = $_GET['name'];

		if( $_GET['type'] === 'people')
		{
			$row = Database::get()->prepare_execute("SELECT * FROM user_profile WHERE name = '$name' or lastname = '$name'" );
			if ( !empty($row) ) {
				$results = new View('View/display_listUser.html');
				$results->setLoop(['LIST' => $row]);
				$message = 'Users you are looking for.';
			}
		}
		else if( $_GET['type'] === 'event')
		{
			$row = Database::get()->prepare_execute("SELECT * FROM event WHERE name = '$name'" );
			if ( !empty($row) ) {
				$results = new View('View/display_listEvent.html');
				$results->setLoop(['LIST' => $row]);
				$message = 'Events you are looking for.';
			}
		}
	}


	/* traitements */
	$header = new View('View/header.html');
	$header->set(['TITLE' => $title, 'MESSAGE' => $message]);
	$nav = new View('View/navbar.html');
	$nav->set(['ID' => $sessionid, 'MAIL' => $sessionmail]);
	$search = new View('View/form_search.html');
	$deconnection = new View('View/form_deconnect.html');
	$footer = new View('View/footer.html');


	/* affichage */
	$header->display();
	$nav->display();
	$search->display();
	if ( !empty($results) )
		$results->display();
	$deconnection->display();
	$footer->display();
}


?>