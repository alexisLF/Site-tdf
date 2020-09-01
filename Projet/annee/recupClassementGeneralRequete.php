<?php
include('../includes/pdo.php');
include('../includes/utils.php');
$conn = OuvrirConnexionPDO();

$req ='SELECT RANG, NOM, PRENOM, floor("TEMPS TOTAL"/3600)||\'h \'||floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )||\'min \'||
           floor (("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 ) - (floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )*60)))||\'s\' as "TEMPS TOTAL", "RADIÉ" FROM(
      SELECT rownum as RANG, NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
        SELECT NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
          SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", \' \' as "RADIÉ" FROM(
           SELECT NOM,PRENOM,temps_total
           FROM(
            SELECT NOM,PRENOM,temps_total FROM
              (
              (select n_coureur,nom,prenom,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide from tdf_coureur
              left join tdf_temps using(n_coureur)
              left join tdf_temps_difference using(n_coureur,annee)
              left join tdf_parti_coureur using(n_coureur, annee)
              where n_coureur not in(
                select n_coureur from tdf_abandon where annee=:annee
                )
                and annee=:annee and valide=\'O\'
                group by (n_coureur,nom,prenom,difference,valide)
              )
              order by temps_total
            )
           )
          )


          UNION


        SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", \'Radié\' as "RADIÉ" FROM(
           SELECT NOM,PRENOM,temps_total
           FROM(
            SELECT NOM,PRENOM,temps_total FROM
              (
              (select n_coureur,\'---\' as NOM, translate (\'---\' using nchar_cs) as PRENOM,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide from tdf_coureur
              left join tdf_temps using(n_coureur)
              left join tdf_temps_difference using(n_coureur,annee)
              left join tdf_parti_coureur using(n_coureur, annee)
              where n_coureur not in(
                select n_coureur from tdf_abandon where annee=:annee
                )
                and annee=:annee and valide=\'R\'
                group by (n_coureur,nom,prenom,difference,valide)
              )
              order by temps_total
            )
           )
          )
        ) ORDER BY "TEMPS TOTAL"
      )
    )
    WHERE RANG = 1

    UNION

    SELECT RANG, NOM, PRENOM, \'+ \'||floor("TEMPS TOTAL"/3600)||\'h \'||floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )||\'min \'||
           floor (("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 ) - (floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )*60)))||\'s\' as "TEMPS TOTAL", "RADIÉ" FROM(
      SELECT rownum as RANG, NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
        SELECT NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
          SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", \' \' as "RADIÉ" FROM(
           SELECT NOM,PRENOM,temps_total
           FROM(
            SELECT NOM,PRENOM,temps_total FROM
              (
              (select n_coureur,nom,prenom,sum(total_seconde) as somme,
              (sum(total_seconde)+nvl(difference,0)-
                (SELECT min(total)
                  FROM
                    (SELECT (sum(total_seconde)+nvl(difference,0)) as total, n_coureur, annee
                    FROM tdf_temps
                    LEFT JOIN tdf_temps_difference USING(n_coureur, annee)
                    WHERE annee = :annee
                    AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = :annee)
                    GROUP BY difference, n_coureur, annee)
                  ))
              as temps_total, valide from tdf_coureur
              left join tdf_temps using(n_coureur)
              left join tdf_temps_difference using(n_coureur,annee)
              left join tdf_parti_coureur using(n_coureur, annee)
              where n_coureur not in(
                select n_coureur from tdf_abandon where annee=:annee
                )
                and annee=:annee and valide=\'O\'
                group by (n_coureur,nom,prenom,difference,valide)
              )
              order by temps_total
            )
           )
          )


          UNION


        SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", \'Radié\' as "RADIÉ" FROM(
           SELECT NOM,PRENOM, temps_total
           FROM(
            SELECT NOM,PRENOM,temps_total FROM
              (
              (select n_coureur,\'---\' as NOM, translate (\'---\' using nchar_cs) as PRENOM,sum(total_seconde) as somme,
              (sum(total_seconde)+nvl(difference,0)-
                (SELECT min(total)
                  FROM
                    (SELECT (sum(total_seconde)+nvl(difference,0)) as total, n_coureur, annee
                    FROM tdf_temps
                    LEFT JOIN tdf_temps_difference USING(n_coureur, annee)
                    WHERE annee = :annee
                    AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = :annee)
                    GROUP BY difference, n_coureur, annee)
                  ))
              as temps_total, valide from tdf_coureur
              left join tdf_temps using(n_coureur)
              left join tdf_temps_difference using(n_coureur,annee)
              left join tdf_parti_coureur using(n_coureur, annee)
              where n_coureur not in(
                select n_coureur from tdf_abandon where annee=:annee
                )
                and annee=:annee and valide=\'R\'
                group by (n_coureur,nom,prenom,difference,valide)
              )
              order by temps_total
            )
           )
          )
        ) ORDER BY "TEMPS TOTAL"
      )
    )
    WHERE RANG > 1';


$cols = false;


$curs = preparerRequetePDO($conn, $req);

$params = array(
  ":annee" => $_GET['annee']
  );


executeReqTabPrepare($curs, $params);
?>
