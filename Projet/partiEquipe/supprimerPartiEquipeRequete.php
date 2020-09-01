<?php

include('../includes/pdo.php');
include('../includes/utils.php');


if(isset($_POST['sub'])){

	$n = $_POST['equipe'];
	$annee = $_POST['annee'];

	$conn = OuvrirConnexionPDO();

	$verif = "SELECT n_coureur FROM tdf_parti_coureur WHERE n_equipe = :n AND annee = :annee";

	$curs = preparerRequetePDO($conn, $verif);
	$curs = ajouterParamPDO($curs, ":n", $n);
	$curs = ajouterParamPDO($curs, ":annee", $annee);

	if(LireDonneesPDOPreparee($curs, $tab) == 0){

		$suppr = "DELETE FROM tdf_parti_equipe WHERE n_equipe = :n AND annee = :annee";

		$curs = preparerRequetePDO($conn, $suppr);
		$curs = ajouterParamPDO($curs, ":n", $n);
		$curs = ajouterParamPDO($curs, ":annee", $annee);


		if(majDonneesPrepareesPDO($curs)){
			echo '<div class="ok">Participation supprimée !<br /></div>';
		}
		else{
			echo '<div class="erreur">Erreur de suppression<br /></div>';
		}

	}
	else{
		echo '<div class="erreur">Impossible de supprimer cette participation !<br /></div>';
	}


}

?>
