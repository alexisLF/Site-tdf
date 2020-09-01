<?php

include('../includes/regex.php');
include('veri.php');

if(isset($_POST["sub"])){

		$ok = true;
		$affiche = "";

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

		if($ok){

			$conn = OuvrirConnexionPDO();

			$nom = $_POST['nom'];
			$nom = suppresionAccentNom($nom);
			$nom = strtoupper($nom);

			$n_equipe = $_POST['n_equipe'];

			$max = "SELECT max(n_sponsor)+1 as n_sponsor from tdf_sponsor where n_equipe = '$n_equipe'";
			$curs = $conn->query($max);
			$ligne = $curs->fetch(PDO::FETCH_ASSOC);
			$n_sponsor = $ligne['N_SPONSOR'];



			$annee = $_POST['annee'];
			$diminutif = $_POST['dimi'];

			$pays = $_POST['pays'];


			if (verifSponsor($nom, $diminutif, $pays)){
				$insert = "INSERT INTO tdf_sponsor(N_EQUIPE, N_SPONSOR, NOM, NA_SPONSOR, CODE_CIO, ANNEE_SPONSOR, DATE_INSERT) values (:n_equipe, :n_sponsor, :nom, :diminutif, :pays, :annee, sysdate)";


				$params = array(

					":n_equipe" => $n_equipe,
					":n_sponsor" => $n_sponsor,
					":nom" => $nom,
					":diminutif" => $diminutif,
					":pays" => $pays,
					":annee" => $annee

					);

				$curs = preparerRequetePDO($conn, $insert);


				if(majDonneesPrepareesTabPDO($curs, $params)){
					echo '<div class="ok">Ajout effectué<br /></div>';
				}
				else{
				echo '<div class="erreur">Échec de l\'ajout<br /></div>';
				}
			}else{
				echo '<div class="erreur">Déjà présent<br /></div>';
			}


		} else {
				echo '<div class="erreur">'.$affiche.'</div>';
		}


	}




?>
