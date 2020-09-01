<?php
include_once("listeRegex.php");
include_once("regex.php");
$resultNom;
for ($i=0; $i <count($listeRegex) ; $i++) {
	$resultNom[$i] = verificationNom($listeRegex[$i]);
}
echo '<table><tr><th>Variable</th><th>Resultat</th></tr>';
for($i = 0; $i < count($listeRegex);$i++){
  echo '<tr>';
  echo '<td>'.$listeRegex[$i].'</td><td>'.$resultNom[$i].'</td>';
  echo '</tr>';
}
echo "</table>";


 ?>
