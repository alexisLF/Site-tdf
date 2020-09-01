<?php

include('../includes/pdo.php');
include('../includes/utils.php');


if(isset($_POST['sub'])){

	$num = $_POST['n_epreuve'];
	$annee = $_POST['annee'];

	$conn = OuvrirConnexionPDO();

	$verif = "SELECT n_epreuve FROM tdf_temps WHERE n_epreuve = :num AND annee = :annee";

	$curs = preparerRequetePDO($conn, $verif);
	$curs = ajouterParamPDO($curs, ":num", $num);
	$curs = ajouterParamPDO($curs, ":annee", $annee);

	if(LireDonneesPDOPreparee($curs, $tab) == 0){

		$suppr = "DELETE FROM tdf_etape WHERE n_epreuve = :num AND annee = :annee";

		$curs = preparerRequetePDO($conn, $suppr);
		$curs = ajouterParamPDO($curs, ":num", $num);
		$curs = ajouterParamPDO($curs, ":annee", $annee);


		if(majDonneesPrepareesPDO($curs)){
			echo '<div class="ok">Étape supprimée !<br /></div>';
		}
		else{
			echo '<div class="erreur">Erreur de suppression<br /></div>';
		}

	}
	else{
		echo '<div class="erreur">Impossible de supprimer cette étape !<br /></div>';
	}
	

}

?>