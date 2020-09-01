<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

	<?php include('../includes/menu.html'); ?>
	
	<form method="POST" action="#">
		<fieldset>
		<legend>Afficher les coureurs d'une equipe</legend>

		<label for="n_equipe">Numero equipe :</label>
				<select name="n_equipe" id="n_equipe">
					<?php remplirListeEquipe(); ?>
				</select>

			<br>
			<label for="annee">Annee de sponsor :</label>
			<input type="text" name="annee" id="annee" maxlength="4">
			<br>
			<input class="btn waves-effect waves-light amber darken-4" type="button" name="sub" id="sub" value="Afficher les coureurs">
		</fieldset>
	</form>


	<div id="tableau">

	</div>

	<script>


	function actu(e){
		var xhr = new XMLHttpRequest();

		var Lire = function()
		{
			if (xhr.readyState === 4 && xhr.status === 200)
		    {
				var tab = xhr.responseText;


				var tableau = document.getElementById('tableau');

				tableau.innerHTML = tab;

			}
		}

		xhr.addEventListener("readystatechange", Lire, false);

		var lien = 'recupCoureursEquipe.php?n_equipe='+document.getElementById('n_equipe').value+'&annee='+document.getElementById('annee').value;

		xhr.open('GET', lien);
		xhr.send();
	}

	document.getElementById('sub').addEventListener('click', actu, true);

	</script>




</body>
<?php include('../includes/footer.html'); ?>
</html>
