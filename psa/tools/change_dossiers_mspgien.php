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
		$DB = 'isas';
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


class dossier{

	/**
	 * recuperation du dosseir avec num et cabinet
	 * @param  [type] $cab [description]
	 * @param  [type] $num [description]
	 * @return [type]      [description]
	 */
	function getDossierByCabAndNum($cab,$num){

		$req = "select * from dossier where cabinet = '$cab' and numero='$num' ";
		#echo $req.'<br>';
		$res = mysql_query($req);
		$row = mysql_fetch_assoc($res);
		return $row;

	}

	/**
	 * mise à jour du dossier avec les nouvelles informations
	 * @param  [type] $id  [description]
	 * @param  [type] $cab [description]
	 * @param  [type] $num [description]
	 * @return [type]      [description]
	 */
	function updateDossier($id,$cab,$num){

		$req = "update dossier set numero='$num',cabinet='$cab' where id='$id' limit 1 ";
		echo $req;
		$res = mysql_query($req);

	}
}






// parsing du fichier
$fichier = 'migration_gienpaulin.csv';

$contenu=file($fichier);
		
		list( $numero_ligne, $ligne1) = each($contenu);
		while ( list( $numero_ligne, $ligne) = each( $contenu ) ) {
		if($numero_ligne!=0){
			$datas = explode(";",$ligne);
			// O est le cabinet initail
			// 1 est le n° de dossier actuel
			// 2 la date de naissance
			// 3 le nouveau N° de dossier
			
			# faut récupérer l'id asalee et changer le N° de dossier et le nom du cabinet
			
			$dossier = dossier::getDossierByCabAndNum($datas[0],$datas[1]);

			if(!$dossier){
				echo '<p style="color:red">dossier non trouv&eacute; '.$datas[0].' '.$datas[1].'</p>';
			}
			else{
				// on update la ligne avec le nom de cabinet mspgien et le nouveau num de dossier
				dossier::updateDossier($dossier['id'],'mspgien','G'.trim($datas[3]));
				echo '<p>dossier '.$datas[1].' OK</p>';

			}
			
		}

		}

		
		
exit;


?>