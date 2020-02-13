<?php

require_once("../../persistence/ConnectionFactory.php");

// recup des WS pour le refrentiel infirmi&egrave;res
// connexion aux WS
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

mysql_connect($hostname_mysql, $username_mysql, $password_mysql) or die("Impossible de se connecter au SGBD");
mysql_select_db($database_mysql) or die("Impossible de se connecter &agrave; la base");

$date_end = date('Y-m-31', strtotime('-1 month'));
$date_start = date('Y-m-31', strtotime('-13 month'));

//if(isset($_POST['region'])) {
	$region = "Nouvelle Aquitaine";//$_POST['region'];
	
	//$q = "SELECT * FROM account WHERE region='".addslashes(strip_tags($region))."'";
	switch ($region) {
		case 'Occitanie': $str_search = "region='Languedoc-Roussillon' OR region='".utf8_decode("Midi-Pyrénées")."'"; break;
		case 'Nouvelle Aquitaine': $str_search = "region='Poitou-Charentes' OR region='Aquitaine'"; break;
	}
	$q = "SELECT * FROM account WHERE " . $str_search . " ORDER BY region, cabinet";
	$liste_cabinet = mysql_query($q);

	$filename = "dashboard_ARS_" . $region . "_" . date('Y-m-d H:i') . ".csv";
	$fname = tempnam("/tmp", $filename);
	$fh=fopen($fname, "rb");
	
	$values = array();

	while($tab_cabinet = mysql_fetch_assoc($liste_cabinet)) {
	  	$current_cabinet = $tab_cabinet['cabinet'];
	  	
	  	$q = "SELECT * FROM dashboard_results WHERE cabinet='".addslashes(strip_tags($current_cabinet))."' AND date_periode >='".$date_start."' AND date_periode <= '".$date_end."'";
	  	$liste_dashboard = mysql_query($q);

	  	$q = "SELECT * FROM account WHERE cabinet='".addslashes(strip_tags($current_cabinet))."'";
	  	$cabinet = mysql_fetch_assoc(mysql_query($q));
	  
	  	// get docteur et infirimieres
	  	$listeInf = '';
	  	$infirmieresDuCabinet = GetLoginsByCab($cabinet['cabinet'], $status);
		foreach($infirmieresDuCabinet as $key=>$inf){
			$listeInf .=$inf['prenom'].' '.$inf['nom'].', ';
		}
	  	$listeInf = substr(utf8_decode($listeInf), 0, -2);

	  	$query = "select nom from medecin where cabinet='".addslashes(strip_tags($cabinet['cabinet']))."'";
	  	$result = mysql_query($query);
	  	$strMedecins = "";
	  	while($tab = mysql_fetch_array($result)) {
	    	$strMedecins .= $tab['nom'].", ";
	  	}
	  	$strMedecins = substr($strMedecins, 0, strlen($strMedecins) - 2);

		
	  	$cpt_patient = $cabinet['total_diab2'] + $cabinet['total_cogni'] + $cabinet['total_HTA'];

	    // calcul des "Nombre total et par types de pathologie de patients ayant bénéficié"
	    $periode_cpt_depistage_diab = $periode_cpt_suiv_diab = $periode_cpt_rcva = $periode_cpt_bpco = $periode_cpt_cognitif = $periode_cpt_tabac = 0;
	    $periode_cpt_tension = array(
	      '1_consult' => 0,
	      '2_consult' => 0,
	      '3_consult' => 0,
	      '4_consult' => 0,
	    );
	    while($tab = mysql_fetch_assoc($liste_dashboard)) {

	      $periode_cpt_depistage_diab += $tab['patient_dep_diabete'];
	      $periode_cpt_suiv_diab += $tab['patient_suivi_diabete'];
	      $periode_cpt_rcva += $tab['patient_rcva'];
	      $periode_cpt_bpco += $tab['patient_bpco'];
	      $periode_cpt_cognitif += $tab['patient_trouble_cogn'];
	      $periode_cpt_tabac += $tab['patient_sevrage_tabac'];
	      
	      $periode_cpt_tension['1_consult'] += intval(str_replace(' %', '', $tab['tension_1']));
	      $periode_cpt_tension['2_consult'] += intval(str_replace(' %', '', $tab['tension_2']));
	      $periode_cpt_tension['3_consult'] += intval(str_replace(' %', '', $tab['tension_3']));
	      $periode_cpt_tension['4_consult'] += intval(str_replace(' %', '', $tab['tension_4']));
	    }


	    // calcul des "Nombre total et par type de pathologies de patients inclus dans le protocole"
	    $q = "SELECT e.id, e.type_consultation FROM evaluation_infirmier AS e INNER JOIN dossier AS d ON e.id=d.id WHERE cabinet='" . $current_cabinet . "' AND e.date < '" . $date_end . "'";
	    $liste_evaluation = mysql_query($q);
	    $total_cpt = array(
	      'dep_diab' => array(),
	      'suivi_diab' => array(),
	      'rcva' => array(),
	      'bpco' => array(),
	      'cognitif' => array(),
	      'automesure' => array(),
	      'sevrage_tabac' => array(),
	    );
	    while($tab = mysql_fetch_assoc($liste_evaluation)) {
	      $liste_type = explode(',', $tab['type_consultation']);
	      for($i = 0; $i < count($liste_type); $i++) {
	        if(!in_array($tab['id'], $total_cpt[$liste_type[$i]])) {
	          array_push($total_cpt[$liste_type[$i]], $tab['id']);
	        }
	      }
	    }

	    // calcul "Taux de patients ayant arrêté de fumer"
	    $q = "SELECT count(distinct(s.numero)) as nb_arret_tabac FROM sevrage_tabac AS s INNER JOIN dossier AS d ON s.numero=d.id WHERE cabinet='" . $current_cabinet . "' AND s.date >='".$date_start."' AND s.date <= '".$date_end."'  AND darret<>'0000-00-00' AND darret <= '".$date_end."'";
	    $nb_arret_tabac = mysql_fetch_assoc(mysql_query($q));

	    // calcul hba1c
	    $q = "SELECT e.id, date_exam, resultat1 FROM liste_exam AS e INNER JOIN dossier AS d ON e.id=d.id WHERE type_exam='hba1c' AND cabinet='" . $current_cabinet . "' AND date_exam >='".$date_start."' AND date_exam <= '".$date_end."'";
	    $liste_exam = mysql_query($q);
	    $temp_id = array();
	    $temp_retenu = array();
	    while($tab = mysql_fetch_assoc($liste_exam)) {
	      if(!in_array($tab['id'], $temp_id)) {
	        array_push($temp_id, $tab['id']);
	        $temp_retenu[$tab['id']] = $tab;
	      }
	      if($tab['date_exam'] > $temp_retenu[$tab['id']]['date_exam']) {
	        $temp_retenu[$tab['id']] = $tab;
	      }
	    }
	    $cpt_hba1c_inf = $cpt_hba1c_middle = $cpt_hba1c_sup = 0;
	    foreach ($temp_retenu as $key => $value) {
	      if($value['resultat1'] < 6.5) {
	        $cpt_hba1c_inf++;
	      }
	      elseif($value['resultat1'] >= 6.5 && $value['resultat1'] <= 8) {
	        $cpt_hba1c_middle++;
	      }
	      else {
	        $cpt_hba1c_sup++;
	      }
	    }

		$item = array(
			"cabinet"							=> $cabinet['cabinet'],
			"date debut"						=> date('d/m/Y', strtotime($date_start)),
			"date fin"							=> date('d/m/Y', strtotime($date_end)),
			"date libellé"						=> "Evaluation du ".date('d/m/Y', strtotime($date_start))." au ".date('d/m/Y', strtotime($date_end)),
			"equipe"							=> $cabinet['nom_complet'],
			"medecins"							=> $strMedecins,
			"infirmières"						=> $listeInf,
			"ville"								=> $cabinet['ville'],
			"points positifs"					=> "> Nombreuses demandes spontanées émanant des médecins comme des infirmières\r> L'activité des infirmières se traduit par un gain de temps de 20' par consultation de fond (patient diabétique déséquilibré) et de 7' pour les consultations de suivi.", 
			"difficultés rencontrées"			=>	"> Il arrive que les déléguées ne disposent pas de bureaux fixes et sont donc amenée à changer de bureaux en fonction des disponibilités du jour\r> Rendez-vous non honoré par les patients qui freine l'activité des infirmières",
			"bénéficié / dépistage du diabète"	=>	$periode_cpt_depistage_diab,
			"bénéficié / suivi du diabète"		=>	$periode_cpt_suiv_diab,
			"bénéficié / suivi RVCA"			=>	$periode_cpt_rcva,
			"bénéficié / suivi BPCO"			=>	($periode_cpt_bpco + $periode_cpt_tabac),
			"bénéficié / troubles cognitifs"	=>	$periode_cpt_cognitif,
			"inclus / dépistage du diabète"		=>	count($total_cpt["dep_diab"]),
			"inclus / suivi du diabète"			=>	count($total_cpt["suivi_diab"]),
			"inclus / suivi RVCA"				=>	count($total_cpt['rcva']) + count($total_cpt['automesure']),
			"inclus / suivi BPCO"				=>	count($total_cpt['bpco']) + count($total_cpt['sevrage_tabac']),
			"inclus / troubles cognitifs"		=>	count($total_cpt['cognitif']),
			"activité / dépistage du diabète"	=> round((count($total_cpt["dep_diab"]) / $cabinet['total_diab2']) * 100, 2) . "%",
			"activité / suivi du diabète"		=> round((count($total_cpt["suivi_diab"]) / $cabinet['total_diab2']) * 100, 2) . "%",
			"activité / suivi RVCA "			=> round(((count($total_cpt['rcva']) + count($total_cpt['automesure'])) / $cabinet['total_HTA']) * 100, 2) . "%",
			"activité / suivi BPCO"				=> round(((count($total_cpt['bpco']) + count($total_cpt['sevrage_tabac'])) / (0.25 * 0.72 * $cpt_patient)) * 100, 2) . "%",
			"activité / troubles cognitifs"		=> round((count($total_cpt['cognitif']) / $cabinet['total_cogni']) * 100, 2) . "%",
			"activité / commentaire"			=> "Bien que variable suivant l'historique des pratiques du cabinet, les patients diabétiques et à risque cardiovasculaire commencent à être vus au démarrage, suivi des patients éligibles aux protocoles  BPCO et troubles cognitifs",
			"gestion risques / commentaire"		=> "Il n'y a pas eu d'événements indésirables liés au protocole de coopération.",
			"satisfaction / acceptation"		=> "Les enquêtes de satisfaction patient montrent une très bonne adhésion aux échanges avec les déléguées avec une notation moyenne de 18.2/20 des 13 assertions jontes en annexe",
			"satisfaction / délégants"			=> "> Sur l'ensemble de médecins adhérent, moins d'une dizaine de cas ont donné lieu à un arrêt ou un changement de déléguée \r> Le % de MG ayant arrêté Asalée depuis qu'il existe des statistiques est de l'ordre de 6,5% (13/198)\r> La formalisation des réunions de concertation déléguant/délégué permet de résoudre la quasi-totalité des difficultés qui pourrait apparaitre.",
			"satisfaction / délégués"			=> "> De même, sur l'ensemble des infirmières, moins d'une dizaine de cas ont donné lieu à des arrêts ou des changements de cabinet",
			"satisfaction / refus"				=> "> Il n'y a pas de refus \"franc\" identifié, en revanche, il peut arriver qu'un patient n'honore pas un RV",
			"satisfaction / sortie"				=> "> Il n'y a pas de sortie \"franche\" identifié, en revanche, il peut arriver qu'un patient n'honore pas un RV",
			"HbA1C / < à 6,5"					=> round(($cpt_hba1c_inf / count($temp_retenu)) * 100, 2) . "%",
			"HbA1C / entre 6,5 et 8"			=> round(($cpt_hba1c_middle / count($temp_retenu)) * 100, 2) . "%",
			"HbA1C / > à 8"						=> round(($cpt_hba1c_sup / count($temp_retenu)) * 100, 2) . "%",
			"RCVA / 1ere cs"					=> round($periode_cpt_tension['1_consult'] / 12) . "%",
			"RCVA / 2 cs"						=> round($periode_cpt_tension['2_consult'] / 12) . "%",
			"RCVA / 3 cs"						=> round($periode_cpt_tension['3_consult'] / 12) . "%",
			"RCVA / 4 cs"						=> round($periode_cpt_tension['4_consult'] / 12) . "%",
			"arret tabac"						=> $nb_arret_tabac['nb_arret_tabac']
		);

		array_push($values, $item);
		
	}
	

	$entete = array_keys($item);
	$str = "";
	foreach ($entete as $value) {
		$str .= '"' . $value . '"';
		$str .= ';';
	}
	$str .= "\n";

    for($i = 0; $i < count($values); $i++) {
    	foreach ($values[$i] as $value) {
    		$str .= '"' . $value . '"';
    		$str .= ';';
    	}
    	$str .= "\n";
    }
    
    file_put_contents($fname, utf8_decode($str));

	header("Content-Type: application/x-msexcel; name=\"".$filename."\"");
	header("Content-Disposition: inline; filename=\"".$filename."\"");
	
	fpassthru($fh);
	unlink($fname);
//}
?>