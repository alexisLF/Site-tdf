<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

		<?php include('../includes/menu.html'); ?>

		<a href="javascript:history.back()">Page Précédente</a>

		<h1>Équipes et sponsors du coureur</h1>

		<div id="tableau">

			<?php

			$conn = OuvrirConnexionPDO();

			$req = 'SELECT courNom as "NOM", prenom, annee, to_char(RANG) as "RANG", tdf_sponsor.nom as "NOM SPONSOR", PAYS FROM 
					  (
					    SELECT n_coureur,courNom,prenom,somme, temps_total, valide, annee, n_equipe, n_sponsor, tdf_nation.nom as "PAYS", ROW_NUMBER() OVER(PARTITION BY annee ORDER BY temps_total) as RANG FROM
					    (
					      (select n_coureur,nom as courNom,prenom,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide, annee, n_equipe, n_sponsor from tdf_coureur
					            left join tdf_temps using(n_coureur)
					            left join tdf_temps_difference using(n_coureur,annee)
					            left join tdf_parti_coureur using(n_coureur, annee)
					            where (n_coureur, annee) not in(
					                select n_coureur, annee from tdf_abandon where annee=tdf_abandon.annee
					              )
					              and valide=\'O\'
					              group by (n_coureur,nom,prenom,difference,valide, annee, n_equipe, n_sponsor)
					            )
					            ORDER BY temps_total
					    )
					    JOIN tdf_app_nation USING (n_coureur)
					    JOIN tdf_nation USING (code_cio)
					    ORDER BY n_coureur
					  )
					JOIN tdf_sponsor USING(n_equipe, n_sponsor)
					WHERE n_coureur = :n

					UNION

					SELECT courNom as "NOM", prenom, annee, RANG, tdf_sponsor.nom as "NOM SPONSOR", PAYS FROM 
					  (
					    SELECT n_coureur,courNom,prenom,somme, temps_total, valide, annee, n_equipe, n_sponsor, tdf_nation.nom as "PAYS", \'Abandon\' as RANG FROM
					    (
					      (select n_coureur,nom as courNom,prenom,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide, annee, n_equipe, n_sponsor from tdf_coureur
					            left join tdf_temps using(n_coureur)
					            left join tdf_temps_difference using(n_coureur,annee)
					            left join tdf_parti_coureur using(n_coureur, annee)
					            where (n_coureur, annee) in(
					                select n_coureur, annee from tdf_abandon where annee=tdf_abandon.annee
					              )
					              and valide=\'O\'
					              group by (n_coureur,nom,prenom,difference,valide, annee, n_equipe, n_sponsor)
					            )
					            ORDER BY temps_total
					    )
					    JOIN tdf_app_nation USING (n_coureur)
					    JOIN tdf_nation USING (code_cio)
					    ORDER BY n_coureur
					  )
					JOIN tdf_sponsor USING(n_equipe, n_sponsor)
					WHERE n_coureur = :n';

			$params = array(

				":n" => $_GET['n_coureur']

				);

			$curs = preparerRequetePDO($conn, $req);

			executeReqTabPrepare($curs, $params);

			?>


		</div>

	</body>
</html>


