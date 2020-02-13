<?php

require_once("../persistence/ConnectionFactory.php");

$serveur = 'localhost';

$env = 'isas';
#$env = 'rv';

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


class sevrage{

	
	/**
	 * mise à jour du dossier avec les nouvelles informations
	 * @param  [type] $id  [description]
	 * @param  [type] $cab [description]
	 * @param  [type] $num [description]
	 * @return [type]      [description]
	 */
	function updateSevrage($darret,$darret2){

		$req = "update sevrage_tabac set darret2='$darret2' where darret='$darret' ";
		echo $req;
		$res = mysql_query($req);

	}

	function listSevrage(){
		$req = "select * from sevrage_tabac where darret != '' AND darret2='0000-00-00' ";
		#echo $req.'<br>';
		$res = mysql_query($req);
		while($row = mysql_fetch_array($res)){
			$rows[] = $row;
		}
		
		return $rows;
	}

	function corrigeDateArret($date){

		$retour = $date.'-01-01';
		return $retour;

	}


}



$liste = sevrage::listSevrage();
	#echo count($liste);exit;
	$i=0;
	foreach($liste as $sevrage){
		
		$array = array('2016','2017','2015','2014','2013','2006','2010','2005','1994','1972','1997','1980','2004','1996','2011','1987');
		if(in_array($sevrage['darret'],$array)){
			$darret2 = sevrage::corrigeDateArret($sevrage['darret']);
			echo '<p>darret = '.$sevrage['darret'].' = '.$darret2.'</p>';
			
			sevrage::updateSevrage($sevrage['darret'],$darret2);

		}

		

		#
	}
	echo '<p>'.$i;



	


?>