<?php


class Dashboard {



    static function getByCabinetsAndAllowedCodes($cabinet,$lesCodes){

        $req = "SELECT * FROM dashboard_publishing where cabinet = '$cabinet' and dashcode IN ($lesCodes) order by date DESC";
        $sql = mysql_query($req);

        #var_dump($sql);
        while ($row = mysql_fetch_array($sql)){
            $liste[] = $row;
        }
        #var_dump($liste);
        return $liste;
    }

    /**
     * enregistre le résultat d'un TDB pour 1 période pour exploiter les données plus facilement dasn un second temps
     * @param  [type] $tdb [description]
     * @return [type]      [description]
     */
    static function record($tdb){

        $periode=substr($tdb[4],0,7);
        $date_debut = substr($tdb[4], 3, 4) . "-" . substr($tdb[4],0, 2) . '-01 00:00:00';
        $localisation = addslashes($tdb[3]);
        $nom_cabinet = addslashes($tdb[1]);
        $req = "INSERT INTO dashboard_results set
			cabinet = '$tdb[0]',
			nom_cabinet = '$nom_cabinet',
			infirmieres = '$tdb[2]',
			localisation = '$localisation',
			periode = '$periode',
			consultation = '$tdb[5]',
			consultation_percent = '$tdb[6]',
			gestion_dossier = '$tdb[7]',
			gestion_dossier_percent = '$tdb[8]',
			concertation = '$tdb[9]',
			concertation_percent = '$tdb[10]',
			formation = '$tdb[11]',
			formation_percent = '$tdb[12]',
			contrib_asalee = '$tdb[13]',
			contrib_asalee_percent = '$tdb[14]',
			non_attribue = '$tdb[15]',
			non_attribue_percent = '$tdb[16]',
			total = '$tdb[17]',
			total_percent = '$tdb[18]',
			jours_retenus = '$tdb[19]',
			nbre_consultations = '$tdb[20]',
			consultations_jours = '$tdb[21]',
			objectifs_percent = '$tdb[22]',
			total_actes_derog = '$tdb[23]',
			spirometrie = '$tdb[24]',
			troubles_cogn = '$tdb[25]',
			ecg = '$tdb[26]',
			examen_du_pied = '$tdb[27]',
			monofilament = '$tdb[28]',
			autre_suivi_diabete = '$tdb[29]',
			nb_examens_saisis = '$tdb[30]',
			nb_examens_mois = '$tdb[31]',
			patient_protocole = '$tdb[32]',
			patient_dep_diabete = '$tdb[33]',
			patient_suivi_diabete = '$tdb[34]',
			patient_rcva = '$tdb[35]',
			patient_trouble_cogn = '$tdb[36]',
			patient_bpco = '$tdb[37]',
			patient_cancer = '$tdb[38]',
			patient_autre_type = '$tdb[39]',
			patient_multiprotocole = '$tdb[40]',
			nb_patient_periode = '$tdb[41]',
			nb_patient_periode_percent = '$tdb[42]',
			hba1c_1_avant = '$tdb[43]',
			hba1c_1_apres = '$tdb[44]',
			hba1c_1_percent = '$tdb[45]',
			hba1c_2_avant = '$tdb[46]',
			hba1c_2_apres = '$tdb[47]',
			hba1c_2_percent = '$tdb[48]',
			hba1c_3_avant = '$tdb[49]',
			hba1c_3_apres = '$tdb[50]',
			hba1c_3_percent = '$tdb[51]',
			hba1c_4_avant = '$tdb[52]',
			hba1c_4_apres = '$tdb[53]',
			hba1c_4_percent = '$tdb[54]',
			hba1c_5_avant = '$tdb[55]',
			hba1c_5_apres = '$tdb[56]',
			hba1c_5_percent = '$tdb[57]',
			hba1c_6_avant = '$tdb[58]',
			hba1c_6_apres = '$tdb[59]',
			hba1c_6_percent = '$tdb[60]',
			ldl_1_avant = '$tdb[61]',
			ldl_1_apres = '$tdb[62]',
			ldl_1_percent = '$tdb[63]',
			ldl_2_avant = '$tdb[64]',
			ldl_2_apres = '$tdb[65]',
			ldl_2_percent = '$tdb[66]',
			ldl_3_avant = '$tdb[67]',
			ldl_3_apres = '$tdb[68]',
			ldl_3_percent = '$tdb[69]',
			tension_1 = '$tdb[70]',
			tension_2 = '$tdb[71]',
			tension_3 = '$tdb[72]',
			tension_4 = '$tdb[73]',
			nb_spiro_unique = '$tdb[74]',
			efr_percent = '$tdb[75]',
			nb_depistage_cognitif = '$tdb[76]',
			test_cognitif_percent = '$tdb[77]',
			nb_patient_total = '$tdb[78]',
			nb_patient_diab_type2 = '$tdb[79]',
			nb_patient_risque_cardio = '$tdb[80]',
			nb_patient_bpco = '$tdb[81]',
			nb_patient_cogn = '$tdb[82]',
			publipostage = '$tdb[83]',
			date_edition = now(),
			date_periode = '$date_debut'
		";

        $sql = mysql_query($req);
    }

    /**
     * gestion et stockage d'un identifiant unique type token pour ne pas refaire tous les calculs de date...
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    static function recordToken($token,$period,$firstMonday,$lastMonday,$nbjours){
        $req = "REPLACE INTO dashbord_token set
		token = '$token',
		period = '$period',
		firstMonday = '$firstMonday',
		lastMonday = '$lastMonday',
		nbjours = '$nbjours',
		datemaj = now()";
        #echo $req;
        $sql = mysql_query($req);
    }

    /**
     * récupération des infos dashboard quand on connait la période
     * @param  [type] $period [description]
     * @return [type]         [description]
     */
    static function getInfoByPeriod($period){
        $req = "SELECT * FROM dashbord_token where period = '$period' ";
        $sql = mysql_query($req);
        $row = mysql_fetch_assoc($sql);
        return $row;
    }


    static function listeToken(){
        $req = "SELECT * FROM dashbord_token order by datemaj DESC ";
        $sql = mysql_query($req);
        while($row = mysql_fetch_assoc($sql)){
            $results[] = $row;
        }

        return $results;
    }

    /**
     * COMPTE LE nbre de tdb pour chaque périodes, stockés dans dashboard_results (résulat des tableaux de bord)
     * @return [type] [description]
     */
    static function calculForAllPeriod(){

        $req = "SELECT periode,count(cabinet) as nbre FROM dashboard_results where periode!='' group by periode order by date_edition DESC ";


        $sql = mysql_query($req);

        while($row = mysql_fetch_assoc($sql)){
            $results[] = $row;
        }
        #var_dump($results);exit;
        return $results;
    }
}
 
