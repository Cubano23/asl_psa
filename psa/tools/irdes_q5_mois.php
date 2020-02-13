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


echo 'Q5B;Q5C;Q5D(BPCO);Q5E;Q5F;Q5G;Q5H;Q5I;Q5J(TC);Q5K;Q5L;Q5M;Q5N;Q5O;Q5P(RCVA);Q5Q;Q5R;Q5S;Q5T;Q5U;Q5V(DT2 suivi_diab+dep_diab);Q5W;Q5X;Q5Y;Q5Z;Q5AA;Q5AB(autres+mixte);Q5AC;Q5AD;Q5AE<br>';
while ($cab = mysql_fetch_array($sqlCabs)){

	$cabinet = $cab['cabinet'];

	$mixte = $suivi_diab = $dep_diab = $rcva = $bpco = $cognitif = $autres = $automesure = 0;
	$nb_mixte = $nb_suivi_diab = $nb_dep_diab = $nb_rcva = $nb_bpco = $nb_cognitif = $nb_autres = $nb_automesure = 0;
	
	$mixteTab = $suivi_diabTab = $dep_diabTab = $rcvaTab = $bpcoTab = $cognitifTab = $autresTab = $automesureTab = array();
	$mixte_dom = $suivi_diab_dom = $dep_diab_dom = $rcva_dom = $bpco_dom = $cognitif_dom = $autres_dom = $automesure_dom = 0;
	$mixte_tel = $suivi_diab_tel = $dep_diab_tel = $rcva_tel = $bpco_tel = $cognitif_tel = $autres_tel = $automesure_tel = 0;
	$mixte_col = $suivi_diab_col = $dep_diab_col = $rcva_col = $bpco_col = $cognitif_col = $autres_col = $automesure_col = 0;
	$tempsConsultation = 0;	


	// MOIS
	$reqMois = "Select * from evaluation_infirmier as E left join dossier as D on E.id=D.id where D.cabinet = '$cabinet' AND date >= '$mois_deb' and date <= '$mois_fin' ";
	$sqlMois = mysql_query($reqMois);
		
		while ($row = mysql_fetch_array($sqlMois)){

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

		
		}	

		
		// Q5 -> semaine
		echo utf8_encode($cabinet).';';
		echo count($bpcoTab).';'.$nb_bpco.';'.calculHeures($bpco).';'.$bpco_dom.';'.$bpco_tel.';'.$bpco_col.';';
		echo count($cognitifTab).';'.$nb_cognitif.';'.calculHeures($cognitif).';'.$cognitif_dom.';'.$cognitif_tel.';'.$cognitif_col.';';
		echo (count($rcvaTab)+count($automesureTab)).';'.($nb_rcva+$nb_automesure).';'.calculHeures($rcva+$automesure).';'.($rcva_dom+$automesure_dom).';'.($rcva_tel+$automesure_tel).';'.($rcva_col+$automesure_col).';';
		echo (count($suivi_diabTab)+count($dep_diabTab)).';'.($nb_suivi_diab+$nb_dep_diab).';'.calculHeures($suivi_diab+$dep_diab).';'.($suivi_diab_dom+$dep_diab_dom).';'.($suivi_diab_tel+$dep_diab_tel).';'.($suivi_diab_col+$dep_diab_col).';';
		echo (count($mixteTab)+count($autresTab)).';'.($nb_mixte+$nb_autres).';'.calculHeures($mixte+$autres).';'.($mixte_dom+$autres_dom).';'.($mixte_tel+$autres_tel).';'.($mixte_col+$autres_col);
		

		echo '<br>';


}


		



?>