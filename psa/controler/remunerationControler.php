<?php

// class qui concerne la rémunération medecin appelé remunération MG

class remuneration{
	
	/**
	 * definition des periodes a prendre en compte pour les requetes dasn les tableaux de bord
	 * @param  [type] $md mois début
	 * @param  [type] $yd annee debut
	 * @param  [type] $mf mois fin
	 * @param  [type] $yf annee fin
	 * @return array  $periodes   exple array('05/2015','06/2015','07/2015','08/2015','09/2015');
	 */
	static function givePeriodesForDashbord($md,$yd,$mf,$yf){
		//si current != dernier alors on ajoute 1 mois
		$current = $premier = $md.'/'.$yd;
		$dernier = $mf.'/'.$yf;
		$periodes = array($premier);
		#echo '<p>'.$dernier.'</p>';
		$periodesRetour = self::ajoutePeriodes($periodes,$current,$dernier);
		#var_dump($reponse);exit;
		return $periodesRetour;

	}

	/**
	 * fonction recursive qui ajoute les périodes pour chercher les infos dans les tdb
	 * @param  [type] $periodes [description]
	 * @param  [type] $current  [description]
	 * @param  [type] $dernier  [description]
	 * @return [type]           [description]
	 */
	static function ajoutePeriodes($periodes,$current,$dernier){
		
		if($current!=$dernier){
			#echo $current.' -> '.$dernier.'<br>';
			// ajoute 1 mois
			$current = self::ajouteMoisperiode($current);
			array_push($periodes,$current);
			// on rappelle cette fonction qui va ajouter la période suivante
			return self::ajoutePeriodes($periodes,$current,$dernier);
		}
		else{
			#self::returnPeriode($periodes);
			return $periodes;
			#return $periodes;
		}
			
		
		
	}

	
	/**
	 * ajoute 1 mois à la période actuelle
	 * @param  [type] $current [description]
	 * @return string $periode le mois +1
	 */
	static function ajouteMoisperiode($current){

		$mois = substr($current,0,2);
		$annee = intval(substr($current,3,4));
		if($mois=='12'){$annee = $annee+1;}
		switch($mois){
			case '01':$retour = '02/'.$annee;break;
			case '02':$retour = '03/'.$annee;break;
			case '03':$retour = '04/'.$annee;break;
			case '04':$retour = '05/'.$annee;break;
			case '05':$retour = '06/'.$annee;break;
			case '06':$retour = '07/'.$annee;break;
			case '07':$retour = '08/'.$annee;break;
			case '08':$retour = '09/'.$annee;break;
			case '09':$retour = '10/'.$annee;break;
			case '10':$retour = '11/'.$annee;break;
			case '11':$retour = '12/'.$annee;break;
			case '12':$retour = '01/'.$annee;break;
		}
		return $retour;
	}

	/**
	 * affiche le mois en toute lettre
	 * @param  [type] $mois [description]
	 * @return [type]       [description]
	 */
	function giveMois($mois){
		switch($mois){
			case '01' : $retour='Janvier';break;
			case '02' : $retour='Février';break;
			case '03' : $retour='Mars';break;
			case '04' : $retour='Avril';break;
			case '05' : $retour='Mai';break;
			case '06' : $retour='Juin';break;
			case '07' : $retour='Juillet';break;
			case '08' : $retour='Août';break;
			case '09' : $retour='Septembre';break;
			case '10' : $retour='Octobre';break;
			case '11' : $retour='Novembre';break;
			case '12' : $retour='Décembre';break;

		}
		return $retour;
	}

	function listeCabinet(){
		$req = "SELECT cabinet from account order by cabinet";
		$sql = mysql_query($req);
		while ($row = mysql_fetch_array($sql)){
			$result[] = $row[0];
		}
		#var_dump($result);
		return $result;

	}

	/**
	 * listing des medecins référencés sur le cabinet, on ne prend pas ceux qui ont pas de nom ou prenom
	 * @param  [type] $cabinet [description]
	 * @return [type]          [description]
	 */
	function listeMG($cabinet){
		$req = "SELECT * from medecin where cabinet='$cabinet' and (nom!='' OR prenom!='') ";
		$sql = mysql_query($req);
		while ($row = mysql_fetch_object($sql)){
			$result[] = $row;
		}
		return $result;
	}

	/**
	 * récupération de la date d'entree d'un medecin
	 * @param  [type] $id_mg   [description]
	 * @param  [type] $cabinet [description]
	 * @return [type]          [description]
	 */
	function giveDateEntreeByMG($id_mg,$cabinet){
		$req = "SELECT * from historique_medecin where actualstatus='0' and cabinet='$cabinet' and medid='$id_mg' order by dstatus LIMIT 1 ";
		$sql = mysql_query($req);
		$result = mysql_fetch_object($sql);
		return $result;
	}


	function giveDateSortieByMG($id_mg,$cabinet){
		$req = "SELECT * from historique_medecin where actualstatus='1' and cabinet='$cabinet' and medid='$id_mg' order by dstatus DESC LIMIT 1 ";
		$sql = mysql_query($req);
		$result = mysql_fetch_object($sql);
		return $result;
	}


	/**
	 * date de premire consultation dans le cabinet, correspond à la date de démarrage du medecin
	 * ce qui est pas tout à fait vrai....
	 * ajout Pierre 15/05/17 : AND evaluation_infirmier.date <> '0000-00-00' 
	 * @param  [type] $cabinet [description]
	 * @param  [type] $DFR     [description]
	 * @return [type]          [description]
	 */
	function giveFirstConsultByCabinet($cabinet,$DFR){

		$req="SELECT `date`
			 FROM `evaluation_infirmier` , `dossier` WHERE dossier.id = evaluation_infirmier.id 
			 AND dossier.cabinet = '$cabinet' 
			 AND evaluation_infirmier.date <> '0000-00-00' 		
			 order by date ASC LIMIT 1,1
			 ";
		$sql = mysql_query($req);
		$date = mysql_result($sql,0,0);
		#var_dump($sql);
		
		// if(empty($date)){
		// 	$date = $DFR;
		// }
		return $date;
	}

	/**
	 * donne le nbre de jours forfaitaires déjà attribués sur les rémunaration précédentes, 
	 * on a initialiser les datas la première fois lors du calcul de mai à septembre 2015
	 * @param  [type] $cabinet [description]
	 * @param  [type] $DDR     [description]
	 * @return [type]          [description]
	 */
	function giveJoursForfaitairesDejaAttribues($cabinet,$DDR,$id_mg){

		$req = "SELECT * from remuneration_mg_jours_forfaitaires where cabinet='$cabinet' and date_fin < '$DDR' and id_mg='$id_mg' ";
		$sql = mysql_query($req);
		$totalJours = 0;
		while ($row = mysql_fetch_array($sql)){
			$totalJours = $totalJours+$row['nbre_jours'];
		}

		return $totalJours;

	}

	/**
	 * presque meme fonction qu'au dessus mais on donne tous les jours attribués, sans filtre de date, et on file le détail
	 * @param  [type] $cabinet [description]
	 * @param  [type] $DDR     [description]
	 * @param  [type] $id_mg   [description]
	 * @return [type]          [description]
	 */
	function giveAllJoursForfaitairesDejaAttribues($cabinet,$id_mg){

		$req = "SELECT * from remuneration_mg_jours_forfaitaires where cabinet='$cabinet' and id_mg='$id_mg' ";
		$sql = mysql_query($req);
		$totalJours = 0;
		while ($row = mysql_fetch_object($sql)){
			$result[] = $row;
		}
		return $result;

	}

	/**
	 * compter le nbre de jours entre 2 dates
	 * @param  [type] $date1 [description]
	 * @param  [type] $date2 [description]
	 * @return [type]        [description]
	 */
	function giveNbJoursByDate($date1,$date2){
		$time1 = strtotime($date1);
		$time2 = strtotime($date2);
		$dif = $time2 - $time1;
		$coef = 60*60*24;
		$nbjours = $dif/$coef;
		return round($nbjours)+1;
	}


	/**
	 * calcul du nombre de jours de la période pour 1 cabinet, cela varie en fonction de la date de 1er consultation
	 * @param  date  $date_deb     [description]
	 * @param  date  $date_fin     [description]
	 * @param  date  $firstConsult [description]
	 * @return [type]                [description]
	 */
	function nombre_de_jours_periode($date_deb,$date_fin,$firstConsult = false){

		if(!empty($firstConsult)){
			$time_first = strtotime($firstConsult);
		}
		$time_deb = strtotime($date_deb);
		$time_fin = strtotime($date_fin);
		
		if($time_first > $time_deb){
			$time_deb = $time_first;
		}

		
		$dif = $time_fin - $time_deb;
		$coef = 60*60*24;
		$nbjours = $dif/$coef;
		return round($nbjours) +1 ;
		#return $time_deb.' '.$time_fin;

	}

	
	/**
	 * donne les informations de calcul associées au prorata
	 * le mode permet de définir si on enregistre en base le nbre de jours attribués à la période, ou si on fait une simulation de rémunaration...
	 * @param  [type] $level        [description]
	 * @param  [type] $firstConsult [description]
	 * @param  [type] $nb_jap       [description]
	 * @param  [type] $DDR          [description]
	 * @param  [type] $DFR          [description]
	 * @param  [type] $JFDA         [description]
	 * @param  [type] $TF           [description]
	 * @param  [type] $id_mg        [description]
	 * @param  [type] $cabinet      [description]
	 * @param  [type] $mode         [description]
	 * @return [type]               [description]
	 */
	function getProrataTemporis($level,$firstConsult,$nb_jap,$DDR,$DFR,$JFDA,$TF,$id_mg,$cabinet,$mode){

		if($level==0){
			$retour['nbRestant'] = 0;
			$retour['pt'] = 0;
		}
		elseif($level==1){
			// il faut calculer le nombre de jours a attribuer
			$nb_jf_restant = remuneration::get_tf_restant($JFDA,$TF,$id_mg);
			// on enregistre le nbre de jours restants en base pour les fois prochaines
			if($nb_jf_restant > 121){ $nb_jf_restant = 121;}
			if($nb_jf_restant > $nb_jap){ $nb_jf_restant = $nb_jap;}
			if($mode=='reel'){
				remuneration::recordJFA($cabinet,$DDR,$DFR,$nb_jf_restant,$id_mg);
			}
			
			$retour['nbRestant'] = $nb_jf_restant;
			$retour['pt'] = round(100*($nb_jf_restant/121),4);

		}
		else{
			if($JFDA!=0){
				$nb_jf_restant = $TF-$JFDA;
			}
			else{
				$nb_jf_restant = $nb_jap-$JFDA;
			}
			if($nb_jf_restant > 121){ $nb_jf_restant = 121;}
			if($nb_jf_restant > $nb_jap){ $nb_jf_restant = $nb_jap;}
			if($mode=='reel'){
				remuneration::recordJFA($cabinet,$DDR,$DFR,$nb_jf_restant,$id_mg);
			}
			$retour['nbRestant'] = $nb_jf_restant;
			$retour['pt'] = round(100*($nb_jf_restant/121),4);
		}
			$retour['level'] = $level;

			
		
		return $retour;
	}

	/**
	 * récupération du fait d'avoir ou pas un prorata temporis
	 * @param  [type] $firstConsult [description]
	 * @param  [type] $DDR          [description]
	 * @param  [type] $DFR          [description]
	 * @return int  $level  0 => demarrage plus de 4 mois avant la date de debut période
	 *                      1 => demarage inférieur à 4 mois avant la fin de la période de calcul
	 *                      2 => sinon on affecte le nombre de jours a affecter restant
	 */
	function getProrataTemporisLevel($firstConsult,$DDR,$DFR){
		
		$ecart1 = self::giveNbJoursByDate($firstConsult,$DDR);
		$ecart2 = self::giveNbJoursByDate($firstConsult,$DFR);

		if($ecart1 > 121){
			$level = 0;
		}
		elseif($ecart2 < $nb_jours_4mois){
			$level = 1;
		}
		else{
			$level = 2;
		}

		return $level;
	}

	

	function calcul_rem_forfaitaire($prorata_temporis,$taux_etp_ide_par_mg){
			
			if($taux_etp_ide_par_mg > 1){
				$taux_etp_ide_par_mg = 1;
			}
		        return round(12*25*4*$prorata_temporis/100*$taux_etp_ide_par_mg,4);
		
	}

	
	/**
	 * calcul du prorata temporis variable (c'est un %)
	 * @param  [type] $nb_jap                       nbre de jours sur la période pour le cabinet en question
	 * @param  [type] $nb_jours_total_periode       nbre de jours total sur la période de calcul
	 * @param  [type] $prorata_temporis_forfaitaire la colonne d'avant 
	 * @param  [type] $TF                           [description]
	 * @return [type]                               [description]
	 */
	function calcul_prorata_temporis_variable($nb_jap,$nb_jours_total_periode,$prorata_temporis_forfaitaire,$TF,$nb_jf_restant){

		$time_first = strtotime($firstConsult);
		$time_fin = strtotime($DFR);

		if($nb_jap==0){
			$result = 0;
		}
		else{
			//146 - 121 / 152 = 16,4
			$point1 = $nb_jap - $nb_jf_restant;
			$result = round($point1/$nb_jours_total_periode,4);
			
		}

		
		return $result;
	}

	/**
	 * calcul du temps de concertation medecin, il faut reperer le medecin avec son nom puisqu'il est pas identifié par un ID...
	 * @param  [type] $medecin [description]
	 * @param  [type] $DDR     [description]
	 * @param  [type] $DFR     [description]
	 * @return [type]          [description]
	 */
	function calcul_temp_concertation($cabinet,$idmedecin,$DDR,$DFR){

		$liste = self::liste_reunions_medecin_periode($cabinet,$idmedecin,$DDR,$DFR);

		$dureeTT = 0; //exprimée en minutes
		foreach($liste as $reunion){
			$dureeTT = $dureeTT+$reunion->duree;
		}
		return $dureeTT;
	}

	/**
	 * liste des réunion par medecin, avant on prenait les nom des medecins, a compteer du 20/10/2016 on prend les id_mg
	 * @param  [type] $cabinet [description]
	 * @param  [type] $medecin [description]
	 * @param  [type] $DDR     [description]
	 * @param  [type] $DFR     [description]
	 * @return [type]          [description]
	 */
	function liste_reunions_medecin_periode($cabinet,$idmedecin,$DDR,$DFR){
		if(strpos($medecin,"Chaham")){$medecin='chaham';}
		#$req = "SELECT * FROM suivi_reunion_medecin where cabinet = '$cabinet' and medecin like '%$medecin%' and date_reunion > '$DDR' and date_reunion < '$DFR' ";
		
		$req = "SELECT *,CONCAT(',',id_mg,',') as essai FROM suivi_reunion_medecin  WHERE CONCAT(',',id_mg,',') like '%,$idmedecin,%' and date_reunion > '$DDR' and date_reunion < '$DFR'";
		#echo '<p>'.$req;
		$sql = mysql_query($req);

		while ($row = mysql_fetch_object($sql)){
			$result[] = $row;
		}
		#var_dump($req);exit;
		#echo 'compte '.count($result);
		return $result;
	}


	function calcul_tx_rem_concertation($tc,$tcm,$DDR,$DFR){
		
		$nbmois = round(self::giveNbJoursByDate($DDR,$DFR)/30);
		$div = round($tcm*$nbmois,4);
		$result = round($tc / $div,4)*100;
		#return $tc.' / '.$tcm.' * '.$nbmois;
		return $result;
	}

	function getNombreJoursPeriode($cabinet,$DDR,$DFR){
		

		$cf = new ConnectionFactory();
		$evaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
	 	
	 	// calcul du nbre de saisie infirmiere sur la période
		$saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetsBetweenDate($cabinet, $DDR, $DFR);
		$consultation = 0;
		foreach ($saisieInfirmiere as $key => $value) 
	      {
	        if(($value['date'] >= $DDR) && ($value['date'] <= $DFR))
	        {
	          $nb_saisie_inf_periode++; // nombre de saisie par infirmières sur la période
	          $consultation += $value['duree'];
	        }
	      }

	    // calcul du nombre de jours d'activité sur la période
		$jour_activite = (getPropertyValue("SuiviHebdomadaireTempsPasse:info_asalee") + getPropertyValue("SuiviHebdomadaireTempsPasse:tps_reunion_medecin") + $consultation + $non_attribue) / ($const_demi_jour);

		return $jour_activite;

	}

	// cell là est OK vu avec PDA le 11-01
	// le taux de consultation est la moyenne sur les 3 mois, elle correspond à une valeur qui est déjà caclulée dans les tableaux de bord donc on ira chercher cette valeur dans dashboard_results
	function calcul_taux_consultation($cabinet,$periodes_dashboard){

		#var_dump($periodes_dashboard);exit;

		foreach($periodes_dashboard as $mois){
			$nbre_consult[] = self::getNbreConsultation($cabinet,$mois);
			$jours_retenus[] = self::getNbreJoursRetenus($cabinet,$mois);
			#$taux[] = str_replace(" %","",$le_taux);
		}
		#$nbre = count($taux);
		
		foreach($nbre_consult as $consult){
			$ttc = $ttc + $consult;
		}
		
		foreach($jours_retenus as $jr){
			$ttjr = $ttjr + $jr;
		}

		$moyenne1 = round($ttc/$ttjr,4);
		$moyenne = round($moyenne1/6,4);
		return $moyenne;
	}

	function getTauxConsultation($cabinet,$mois){
		$req = "SELECT objectifs_percent FROM dashboard_results where cabinet = '$cabinet' and periode = '$mois' ";
		$sql = mysql_query($req);
		$row = mysql_fetch_row($sql);
		#echo $row[0];exit;
		return($row[0]);
	}

	function getNbreConsultation($cabinet,$mois){
		#$periode = $mois.' - '.$mois;
		$req = "SELECT nbre_consultations FROM dashboard_results where cabinet = '$cabinet' and periode = '$mois' ";
		$sql = mysql_query($req);
		$row = mysql_fetch_row($sql);
		#echo $row[0];exit;
		return($row[0]);
	}

	function getNbreJoursRetenus($cabinet,$mois){
		#$periode = $mois.' - '.$mois;
		$req = "SELECT jours_retenus FROM dashboard_results where cabinet = '$cabinet' and periode = '$mois' ";
		$sql = mysql_query($req);
		$row = mysql_fetch_row($sql);
		#echo $row[0];exit;
		return($row[0]);
	}

	/**
	 * ajustemanet du taux de remuneration composé par rapport à un min et un max, il doit rester dans la fourchette
	 * min = 15 max = 300
	 * @param  [type] $taux [description]
	 * @return [type]       [description]
	 */
	function ajusteTauxRemCompose($taux){

		if($taux < 15){
			$taux = 15;
		}
		if($taux > 300){
			$taux = 300;
		}
		return round($taux,4);
	}

	/**
	 * la somme des temps déclarés sur la période, on ira cherche les infos dans les tdb
	 * @param  [type] $cabinet            [description]
	 * @param  [type] $periodes_dashboard [description]
	 * @return [type]                     [description]
	 */
	function getSommeTempsPeriode($cabinet,$periodes_dashboard){
		$tt = '';
		foreach($periodes_dashboard as $mois){
			$le_temps = self::getTempsConsultation($cabinet,$mois);
			$tt = $le_temps + $tt;
		}

		return $tt;
	}

	/**
	 * récupération du temps déclaré sur un mois défini, c'est dans les résultats tdb
	 * on prend le total du temps passé sur lequel on déduis le temps de contribution asalée
	 * @param  [type] $cabinet [description]
	 * @param  [type] $mois    [description]
	 * @return [type]          [description]
	 */
	static function getTempsConsultation($cabinet,$mois){
		$req = "SELECT total,contrib_asalee FROM dashboard_results where cabinet = '$cabinet' and periode = '$mois' ";
		$sql = mysql_query($req);
		$row = mysql_fetch_array($sql);
		#var_dump($row);
		#echo $row['total'].'-'.$row['contrib_asalee'].'<br>';
		#$restant = $row['total']-$row['contrib_asalee']; // on ne soustrait plus le temps de contribution asalee, demande du 15 juin 2016
		$restant = $row['total'];
		return($restant);
	}

	/**
	 * on plafonne la remuneration variable par la base de depart, si c'est superieur on limite à la base, le $coefteipmg est un coeficient ajouté ensuite
	 * si 
	 * @param  [type] $rem_variable   [description]
	 * @param  [type] $base_de_depart [description]
	 * @param  [type] $base_de_depart [description]
	 * @return [type]                 [description]
	 */
	function getPlafondRemVariable($rem_variable,$base_de_depart,$taux_etp_ide_par_mg){
		
		if($taux_etp_ide_par_mg > 1){
			$coefteipmg = 1; // coeficient taux etp id par mg ajouté dans le total le 15 juin 2016
		}
		else{
			$coefteipmg = $taux_etp_ide_par_mg;
		}

		if($rem_variable > $base_de_depart*$coefteipmg){
			$rem_variable = $base_de_depart*$coefteipmg;
		}

		

		return $rem_variable;
	}


	/**
	 * calcule du nombre de jours forfaitaires restants quand ils n'ont pas tous été attribués
	 * @param  [type] $JFDA [description]
	 * @param  [type] $TF   [description]
	 * @return [type]       [description]
	 */
	function get_tf_restant($JFDA,$TF){
		return $TF-$JFDA;
	}

	/**
	 * enregistrement des jours forfaitaires attribués restant sur le cabinet
	 * @param  [type] $cabinet       [description]
	 * @param  [type] $DDR           [description]
	 * @param  [type] $DFR           [description]
	 * @param  [type] $nb_jf_restant [description]
	 * @return [type]                [description]
	 */
	function recordJFA($cabinet,$DDR,$DFR,$nbjours,$id_mg){

	  // on vérifie si y'a pas déjà des valeurs pour ce cab sur la période
	  $req = "SELECT * from remuneration_mg_jours_forfaitaires where cabinet='$cabinet' and date_debut='$DDR' and date_fin='$DFR' and id_mg='$id_mg' ";
	  $sql = mysql_query($req);
	  $row = mysql_fetch_object($sql);
	  
	  if($row){
	    // on update
	    $record = "UPDATE remuneration_mg_jours_forfaitaires set nbre_jours = '$nbjours' 
	    where cabinet='$cabinet' and date_debut='$DDR' and date_fin='$DFR' and id_mg='$id_mg') ";
	    $sql2 = mysql_query($record);
	  }
	  else{
	    // on insert
	    $record = "INSERT INTO remuneration_mg_jours_forfaitaires set nbre_jours = '$nbjours', 
	    id_mg='$id_mg',
	    cabinet='$cabinet',
	    date_debut='$DDR',
	    date_fin='$DFR' 
	    ";
	    $sql2 = mysql_query($record);
	  }

  	}

  	/**
  	 * supprime tous les enregistrements de la table qui stocke les jours forfaitaires attribués 
  	 * dans le cas où on relance un calcul qui aurait déjà été effectué...
  	 * @param  [type] $DDR [description]
  	 * @return [type]      [description]
  	 */
  	function videJfaForPeriode($DDR){

  		$req = "DELETE from remuneration_mg_jours_forfaitaires where date_debut='$DDR' ";
  		$sql2 = mysql_query($req);

  	}




}
