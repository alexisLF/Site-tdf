

		<?php include('../includes/menu.html'); include('modifierEtapeRequete.php'); ?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Modification d'une étape</legend>
				
				<label for="annee">Année : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select><br />

				<label for="n_epreuve">Étape : </label>
					<select name="n_epreuve" id="n_epreuve">
						<option value="-1">----------------------</option>
					</select><br />


				<label for="ville_d">Ville de départ : </label><input type="text" name="ville_d" id="ville_d" value="<?php verifierRempli('ville_d'); ?>"/><br />
				<label for="pays_d">Pays de la ville de départ : </label>
					<select name="pays_d" id="pays_d">
						<?php remplirListePays(); ?>
					</select><br />

				<label for="ville_a">Ville d'arrivée' : </label><input type="text" name="ville_a" id="ville_a" value="<?php verifierRempli('ville_a'); ?>"/><br />
				<label for="pays_a">Pays de la ville d'arrivée : </label>
					<select name="pays_a" id="pays_a">
						<?php remplirListePays(); ?>
					</select><br />

				<label for="distance">Distance : </label><input type="text" name="distance" id="distance" value="<?php verifierRempli('distance'); ?>"/><br />

				<label for="moyenne">Moyenne : </label><input type="text" name="moyenne" id="moyenne" value="<?php verifierRempli('moyenne'); ?>"/><br />

				<label for="cat_code">Type d'épreuve : </label>
					<select name="cat_code" id="cat_code">
						<?php remplirTypesEtapes(); ?>
					</select><br />

				<label for="jour">Jour : </label><input type="text" name="jour" id="jour" value="<?php verifierRempli('jour'); ?>"/><br />

				<label for="mois">Mois : </label><input type="text" name="mois" id="mois" value="<?php verifierRempli('mois'); ?>"/><br />

				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Modifier" name="sub" />

			</fieldset>
		</form>

	<script>

	var remplirNumeroEpreuve = function (e){
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
			form.n_epreuve.innerHTML = '<option value="-1">----------------------</option>';

		}
	}

	var remplirInfos = function (e){
		var form = document.getElementById('formu');

		if(document.getElementById('n_epreuve').value != 0){


			var xhr = new XMLHttpRequest();

			var Lire = function() 
			{
				if (xhr.readyState === 4 && xhr.status === 200) 
			    { 
					var res = xhr.responseText.split('|');

					form.ville_d.value=res[1];
					form.ville_a.value=res[2];
					form.pays_d.value=res[3];	
					form.pays_a.value = res[4];
					form.jour.value = res[5];
					form.mois.value = res[6];
					form.distance.value = res[7];
					form.moyenne.value = res[8];
					form.cat_code.value = res[9];
				}
			}
			xhr.addEventListener("readystatechange", Lire, false);

			xhr.open('GET', 'recupInfosEtape.php?annee='+document.getElementById('annee').value+'&n_epreuve='+document.getElementById('n_epreuve').value);
			xhr.send();	
		}
		else{

		}
	}

	document.getElementById('annee').addEventListener('change', remplirNumeroEpreuve, false);
	document.getElementById('n_epreuve').addEventListener('change', remplirInfos, false);

	</script>

	</body>
</html>


