


		<?php include('../includes/menu.html'); include('supprimerEtapeRequete.php');?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Suppresion d'une étape</legend>
				
				<label for="annee">Année : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select><br />

					

				<label for="n_epreuve">Étape : </label>
					<select name="n_epreuve" id="n_epreuve">
					</select><br />


				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Supprimer l'étape" name="sub" />

			</fieldset>
		</form>

		<script>

		var remplir = function (e){
			var form = document.getElementById('formu');

			if(document.getElementById('annee').value != 0){

				var xhr = new XMLHttpRequest();

				var Lire = function() 
				{
					if (xhr.readyState === 4 && xhr.status === 200) 
				    { 
						var res = xhr.responseText;
						form.n_epreuve.innerHTML = res;	
					}
				}
				xhr.addEventListener("readystatechange", Lire, false);

				xhr.open('GET', 'recupEtapesAnnee.php?annee='+document.getElementById('annee').value);
				xhr.send();	
			}
			else{
				form.n_epreuve.disabled = true;
			}
		}

		document.getElementById('annee').addEventListener('change', remplir, false);

		</script>

	</body>
</html>


