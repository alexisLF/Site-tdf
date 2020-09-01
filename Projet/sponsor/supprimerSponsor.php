

		<?php include('../includes/menu.html');
					include('supprimerSponsorRequete.php'); ?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Suppresion d'un sponsor</legend>

				<label for="n_equipe">Sélectionnez un sponsor : </label>
					<select name="n_equipe" id="n_equipe">
						<?php remplirListeEquipe(); ?>
					</select><br />


				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Supprimer le sponsor" name="sub" />

			</fieldset>
		</form>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
