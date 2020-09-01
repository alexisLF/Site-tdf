<?php

include('../includes/pdo.php');
include('../includes/utils.php');
include('../includes/regex.php');
include('veri.php');

if(isset($_POST["sub"])){

		$ok = true;
		$affiche = "";

		if($_POST['n_equipe'] == '0'){
			$affiche .= 'Veuillez sélectionner une équipe<br />';
			$ok = false;
		}
		else{
			if(empty($_POST['nom'])){
				$affiche .= 'Veuillez saisir un nom<br />';
				$ok = false;
			}
			if(empty($_POST['dimi'])){
				$affiche .= 'Veuillez saisir un diminutif<br />';
				$ok = false;
			}
			if($_POST['pays'] == '0'){
				$affiche .= 'Veuillez sélectionner un pays<br />';
				$ok = false;
			}
			if($_POST['n_equipe'] == '0'){
				$affiche .= 'Veuillez sélectionner un sponsor<br />';
				$ok = false;
			}
		}

		if($ok){

			$conn = OuvrirConnexionPDO();

			$nom = $_POST['nom'];
			$nom = suppresionAccentNom($nom);
			$nom = strtoupper($nom);



			$n_equipe = $_POST['n_equipe'];


			$max = "SELECT max(n_sponsor) as n_sponsor from tdf_sponsor where n_equipe = :n";
			$curs = preparerRequetePDO($conn, $max);

			$curs->execute(array(":n" => $n_equipe));
			$ligne = $curs->fetch(PDO::FETCH_ASSOC);
			$n_sponsor = $ligne['N_SPONSOR'];





			$annee = $_POST['annee'];
			$diminutif = $_POST['dimi'];
			$pays = $_POST['pays'];


			if (verifSponsor($nom, $diminutif, $pays)){
				$update = "UPDATE tdf_sponsor SET nom = :nom, na_sponsor = :diminutif, code_cio = :pays, annee_sponsor = :annee WHERE n_equipe = :n_equipe AND n_sponsor = :n_sponsor";

				$params = array(

					":n_equipe" => $n_equipe,
					":n_sponsor" => $n_sponsor,
					":nom" => $nom,
					":diminutif" => $diminutif,
					":pays" => $pays,
					":annee" => $annee

					);

				$curs = preparerRequetePDO($conn, $update);


				if(majDonneesPrepareesTabPDO($curs, $params)){
					echo '<div class="ok">Modification effectuée<br /></div>';
				}
				else{
					echo '<div class="erreur">Échec de la modification<br /></div>';
				}
			}else{
					echo "<div class='erreur'>Sponsor déjà présent</div>";
			}


		}
		else{
			echo '<div class="erreur">'.$affiche.'</div>';
		}


	}


?>
