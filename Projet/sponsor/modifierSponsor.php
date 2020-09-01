

		<?php include('../includes/menu.html');

		include('modifierSponsorRequete.php');?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Modification d'un sponsor</legend>

				<label for="n_equipe">Sélectionnez un sponsor : </label>
					<select name="n_equipe" id="n_equipe">
						<?php remplirListeEquipe(); ?>
					</select><br />

				<label for="nom">Nom de sponsor :</label>
			<input type="text" name="nom" value="<?php verifierRempli('nom'); ?>">

			<br>

			<label for="pays">Nationalité :</label>
				<select name="pays" id="pays">
					<?php remplirListePays(); ?>
				</select>
			<br>

			<label for="annee">Annee de sponsor :</label>
			<input type="text" name="annee" id="annee" maxlength="4" value="<?php verifierRempli('annee'); ?>">
			<br>

			<label for="dimi">Diminutif de sponsor :</label>
			<input type="text" name="dimi" id="dimi" maxlength="3" value="<?php verifierRempli('dimi'); ?>">
			<br>

				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Modifier" name="sub" />

			</fieldset>
		</form>

	<script>

	var remplir = function (e){
		var form = document.getElementById('formu');

		if(document.getElementById('n_equipe').value != 0){

			var xhr = new XMLHttpRequest();

			var Lire = function()
			{
				if (xhr.readyState === 4 && xhr.status === 200)
			    {
					var tab = xhr.responseText.split('|');
					form.nom.value = tab[1];
					form.pays.value = tab[2];
					form.annee.value = tab[3];
					form.dimi.value = tab[4];
				}
			}
			xhr.addEventListener("readystatechange", Lire, false);

			xhr.open('GET', 'recupInfosSponsor.php?n_equipe='+document.getElementById('n_equipe').value);
			xhr.send();
		}
		else{

		}
	}

	document.getElementById('n_equipe').addEventListener('change', remplir, false);

	</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
