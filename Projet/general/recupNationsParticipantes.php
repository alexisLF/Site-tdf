<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>


		<?php include('../includes/menu.html'); ?>

		<a href="javascript:history.back()">Page Précédente</a>

		<h1>Nations participantes du Tour de France</h1>

		<?php

			$conn = OuvrirConnexionPDO();

			$req ='SELECT count(distinct annee) as "NB ANNÉES", nom as "PAYS" FROM tdf_parti_coureur
					JOIN tdf_app_nation USING(n_coureur)
					JOIN tdf_nation USING(CODE_CIO)
					GROUP BY nom';



			$curs = preparerRequetePDO($conn, $req);
		
			executeReqTab($curs);

		?>

		

	</body>
</html>