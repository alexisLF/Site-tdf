<?php

//---------------------------------------------------------------------------------------------
function OuvrirConnexionPDO()
{


	$db = "oci:dbname=spartacus.iutc3.unicaen.fr:1521/info.iutc3.unicaen.fr;charset=AL32UTF8";
	$db_username = "username";
	$db_password = "password";

	/

	try
	{
		$conn = new PDO($db,$db_username,$db_password);
	}
	catch (PDOException $erreur)
	{
		echo $erreur->getMessage();
	}
	return $conn;
}
//---------------------------------------------------------------------------------------------
function majDonneesPDO($conn,$sql)
{
	$stmt = $conn->exec($sql);
	return $stmt;
}
//---------------------------------------------------------------------------------------------
function preparerRequetePDO($conn,$sql)
{
	$cur = $conn->prepare($sql);
	return $cur;
}
//---------------------------------------------------------------------------------------------
function ajouterParamPDO($cur,$param,$contenu,$type='texte',$taille=0) // fonctionne avec preparerRequete
{
// Sur Oracle, on peut tout passer sans préciser le type. Sur MySQL ???
//	if ($type == 'nombre')
//		$cur->bindParam($param, $contenu, PDO::PARAM_INT);
//	else
		//$cur->bindParam($param, $contenu, PDO::PARAM_STR, $taille);
	$cur->bindParam($param, $contenu);
	return $cur;
}
//---------------------------------------------------------------------------------------------
function majDonneesPrepareesPDO($cur) // fonctionne avec ajouterParam
{
	$res = $cur->execute();
	return $res;
}
//---------------------------------------------------------------------------------------------
function majDonneesPrepareesTabPDO($cur,$tab) // fonctionne directement après preparerRequete
{
	$res = $cur->execute($tab);
	return $res;
}
//---------------------------------------------------------------------------------------------
function LireDonneesPDO1($conn,$sql,&$tab)
{
	$i=0;
	foreach  ($conn->query($sql,PDO::FETCH_ASSOC) as $ligne)     
		$tab[$i++] = $ligne;
	$nbLignes = $i;
	return $nbLignes;
}
//---------------------------------------------------------------------------------------------
function LireDonneesPDO2($conn,$sql,&$tab)
{
	$i=0;
	$cur = $conn->query($sql);
	while ($ligne = $cur->fetch(PDO::FETCH_ASSOC))
		$tab[$i++] = $ligne;
	$nbLignes = $i;
	return $nbLignes;
}
//---------------------------------------------------------------------------------------------
function LireDonneesPDO3($conn,$sql,&$tab)
{
  $cur = $conn->query($sql);
  $tab = $cur->fetchall(PDO::FETCH_ASSOC);
  return count($tab);
}
//---------------------------------------------------------------------------------------------
function LireDonneesPDOPreparee($cur,&$tab)
{
  $res = $cur->execute();
  $tab = $cur->fetchall(PDO::FETCH_ASSOC);
  return count($tab);
}
//---------------------------------------------------------------------------------------------
// fonctions supplementaires
//---------------------------------------------------------------------------------------------
function fabriquerChaineConnexPDO()
{
	$hote = 'localhost';
	$port = '1521'; // port par défaut
	$service = 'xe';

	$db =
	"oci:dbname=(DESCRIPTION =
	(ADDRESS_LIST =
		(ADDRESS =
			(PROTOCOL = TCP)
			(Host = ".$hote .")
			(Port = ".$port."))
	)
	(CONNECT_DATA =
		(SERVICE_NAME = ".$service.")
	)
	)";
	return $db;
}

 ?>
