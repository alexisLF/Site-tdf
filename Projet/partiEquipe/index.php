<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

		<?php include('../includes/menu.html'); include('ajout.php'); ?>

		<form method="POST" action="#">

			<fieldset>
				<legend>Ajout d'une participation d'équipe</legend>

				<label for="equipe">Équipe : </label>
				<select name="equipe" id="equipe">
						<?php remplirListeEquipe(); ?>
					</select><br />


				<label for="annee">Année du Tour : </label>
					<select name="annee" id="annee">
						<?php remplirAnnees(); ?>
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

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Ajouter" name="sub" />

			</fieldset>
		</form>


	</body>
	<?php include('../includes/footer.html'); ?>
</html>
