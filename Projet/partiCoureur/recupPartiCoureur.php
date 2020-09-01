<?php

	include('../includes/pdo.php');


	$conn = OuvrirConnexionPDO();
	
	$req = "SELECT valide, n_equipe
			FROM tdf_parti_coureur
			WHERE n_coureur = :n AND annee = :annee AND rownum <= 1";

	$params = array(

		":n" => $_REQUEST['n_coureur'],
		":annee" => $_REQUEST['annee']

	);

	$res = "";

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		$ligne = $curs->fetch(PDO::FETCH_ASSOC);

		$equipe = $ligne['N_EQUIPE'];
		$valide = $ligne['VALIDE'];


		$res = $equipe.'|'.$valide;
	}

	echo $res;

?>