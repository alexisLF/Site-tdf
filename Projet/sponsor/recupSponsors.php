<?php
	
	include('../includes/pdo.php');
	include('../includes/utils.php');

	$n_equipe = $_REQUEST['n_equipe'];
	$conn = OuvrirConnexionPDO();

	$req = "SELECT annee_sponsor, nom from tdf_sponsor where n_equipe = :n_equipe
			order by annee_sponsor";

	$curs = preparerRequetePDO($conn, $req);
	$curs = ajouterParamPDO($curs, ":n_equipe", $n_equipe);

	executeReqTab($curs);

?>