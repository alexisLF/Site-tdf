SELECT * FROM tdf_coureur ORDER BY n_coureur DESC;
SELECT max(n_coureur) as n_coureur FROM prof.vt_coureur;
SELECT * FROM tdf_coureur ORDER BY nom, prenom;

SELECT * FROM tdf_sponsor ORDER BY n_equipe;

SELECT tdf_coureur.nom, prenom, tdf_sponsor.nom as "NOM SPONSOR", annee, tdf_nation.nom as "PAYS"
FROM tdf_coureur
JOIN tdf_parti_coureur USING(n_coureur)
JOIN tdf_sponsor USING(n_equipe, n_sponsor)
JOIN tdf_nation USING(code_cio)
ORDER BY n_coureur;

SELECT * FROM tdf_coureur ORDER BY n_coureur DESC;
SELECT * FROM tdf_app_nation ORDER BY n_coureur DESC;

SELECT * FROM tdf_coureur JOIN tdf_app_nation USING(n_coureur);

SELECT n_coureur, nom, prenom, annee_naissance, annee_prem, code_cio, annee_debut, annee_fin, 
(SELECT code_cio FROM tdf_app_nation WHERE annee_fin IS NULL AND n_coureur = '1773') as code2, 
(SELECT annee_debut FROM tdf_app_nation WHERE annee_fin IS NULL AND n_coureur = '1773') as debut2
FROM tdf_coureur 
JOIN tdf_app_nation USING(n_coureur) 
WHERE n_coureur = '1773' AND rownum <= 1;


INSERT INTO tdf_coureur VALUES('1772', 'Huet', 'Jérémy', '1990', '2010', 'ETU2_10', sysdate);
DELETE FROM tdf_coureur WHERE compte_oracle = 'ETU2_10';
DELETE FROM tdf_coureur WHERE n_coureur >= '1772';
DELETE FROM tdf_app_nation WHERE n_coureur >= '1772';
commit;

DELETE FROM lieux;

SELECT * FROM tdf_coureur
JOIN tdf_app_nation USING(n_coureur)
ORDER BY n_coureur DESC;

DELETE FROM tdf_coureur WHERE n_coureur = '1774';

DESC tdf_app_nation;
DESC tdf_coureur;

SELECT * FROM tdf_app_nation ORDER BY n_coureur DESC;
rollback;

SELECT * FROM tdf_parti_coureur
JOIN tdf_coureur USING(n_coureur);

SELECT
'CREATE TABLE '||table_name||' as select * from patrice.'||table_name||';'
FROM all_tables where owner = 'PATRICE' AND table_name like 'TDF%';

SELECT
'drop table '||table_name||';'
FROM user_tables WHERE table_name like 'TDF%';

drop table TDF_ABANDON;
drop table TDF_ANNEE;
drop table TDF_APP_NATION;
drop table TDF_COMMENTAIRE;
drop table TDF_COUREUR;
drop table TDF_DIRECTEUR;
drop table TDF_EPREUVE;
drop table TDF_EQUIPE;
drop table TDF_EQU_SUCCEDE;
drop table TDF_NATION;
drop table TDF_NAT_SUCCEDE;
drop table TDF_ORDREQUI;
drop table TDF_PARTI_COUREUR;
drop table TDF_PARTI_EQUIPE;
drop table TDF_SPONSOR;
drop table TDF_TEMPS;
drop table TDF_TEMPS_DIFFERENCE;
drop table TDF_TYPEABAN;

CREATE TABLE TDF_ABANDON as select * from patrice.TDF_ABANDON;
CREATE TABLE TDF_ANNEE as select * from patrice.TDF_ANNEE;
CREATE TABLE TDF_APP_NATION as select * from patrice.TDF_APP_NATION;
CREATE TABLE TDF_CATEGORIE_EPREUVE as select * from patrice.TDF_CATEGORIE_EPREUVE;
CREATE TABLE TDF_CLASSEMENTS_GENERAUX as select * from patrice.TDF_CLASSEMENTS_GENERAUX;
CREATE TABLE TDF_COMMENTAIRE as select * from patrice.TDF_COMMENTAIRE;
CREATE TABLE TDF_COUREUR as select * from patrice.TDF_COUREUR;
CREATE TABLE TDF_DIRECTEUR as select * from patrice.TDF_DIRECTEUR;
CREATE TABLE TDF_EQUIPE as select * from patrice.TDF_EQUIPE;
CREATE TABLE TDF_EQU_SUCCEDE as select * from patrice.TDF_EQU_SUCCEDE;
CREATE TABLE TDF_ETAPE as select * from patrice.TDF_ETAPE;
CREATE TABLE TDF_NATION as select * from patrice.TDF_NATION;
CREATE TABLE TDF_NAT_SUCCEDE as select * from patrice.TDF_NAT_SUCCEDE;
CREATE TABLE TDF_ORDREQUI as select * from patrice.TDF_ORDREQUI;
CREATE TABLE TDF_PARTI_COUREUR as select * from patrice.TDF_PARTI_COUREUR;
CREATE TABLE TDF_PARTI_EQUIPE as select * from patrice.TDF_PARTI_EQUIPE;
CREATE TABLE TDF_PRIX_CEE as select * from patrice.TDF_PRIX_CEE;
CREATE TABLE TDF_PRIX_CEP as select * from patrice.TDF_PRIX_CEP;
CREATE TABLE TDF_PRIX_CFE as select * from patrice.TDF_PRIX_CFE;
CREATE TABLE TDF_PRIX_CFI as select * from patrice.TDF_PRIX_CFI;
CREATE TABLE TDF_SPONSOR as select * from patrice.TDF_SPONSOR;
CREATE TABLE TDF_TEMPS as select * from patrice.TDF_TEMPS;
CREATE TABLE TDF_TEMPS_DIFFERENCE as select * from patrice.TDF_TEMPS_DIFFERENCE;
CREATE TABLE TDF_TYPEABAN as select * from patrice.TDF_TYPEABAN;
CREATE TABLE TDF_TYPE_EPREUVE as select * from patrice.TDF_TYPE_EPREUVE;
CREATE TABLE TDF_USER as select * from patrice.TDF_USER;



select * from INFORMATION_SCHEMA.COLUMNS;









--Palmarès coureur

SELECT courNom as "NOM", prenom, annee, to_char(RANG) as "RANG", tdf_sponsor.nom as "NOM SPONSOR", PAYS FROM 
  (
    SELECT n_coureur,courNom,prenom,somme, temps_total, valide, annee, n_equipe, n_sponsor, tdf_nation.nom as "PAYS", ROW_NUMBER() OVER(PARTITION BY annee ORDER BY temps_total) as RANG FROM
    (
      (select n_coureur,nom as courNom,prenom,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide, annee, n_equipe, n_sponsor from tdf_coureur
            left join tdf_temps using(n_coureur)
            left join tdf_temps_difference using(n_coureur,annee)
            left join tdf_parti_coureur using(n_coureur, annee)
            where (n_coureur, annee) not in(
                select n_coureur, annee from tdf_abandon where annee=tdf_abandon.annee
              )
              and valide='O'
              group by (n_coureur,nom,prenom,difference,valide, annee, n_equipe, n_sponsor)
            )
            ORDER BY temps_total
    )
    JOIN tdf_app_nation USING (n_coureur)
    JOIN tdf_nation USING (code_cio)
    ORDER BY n_coureur
  )
JOIN tdf_sponsor USING(n_equipe, n_sponsor)
WHERE n_coureur = 710

UNION

SELECT courNom as "NOM", prenom, annee, RANG, tdf_sponsor.nom as "NOM SPONSOR", PAYS FROM 
  (
    SELECT n_coureur,courNom,prenom,somme, temps_total, valide, annee, n_equipe, n_sponsor, tdf_nation.nom as "PAYS", 'Abandon' as RANG FROM
    (
      (select n_coureur,nom as courNom,prenom,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide, annee, n_equipe, n_sponsor from tdf_coureur
            left join tdf_temps using(n_coureur)
            left join tdf_temps_difference using(n_coureur,annee)
            left join tdf_parti_coureur using(n_coureur, annee)
            where (n_coureur, annee) in(
                select n_coureur, annee from tdf_abandon where annee=tdf_abandon.annee
              )
              and valide='O'
              group by (n_coureur,nom,prenom,difference,valide, annee, n_equipe, n_sponsor)
            )
            ORDER BY temps_total
    )
    JOIN tdf_app_nation USING (n_coureur)
    JOIN tdf_nation USING (code_cio)
    ORDER BY n_coureur
  )
JOIN tdf_sponsor USING(n_equipe, n_sponsor)
WHERE n_coureur = 710;
    
    
    
    
    
    
select * from tdf_coureur
where n_coureur =1603;






--Étape + gagnant


SELECT n_epreuve as "NUMÉRO D'EPREUVE", ville_d as "VILLE DE DÉPART", ville_a as "VILLE D'ARRIVÉE", to_char(n_coureur) as "NUMÉRO DU GAGNANT", tdf_coureur.nom as "NOM", tdf_coureur.prenom as "PRENOM", tdf_sponsor.nom as "NOM SPONSOR", cat_code as "TYPE D'ÉPREUVE"
FROM tdf_temps
JOIN tdf_etape USING(n_epreuve, annee)
JOIN tdf_coureur USING(n_coureur)
JOIN tdf_parti_coureur USING(n_coureur, annee)
JOIN tdf_sponsor USING (n_sponsor, n_equipe)
WHERE annee = 2018 AND n_epreuve = 1
AND rang_arrivee = 1
AND n_coureur NOT IN
  (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018)
AND valide = 'O'

UNION

SELECT n_epreuve as "NUMÉRO D'EPREUVE", ville_d as "VILLE DE DÉPART", ville_a as "VILLE D'ARRIVÉE", 'Pas de gagnant' as "NUMÉRO DU GAGNANT", 'Pas de gagnant' as "NOM", translate ('Pas de gagnant' using nchar_cs) as "PRENOM", 'Pas de gagnant' as "NOM SPONSOR", cat_code as "TYPE D'ÉPREUVE"
FROM tdf_temps
JOIN tdf_etape USING(n_epreuve, annee)
WHERE annee = 2018 AND n_epreuve = 1
AND rang_arrivee > 1
AND n_coureur NOT IN
  (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018)
AND NOT EXISTS
  (SELECT n_coureur, rang_arrivee FROM tdf_temps
  JOIN tdf_etape USING(n_epreuve, annee)
  JOIN tdf_parti_coureur USING(n_coureur, annee)
  WHERE annee = 2018 AND n_epreuve = 1
  AND rang_arrivee = 1
  AND valide = 'O'
  AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018));



--Rang minimum d'une étape (PAS DANS APPLI)
SELECT n_epreuve, min(rang_arrivee)
FROM tdf_temps
JOIN tdf_parti_coureur USING(n_coureur, annee)
WHERE n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018)
AND annee = 2018
GROUP BY n_epreuve
ORDER BY n_epreuve;


    
    
    
--Participations, sponsors et abandons
SELECT * FROM
(SELECT n_coureur, n_dossard, tdf_coureur.nom, prenom, tdf_sponsor.nom as "NOM SPONSOR", ' ' as "ABANDON" FROM tdf_parti_coureur
JOIN tdf_coureur USING(n_coureur)
JOIN tdf_sponsor USING(n_sponsor, n_equipe)
WHERE annee = 2018
AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018)


UNION

SELECT n_coureur, n_dossard, tdf_coureur.nom, prenom, tdf_sponsor.nom as "NOM SPONSOR", libelle as "ABANDON" FROM tdf_parti_coureur
JOIN tdf_coureur USING(n_coureur)
JOIN tdf_sponsor USING(n_sponsor, n_equipe)
JOIN tdf_abandon USING(n_coureur, annee)
JOIN tdf_typeaban USING(c_typeaban)
WHERE annee = 2018
AND n_coureur IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018))
ORDER BY n_dossard;




--Classement général
SELECT RANG, NOM, PRENOM, floor("TEMPS TOTAL"/3600)||'h '||floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )||'min '||
       floor (("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 ) - (floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )*60)))||'s' as "TEMPS TOTAL", "RADIÉ" FROM(
  SELECT rownum as RANG, NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
    SELECT NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
      SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", ' ' as "RADIÉ" FROM(
       SELECT NOM,PRENOM,temps_total
       FROM( 
        SELECT NOM,PRENOM,temps_total FROM
          (
          (select n_coureur,nom,prenom,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide from tdf_coureur
          left join tdf_temps using(n_coureur)
          left join tdf_temps_difference using(n_coureur,annee)
          left join tdf_parti_coureur using(n_coureur, annee)
          where n_coureur not in(
            select n_coureur from tdf_abandon where annee=2002
            )
            and annee=2002 and valide='O'
            group by (n_coureur,nom,prenom,difference,valide)
          )
          order by temps_total
        )
       )
      )
      
      
      UNION
      
      
    SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", 'Radié' as "RADIÉ" FROM(
       SELECT NOM,PRENOM,temps_total
       FROM( 
        SELECT NOM,PRENOM,temps_total FROM
          (
          (select n_coureur,nom,prenom,sum(total_seconde) as somme,sum(total_seconde)+nvl(difference,0) as temps_total, valide from tdf_coureur
          left join tdf_temps using(n_coureur)
          left join tdf_temps_difference using(n_coureur,annee)
          left join tdf_parti_coureur using(n_coureur, annee)
          where n_coureur not in(
            select n_coureur from tdf_abandon where annee=2002
            )
            and annee=2002 and valide='R'
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

SELECT RANG, NOM, PRENOM, '+ '||floor("TEMPS TOTAL"/3600)||'h '||floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )||'min '||
       floor (("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 ) - (floor(   ("TEMPS TOTAL" - (floor("TEMPS TOTAL" / 3600)*3600 )) /60  )*60)))||'s' as "TEMPS TOTAL", "RADIÉ" FROM(
  SELECT rownum as RANG, NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
    SELECT NOM, PRENOM, "TEMPS TOTAL", "RADIÉ" FROM(
      SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", ' ' as "RADIÉ" FROM(
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
                WHERE annee = 2002
                AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2002)
                GROUP BY difference, n_coureur, annee)
              ))
          as temps_total, valide from tdf_coureur
          left join tdf_temps using(n_coureur)
          left join tdf_temps_difference using(n_coureur,annee)
          left join tdf_parti_coureur using(n_coureur, annee)
          where n_coureur not in(
            select n_coureur from tdf_abandon where annee=2002
            )
            and annee=2002 and valide='O'
            group by (n_coureur,nom,prenom,difference,valide)
          )
          order by temps_total
        )
       )
      )
      
      
      UNION
      
      
    SELECT NOM, PRENOM, temps_total as "TEMPS TOTAL", 'Radié' as "RADIÉ" FROM(
       SELECT NOM,PRENOM, temps_total
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
                WHERE annee = 2002
                AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2002)
                GROUP BY difference, n_coureur, annee)
              ))
          as temps_total, valide from tdf_coureur
          left join tdf_temps using(n_coureur)
          left join tdf_temps_difference using(n_coureur,annee)
          left join tdf_parti_coureur using(n_coureur, annee)
          where n_coureur not in(
            select n_coureur from tdf_abandon where annee=2002
            )
            and annee=2002 and valide='R'
            group by (n_coureur,nom,prenom,difference,valide)
          )
          order by temps_total
        )
       )
      )
    ) ORDER BY "TEMPS TOTAL"
  )
)
WHERE RANG > 1;
    
SELECT nom, prenom, sum(total_seconde)+nvl(difference, 0) as temps FROM tdf_temps
LEFT JOIN tdf_coureur USING(n_coureur)
LEFT JOIN tdf_temps_difference USING(n_coureur, annee)
WHERE annee = 2002
AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2002)
GROUP BY nom, prenom, difference
ORDER BY temps;

SELECT * FROM tdf_temps_difference;


floor(temps_total/3600)||'h ' as heures, 
       floor(   (temps_total - (floor(temps_total / 3600)*3600 )) /60  )||'min ' as minutes,
       floor ((temps_total - (floor(temps_total / 3600)*3600 ) - (floor(   (temps_total - (floor(temps_total / 3600)*3600 )) /60  )*60)))||'s' as secondes




--Villes étapes
SELECT count(annee) as "NB ANNÉES DÉPART", ville_d as "VILLE DÉPART" FROM tdf_etape
GROUP BY ville_d;

SELECT  count(annee) as "NB ANNÉES ARRIVEÉ", ville_a as "VILLE D'ARRIVÉE" FROM tdf_etape
GROUP BY ville_a;

--Nations participantes

SELECT count(distinct annee) as "NB ANNÉES", nom as "PAYS" FROM tdf_parti_coureur
					JOIN tdf_app_nation USING(n_coureur)
					JOIN tdf_nation USING(CODE_CIO)
					GROUP BY nom;
          

SELECT count(distinct CONCAT(annee_d, annee_a)) as "NB ANNÉES", nom as "PAYS" FROM(         
  SELECT to_char(annee) as annee_d, nom, '' as annee_a FROM tdf_etape et
  JOIN tdf_nation na ON na.CODE_CIO = et.CODE_CIO_D
  
  UNION
  
  SELECT '' as annee_d, nom, to_char(annee) as annee_a FROM tdf_etape et
  JOIN tdf_nation na ON na.CODE_CIO = et.CODE_CIO_A
)
GROUP BY nom;













SELECT n_directeur, nom, prenom FROM tdf_directeur ORDER BY n_directeur;


                            
                            
SELECT spons.nom as equipe, dir.nom||' '||dir.prenom as directeur, codir.nom||' '||codir.prenom as codirecteur, annee FROM tdf_parti_equipe
			JOIN tdf_directeur dir ON n_pre_directeur = dir.n_directeur
			JOIN tdf_directeur codir ON n_co_directeur = codir.n_directeur
			JOIN tdf_sponsor spons USING(n_equipe, n_sponsor)
            ORDER BY annee, equipe;



SELECT n_dossard, nom, prenom, n_equipe FROM tdf_coureur 
			JOIN tdf_parti_coureur USING (n_coureur)
			WHERE annee = 2018
			order by n_dossard;

SELECT nvl(max(n_dossard), 0)+1 FROM tdf_parti_coureur WHERE annee = 2019;
            
SELECT * FROM tdf_parti_equipe WHERE annee = 2018;

SELECT * FROM tdf_parti_equipe WHERE annee = 2018 and (n_co_directeur = 75 OR n_pre_directeur = 75);

SELECT n_equipe, max(n_sponsor) as spons, nom
			FROM tdf_parti_equipe eq
			JOIN tdf_sponsor USING(n_equipe, n_sponsor)
			WHERE annee = 2018
            GROUP BY n_equipe, nom
            ORDER BY nom;
            
select * from tdf_coureur 
JOIN tdf_parti_coureur USING(n_coureur)
WHERE annee = 2018
AND n_equipe = 78;
            
            
            
            
            
SELECT * FROM        
(SELECT N_EQUIPE,spons.nom as equipe,to_char(dir.nom||' '||dir.prenom) as directeur,to_char(codir.nom||' '||codir.prenom) as codirecteur,annee FROM tdf_parti_equipe
			JOIN tdf_directeur dir ON n_pre_directeur = dir.n_directeur
			JOIN tdf_directeur codir ON n_co_directeur = codir.n_directeur
			JOIN tdf_sponsor spons USING(n_equipe, n_sponsor)
			UNION
			SELECT N_EQUIPE,spons.nom as equipe,' ' as directeur,' ' as codirecteur,annee FROM tdf_parti_equipe
			JOIN tdf_sponsor spons USING(n_equipe, n_sponsor)
			WHERE n_pre_directeur IS NULL OR n_co_directeur IS NULL)
      WHERE annee LIKE '2018'
      ORDER BY annee, equipe;
            
SELECT * FROM tdf_parti_coureur WHERE annee = 2018 ORDER BY n_dossard;
SELECT (ceil(nvl(max(n_dossard),0)/10))*10+1 FROM tdf_parti_coureur WHERE annee = 2018;
            
            
SELECT * FROM tdf_parti_coureur;



SELECT * FROM tdf_temps WHERE n_coureur = 1794;



SELECT n_dossard, c.nom, prenom, sp.nom, valide, jeune FROM tdf_parti_coureur
JOIN tdf_coureur c USING(n_coureur)
JOIN tdf_sponsor sp USING(n_equipe, n_sponsor)
WHERE annee = 2018
ORDER BY n_dossard;

SELECT n_equipe, max(n_sponsor), nom FROM tdf_sponsor GROUP BY n_equipe, nom ORDER BY nom;

commit;


SELECT n_epreuve, annee, count(jour) FROM tdf_etape
HAVING count(jour) >= 2
GROUP BY n_epreuve, annee;

SELECT n_epreuve, annee, count(jour) as compte FROM tdf_etape
GROUP BY n_epreuve, annee
ORDER BY compte ASC;


SELECT * FROM tdf_etape WHERE annee = 2018 ORDER BY n_epreuve;

SELECT * FROM
      (SELECT n_coureur, n_dossard, tdf_coureur.nom, prenom, tdf_sponsor.nom as "NOM SPONSOR", ' ' as "ABANDON" FROM tdf_parti_coureur
      JOIN tdf_coureur USING(n_coureur)
      JOIN tdf_sponsor USING(n_sponsor, n_equipe)
      WHERE annee = 2018
      AND n_coureur NOT IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018)


      UNION

      SELECT n_coureur, n_dossard, tdf_coureur.nom, prenom, tdf_sponsor.nom as "NOM SPONSOR", libelle||' ('||tdf_abandon.commentaire||')' as "ABANDON" FROM tdf_parti_coureur
      JOIN tdf_coureur USING(n_coureur)
      JOIN tdf_sponsor USING(n_sponsor, n_equipe)
      JOIN tdf_abandon USING(n_coureur, annee)
      JOIN tdf_typeaban USING(c_typeaban)
      WHERE annee = 2018
      AND n_coureur IN (SELECT n_coureur FROM tdf_abandon WHERE annee = 2018))
      ORDER BY n_dossard;



Affichages :
Classement général par année
Étapes + gagnants par année
Participants + sponsos par année
Palamarès coureur
