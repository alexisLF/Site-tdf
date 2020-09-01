
	<?php include('../includes/menu.html');
		include('ajoutEquipeRequete.php'); ?>

<form method="POST" action="#">
		<fieldset>
			<legend>Ajout d'une equipe</legend>

			<label for="annee">Annee création de l'équipe :</label>
			<input type="text" name="creation" id="creation" maxlength="4" value="<?php verifierRempli('creation'); ?>">
			<br>

			<label for="nom">Nom de sponsor :</label>
			<input type="text" name="nom" value="<?php verifierRempli('nom'); ?>">
			<br>



			<label for="pays">Nationalité :</label>
				<select name="pays" id="pays">
					<?php remplirListePays(); ?>
				</select>
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
