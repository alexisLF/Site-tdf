


		<?php include('../includes/menu.html');
					include('modifierPartiCoureurRequete.php'); ?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Modification d'une participation d'un coureur</legend>

				<label for="annee">Sélectionnez une année : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select><br />

				<label for="coureur">Sélectionnez un coureur : </label>
					<select name="coureur" id="coureur">
						<?php remplirListeCoureursAnnee(); ?>
					</select><br />

				<label for="equipe">Équipe : </label>
					<select name="equipe" id="equipe">
						<?php remplirListeEquipe(); ?>
					</select><br />

				<label for="valide">Valide : </label>
					<select name="valide" id="valide">
						<option value='O'>Valide</option>
						<option value='R'>Radié</option>
					</select>

				<br /><br />

				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Modifier" name="sub" />

			</fieldset>
		</form>

	<script>

	var remplir = function (e){
		var form = document.getElementById('formu');

		if(document.getElementById('coureur').value != 0){

			var xhr = new XMLHttpRequest();

			var Lire = function()
			{
				if (xhr.readyState === 4 && xhr.status === 200)
			    {
					var tab = xhr.responseText.split('|');
					form.equipe.value = tab[0];
					form.valide.value = tab[1];
				}
			}
			xhr.addEventListener("readystatechange", Lire, false);

			xhr.open('GET', 'recupPartiCoureur.php?n_coureur='+document.getElementById('coureur').value+'&annee='+document.getElementById('annee').value);
			xhr.send();
		}
		else{
			form.equipe.innerHTML="<option value=\"0\">----------------------</option>";

		}
	}


	var debloque = function(e){
		var form = document.getElementById('formu');

		if(document.getElementById('annee').value != 0){


			var xhr = new XMLHttpRequest();

			var Lire = function()
			{
				if (xhr.readyState === 4 && xhr.status === 200)
			    {
					var res = xhr.responseText;
					form.coureur.innerHTML = res;
				}
			}
			xhr.addEventListener("readystatechange", Lire, false);

			xhr.open('GET', 'recupPartiCoureurAnnee.php?annee='+document.getElementById('annee').value);
			xhr.send();


			/*-----------------------------------------------*/


			var xhr2 = new XMLHttpRequest();

			var Lire = function()
			{
				if (xhr2.readyState === 4 && xhr2.status === 200)
			    {
					var res = xhr2.responseText;
					form.equipe.innerHTML = res;
				}
			}
			xhr2.addEventListener("readystatechange", Lire, false);

			xhr2.open('GET', '../partiEquipe/recupPartiEquipeAnnee.php?annee='+document.getElementById('annee').value);
			xhr2.send();
		}
		else{
			form.coureur.innerHTML="<option value=\"0\">----------------------</option>";
		}




	}

	document.getElementById('coureur').addEventListener('change', remplir, false);
	document.getElementById('annee').addEventListener('change', debloque, false);

	</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
