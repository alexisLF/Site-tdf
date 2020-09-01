<?php
define("MAX_PRENOM", 30 , false);
define("MAX_NOM", 30 , false);
define("CARACTERESAUTORISES", "[a-z]|á|à|â|ä|ȧ|ā|ã|ć|č|é|è|ê|ë|ę|í|î|ï|ī|ł|ñ|ó|ô|ö|ø|õ|ṭ|ú|ù|û|ü|ÿ|ž|ẓ|æ|œ|ç|’|'| |-" , false);
define("CARACTERESAUTORISESVILLES", "[0-9]|[a-z]|á|à|â|ä|ȧ|ā|ã|ć|č|é|è|ê|ë|ę|í|î|ï|ī|ł|ñ|ó|ô|ö|ø|õ|ṭ|ú|ù|û|ü|ÿ|ž|ẓ|æ|œ|ç|’|'| |-" , false);
define("CARACTERESAREMPLACER","[a-z]|ç|á|à|â|ä|ȧ|ā|ã|ć|č|é|è|ê|ë|ę|ł|ñ|ó|ô|ö|ø|õ|ṭ|ú|ù|û|ü|ÿ|ž|ẓ|æ|œ",false);
define("CARACTERESACCENTFRANCAISAUTORISES","à|â|ä|é|è|ê|ë|ï|î|ô|ö|ù|û|ü|ÿ|ç",false);

function verificationApostrophes($str){
	$str = preg_replace('/’/', "'", $str);

	if(preg_match("/''/", $str))
		return 0;

	if(preg_match("/^'-/", $str))
		return 0;

	if(preg_match("/-'$/", $str))
		return 0;

	return $str;
}

function verificationPrenom($str){
	if(verificationCaractereAutorises($str) == 0)
		return 0;
	if(verificationLongeurPrenom($str) == 0)
		return 0;
	$str = suppresionEspaceEncombrant($str);
	$str = verificationRegleTiret($str);
	if(verificationReglesDiversesPrenom($str) == 0)
		return 0;

	$str = verificationApostrophes($str);

	$str = suppresionAccentPrenom($str);
	$str = ajoutMajusculePrenom($str);
	return $str;
}

function verificationCaractereAutorises($str){
	$str = mb_strtolower($str);
	$tab = mbStringToArray($str);
	for ($i=0; $i <count($tab) ; $i++) {
		if(!preg_match("/".CARACTERESAUTORISES."/",$tab[$i])){
			return 0;
		}
	}
	$str = implode($tab);
  return 1;
}
function verificationLongeurPrenom($str){
	if(mb_strlen($str)>MAX_PRENOM)
		return 0;
	return 1;
}
function mbStringToArray ($str) {
    $strlen = mb_strlen($str);
    while ($strlen) {
        $array[] = mb_substr($str,0,1,"UTF-8");
        $str = mb_substr($str,1,$strlen,"UTF-8");
        $strlen = mb_strlen($str);
    }
    return $array;
}
function verificationRegleTiret($str){
	$str = mb_strtolower($str);
	$str = preg_replace('/ - /', '-', $str);
	$str = preg_replace('/ -/', '-', $str);
	$str = preg_replace('/- /', '-', $str);
	$str = preg_replace('/- -/', '--', $str);

	$tab = mbStringToArray($str);
	//Suppression tiret à la fin
	if(preg_match('/.-+$/',$str)){
		$check = true;
		$i = count($tab)-1;
		while($check && $i!=0) {
			$tab[$i]="";
			$i--;
			if($tab[$i]!="-")
				$check =false;
		}
	}
	//Suppression tiret au début
	if(preg_match('/^-+./',$str)){
		$check = true;
		$i = 0;
		while($check && $i!=count($tab)) {
			$tab[$i]="";
			$i++;
			if($tab[$i]!="-")
				$check =false;
		}
	}
	$str = implode($tab);
  return $str;
}
function suppresionEspaceEncombrant($str)
{
	while(strpos($str, '  ') !== false)
		$str = preg_replace('/  /', ' ', $str);
	return $str;
}
function verificationReglesDiversesPrenom($str){
	$str = mb_strtolower($str);
	if(strlen($str)==1){
		if(!preg_match("/".CARACTERESAREMPLACER."/",$str))
			return 0;
	}
	if(preg_match("/'{2}/",$str))
		return 0;
	if(preg_match("/-{2}/",$str))
		return 0;
	return 1;

}
function ajoutMajusculePrenom($str){
	$str = mb_strtolower($str);
	$tab = mbStringToArray($str);
	if(preg_match("/[a-z]/",$tab[0])){
			$tab[0]	=	strtoupper($tab[0]);
	}
	if(preg_match("/".CARACTERESACCENTFRANCAISAUTORISES."/",$tab[0])){
		$tab[0]= remplaceCaractereAccentFrancais($tab[0]);
		$tab[0]	=	strtoupper($tab[0]);
	}
	for ($i=0; $i < count($tab) ; $i++) {
		if(preg_match("/'| |-/",$tab[$i]) && $i+1<count($tab)){
			if(preg_match("/".CARACTERESACCENTFRANCAISAUTORISES."/",$tab[$i+1]))
				$tab[$i+1]= remplaceCaractereAccentFrancais($tab[$i+1]);
			$tab[$i+1]	=	strtoupper($tab[$i+1]);
		}
	}
	$str = implode($tab);
  return $str;
}
function remplaceCaractereSpecial($car){
	$car = preg_replace( '/á|â|ä|ȧ|ā|ã/', 'a', $car );
	$car = preg_replace( '/ć|č|ç/', 'c', $car );
	$car = preg_replace( '/ę/', 'e', $car );
	$car = preg_replace( '/í|î|ï|ī/', 'i', $car );
	$car = preg_replace( '/ł/', 'l', $car );
	$car = preg_replace( '/ñ/', 'n', $car );
	$car = preg_replace( '/ó|ô|ö|ø|õ/', 'o', $car );
	$car = preg_replace( '/ṭ/', 't', $car );
	$car = preg_replace( '/ú|û|ü/', 'u', $car );
	$car = preg_replace( '/ÿ/', 'y', $car );
	$car = preg_replace( '/ž|ẓ/', 'z', $car );
	$car = preg_replace( '/æ/', 'ae', $car );
	$car = preg_replace( '/œ/', 'oe', $car );
	return $car;
}
function suppresionAccentPrenom($str){
	$str = mb_strtolower($str);
	$tab = mbStringToArray($str);
	for ($i=0; $i <count($tab) ; $i++) {
		if(!preg_match("/[a-z]|".CARACTERESACCENTFRANCAISAUTORISES."|'|’| |-/",$tab[$i])){
			$tab[$i] = remplaceCaractereSpecial($tab[$i]);
		}
	}
	$str = implode($tab);
  return $str;
}
function remplaceCaractereAccentFrancais($car){
	$car = preg_replace( '/à|â|ä/', 'a', $car );
	$car = preg_replace( '/é|è|ê|ë/', 'e', $car );
	$car = preg_replace( '/ï|î/', 'i', $car );
	$car = preg_replace( '/ô|ö/', 'o', $car );
	$car = preg_replace( '/ù|û|ü/', 'u', $car );
	$car = preg_replace( '/ÿ/', 'y', $car );
	$car = preg_replace( '/ç/', 'c', $car );
	return $car;
}
function verificationNom($str){
	if(verificationCaractereAutorises($str) == 0)
		return 0;
	if(verificationLongeurNom($str) == 0)
		return 0;
	$str = verificationRegleTiret($str);
	$str = suppresionEspaceEncombrant($str);
	if(verificationReglesDiversesNom($str) == 0)
		return 0;
	
	$str = verificationApostrophes($str);

	$str = suppresionAccentNom($str);
	$str = strtoupper($str);
	return $str;
}
function verificationLongeurNom($str){
	if(mb_strlen($str)>MAX_NOM)
		return 0;
	return 1;
}

function verificationReglesDiversesNom($str){
	$str = mb_strtolower($str);
	if(strlen($str)==1){
		if(!preg_match("/".CARACTERESAREMPLACER."/",$str))
			return 0;
	}
	if(preg_match("/'{2}/",$str))
		return 0;
	if(preg_match("/'’|’'/",$str))
			return 0;
	if(preg_match("/-{3}/",$str))
		return 0;
	if(preg_match("/-{2}.*-{2}/",$str))
		return 0;
	return 1;

}

function suppresionAccentNom($str){
	$str = mb_strtolower($str);
	$tab = mbStringToArray($str);
	for ($i=0; $i <count($tab) ; $i++) {
		if(!preg_match("/[a-z]|'|’| |-/",$tab[$i])){
			$tab[$i] = remplaceCaractereSpecial($tab[$i]);
			$tab[$i] = remplaceCaractereAccentFrancais($tab[$i]);
		}
	}
	$str = implode($tab);
  return $str;
}

function verificationNomVille($str){
	if(verificationCaractereAutorisesVille($str) == 0)
		return 0;
	if(verificationLongeurNom($str) == 0)
		return 0;
	$str = verificationRegleTiret($str);
	$str = suppresionEspaceEncombrant($str);
	if(verificationReglesDiversesNom($str) == 0)
		return 0;
	
	$str = verificationApostrophes($str);

	$str = suppresionAccentNom($str);
	$str = strtoupper($str);
	return $str;
}
function verificationCaractereAutorisesVille($str){
	$str = mb_strtolower($str);
	$tab = mbStringToArray($str);
	for ($i=0; $i <count($tab) ; $i++) {
		if(!preg_match("/".CARACTERESAUTORISES."/",$tab[$i])){
			return 0;
		}
	}
	$str = implode($tab);
  return 1;
}

 ?>
