<?php

function verifSponsor($nom, $na_sponsor, $code_cio){

	$conn = OuvrirConnexionPDO();

	$req = "SELECT nom, na_sponsor, code_cio from 
			(
			select n_equipe, max(n_sponsor) as n_sponsor
			from tdf_sponsor
			group by n_equipe
			)
			join tdf_sponsor using(n_equipe,n_sponsor)
			join tdf_equipe using(n_equipe)
			where annee_disparition is null
			order by n_equipe";

	$curs = $conn->query($req);
	$verifie = true;
		
	while($ligne = $curs->fetch(PDO::FETCH_ASSOC)){
		
			if ($ligne['NOM'] == $nom && $ligne['NA_SPONSOR'] == $na_sponsor && $ligne['CODE_CIO'] == $code_cio) {
				$verifie = false;
			}
		}

	return $verifie;
}

?>