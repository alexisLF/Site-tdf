<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>


		<?php include('../includes/menu.html'); ?>

		<a href="javascript:history.back()">Page Précédente</a>


		<form method="POST" action="#">

			<fieldset>
				<legend>Choisir l'année d'un tour</legend>

				<label for="annee">Année du Tour : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select>
			</fieldset>
		</form>

		<div id="liens">


		</div>

		<script>

		document.getElementById('annee').addEventListener('change', afficheLiens, true);

		if(document.getElementById('annee').value != 0)
			afficheLiens();

		function afficheLiens(e){

			if(document.getElementById('annee').value != 0){
				var texte = "<a href='recupEtapes.php?annee="+document.getElementById('annee').value+"'>Liste des étapes de "+document.getElementById('annee').value+"</a><br />";
				texte += "<a href='recupClassementGeneral.php?annee="+document.getElementById('annee').value+"'>Classement général de "+document.getElementById('annee').value+"</a><br />";
				texte += "<a href='recupParticipations.php?annee="+document.getElementById('annee').value+"'>Participations de "+document.getElementById('annee').value+"</a><br />";
				document.getElementById('liens').innerHTML = texte;
			}
			else
				document.getElementById('liens').innerHTML = "";

		}


		</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
