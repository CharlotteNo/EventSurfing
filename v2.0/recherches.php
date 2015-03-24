<?php
require('bdd.php');
require('view.php');

$titre = "Recherche";

if( isset($_GET['recherche']) && !empty($_GET['nom']) )
{
	$nom = $_GET['nom'];

	if( $_GET['type'] === 'people')
	{
		$row = Database::get()->prepare_execute("SELECT * FROM user_profile WHERE name = '$nom' or lastname = '$recherche'" );
		if ( !empty($row) ) {
			$vue = new View('recherches_people.html');
			$vue->setLoop(['LISTE' => $row]);
		}			
	}
	else if( $_GET['type'] === 'event')
	{
		$row = Database::get()->prepare_execute("SELECT * FROM event WHERE name = '$nom'" );
		if ( !empty($row) ) {
			$vue = new View('recherches_event.html');
			$vue->setLoop(['LISTE' => $row]);
		}
	}
	
	if ( empty($row) )
		$vue = new View('recherches.html');
}
else
{
	$vue = new View('recherches.html');
}

/* traitements */
$vue->set(['TITRE'=> $titre]);
$vue->display();
?>
