<?php

	include('../includes/regex.php');

	if(isset($_POST["sub"])){

		$ok = true;
		$affiche ="";

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

			$max = "SELECT max(n_coureur)+1 as n_coureur FROM tdf_coureur";

			$curs = $conn->query($max);
			$ligne = $curs->fetch(PDO::FETCH_ASSOC);
			$num = $ligne['N_COUREUR'];


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

			$reqNom = "SELECT n_coureur FROM tdf_coureur WHERE nom = :nom AND prenom = :prenom";
			$params = array(":nom" => $nom, ":prenom" => $prenom);

			$curs = preparerRequetePDO($conn, $reqNom);
			$curs->execute($params);
			if($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
				$affiche .= 'Il existe déjà un coureur nommé '.$prenom.' '.$nom.'<br />';
				$ok = false;
			}


			if($ok){
				$naiss = $_POST['naiss'];
				$prem = $_POST['prem'];
				$paysNaissance = $_POST['paysNaissance'];

				$insert = "INSERT INTO tdf_coureur(n_coureur, nom, prenom, annee_naissance, annee_prem, date_insert) VALUES(:num, :nom, :prenom, :naiss, :prem, sysdate)";
				$insertNation = "INSERT INTO tdf_app_nation(n_coureur, code_cio, annee_debut, annee_fin, date_insert) VALUES(:num, :code, :deb, :fin, sysdate)";

				$params = array(

					":num" => $num,
					":nom" => $nom,
					":prenom" => $prenom,
					":naiss" => $naiss,
					":prem" => $prem

					);

				$curs = preparerRequetePDO($conn, $insert);

				if($_POST['paysActuel'] != $paysNaissance && $_POST['paysActuel'] != '0')
					$dateFinNaissance = $_POST['datePaysActuel'];
				else
					$dateFinNaissance = '';


				$paramsNation = array(

					":num" => $num,
					":code" => $paysNaissance,
					":deb" => $naiss,
					":fin" => $dateFinNaissance

					);

				$cursNation = preparerRequetePDO($conn, $insertNation);



				if(majDonneesPrepareesTabPDO($curs, $params) && majDonneesPrepareesTabPDO($cursNation, $paramsNation)){

					if($_POST['paysActuel'] != $paysNaissance && $_POST['paysActuel'] != '0'){


						$paysActuel = $_POST['paysActuel'];

						$paramsNation = array(

						":num" => $num,
						":code" => $paysActuel,
						":deb" => $dateFinNaissance,
						":fin" => ""

						);


						if(majDonneesPrepareesTabPDO($cursNation, $paramsNation)){
							echo '<div class="ok">Ajout effectué<br /></div>';
						}
						else{
							echo '<div class="erreur">Échec de l\'ajout<br /></div>';
						}
					}
					else{
						echo '<div class="ok">Ajout effectué<br /></div>';
					}

				}
				else{
					echo '<div class="erreur">Échec de l\'ajout<br /></div>';
				}

			}
			else{
				echo '<div class="erreur">'.$affiche.'</div>';
			}


		} else {
				echo '<div class="erreur">'.$affiche.'</div>';
		}


	}

?>
