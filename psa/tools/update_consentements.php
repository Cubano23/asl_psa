<?php

require_once("../persistence/ConnectionFactory.php");

$serveur = 'localhost';

$env = 'ids';
#$env ='rv';
#$env = 'pierre';


switch($env){
	case 'pierre':
		$idDB = 'root';
		$mdpDB = 'root';
		$DB = 'isas';
	break;
		
	case 'rv' :
		$idDB = 'root';
		$mdpDB = 'root';
		$DB = 'isas';
	break;

	case 'ids' :
		$idDB = 'informed';
		$mdpDB = 'no11iugX';
		$DB = 'informed3';
	break;
}


function dcExist($id){

	$req = "SELECT * FROM dossier where id='$id' limit 1 ";
	$sql = mysql_query($req);

	$row = mysql_fetch_array($sql);

	if($row['dconsentement']!='0000-00-00'){
		return true;
	}
	else{
		return false;
	}

}


mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");

// parsing du fichier
$fichier = 'bak/dossier-'.$_GET['date'].'.csv';
#echo $fichier;
$nbupdate =0;
$contenu=file($fichier);
		
		list( $numero_ligne, $ligne1) = each($contenu);
		
		while ( list( $numero_ligne, $ligne) = each( $contenu ) ) {
		
		if($numero_ligne!=0){
			$datas = explode(";",$ligne);
			// O est le dossier actuel
			// 4 est le nouveau n° de dossier
			$splitdate = explode("/",$datas[1]);
			#$dateUs = '20'.trim($splitdate[2]).'-'.$splitdate[1].'-'.$splitdate[0];
			$id = $datas[0];
			$dateUS = $datas[1];
			#echo '<br>id = '.$id.' => '.$dateUS.',';

			if(!dcExist($id)){
#				
				echo $id.';'.$dateUS;
#				echo ' => UPDATE';$nbupdate = $nbupdate+1;
				$updt = "update dossier set dconsentement='$dateUS' where id='$id' limit 1 ";
#				mysql_query($updt);
			echo '<br>';
			}
		

		
			

		}

		}

		echo '<h3>'.$nbupdate.' a int&eacute;grer</h3>'
		



?>