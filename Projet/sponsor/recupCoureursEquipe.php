<?php

	include('../includes/pdo.php');
	include('../includes/utils.php');

	$n_equipe = $_REQUEST['n_equipe'];
	$annee = $_REQUEST['annee'];
	$conn = OuvrirConnexionPDO();

	$req = "SELECT n_dossard, nom, prenom FROM tdf_coureur 
			JOIN tdf_parti_coureur USING (n_coureur)
			WHERE n_equipe = :n_equipe AND annee = :annee
			order by n_dossard";

	$params = array(

	":n_equipe" => $n_equipe,
	":annee" => $annee

	);

	$curs = preparerRequetePDO($conn, $req);

	executeReqTabPrepare($curs, $params);

?>