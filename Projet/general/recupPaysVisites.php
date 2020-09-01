<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

		<?php include('../includes/menu.html'); ?>

		<a href="javascript:history.back()">Page Précédente</a>

		<h1>Pays visités du Tour de France</h1>

		<?php

			$conn = OuvrirConnexionPDO();

			$req ='SELECT count(distinct CONCAT(annee_d, annee_a)) as "NB ANNÉES", nom as "PAYS" FROM(         
					  SELECT to_char(annee) as annee_d, nom, \'\' as annee_a FROM tdf_etape et
					  JOIN tdf_nation na ON na.CODE_CIO = et.CODE_CIO_D
					  
					  UNION
					  
					  SELECT \'\' as annee_d, nom, to_char(annee) as annee_a FROM tdf_etape et
					  JOIN tdf_nation na ON na.CODE_CIO = et.CODE_CIO_A
					)
					GROUP BY nom';



			$curs = preparerRequetePDO($conn, $req);
		
			executeReqTab($curs);

		?>

		

	</body>
</html>