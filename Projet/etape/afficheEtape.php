<?php

include('../includes/pdo.php');
include('../includes/utils.php');

?>

		<?php include('../includes/menu.html'); ?>

		<a href="javascript:history.back()">Page Précédente</a>

		<form method="POST" action="#">

			<fieldset>
				<legend>Affichage des coureurs</legend>

				<input type='checkbox' name='colonnes[]' value='nom||prenom as "COUREUR"' id='colNom' /><label for='colNom'>Nom et prénom</label><br />
				<input type='checkbox' name='colonnes[]' value='nvl(annee_naissance, 0) as "ANNÉE DE NAISSANCE"' id="annee_naissance" /><label for="annee_naissance">Année de naissance</label><br />
				<input type='checkbox' name='colonnes[]' value='nvl(annee_prem, 0) as "PREMIÈRE PARTICIPATION"' id='annee_prem' /><label for='annee_prem'>Année de première participation</label><br />

				<label for="nom">Nom : </label><input type="text" name="nom" id="nom"/><br />
				<label for="naiss">Date de naissance : </label><input type="text" name="naiss" id="naiss"/><br />
				<label for="prem">Date de première participation : </label><input type="text" name="prem" id="prem"/><br />

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

							if(i != 0)
								texte += "<td><a href='afficheDetailsCoureur.php?n_coureur="+contenu[i][0]+"'>Voir les détails</a></td>";

							texte +=  "</tr>";
						}


						texte +=  "</table>";
					}
					tableau.innerHTML = texte;

				}
			}
			
			xhr.addEventListener("readystatechange", Lire, false);

			var lien = 'recupNomsCoureur.php?nom='+document.getElementById('nom').value+'&naiss='+document.getElementById('naiss').value+'&prem='+document.getElementById('prem').value+'&colonnes=N_COUREUR,';

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

		document.getElementById('nom').addEventListener('input', actu, true);
		document.getElementById('naiss').addEventListener('input', actu, true);
		document.getElementById('prem').addEventListener('input', actu, true);

		window.onLoad = actu();
		
		var cols = document.getElementsByName('colonnes[]');

			for(i = 0; i < cols.length; i++){
				cols[i].addEventListener('change', actu, true);
			}
		</script>

	</body>
</html>


