<?php

include('../includes/pdo.php');
include('../includes/utils.php');
include('../includes/regex.php');

if(isset($_POST['sub'])){

	$ok = true;
	$affiche = "";

	if(empty($_POST['nom'])){
		$affiche .= 'Veuillez saisir un nom<br />';
		$ok = false;
	}
	if(empty($_POST['prenom'])){
		$affiche .= 'Veuillez saisir un prénom<br />';
		$ok = false;
	}
	if($_POST['paysNaissance'] == '0'){
		$affiche .= 'Veuillez sélectionner un pays<br />';
		$ok = false;
	}


	/*Validité dates*/

	if(!empty($_POST['prem'])){
		if(intval($_POST['prem'] < 1900)){
			$affiche .= 'Pas de dates avant 1900<br />';
			$ok = false;
		}
	}

	if(!empty($_POST['naiss'])){
		if(intval($_POST['naiss'] < 1900)){
			$affiche .= 'Pas de dates avant 1900<br />';
			$ok = false;
		}
	}

	if(!empty($_POST['datePaysActuel'])){
		if(intval($_POST['datePaysActuel'] < 1900)){
			$affiche .= 'Pas de dates avant 1900<br />';
			$ok = false;
		}
	}


	if(!empty($_POST['prem'] && !empty($_POST['naiss']))){
		if(intval($_POST['prem']) - intval($_POST['naiss']) < 19){
			$affiche .= 'Le coureur n\'avait pas 18 ans lors de son premier tour<br />';
			$ok = false;
		}
	}

	if($_POST['paysActuel'] != '0' && $_POST['paysActuel'] != $_POST['paysNaissance'] && empty($_POST['datePaysActuel'])){
		$affiche .= 'Veuillez saisir une date pour la nationalité actuelle<br />';
		$ok = false;
	}
	else if($_POST['paysActuel'] != '0' && $_POST['paysActuel'] != $_POST['paysNaissance'] && intval($_POST['naiss']) > intval($_POST['datePaysActuel'])){
		$affiche .= 'Le coureur ne peut pas avoir obtenu une nouvelle nationalité avant sa naissance<br />';
		$ok = false;
	}

	if($ok){
		$conn = OuvrirConnexionPDO();

		$update = "UPDATE tdf_coureur SET nom = :nom, prenom = :prenom, annee_naissance = :naiss, annee_prem = :prem WHERE n_coureur = :num";

		$updateNaiss = "UPDATE tdf_app_nation SET annee_debut = :naiss WHERE n_coureur = :num AND annee_debut <= all (
		  SELECT min(annee_debut) FROM tdf_app_nation
		  WHERE n_coureur = :num
		)";


		$num = $_POST['num'];
		$naiss = $_POST['naiss'];
		$prem = $_POST['prem'];

		$nom = $_POST['nom'];
		$nom = verificationNom($nom);


		$prenom = $_POST['prenom'];
		$prenom = verificationPrenom($prenom);

		$ok = true;

		if($nom === 0){
			$ok = false;
			$affiche .= 'Le nom est invalide<br />';
		}
		if($prenom === 0){
			$ok = false;
			$affiche .= 'Le prénom est invalide<br />';
		}

		if($ok){

			$params = array(

				":num" => $num,
				":nom" => $nom,
				":prenom" => $prenom,
				":naiss" => $naiss,
				":prem" => $prem

				);

			$curs = preparerRequetePDO($conn, $update);

			$paramsNaiss = array(

				":num" => $num,
				":naiss" => $naiss,

				);

			$cursNaiss = preparerRequetePDO($conn, $updateNaiss);

			if(majDonneesPrepareesTabPDO($curs, $params) && majDonneesPrepareesTabPDO($cursNaiss, $paramsNaiss)){
				echo '<div class="ok">Modification effectué<br /></div>';
			}
			else{
				echo '<div class="erreur">Échec de la modification<br /></div>';
			}

		}
		else{
			echo '<div class="erreur">'.$affiche.'</div>';
		}
	}
	else{
		echo '<div class="erreur">'.$affiche.'</div>';
	}

}

?>
