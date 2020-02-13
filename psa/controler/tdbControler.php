<?php

class tdbControler{

	/**
	 * gestion des affichages des tableaux de bord a publié sur la home page d'accueil
	 * @return [type] [description]
	 */
	static function givePublishedCode(){

		$retour = array(
            "2018_11" => "Tableau de bord de novembre 2018",
            "2018_10" => "Tableau de bord d'octobre 2018",
            "2018_09" => "Tableau de bord de septembre 2018",
            "2018_08" => "Tableau de bord d'aout 2018",
			"2018_07" => "Tableau de bord de juillet 2018",
			"2018_06" => "Tableau de bord de juin 2018",
			"2018_05" => "Tableau de bord de mai 2018", 
		 	"2018_04" => "Tableau de bord d'avril 2018", 
			"2018_03" => "Tableau de bord de mars 2018", 
			"2018_02" => "Tableau de bord de février 2018", 
                        "2018_01" => "Tableau de bord de janvier 2018",
			"2017_all" => "Tableau de bord de l'année 2017",
			"2017_12" => "Tableau de bord de décembre 2017",
			"2017_11" => "Tableau de bord de novembre 2017",
			"2017_10" => "Tableau de bord d'octobre 2017",
			"2017_09" => "Tableau de bord de Septembre 2017",
			"2017_08" => "Tableau de bord de Août 2017",
			"2017_07" => "Tableau de bord de Juillet 2017",
			"2017_06" => "Tableau de bord de Juin 2017",
			"2017_05" => "Tableau de bord de Mai 2017",
			"2017_04" => "Tableau de bord d'Avril 2017",
			"2017_03" => "Tableau de bord de Mars 2017",
			"2017_02" => "Tableau de bord de Février 2017",
			"2017_01" => "Tableau de bord de Janvier 2017",
			"2016_01_12" => "Tableau de bord de Janvier à Décembre 2016",
			"2016_12" => "Tableau de bord de décembre 2016",
			"2016_11" => "Tableau de bord de novembre 2016",
			"2016_10" => "Tableau de bord d'octobre 2016",
			"2016_09" => "Tableau de bord de septembre 2016",
			"2016_08" => "Tableau de bord d'Août 2016",
			"2016_07" => "Tableau de bord de Juillet 2016",
			"2016_06" => "Tableau de bord de Juin 2016",
			"2016_05" => "Tableau de bord de Mai 2016",
			"2016_04" => "Tableau de bord d'Avril 2016",
			"2016_03" => "Tableau de bord de Mars 2016",
			"2016_02" => "Tableau de bord de Février 2016",
			"2016_01" => "Tableau de bord de Janvier 2016"
#			"2015_janvier_decembre" => "Tableau de bord de Janvier à Décembre 2015"
			);

		return $retour;
	}


	/**
	 * initialisation des calculs avant de lancer, 
	 * dans un futur proche ces datas seront gérées dynamiquement 
	 * @param string $type // consolides pour plusieurs mois (en général a partir du 1er janvier), mensuel pour 1 seul mois
	 * @param string la zone définie soit par cabinet, soit par regroupement (on en fait plus depuis début 2016 mais bon ça peux remonter :-)
	 * @return [type] [description]
	 */
	static function initCalculs(){

		$period = 'mensuels'; // mensuels ou consolides
		$type = 'cabinet'; // cabinet (cabinet seul) Vs region (regions consolidées)

		$params = new stdClass();
		$params->environnement = 'rv'; // rv ou prod ou stag / détermine les éléments pour connexion base de données
		$params->type = $type;


		if($period=='mensuels'){
			$params->my_date_start_lundi = '2016-12-26'; //date du début des calculs le 1er lundi précédent le 1er du mois
			$params->my_date_end_lundi = '2017-02-06'; // date de fin, dernier lundi suivant le dernier jour du mois
			$params->begin_year = "2017"; // année de départ
			$params->begin_month = "01"; // mois de départ
			$params->end_year = "2017"; // année de fin
			$params->end_month = "01"; // mois de fin
			$params->nb_jour_month = '31'; // nombre de jours du dernier mois
			$params->dernier_jour_du_mois = '31'; // nombre de jours de la période
			$params->outputName = "2017_01_pierre";
		}
		if($period=='consolides'){

			$params->my_date_start_lundi = '2015-12-28'; //date du début des calculs le 1er lundi précédent le 1er du mois
			$params->my_date_end_lundi = '2016-08-01'; // date de fin, dernier lundi suivant le dernier jour du mois
			$params->begin_year = "2016"; // année de départ
			$params->begin_month = "01";// mois de départ
			$params->end_year = "2016";  // année de fin
			$params->end_month = "07"; // mois de fin
			$params->nb_jour_month = 31+29+31+30+31+30+31;  // nombre de jours de la période
			$params->dernier_jour_du_mois = '31'; // nombre de jours du dernier mois
			$params->outputName = "2016_01_07";

		}
		

		return $params;

	}

	/**
	 * établie la connexion à la base de données en fonction de l'environnement défini dans les params globaux
	 * @param  [type] $environnement [description]
	 * @return [type]                [description]
	 */
	static function initConnexion($environnement){


		if($environnement=='rv'){
		  $idDB = 'root';
		  $mdpDB = 'root';
		  $DB = 'isas';
		}
		elseif($environnement=='prod'){
		  $idDB = 'informed';
		  $mdpDB = 'no11iugX';
		  $DB = 'informed3';
		}
		$serveur = 'localhost';
		mysql_connect($serveur,$idDB,$mdpDB) or die("Impossible de se connecter au SGBD");
		mysql_select_db($DB) or die("Impossible de se connecter &agrave; la base");

	}

	/**
	 * donne le(s) cabinet(s) current a calculer (donne les noms)
	 * @param  [type] $type_tdb [description]
	 * @return [type]           [description]
	 */
	static function giveCabinetForCalcul($type_tdb){

		if($type_tdb == "region"){
		  $q = "SELECT * FROM temp_dashboard as t inner join account as a ON a.cabinet = t.cabinet WHERE t.is_ok=0 AND is_actif=1 AND t.cabinet in (SELECT cabinet FROM account WHERE region='".$region_consolide."')";
		}
		else if($type_tdb == "cabinet"){
		  $q = "SELECT * FROM account as a  WHERE tdb_export='0000-00-00' LIMIT 1"; // version Rv on les prend tous si ils ont un tdb_export à 0
		  #version pierrot avec table temporaire $q = "SELECT * FROM temp_dashboard as t inner join account as a ON a.cabinet = t.cabinet WHERE a.cabinet='regny' LIMIT 1";
		}
		#echo $q;
		$res = mysql_query($q);
		$aCabinet = array();
		$cpt_patient = $cpt_diab2 = $cpt_cogni = $cpt_hta = 0;
		
		$comptage = array();
		while($tab_cabinet = mysql_fetch_array($res))
		{

		  array_push($aCabinet, $tab_cabinet['cabinet']);
		  $comptage['cpt_patient'] += $tab_cabinet['total_pat'];
		  $comptage['cpt_cogni'] += $tab_cabinet['total_cogni'];
		  $comptage['cpt_diab2'] += $tab_cabinet['total_diab2'];
		  $comptage['cpt_hta'] += $tab_cabinet['total_HTA'];
		}

		#var_dump($aCabinet);
		return array($aCabinet,$comptage);

	}



	/**
	 * renvoi le premier & le dernier lundi qui rentre dans les calculs
	 * on prend toujours le dernier lundi du mois d'avant, et le premier lundi du mois d'après  
	 * @param  string $deb mois de début de calcul exprimé en yyyy-mm
	 * @param  string $fin mois de fin de calcul exprimé en yyyy-mm
	 * @return [type]      [description]
	 */
	static function giveLundiForPeriod($period){
		echo $period;
		$periodTab = explode('-',$period);
		$firstMonday = self::giveFirstMondayFromPeriod($periodTab);
		$lastMonday = self::giveLastMondayFromPeriod($periodTab);
		#echo '<p>'.$period.' -> du '.$firstMonday.' au '.$lastMonday;exit;
		return array($firstMonday,$lastMonday);
	}

	/**
	 * donne la date du lundi précédent le premier jour de la période
	 * @param  array $periodTab c'est le mois du calcul, passé en array
	 * @return [type]            [description]
	 */
	static function giveFirstMondayFromPeriod($periodTab){
		$firstDay = date("w", mktime(0, 0, 0, $periodTab['1'], 1, $periodTab['0']));

		#echo 'premierjour : '.$firstDay.'<br>';
		$timestamp = strtotime($periodTab['0'].'-'.$periodTab['1'].'-01');
		
		switch($firstDay){
			// lundi
			case '1' : $firstMonday = $periodTab['0'].'-'.$periodTab['1'].'-01';
			break;
		
			// mardi
			case '2' : 
			$lundi = strtotime("-1day" ,$timestamp);
			$firstMonday = date("Y-m-d", $lundi);
			break;

			// mercredi
			case '3' : 
			$lundi = strtotime("-2day" ,$timestamp);
			$firstMonday = date("Y-m-d", $lundi);
			break;

			// jeudi
			case '4' : 
			$lundi = strtotime("-3day" ,$timestamp);
			$firstMonday = date("Y-m-d", $lundi);
			break;

			// vendredi
			case '5' : 
			$lundi = strtotime("-4day" ,$timestamp);
			$firstMonday = date("Y-m-d", $lundi);
			break;

			// samedi
			case '6' : 
			$lundi = strtotime("-5day" ,$timestamp);
			$firstMonday = date("Y-m-d", $lundi);
			break;

			// dimanche
			case '0' : 
			$lundi = strtotime("-6day" ,$timestamp);
			$firstMonday = date("Y-m-d", $lundi);
			break;
		}

		return $firstMonday;

	}

	/**
	 * donne le dernier lundi qui dépasse la période
	 * @param  [type] $periodTab [description]
	 * @return [type]            [description]
	 */
	static function giveLastMondayFromPeriod($periodTab){
		$lastNumberDay = date("t", mktime(0, 0, 0, $periodTab['1'], 1, $periodTab['0']));
		$lastDay = date("w", mktime(0, 0, 0, $periodTab['1'], $lastNumberDay, $periodTab['0']));


		echo '<p>dernierjour : '.$lastDay.' '.$periodTab['0'].'-'.$periodTab['1'].'-'.$lastNumberDay.'<br>';
		$timestamp = strtotime($periodTab['0'].'-'.$periodTab['1'].'-'.$lastNumberDay);
		
		switch($lastDay){
			// lundi
			case '1' : $lastMonday = $periodTab['0'].'-'.$periodTab['1'].'-'.$lastNumberDay;
			break;
		
			// mardi
			case '2' : 
			$lundi = strtotime("+6day" ,$timestamp);
			$lastMonday = date("Y-m-d", $lundi);
			break;

			// mercredi
			case '3' : 
			$lundi = strtotime("+5day" ,$timestamp);
			$lastMonday = date("Y-m-d", $lundi);
			break;

			// jeudi
			case '4' : 
			$lundi = strtotime("+4day" ,$timestamp);
			$lastMonday = date("Y-m-d", $lundi);
			break;

			// vendredi
			case '5' : 
			$lundi = strtotime("+3day" ,$timestamp);
			$lastMonday = date("Y-m-d", $lundi);
			break;

			// samedi
			case '6' : 
			$lundi = strtotime("+2day" ,$timestamp);
			$lastMonday = date("Y-m-d", $lundi);
			break;

			// dimanche
			case '0' : 
			$lundi = strtotime("+1day" ,$timestamp);
			$lastMonday = date("Y-m-d", $lundi);
			break;
		}

		return $lastMonday;

	}

	/**
	 * initialse la période suivant si elle est définie ou non
	 * periode en get, ou a récupérer avec le token...
	 * @return [type] [description]
	 */
	static function getPeriod(){
		if(!isset($_GET['period'])){
		  $month = date('m')-1;
		  if(strlen($month)==1){
		    $month='0'.$month;
		  }
		  if($month==12){
		    $year = date('Y-1');
		  }else{
		    $year = date('Y');
		  }
		  $period = $year.'-'.$month;
		}
		else{
		  $period = $_GET['period'];
		}
		return $period;
	}

	/**
	 * calcul le nbre de jours sur la péeriod, pour l'instant 1 seul mois dans le calcul c'est facile
	 * @param  [type] $period [description]
	 * @return [type]         [description]
	 */
	static function calculNbDaysForPeriod($period){
		$periodTab = explode("-",$period);
		$lastNumberDay = date("t", mktime(0, 0, 0, $periodTab['1'], 1, $periodTab['0']));
		
		return $lastNumberDay;

	}

	
	/**
	 * calcul du nombre d'examens saisis sur la période
	 * fonctionne pour les consolidés ou pour les indivivuels
	 * @param  [type] $str_cabinets [description]
	 * @param  [type] $date_start   [description]
	 * @param  [type] $date_end     [description]
	 * @return array   $nbExam            [description]
	 */
	static function compteNbreExamsPeriode($str_cabinets,$date_start,$date_end){
		// Nb exams saisis ou int&eacute;gr&eacute;s / nb exams r&eacute;alis&eacute;s
          // R&eacute;alis&eacute;s : 
          $req = "SELECT count(*) as nb FROM liste_exam as e INNER JOIN dossier as d ON e.id=d.id WHERE d.cabinet IN ('".$str_cabinets."') AND e.date_exam BETWEEN '".$date_start."' AND '".$date_end."'";
          $res=mysql_query($req);
          $nb_exam_realises = mysql_fetch_assoc($res);
          // Saisis ou int&eacute;gr&eacute;s : 
          $req = "SELECT count(*) as nb FROM liste_exam as e INNER JOIN dossier as d ON e.id=d.id WHERE d.cabinet IN ('".$str_cabinets."') AND e.dmaj BETWEEN '".$date_start."' AND '".$date_end."'";
          $res=mysql_query($req);
          //echo "##".$req;
          $nb_exam_saisis = mysql_fetch_assoc($res);

          $nbExam = array();
          $nbExam['nb_exam_realises'] = $nb_exam_realises;
          $nbExam['nb_exam_saisis'] = $nb_exam_saisis;
          
          return $nbExam;

	}


	/**
	 * récuération des dossiers selon cabinet ou région, ça compte 
	 * mais a vérifier ce qui sert vraiment, a priori seulement $regions[$cab] et $dossiers[$region]
	 * @param  [type] $str_cabinets [description]
	 * @param  [type] $region       [description]
	 * @return [type]               [description]
	 */
	function getDossiers($str_cabinets,$region){

		$req="SELECT dossier.cabinet, count(*), nom_cab, region ".
		     "FROM dossier, account ".
		     "WHERE infirmiere!='' and region!='' ".
		     "AND actif='oui' ".
		     "and dossier.cabinet=account.cabinet ".
		     "AND account.cabinet IN ('".$str_cabinets."') ".
		     "GROUP BY nom_cab ".
		     "ORDER BY nom_cab, numero ";

		#echo $req;exit;

		$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

		//if (mysql_num_rows($res)==0) exit ("<p align='center'>Aucun cabinet n'est actif</p>");

		$tcabinet=array();
		$liste_reg=array();

		while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
		  
		  $tcabinet[] = $cab;
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
		   
		}

		$retour['regions'] = $regions;
		$retour['tcabinet'] = $tcabinet;
		$retour['dossiers'] = $dossiers;

		return $retour;


	}	


	/**
	 * liste des consultations par patient dans le cabinet
	 * @param  [type] $str_cabinets [description]
	 * @param  [type] $end_year     [description]
	 * @param  [type] $end_month    [description]
	 * @return array               une liste de dates de consultations
	 */
	function listeConsultsPatients($str_cabinets,$end_year,$end_month,$regions){

		//Liste des consults par patient
		$req="SELECT cabinet, dossier.id, date ".
		     "FROM evaluation_infirmier, dossier ".
		     "WHERE actif='oui' ".
		     "AND evaluation_infirmier.id=dossier.id ".
		     "AND cabinet IN ('".$str_cabinets."') AND date < '".$end_year.'-'.$end_month."-31' ".
		     "ORDER BY cabinet, id, date ";

		
		$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
		// echo '<br>'.$req;
		$id_prec="";

		while(list($cabinet, $id, $date)=mysql_fetch_row($res)){
		  // echo '<br>list($cabinet, $id, $date)'.$cabinet.', '.$id.', '.$date;
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

		return $consult;

	}


	/**
	 * liste des tensions par patient en rcva
	 * @param  [type] $str_cabinets [description]
	 * @param  [type] $end_year     [description]
	 * @param  [type] $end_month    [description]
	 * @param  [type] $regions      [description]
	 * @return [type]               [description]
	 */
	function listeTensionsRCVA($str_cabinets,$end_year,$end_month,$regions){

		//Liste des tensions par patient en RCVA
		$req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
		     "FROM cardio_vasculaire_depart, dossier, liste_exam ".
		     "WHERE actif='oui' ".
		     "AND cardio_vasculaire_depart.id=dossier.id and dossier.id=liste_exam.id ".
		     "and type_exam='systole' ".
		     "and date_exam>'1990-01-01' AND date_exam < '".$end_year.'-'.$end_month."-31'".
		     "AND cabinet IN ('".$str_cabinets."') ".
		     "GROUP BY cabinet, dossier.id, date_exam ";
		     "ORDER BY cabinet, dossier.id, date_exam ";

		$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
		// echo '<br>'.$req;
		$id_prec="";

		while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
		  // echo '<br>list($cabinet, $id, $date, $TaSys)'.$cabinet.', '.$id.', '.$date.', '.$TaSys;

		  $req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
		      "date_exam='$date'";
		  $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
		  // echo '<br>'.$req2;

		  list($TaDia)=mysql_fetch_row($res2);
		  // echo '<br>list($TaDia)'.$TaDia;

		  if(isset($regions[$cabinet])){
		    $id_prec=$id;
		    $dossiers[$cabinet][]=$id;
		    $dossiers[$regions[$cabinet]][]=$id;
		    $cabinets[$id]=$cabinet;
		    $liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
		  }
		}
		//echo '<br>$liste_tension:';
		//echo '<pre>'; var_dump($liste_tension); echo '</pre>';

		return $liste_tension;

	}

	/**
	 * liste des tensions patients en suivi diabete
	 * @param  [type] $str_cabinets [description]
	 * @param  [type] $end_year     [description]
	 * @param  [type] $end_month    [description]
	 * @param  [type] $regions      [description]
	 * @param  [type] $regions      liste des tensions de la requete précédente, on enrichi le array
	 * @return [type] [description]
	 */
	function listeTensionsDIAB($str_cabinets,$end_year,$end_month,$regions,&$liste_tension){

		//echo '<br><br>liste des tensions par patient en suivi diabète';
		//Liste des tensions par patient en suivi diabète
		$req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
		     "FROM suivi_diabete, dossier, liste_exam ".
		     "WHERE actif='oui' ".
		     "AND dossier_id=dossier.id and dossier.id=liste_exam.id ".
		     "and date_exam>'1990-01-01' and type_exam='systole' AND date_exam < '".$end_year.'-'.$end_month."-31' ".
		     "AND cabinet in ('".$str_cabinets."') ".
		     "GROUP BY cabinet, dossier.id, date_exam ";
		     "ORDER BY cabinet, dossier.id, date_exam ";

		$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
		// echo '<br>'.$req;
		$id_prec="";

		while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
		  // echo '<br>list($cabinet, $id, $date, $TaSys)'.$cabinet.', '.$id.', '.$date.', '.$TaSys;

		  $req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
		      "date_exam='$date'";
		  $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
		  // echo '<br>'.$req2;

		  list($TaDia)=mysql_fetch_row($res2);
		  // echo '<br>list($TaDia)'.$TaDia;

		  if(isset($regions[$cabinet])){
		    $id_prec=$id;
		    $cabinets[$id]=$cabinet;
		    $liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
		  }
		}

		return $liste_tension;

	}

	/**
	 * definition des denominateurs de calcul pour a tensions en colonnes 70 a 73
	 * @param  array $listeConsult  liste des consultations
	 * @param  array $liste_tension liste des tensions
	 * @return [type]                [description]
	 */
	function getTauxTensions($listeConsult,$liste_tension){
		
		$denominateur[1] = $denominateur[2] = $denominateur[3] = $denominateur[4] = 0;
		$numerateur[1] = $numerateur[2] = $numerateur[3] = $numerateur[4] = 0;
		
		foreach($listeConsult as $id => $tab_consult){

		  //echo "<br />## ID: ".$id;
		  //echo "<pre>"; var_dump($tab_consult); echo "</pre>"; 
		  //echo '<br>---------------';
		  if(isset($tab_consult) && sizeof($liste_tension[$id]) > 1){
		    //echo "<pre>"; var_dump($liste_tension[$id]); echo "</pre>"; 
		    //echo '<br>---------------';
		    //echo '<br>---------------';
		    
		    $new_assoc_array = array();
		    foreach($tab_consult as $num_consult => $date_consult){
		      $new_assoc_array[$date_consult.'_consult'] = $num_consult;
		    }
		    foreach($liste_tension[$id] as $date_tension => $valeur_tension){
		      $new_assoc_array[$date_tension.'_atension'] = $valeur_tension;
		    }
		    ksort($new_assoc_array);
		    
		    $new_normal_array = array();
		    foreach($new_assoc_array as $date => $valeur){
		      array_push($new_normal_array, $date);
		    }
		    
		    for($i=0; $i < sizeof($new_normal_array); $i++){
		      if(strpos($new_normal_array[$i], '_atension') !== false){
		        if(strpos($new_normal_array[$i + 1], '_atension') !== false){
		          array_shift($new_normal_array);
		        }
		      }
		      elseif(strpos($new_normal_array[$i], '_consult') !== false){
		        break;
		      }
		    }
		    //echo "<pre>@@@ "; var_dump($new_normal_array); echo "</pre>"; 
		    //echo '<hr />';

		    $array_consult = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
		    $cpt_consult = 0;
		    // si la première valeur du tableau est une tension, on peut regarder
		    // 
		    if(strpos($new_normal_array[0], '_atension') !== false && intval($new_assoc_array[$new_normal_array[0]]["TaSys"]) > 140){
		      for($i=0; $i < sizeof($new_normal_array); $i++){
		        if(strpos($new_normal_array[$i], '_atension') !== false){

		        }
		        elseif(strpos($new_normal_array[$i], '_consult') !== false){
		          $cpt_consult++;
		          // si c'est une consultation, on regarde si une valeur de tension juste après
		          // 
		          if(strpos($new_normal_array[$i + 1], '_atension') !== false){
		            //echo "<br />## I: ".$i;
		            // ok valeur de tension juste après une consult => on regarde la valeur si > 140
		            // 
		            //echo "<br> valeur: ".$new_normal_array[$i + 1];
		            //echo "<br> ok on prend denominateur: ".$new_assoc_array[$new_normal_array[$i]];
		            $denominateur[$new_assoc_array[$new_normal_array[$i]]]++;
		            if(intval($new_assoc_array[$new_normal_array[$i + 1]]["TaSys"]) <= 140){
		              //echo "<br> ok on prend numerateur: ".$new_assoc_array[$new_normal_array[$i]];
		              $numerateur[$new_assoc_array[$new_normal_array[$i]]]++;
		            }
		          }
		        }
		        if($cpt_consult > 3) break;  // si plus de 4 consultation, on sort, mais attention la dernière est la 5ième, c'est juste pour ranger la prochaine tension
		      }
		    }
		  }
		  //echo '<hr>';
		}
		echo "<pre>"; var_dump($denominateur); echo "</pre>"; 
		echo "<pre>"; var_dump($numerateur); echo "</pre>"; 



		$change_taux = array();
		for($i = 1; $i <= sizeof($denominateur); $i++){
		  $change_taux[$i] = round(($numerateur[$i] / $denominateur[$i]) * 100);
		}

		$retour = array();
		$retour['denominateur'] = $denominateur; // array
		$retour['numerateur'] = $numerateur; // array
		$retour['change_taux'] = $change_taux; // array

		return $retour;
	}









	/**
	 * affichage des entetes de colonne dans le csv
	 * @return [type] [description]
	 */
	static function giveEntete(){
	
	  // infos cabinet
	  $tab_entete["0"] = "Id cabinet";
	  $tab_entete["1"] = "Nom cabinet";
	  $tab_entete["2"] = "Infirmier(es)";
	  $tab_entete["3"] = "Localisation";
	  $tab_entete["4"] = "Mois concerne";

	  // gestion du temps
	  $tab_entete["5"] = "consultation";
	  $tab_entete["6"] = "% consultation";
	  $tab_entete["7"] = "Gestion dossier";
	  $tab_entete["8"] = "% gestion dossier";
	  $tab_entete["9"] = "Concertation";
	  $tab_entete["10"] = "% concertation";
	  $tab_entete["11"] = "Formation";
	  $tab_entete["12"] = "% formation";
	  $tab_entete["13"] = "Contrib asalee";
	  $tab_entete["14"] = "% contrib asalee";
	  $tab_entete["15"] = "Non attribue";
	  $tab_entete["16"] = "% non attribue";
	  $tab_entete["17"] = "Total";
	  $tab_entete["18"] = "% total";

	  // Analyse activité 
	  $tab_entete["19"] = "Jours retenus";
	  $tab_entete["20"] = "Nb consultations";
	  $tab_entete["21"] = "Consultations/jours";
	  $tab_entete["22"] = "% objectifs";

	  // actes derogatoire
	  $tab_entete["23"] = "Total actes derog";
	  $tab_entete["24"] = "Spirometrie";
	  $tab_entete["25"] = "Troubles cog";
	  $tab_entete["26"] = "Ecg";
	  $tab_entete["27"] = "Exam du pied";
	  $tab_entete["28"] = "Monofilament";
	  $tab_entete["29"] = "Autres suivi diabete";
	  $tab_entete["30"] = "Nb examens saisis";
	  $tab_entete["31"] = "Nb examens mois";

	  // protocoles
	  $tab_entete["32"] = "Patients/protocole";
	  $tab_entete["33"] = "Patient dep diabete";
	  $tab_entete["34"] = "Patient suivi diabete";
	  $tab_entete["35"] = "Patient rcva";
	  $tab_entete["36"] = "Patient trouble cog";
	  $tab_entete["37"] = "Patient bpco";
	  $tab_entete["38"] = "Patient cancer"; // pierre
	  $tab_entete["39"] = "Patient autres types";
	  $tab_entete["40"] = "Patient multiprotocole";
	  $tab_entete["41"] = "Nb patients de la periode";
	  $tab_entete["42"] = "% nouveaux patients";

	  // hba1c
	  $tab_entete["43"] = "1ere HBA1c avant";
	  $tab_entete["44"] = "1ere HBA1c apres";
	  $tab_entete["45"] = "% 1ere HBA1c";
	  $tab_entete["46"] = "2eme HBA1c avant";
	  $tab_entete["47"] = "2eme HBA1c apres";
	  $tab_entete["48"] = "% 2eme HBA1c";
	  $tab_entete["49"] = "3eme HBA1c avant";
	  $tab_entete["50"] = "3eme HBA1c apres";
	  $tab_entete["51"] = "% 3eme HBA1c";
	  $tab_entete["52"] = "4eme HBA1c avant";
	  $tab_entete["53"] = "4eme HBA1c apres";
	  $tab_entete["54"] = "% 4eme HBA1c";
	  $tab_entete["55"] = "5eme HBA1c avant";
	  $tab_entete["56"] = "5eme HBA1c apres";
	  $tab_entete["57"] = "% 5eme HBA1c";
	  $tab_entete["58"] = "6eme HBA1c avant";
	  $tab_entete["59"] = "6eme HBA1c apres";
	  $tab_entete["60"] = "% 6eme HBA1c";
	  
	  // ldl
	  $tab_entete["61"] = "1ere LDL avant";
	  $tab_entete["62"] = "1ere LDL apres";
	  $tab_entete["63"] = "% 1ere LDL";
	  $tab_entete["64"] = "2eme LDL avant";
	  $tab_entete["65"] = "2eme LDL apres";
	  $tab_entete["66"] = "% 2eme LDL";
	  $tab_entete["67"] = "3eme LDL avant";
	  $tab_entete["68"] = "3eme LDL apres";
	  $tab_entete["69"] = "% 3eme LDL";

	  // Evolution tension
	  $tab_entete["70"] = "tension 1ere consult";
	  $tab_entete["71"] = "tension 2eme consult";
	  $tab_entete["72"] = "tension 3eme consult";
	  $tab_entete["73"] = "tension 4eme consult";
	  
	  // par protocoles
	  $tab_entete["74"] = "Nb sipro/patient unique";
	  $tab_entete["75"] = "% EFR";
	  $tab_entete["76"] = "Nb depistage cognitif";
	  $tab_entete["77"] = "% test cognitif";
	  $tab_entete["78"] = "Nb patient total";
	  $tab_entete["79"] = "Nb patient diab type 2";
	  $tab_entete["80"] = "Nb patient risque cardio";
	  $tab_entete["81"] = "Nb patient BPCO";
	  $tab_entete["82"] = "Nb patient cognitif";
	  $tab_entete["83"] = "Publipostage";

	  return $tab_entete;

	}


	/**
	 * calcul du temps passé dans a cabinet, se base sur suivi_temps_passe
	 * @param  [type] $SuiviHebdomadaireTempsPasseMapper [description]
	 * @param  [type] $str_cabinets                      [description]
	 * @param  [type] $first_lundi_final                 [description]
	 * @param  [type] $last_lundi_final                  [description]
	 * @param  [type] $diff_start                        [description]
	 * @param  [type] $diff_end                          [description]
	 * @param  [type] $nb_jour_month                     [description]
	 * @return [type]                                    [description]
	 */
	static function calculTempsPasseCabinet($SuiviHebdomadaireTempsPasseMapper,$str_cabinets,$first_lundi_final,$last_lundi_final,$diff_start,$diff_end,$nb_jour_month){


	   // Activit&eacute; : suivi temps hebdo 
      $SuiviHebdomadaireTempsPasse = new SuiviHebdomadaireTempsPasse();
      $SuiviHebdomadaireTempsPasse->info_asalee = 0;
      $resultTemps = $SuiviHebdomadaireTempsPasseMapper->getObjectsByCabinetsBetweenDates($str_cabinets, date('Y-m-d', $first_lundi_final), date('Y-m-d', $last_lundi_final));
      
      #var_dump(array($SuiviHebdomadaireTempsPasseMapper,$str_cabinets,$first_lundi_final,$last_lundi_final,$diff_start,$diff_end));exit;
      $formation = 0;
      $contribution = 0;
      $dossier = 0;
      $coordination = 0;
      $non_attribue = 0;
      $temps_total_suivi_temps = 0;

      foreach ($resultTemps as $key => $value) 
      {
        
        echo '<h5>temps passé semaine</h5>';
        //echo '<pre>';
        if($value['non_attribue'] != '') echo "<br># cabinet: ".$value['cabinet']." / date : ".$value['date'].'=> non attrib = '.$value['non_attribue'];
        //echo '</pre>';
        if($key == 0)
        {
          echo '<br>#'.$key.' : first => prorata : '.$diff_start;
          $coef_diff = $diff_start;
        }
        else if(($key == sizeof($resultTemps) - 1) && (intval(substr($value['date'], 8, 2)) + 7 > $nb_jour_month))
        {
          echo '<br>#'.$key.' : last => prorata : '.$diff_end;
          $coef_diff = $diff_end;
        }
        else
        {
          //echo '<br>#'.$key.' : middle => prorata : 7';
          $coef_diff = 7;
        }
        //echo "<br>&nbsp;<br>&nbsp;";

        if($value['formation'] != null)
          $formation += (($value['formation']) / 7) * $coef_diff;
        if($value['autoformation'] != null)
          $formation += (($value['autoformation']) / 7) * $coef_diff;
        if($value['stagiaires'] != null)
          $formation += (($value['stagiaires']) / 7) * $coef_diff;
        if($value['tps_contact_tel_patient'] != null)
          $contribution += (($value['tps_contact_tel_patient']) / 7) * $coef_diff;
        if($value['info_asalee'] != null) {
          echo "<br />@@@@ TODAY TODAY: " .$value['info_asalee'] . ' - coef: ' . $coef_diff .' => '.((($value['info_asalee']) / 7) * $coef_diff);
          $dossier += ((($value['info_asalee']) / 7) * $coef_diff);
        }
        if($value['tps_reunion_infirmiere'] != null)
          $formation += (($value['tps_reunion_infirmiere']) / 7) * $coef_diff;
        
      }
      
      $tempsTDB = array();
      $tempsTDB['formation'] = $formation;
      $tempsTDB['contribution'] = $contribution;
      $tempsTDB['dossier'] = $dossier;
      $tempsTDB['coordination'] = $coordination; // temps de concertation medecins
      $tempsTDB['non_attribue'] = $non_attribue;
      $tempsTDB['temps_total_suivi_temps'] = $temps_total_suivi_temps;


	      echo '<p>formation : '.$formation;
          echo '<p>contribution : '.$contribution;
          echo '<p>dossier : '.$dossier;
          echo '<p>non_attribue_periode (cumulé): '.$non_attribue_periode.'</p>';

      #var_dump($tempsTDB);exit;
      return ($tempsTDB);


	}



	function getExamensCabinet($SuiviHebdomadaireTempsPasse,$evaluationInfirmierMapper,$str_cabinets,$first_lundi_final,$last_lundi_final,$date_start,$date_end){

		echo '<h4>getExamensCabinet</h4>';
		// Activit&eacute; : liste_exams 
          $evaluationInfirmier = new EvaluationInfirmier();
          $evaluationInfirmier->date = dateToMysqlDate($SuiviHebdomadaireTempsPasse->date);
          $saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetsBetweenDate($str_cabinets, date('Y-m-d', $first_lundi_final), date('Y-m-d', $last_lundi_final));
          $consultation = 0;
          $aJourconsult = array();
          $aPatient = array();
          $aProtocole = array('dep_diab'=>array(), 
          					  'suivi_diab'=>array(), 
          					  'rcva'=>array(), 
          					  'cognitif'=>array(), 
          					  'bpco'=>array(), 
          					  'automesure'=>array(), 
          					  'autres'=>array(), 
          					  'uterus'=>array(), 
          					  'sein'=>array(), 
          					  'hemocult'=>array(), 
          					  'colon'=>array()
          					  );
          $aPatientProtocole = array();
          $nb_new_mois = 0;
          $aNewPatientMois = array();

          $examsDerogatoire = array();
          $examsDerogatoire['spiro'] = 0;
          $examsDerogatoire['cogn'] = 0;
          $examsDerogatoire['ecg'] = 0;
          $examsDerogatoire['pied'] = 0;
          $examsDerogatoire['monofil'] = 0;
          $examsDerogatoire['autre'] = 0;
          $nb_saisie_inf_periode = 0;
          //echo "<hr/>saisieInfirmiere: ";
          
          #var_dump($saisieInfirmiere);exit;
          foreach ($saisieInfirmiere as $key => $value) 
          {
            //echo "<br>".$value['date'].' >= '.$date_start.' /// '.$value['date'].' <= '.$date_end;
            if(($value['date'] >= $date_start) && ($value['date'] <= $date_end))
            {
                  // echo '<pre style="background-color:#CCC;">';
                  // echo $value["duree"];
                  // echo '</pre>';

              $nb_saisie_inf_periode++;
              $consultation += $value['duree'];
              if(!in_array($value['date'], $aJourconsult))
              { 
                array_push($aJourconsult, $value['date']);
              }
              if(!in_array($value['numero'], $aPatient))
              {
                array_push($aPatient, $value['numero']);
              }
              $aTypeConsult = explode(',', $value['type_consultation']); // les types consultation peuvent etre multiples
              for($i=0; $i<sizeof($aTypeConsult); $i++)
              {
                //echo '<br>#'.$value['numero'].' / '.$aTypeConsult[$i];
                if(isset($aProtocole[$aTypeConsult[$i]]))
                {
                  if(!in_array($value['numero'], $aProtocole[$aTypeConsult[$i]]))
                    array_push($aProtocole[$aTypeConsult[$i]], $value['numero']);

                  foreach ($aProtocole as $key => $aValueId) 
                  {
                    //echo '<br>###-------'.$key;
                    if($key != $aTypeConsult[$i])
                    {
                      //echo '<br>####if('.$key.' != '.$aTypeConsult[$i].')';
                      if(in_array($value['numero'], $aValueId))
                      {
                        //echo '<br>####if(inArray('.$value['numero'].')';
                        array_push($aPatientProtocole, $value['numero']);
                        //echo "<br>@@push(".$value['numero'].')';
                      }
                    }
                  }
                }
              }
              if(($value['dcreat'] >= $date_start) && ($value['dcreat'] <= $date_end) && (!in_array($value['numero'], $aNewPatientMois)))  // nb new patient dans le mois
                array_push($aNewPatientMois, $value['numero']);
                //$nb_new_mois++;
              
              
              $TpsConsultation = array();
              $TpsConsultation[$value['type_consultation']] = $TpsConsultation[$value['type_consultation']]+intval($value['duree']);

              $examsDerogatoire['spiro'] += ($value['spirometre'] != NULL) ? $value['spirometre'] : 0;//((($value['spirometre_seul'] != NULL) ? $value['spirometre_seul'] : 0) + (($value['spirometre'] != NULL) ? $value['spirometre'] : 0));
              $examsDerogatoire['cogn'] += $value['t_cognitif'];
              $examsDerogatoire['ecg'] += ($value['ecg'] != NULL) ? $value['ecg'] : 0;//((($value['ecg_seul'] != NULL) ? $value['ecg_seul'] : 0) + (($value['ecg'] != NULL) ? $value['ecg'] : 0));
              $examsDerogatoire['pied'] += $value['exapied'];
              $examsDerogatoire['monofil'] += $value['monofil'];
              $examsDerogatoire['autre'] += $value['hba'];
            }
          }

          $retour = array();
          $retour['TpsConsultation'] = $TpsConsultation;
          $retour['examsDerogatoire'] = $examsDerogatoire;
          $retour['aNewPatientMois'] = $aNewPatientMois;
          $retour['aPatientProtocole'] = $aPatientProtocole;
          $retour['nb_saisie_inf_periode'] = $nb_saisie_inf_periode;
          $retour['aJourconsult'] = $aJourconsult;
          $retour['aPatient'] = $aPatient;
          $retour['aProtocole'] = $aProtocole;
          #var_dump($retour);exit;
          return $retour;
	}








	// HBA1C - calcul des consultations de 1 à 6, normalement mm calculs
	// a terminer @ rv le 8_09_2016

	function calculHBA1C($nbConsult,$str_cabinets,$end_year,$end_month){

		$req1="SELECT account.cabinet, nom_cab ".
		     "FROM dossier, account ".
		     "WHERE infirmiere!='' and region!='' ".
		     "AND dossier.cabinet=account.cabinet ".
		     "AND actif='oui' ".
		     "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
		     "GROUP BY nom_cab ".
		     "ORDER BY nom_cab, numero ";

		$res1=mysql_query($req1) or die("erreur SQL:".mysql_error()."<br>$req1");

		if (mysql_num_rows($res1)==0) {
		  //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
		}
		$tcabinet=array();


		while(list($cab, $pat, $ville) = mysql_fetch_row($res1)) {
		   $tcabinet[] = $cab;
		   $tville[]=$ville;
		   $avantsup7[$cab]=0;
		   $nb_dossierssup7[$cab]=0;
		   $apressup7[$cab]=0;


		   $avantinf7[$cab]=0;
		   $nb_dossiersinf7[$cab]=0;
		   $apresinf7[$cab]=0;
		}




		$req1= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
		    "evaluation_infirmier.date as date_consult, DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
		    "FROM dossier, liste_exam, evaluation_infirmier, suivi_diabete ".
		    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id  and ".
		    "dossier.cabinet IN ('".$str_cabinets."') and ".
		    "type_exam='HBA1c' and suivi_diabete.dossier_id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$end_year.'-'.$end_month.'-31'."' ".
		    "ORDER BY cabinet, dossier.id, date_consult, date_exam ";
		    
		$res1=mysql_query($req1) or die("erreur SQL:".mysql_error()."<br>$req1");

		$id_prec="";
		$cabinet_prec="";
		while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res1)){

		  if(isset($nb_dossierssup7[$cabinet_prec])){
		    if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
		      if($id_prec!=""){
		        if($hba_suiv!=0 && $nb_consult==$nbConsult){
		          if($hba_prec>7){
		            $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
		            $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$hba_prec;
		            $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$hba_suiv;

		          }
		          else{
		            $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
		            $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$hba_prec;
		            $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$hba_suiv;
		  
		          }
		        }
		      }
		      $date_consult_prec=$date_consult;
		      $cabinet_prec=$cabinet;
		      
		      $hba_prec=$ResHBA;
		      $hba_suiv=0;
		      $id_prec=$dossier_id;
		      $deltaj_prec=$deltaj;
		    }
		    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
		      if($date_consult_prec==$date_consult){
		        if($deltaj<0){//Le HBA est avant la consult
		          $hba_prec=$ResHBA;
		          $deltaj_prec=$deltaj;
		        }
		        else{//Un HBA après la consult => on regarde s'il a déjà été enregistré
		          if($hba_suiv==0){
		            $hba_suiv=$ResHBA;
		            $deltaj_suiv=$deltaj;
		          }
		        }
		      }

		    }
		  }//fin if isset($nb_dossierssup7[$cabinet_prec]
		  else{
		    $date_consult_prec=$date_consult;
		    $cabinet_prec=$cabinet;
		    $hba_prec=$ResHBA;
		    $hba_suiv=0;
		    $id_prec=$dossier_id;
		    $deltaj_prec=$deltaj;
		  }
		}//fin while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res))




	}



}


