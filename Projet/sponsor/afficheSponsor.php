<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

	<?php include('../includes/menu.html'); ?>

	<form method="POST" action="#">
		<fieldset>
		<legend>Afficher les sponsors d'une equipe</legend>

		<label for="n_equipe">Numero equipe :</label>
				<select name="n_equipe" id="n_equipe">
					<?php remplirListeEquipe(); ?>
				</select>

			<br>
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

		var lien = 'recupSponsors.php?n_equipe='+document.getElementById('n_equipe').value;

		xhr.open('GET', lien);
		xhr.send();
	}

	document.getElementById('n_equipe').addEventListener('change', actu, true);

	window.onLoad = actu();

	</script>




</body>
<?php include('../includes/footer.html'); ?>
</html>
<?php include('afficheSponsorRequete.php'); ?>
