<?php

	include('../includes/pdo.php');


	$conn = OuvrirConnexionPDO();
	
	$req ="SELECT * FROM tdf_etape 
			WHERE annee = :annee AND n_epreuve = :n_epreuve";

	$params = array(

		":annee" => $_REQUEST['annee'],
		":n_epreuve" => $_REQUEST['n_epreuve'],

	);

	$res = "";

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		$ligne = $curs->fetch(PDO::FETCH_ASSOC);

		$num = $ligne['N_EPREUVE'];
		$ville_d = $ligne['VILLE_D'];
		$ville_a = $ligne['VILLE_A'];
		$pays_d = $ligne['CODE_CIO_D'];
		$pays_a = $ligne['CODE_CIO_A'];
		$jour = $ligne['JOUR'];
		$distance = $ligne['DISTANCE'];
		$moyenne = $ligne['MOYENNE'];
		$cat_code = $ligne['CAT_CODE'];

		$mois = substr($jour, 3, 2);
		$jour = substr($jour, 0, 2);

		$res = $num.'|'.$ville_d.'|'.$ville_a.'|'.$pays_d.'|'.$pays_a.'|'.$jour.'|'.$mois.'|'.$distance.'|'.$moyenne.'|'.$cat_code;
	}
	else{
		$res = 'Échec';
	}

	echo $res;

?>