

		<?php include('../includes/menu.html');
		 			include('modifierPartiEquipeRequete.php');?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Modification d'une participation d'équipe</legend>

				<label for="annee">Année du Tour : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select><br />

				<label for="equipe">Équipe : </label>
				<select name="equipe" id="equipe">
						<option value="0">----------------------</option>
					</select><br />

				<label for="direc">Directeur de l'équipe : </label>
					<select name="direc" id="direc">
						<?php remplirListeDirecteurs(); ?>
					</select><br />

				<label for="co_direc">Co-directeur de l'équipe : </label>
					<select name="co_direc" id="co_direc">
						<?php remplirListeDirecteurs(); ?>
					</select>

				<br /><br />

				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Modifier" name="sub" />

			</fieldset>
		</form>

	<script>

	var remplirTout = function (e){
		var form = document.getElementById('formu');

		if(document.getElementById('equipe').value != 0){


			var xhr = new XMLHttpRequest();

			var Lire = function()
			{
				if (xhr.readyState === 4 && xhr.status === 200)
			    {
					var tab = xhr.responseText.split('|');
					form.annee.value = tab[1];
					form.direc.value = tab[2];
					form.co_direc.value = tab[3];
				}
			}
			xhr.addEventListener("readystatechange", Lire, false);

			xhr.open('GET', 'recupPartiEquipe.php?n_equipe='+document.getElementById('equipe').value+'&annee='+document.getElementById('annee').value);
			xhr.send();
		}
		else{

		}
	}

	document.getElementById('equipe').addEventListener('change', remplirTout, false);



	
	var remplirEquipes = function(e){
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

				xhr.open('GET', 'recupPartiEquipeAnnee.php?annee='+document.getElementById('annee').value);
				xhr.send();
			}
			else{
				form.equipe.innerHTML="<option value=\"0\">----------------------</option>";
			}
		}


		document.getElementById('annee').addEventListener('change', remplirEquipes, true);

	</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
