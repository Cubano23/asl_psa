<?php

require_once("../persistence/ConnectionFactory.php");



function listeDossiers($cabinet){

		$req = "SELECT D.cabinet,D.numero, D.id, E.type_exam, E.date_exam, E.resultat1
				FROM dossier AS D, suivi_diabete AS S, liste_exam AS E
				WHERE D.id = S.dossier_id
				AND D.id = E.id
				#AND E.type_exam IN ('LDL','HBA1c','poids','type_tension')
				AND D.dconsentement != '0000-00-00'
				AND D.encnir != ''
				AND D.actif = 'oui'
				AND D.cabinet = '$cabinet'
				GROUP BY D.numero
				ORDER BY D.numero,E.type_exam,E.date_exam 
				";
		
		$sql = mysql_query($req);

		$results = array();
		while($row = mysql_fetch_object($sql)){
			$results[] = $row;
		}
		
		return $results;
	}


// 1ere requete qui donnait la date de première consultation de suivi_diabete pour le patient - date de démarrage du listing		
function giveFirstConsultation($id,$cabinet){

		$req = "Select date from evaluation_infirmier where id='$id' and type_consultation like '%suivi_diab%' ORDER BY DATE ASC LIMIT 1";
		$sql = mysql_query($req);
		$row = mysql_fetch_array($sql);
		return $row;
		
}

function giveFirstConsultationForAllDossiers($dossiers){

	// comme on a pas le cabinet dans evaluation_infirmere il faut récupérer tous les dossiers du cabinet et requeter sur tous les dossiers, prendre le plus ancien
	// c'est pas économique !
	$in = '(';
	foreach($dossiers as $dossier){
		$in .= "'".$dossier->id."',";
	}
	$in = substr($in,0,-1);
	$in = $in.')';

	$req = "Select date from evaluation_infirmier where id IN $in ORDER BY DATE ASC LIMIT 1";
	#var_dump($req);exit;
	$sql = mysql_query($req);
	$row = mysql_fetch_array($sql);
	return $row;

}

function giveDateInitiale($date){
	#$dateTab = explode("-",$date);
	#$date = '2006-04-04';
	#echo $date;
	$timestamp = strtotime(date($date));
	$dateMini = strtotime("-1year" ,$timestamp);
	#var_dump($timestamp);exit;
	return date("Y-m-d", $dateMini);
}

function prochaine_date_tp($date){
	$timestamp = strtotime(date($date));
	$prochaine = strtotime("+1year" ,$timestamp);
	return $prochaine;
}

function tp($date){
	return strtotime(date($date));
}

function compareDates($date1,$date2){
	#echo $date1.' - '.$date2.'<br>';
	$date1plus = strtotime("+1year" ,$date1);
	$dif = $date1plus - $date2;
	return $dif;

}



function listeExamsByDossier($id,$dateMini){
	$req = "SELECT * from liste_exam 
			where id = '$id' 
			and type_exam IN('HBA1c','LDL','poids','type_tension')
			and date_exam > '$dateMini'
			GROUP BY date_exam,type_exam
			ORDER BY type_exam,date_exam ASC ";
	$sql = mysql_query($req);

	while($row = mysql_fetch_object($sql)){
		$results[] = $row;
	}
	return $results;
}

function selectCabinets(){
	$req = "SELECT cabinet from account order by cabinet";
	$sql = mysql_query($req); 

	$options = '';
	while($row = mysql_fetch_array($sql)){
		$options .='<option value="'.$row['cabinet'].'">'.$row['cabinet'].'</option>\n';
	}
	return $options;
}

$serveur = 'localhost';
/*
// pierre
$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
*/

// rv
/*
$idDB = 'root';
$mdpDB = 'root';
$DB = 'informed3';
*/

$idDB = 'informed';
$mdpDB = 'no11iugX';
$DB = 'informed3';

mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");



$cabinet = $_POST['cabinet'];

if($cabinet==''){
	$cabinet = false;
}

?>

<form action="#" method= 'POST'>
<select name="cabinet">
<option>S&eacute;lectionnez un cabinet</option>
<?php echo selectCabinets(); ?>
</select>
<input type="submit" value="Rechercher">
</form>


<?php

if($cabinet){


	echo '<h1>Cabinet : '.$cabinet.' - suivi diabete</h1>';
	$dossiers = listeDossiers($cabinet);
	if(count($dossiers) == 0){
		echo '<h2>Aucun dossier</h2>';
	}

	$firstConsult = giveFirstConsultationForAllDossiers($dossiers);
	#var_dump($firstConsult);
	$dateInitiale = giveDateInitiale($firstConsult[0]);
	#var_dump($dateInitiale);exit;
	$i=1;
	foreach($dossiers as $dossier){
		#var_dump($dossier);
		$i++;
		#$firstConsult = giveFirstConsultation($dossier->id);

		
		echo '<h2>dossier : '.$dossier->id.' 
		<br>Date de Premi&egrave;re consultation infirmi&egrave;re = '.$firstConsult[0].'<br>
		Date de d&eacute;but de recherche : '.$dateInitiale.'</h2>';

		//pour chacun des dossier on liste les examens dans liste_exam dans les 4 sections recherchées
		$liste_examens = array();
		$liste_examens = listeExamsByDossier($dossier->id,$dateInitiale);
		echo '<table border="1"><tr><td> Type exam</td><td>Date exam</td></tr>';
		#$precedent_type='';
		foreach($liste_examens as $exam){
			
			// la date actuelle doit être comparée à la date précédente, si c'ets plus de 12 mois on la met en rouge.
			#echo'<tr><td>DG '.$exam->type_exam.'</td><td>'.$precedente_date.'</td></tr>';
			if($exam->type_exam != $precedent_type){
				#$capt = 1;
				$date_reference = $dateInitiale;
				$prochain_exam = prochaine_date_tp($dateInitiale); // juste pour info
				// espace
				echo '<tr><td colspan="2">&nbsp;</td></tr>';
			}
			else{
				#$capt=2;
				$date_reference = $precedente_date;
				$prochain_exam = prochaine_date_tp($exam->date_exam);
			}
			
			$dif = compareDates(tp($date_reference),tp($exam->date_exam)); // les 2 dates ne doivent pas avoir un ecart de plus de 12 mois, c'st pourquoi on rajoute 12 mois à la date 1 dans la fonction
			if($dif < 0){$color="red";}else{$color="#FFF";}
			echo '<tr style="background-color:'.$color.'"><td>'.$capt.' '.$exam->type_exam.'</td><td>'.$exam->date_exam.'
			<span style="color:blue">## (precedente date '.date("Y-m-d",tp($date_reference)).') ##</span>
			</td></tr>';
			$precedente_date = $exam->date_exam;
			$precedent_type = $exam->type_exam;
		}
		echo '</table>';
		
	}
}

		



?>