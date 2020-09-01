<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

		<?php include('../includes/menu.html'); ?>

		<form method="POST" action="#">

			<fieldset>
				<legend>Affichage des participations des équipes</legend>

				<input type='checkbox' name='colonnes[]' value='spons.nom as equipe' id='equipe' /><label for='equipe'>Équipe</label><br />
				<input type='checkbox' name='colonnes[]' value="to_char(dir.nom||' '||dir.prenom) as directeur" id="directeur" /><label for="directeur">Premier directeur</label><br />
				<input type='checkbox' name='colonnes[]' value="to_char(codir.nom||' '||codir.prenom) as codirecteur" id="codirecteur" /><label for="codirecteur">Co-directeur</label><br />
				<input type='checkbox' name='colonnes[]' value='annee' id='anneeCol' /><label for='anneeCol'>Année</label><br />

				<label for="annee">Année : </label><input type="text" name="annee" id="annee"/><br />

				<br /><br />

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
					var tab = xhr.responseText.split('<br />');
					var contenu = new Array(tab);

					var i;
					var j;

					for(i = 0; i < tab.length; i++)
						contenu[i] = tab[i].split('|');


					var tableau = document.getElementById('tableau');
					var texte = "";

					tableau.innerHTML = "";

					if(contenu[0].length > 1){

						texte += "<table border='2'>";

						for(i = 0; i < contenu.length; i++){

							texte +=  "<tr>";

							for(j = 1; j < contenu[i].length; j++){
								texte +=  "<td>"+contenu[i][j]+"</td>";
							}


							texte +=  "</tr>";
						}


						texte +=  "</table>";
					}
					tableau.innerHTML = texte;

				}
			}

			xhr.addEventListener("readystatechange", Lire, false);

			var lien = 'recupNomsPartiEquipe.php?annee='+document.getElementById('annee').value+'&colonnes=N_EQUIPE,';

			var cols = document.getElementsByName('colonnes[]');

			for(i = 0; i < cols.length; i++){
				if(cols[i].checked){
					lien = lien+cols[i].value+",";
				}
			}
			console.log(lien);

			xhr.open('GET', lien);
			xhr.send();
		}

		document.getElementById('annee').addEventListener('input', actu, true);

		window.onLoad = actu();

		var cols = document.getElementsByName('colonnes[]');

			for(i = 0; i < cols.length; i++){
				cols[i].addEventListener('change', actu, true);
			}
		</script>

	</body>
	<?php include('../includes/footer.html'); ?>
</html>
