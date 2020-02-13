<?php

require_once("../persistence/ConnectionFactory.php");

#$env = 'herve';


$sem_deb = '2015-06-08';
$sem_fin = '2015-06-14';

$mois_deb = '2015-05-18';
$mois_fin = '2015-06-14';

switch($env){

	case 'herve' :
	$idDB = 'root';
	$mdpDB = 'root';
	$DB = 'isas';
	break;

	default:
	$idDB = 'informed';
	$mdpDB = 'no11iugX';
	$DB = 'informed3';
	break;

}
$serveur = 'localhost';

mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");

function calculBilan($suivi_diab,$dep_diab,$rcva,$bpco,$cognitif,$autres,$automesure,$mixte){
	$result = (($suivi_diab*0.25) + ($dep_diab*0.25) + ($rcva*0.25) + ($bpco*0.2) + ($cognitif*0.1) + ($autres*0.2) + ($cognitif*0.1));
	return $result;
}

function calculHeures($var){
	return round($var/60,1);
}
function calculDM($var){
	return round($var/60/3.5,1);
}

/*
Colonne G, préparation bilan consultations délégués
 //  Ces taux sont diff&eacute;renci&eacute;s pour les diff&eacute;rents protocoles avec les coefficients suivant :
                          //Suivi diabète –> taux 0,25
                          //D&eacute;pistage du Diabète type 2 –> taux 0,25
                          //Suivi du patient RCVA –> taux 0,25
                          //Rep&eacute;rage BPCO tabagique –> taux 0,2
                          //Rep&eacute;rage trouble cognitif –> taux 0,1
                          //H&eacute;moccult –> taux 0
                          //D&eacute;pistage cancer du sein –> taux 0
                          //D&eacute;pistage cancer du colon –> taux 0
                          //D&eacute;pistage cancer de l'ut&eacute;rus –> taux 0
                          //D&eacute;pistage cancer du sein –> taux 0
                          //Autres0,2
                          //Automesure -> 0,10

    $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) +
    ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['autres']*0.2) + ($TpsConsultation['cognitif']*0.1));
 */


$reqCabs = "Select cabinet from evaluation_infirmier as E left join dossier as D on E.id=D.id where date >= '$mois_deb' and date <= '$mois_fin' and D.cabinet!='' group by D.cabinet order by D.cabinet ";
$sqlCabs = mysql_query($reqCabs);


echo 'Q3A;Q3G;Q3H;Q3I;Q3J;Q3K;Q3L;Q3M;Q3N;Q3O;Q3P;Q3Q;Q3R;Q3S;Q3T;Q3U;q3V;Q3W;Q3X<br>';
while ($cab = mysql_fetch_array($sqlCabs)){

	$cabinet = $cab['cabinet'];

	$mixte = $suivi_diab = $dep_diab = $rcva = $bpco = $cognitif = $autres = $automesure = 0;
	$nb_mixte = $nb_suivi_diab = $nb_dep_diab = $nb_rcva = $nb_bpco = $nb_cognitif = $nb_autres = $nb_automesure = 0;
	
	$mixteTab = $suivi_diabTab = $dep_diabTab = $rcvaTab = $bpcoTab = $cognitifTab = $autresTab = $automesureTab = array();
	$mixte_dom = $suivi_diab_dom = $dep_diab_dom = $rcva_dom = $bpco_dom = $cognitif_dom = $autres_dom = $automesure_dom = 0;
	$mixte_tel = $suivi_diab_tel = $dep_diab_tel = $rcva_tel = $bpco_tel = $cognitif_tel = $autres_tel = $automesure_tel = 0;
	$mixte_col = $suivi_diab_col = $dep_diab_col = $rcva_col = $bpco_col = $cognitif_col = $autres_col = $automesure_col = 0;
	$tempsConsultation = 0;







	// SEMAINE
	$reqSem = "Select * from evaluation_infirmier as E left join dossier as D on E.id=D.id where D.cabinet = '$cabinet' AND date >= '$sem_deb' and date <= '$sem_fin' ";
	$sqlSem = mysql_query($reqSem);
		
		while ($row = mysql_fetch_array($sqlSem)){

			// on additionne les valeurs
			$tempsConsultation = $row['duree']+$tempsConsultation;
			
			// detail
			$typeTab = explode(",",$row['type_consultation']);
			if(count($typeTab) > 1){
				$nb_mixte = $nb_mixte + 1;
				$mixte = $mixte + $row['duree'];
				if(!in_array($row['id'],$mixteTab)){
					array_push($mixteTab,$row['id']);
				}
				if($row['consult_domicile']){$mixte_dom = $mixte_dom+1;}
				if($row['consult_tel']){$mixte_tel = $mixte_tel+1;}
				if($row['consult_collective']){$mixte_col = $mixte_col+1;}

			}
			elseif(in_array('suivi_diab',$typeTab)){
				$nb_suivi_diab = $nb_suivi_diab + 1;
				$suivi_diab = $suivi_diab+$row['duree'];
				if(!in_array($row['id'],$suivi_diabTab)){
					array_push($suivi_diabTab,$row['id']);
				}

				if($row['consult_domicile']){$suivi_diab_dom = $mixte_dom+1;}
				if($row['consult_tel']){$suivi_diab_tel = $mixte_tel+1;}
				if($row['consult_collective']){$suivi_diab_col = $mixte_col+1;}
			}
			elseif(in_array('dep_diab',$typeTab)){
				$nb_dep_diab = $nb_dep_diab + 1;
				$dep_diab = $dep_diab+$row['duree'];
				if(!in_array($row['id'],$dep_diabTab)){
					array_push($dep_diabTab,$row['id']);
				}
				if($row['consult_domicile']){$dep_diab_dom = $mixte_dom+1;}
				if($row['consult_tel']){$dep_diab_tel = $mixte_tel+1;}
				if($row['consult_collective']){$dep_diab_col = $mixte_col+1;}
			}
			elseif(in_array('rcva',$typeTab)){
				$nb_rcva = $nb_rcva + 1;
				$rcva = $rcva+$row['duree'];
				if(!in_array($row['id'],$rcvaTab)){
					array_push($rcvaTab,$row['id']);
				}
				if($row['consult_domicile']){$rcva_dom = $mixte_dom+1;}
				if($row['consult_tel']){$rcva_tel = $mixte_tel+1;}
				if($row['consult_collective']){$rcva_col = $mixte_col+1;}
			}
			elseif(in_array('bpco',$typeTab)){
				$nb_bpco = $nb_bpco + 1;
				$bpco = $bpco+$row['duree'];
				if(!in_array($row['id'],$bpcoTab)){
					array_push($bpcoTab,$row['id']);
				}
				if($row['consult_domicile']){$bpco_dom = $mixte_dom+1;}
				if($row['consult_tel']){$bpco_tel = $mixte_tel+1;}
				if($row['consult_collective']){$bpco_col = $mixte_col+1;}
			}
			elseif(in_array('cognitif',$typeTab)){
				$nb_cognitif = $nb_cognitif + 1;
				$cognitif = $cognitif+$row['duree'];
				if(!in_array($row['id'],$cognitifTab)){
					array_push($cognitifTab,$row['id']);
				}
				if($row['consult_domicile']){$cognitif_dom = $mixte_dom+1;}
				if($row['consult_tel']){$cognitif_tel = $mixte_tel+1;}
				if($row['consult_collective']){$cognitif_col = $mixte_col+1;}
			}
			elseif(in_array('autres',$typeTab) || in_array('hemocult',$typeTab) || in_array('sein',$typeTab) || in_array('colon',$typeTab)){
				$nb_autres = $nb_autres + 1;
				$autres = $autres+$row['duree'];
				if(!in_array($row['id'],$autresTab)){
					array_push($autresTab,$row['id']);
				}
				if($row['consult_domicile']){$autres_dom = $mixte_dom+1;}
				if($row['consult_tel']){$autres_tel = $mixte_tel+1;}
				if($row['consult_collective']){$autres_col = $mixte_col+1;}
			}
			elseif(in_array('automesure',$typeTab)){
				$nb_automesure = $nb_automesure + 1;
				$automesure = $automesure+$row['duree'];
				if(!in_array($row['id'],$automesureTab)){
					array_push($automesureTab,$row['id']);
				}
				if($row['consult_domicile']){$automesure_dom = $mixte_dom+1;}
				if($row['consult_tel']){$automesure_tel = $mixte_tel+1;}
				if($row['consult_collective']){$automesure_col = $mixte_col+1;}
			}


		// pour chaque cabinet on calcule les consultations
		$req = "Select * from suivi_hebdo_temps_passe where cabinet = '$cabinet' AND date = '$sem_deb' ";
		$sql = mysql_query($req);
			
			
			$row1 = mysql_fetch_array($sql);
				#var_dump($req);exit;
				$gestion_dossier_patient = $row1['info_asalee'];
				$tps_reunion_infirmiere = $row1['tps_reunion_infirmiere'];
				$contribution_asalee =  $row1['tps_contact_tel_patient'];
				$autoformation =  $row1['autoformation'];
				$formation =  $row1['formation'];
				$stagiaire =  $row1['stagiaire'];
		
		// pour chaque cabinet on calcule les consultations
		$req2 = "Select * from suivi_reunion_medecin where cabinet = '$cabinet' AND date >= '$sem_deb' and date <= '$sem_fin' ";
		$sql2 = mysql_query($req2);
			
			$tps_reunion_medecin = $nb_reunions = 0;
			#var_dump($req2);exit;
			while($row2 = mysql_fetch_array($sql2)){
				$tps_reunion_medecin = $tps_reunion_medecin+$row2['duree'];
				$nb_reunions = $nb_reunions+1;
			}
		
				

		
		}	


		//automesure a ajouter à RCVA
		// hemocult sein + colon dans autre
		// 1622 est cohérent


		$bilan = calculBilan($suivi_diab,$dep_diab,$rcva,$bpco,$cognitif,$autres,$automesure,$mixte);


		//Q3G à Q3J - 4 colonnes // semaine
		echo utf8_decode($cabinet).';Non disponible;Non disponible;Non disponible;Non disponible;'.calculHeures($tempsConsultation).';'.calculDM($tempsConsultation).';'.calculHeures($bilan).';'.calculDM($bilan).';';
		echo calculHeures($gestion_dossier_patient).';'.calculDM($gestion_dossier_patient).';';
		echo calculHeures($tps_reunion_medecin).';'.calculDM($tps_reunion_medecin).';';
		echo calculHeures($tps_reunion_infirmiere).';'.calculDM($tps_reunion_infirmiere).';';
		echo calculHeures($contribution_asalee).';'.calculDM($contribution_asalee).';';
		echo calculHeures($autoformation).';'.calculDM($autoformation).';';
		echo calculHeures($formation).';'.calculDM($formation).';';
		echo calculHeures($stagiaire).';'.calculDM($stagiaire);

		echo '<br>';


}


		



?>