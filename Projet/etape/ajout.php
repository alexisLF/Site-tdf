<?php


	include('../includes/regex.php');

	if(isset($_POST["sub"])){

		$ok = true;
		$affiche = "";

		if(empty($_POST['n_epreuve'])){
			$affiche .= 'Veuillez saisir un numéro d\'épreuve<br />';
			$ok = false;
		}
		if($_POST['n_epreuve'] < 0){
			$affiche .= 'Numéro d\'épreuve invalide<br />';
			$ok = false;
		}

		if(empty($_POST['ville_d'])){
			$affiche .= 'Veuillez saisir une ville de départ<br />';
			$ok = false;
		}
		if(empty($_POST['ville_a'])){
			$affiche .= 'Veuillez saisir une ville d\'arrivée<br />';
			$ok = false;
		}

		if($_POST['pays_d'] == '0'){
			$affiche .= 'Veuillez sélectionner un pays de départ<br />';
			$ok = false;
		}
		if($_POST['pays_a'] == '0'){
			$affiche .= 'Veuillez sélectionner un pays d\'arrivée<br />';
			$ok = false;
		}

		if(empty($_POST['annee'])){
			$affiche .= 'Veuillez saisir une année<br />';
			$ok = false;
		}

		if(empty($_POST['jour'])){
			$affiche .= 'Veuillez saisir un jour<br />';
			$ok = false;
		}

		if(empty($_POST['mois'])){
			$affiche .= 'Veuillez saisir un mois<br />';
			$ok = false;
		}

		if($_POST['cat_code'] == '0'){
			$affiche .= 'Veuillez sélectionner un type d\'épreuve<br />';
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


			$ville_d = $_POST['ville_d'];
			$ville_d = verificationNomVille($ville_d);

			
			

			$ville_a = $_POST['ville_a'];
			$ville_a = verificationNomVille($ville_a);

			$ok = true;

			if($ville_d === 0){
				$ok = false;
				$affiche .= 'La ville de départ est invalide<br />';
			}
			if($ville_a === 0){
				$ok = false;
				$affiche .= 'La ville d\'arrivée est invalide<br />';
			}


			$jour = $_POST['jour'];
			$mois = $_POST['mois'];

			if($jour < 1 || $jour > 31){
				$ok = false;
				$affiche .= 'Le jour est invalide<br />';
			}

			if($mois < 1 || $mois > 12){
				$ok = false;
				$affiche .= 'Le mois est invalide<br />';
			}

			if($mois < 10 && strlen($mois) < 2)
				$mois = '0'.$mois;


			if($ok){
				$n_epreuve = $_POST['n_epreuve'];
				$annee = $_POST['annee'];


				$reqNumero = "SELECT n_epreuve FROM tdf_etape WHERE annee = :annee AND n_epreuve = :n_epreuve";
				$params = array(":n_epreuve" => $n_epreuve, ":annee" => $annee);

				$curs = preparerRequetePDO($conn, $reqNumero);
				$curs->execute($params);
				if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
					$affiche .= 'Il existe déjà une étape ayant ce numéro !<br />';
					$ok = false;
				}

				$reqJour = "SELECT jour FROM tdf_etape WHERE jour = :jour";
				$params = array(":jour" => $jour);

				$curs = preparerRequetePDO($conn, $reqJour);
				$curs->execute($params);
				if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
					$affiche .= 'Il existe déjà une étape durant ce jour !<br />';
					$ok = false;
				}



				if($ok){
				
					$pays_d = $_POST['pays_d'];
					$pays_a = $_POST['pays_a'];
					$distance = $_POST['distance'];
					$cat_code = $_POST['cat_code'];
					

					$moyenne = null;
					if(!empty($_POST['moyenne'])){
						$moyenne = $_POST['moyenne'];
					}

					$anneeJour = substr($annee, -2);
					$jour = $jour.'/'.$mois.'/'.$anneeJour;

					$insert = "INSERT INTO tdf_etape(n_epreuve, annee, ville_d, ville_a, code_cio_d, code_cio_a, distance, moyenne, cat_code, jour) VALUES(:n_epreuve, :annee, :ville_d, :ville_a, :code_cio_d, :code_cio_a, :distance, :moyenne, :cat_code, to_date(:jour, 'DD/MM/YY'))";

					$params = array(

						":n_epreuve" => $n_epreuve,
						":annee" => $annee,
						":ville_d" => $ville_d,
						":ville_a" => $ville_a,
						":code_cio_d" => $pays_d,
						":code_cio_a" => $pays_a,
						":distance" => $distance,
						":moyenne" => $moyenne,
						":cat_code" => $cat_code,
						":jour" => $jour

						);

					$curs = preparerRequetePDO($conn, $insert);




					if(majDonneesPrepareesTabPDO($curs, $params)){
						echo '<div class="ok">Ajout effectué<br /></div>';

					}
					else{
						echo '<div class="erreur">Échec de l\'ajout<br /></div>';
					}
				}

			}
			
		}
		else{
			echo '<div class="erreur">';
			echo $affiche.'</div>';
		}


	}

?>