<?php
require('bdd.php');
require('view.php');

$vue = new View('recherche.html');
$vue->set(['TITRE'=> $titre]);


if( isset($_POST['recherche']) )
{
	$type = $_POST['type'];
	$recherche = $_POST['recherche'];

	if($type === 1){
	$row = Database::get()->prepare_execute("SELECT * FROM user_profile WHERE name = '$recherche' or lastname = '$recherche'" );
}
	else{
		$row = Database::get()->prepare_execute("SELECT * FROM event WHERE name = '$recherche'" );
	}
	$vue->setLoop(['LISTE' => $row]);
}
else 
{
	$message = 'Recherche';
}

/* traitements */
$titre = "Recherche $name";

/* Affichage */
// $vue = new View('recherche.html');
// $vue->set(['TITRE'=> $titre]);
// $vue->setLoop(['LISTE' => $row]);
$vue->display();
?>
