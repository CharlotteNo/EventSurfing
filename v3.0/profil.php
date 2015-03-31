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
	$title = 'Profil';
	$sessionmail = $_SESSION['login'];
	$sessionid = $_SESSION['id'];
	$modiflink = '';
	
	if( isset($_GET['id']) && is_numeric($_GET['id']) )
	{
		$id = $_GET['id'];
		$row = Database::get()->prepare_execute("SELECT * FROM user_profile WHERE user_id = $id" );
	
		if ( !empty($row) )
		{
			$name = $row[0]['name'];
			$lastname = $row[0]['lastname'];
			$createtime = $row[0]['createtime'];
			$address = $row[0]['address'];
			$city = $row[0]['city'];
			$bio = $row[0]['bio'];
	
			$message = "$name&#39s profile";
			if ( $id==$sessionid )
				$modiflink = '<a href="update.php?type=user&id='.$id.'"> <i>Modify this</i> </a>';
		}
		else
		{
			$message = 'This user do not exist.';
		}
	}
	else 
	{
		$message = 'No user to display.';
	}

	// Traitements
	$header = new View('View/header.html');
	$header->set(['TITLE' => $title, 'MESSAGE' => $message]);
	$nav = new View('View/navbar.html');
	$nav->set(['ID' => $sessionid, 'MAIL' => $sessionmail]);
	$content = new View('View/display_profil.html');
	$content->set(['NAME' => $name, 'LASTNAME' => $lastname, 'CREATETIME' => $createtime,'ADDRESS' => $address,'CITY' => $city,'BIO' => $bio, 'MODIFLINK' => $modiflink]);
	$deconnection = new View('View/form_deconnect.html');
	$footer = new View('View/footer.html');

	// Affichage
	$header->display();
	$nav->display();
	$content->display();
	$deconnection->display();
	$footer->display();
}


?>