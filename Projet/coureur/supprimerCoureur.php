


		<?php include('../includes/menu.html');
					include('supprimerCoureurRequete.php'); ?>

		<form method="POST" action="#" id="formu">

			<fieldset>
				<legend>Suppresion d'un coureur</legend>

				<label for="num">Sélectionnez un coureur : </label>
					<select name="num" id="num">
						<?php remplirListeCoureurs(); ?>
					</select><br />


				<br /><br />

				<input class="btn waves-effect waves-light amber darken-4" type="submit" value="Supprimer le coureur" name="sub" />

			</fieldset>
		</form>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
