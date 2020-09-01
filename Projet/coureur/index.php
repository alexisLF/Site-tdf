<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

		<?php include('../includes/menu.html'); include('ajout.php') ?>

		<form method="POST" action="#">

			<fieldset>
				<legend>Ajout d'un coureur</legend>

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

				<input  class="btn waves-effect waves-light amber darken-4" type="submit" value="Ajouter" name="sub" />

			</fieldset>
		</form>


		<script>

			document.getElementById('paysNaissance').addEventListener('change', function(){
				document.getElementById('paysActuel').value = this.value;
			}, false);

		</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
