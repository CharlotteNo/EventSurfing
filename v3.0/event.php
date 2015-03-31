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
	$title = 'Event';
	$sessionmail = $_SESSION['login'];
	$sessionid = $_SESSION['id'];
	$modiflink = '';
	
	if( isset($_GET['id']) && is_numeric($_GET['id']) )
	{
		$id = $_GET['id'];
		$eventrow = Database::get()->prepare_execute("SELECT * FROM event WHERE id = $id" );
		
	
		if ( !empty($eventrow) )
		{
			// Récupération des données
			$name = $eventrow[0]['name'];
			$hostid = $eventrow[0]['host'];
			$user_name = Database::get()->prepare_execute("SELECT name FROM user_profile WHERE user_id = '$hostid'" );
			$host = $user_name[0]['name'];
			$address = $eventrow[0]['address'];
			$time = $eventrow[0]['time'];
			$city = $eventrow[0]['city'];
			$bio = $eventrow[0]['bio'];				
			
			// Si participation
			if ( isset($_GET['req']) && $_GET['req']=='participate' ) {
				Database::get()->insert_update("INSERT INTO event_relation (user_id, event_id) VALUES ('$sessionid','$id')");
				$message = 'You&#39re in.';
			}
			
			// Si commentaire
			else if ( isset($_POST['commentform']) && !empty($_POST['comment']) ) {
				$comment = $_POST['comment'];
				$time = date("Y-m-d");
				Database::get()->insert_update("INSERT INTO comment (user, time, text, event_id) VALUES ('$sessionid', '$time','$comment', '$id')");
				$message = "$name";
			}
			// Sinon
			else $message = "$name";
			
			// Récupération des comments
			$commentrow = Database::get()->prepare_execute("SELECT * FROM comment WHERE event_id = $id" );
			if ( !empty($commentrow) ) {
				$commentlist = new View('View/display_listComment.html');
				$commentlist->setLoop(['LIST' => $commentrow]);
			}
			
			// Création du lien
			if ( $hostid==$sessionid )
				$modiflink = '<a href="update.php?type=event&id='.$id.'"> <i>Modify this</i> </a>';
			else
				$modiflink = '<a href="event.php?id='.$id.'&req=participate"> <i>Participate</i> </a>';
		}
		else
		{
			$message = 'This event do not exist.';
		}
	}
	else 
	{
		$message = 'No event to display.';
	}

	// Traitements
	$header = new View('View/header.html');
	$header->set(['TITLE' => $title, 'MESSAGE' => $message]);
	$nav = new View('View/navbar.html');
	$nav->set(['ID' => $sessionid, 'MAIL' => $sessionmail]);
	$content = new View('View/display_event.html');
	$content->set(['NAME' => $name, 'HOSTID' => $hostid, 'HOST' =>$host, 'ADDRESS' => $address, 'TIME' => $time, 'CITY' => $city,'BIO' => $bio, 'MODIFLINK' => $modiflink]);
	$comment = new View('View/form_comment.html');
	$comment->set(['ID' => $id]);
	$deconnection = new View('View/form_deconnect.html');
	$footer = new View('View/footer.html');

	// Affichage
	$header->display();
	$nav->display();
	$content->display();
	if ( !empty($commentlist) )
		$commentlist->display();
	$comment->display();
	$deconnection->display();
	$footer->display();
}


?>