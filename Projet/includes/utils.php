<?php

function verifierRempli($n)
{  
	if (isset($_POST[$n]))
	{
	  $var = $_POST[$n];
	  if ($var <> "")
		echo $var; 
	}
	else 
	  echo ""; 
}

function remplirListeCoureurs(){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT n_coureur, nom, prenom FROM tdf_coureur ORDER BY nom, prenom";

	$curs = $conn->query($req);

	if($curs){
		echo "<option value=\"0\">----------------------</option>";

		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='$ligne[N_COUREUR]'>$ligne[NOM] $ligne[PRENOM]</option>";
		}

	}
	else{
		echo "<option value='-1'>Erreur</option>";
	}


}

function remplirListeCoureursAnnee($annee){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT n_coureur, nom, prenom FROM tdf_parti_coureur
			JOIN tdf_coureur USING(n_coureur)
			WHERE annee = :annee
			ORDER BY nom, prenom";

	$params = array(

		":annee" => $annee

	);

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		echo "<option value=\"0\">----------------------</option>";

		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='$ligne[N_COUREUR]'>$ligne[NOM] $ligne[PRENOM]</option>";
		}

	}
	else{
		echo "<option value='-1'>Erreur</option>";
	}


}

function remplirListeEtapesAnnee($annee){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT n_epreuve, ville_d, ville_a FROM tdf_etape
			WHERE annee = :annee
			ORDER BY n_epreuve";

	$params = array(

		":annee" => $annee

	);

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		echo "<option value=\"-1\">----------------------</option>";
		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){

			$num = $ligne['N_EPREUVE'];
			$val = $ligne['VILLE_D'].' - '.$ligne['VILLE_A'];

			echo "<option value='$num'>$num : $val</option>";
		}
	}
	else{
		echo "<option value='-1'>Erreur</option>";
	}


}

function remplirListePays(){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT CODE_CIO, NOM FROM tdf_nation ORDER BY NOM";

	$curs = $conn->query($req);

	if($curs){
		echo "<option value='0'>----------------------</option>";

		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='$ligne[CODE_CIO]'>$ligne[NOM]</option>";
		}

	}
	else{
		echo "<option value='-1'>Erreur</option>";
	}


}

function remplirListeDirecteurs(){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT n_directeur, nom, prenom FROM tdf_directeur ORDER BY nom, prenom";

	$curs = $conn->query($req);

	if($curs){
		echo "<option value='0'>----------------------</option>";

		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='$ligne[N_DIRECTEUR]'>$ligne[NOM] $ligne[PRENOM]</option>";
		}

	}
	else{
		echo "<option value='-1'>Erreur</option>";
	}


}

function remplirListeEquipe(){

	$conn = OuvrirConnexionPDO();

	$req = "select N_EQUIPE, NOM from 
			( select n_equipe, count (*) as nb_sponsors, max(n_sponsor) as n_sponsor 
			from tdf_sponsor group by n_equipe )
			join tdf_sponsor using(n_equipe,n_sponsor)
			order by nom ";

	$curs = $conn->query($req);

	if($curs){
		echo "<option value='0'>----------------------</option>";

		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='$ligne[N_EQUIPE]'>$ligne[NOM]</option>";
		}

	}
	else{
		echo "<option value='1'>Erreur</option>";
	}


}

function remplirListeEquipesParticipantes($annee){

	$conn = OuvrirConnexionPDO();
	
	$req = "SELECT n_equipe, max(n_sponsor) as spons, nom
			FROM tdf_parti_equipe eq
			JOIN tdf_sponsor USING(n_equipe, n_sponsor)
			WHERE annee = :annee
            GROUP BY n_equipe, nom
            ORDER BY nom";

	$params = array(

		":annee" => $annee

	);

	$res = "";

	$curs = preparerRequetePDO($conn, $req);

	if(majDonneesPrepareesTabPDO($curs, $params)){
		echo "<option value=\"0\">----------------------</option>";
		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){

			$num = $ligne['N_EQUIPE'];
			$nom = $ligne['NOM'];

			$res .= "<option value='$num'>$nom</option>";
		}
	}

	echo $res;
}


function remplirAnnees(){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT annee FROM tdf_annee ORDER BY annee desc";

	$curs = $conn->query($req);

	if($curs){
		echo "<option value='0'>----------------------</option>";

		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='$ligne[ANNEE]'>$ligne[ANNEE]</option>";
		}

	}
	else{
		echo "<option value='-1'>Erreur</option>";
	}


}


function remplirTypesEtapes(){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT distinct cat_code FROM tdf_etape ORDER BY cat_code";

	$curs = $conn->query($req);

	if($curs){
		echo "<option value='0'>----------------------</option>";

		while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='$ligne[CAT_CODE]'>$ligne[CAT_CODE]</option>";
		}

	}
	else{
		echo "<option value='-1'>Erreur</option>";
	}


}

function executeReqTabPrepare($curs, $params){
	if(majDonneesPrepareesTabPDO($curs, $params)){

		afficherResultatRequeteTableau($curs);

	}
	else{
		echo "Erreur<br />"; 
	}
}


function executeReqTab($curs){
	if(majDonneesPrepareesPDO($curs)){

		afficherResultatRequeteTableau($curs);

	}
	else{
		echo "Erreur<br />"; 
	}
}

function afficherResultatRequeteTableau($curs){
	echo "<table border='2'>";

	$cols = false;


	while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
							

		if(!$cols){
								
			echo "<tr>";

			foreach($ligne as $cle=>$val)
				echo'<td><b>'.$cle.'</b></td>';

			$cols = true;
			echo "</tr>";
		}

		echo "<tr>";



		foreach($ligne as $cle=>$val){
			echo "<td>$val</td>";
		}


		echo "</tr>";

	}

	echo "</table>";
}

?>