<?php
include('../includes/pdo.php');
include('../includes/utils.php');
  $conn = OuvrirConnexionPDO();

  $req ='SELECT * FROM
      (SELECT n_dossard, tdf_coureur.nom, prenom, tdf_sponsor.nom as "NOM SPONSOR", \' \' as "ABANDON" FROM tdf_parti_coureur
      JOIN tdf_coureur USING(n_coureur)
      JOIN tdf_sponsor USING(n_sponsor, n_equipe)
      WHERE annee = :annee
      AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = :annee)


      UNION

      SELECT n_dossard, tdf_coureur.nom, prenom, tdf_sponsor.nom as "NOM SPONSOR", libelle||\' à l\'\'étape \'||n_epreuve||replace(\' (\'||to_char(tdf_abandon.commentaire)||\')\', \'()\', \'\') as "ABANDON" FROM tdf_parti_coureur
      JOIN tdf_coureur USING(n_coureur)
      JOIN tdf_sponsor USING(n_sponsor, n_equipe)
      JOIN tdf_abandon USING(n_coureur, annee)
      JOIN tdf_typeaban USING(c_typeaban)
      WHERE annee = :annee
      AND n_coureur IN (SELECT n_coureur FROM tdf_abandon WHERE annee = :annee))
      ORDER BY n_dossard';



  $curs = preparerRequetePDO($conn, $req);

  $params = array(
    ":annee" => $_GET['annee']
    );


  executeReqTabPrepare($curs, $params);


?>
