<?php

include('../includes/pdo.php');
include('../includes/utils.php');
include('../includes/regex.php');
include('veri.php');

if(isset($_POST["sub"])){

		$ok = true;
		$affiche ="";

		if(empty($_POST['creation'])){
			$affiche .= 'Veuillez saisir une année de création<br />';
			$ok = false;
		}
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

		if(!empty($_POST['creation'])){
			if(intval($_POST['creation'] < 1900)){
				$affiche .= 'Pas de dates avant 1900<br />';
				$ok = false;
			}
		}

		if($ok){

			$conn = OuvrirConnexionPDO();

			$nom = $_POST['nom'];
			$nom = suppresionAccentNom($nom);
			$nom = strtoupper($nom);


			$num = "SELECT max(n_equipe)+1 as n_equipe from tdf_equipe";
			$curs = $conn->query($num);
			$ligne = $curs->fetch(PDO::FETCH_ASSOC);
			$n_equipe = $ligne['N_EQUIPE'];
			$annee_creation = $_POST['creation'];



			$annee = $_POST['creation'];
			$diminutif = $_POST['dimi'];
			$pays = $_POST['pays'];


			$insert_equipe = "INSERT INTO tdf_equipe(N_EQUIPE, ANNEE_CREATION) values (:n_equipe, :annee_creation)";


			$param_equipe = array(

				":n_equipe" => $n_equipe,
				":annee_creation" => $annee_creation

				);

			$curs = preparerRequetePDO($conn, $insert_equipe);


			if(majDonneesPrepareesTabPDO($curs, $param_equipe)){
				echo '<div class="ok">Ajout équipe effectué<br /></div>';

				if (verifSponsor($nom, $diminutif, $pays)) {
					$insert_sponsor = "INSERT INTO tdf_sponsor(N_EQUIPE, N_SPONSOR, NOM, NA_SPONSOR, CODE_CIO, ANNEE_SPONSOR, DATE_INSERT) values (:n_equipe, 1, :nom, :diminutif, :pays, :annee, sysdate)";

					$param_sponsor = array(

						":n_equipe" => $n_equipe,

						":nom" => $nom,
						":diminutif" => $diminutif,
						":pays" => $pays,
						":annee" => $annee

						);

					$curs = preparerRequetePDO($conn, $insert_sponsor);
					if(majDonneesPrepareesTabPDO($curs, $param_sponsor)){
						echo '<div class="ok">Ajout sponsor effectué<br /></div>';
					}
					else{
						echo '<div class="erreur">Échec de l\'ajout sponsor<br /></div>';
					}
				}else{
					echo '<div class="erreur">Le sponsor est déjà présent<br /></div>';
				}
			}
			else{
				echo '<div class="erreur">Échec de l\'ajout equipe<br /></div>';
			}



		} else {
				echo '<div class="erreur">'.$affiche.'</div>';
		}


	}



?>
