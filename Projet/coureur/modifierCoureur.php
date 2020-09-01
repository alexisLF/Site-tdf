

		<?php include('../includes/menu.html');
		 			include('modifierCoureurRequete.php');
		?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Modification d'un coureur</legend>

				<label for="num">Sélectionnez un coureur : </label>
					<select name="num" id="num">
						<?php remplirListeCoureurs(); ?>
					</select><br />

				<label for="nom">Nom du coureur : </label><input type="text" name="nom" id="nom" value="<?php verifierRempli('nom'); ?>"/><br />
				<label for="prenom">Prénom du coureur : </label><input type="text" name="prenom" id="prenom" value="<?php verifierRempli('prenom'); ?>"/><br />
				<label for="naiss">Année de naissance du coureur : </label><input type="text" name="naiss" id="naiss" maxlength="4" value="<?php verifierRempli('naiss'); ?>"/><br />
				<label for="prem">Année de la première participation du coureur : </label><input type="text" name="prem" id="prem" maxlength="4" value="<?php verifierRempli('prem'); ?>"/><br />

				<label for="paysNaissance">Nationalité de naissance du coureur : </label>
					<select name="paysNaissance" id="paysNaissance">
						<?php remplirListePays(); ?>
					</select><br />

				<label for="paysActuel">Nationalité actuelle du coureur : </label>
					<select name="paysActuel" id="paysActuel">
						<?php remplirListePays(); ?>
					</select>
				<label for="datePaysActuel">Date d'obtention de cette nationalité : </label><input type="text" name="datePaysActuel" id="datePaysActuel" maxlength="4" value="<?php verifierRempli('datePaysActuel'); ?>"/><br />

				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Modifier" name="sub" />

			</fieldset>
		</form>

	<script>

	var remplir = function (e){
		var form = document.getElementById('formu');

		if(document.getElementById('num').value != 0){

			var xhr = new XMLHttpRequest();

			var Lire = function()
			{
				if (xhr.readyState === 4 && xhr.status === 200)
			    {
					var tab = xhr.responseText.split('|');
					form.nom.value = tab[1];
					form.prenom.value = tab[2];
					form.naiss.value = tab[3];
					form.prem.value = tab[4];
					form.paysNaissance.value = tab[5];
					form.paysActuel.value = tab[6];
					form.datePaysActuel.value = tab[7];
				}
			}
			xhr.addEventListener("readystatechange", Lire, false);

			xhr.open('GET', 'recupInfosCoureur.php?n_coureur='+document.getElementById('num').value);
			xhr.send();
		}
		else{

		}
	}

	document.getElementById('num').addEventListener('change', remplir, false);

	</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
