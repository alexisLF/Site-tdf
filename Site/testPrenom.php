<?php
include_once("listeRegex.php");
include_once("regex.php");
$resultPrenom;
for ($i=0; $i <count($listeRegex) ; $i++) {
	$resultPrenom[$i] = verificationPrenom($listeRegex[$i]);
}
echo '<table><tr><th>Variable</th><th>Resultat</th></tr>';
for($i = 0; $i < count($listeRegex);$i++){
  echo '<tr>';
  echo '<td>'.$listeRegex[$i].'</td><td>'.$resultPrenom[$i].'</td>';
  echo '</tr>';
}
echo "</table>";


 ?>
