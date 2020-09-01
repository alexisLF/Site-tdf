<?php

	include('../includes/pdo.php');


	$conn = OuvrirConnexionPDO();
	
	$req = "SELECT * FROM tdf_sponsor WHERE n_equipe = :n AND n_sponsor = 
			(
				SELECT MAX(N_SPONSOR) AS n_sponsor FROM tdf_sponsor 
				WHERE n_equipe = :n
			)";

	$params = array(

		":n" => $_REQUEST['n_equipe']

	);

	$res = "";

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		$ligne = $curs->fetch(PDO::FETCH_ASSOC);

		$n_equipe = $ligne['N_EQUIPE'];
		$nom = $ligne['NOM'];
		$nat = $ligne['CODE_CIO'];
		$annee = $ligne['ANNEE_SPONSOR'];
		$diminutif = $ligne['NA_SPONSOR'];
		

		$res = $n_equipe.'|'.$nom.'|'.$nat.'|'.$annee.'|'.$diminutif;
	}
	else{
		$res = 'Échec';
	}

	echo $res;

?>