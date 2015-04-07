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
	$title = 'Update';
	$sessionmail = $_SESSION['login'];
	$sessionid = $_SESSION['id'];
	$message = 'Settings';
	
	if ( isset($_GET['type']) && isset($_GET['id']) && is_numeric($_GET['id']) ) {
		
		$id = $_GET['id'];
		
		if ( $_GET['type']=='event' ) {
			$content = new View('View/modif_event.html');
			
			// Récupération de l'organisateur
			$row = Database::get()->prepare_execute("SELECT host FROM event WHERE id='$id'");
			$host = $row[0]['host'];
			
			if ( $host==$sessionid ) {
				// Mise à jour si affectuée
				if ( isset($_POST['modif_event']) ) {
					$name = $_POST['name'];
					$address = $_POST['address'];
					$time = $_POST['time'];
					$bio = $_POST['bio'];
					Database::get()->insert_update("UPDATE event SET name = '$name', address = '$address', time = '$time', bio = '$bio' WHERE id = '$id'");
					header( "Location: dashboard.php" );
				}
				
				// Sinon, récupération des informations de l'event
				$row = Database::get()->prepare_execute("SELECT * FROM event WHERE id='$id'");
				$name = $row[0]['name'];
				$address = $row[0]['address'];
				$time = $row[0]['time'];
				$bio = $row[0]['bio'];
				
				//Affichage
				$content->set(['ID' => $id, 'NAME' => $name, 'ADDRESS' => $address,'TIME' => $time,'BIO' => $bio]);
				
			} else $message = 'Not yours.';
		}
		
		else if ( $_GET['type']=='user' ) {
			$content = new View('View/modif_profil.html');
			
			if ( $id==$sessionid ) {
				// Mise à jour si affectuée
				if ( isset($_POST['modif_profil']) ) {
					$name = $_POST['name'];
					$lastname = $_POST['lastname'];
					$city = $_POST['city'];
					$address = $_POST['address'];
					$bio = $_POST['bio'];
					Database::get()->insert_update("UPDATE user_profile SET name = '$name', lastname = '$lastname', city = '$city', address = '$address', bio = '$bio' WHERE user_id = '$id'");
					header( "Location: dashboard.php" );
				}
				
				// Sinon, récupération des informations de l'event
				$row = Database::get()->prepare_execute("SELECT * FROM user_profile WHERE user_id='$id'");
				$name = $row[0]['name'];
				$lastname = $row[0]['lastname'];
				$city = $row[0]['city'];
				$address = $row[0]['address'];
				$bio = $row[0]['bio'];
				
				//Affichage
				$content->set(['ID' => $id, 'NAME' => $name, 'LASTNAME' => $lastname, 'CITY' => $city, 'ADDRESS' => $address, 'BIO' => $bio]);
				
			} else $message = 'Not yours.';
		}
		
	}

	// Traitements
	$header = new View('View/header.html');
	$header->set(['TITLE' => $title, 'MESSAGE' => $message]);
	$nav = new View('View/navbar.html');
	$nav->set(['ID' => $sessionid, 'MAIL' => $sessionmail]);
	$deconnection = new View('View/form_deconnect.html');
	$footer = new View('View/footer.html');

	// Affichage
	$header->display();
	$nav->display();
	if ( !empty($content) )
		$content->display();
	$deconnection->display();
	$footer->display();
}


?>