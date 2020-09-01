<?php
include('../includes/pdo.php');
include('../includes/utils.php');
  $conn = OuvrirConnexionPDO();



  $reqNbEtapes = "SELECT n_epreuve as N FROM tdf_etape WHERE annee = :annee ORDER BY n_epreuve";
  $params = array(":annee" => $_GET['annee']);
  $cursNbEtapes = preparerRequetePDO($conn, $reqNbEtapes);




  $req ='SELECT n_epreuve as "NUMÉRO D\'EPREUVE", ville_d as "VILLE DE DÉPART", ville_a as "VILLE D\'ARRIVÉE", to_char(n_coureur) as "NUMÉRO DU GAGNANT", tdf_coureur.nom as "NOM", tdf_coureur.prenom as "PRENOM", tdf_sponsor.nom as "NOM SPONSOR", cat_code as "TYPE D\'ÉPREUVE"
    FROM tdf_temps
    JOIN tdf_etape USING(n_epreuve, annee)
    JOIN tdf_coureur USING(n_coureur)
    JOIN tdf_parti_coureur USING(n_coureur, annee)
    JOIN tdf_sponsor USING (n_sponsor, n_equipe)
    WHERE annee = :annee AND n_epreuve = :etape
    AND rang_arrivee = 1
    AND n_coureur NOT IN
      (SELECT n_coureur FROM tdf_abandon WHERE annee = :annee)
    AND valide = \'O\'

    UNION

    SELECT n_epreuve as "NUMÉRO D\'EPREUVE", ville_d as "VILLE DE DÉPART", ville_a as "VILLE D\'ARRIVÉE", \'Pas de gagnant\' as "NUMÉRO DU GAGNANT", \'Pas de gagnant\' as "NOM", translate (\'Pas de gagnant\' using nchar_cs) as "PRENOM", \'Pas de gagnant\' as "NOM SPONSOR", cat_code as "TYPE D\'ÉPREUVE"
    FROM tdf_temps
    JOIN tdf_etape USING(n_epreuve, annee)
    WHERE annee = :annee AND n_epreuve = :etape
    AND rang_arrivee > 1
    AND n_coureur NOT IN
      (SELECT n_coureur FROM tdf_abandon WHERE annee = :annee)
    AND NOT EXISTS
      (SELECT n_coureur, rang_arrivee FROM tdf_temps
      JOIN tdf_etape USING(n_epreuve, annee)
      JOIN tdf_parti_coureur USING(n_coureur, annee)
      WHERE annee = :annee AND n_epreuve = :etape
      AND rang_arrivee = 1
      AND valide = \'O\'
      AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = :annee))';


  $nEtape = 0;
  $cols = false;



  $curs = preparerRequetePDO($conn, $req);

  if(majDonneesPrepareesTabPDO($cursNbEtapes, $params)){
    echo "<table border = '2'>";
    while($ligne = $cursNbEtapes->fetch(PDO::FETCH_ASSOC)){

      $nEtape = $ligne['N'];

      $paramsEtape = array(
          ":annee" => $_REQUEST['annee'],
          ":etape" => $nEtape
        );

      if(majDonneesPrepareesTabPDO($curs, $paramsEtape)){

        while($ligneEtape = $curs->fetch(PDO::FETCH_ASSOC)){


          if(!$cols){

            echo "<tr>";

            foreach($ligneEtape as $cle=>$val)
              echo'<td><b>'.$cle.'</b></td>';

            $cols = true;
            echo "</tr>";
          }

          echo "<tr>";



          foreach($ligneEtape as $cle=>$val){
            echo "<td>$val</td>";
          }


          echo "</tr>";

        }

      }
      else{
        echo "Erreur de récupération de l'étape $nEtape<br />";
      }

    }

    echo "</table>";

  }
  else{
    echo "Erreur de récupération des numéros d'épreuves<br />";
  }

?>
