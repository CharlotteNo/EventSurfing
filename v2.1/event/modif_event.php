<?php
require('../modele/bdd.php');
require('../vue/view.php');

/*Initialisation des messages HTML*/
$titre = 'Créer un event';
$errormessage = '';

//Ouverture de la session
session_start();

if( isset($_GET['create_event']) && !empty($_GET) )
{
	
	foreach( $_GET as $cle=>$val )
	{
		if( empty($val) )
				$errormessage = 'Merci de renseigner tous les champs. <br />';
	}
	
	if ( empty($errormessage) )
	{
		$mail = $_SESSION['login'];
		$name = $_GET['name'];
		$address = $_GET['address'];
		$time = $_GET['time'];
		$bio = $_GET['bio'];

		//récupère l'id de l'utilisateur logger
		$row = Database::get()->prepare_execute("SELECT id FROM user WHERE mail='$mail'");
		$id = $row[0]['id'];
		echo $id;

		//création d'un nouvel evenement 
		Database::get()->insert_update("UPDATE event (name, address, host, time, bio)
	            						VALUES ('$name', '$address','$id', '$time', '$bio')");

		//récupère l'id du nouvel évenement
		$req = Database::get()->prepare_execute("SELECT id FROM event WHERE name='$name' AND host='$id'"); 
		$idEvent = $req[0]['id'];
		echo $idEvent;
		var_dump($idEvent);

		//pour chaque tag coché créer une relation entre le tag et l'event
		foreach($_GET["choix"] as $valeur){
			Database::get()->insert_update("INSERT INTO tag_relation (tag_id, event_id) VALUES ('$valeur','$idEvent')");
		};

		$message = "Evenement $name créé !";
    }
}

/* Affichage */
$vue = new View('create_event.html');
$vue->set(['TITRE' => $titre,'ERRORMESSAGE' => $errormessage, 'MESSAGE' => $message]);
$vue->display();
?>