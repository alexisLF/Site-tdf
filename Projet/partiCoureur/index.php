<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>


		<?php include('../includes/menu.html'); include('ajout.php'); ?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Ajout d'une participation d'un coureur'</legend>

				<label for="coureur">Coureur : </label>
				<select name="coureur" id="coureur">
						<?php remplirListeCoureurs(); ?>
					</select><br />


				<label for="annee">Année du Tour : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select><br />


				<label for="equipe">Équipe : </label>
				<select name="equipe" id="equipe">
						<option value='0'>----------------------</option>
					</select><br />


				<label for="valide">Participation valide : </label>
					<select name="valide" id="valide">
						<option value='O'>Valide</option>
						<option value='R'>Radié</option>
					</select><br />

				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Ajouter" name="sub" />

			</fieldset>
		</form>

		<script>

		function actu (e){
			var form = document.getElementById('formu');

			if(document.getElementById('annee').value != 0){

				var xhr = new XMLHttpRequest();

				var Lire = function()
				{
					if (xhr.readyState === 4 && xhr.status === 200)
				    {
						var res = xhr.responseText;
						form.equipe.innerHTML = res;
					}
				}
				xhr.addEventListener("readystatechange", Lire, false);

				xhr.open('GET', '../partiEquipe/recupPartiEquipeAnnee.php?annee='+document.getElementById('annee').value);
				xhr.send();
			}
			else{
				form.equipe.innerHTML = "<option value='0'>----------------------</option>";
			}
		}

		document.getElementById('annee').addEventListener('change', actu, false);

		window.onLoad = actu();

		</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
