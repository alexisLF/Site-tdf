<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

		<?php include('../includes/menu.html'); include('ajout.php');?>



		<form method="POST" action="#">

			<fieldset>
				<legend>Ajout d'une étape</legend>

				<label for="annee">Année : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
					</select><br />

				<label for="n_epreuve">Numéro d'épreuve : </label><input type="number" name="n_epreuve" id="n_epreuve" min="0" value="<?php verifierRempli('n_epreuve'); ?>"/><br />


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

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Ajouter" name="sub" />

			</fieldset>
		</form>

	</body>
</html>


