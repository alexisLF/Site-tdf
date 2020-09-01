<?php



	if(isset($_POST["sub"])){

		$ok = true;
		$affiche = "";

		if(empty($_POST['annee'])){
			$affiche .= 'Veuillez saisir une année<br />';
			$ok = false;
		}
		if($_POST['equipe'] == '0'){
			$affiche .= 'Veuillez sélectionner une équipe<br />';
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




			$reqDirec = "SELECT * FROM tdf_parti_equipe WHERE annee = :annee and (n_co_directeur = :n OR n_pre_directeur = :n)";
			$curs = preparerRequetePDO($conn, $reqDirec);

			$curs->execute(array(":annee" => $annee, ":n" => $direc));
			if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
				$affiche .= 'Le directeur est déjà dans une équipe cette année !<br />';
				$ok = false;
			}

			$curs->execute(array(":annee" => $annee, ":n" => $co_direc));
			if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
				$affiche .= 'Le co-directeur est déjà dans une équipe cette année !<br />';
				$ok = false;
			}




			if($ok){
				$reqSponsor = "SELECT max(n_sponsor) as N_SPONSOR FROM tdf_sponsor WHERE n_equipe = :n";
				$params = array(":n" => $equipe);

				$curs = preparerRequetePDO($conn, $reqSponsor);

				majDonneesPrepareesTabPDO($curs, $params);

				$ligne = $curs->fetch(PDO::FETCH_ASSOC);
				$sponsor = $ligne['N_SPONSOR'];


				$insert = "INSERT INTO tdf_parti_equipe(n_equipe, n_sponsor, annee, n_pre_directeur, n_co_directeur, date_insert) VALUES(:equipe, :sponsor, :annee, :direc, :codirec, sysdate)";

				$params = array(

					":equipe" => $equipe,
					":sponsor" => $sponsor,
					":annee" => $annee,
					":direc" => $direc,
					":codirec" => $co_direc

					);

				$curs = preparerRequetePDO($conn, $insert);



				if(majDonneesPrepareesTabPDO($curs, $params)){
				echo '<div class="ok">Ajout effectué<br /></div>';
				}
				else{
					echo '<div class="erreur">Échec de l\'ajout<br /></div>';
				}
			}else{
				echo '<div class="erreur">'.$affiche.'</div>';
			}


		}else{
			echo '<div class="erreur">'.$affiche.'</div>';
		}



	}

?>
