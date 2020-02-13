<?php 

	/*
	 * 11/10/13 : Pierre
	 *
	 */
	
	/* bean objects */
	require_once("bean/FicheCabinet.php");
	require_once("bean/SuiviHebdomadaireTempsPasse.php");
	require_once("bean/EvaluationInfirmier.php");
	require_once("bean/SuiviReunionMedecin.php");

	require_once("bean/ControlerParams.php");
	
	/* persistence object */
	require_once("persistence/FicheCabinetMapper.php");
	require_once("persistence/SuiviHebdomadaireTempsPasseMapper.php");
	require_once("persistence/EvaluationInfirmierMapper.php");
	require_once("persistence/SuiviReunionMedecinMapper.php");

	require_once("persistence/ConnectionFactory.php");
	require_once("tools/date.php");

	class DashboardControler{
	
		var $mappingTable;
		
		function DashboardControler() {
			$this->mappingTable = 
			array(
			"URL_VIEW"=>"view/dashboard/dashboard.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");

		}
	
		function date_diff($date1, $date2)  
		{ 
		 $s = $date2-$date1; 
		 $d = intval($s/86400)+1;   
		 return "$d"; 
		}

		function start() 
		{
			$current_year = '2013';
			$current_month = '04';
			$date_start = $current_year.'-'.$current_month.'-01';
			$date_end = $current_year.'-'.$current_month.'-30';

			$month = mktime(0, 0, 0, $current_month, 1, $current_year);
			//echo 'Mois concern&eacute; : '.date('F Y', $month);

			$nb_jour_month = date('t', $month);
			$first_jour_month = date('N', $month);

			if($first_jour_month == 7)
			{
				$first_lundi = "02";
				$first_lundi_final = strtotime( "-1 week", mktime(0, 0, 0, $current_month, 2, $current_year));
			}
			else if($first_jour_month > 1)
			{
				$first_lundi = (1 + 8) - $first_jour_month;
				$first_lundi_final = strtotime( "-1 week", mktime(0, 0, 0, $current_month, $first_lundi, $current_year));
			}
			else{
				$first_lundi = "01";
				$first_lundi_final = $month;
			}


			$last_lundi = floor(($nb_jour_month - $first_lundi) / 7) * 7 + $first_lundi;
			$last_lundi_final = mktime(0, 0, 0, $current_month, $last_lundi, $current_year);
			$diff_start = (7 - $this->date_diff($first_lundi_final, mktime(0, 0, 0, $current_month, 1, $current_year)) + 1);
			$diff_end = (($this->date_diff($last_lundi_final, mktime(0, 0, 0, $current_month, ($nb_jour_month), $current_year))));
/*echo '<hr><br>1er lundi : '.date('Y-m-d', $first_lundi_final);
echo '<br>Dernier lundi : '.date('Y-m-d', $last_lundi_final);
echo '<br> Diff d&eacute;but de mois : '.$diff_start;
echo '<br> Diff fin de mois : '.$diff_end;
echo '<hr>';*/



			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $FicheCabinet;
			global $dossier;
			global $SuiviHebdomadaireTempsPasse;
			global $saisieInfirmiere;
			global $tempsPrepaBilanConsultation;
			global $SuiviReunionMedecin;
			global $objExams;
			global $examsDerogatoire;
			global $nb_exam_realises; 
			global $nb_exam_saisis;

			global $const_demi_jour;
			global $const_objectif_consult_jour;

			if(array_key_exists("FicheCabinet",$objects))
				$FicheCabinet = $objects["FicheCabinet"];


			global $SuiviHebdomadaireTempsPasse;
			if(array_key_exists("SuiviHebdomadaireTempsPasse",$objects))
				$SuiviHebdomadaireTempsPasse = $objects["SuiviHebdomadaireTempsPasse"];

			global $evaluationInfirmier;
			if(array_key_exists("evaluationInfirmier",$objects))
				$evaluationInfirmier = $objects["evaluationInfirmier"];	

			global $SuiviReunionMedecin;
			if(array_key_exists("SuiviReunionMedecin",$objects))
				$SuiviReunionMedecin = $objects["SuiviReunionMedecin"];	


			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","DashboardControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$FicheCabinetMapper = new FicheCabinetMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());
			$SuiviHebdomadaireTempsPasseMapper = new SuiviHebdomadaireTempsPasseMapper($cf->getConnection());
			$evaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
			$SuiviReunionMedecinMapper = new SuiviReunionMedecinMapper($cf->getConnection());
			

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action)
			{
				case ACTION_MANAGE:
					$const_demi_jour = 210; // en minutes car nous avons en durée minute dans suivi hebdo du temps
					$const_objectif_consult_jour = 6;
					$total_temps = 0;

					$FicheCabinet=New FicheCabinet();
					$FicheCabinet->cabinet = $account->cabinet;
					$FicheCabinet->region = $account->region;
					$FicheCabinet->infirmiere = $account->infirmiere;
					$resultCabinet = $FicheCabinetMapper->findObject($FicheCabinet->beforeSerialisation($account));
					$FicheCabinet = $resultCabinet->afterDeserialisation($account);

					/* Activité : suivi temps hebdo */
					$SuiviHebdomadaireTempsPasse = new SuiviHebdomadaireTempsPasse();
					//$SuiviHebdomadaireTempsPasse->date= '2013-05-20';
					$SuiviHebdomadaireTempsPasse->cabinet = $account->cabinet;
					$resultTemps = $SuiviHebdomadaireTempsPasseMapper->getObjectsByCabinetBetweenDates($account->cabinet, date('Y-m-d', $first_lundi_final), date('Y-m-d', strtotime('+1 week', $last_lundi_final)));
					$formation = 0;
					$contribution = 0;
					$dossier = 0;
					$coordination = 0;
					foreach ($resultTemps as $key => $value) 
					{
						if($key == 0)
						{
							//echo '<br>#'.$key.' : first => prorata : '.$diff_start;
							$coef_diff = $diff_start;
						}
						else if($key == sizeof($resultTemps) - 1)
						{
							//echo '<br>#'.$key.' : last => prorata : '.$diff_end;
							$coef_diff = $diff_end;
						}
						else
						{
							//echo '<br>#'.$key.' : middle';
							$coef_diff = 7;
						}

						if($value['formation'] != null)
							$formation += (intval($value['formation']) / 7) * $coef_diff;
						if($value['autoformation'] != null)
							$formation += (intval($value['autoformation']) / 7) * $coef_diff;
						if($value['stagiaires'] != null)
							$formation += (intval($value['stagiaires']) / 7) * $coef_diff;
						if($value['tps_contact_tel_patient'] != null)
							$contribution += (intval($value['tps_contact_tel_patient']) / 7) * $coef_diff;
						if($value['tps_contact_tel_patient'] != null)
							$dossier += (intval($value['info_asalee']) / 7) * $coef_diff;
						if($value['tps_reunion_infirmiere'] != null)
							$coordination += (intval($value['tps_reunion_infirmiere']) / 7) * $coef_diff;
						
						
					}
					

					
					/* Activité : liste_exams */
					$evaluationInfirmier = new EvaluationInfirmier();
					$evaluationInfirmier->date = dateToMysqlDate($SuiviHebdomadaireTempsPasse->date);
					$saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetBetweenDate($account->cabinet, $date_start, $date_end);
					$consultation = 0;
					$aJourconsult = array();
					$aPatient = array();
					$aProtocole = array('dep_diab'=>array(), 'suivi_diab'=>array(), 'rcva'=>array(), 'cognitif'=>array(), 'bpco'=>array());
					$aPatientProtocole = array();
					$nb_new_mois = 0;

					$examsDerogatoire = array();
					$examsDerogatoire['spiro'] = 0;
					$examsDerogatoire['cogn'] = 0;
					$examsDerogatoire['ecg'] = 0;
					$examsDerogatoire['pied'] = 0;
					$examsDerogatoire['monofil'] = 0;
					$examsDerogatoire['autre'] = 0;


					foreach ($saisieInfirmiere as $key => $value) 
					{
						$consultation += $value['duree'];
						if(!in_array($value['date'], $aJourconsult))
						{	
							array_push($aJourconsult, $value['date']);
						}
						if(!in_array($value['numero'], $aPatient))
						{
							array_push($aPatient, $value['numero']);
						}
						$aTypeConsult = explode(',', $value['type_consultation']);
						for($i=0; $i<sizeof($aTypeConsult); $i++)
						{
							if(isset($aProtocole[$aTypeConsult[$i]]))
							{
								if(!in_array($value['numero'], $aProtocole[$aTypeConsult[$i]]))
									array_push($aProtocole[$aTypeConsult[$i]], $value['numero']);
								foreach ($aProtocole as $key => $aValueId) 
								{
									if($key != $aTypeConsult[$i])
									{
										if(in_array($value['numero'], $aValueId))
											array_push($aPatientProtocole, $value['numero']);
									}
								}
							}
						}
						if($value['dcreated'] > $date_start)	// nb new patient dans le mois
							$nb_new_mois++;

					
						$TpsConsultation[$value['type_consultation']] = $TpsConsultation[$value['type_consultation']]+intval($value['duree']);


						$examsDerogatoire['spiro'] += ($value['spirometre_seul'] != NULL) ? $value['spirometre_seul'] : 0;
						$examsDerogatoire['cogn'] += $value['t_cognitif'];
						$examsDerogatoire['ecg'] += $value['ecg_seul'];
						$examsDerogatoire['pied'] += $value['exapied'];
						$examsDerogatoire['monofil'] += $value['monofil'];
						$examsDerogatoire['autre'] += $value['autre'];
					    
					}
					
					// PREPARATION BILAN DES CONSULTATIONS est calcul&eacute; en appliquant des taux forfaitaires par rapport au temps de consultation. 
					$tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) + ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['autres']*0.2) + ($TpsConsultation['cognitif']*0.1));

					$SuiviHebdomadaireTempsPasse->formation = $formation;
					$SuiviHebdomadaireTempsPasse->tps_contact_tel_patient = $contribution;
					$SuiviHebdomadaireTempsPasse->info_asalee = $dossier + $tempsPrepaBilanConsultation;
					$total_temps += $SuiviHebdomadaireTempsPasse->formation + $SuiviHebdomadaireTempsPasse->tps_contact_tel_patient + $SuiviHebdomadaireTempsPasse->info_asalee;




					$total_temps += $consultation;
					//echo'<hr>';
					//var_dump ($nb_new_mois);

					/* Activité : liste_exams */
					$SuiviReunionMedecin = new SuiviReunionMedecin();
					$SuiviReunionMedecin = $SuiviReunionMedecinMapper->getObjectsByCabinetBetweenDate($account->cabinet, $date_start, $date_end);
					foreach ($SuiviReunionMedecin as $key => $value) 
					{
						$coordination += $value['duree'];
					}
					$total_temps += $coordination;
					$SuiviHebdomadaireTempsPasse->tps_reunion_medecin = $coordination;

					$objExams = new stdClass();
					$objExams->consultation = $consultation;
					$objExams->total = $total_temps;
					$objExams->nb_jour_consult = sizeof($aJourconsult);
					$objExams->nb_patient = sizeof($aPatient);
					$objExams->protocoles = $aProtocole;
					$objExams->nb_multiprotocole = sizeof(array_unique($aPatientProtocole));
					$objExams->nb_new = $nb_new_mois;


					// Nb exams saisis ou intégrés / nb exams réalisés
					// Réalisés : 
					$req = "SELECT count(*) as nb FROM isas.liste_exam as e INNER JOIN isas.dossier as d ON e.id=d.id WHERE d.cabinet='".$account->cabinet."' AND e.date_exam BETWEEN '".$date_start."' AND '".$date_end."'";
					$res=mysql_query($req);
					$nb_exam_realises = mysql_fetch_assoc($res);
					// Saisis ou intégrés : 
					$req = "SELECT count(*) as nb FROM isas.liste_exam as e INNER JOIN isas.dossier as d ON e.id=d.id WHERE d.cabinet='".$account->cabinet."' AND e.dmaj BETWEEN '".$date_start."' AND '".$date_end."'";
					$res=mysql_query($req);
					$nb_exam_saisis = mysql_fetch_assoc($res);


/////////////////////////////////////////////////////////////////

/*
$req="SELECT dossier.cabinet, count(*), nom_cab, region ".
		 "FROM dossier, account ".
		 "WHERE infirmiere!='' and region!='' ".
		 "AND actif='oui' ".
		 "and dossier.cabinet=account.cabinet ".
		 "GROUP BY nom_cab ".
		 "ORDER BY nom_cab, numero ";
//echo $req;
//die;
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

if (mysql_num_rows($res)==0) {
	exit ("<p align='center'>Aucun cabinet n'est actif</p>");
}
$tcabinet=array();
$liste_reg=array();
		$dossierssup140["tot"][1]=0;
		$dossierssup140["tot"][2]=0;
		$dossierssup140["tot"][3]=0;
		$dossierssup140["tot"][4]=0;
		$dossiersinf140["tot"][1]=0;
		$dossiersinf140["tot"][2]=0;
		$dossiersinf140["tot"][3]=0;
		$dossiersinf140["tot"][4]=0;
		$dossierspastension["tot"]=0;
		$change["tot"][1]=0;
		$change["tot"][2]=0;
		$change["tot"][3]=0;
		$change["tot"][4]=0;

while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
	$tcabinet[] = $cab;
	$tville[$cab]=$ville;
	$regions[$cab]=$region;
	 
	if(!in_array($region, $liste_reg)){
		$liste_reg[]=$region;
		$dossiers[$region]=array();
		$dossierssup140[$region][1]=0;
		$dossierssup140[$region][2]=0;
		$dossierssup140[$region][3]=0;
		$dossierssup140[$region][4]=0;
		$dossiersinf140[$region][1]=0;
		$dossiersinf140[$region][2]=0;
		$dossiersinf140[$region][3]=0;
		$dossiersinf140[$region][4]=0;
		$dossierspastension[$region]=0;
		$change[$region][1]=0;
		$change[$region][2]=0;
		$change[$region][3]=0;
		$change[$region][4]=0;
	}
	 
	$dossiers[$cab]=array();
	$dossierssup140[$cab][1]=0;
	$dossierssup140[$cab][2]=0;
	$dossierssup140[$cab][3]=0;
	$dossierssup140[$cab][4]=0;
	$dossiersinf140[$cab][1]=0;
	$dossiersinf140[$cab][2]=0;
	$dossiersinf140[$cab][3]=0;
	$dossiersinf140[$cab][4]=0;
	$dossierspastension[$cab]=0;
	$change[$cab][1]=0;
	$change[$cab][2]=0;
	$change[$cab][3]=0;
	$change[$cab][4]=0;
	 
//	 $tpat[$cab] = $pat;
}

sort($liste_reg);

echo "<br>
<table border=1 width='100%'>";



//Liste des consults par patient
$req="SELECT cabinet, dossier.id, date ".
		 "FROM evaluation_infirmier, dossier ".
		 "WHERE actif='oui' ".
		 "AND evaluation_infirmier.id=dossier.id ".
		 "ORDER BY cabinet, id, date ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$id_prec="";

while(list($cabinet, $id, $date)=mysql_fetch_row($res)){
	if(isset($regions[$cabinet])){
		if($id_prec!=$id){//Nouveau dossier=> 1ère consult
			$consult[$id][1]=$date;
			$id_prec=$id;
			$nb_consult=1;
		}
		else{
			$nb_consult++;
			$consult[$id][$nb_consult]=$date;
		}
	}
}

//Liste des tensions par patient en RCVA
$req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
		 "FROM cardio_vasculaire_depart, dossier, liste_exam ".
		 "WHERE actif='oui' ".
		 "AND cardio_vasculaire_depart.id=dossier.id and dossier.id=liste_exam.id ".
		 "and type_exam='systole' ". 
		 //"and date_exam > '2013-03-01' ".
		 "GROUP BY cabinet, dossier.id, date_exam ";
		 "ORDER BY cabinet, dossier.id, date_exam ";
echo $req;
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$id_prec="";

while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
	$req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
		  "date_exam='$date'";
	$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
	
	list($TaDia)=mysql_fetch_row($res2);

	if(isset($regions[$cabinet])){
		$id_prec=$id;
		$dossiers[$cabinet][]=$id;
		$dossiers[$regions[$cabinet]][]=$id;
		$cabinets[$id]=$cabinet;
		$liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
	}
}
echo "<br>##".sizeof($liste_tension)."<br>";
foreach ($liste_tension as $key => $value) {
	echo '<br>'.$key.' => ';
	foreach ($value as $k => $v) {
		echo '| '.$k.' => ';
		foreach ($v as $k2 => $v2) {
			echo ' | '.$k2.' => '.$v2;
		}
	}
}
exit();
//Liste des tensions par patient en suivi diabète
$req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
		 "FROM suivi_diabete, dossier, liste_exam ".
		 "WHERE actif='oui' ".
		 "AND dossier_id=dossier.id and dossier.id=liste_exam.id ".
		 "and date_exam < '2013-03-01' and type_exam='systole' ".
		 "GROUP BY cabinet, dossier.id, date_exam ";
		 "ORDER BY cabinet, dossier.id, date_exam ";
//echo $req;
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$id_prec="";

while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
	$req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
		  "date_exam='$date'";
	$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
	
	list($TaDia)=mysql_fetch_row($res2);

	if(isset($regions[$cabinet])){
		$id_prec=$id;
		$cabinets[$id]=$cabinet;
		$liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
	}
}
echo "<br>##".sizeof($liste_tension)."<br>";
foreach ($liste_tension as $key => $value) {
	echo '<br>'.$key.' => ';
	foreach ($value as $k => $v) {
		echo '| '.$k.' => ';
		foreach ($v as $k2 => $v2) {
			echo ' | '.$k2.' => '.$v2;
		}
	}
}
//exit();



foreach($liste_tension as $id => $tab){
	//echo "@@"; var_dump($tab);
	//if(true)
	//{
		if(isset($consult[$id])){
			$consult1=$consult[$id][1];
			if(isset($consult[$id][2])){
				$consult2=$consult[$id][2];
			}
			else{
				$consult2="2100-01-01";
			}
			if(isset($consult[$id][3])){
				$consult3=$consult[$id][3];
			}
			else{
				$consult3="2100-01-01";
			}
			if(isset($consult[$id][4])){
				$consult4=$consult[$id][4];
			}
			else{
				$consult4="2100-01-01";
			}
			
			$valeur1=$valeur2=$valeur3=$valeur4=$valeur5="";
			
			foreach($tab as $date=>$valeurs){
				if($date<$consult1){
					$valeur1=$valeurs;
				}
				elseif(($date>=$consult1)&&($date<$consult2)){
					if($valeur2==""){
						$valeur2=$valeurs;
					}
				}
				elseif(($date>=$consult2)&&($date<$consult3)){
					if($valeur3==""){
						$valeur3=$valeurs;
					}
				}
				elseif(($date>=$consult3)&&($date<$consult4)){
					if($valeur4==""){
						$valeur4=$valeurs;
					}
				}
				else{
					if($valeur5==""){
						$valeur5=$valeurs;
					}
				}
			}
			
			if($valeur1==""){
				$dossierspastension[$cabinets[$id]]=$dossierspastension[$cabinets[$id]]+1;
				$dossierspastension["tot"]=$dossierspastension["tot"]+1;
			}
			elseif($valeur2!=""){
				if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
					$dossiersinf140[$cabinets[$id]][1]=$dossiersinf140[$cabinets[$id]][1]+1;
					$dossiersinf140["tot"][1]=$dossiersinf140["tot"][1]+1;
				}
				else{
					$dossierssup140[$cabinets[$id]][1]=$dossierssup140[$cabinets[$id]][1]+1;
					$dossierssup140["tot"][1]=$dossierssup140["tot"][1]+1;
					
					if(($valeur2["TaSys"]<140)&&($valeur2["TaDia"]<90)){
						$change[$cabinets[$id]][1]=$change[$cabinets[$id]][1]+1;
						$change["tot"][1]=$change["tot"][1]+1;
					}
				}
			}
			elseif($valeur3!=""){
				if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
					$dossiersinf140[$cabinets[$id]][2]=$dossiersinf140[$cabinets[$id]][2]+1;
					$dossiersinf140["tot"][2]=$dossiersinf140["tot"][2]+1;
				}
				else{
					$dossierssup140[$cabinets[$id]][2]=$dossierssup140[$cabinets[$id]][2]+1;
					$dossierssup140["tot"][2]=$dossierssup140["tot"][2]+1;
					
					if(($valeur3["TaSys"]<140)&&($valeur3["TaDia"]<90)){
						$change[$cabinets[$id]][2]=$change[$cabinets[$id]][2]+1;
						$change["tot"][2]=$change["tot"][2]+1;
					}
				}
			}
			elseif($valeur4!=""){
				if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
					$dossiersinf140[$cabinets[$id]][3]=$dossiersinf140[$cabinets[$id]][3]+1;
					$dossiersinf140["tot"][3]=$dossiersinf140["tot"][3]+1;
				}
				else{
					$dossierssup140[$cabinets[$id]][3]=$dossierssup140[$cabinets[$id]][3]+1;
					$dossierssup140["tot"][3]=$dossierssup140["tot"][3]+1;
					
					if(($valeur4["TaSys"]<140)&&($valeur4["TaDia"]<90)){
						$change[$cabinets[$id]][3]=$change[$cabinets[$id]][3]+1;
						$change["tot"][3]=$change["tot"][3]+1;
					}
				}
			}
			elseif($valeur5!=""){
				if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
					$dossiersinf140[$cabinets[$id]][4]=$dossiersinf140[$cabinets[$id]][4]+1;
					$dossiersinf140["tot"][4]=$dossiersinf140["tot"][4]+1;
				}
				else{
					$dossierssup140[$cabinets[$id]][4]=$dossierssup140[$cabinets[$id]][4]+1;
					$dossierssup140["tot"][4]=$dossierssup140["tot"][4]+1;
					
					if(($valeur5["TaSys"]<140)&&($valeur5["TaDia"]<90)){
						$change[$cabinets[$id]][4]=$change[$cabinets[$id]][4]+1;
						$change["tot"][4]=$change["tot"][4]+1;
					}
				}
			}
		//}
	}
}
//echo "<br>##".sizeof($dossiersinf140)."<br>";
//var_dump($dossiersinf140);
//exit();

echo '<tr>
<td>Valeurs de la tension</td>
	<td align="center"><b>Moyenne</b></td>';
	
	
	
	foreach ($tcabinet as $cab)
	{
	    
	    if($cab == 'Ruelle') echo '<td align="center"><b>'.$cab.'</b></td>';
	    

	}

echo "</tr>";



for($i=1; $i<=4; $i++)
{
	if($i>1){
		$s="s";
	}
	else{
		$s="";
	}
echo "<tr>
		<td>Nb dossiers avec tension &gt; 140/90 avant $i consultation$s<sup>1</sup> </td>
   			<td align='right'>".$dossierssup140["tot"][$i]."</Td>";
			
	
	foreach ($tcabinet as $cab)
	{
		if($cab == 'Ruelle') echo "<td align='right'>".$dossierssup140[$cab][$i]."</Td>";
	}
	
	echo "</tr>";
	
	echo "<tr><td>Taux dossiers avec tension &gt; 140/90 avant $i consultation$s et passant &lt;140/90<sup>2</sup> </td><td align='right'>".$dossierssup140["tot"][$i]." %</Td>";
	//echo "<tr><td>Taux dossiers avec tension &gt; 140/90 avant $i consultation$s et passant &lt;140/90<sup>2</sup> </td><td align='right'>".round($change["tot"][$i]/$dossierssup140["tot"][$i]*100)." %</Td>";
			
	
	
	foreach ($tcabinet as $cab)
	{
		if($cab == 'Ruelle') 
		{
			if($dossierssup140[$cab][$i]==0){
				echo "<td align='right'>ND</Td>";
			}
			else{
				echo "<td align='right'>".round($change[$cab][$i]/$dossierssup140[$cab][$i]*100)." %</Td>";
			}
		}
	}
	
	echo "</tr>";
}


echo "</table>";

*/
////////////////////////////////////////////////////////////////

					forward($this->mappingTable["URL_VIEW"]);
					break;

				default:
					echo("ACTION IS NULL");
					break;
			}


		}
	}
?> 
