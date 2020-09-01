<?php

	include('../includes/pdo.php');


	$conn = OuvrirConnexionPDO();

	$champs = $_REQUEST['colonnes'];

	$champs = substr($champs, 0, -1);

	$champs2 = str_replace("dir.nom||' '||dir.prenom", "' '", $champs);
	$champs2 = str_replace("codir.nom||' '||codir.prenom", "' '", $champs2);
	echo $champs2;

	$req = "SELECT * FROM
			(SELECT $champs FROM tdf_parti_equipe
			JOIN tdf_directeur dir ON n_pre_directeur = dir.n_directeur
			JOIN tdf_directeur codir ON n_co_directeur = codir.n_directeur
			JOIN tdf_sponsor spons USING(n_equipe, n_sponsor)
			UNION
			SELECT $champs2 FROM tdf_parti_equipe
			JOIN tdf_sponsor spons USING(n_equipe, n_sponsor)
			WHERE n_pre_directeur IS NULL OR n_co_directeur IS NULL)";
	
	$req .= " WHERE annee LIKE :annee";
	$req .= " ORDER BY annee, equipe";

	$getAnnee = $_REQUEST['annee'];

	$params = array(

		":annee" => "$getAnnee%",

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