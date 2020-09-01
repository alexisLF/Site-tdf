<?php


	if(isset($_POST["sub"])){

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



			/*--------------------------------------------*/

			$reqCoureur = "SELECT n_coureur FROM tdf_parti_coureur WHERE annee = :annee and n_coureur = :n";
			$curs = preparerRequetePDO($conn, $reqCoureur);

			$curs->execute(array(":annee" => $annee, ":n" => $coureur));
			if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
				$affiche .= 'Le coureur a déjà participé à ce Tour !<br />';
				$ok = false;
			}




			if($ok){

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


				if($ok){
					/*--------------------------------------------*/
					$reqSponsor = "SELECT max(n_sponsor) as N_SPONSOR FROM tdf_sponsor WHERE n_equipe = :n";
					$params = array(":n" => $equipe);

					$curs = preparerRequetePDO($conn, $reqSponsor);

					majDonneesPrepareesTabPDO($curs, $params);

					$ligne = $curs->fetch(PDO::FETCH_ASSOC);
					$sponsor = $ligne['N_SPONSOR'];



					/*--------------------------------------------*/
					$reqJeune = "SELECT pc.annee - c.annee_naissance as jeune FROM tdf_parti_coureur pc
								JOIN tdf_coureur c USING(n_coureur)
								WHERE annee = :annee AND n_coureur = :n";

					$params = array(":annee" => $annee, ":n" => $coureur);

					$curs = preparerRequetePDO($conn, $reqJeune);

					majDonneesPrepareesTabPDO($curs, $params);

					$ligne = $curs->fetch(PDO::FETCH_ASSOC);
					$jeune = $ligne['JEUNE'];

					if($jeune <= 25)
						$jeune = 'o';
					else
						$jeune = null;


					/*--------------------------------------------*/
					$insert = "INSERT INTO tdf_parti_coureur(n_equipe, n_sponsor, annee, n_coureur, valide, jeune, n_dossard, date_insert) VALUES(:equipe, :sponsor, :annee, :coureur, :valide, :jeune, :dossard, sysdate)";

					$params = array(

						":equipe" => $equipe,
						":sponsor" => $sponsor,
						":annee" => $annee,
						":coureur" => $coureur,
						":valide" => $valide,
						":jeune" => $jeune,
						":dossard" => $dossard

					);

					$curs = preparerRequetePDO($conn, $insert);



					if(majDonneesPrepareesTabPDO($curs, $params)){
							echo '<div class="ok">Ajout effectué<br /></div>';
					}
					else{
						echo '<div class="erreur">Échec de l\'ajout<br /></div>';
					}
				}
				else{
					echo '<div class="erreur">'.$affiche.'</div>';
				}
			}else{
				echo '<div class="erreur">'.$affiche.'</div>';
			}


		}
		else{
			echo '<div class="erreur">'.$affiche.'</div>';
		}



	}

?>
