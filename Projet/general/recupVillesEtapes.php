<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>
		

		<?php include('../includes/menu.html'); ?>

		<a href="javascript:history.back()">Page Précédente</a>

		<h1>Villes étapes du Tour de France</h1>


		<?php

			$conn = OuvrirConnexionPDO();

			$req ='SELECT * FROM(SELECT count(annee) as "NB ANNÉES DÉPART", ville_d as "VILLE DÉPART" FROM tdf_etape
			GROUP BY ville_d)
			ORDER BY "VILLE DÉPART"';



			$curs = preparerRequetePDO($conn, $req);
		

			

			$req ='SELECT * FROM (SELECT  count(annee) as "NB ANNÉES ARRIVEÉ", ville_a as "VILLE D\'ARRIVÉE" FROM tdf_etape
			GROUP BY ville_a)
			ORDER BY "VILLE D\'ARRIVÉE"';


		

			$curs2 = preparerRequetePDO($conn, $req);
		

			echo "<table><tr>";

			echo "<td>";
			executeReqTab($curs);
			echo "</td>";

			echo "<td>";
			executeReqTab($curs2);
			echo "</td>";

			echo "</tr></table>";

		?>

		

	</body>
</html>