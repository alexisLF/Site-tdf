--La projection et la restriction (lire le cours de 1.1 � 1.2 et tester les exemples)
--1) �tablir la liste des �preuves dont le n� est compris entre 5 et 10. Afficher le n� de l'�preuve, la ville d�part,
--la ville arriv�e et la distance.
SELECT n_epreuve, ville_d, ville_a, distance 
FROM prof.vt_epreuve 
WHERE n_epreuve >= 5 AND n_epreuve <= 10;
--2) M�me requ�te que pr�c�demment mais pour l'ann�e 2017.
SELECT n_epreuve, ville_d, ville_a, distance 
FROM prof.vt_epreuve 
WHERE annee = 2017 
AND (n_epreuve >= 5 AND n_epreuve <= 10);
--3) Afficher la liste des �preuves dont le n� est inf�rieur � 5 ou sup�rieur � 10 pour l'ann�e 2017 (2 solutions).
SELECT * 
FROM prof.vt_epreuve 
WHERE (n_epreuve < 5 OR n_epreuve > 10) 
AND annee = 2017;
--4) �tablir la liste des �preuves "prologue" (relire 1_Pres_TDF_2017.pdf). Afficher le code pays d�part, le code
--pays arriv�e, la ville d�part, la ville arriv�e, la distance, la vitesse moyenne, ann�e et le type d'�preuve. La liste
--affich�e sera pr�sent�e par ordre croissant de la distance.
SELECT code_cio_d, code_cio_a, ville_d, ville_a, distance, moyenne, annee, cat_code 
FROM prof.vt_epreuve 
WHERE cat_code = 'PRO' 
ORDER BY distance DESC;
--5) Afficher les �preuves r�pondant � l'une ou l'autre des restrictions suivantes (une seule requ�te) :
--� le premier caract�re de la ville de d�part est un 'B',
--� le dernier caract�re de la ville de d�part est un 'A',
--� la ville de d�part contient un 'U'.
SELECT * 
FROM prof.vt_epreuve 
WHERE ville_d like 'B%' 
OR ville_d like '%A' 
OR ville_d like '%U%';
--6) Afficher l'�preuve courue le 14 juillet 2017
SELECT *
FROM prof.vt_epreuve
WHERE jour = '14/07/2017';
--7) Donner la liste des �preuves dont la moyenne est vide pour une distance inf�rieure � 100 Km pour toutes les
--ann�es.
SELECT *
FROM prof.vt_epreuve
WHERE distance < 100
AND moyenne IS NULL;
--8) Afficher par ordre alphab�tique croissant des pr�noms et par nom des coureurs d�croissant, la liste des
--coureurs dont le nom commence par un 'V'.
SELECT *
FROM prof.vt_coureur
WHERE nom like 'V%'
ORDER BY prenom ASC, nom DESC;
--9) Afficher la liste des nations des coureurs (app_nation) dont le pays d'origine a pour code : "SUI", "JAP" ou
--"POL".
SELECT * 
FROM prof.vt_app_nation
WHERE code_cio = 'SUI'
OR code_cio = 'JAP'
OR code_cio = 'POL';





--La jointure (lire le cours chap 1.3)
--10) Donner la liste alphab�tique des coureurs ayant particip� au Tour 2017. Afficher le nom, le pr�nom, le
--num�ro de dossard et le n�de l'�quipe. Utiliser au moins 2 m�thodes pour effectuer la jointure.
SELECT nom, prenom, n_equipe, n_dossard
FROM prof.vt_coureur
NATURAL JOIN prof.vt_parti_coureur
WHERE annee = 2017
ORDER BY nom ASC;

SELECT nom, prenom, n_equipe, n_dossard
FROM prof.vt_coureur
JOIN prof.vt_parti_coureur USING(n_coureur) 
WHERE annee = 2017
ORDER BY nom ASC;
--10bis) M�me requ�te que pr�c�demment mais pour les dossards compris entre 1 et 9.
SELECT nom, prenom, n_equipe, n_dossard
FROM prof.vt_coureur
NATURAL JOIN prof.vt_parti_coureur
WHERE annee = 2017
AND n_dossard BETWEEN 1 AND 9
ORDER BY nom ASC;
--10ter) (un peu difficile). M�me requ�te que pr�c�demment mais en projetant en compl�ment le nom du sponsor. 
SELECT cour.nom, cour.prenom, partCour.n_dossard, spons.nom, n_equipe
FROM prof.vt_coureur cour
JOIN prof.vt_parti_coureur partCour USING(n_coureur)
JOIN prof.vt_sponsor spons USING(n_equipe, n_sponsor)
WHERE partCour.annee = 2017
AND partCour.n_dossard BETWEEN 1 AND 9
ORDER BY cour.nom;
--ORDER BY prof.vt_coureur.nom ASC;
--Exoplus N�1 : les jointures : cet exercice est � t�l�charger depuis commun

--11) Donner la liste des coureurs consid�r�s comme jeune (voir 1-Pres_TDF_2017) pour le Tour 2017. Afficher le
--nom, le pr�nom et le nom du sponsor class�s par ordre alphab�tique sur le nom du sponsor et sur le nom du
--coureur.
SELECT cour.nom, cour.prenom, spons.nom
FROM prof.vt_coureur cour
JOIN prof.vt_parti_coureur partCour USING(n_coureur)
JOIN prof.vt_sponsor spons USING(n_equipe, n_sponsor)
WHERE partCour.jeune = 'o'
AND partCour.annee = 2017
ORDER BY spons.nom ASC, cour.nom ASC;
--12) Donner la liste des coureurs dont les num�ros de dossard sont compris entre 25 et 27. Afficher le nom, le
--pr�nom, le n� d��quipe, le n� de sponsor et l�ann�e du Tour de France. Le r�sultat sera class� sur l�ann�e du Tour.
SELECT cour.nom, cour.prenom, n_equipe, n_sponsor, partCour.annee
FROM prof.vt_coureur cour
JOIN prof.vt_parti_coureur partCour USING(n_coureur)
JOIN prof.vt_sponsor spons USING(n_equipe, n_sponsor)
WHERE partCour.n_dossard BETWEEN 25 AND 27
ORDER BY partCour.annee ASC;
--13) Donner la liste alphab�tique, class�e sur le nom et le pr�nom, des coureurs ayant des homonymes.
--Aide : les coureurs ont le m�me nom mais pas le m�me n� de coureur. La diff�rence se fait sur une valeur
--diff�rente pour chacun des coureurs. Ces requ�tes se nomment des auto-jointures.
SELECT distinct cour1.n_coureur, cour1.nom, cour1.prenom
FROM prof.vt_coureur cour1, prof.vt_coureur cour2
WHERE cour1.nom = cour2.nom
AND cour1.n_coureur <> cour2.n_coureur
order by cour1.NOM asc, cour1.prenom asc;
--Exoplus N�2 : les auto-jointures : cet exercice est � t�l�charger depuis commun

--14) Donner la liste des �preuves dont la ville d'arriv�e pour diff�rentes �preuves est la m�me. Afficher le n�
--�preuve, la ville d�part, la ville arriv�e et l'ann�e du Tour de France.
select distinct e1.n_epreuve, e1.ville_a, e1.ville_d, e1.annee 
from prof.vt_epreuve e1, prof.vt_epreuve e2
where e1.ville_a = e2.ville_a and (e1.annee <> e2.annee or e1.n_epreuve <> e2.n_epreuve)
order by ville_a asc;
--14bis) M�me question mais sans afficher l'ann�e.
--Pourquoi perd-on des lignes ?
select distinct e1.n_epreuve, e1.ville_a, e1.ville_d
from prof.vt_epreuve e1, prof.vt_epreuve e2
where e1.ville_a = e2.ville_a and (e1.annee <> e2.annee or e1.n_epreuve <> e2.n_epreuve)
order by ville_a asc;
--15) Donner la liste des diff�rents types d'abandon, m�me les types pour lesquels il n'existe aucun abandon. Ne
--pas utiliser join using. Afficher type_aban de vt_abandon et de vt_typeaban ainsi que le libell� de
--vt_typeaban.
--Commentaire : �crire les deux versions de cette jointure.
SELECT distinct ab.c_typeaban, tab.c_typeaban, tab.libelle
FROM prof.vt_typeaban tab
LEFT JOIN prof.vt_abandon ab on tab.c_typeaban = ab.c_typeaban;

SELECT distinct ab.c_typeaban, tab.c_typeaban, tab.libelle
FROM prof.vt_abandon ab
RIGHT JOIN prof.vt_typeaban tab on ab.c_typeaban = tab.c_typeaban;


--15 bis) Difficile : Donner la liste des coureurs des �quipes "fdj", "Tinkoff" et " Movistar Team " ayant abandonn�
--dans le Tour 2017. Afficher le nom, le pr�nom, le type d'abandon et les directeurs d'�quipe. V�rifier l�ex�cution
--de cette requ�te avec deux autres ann�es.
--Les tables concern�es sont:
--� vt_abandons
--� vt_coureur
--� vt_parti_coureur
--� vt_sponsor
--� vt_parti_equipe
--� vt_directeur (2 fois)
SELECT distinct cour.nom, cour.prenom, ab.c_typeaban, dir1.nom, dir1.prenom
FROM 
prof.vt_parti_coureur partCour, 
prof.vt_coureur cour,
prof.vt_sponsor spons,
prof.vt_parti_equipe equipe,
prof.vt_abandon ab,
prof.vt_directeur dir1,
prof.vt_directeur dir2
WHERE partCour.n_coureur = cour.n_coureur
AND partCour.n_equipe = spons.n_equipe AND partCour.n_sponsor = spons.n_sponsor
AND spons.n_equipe = equipe.n_equipe AND spons.n_sponsor = equipe.n_sponsor AND partCour.annee = equipe.annee
AND cour.n_coureur = ab.n_coureur AND partCour.annee = ab.annee
AND equipe.n_pre_directeur = dir1.n_directeur
AND equipe.n_co_directeur = dir2.n_directeur
AND dir1.n_directeur <> dir2.n_directeur
AND equipe.annee = 2017
AND (spons.nom = 'FDJ' OR spons.nom = 'TINKOFF' OR spons.nom = 'MOVISTAR TEAM');


-- Exemple de requ�tes ensemblistes
select nom from prof.vt_coureur
intersect
select prenom from prof.vt_coureur
order by nom ;
--
select nom from prof.vt_coureur
intersect
select upper(prenom) from prof.vt_coureur
order by 1;
--
select ville_d from prof.vt_epreuve
intersect
select to_char(n_epreuve) from prof.vt_epreuve;
--
select ville_d from prof.vt_epreuve
intersect
select n_epreuve from prof.vt_epreuve;
�
select to_char(jour,'DD') as lejour from prof.vt_epreuve
intersect
select to_char(n_epreuve) as lejour from prof.vt_epreuve
order by 1;
--
select * from prof.vt_typeaban
minus
select * from prof.vt_abandon;
--
select nom from prof.vt_coureur
intersect
select jour from prof.vt_epreuve;
--
select nom,prenom from prof.vt_coureur
intersect
select nom from prof.vt_directeur;
--

--16) Afficher le type d'abandon n'ayant aucun abandon correspondant.
SELECT c_typeaban FROM prof.vt_typeaban
MINUS
SELECT c_typeaban FROM prof.vt_abandon;

--16bis) Afficher les villes ayant �t� ville de d�part et d'arriv�e.
SELECT ville_d FROM prof.vt_epreuve
INTERSECT
SELECT ville_a FROM prof.vt_epreuve;

--16 ter) Afficher le type et le libell� d'abandon n'ayant aucun abandon correspondant.
SELECT c_typeaban, libelle 
FROM prof.vt_typeaban
WHERE c_typeaban IN (SELECT c_typeaban FROM prof.vt_typeaban
MINUS
SELECT c_typeaban FROM prof.vt_abandon);

--17) Afficher le n� des coureurs ayant termin� le Tour 2017
SELECT n_coureur FROM prof.vt_parti_coureur
WHERE annee = 2017
MINUS
SELECT n_coureur FROM prof.vt_abandon
WHERE annee = 2017;

--17 bis) Donner la liste des coureurs de nationalit� fran�aise ayant termin� le Tour 2017.
--Afficher toutes les colonnes de "coureur".
SELECT cou.*
FROM prof.vt_coureur cou, prof.vt_app_nation pays
WHERE cou.n_coureur = pays.n_coureur
AND cou.n_coureur IN 
(SELECT n_coureur FROM prof.vt_parti_coureur
WHERE annee = 2017
MINUS
SELECT n_coureur FROM prof.vt_abandon
WHERE annee = 2017)
AND pays.CODE_CIO = 'FRA';

SELECT * FROM prof.vt_nation;

--18) Afficher le n� de coureur pour les coureurs ayant particip� � tous les "Tour de France" depuis 15 ans.
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2017 INTERSECT
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2016 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2015 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2014 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2013 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2012 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2011 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2010 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2009 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2008 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2007 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2006 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2005 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2004 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2003; 


  



--18 bis) Afficher la liste des coureurs pour les coureurs ayant particip� � tous les "Tour de France" depuis 15 ans.
SELECT * FROM (
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2017 INTERSECT
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2016 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2015 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2014 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2013 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2012 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2011 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2010 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2009 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2008 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2007 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2006 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2005 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2004 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2003);

--18 ter) Afficher la liste des coureurs (toutes les colonnes) et l'ann�e de participation pour les coureurs ayant particip� �
--tous les "Tour de France" depuis 15 ans.
SELECT cou.*, par.annee FROM prof.vt_coureur cou, prof.vt_parti_coureur par
WHERE cou.n_coureur = par.n_coureur
AND annee>TO_CHAR(SYSDATE, 'YYYY')-15
AND cou.n_coureur IN
(SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2017 INTERSECT
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2016 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2015 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2014 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2013 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2012 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2011 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2010 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2009 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2008 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2007 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2006 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2005 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2004 INTERSECT 
SELECT n_coureur FROM prof.vt_parti_coureur WHERE annee = 2003);



--19) Difficile Afficher le n� d'�quipe et le n� de sponsor des sponsors class�s dans les 10 premiers mais n'ayant
--jamais particip� au tour(1) ainsi que les sponsors class�s au-del� des 20 premiers et qui ont particip� au tour(2).
--(utiliser vt_ordrequi).
--Conseil :
--� Faites une requ�te correspondant � la premi�re partie de la question(1)
--� Faites une requ�te correspondant � la seconde partie de la question(2)
--� Assembler les deux requ�tes.
(SELECT n_equipe, n_sponsor FROM prof.vt_ordrequi
WHERE numero_ordre <= 10
MINUS
SELECT n_equipe, n_sponsor FROM prof.vt_parti_equipe)
UNION
(SELECT n_equipe, n_sponsor FROM prof.vt_ordrequi
WHERE numero_ordre > 20
INTERSECT
SELECT n_equipe, n_sponsor FROM prof.vt_parti_equipe);



--19 bis) Donner la liste des sponsors (tous les champs) class�s dans les 10 premiers mais n'ayant jamais particip� au tour
--ainsi que les sponsors class�s au-del� des 20 premiers et qui ont particip� au tour
SELECT *
FROM prof.vt_sponsor
WHERE (n_equipe, n_sponsor) IN 
((SELECT n_equipe, n_sponsor FROM prof.vt_ordrequi
WHERE numero_ordre <= 10
MINUS
SELECT n_equipe, n_sponsor FROM prof.vt_parti_equipe)
UNION
(SELECT n_equipe, n_sponsor FROM prof.vt_ordrequi
WHERE numero_ordre > 20
INTERSECT
SELECT n_equipe, n_sponsor FROM prof.vt_parti_equipe));

--Exoplus N�3 : requ�tes ensemblistes


--20) [Important] Donner la liste des �preuves 8 � 12 du Tour 2017 en affichant dans la m�me colonne la
--distance et la moyenne (sur deux lignes diff�rentes). Afficher le n� de l'�preuve, la ville d�part, la ville
--arriv�e, les cha�nes de caract�res "distance" et "moyenne" dans une colonne nomm�e "libell�" et les valeurs
--de distance et de moyenne dans une colonne nomm�e "r�sultat".
SELECT n_epreuve, ville_d, ville_a, 'distance :' as libell�, distance as r�sultat
FROM prof.vt_epreuve
WHERE annee = 2017
AND n_epreuve >= 8
AND n_epreuve <= 12
UNION
SELECT n_epreuve, ville_d, ville_a, 'moyenne :' as libell�, moyenne as r�sultat
FROM prof.vt_epreuve
WHERE annee = 2017
AND n_epreuve >= 8
AND n_epreuve <= 12;


--21) Cr�er une vue "v_aban_25" permettant d'afficher la liste des jeunes coureurs (ann�e, n�_�quipe, n� de
--sponsor, n� de coureur et n� de dossard) ayant abandonn� quelle que soit l'ann�e.
DROP VIEW v_aban_25;
CREATE VIEW v_aban_25 AS
SELECT annee, n_equipe, n_sponsor, n_coureur, n_dossard
FROM prof.vt_parti_coureur
JOIN prof.vt_abandon USING(n_coureur, annee)
WHERE jeune = 'o';



--22)
--a) Afficher la structure de la vue cr��e pr�c�demment
desc v_aban_25;


--b) Lancer l'ex�cution de la vue cr��e pr�c�demment en classant les coureurs sur le n� de coureur
SELECT * FROM v_aban_25 ORDER BY n_coureur;


--c) Afficher la liste des vues cr��es : user_views
SELECT * FROM user_views;
SELECT * FROM user_tables;


--d) Afficher le catalogue des objets cr��s : user_catalog
SELECT * FROM user_catalog;


--e) Renommer la vue cr��e pr�c�demment par "v_aban_jeune"
RENAME v_aban_25 to v_aban_jeune;
RENAME v_aban_jeune to v_aban_25;


--23) En utilisant la vue cr��e pr�c�demment, donner la liste des jeunes coureurs ayant abandonn� le Tour en
--2017. Afficher le nom, le pr�nom, le n� de dossard et le nom de l'�quipe (sponsor).
SELECT v_aban_25.nom, v_aban_25.prenom, v_aban_25.n_dossard, spons.nom
FROM v_aban_25, prof.vt_sponsor spons
WHERE v_aban_25.n_equipe = spons.n_equipe
AND v_aban_25.n_sponsor = spons.n_sponsor
AND annee = 2017;


--24) Sauvegarder les requ�tes 12 et 13 sous forme de vues "v_req12" et v_req13".
DROP VIEW v_req12;
CREATE VIEW v_req12 AS
SELECT cour.nom, cour.prenom, partCour.n_equipe, partCour.n_sponsor, partCour.annee
FROM prof.vt_coureur cour
JOIN prof.vt_parti_coureur partCour ON cour.n_coureur = partCour.n_coureur
WHERE partCour.n_dossard BETWEEN 25 AND 27
ORDER BY partCour.annee ASC;

DROP VIEW v_req13;
CREATE VIEW v_req13 AS
SELECT distinct cour1.n_coureur, cour1.nom, cour1.prenom
FROM prof.vt_coureur cour1, prof.vt_coureur cour2
WHERE cour1.nom = cour2.nom
AND cour1.n_coureur <> cour2.n_coureur
order by cour1.NOM asc, cour1.prenom asc;

--25) R�-ex�cuter les requ�tes 12 et 13, ind�pendamment, en utilisant la vue cr��e pr�c�demment.
SELECT * FROM v_req12;
SELECT * FROM v_req13;



--26) Afficher toutes les colonnes de la vue v_req12 en compl�tant la projection par le nom du sponsor. Le
--r�sultat sera class� sur le nom de l��quipe (sponsor) et le nom du coureur.
SELECT v_req12.nom, prenom, n_equipe, n_sponsor, annee, spons.nom FROM v_req12
JOIN prof.vt_sponsor spons USING(n_equipe, n_sponsor)
ORDER by n_equipe, v_req12.nom;


-- RIP LA QUESTION 27


--28) Afficher la liste des coureurs (tous les champs) n'ayant pas pris le d�part d'un Tour de France (l'absence au
--d�part du Tour est consid�r� comme un abandon)
SELECT *
FROM prof.vt_coureur cou
WHERE n_coureur IN
(
  SELECT n_coureur
  FROM prof.vt_abandon
  WHERE c_typeaban = 'NP'
  AND (annee, n_coureur) NOT IN
  (
    SELECT distinct annee, n_coureur
    FROM prof.vt_temps
  )
);

--Exoplus N�4 : les sous-requ�tes

Drop table Essai4A;
Drop table Essai4B;
create table Essai4A(es4a_num int, es4a_nom char(20));
create table Essai4B(es4b_num int, es4b_ville char(20));
insert into Essai4A values (1,'Robert');
insert into Essai4A values (2,'Antoine');
insert into Essai4A values (2,'Laurent');
insert into Essai4A values (4,'Jean-Yves');
insert into Essai4A values (4,'Philippe');
insert into Essai4A values (4,'Philippe');
insert into Essai4A values (6,'Didier');
insert into Essai4B values (1,'Caen');
insert into Essai4B values (2,'Ifs');
insert into Essai4B values (3,'Troarn');
insert into Essai4B values (4,'Fleury');
commit;

--1) Ex�cuter et commenter la requ�te suivante : justifier les r�sultats.
select * from Essai4A where es4a_num =
(
select es4b_num from Essai4B where es4b_ville='Fleury'
);


--2) Ex�cuter et commenter la requ�te suivante : justifier les r�sultats.
select * from Essai4A where es4a_num =
(
select es4b_num from Essai4B where es4b_ville ='Flers'
);


--3) Ex�cuter et commenter la requ�te suivante : justifier les r�sultats.
select * from Essai4b where es4b_num =
(
select es4a_num from Essai4A where es4a_nom ='Philippe'
);


--4) Ex�cuter et commenter la requ�te suivante : justifier les r�sultats.
select * from Essai4A where
es4a_num = any
(select es4b_num from Essai4B );


--5) Ex�cuter et commenter la requ�te suivante : justifier les r�sultats.
select * from Essai4A where
es4a_num = all
(select es4b_num from Essai4B );


--6) Ex�cuter et commenter la requ�te suivante : justifier les r�sultats.
select * from Essai4A where
es4a_num != any
(
select es4b_num from Essai4B where es4b_ville ='Fleury'
);


--29) Afficher la liste alphab�tique des coureurs pour les ceux arriv�s entre la 1�re et la 20� places dans l'�tape 1
--du Tour 2017 ?
SELECT *
FROM prof.vt_coureur cou
WHERE n_coureur IN
(
SELECT n_coureur
FROM prof.vt_temps
WHERE rang_arrivee BETWEEN 1 AND 20
AND n_epreuve = 1
AND annee = 2017
)
ORDER BY cou.nom;

--30) Afficher la liste des coureurs (nom,pr�nom) et le n� d'�preuve pour ceux ayant gagn� chacune des
--�preuves du tour 2005. Justifier le nombre de r�sultats
SELECT cou.nom, cou.prenom, pays.code_cio, temps.n_epreuve
FROM prof.vt_coureur cou
JOIN prof.vt_app_nation pays ON cou.n_coureur = pays.n_coureur
JOIN prof.vt_temps temps ON cou.n_coureur = temps.n_coureur
WHERE temps.annee = 2005
AND rang_arrivee = 1
ORDER BY n_epreuve;


SELECT cou.nom, cou.prenom, tps.n_epreuve
FROM prof.vt_coureur cou, prof.vt_temps tps
WHERE cou.n_coureur IN
(
SELECT n_coureur
FROM prof.vt_temps tps2
WHERE annee = 2005
AND rang_arrivee = 1
AND tps2.n_coureur = tps.n_coureur
AND tps2.annee = tps.annee
AND tps2.n_epreuve = tps.n_epreuve
)
ORDER BY tps.n_epreuve;


SELECT * FROM prof.vt_epreuve WHERE annee = 2005;
--30 bis) M�me question mais, on demande de projeter le nom des sponsors uniquement.
SELECT nom
FROM prof.vt_sponsor
WHERE (n_equipe, n_sponsor) IN
(
  SELECT n_equipe, n_sponsor
  FROM prof.vt_parti_coureur
  WHERE (n_coureur, annee) IN
  (
    SELECT n_coureur, annee
    FROM prof.vt_temps
    WHERE annee = 2005
    AND rang_arrivee = 1
  )
);


--31) Afficher le sponsor dont le coureur a remport� l'�preuve dont la ville de d�part est {PARIS | GAP |
--PORNIC}
--La requ�te devra contenir 3 sous-requ�tes imbriqu�es.

SELECT * FROM prof.vt_sponsor
WHERE (n_sponsor, n_equipe) IN
(
  SELECT distinct n_sponsor, n_equipe FROM prof.vt_parti_coureur
  WHERE (n_coureur, annee) IN
  (
    SELECT n_coureur, annee
    FROM prof.vt_temps
    WHERE rang_arrivee = 1
    AND (annee, n_epreuve) IN
    (
      SELECT annee, n_epreuve
      FROM prof.vt_epreuve
      WHERE ville_d IN ('PARIS', 'GAP', 'PORNIC')
    )
  )
);

--31bis) M�me requ�te que pr�c�demment mais compl�tant la projection par le nom de la ville-d�part. 2 solutions
--sont propos�es. Les commenter.

-- Solution 1
select distinct spo.*,ville_d from prof.vt_sponsor spo, prof.vt_epreuve epr1
where (n_equipe,n_sponsor) in
(
select n_equipe, n_sponsor
from prof.vt_parti_coureur
where (annee, n_coureur) in
(
select annee, n_coureur
from prof.vt_temps
where rang_arrivee = 1
and (annee, n_epreuve) in
(
select annee, n_epreuve
from prof.vt_epreuve epr2
where ville_d in ('PARIS', 'GAP', 'PORNIC')
and epr1.annee= epr2.annee and epr1.n_epreuve = epr2.n_epreuve
--and epr1.ville_d = ville_d
)
)
);
-- Solution 2
select distinct spo.*, ville_d from prof.vt_sponsor spo, prof.vt_epreuve epr
where (epr.annee, epr.n_epreuve, spo.n_equipe, spo.n_sponsor ) in
(
select annee, epr.n_epreuve, n_equipe, n_sponsor
from prof.vt_parti_coureur
where (annee, n_coureur, epr.n_epreuve) in
(
select annee, n_coureur, n_epreuve
from prof.vt_temps
where rang_arrivee = 1
and (annee, n_epreuve) in
(
select annee, n_epreuve
from prof.vt_epreuve
where ville_d in ('PARIS', 'GAP', 'PORNIC')
)
)
);
--32) Afficher les �preuves du Tour 2017 dont la distance est la plus longue (pas de fonction d'agr�gat).

SELECT *
FROM prof.vt_epreuve
WHERE annee = 2017
AND distance >= all
(
  SELECT distance
  FROM prof.vt_epreuve
  WHERE annee = 2017
);


--33) Afficher les �preuves de plus faible moyenne non nulle en 2017. (pas de fonction d'agr�gat)

SELECT *
FROM prof.vt_epreuve
WHERE annee = 2017
AND moyenne > 0
AND moyenne <= all
(
  SELECT moyenne
  FROM prof.vt_epreuve
  WHERE annee = 2017
);

--Exoplus N�5 : dual et les dates

-- Ex�cuter et commenter les requ�tes suivantes :
desc dual;
-- dummy = factice
--1.1
select 1 un,2,3,4 from dual;
--1.2
select user from dual;
--1.3
select ' create user ETU1_'||level ||';' valeur from dual connect by level <= 90;
--1.4
select 'chateau' exemple from dual;
--1.5
select 'Monsieur ' || nom exemple from prof.vt_coureur;
--1.6
select 'chat'||'eau' exemple from dual;
select prenom||' '||nom exemple from prof.vt_coureur;
--1.7
select greatest(100,200,300) grand, least(100,200,300) petit from dual;
--1.8
select 1 + 3 from dual;
--1.9
select 1 + 3 as resultat from dual;
--1.10
select 1 - 3 as r�sultat from dual;
--1.11
select 1 / 0 as r�sultat from dual;
--1.12
select annee,ville_d,ville_a,moyenne from prof.vt_epreuve where moyenne is null;
select annee,ville_d,ville_a,nvl(moyenne,0) from prof.vt_epreuve where moyenne is null;
select annee,ville_d,ville_a,nvl(to_char(moyenne),'INCONNUE') from prof.vt_epreuve where moyenne is null;
select 10 +10+10+ null as r�sultat from dual;
select 10+10+10 + nvl(null,9) as r�sultat from dual;
select annee,ville_d,ville_a,moyenne from prof.vt_epreuve where moyenne is null;
select annee,ville_d,ville_a,nvl(to_char(moyenne),'inconnue') as moy
from prof.vt_epreuve where moyenne is null;
select annee,ville_d,ville_a,nvl(to_char(moyenne),'inconnue') as moy
from prof.vt_epreuve where annee=1996;
2) Le type date
--2.1.
select sysdate+10.1 from dual;
select to_char(sysdate+10.1,'dd/mm/yyyy hh24:mi:ss') from dual;
select to_date('2010','yyyy') from dual;
select to_char(sysdate,'dd/mm/yyyy HH24:mi:ss') from dual;
select to_char(sysdate,'dd/mm/yy') from dual;
select to_char(sysdate,'dd') from dual;
--2.2
select to_char(jour,'dd/mm/yyyy HH:mi:ss') from prof.vt_epreuve;
select jour from prof.vt_epreuve ;
--2.3
select to_char(jour,'YYYY') +1 from prof.vt_epreuve;
--2.4
select to_date('15/10/1985','DD/MM/YYYY') from dual;
select to_date('10-15-1985','MM-DD-YYYY') from dual;
select to_char(to_date('10-15-1985','MM-DD-YYYY'),'dd/MM/YYYY') from dual;
select to_date('2005','yyyy') from dual;
select sysdate - to_char(jour,'dd/mm/yyyy') from prof.vt_epreuve ;
select sysdate - jour from prof.vt_epreuve where annee=2016;
select sysdate - to_date('20/10/2001','dd/mm/yyyy') from dual ;
select round(( sysdate - to_date('20/10/2001','dd/mm/yyyy'))/365,3) as age from dual ;
select to_char(sysdate,'year') from dual ;
select to_char(sysdate,'month') from dual ;
select to_char(sysdate,'day') from dual ;
--2.5
select jour from prof.vt_epreuve where n_epreuve=1 and annee=2016;
--2.6
select jour from prof.vt_epreuve where n_epreuve=21 and annee=2016;
--3) Requ�tes sp�ciales
select
(
(select jour from prof.vt_epreuve where n_epreuve=20 and annee=2009)
-
(select jour from prof.vt_epreuve where n_epreuve=1 and annee=2009)
)
as nbJours from dual;

select
(
(select to_number(to_char(jour,'DD')) from prof.vt_epreuve where n_epreuve=20 and annee=2009)
-
(select to_number(to_char(jour,'DD')) from prof.vt_epreuve where n_epreuve=1 and annee=2009)
)
as nbJours from dual;

--34) Calculer la dur�e en jours du Tour de France 2017 en prenant en compte la date de la premi�re �preuve et
--la date de la derni�re �preuve du Tour 2017.
--Note : select jour1 - jour2 from �

SELECT
(
  (
    SELECT jour FROM prof.vt_epreuve WHERE annee = 2017 AND n_epreuve >=all
    (
      SELECT n_epreuve FROM prof.vt_epreuve WHERE annee = 2017
    )
  )
  -
  (
    SELECT jour FROM prof.vt_epreuve WHERE annee = 2017 AND n_epreuve <=all
    (
      SELECT n_epreuve FROM prof.vt_epreuve WHERE annee = 2017
    )
  )
  +
  1
) as nbJours FROM dual;

--34 bis) Afficher le nombre de jours courus dans le Tour 2017.

SELECT
(
  (
    SELECT jour FROM prof.vt_epreuve WHERE annee = 2017 AND n_epreuve >=all
    (
      SELECT n_epreuve FROM prof.vt_epreuve WHERE annee = 2017
    )
  )
  -
  (
    SELECT jour FROM prof.vt_epreuve WHERE annee = 2017 AND n_epreuve <=all
    (
      SELECT n_epreuve FROM prof.vt_epreuve WHERE annee = 2017
    )
  )
  +
  1
  -
  (
    SELECT jour_repos FROM prof.vt_annee WHERE annee = 2017
  )
) as nbJours FROM dual;

--35) Difficile Donner la liste des coureurs arriv�s premiers � une �preuve en 2017. Le pays d'origine du coureur
--doit �tre le m�me que celui de la ville de d�part de l'�preuve o� le coureur a gagn�. Afficher les caract�ristiques
--de "coureur". Traiter la r�ponse sous la forme d�une requ�te principale et de 2 sous-requ�tes imbriqu�es.
--Expliquer pourquoi la synchronisation est obligatoire.

SELECT *
FROM prof.vt_coureur co
WHERE EXISTS
(
  SELECT code_cio
  FROM prof.vt_app_nation nat
  WHERE n_coureur = co.n_coureur
  AND n_coureur IN
  (
    SELECT n_coureur
    FROM prof.vt_temps
    WHERE annee = 2017
    AND rang_arrivee = 1
    AND (annee, n_epreuve) IN
    (
      SELECT annee, n_epreuve
      FROM prof.vt_epreuve
      WHERE code_cio_d = nat.code_cio
    )
  )
);

--35bis) Donner la liste des coureurs ayant gagn� au moins une �preuve en 2017. Utiliser une requ�te
--synchronis�e.

SELECT *
FROM prof.vt_coureur co
WHERE EXISTS
(
  SELECT 1
  FROM prof.vt_temps
  WHERE rang_arrivee = 1
  AND annee = 2017
  AND n_coureur = co.n_coureur
);

--35ter Afficher les 5 premiers coureurs par liste alphab�tique invers�e des noms (aucun rapport avec les requ�tes
--synchronis�es).

SELECT * FROM (SELECT * FROM prof.vt_coureur ORDER BY nom DESC)
WHERE rownum <=5;




--36a) Pour chaque �preuve de 2017, afficher le num�ro, la distance et le type d'�preuve en clair sachant que :
--� PRO =Prologue
--� CMI =Contre la montre individuel
--� CME =Contre la montre par �quipe
--� ETA =Etape en ligne

SELECT n_epreuve, distance, decode(cat_code, 'PRO', 'Prologue', 'CMI', 'Contre la montre individuel', 'CME', 'Contre la montre par �quipe', 'ETA', '�tape en ligne') as Type
FROM prof.vt_epreuve
WHERE annee = 2017;


--36b) en utilisant la fonction dump, afficher les pr�noms des coureurs contenant des caract�res comme '�' ou '�'
--ou '�' ou '�'.

select  distinct prenom from prof.vt_coureur where prenom LIKE '%�%' OR prenom LIKE '%�%' OR prenom LIKE '%�%' OR prenom LIKE '%�%';

select dump(prenom,17) from prof.vt_coureur where n_coureur<100;

--36c) Afficher les �preuves disput�es en dehors de juillet.

SELECT * FROM prof.vt_epreuve
WHERE to_char(jour, 'MM') != '07';

--Le groupement des donn�es et les fonctions d'agr�gat (lire le cours chap 1.8)



--Exoplus N�6 : le groupement des donn�es


Select max(total_seconde) as maxi, min(total_seconde) as mini,
round(avg(total_seconde),4) as moyenne from prof.vt_temps;


Select sum(total_seconde) as somme,count(total_seconde) as nombre from prof.vt_temps;



Select sum(total_seconde) as somme,count(*) as nombre from prof.vt_temps;


select n_coureur from prof.vt_parti_coureur order by n_coureur;


select count(*) from prof.vt_parti_coureur ;


select count(*),n_coureur from prof.vt_parti_coureur order by n_coureur;


select n_coureur, count(*) as nb from prof.vt_parti_coureur
group by n_coureur
order by nb desc;


select n_coureur,n_equipe,count(*) as nb
from prof.vt_parti_coureur
group by n_coureur
order by nb,n_equipe desc;


select n_coureur,n_equipe,count(*) as nb_tours from prof.vt_parti_coureur
group by n_equipe ,n_coureur
order by 1,2 desc;


select n_coureur,n_equipe,count(*) as nb_par from prof.vt_parti_coureur
group by n_coureur,n_equipe
where count(*) > 12
order by n_coureur;


select n_coureur,n_equipe,count(*) as nb_par from prof.vt_parti_coureur
group by n_coureur,n_equipe
where nb_par > 12
order by n_coureur;


select n_coureur from prof.vt_parti_coureur
group by n_coureur
having count(*) >12
order by n_coureur;


select n_coureur from prof.vt_parti_coureur
group by n_coureur
having count(*) = 17;


select n_coureur from prof.vt_parti_coureur
group by n_coureur
having count(*) =
(
select max(count(n_coureur)) from prof.vt_parti_coureur
group by n_coureur
);


-- HORS EVALUATION
select annee, cat_code, count(*) as nb_km from prof.vt_epreuve
group by rollup (annee,cat_code)
order by annee desc,nb_km desc;


select annee, cat_code, count(*) as nb_km from prof.vt_epreuve
group by cube (annee,cat_code)
order by annee desc,nb_km desc;



--37)
--a1) Nombre total de coureurs dans la base de donn�es

SELECT count(*) as nbCoueur FROM prof.vt_coureur;


--a2) On veut le m�me nombre de r�ponses que pr�c�demment mais � partir de l�objet " parti_coureur "

SELECT count(*) as nbCoueur FROM prof.vt_parti_coureur group by n_coureur;


--b) Afficher le nombre de coureurs ayant termin� le Tour 2017 (ceux qui n�ont pas abandonn� par exemple)

select count(*) from prof.vt_parti_coureur where annee=2017 and
(n_coureur, annee) NOT IN
(
  SELECT n_coureur, annee FROM prof.vt_abandon
);
--c) Donner le temps maximum, le temps minimum et le temps moyen de la premi�re �preuve de 2017.
--Arrondir pour la moyenne.

select min(total_seconde), max(total_seconde),round(avg(total_seconde),2) from prof.vt_temps where annee = 2017 and n_epreuve = 1; 


--d) Afficher pour chacun des Tours : l'ann�e, le dernier jour, le 1er jour, le nombre de jours entre le dernier
--jour et le 1er jour, le nombre d'�preuves, le nombre de jours de repos .

SELECT annee, max(jour) as maxou, min(jour) as minn, max(n_epreuve), jour_repos
FROM prof.vt_epreuve
JOIN prof.vt_annee USING(annee)
GROUP BY annee, jour_repos;


--e) Projeter en heures, le temps maximum et le temps minimum pass� sur un v�lo dans une �preuve du Tour
--2017.


SELECT heure, minute, seconde FROM prof.vt_temps
WHERE annee = 2017
AND total_seconde >= all
  (SELECT total_seconde FROM prof.vt_temps WHERE annee = 2017);


--f) Nombre de types distincts d'abandons constat�s

SELECT count(distinct c_typeaban) FROM prof.vt_typeaban ty
JOIN prof.vt_abandon ab USING(c_typeaban);

--g) Afficher l'ann�e, le n� d'�preuve, le type d'abandon et le nombre d�abandons par type pour l�ann�e 2017.
--Le r�sultat projet� sera class� par ordre croissant sur le num�ro d'�preuve.

select annee, n_epreuve, c_typeaban, count(*) as Nbaban
from prof.vt_abandon
where annee = 2017
group by annee, n_epreuve, c_typeaban
order by n_epreuve;


--h) Afficher les types d�abandon, le nombre d�abandons par type, le total des abandons pour l�ann�e 2017
--� Solution 1 :
--? Cr�er une vue pour obtenir le nombre total d'abandons,
--? Cr�er une vue pour obtenir le nombre d'abandons par type,
--? �crire la requ�te utilisant les 2 vues

CREATE OR REPLACE VIEW vue_37_h_1 AS
SELECT count(*) as taRaceZ FROM prof.vt_abandon
WHERE annee = 2017;

CREATE OR REPLACE VIEW vue_37_h_2 AS
SELECT c_typeaban, count(*) as taRace FROM prof.vt_abandon
WHERE annee = 2017
group by c_typeaban;

SELECT distinct v2.c_typeaban, v2.taRace, v1.taRaceZ FROM prof.vt_abandon ab, vue_37_h_1 v1, vue_37_h_2 v2;

--� Solution 2 :
--? �crire la requ�te calculant le nombre d'abandons par type et r�utilisant la vue pour obtenir le
--nombre d'abandons total

SELECT c_typeaban, count(*) as taRace, v1.taRaceZ FROM prof.vt_abandon ab, vue_37_h_1 v1
WHERE annee = 2017
GROUP BY c_typeaban, v1.taRaceZ;



--� Solution 3 :
--? A la place du nom de la vue, placer la requ�te la composant

SELECT c_typeaban, count(*) as taRace, taRaceZ FROM prof.vt_abandon ab, (SELECT count(*) as taRaceZ FROM prof.vt_abandon
WHERE annee = 2017)
WHERE annee = 2017
GROUP BY c_typeaban, taRaceZ;



--� Solution 4 :
--? A la place du nom de la colonne, placer la requ�te la composant

SELECT c_typeaban, count(*) as taRace, (SELECT count(*) as taRaceZ FROM prof.vt_abandon
WHERE annee = 2017) as salut FROM prof.vt_abandon ab
WHERE annee = 2017
GROUP BY c_typeaban;


--� Solution 5 :
--? Requ�te analytique (voir solution en TD)


--� Solution 6 :
--? Pour cette solution, le total doit �tre plac� en bas.


--i) R�utiliser une des solutions pr�c�dentes pour afficher en plus le pourcentage d'abandons par type

SELECT distinct v2.c_typeaban, v2.taRace, v1.taRaceZ, round((v2.taRace/v1.taRaceZ)*100, 0) as pourcentage FROM prof.vt_abandon ab, vue_37_h_1 v1, vue_37_h_2 v2;


--38)
--a1) Afficher les noms et pr�noms des coureurs avec leur nombre de participations pour ceux ayant
--particip� plus de 10 fois au tour. Trier par ordre d�croissant de participations.

select nom, prenom, count(n_coureur) as jesepa from prof.vt_parti_coureur
JOIN prof.vt_coureur USING(n_coureur)
having count(n_coureur) > 10
GROUP BY nom, prenom
ORDER BY jesepa DESC;



--a2) Afficher les noms et pr�noms des coureurs poss�dant le record du nombre de victoires d'�tapes au tour
--de France.

select nom, prenom, count(n_coureur) as jesepa from prof.vt_temps
JOIN prof.vt_coureur USING(n_coureur)
WHERE rang_arrivee = 1
HAVING count(n_coureur) >= all (SELECT count(n_coureur) as jesepo from prof.vt_temps WHERE rang_arrivee = 1 GROUP BY n_coureur)
GROUP BY nom, prenom
ORDER BY jesepa DESC;

--b) Donner la liste des coureurs ayant r�alis� pour l�avant-derni�re �preuve du Tour 2017 un temps
--inf�rieur � la moyenne des temps de l�avant-derni�re �preuve du Tour 2017. Afficher les donn�es avec
--le n� de ligne (rownum) � la projection, toutes les caract�ristiques de "coureur" et le temps r�alis�. Le
--r�sultat sera tri� en ordre croissant sur le temps d�arriv�e.

SELECT nom, prenom, annee_naissance, annee_prem, 
  (SELECT round(avg(total_seconde), 2) FROM prof.vt_temps WHERE annee = 2017 AND n_epreuve = (SELECT max(n_epreuve)-1 FROM prof.vt_temps WHERE annee = 2017)) as TG_FDP,
  total_seconde, n_epreuve 
FROM prof.vt_coureur 
JOIN prof.vt_temps USING(n_coureur)
where total_seconde < (SELECT avg(total_seconde) FROM prof.vt_temps WHERE annee = 2017  AND n_epreuve = (SELECT max(n_epreuve)-1 FROM prof.vt_temps WHERE annee = 2017))
AND n_epreuve = (SELECT max(n_epreuve)-1 FROM prof.vt_temps WHERE annee = 2017)
AND annee = 2017
GROUP BY nom, prenom, annee_naissance, annee_prem, total_seconde, n_epreuve;



--c1) Afficher le n� du coureur, le nom, le pr�nom, la somme des � total_seconde � et la diff�rence (colonne
--r�f�renc�e dans l�objet vt_temps_difference contenant l�ensemble des bonifications et p�nalit�s concernant
--certains coureurs pour certaines ann�es) pour les coureurs n�ayant pas abandonn� en 2007.

SELECT distinct n_coureur, nom, prenom, sum(total_seconde) as PKPA, sum(difference)as KK FROM prof.vt_coureur
LEFT JOIN prof.vt_temps_difference USING(n_coureur)
JOIN prof.vt_temps USING(n_coureur)
WHERE n_coureur NOT IN (
  SELECT n_coureur FROM prof.vt_abandon WHERE annee = 2007
  )
AND prof.vt_temps.annee = 2007  
GROUP BY n_coureur, nom, prenom
ORDER BY PKPA ;


--c2) Donner le temps total r�alis� par les coureurs du Tour 2005 n'ayant pas abandonn� (quel que soit le
--type d�abandon). Afficher le n� du coureur, le nom, le pr�nom et le temps total r�alis� en secondes
--(total_seconde + diff�rence) (renommer la colonne par "temps total").
--Option : Difficile. Pour m�riter � vie l'estime de votre enseignant, remplacer les tricheurs par des blancs
--(voir 1-Pres_TDF_2017).

(SELECT distinct REPLACE(n_coureur, n_coureur, n_coureur)as n_coureur, nom, prenom, (sum(total_seconde)+sum(nvl(difference, 0))) as temps_total FROM prof.vt_temps
LEFT JOIN prof.vt_temps_difference USING(n_coureur, annee)
JOIN prof.vt_coureur USING (n_coureur)
JOIN prof.vt_parti_coureur USING(n_coureur, annee)
WHERE annee = 2005
AND VALIDE != 'R'
AND n_coureur NOT IN
(
  SELECT n_coureur FROM prof.vt_abandon
  WHERE annee = 2005
)
GROUP BY n_coureur, nom, prenom)
UNION
(SELECT distinct REPLACE(n_coureur, n_coureur, ' ') as n_coureur, nom, prenom, (sum(total_seconde)+sum(nvl(difference, 0))) as temps_total FROM prof.vt_parti_coureur 
JOIN prof.vt_temps USING (n_coureur, annee)
JOIN prof.vt_coureur USING(n_coureur)
LEFT JOIN prof.vt_temps_difference USING(n_coureur, annee)
WHERE annee = 2005 
AND VALIDE = 'R'
AND n_coureur NOT IN
(
SELECT n_coureur FROM prof.vt_abandon WHERE annee = 2005
)
GROUP BY n_coureur, nom, prenom);



--d1) Donner la liste des sponsors (n_equipe, n_sponsor et nom) et le nombre de coureurs par �quipe ayant
--particip� au Tour 1998. En faire une vue nomm�e v_req38_depart

CREATE OR REPLACE VIEW vue_38_d1 AS
SELECT n_equipe, n_sponsor, nom, count(n_coureur) as AH FROM prof.vt_sponsor
JOIN prof.vt_parti_coureur USING (n_equipe, n_sponsor)
WHERE annee = 1998
GROUP BY  n_equipe, n_sponsor, nom;



--d2) Donner la liste des sponsors (n_equipe, n_sponsor et nom) et le nombre de coureurs par �quipe ayant
--termin� le Tour 1998. En faire une vue nomm�e v_req38_arrivee

CREATE OR REPLACE VIEW vue_38_d2 AS
SELECT n_equipe, n_sponsor, nom, COUNT(n_coureur) AS AH FROM prof.vt_sponsor
JOIN prof.vt_parti_coureur USING (n_equipe, n_sponsor)
WHERE annee = 1998
AND (n_coureur,annee) NOT IN 
(
  SELECT n_coureur, annee FROM prof.vt_abandon
)
GROUP BY  n_equipe, n_sponsor, nom;


--d3) M�me requ�te que pr�c�demment pour les coureurs n'ayant pas termin� le Tour 1998. En faire une vue
--nomm�e v_req38_abandon

CREATE OR REPLACE VIEW vue_38_d3 AS
SELECT n_equipe, n_sponsor, nom, COUNT(n_coureur) AS AH FROM prof.vt_sponsor
JOIN prof.vt_parti_coureur USING (n_equipe, n_sponsor)
WHERE annee = 1998
AND (n_coureur,annee) IN 
(
  SELECT n_coureur, annee FROM prof.vt_abandon
)
GROUP BY  n_equipe, n_sponsor, nom;

--e) Faire l�union des trois requ�tes pr�c�dentes. Bien distinguer les d�parts, les arriv�es et les abandons.

SELECT v1.*, 'D�part' FROM vue_38_d1 v1
UNION
SELECT v2.*, 'Arriv�' FROM vue_38_d2 v2
UNION
SELECT v3.*, 'Abandon' FROM vue_38_d3 v3;


--f) Afficher le nom des sponsors, le nombre de coureurs ayant termin� le Tour 1998, le nombre de coureurs
--ayant abandonn� et le nombre de coureurs au d�part. Utiliser les vues 38 dx.



SELECT n_equipe, n_sponsor, v1.nom, nvl(v1.AH, 0) as "D�part", nvl(v2.AH, 0) as "Arriv�", nvl(v3.AH, 0) as "Abandon" FROM vue_38_d1 v1
LEFT JOIN vue_38_d2 v2 USING(n_equipe, n_sponsor)
LEFT JOIN vue_38_d3 v3 USING(n_equipe, n_sponsor)
GROUP BY n_equipe, n_sponsor, v1.nom, v1.AH, v2.AH, v3.AH;

--g) Quelle(s) sont la ou les �quipes comportant le plus de coureurs � la fin du Tour 1998

SELECT n_equipe, v2.AH as NB_COUREUR_FIN FROM vue_38_d2 v2
WHERE v2.AH IN
(
  SELECT max(v2.AH) FROM vue_38_d2 v2
);

--h) Afficher la liste des sponsors actuels des �quipes encore existantes.


SELECT n_sponsor, n_equipe, annee_sponsor, annee_disparition FROM prof.vt_equipe 
JOIN prof.vt_sponsor VS USING(n_equipe)
WHERE annee_disparition IS NULL
AND (annee_sponsor, n_equipe) IN
(
  SELECT max(annee_sponsor), n_equipe FROM prof.vt_sponsor
  GROUP BY n_equipe
)
ORDER BY n_sponsor, n_equipe;


--i) Difficile Afficher les propri�t�s du dernier sponsor pour les �quipes en ayant eu plus de 6. Afficher
--�galement le nombre de d�nominations que cette �quipe a connue.

SELECT n_equipe, n_sponsor, nom, max(n_sponsor) as NB_DENOM FROM prof.vt_sponsor sp
WHERE (n_sponsor, n_equipe) IN
(
  SELECT max(n_sponsor), n_equipe FROM prof.vt_sponsor
  HAVING max(n_sponsor) > 6
  GROUP BY n_equipe
)
GROUP BY n_equipe, n_sponsor, nom;

--Option : Ne retenir que les �quipes ayant particip� au tour de France.

SELECT n_equipe, n_sponsor, nom, max(n_sponsor) as NB_DENOM FROM prof.vt_sponsor sp
WHERE (n_sponsor, n_equipe) IN
(
  SELECT max(n_sponsor), n_equipe FROM prof.vt_sponsor
  HAVING max(n_sponsor) > 6
  GROUP BY n_equipe
)
AND (n_equipe, n_sponsor) IN
(
  SELECT n_equipe, n_sponsor FROM prof.vt_parti_equipe
)
GROUP BY n_equipe, n_sponsor, nom;


--j) Afficher les �quipes ayant succ�d� � l'�quipe 14 en respectant l�arborescence ci-dessous. (voir cours 1.10
--Les requ�tes hi�rarchiques)

SELECT level, n_equipe, lpad(' ', 4*level-4)||n_successeur as n_successeur
FROM prof.vt_equipe
JOIN prof.vt_equ_succede USING(n_equipe)
start with n_equipe = 14
connect by prior n_successeur = n_equipe
ORDER BY level;

--k) Difficile Afficher le premier sponsor et le dernier sponsor (actuel) des �quipes encore existantes.
--P. Monchy

