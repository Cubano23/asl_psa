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
		$DB = 'informed3';
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
$f_isas = 'inte_isas.csv';
$f_informed	 = 'inte_informed.csv';

$fichiers = array(	'inte-04-05',
					'inte-05-05',
					'inte-06-05',
					'inte-08-05',
					'inte-09-05',
					'inte-10-05',
					'inte-11-05',
					'inte-12-05',
					'inte-13-05',
					'inte-14-05',
					'inte-16-05',
					'inte-17-05',
					'inte-18-05',
					'inte-19-05',
					'inte-20-05',
					'inte-21-05',
					'inte-23-05',
					'inte-24-05',
					'inte-25-05',
					'inte-26-05',
					'inte-27-05',
					'inte-28-05',
					'inte-29-05',
					'inte-30-05',
					'inte-31-05',
					'inte-01-06',
					'inte-02-06',
					'inte-03-06',
					'inte-04-06',
					'inte-05-06',
					'inte-06-06',
					'inte-07-06',
					'inte-08-06',
					'inte-09-06',
					'inte-10-06',
					'inte-11-06',
					'inte-12-06',
					'inte-13-06',
					'inte-14-06',
					'inte-15-06',
					'inte-16-06',
					'inte-17-06',
					'inte-18-06',
					'inte-19-06',
					'inte-20-06',
					'inte-21-06',
					'inte-23-06',
					'inte-24-06',
					'inte-25-06',
					'inte-26-06',
					'inte-27-06',
					'inte-28-06',
					'inte-29-06',
					'inte-30-06',
					'inte-01-07',
					'inte-02-07',
					'inte-03-07',
					'inte-04-07',
					'inte-05-07',
					'inte-06-07',
					'inte-07-07',
					'inte-08-07',
					'inte-09-07',
					'inte-10-07',
					'inte-11-07',
					'inte-12-07',
					'inte-13-07',
					'inte-14-07',
					'inte-15-07',
					'inte-16-07',
					'inte-17-07',
					'inte-18-07',
					'inte-19-07',
					'inte-20-07',
					'inte-21-07',
					'inte-23-07',
					'inte-24-07',
					'inte-25-07',
					'inte-26-07',
					'inte-27-07',
					'inte-28-07'
					);



$dossiers = array();
$atraiter = array();

		foreach($fichiers as $fic){
			echo '@';
			$contenu=file('bak/'.$fic.'.csv');
		
			list( $numero_ligne, $ligne1) = each($contenu);
			while ( list( $numero_ligne, $ligne) = each( $contenu ) ) {
				$datas = explode(";",$ligne);
				$id = $datas[0];
				if(!in_array($id,$dossiers)){
					array_push($dossiers,$id);
					array_push($atraiter,$datas);
				}	
			}		
			

		}	
	


		echo '<h3>'.count($dossiers).' a traiter</h3>';
		
		
		#var_dump($atraiter);
		$nbupdate=0;
		foreach($atraiter as $data){

			$id = $data[0];$dateUS = $data[1];
			if(!dcExist($id)){
#				
				echo $id.';'.$dateUS;$nbupdate = $nbupdate+1;
#				echo ' => UPDATE';
				$updt = "update dossier set dconsentement='$dateUS' where id='$id' limit 1 ";
				mysql_query($updt);
			echo '<br>';
			}

		}

		echo '<h3>'.$nbupdate.' update</h3>';


?>