<?php

	include('../includes/pdo.php');


	$conn = OuvrirConnexionPDO();
	
	$req ="SELECT n_coureur, nom, prenom, annee_naissance, annee_prem, code_cio, annee_debut, annee_fin, 
			(SELECT code_cio FROM tdf_app_nation WHERE annee_fin IS NULL AND n_coureur = :n) as code2, 
			(SELECT annee_debut FROM tdf_app_nation WHERE annee_fin IS NULL AND n_coureur = :n) as debut2
			FROM tdf_coureur 
			JOIN tdf_app_nation USING(n_coureur) 
			WHERE n_coureur = :n AND rownum <= 1";

	$params = array(

		":n" => $_REQUEST['n_coureur']

	);

	$res = "";

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		$ligne = $curs->fetch(PDO::FETCH_ASSOC);

		$num = $ligne['N_COUREUR'];
		$nom = $ligne['NOM'];
		$prenom = $ligne['PRENOM'];
		$naiss = $ligne['ANNEE_NAISSANCE'];
		$prem = $ligne['ANNEE_PREM'];
		$code = $ligne['CODE_CIO'];
		$code2 = $ligne['CODE2'];
		$debut2 = $ligne['DEBUT2'];

		$res = $num.'|'.$nom.'|'.$prenom.'|'.$naiss.'|'.$prem.'|'.$code.'|'.$code2.'|'.$debut2;
	}
	else{
		$res = 'Échec';
	}

	echo $res;

?>