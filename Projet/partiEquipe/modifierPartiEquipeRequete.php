<?php

include('../includes/pdo.php');
include('../includes/utils.php');
include('../includes/regex.php');

if(isset($_POST['sub'])){

	$ok = true;
	$affiche = "";

	if(empty($_POST['annee'])){
		$affiche .= 'Veuillez saisir une année<br />';
		$ok = false;
	}
	if($_POST['direc'] == '0'){
		$affiche .= 'Veuillez sélectionner un directeur<br />';
		$ok = false;
	}


	/*Validité dates*/

	if(!empty($_POST['annee'])){
		if(intval($_POST['annee'] < 1900)){
			$affiche .= 'Pas de dates avant 1900<br />';
			$ok = false;
		}
	}


	if($_POST['direc'] != '0' && $_POST['direc'] == $_POST['co_direc']){
		$affiche .= 'Le co-directeur doit être différent du premier directeur<br />';
		$ok = false;
	}

	if($ok){
		$conn = OuvrirConnexionPDO();


		$equipe = $_POST['equipe'];
		$annee = $_POST['annee'];
		$direc = $_POST['direc'];

		$co_direc = $_POST['co_direc'];
			if($co_direc == '0')
				$co_direc = null;



		$reqDirec = "SELECT * FROM tdf_parti_equipe WHERE annee = :annee and (n_co_directeur = :n OR n_pre_directeur = :n) AND n_equipe != :equipe";
		$curs = preparerRequetePDO($conn, $reqDirec);

		$curs->execute(array(":annee" => $annee, ":n" => $direc, "equipe" => $equipe));
		if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			$affiche .= 'Le directeur est déjà dans une équipe cette année !<br />';
			$ok = false;
		}

		$curs->execute(array(":annee" => $annee, ":n" => $co_direc, "equipe" => $equipe));
		if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			$affiche .= 'Le co-directeur est déjà dans une équipe cette année !<br />';
			$ok = false;
		}



		if($ok){

			$update = "UPDATE tdf_parti_equipe SET annee = :annee, direc = :direc, co_direc = :co_direc WHERE n_equipe = :n";

			$params = array(

				":n" => $equipe,
				":annee" => $annee,
				":direc" => $direc,
				":co_direc" => $co_direc

				);

			$curs = preparerRequetePDO($conn, $update);


			if(majDonneesPrepareesTabPDO($curs, $params)){
				echo '<div class="ok">Modification effectué<br /></div>';
			}
			else{
				echo '<div class="erreur">Échec de la modification<br />';
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
