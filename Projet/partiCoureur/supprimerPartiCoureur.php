

		<?php include('../includes/menu.html');
					include('supprimerPartiCoureurRequete.php'); ?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Suppresion d'une participation d'un coureur</legend>


				<label for="annee">Sélectionnez une année : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select><br />

				<label for="coureur">Sélectionnez un coureur : </label>
					<select name="coureur" id="coureur">
						<?php remplirListeCoureursAnnee(); ?>
					</select><br />




				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Supprimer la participation" name="sub" />

			</fieldset>
		</form>


		<script>


		var actu = function(e){
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
			}
			else{
				form.coureur.innerHTML="<option value=\"0\">----------------------</option>";
			}
		}


		document.getElementById('annee').addEventListener('change', actu, true);

		</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
