<?php

require_once("../persistence/ConnectionFactory.php");

$serveur = 'localhost';

$env = 'isas';

/*
// pierre
$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
*/

switch($env){
	case 'rv':
		$idDB = 'root';
		$mdpDB = 'root';
		$DB = 'informed3';
		break;
	case 'isas':
		$idDB = 'informed';
		$mdpDB = 'no11iugX';
		$DB = 'informed3';
		break;
}



mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");


// parsing du fichier
$fichier = 'split-staubin.csv';

$contenu=file($fichier);
		
		list( $numero_ligne, $ligne1) = each($contenu);
		while ( list( $numero_ligne, $ligne) = each( $contenu ) ) {
		if($numero_ligne!=0){
			$datas = explode(";",$ligne);
			// O est le dossier actuel
			// 4 est le nouveau n° de dossier
			
			// replace en base
			$numtemp = 'st'.$datas[0];
			
			// 1ere requete qui met un numéro temporaire
			#$updt = "update dossier set numero='$numtemp' where cabinet='aubin' and numero='$datas[0]' limit 1 ";
			
			// la dexime requete qui remplace le nom tempo par le nouveau NUM de dossier
			$updt = "update dossier set numero='$datas[1]' where cabinet='aubin' and numero='$numtemp' limit 1 ";
			echo $updt.'<br>';
			

			if(mysql_query($updt)){
				echo $datas[1].' OK<br>';
				#echo $datas[0].' -> '.$datas[4].' OK<br>';
			}
			else{
				echo $datas[1].' ERROR<br>';

				#echo $datas[0].' -> '.$datas[4].' ERROR<br>';
			}
		}

		}

		
		
exit;


?>