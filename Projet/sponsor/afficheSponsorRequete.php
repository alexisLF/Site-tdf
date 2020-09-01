
<?php



			if(isset($_POST['coureurs'])){

				$n_equipe = $_POST['n_equipe'];
				$annee = $_POST['annee'];
				$conn = OuvrirConnexionPDO();

				$req = "SELECT n_coureur, n_dossard, nom, prenom FROM tdf_coureur
						JOIN tdf_parti_coureur USING (n_coureur)
						WHERE n_equipe = :n_equipe AND annee = :annee
						order by n_dossard";

				$params = array(

				":n_equipe" => $n_equipe,
				":annee" => $annee

				);

				$curs = preparerRequetePDO($conn, $req);

				$curs->execute($params);


				if(LireDonneesPDOPreparee($curs, $tab)){

					$bool = true;

						echo '<table border="2">';
						echo '<tr>';
						foreach($tab as $cle =>$valeur){
						   if (is_array($valeur)) {

							   	if ($bool) {
							   		foreach($valeur as $key=>$value){
							   			if($key != "N_COUREUR")
											echo "<td><b>$key </b></td>";

									}
										$bool = false;
								}

								echo "</tr>";
								echo '<tr>';

						        foreach ($valeur as $key=>$value) {
						        	if($key != "N_COUREUR"){
							         	echo "<td>$value</td>";
							        }
						        }

						        echo "<td><a href='../coureur/afficheDetailsCoureur.php?n_coureur=$valeur[N_COUREUR]'>Voir les détails</a></td>";

							}
						}
						echo "</tr>";
						echo '</table>';

				}
				else{
					echo "Aucun résultat";

				}

			}

		?>
