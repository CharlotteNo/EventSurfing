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
	$title = 'Post';
	$sessionmail = $_SESSION['login'];
	$sessionid = $_SESSION['id'];
	$message = "Please fill in the fields.";
	$errormessage = '';
	
	
	if( isset($_POST['eventpost']) )
	{
	
		foreach( $_POST as $cle=>$val )
		{
			if( empty($val) )
					$errormessage = 'All the fields. <i>Please</i><br />';
		}
	
		if ( empty($errormessage) )
		{
			$name = $_POST['name'];
			$address = $_POST['address'];
			$time = $_POST['time'];
			$bio = $_POST['bio'];

			//création d'un nouvel evenement 
			Database::get()->insert_update("INSERT  INTO event (name, address, host, time, bio) VALUES ('$name', '$address','$sessionid', '$time', '$bio')");

			//récupère l'id du nouvel évenement
			$row = Database::get()->prepare_execute("SELECT id FROM event WHERE name='$name' AND host='$sessionid'"); //comment récuperer le bon id ? plusieurs conditions à vérifier ?
			$idEvent = $row[0]['id'];

			//pour chaque tag coché créer une relation entre le tag et l'event
			foreach($_POST['tag'] as $val){
				Database::get()->insert_update("INSERT INTO tag_relation (tag_id, event_id) VALUES ('$val','$idEvent')");
			};

			header("Location: dashboard.php" );
	    }
	}
	
	
	// Traitement
	$header = new View('View/header.html');
	$header->set(['TITLE' => $title, 'MESSAGE' => $message]);
	$nav = new View('View/navbar.html');
	$nav->set(['ID' => $sessionid, 'MAIL' => $sessionmail]);
	$post = new View('View/form_event.html');
	$footer = new View('View/footer.html');

	// Affichage
	$header->display();
	$nav->display();
	$post->display();
	$footer->display();
}
?>