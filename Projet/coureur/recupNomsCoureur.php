<?php

	include('../includes/pdo.php');


	$conn = OuvrirConnexionPDO();

	$champs = $_REQUEST['colonnes'];

	$champs = substr($champs, 0, -1);

	$champs = str_replace("nom||prenom", "nom||' '||prenom", $champs);


	$champsFinaux = "N_COUREUR,";

	if(preg_match("/COUREUR/", $champs))
		$champsFinaux .= '"COUREUR",';

	if(preg_match("/annee_naissance/", $champs))
		$champsFinaux .= '"ANNÉE DE NAISSANCE",';

	if(preg_match("/annee_prem/", $champs))
		$champsFinaux .= '"PREMIÈRE PARTICIPATION",';

	$champsFinaux = substr($champsFinaux, 0, -1);

	$req = "SELECT $champsFinaux FROM
			(SELECT $champs FROM tdf_coureur
			WHERE nom LIKE :nom
			AND annee_naissance LIKE :naiss
			AND annee_prem LIKE :prem

			UNION

			SELECT $champs FROM tdf_coureur
			WHERE nom LIKE :nom
			AND (annee_naissance IS NULL OR annee_prem IS NULL))
			ORDER BY \"COUREUR\"";

	/*$req = "SELECT $champs FROM tdf_coureur
			WHERE nom LIKE :nom
			AND annee_naissance LIKE :naiss
			AND annee_prem LIKE :prem
			ORDER BY nom, prenom";*/


	$getNom = strtoupper($_REQUEST['nom']);
	$getNaiss = $_REQUEST['naiss'];
	$getPrem = $_REQUEST['prem'];

	$params = array(

		":nom" => "$getNom%",
		":naiss" => "$getNaiss%",
		":prem" => "$getPrem%"


	);

	$res = "";
	$actuel = "";

	$curs = $conn->prepare($req);

	$cols = false;

	if($curs->execute($params)){
		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){

			if(!$cols){
				foreach($ligne as $cle=>$val)
					$res .= '<b>'.$cle.'</b>|';

				$res = substr_replace($res, '<br />', -1);
				$cols = true;
			}

			$actuel = "";

			foreach($ligne as $cle=>$val){
				if($cle == "ANNÉE DE NAISSANCE" || $cle == "PREMIÈRE PARTICIPATION"){
					$val = preg_replace("/^0$/", " ", $val);
				}
				$actuel .= $val.'|';
			}

			$actuel = substr_replace($actuel, '<br />', -1);

			$res .= $actuel;

		}
	}
	else{
		$res .= 'Échec';
	}

	$res = substr_replace($res, '', -6);

	echo $res;

?>