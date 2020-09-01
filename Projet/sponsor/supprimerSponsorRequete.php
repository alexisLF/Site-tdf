<?php

include('../includes/pdo.php');
include('../includes/utils.php');

if(isset($_POST['sub'])){

	$n_equipe = $_POST['n_equipe'];

	$conn = OuvrirConnexionPDO();

	$verif = "SELECT n_sponsor FROM tdf_sponsor WHERE n_sponsor = :n_equipe";

	$curs = preparerRequetePDO($conn, $verif);
	$curs = ajouterParamPDO($curs, ":n_equipe", $n_equipe);

	if(LireDonneesPDOPreparee($curs, $tab) == 0){

		$suppr = "DELETE FROM tdf_sponsor WHERE n_equipe = :n_equipe";

		$curs = preparerRequetePDO($conn, $suppr);
		$curs = ajouterParamPDO($curs, ":n_equipe", $n_equipe);


		if(majDonneesPrepareesPDO($curs)){
			echo '<div class="ok">Sponsor supprimé !<br /></div>';
		}
		else{
			echo '<div class="erreur">Erreur de suppression<br /></div>';
		}

	}
	else{
		echo '<div class="erreur">Impossible de supprimer ce sponsor !<br /></div>';
	}


}

?>
