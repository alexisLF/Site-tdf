<?php

	include('../includes/pdo.php');


	$conn = OuvrirConnexionPDO();
	
	$req = "SELECT n_equipe, annee, n_pre_directeur, n_co_directeur
			FROM tdf_parti_equipe
			WHERE n_equipe = :n AND annee = :annee AND rownum <= 1";

	$params = array(

		":n" => $_REQUEST['n_equipe'],
		":annee" => $_REQUEST['annee']

	);

	$res = "";

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		$ligne = $curs->fetch(PDO::FETCH_ASSOC);

		$num = $ligne['N_EQUIPE'];
		$annee = $ligne['ANNEE'];
		$direc = $ligne['N_PRE_DIRECTEUR'];
		$co_direc = $ligne['N_CO_DIRECTEUR'];


		$res = $num.'|'.$annee.'|'.$direc.'|'.$co_direc;
	}

	echo $res;

?>