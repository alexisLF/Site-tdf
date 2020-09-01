<?php

include('../includes/pdo.php');
include('../includes/utils.php');
include('../includes/regex.php');

if(isset($_POST['sub'])){

	$ok = true;
	$affiche = "";

		if($_POST['annee'] == '0'){
			$affiche .= 'Veuillez saisir une année<br />';
			$ok = false;
		}
		if($_POST['equipe'] == '0'){
			$affiche .= 'Veuillez sélectionner une équipe<br />';
			$ok = false;
		}
		if($_POST['coureur'] == '0'){
			$affiche .= 'Veuillez sélectionner un coureur<br />';
			$ok = false;
		}


		/*Validité dates*/

		if(!empty($_POST['annee'])){
			if(intval($_POST['annee'] < 1900)){
				$affiche .= 'Pas de dates avant 1900<br />';
				$ok = false;
			}
		}


		if($ok){

			$conn = OuvrirConnexionPDO();

			$equipe = $_POST['equipe'];
			$annee = $_POST['annee'];
			$coureur = $_POST['coureur'];
			$valide = $_POST['valide'];
			$dossard = 0;



			/*Pas de changement de dossard si c'est la même équipe*/

			$reqMemeEquipe = "SELECT n_equipe, n_coureur, n_dossard FROM tdf_parti_coureur WHERE annee = :annee and n_coureur = :n and n_equipe = :equipe";
			$curs = preparerRequetePDO($conn, $reqMemeEquipe);

			$curs->execute(array(":annee" => $annee, ":n" => $coureur, ":equipe" => $equipe));
			if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
				$dossard = $ligne['N_DOSSARD'];
			}


			if($dossard == 0){


				$reqDossard = "SELECT nvl(max(n_dossard),0)+1 as dossard FROM tdf_parti_coureur WHERE annee = :annee AND n_equipe = :n ORDER BY n_dossard";
				$params = array("annee" => $annee, ":n" => $equipe);

				$curs = preparerRequetePDO($conn, $reqDossard);

				majDonneesPrepareesTabPDO($curs, $params);

				$ligne = $curs->fetch(PDO::FETCH_ASSOC);
				$dossard = $ligne['DOSSARD'];

				if($dossard % 10 == 0){
					$ok = false;
					$affiche .= 'L\'équipe est déjà pleine !<br />';
				}
				else if($dossard % 10 == 1){
					$reqDossard = "SELECT (ceil(nvl(max(n_dossard),0)/10))*10+1 as dossard FROM tdf_parti_coureur WHERE annee = :annee";
					$params = array(":annee" => $annee);

					$curs = preparerRequetePDO($conn, $reqDossard);

					majDonneesPrepareesTabPDO($curs, $params);

					$ligne = $curs->fetch(PDO::FETCH_ASSOC);
					$dossard = $ligne['DOSSARD'];
				}

			}


			if($ok){
				/*--------------------------------------------*/
				$reqSponsor = "SELECT max(n_sponsor) as N_SPONSOR FROM tdf_sponsor WHERE n_equipe = :n";
				$params = array(":n" => $equipe);

				$curs = preparerRequetePDO($conn, $reqSponsor);

				majDonneesPrepareesTabPDO($curs, $params);

				$ligne = $curs->fetch(PDO::FETCH_ASSOC);
				$sponsor = $ligne['N_SPONSOR'];



				/*--------------------------------------------*/
				$update = "UPDATE tdf_parti_coureur SET n_equipe = :equipe, n_sponsor = :sponsor, valide = :valide, n_dossard = :dossard WHERE n_coureur = :coureur";

				$params = array(

					":equipe" => $equipe,
					":sponsor" => $sponsor,
					":valide" => $valide,
					":dossard" => $dossard,
					":coureur" => $coureur

				);

				$curs = preparerRequetePDO($conn, $update);



				if(majDonneesPrepareesTabPDO($curs, $params)){
					echo '<div class="ok">Modification effectuée<br /></div>';
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
