<?php
require('../M/bdd.php');
require('../V/view.php');


if( isset($_GET['recherche']) )
{
	$nom = $_GET['nom'];

	if( $_GET['type'] === 'people')
	{
		$row = Database::get()->prepare_execute("SELECT * FROM user_profile WHERE name = '$nom' or lastname = '$recherche'" );
		if ( !empty($row) ) {
			$body = new View('../V/search_displayListPeople.html');
			$body->setLoop(['LISTE' => $row]);
		}			
	}
	else if( $_GET['type'] === 'event')
	{
		$row = Database::get()->prepare_execute("SELECT * FROM event WHERE name = '$nom'" );
		if ( !empty($row) ) {
			$body = new View('../V/search_displayListEvent.html');
			$body->setLoop(['LISTE' => $row]);
		}
	}
}


/* traitements */
$header = new View('../V/header.html');
$header->set(['TITRE'=> 'Recherche']);
$search = new View('../V/search.html');
$footer = new View('../V/footer.html');


/* affichage */
$header->display();
$search->display();
if ( !empty($body) )
	$body->display();
$footer->display();

?>
