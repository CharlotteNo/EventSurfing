<?php
require('bdd.php');
require('view.php');

$vue = new View('recherche.html');
$vue->set(['TITRE'=> $titre]);


if( isset($_POST['recherche']) )
{
	$name = $_POST['name'];

	$row = Database::get()->prepare_execute("SELECT * FROM user_profile WHERE name = '$name' or lastname = '$name'" );

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