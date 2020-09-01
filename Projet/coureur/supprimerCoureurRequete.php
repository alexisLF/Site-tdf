<?php

include('../includes/pdo.php');
include('../includes/utils.php');


if(isset($_POST['sub'])){

	$num = $_POST['num'];

	$conn = OuvrirConnexionPDO();

	$verif = "SELECT n_coureur FROM tdf_parti_coureur WHERE n_coureur = :num";

	$curs = preparerRequetePDO($conn, $verif);
	$curs = ajouterParamPDO($curs, ":num", $num);

	if(LireDonneesPDOPreparee($curs, $tab) == 0){

		$suppr = "DELETE FROM tdf_coureur WHERE n_coureur = :num";

		$curs = preparerRequetePDO($conn, $suppr);
		$curs = ajouterParamPDO($curs, ":num", $num);

		$supprNation = "DELETE FROM tdf_app_nation WHERE n_coureur = :num";

		$cursNation = preparerRequetePDO($conn, $supprNation);
		$cursNation = ajouterParamPDO($cursNation, ":num", $num);


		if(majDonneesPrepareesPDO($curs) && majDonneesPrepareesPDO($cursNation)){
			echo '<div class="ok">Coureur supprimé !<br /></div>';
		}
		else{
			echo '<div class="erreur">Erreur de suppression<br /></div>';
		}

	}
	else{
		echo '<div class="erreur">Impossible de supprimer ce coureur !<br /></div>';
	}


}

?>
