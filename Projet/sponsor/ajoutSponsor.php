<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>


	<?php include('../includes/menu.html');include('ajout.php'); ?>

<form method="POST" action="#">
		<fieldset>
			<legend>Ajout d'un sponsor</legend>
			<label for="nom">Nom de sponsor :</label>
			<input type="text" name="nom" value="<?php verifierRempli('nom'); ?>">
			<br>

			<label for="n_equipe">Numero equipe :</label>
				<select name="n_equipe" id="n_equipe">
					<?php remplirListeEquipe(); ?>
				</select>

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

			<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Ajouter" name="sub">

		</fieldset>
	</form>
</body>
<?php include('../includes/footer.html'); ?>
</html>
