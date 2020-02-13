<?php
set_time_limit(1000);
$tspdeb = strtotime(date('Y-m-d H:i:s'));
ini_set("memory_limit","4096M");

require_once("bean/dashboard.php");
require_once("bean/SuiviHebdomadaireTempsPasse.php");
require_once("bean/SuiviReunionMedecin.php");


require_once("persistence/FicheCabinetMapper.php");
require_once("persistence/SuiviHebdomadaireTempsPasseMapper.php");
require_once("persistence/EvaluationInfirmierMapper.php");echo "ok5";
require_once("persistence/SuiviReunionMedecinMapper.php");
require_once("persistence/ConnectionFactory.php");

require_once("bean/beanparser/htmltags.php");

require_once("view/common/vars.php");

require_once("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

error_log("hello ",0);

/**
 * calcul du coeficient multiplicateur pour le prorata des r�sultats sur la semaine..
 * @return [type] [description]
 */
function giveProrataForWeek($key,$weekCurrent,$diff_start,$diff_end,$nb_jour_month,$resultTemps){

    if($key == 0)
    {
        #echo '<br>#'.$key.' : first => prorata : '.$diff_start;
        $coef_diff = $diff_start;
    }
    else if(($key == sizeof($resultTemps) - 1) && (intval(substr($weekCurrent, 8, 2)) + 7 > $nb_jour_month))
    {
        #echo '<br>#'.$key.' : last => prorata : '.$diff_end;
        $coef_diff = $diff_end;
    }
    else
    {
        //echo '<br>#'.$key.' : middle => prorata : 7';
        $coef_diff = 7;
    }
    return $coef_diff;
}

/**
 * applique la prorata � la valeur en fonction du nombre de jours dans la semaine
 * @param  [type] $value     [description]
 * @param  [type] $coef_diff [description]
 * @return [type]            [description]
 */
function proratise($value,$coef_diff){
    return round(($value / 7) * $coef_diff,2);
}

function convertiJours($value){
    return round(($value / 420),2);
}

/**
 * applicque un coeficient a chaque type de consultation pour estimer le temps de pr�paration des dossiers
 * coefficients identiques dans le suivi hebdo du temps pass�
 * 09/03/2017 : modif pierre : uterus+sein+colon => taux � 0
 * @param  [type] $TpsConsultation [description]
 * @return [type]                  [description]
 */
function calculTempsPrepaDossiers($TpsConsultation){
    $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) +
        ($TpsConsultation['dep_diab']*0.25) +
        ($TpsConsultation['rcva']*0.25) +
        ($TpsConsultation['bpco']*0.2) +
        ($TpsConsultation['cognitif']*0.1) +
        ($TpsConsultation['autres']*0.2) +
        ($TpsConsultation['automesure']*0.2) +
        // ($TpsConsultation['uterus']*0.2) +
        // ($TpsConsultation['sein']*0.2) +
        // ($TpsConsultation['colon']*0.2) +
        ($TpsConsultation['sevrage_tabac']*0.2)
    );
    return $tempsPrepaBilanConsultation;
}

function calculTempsPrepaDossiers2($duree, $type){
    $temps = 0;
    switch($type) {
        case 'suivi_diab': $temps = $duree * 0.25; break;
        case 'dep_diab': $temps = $duree * 0.25; break;
        case 'rcva': $temps = $duree * 0.25; break;
        case 'bpco': $temps = $duree * 0.2; break;
        case 'cognitif': $temps = $duree * 0.1; break;
        case 'autres': $temps = $duree * 0.2; break;
        case 'automesure': $temps = $duree * 0.2; break;
        case 'sevrage_tabac': $temps = $duree * 0.2; break;
        // case 'uterus': $temps = $duree * 0.2; break;
        // case 'sein': $temps = $duree * 0.2; break;
        // case 'colon': $temps = $duree * 0.2; break;
    }
    return $temps;
}

/* millau, montdidier, pierrepont */

/*
 *
 */
$type_tdb = "cabinet"; // region, cabinet

$region_consolide = "";


// pour le stdb sur 1 seul mois
#$my_date_start_lundi = '2015-02-28'; $my_date_end_lundi = '2016-02-01'; // janvier 2016
#$my_date_start_lundi = '2016-02-01'; $my_date_end_lundi = '2016-02-29'; // fevrier 2016
#$my_date_start_lundi = '2016-02-29'; $my_date_end_lundi = '2016-04-04'; // mars 2016
#$my_date_start_lundi = '2016-03-28'; $my_date_end_lundi = '2016-05-02'; // avril 2016
#$my_date_start_lundi = '2016-04-24'; $my_date_end_lundi = '2016-06-06'; // mai 2016
#$my_date_start_lundi = '2016-05-30'; $my_date_end_lundi = '2016-07-04'; // juin 2016
#$my_date_start_lundi = '2016-06-27'; $my_date_end_lundi = '2016-08-01'; // juillet 2016
#$my_date_start_lundi = '2016-08-01'; $my_date_end_lundi = '2016-09-05'; // aout 2016
#$my_date_start_lundi = '2016-08-29'; $my_date_end_lundi = '2016-10-03'; // sept 2016
#$my_date_start_lundi = '2016-09-26'; $my_date_end_lundi = '2016-10-31'; // oct 2016
#$my_date_start_lundi = '2016-10-31'; $my_date_end_lundi = '2016-12-05'; // nov 2016
#$my_date_start_lundi = '2016-11-28'; $my_date_end_lundi = '2017-01-02'; // dec 2016
#$my_date_start_lundi = '2016-12-26'; $my_date_end_lundi = '2017-02-06'; // jav 2017
#$my_date_start_lundi = '2015-12-28'; $my_date_end_lundi = '2017-01-02'; // janvier � dec 2016
#$my_date_start_lundi = '2016-12-26'; $my_date_end_lundi = '2017-02-06'; // janvier 2017
#$my_date_start_lundi = '2017-01-30'; $my_date_end_lundi = '2017-03-06'; // f�vrier 2017
#$my_date_start_lundi = '2017-01-30'; $my_date_end_lundi = '2017-03-06'; // mars 2017
#$my_date_start_lundi = '2017-03-27'; $my_date_end_lundi = '2017-05-01'; // avril 2017
// $my_date_start_lundi = '2017-05-01'; $my_date_end_lundi = '2017-06-05'; // mai 2017
// $my_date_start_lundi = '2017-05-29'; $my_date_end_lundi = '2017-07-03'; // juin 2017
// $my_date_start_lundi = '2017-06-26'; $my_date_end_lundi = '2017-08-07'; // juillet 2017
// $my_date_start_lundi = '2017-07-31'; $my_date_end_lundi = '2017-09-04'; // aout 2017
//$my_date_start_lundi = '2017-08-28'; $my_date_end_lundi = '2017-10-02'; // septembre 2017
##$my_date_start_lundi = '2017-10-02'; $my_date_end_lundi = '2017-11-06'; // octobre 2017
//$my_date_start_lundi = '2017-10-30'; $my_date_end_lundi = '2017-12-04'; // novembre 2017
//$my_date_start_lundi = '2017-11-27'; $my_date_end_lundi = '2018-01-01'; // decembre 2017
//$my_date_start_lundi = '2016-12-26'; $my_date_end_lundi = '2018-01-01'; // ALL 2017
//$my_date_start_lundi = '2018-01-01'; $my_date_end_lundi = '2018-02-05'; // Janvier 2018
//$my_date_start_lundi = '2018-01-29'; $my_date_end_lundi = '2018-03-05'; // Fevrier 2018 
//$my_date_start_lundi = '2018-02-26'; $my_date_end_lundi = '2018-04-02'; // Mars 2018
//$my_date_start_lundi = '2018-03-26'; $my_date_end_lundi = '2018-05-07'; // Avril 2018
//$my_date_start_lundi = '2018-04-30'; $my_date_end_lundi = '2018-06-04'; // Mai 2018
//$my_date_start_lundi = '2018-05-28'; $my_date_end_lundi = '2018-07-02'; // Juin 2018
//$my_date_start_lundi = '2018-06-25'; $my_date_end_lundi = '2018-08-06'; // Juillet 2018
//$my_date_start_lundi = '2018-07-30'; $my_date_end_lundi = '2018-09-03'; // Aout 2018
//$my_date_start_lundi = '2018-08-27'; $my_date_end_lundi = '2018-10-01'; // septembre 2018
//$my_date_start_lundi = '2018-09-24'; $my_date_end_lundi = '2018-11-05'; // octobre 2018
//$my_date_start_lundi = '2018-10-29'; $my_date_end_lundi = '2018-12-03'; // novembre 2018

$my_date_start_lundi = '2018-11-26'; $my_date_end_lundi = '2019-01-07'; // decembre 2018

$begin_year = "2018"; $begin_month = "12";
$end_year = "2018"; $end_month = "12";
$nb_jour_month = 31;
$dernier_jour_du_mois = '31';
$filename = "2018-12";






global $date_start; $date_start = $begin_year.'-'.$begin_month.'-01';
global $date_end; $date_end = $end_year.'-'.$end_month.'-'.$dernier_jour_du_mois;


// nom du fichier � la sortie





$is_local = false;
$serveur = 'localhost';

if($is_local){
    $idDB = 'root';
    $mdpDB = 'root';
    $DB = 'isas';
}
else{
    $idDB = 'informed';
    $mdpDB = 'no11iugX';
    $DB = 'informed3';
}


mysql_connect($serveur,$idDB,$mdpDB) or die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or die("Impossible de se connecter &agrave; la base");

function stripAccents($text)
{
    if(mb_detect_encoding($text)!="UTF-8"){
        $text = utf8_encode($text);
    }
    return $text;
}
function date_diff2($date1, $date2)
{
    $s = $date2-$date1;
    $d = intval($s/86400)+1;
    return $d;
}



// ici d�finition pour p�riode de date libres
$first_lundi_final = strtotime($my_date_start_lundi);
$last_lundi_final = strtotime($my_date_end_lundi);

$diff_start = (7 - date_diff2($first_lundi_final, mktime(0, 0, 0, $begin_month, 1, $begin_year)) + 1);

/* pierre modif du 24/11/15 sinon prorata n�gatif */
$diff_end = 7 + ((date_diff2($last_lundi_final, mktime(0, 0, 0, $end_month, ($nb_jour_month), $end_year))));



$cpt = 0;


/* Table tampon pour r�cup le cabinet � traiter */
if($type_tdb == "region"){
    // pierre $q = "SELECT * FROM temp_dashboard as t inner join account as a ON a.cabinet = t.cabinet WHERE t.is_ok=0 AND is_actif=1 AND t.cabinet in (SELECT cabinet FROM account WHERE region='".$region_consolide."')";
    $q = "SELECT cabinet FROM account WHERE region='".$region_consolide."' ";
}
else if($type_tdb == "cabinet"){
//    $q = "SELECT * FROM account as a  WHERE tdb_export='0000-00-00' LIMIT 1";
    #$q = "SELECT * FROM temp_dashboard as t inner join account as a ON a.cabinet = t.cabinet WHERE a.cabinet='regny' LIMIT 1";
    $q = "SELECT a.* ".
        "FROM evaluation_infirmier
                INNER JOIN dossier on dossier.id = evaluation_infirmier.id
                INNER JOIN account as a on a.cabinet = dossier.cabinet
                WHERE MONTH(evaluation_infirmier.date) >= '". $begin_month .
        "' AND YEAR(evaluation_infirmier.date) >= '". $begin_year.
        "' and MONTH(evaluation_infirmier.date) <= '". $end_month.
        "' and YEAR(evaluation_infirmier.date) <= '". $end_year.
        "' and tdb_export='0000-00-00' 
        and a.recordstatus = 0
        limit 1 ";
}

$res_cabinet = mysql_query($q);
if(mysql_num_rows($res_cabinet) == 0)
{
    echo '<br>@@ final @@';
    #mail('pierre.dufour@touaregs.com', 'cron dashboard', 'cron ok');
    exit();
}

$aCabinet = array();
$cpt_patient = $cpt_diab2 = $cpt_cogni = $cpt_hta = 0;
while($tab_cabinet = mysql_fetch_array($res_cabinet))
{
    array_push($aCabinet, $tab_cabinet['cabinet']);
    $cpt_patient += $tab_cabinet['total_pat'];
    $cpt_cogni += $tab_cabinet['total_cogni'];
    $cpt_diab2 += $tab_cabinet['total_diab2'];
    $cpt_hta += $tab_cabinet['total_HTA'];
}
$str_cabinets = implode("','", $aCabinet);
echo "@@".$str_cabinets;
$cpt++;


$objects = array();
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


//Create connection factory
$cf = new ConnectionFactory();

//create mappers
$FicheCabinetMapper = new FicheCabinetMapper($cf->getConnection());
$dossierMapper = new DossierMapper($cf->getConnection());
$SuiviHebdomadaireTempsPasseMapper = new SuiviHebdomadaireTempsPasseMapper($cf->getConnection());
$evaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
$SuiviReunionMedecinMapper = new SuiviReunionMedecinMapper($cf->getConnection());



$const_demi_jour = 420; // en minutes car nous avons en dur&eacute;e minute dans suivi hebdo du temps
$const_objectif_consult_jour = 6;
$total_temps = 0;

$FicheCabinet=New FicheCabinet();
if($type_tdb == "cabinet"){
    $current_cabinet = $str_cabinets;
    $FicheCabinet->cabinet = $current_cabinet;
    $FicheCabinet->region = $account->region;
    $FicheCabinet->infirmiere = $account->infirmiere;
    $resultCabinet = $FicheCabinetMapper->findObject($FicheCabinet->beforeSerialisation($account));
    $FicheCabinet = $resultCabinet->afterDeserialisation($account);
}
// Activit&eacute; : suivi temps hebdo
$SuiviHebdomadaireTempsPasse = new SuiviHebdomadaireTempsPasse();
//$SuiviHebdomadaireTempsPasse->date= '2013-05-20';
$SuiviHebdomadaireTempsPasse->info_asalee = 0;
//$SuiviHebdomadaireTempsPasse->cabinet = $current_cabinet;

$resultTemps = $SuiviHebdomadaireTempsPasseMapper->getObjectsByCabinetsBetweenDates($str_cabinets, date('Y-m-d', $first_lundi_final), date('Y-m-d', $last_lundi_final));

$formation = 0;
$contribution = 0;
$dossier = 0;
$coordination = 0;
$non_attribue = 0;
$temps_total_suivi_temps = 0;
#var_dump($resultTemps); // suivi hebdo du temps pass�

// consultations et actes derogatoires
// initialisation des datas
$consultation = 0;
$aJourconsult = array();
$aPatient = array();
$aProtocole = array('dep_diab'=>array(), 'suivi_diab'=>array(), 'rcva'=>array(), 'cognitif'=>array(), 'bpco'=>array(), 'automesure'=>array(), 'autres'=>array(), 'uterus'=>array(), 'sein'=>array(), 'hemocult'=>array(), 'colon'=>array(), 'sevrage_tabac'=>array());
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

// l� on boucle chaque semaine prise en compte dans la p�riode
foreach ($resultTemps as $key => $value)
{


    // pour chaque semaine on va checker les evaluations faites dans le cabinet
    $weekCurrent = $value['date'];
    $dev_asalee = $value['tps_contact_tel_patient']; // qui est le temps de contribution asalee
    $gestion_dossiers = $value['info_asalee'];
    $autoformation = $value['autoformation'];
    $formation = $value['formation'];
    $stagiaire = $value['stagiaire'];
    $tps_reunion_medecin = $value['tps_reunion_medecin'];
    $tps_reunion_infirmiere = $value['tps_reunion_infirmiere'];
    $non_atribue = $value['non_atribue'];
    $tps_passe_cabinet = $value['tps_passe_cabinet'];



    echo '<h5>Semaine '.$weekCurrent.' / cabinet '.$value['cabinet'].'</h5>';

    $saisieInfirmiere[$weekCurrent] = $evaluationInfirmierMapper->getObjectsByCabinetsBetweenDate($value['cabinet'], $weekCurrent, $weekCurrent);

    $saisieInfirmiereTT = $saisieInfirmiereTT+count($saisieInfirmiere);

    #var_dump($saisieInfirmiere[$weekCurrent]);exit;
    echo '<br>Nbre de evaluations '.count($saisieInfirmiere[$weekCurrent]);

    // pour chaque �valuation on va compter le temps
    $dureeHebdo = 0;
    $consult_collectives_uuid = array();
    $tempsPrepaBilanConsultation = 0;
    echo '<hr />';
    foreach($saisieInfirmiere[$weekCurrent] as $evaluation){

        if($evaluation['date'] >= $date_start && $evaluation['date'] <= $date_end) {
            //var_dump($evaluation);exit;
            echo '<p>consultation ID : '.$evaluation['id'].' / Date : '.$evaluation['date'] . ' / Dur�e: ' . $evaluation['duree'] . ' / Type: ' . $evaluation['type_consultation'] . ' / UUID: ' . $evaluation['uuid_collectif'];
            echo '<hr />';

            // 09/03/2017 : ajout pierre : d�boublonnage des consultations collectives
            if($evaluation['uuid_collectif'] == '' || !in_array($evaluation['uuid_collectif'], $consult_collectives_uuid)) {
                $dureeHebdo += $evaluation['duree'];

                // calcul du temps de pr�paration des dossiers
                $tempsPrepaBilanConsultation += calculTempsPrepaDossiers2($evaluation['duree'], $evaluation['type_consultation']);

                array_push($consult_collectives_uuid, $evaluation['uuid_collectif']);
            }

            #$dureeEvaluation = $evaluation['duree'];

            ### TYPE CONSULTATIONS
            // on r�parti le temps en fonction du type de consult pour ensuite calculer le temps de pr�paration des dossiers
            // on fait comme dans suivi hebdo temps pass�
            if($evaluation['type_consultation']!='' && strpos($evaluation['type_consultation'], ',') === false){
                $countTypeConsultation[$evaluation['type_consultation']] = $countTypeConsultation[$evaluation['type_consultation']]+1;
                $TpsConsultation[$evaluation['type_consultation']] = $TpsConsultation[$evaluation['type_consultation']]+$evaluation['duree'];
            }
            else {
                $countTypeConsultation['autres'] = $countTypeConsultation['autres']+1;
                $TpsConsultation['autres'] = $TpsConsultation['autres']+$evaluation['duree'];
            }


            ### CALCUL NBRE CONSULT ET ACTES
            $nb_saisie_inf_periode++;
            $consultation += $evaluation['duree'];
            if(!in_array($evaluation['date'], $aJourconsult))
            {
                array_push($aJourconsult, $evaluation['date']);
            }
            if(!in_array($evaluation['numero'], $aPatient))
            {
                array_push($aPatient, $evaluation['numero']);
            }


            $aTypeConsult = explode(',', $evaluation['type_consultation']); // les types consultation peuvent etre multiples
            for($i=0; $i<sizeof($aTypeConsult); $i++)
            {
                //echo '<br>#'.$value['numero'].' / '.$aTypeConsult[$i];
                if(isset($aProtocole[$aTypeConsult[$i]]))
                {
                    if(!in_array($evaluation['numero'], $aProtocole[$aTypeConsult[$i]]))
                        array_push($aProtocole[$aTypeConsult[$i]], $evaluation['numero']);

                    foreach ($aProtocole as $key2 => $aValueId)
                    {
                        //echo '<br>###-------'.$key;
                        if($key2 != $aTypeConsult[$i])
                        {
                            //echo '<br>####if('.$key.' != '.$aTypeConsult[$i].')';
                            if(in_array($evaluation['numero'], $aValueId))
                            {
                                //echo '<br>####if(inArray('.$value['numero'].')';
                                array_push($aPatientProtocole, $evaluation['numero']);
                                //echo "<br>@@push(".$value['numero'].')';
                            }
                        }
                    }
                }
            }
            if(($evaluation['dcreat'] >= $date_start) && ($evaluation['dcreat'] <= $date_end) && (!in_array($evaluation['numero'], $aNewPatientMois)))  // nb new patient dans le mois
                array_push($aNewPatientMois, $evaluation['numero']);
            //$nb_new_mois++;


            # $TpsConsultation = array();
            # $TpsConsultation[$value['type_consultation']] = $TpsConsultation[$value['type_consultation']]+intval($value['duree']);

            $examsDerogatoire['spiro'] += ($evaluation['spirometre'] != NULL) ? $evaluation['spirometre'] : 0;//((($value['spirometre_seul'] != NULL) ? $value['spirometre_seul'] : 0) + (($value['spirometre'] != NULL) ? $value['spirometre'] : 0));
            $examsDerogatoire['cogn'] += $evaluation['t_cognitif'];
            $examsDerogatoire['ecg'] += ($evaluation['ecg'] != NULL) ? $evaluation['ecg'] : 0;//((($value['ecg_seul'] != NULL) ? $value['ecg_seul'] : 0) + (($value['ecg'] != NULL) ? $value['ecg'] : 0));
            $examsDerogatoire['pied'] += $evaluation['exapied'];
            $examsDerogatoire['monofil'] += $evaluation['monofil'];
            $examsDerogatoire['autre'] += $evaluation['hba'];
        }

    } // fin de la boucle $saisieInfirmiere[$weekCurrent]


    // 11/05/17 : pierre : calcul d�plac� dans la boucle des evaluations pour d�doublonner les consult collectives
    // // calcul du temps de pr�paration des dossiers
    // $tempsPrepaBilanConsultation = calculTempsPrepaDossiers($TpsConsultation);


    // le temps de r�union medecin a �t� d�localis� dans une autre table, donc
    // pour chaque semaine il faut r�cup�rer la liste des r�unions m�decins
    // c'ets ce qu'on appelle aussi concertation ou coordination

    $listeReunionsMedecins = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDateForHebdo($value['cabinet'], $weekCurrent);
    #var_dump($listeReunionsMedecins);exit;
    $dureeReunionMG = 0;
    foreach($listeReunionsMedecins as $reu){
        //$dureeReunionMG = $dureeReunionMG+$reu['duree'];
        $tempAdd = count(explode(",", $reu["infirmiere"])) * $reu['duree'];
        $dureeReunionMG += $tempAdd;
    }
    #echo 'duree r�unions : '.$dureeReunionMG;exit;



    #$non_attribue_temp = $value['non_atribue'];
    echo '<p>Duree hebdo totale (consultations) : '.$dureeHebdo;
    echo '<br>Preparation dossier sur la semaine : '.round($tempsPrepaBilanConsultation);
    echo '<br>Contribution aux actions developpement asalee : '.round($dev_asalee);
    echo '<br>Gestion sur dossiers patients : '.round($gestion_dossiers);
    echo '<br>Autoformation : '.round($autoformation);
    echo '<br>Formation : '.round($formation);
    echo '<br>Encadrement stagiaires : '.round($stagiaire);
    echo '<br>Concertation medecins : '.round($dureeReunionMG);
    echo '<br>Echanges infirmieres : '.round($tps_reunion_infirmiere);
    echo '<br>Non attribue : '.round($non_atribue);
    echo '<br>Temps total d&eacute;clar&eacute; : '.$tps_passe_cabinet;
    $TpsConsultation = null; // on reset pour pas qu'il s'incr�mente de semaine en semaine



    $coef_diff = giveProrataForWeek($key,$weekCurrent,$diff_start,$diff_end,$nb_jour_month,$resultTemps);




    echo '<p>Coeficient pour prorata : '.$coef_diff.' / key('.$key.')</p>';
    //echo '<pre>';

    if($coef_diff != 7){

        $dureeHebdo = proratise($dureeHebdo,$coef_diff);
        $tempsPrepaBilanConsultation = proratise($tempsPrepaBilanConsultation,$coef_diff);
        $dev_asalee = proratise($dev_asalee,$coef_diff);
        $gestion_dossiers = proratise($gestion_dossiers,$coef_diff);
        $autoformation = proratise($autoformation,$coef_diff);
        $formation = proratise($formation,$coef_diff);
        $stagiaire = proratise($stagiaire,$coef_diff);
        $dureeReunionMG = proratise($dureeReunionMG,$coef_diff);
        $tps_reunion_infirmiere = proratise($tps_reunion_infirmiere,$coef_diff);
        $non_atribue = proratise($non_atribue,$coef_diff);
        $tps_passe_cabinet = proratise($tps_passe_cabinet,$coef_diff);

        echo 'On doit appliquer le coeficient du prorata';
        echo '<br>Duree hedbo consultations proratis&eacute; :'.$dureeHebdo;
        echo '<br>Preparation dossier sur la semaine proratis&eacute; :'.$tempsPrepaBilanConsultation;
        echo '<br>Contribution aux actions developpement proratis&eacute; : '.$dev_asalee;
        echo '<br>Gestion sur dossiers patients proratis&eacute; : '.$gestion_dossiers;
        echo '<br>Autoformation  proratis&eacute; : '.$autoformation;
        echo '<br>Formation  proratis&eacute; : '.$formation;
        echo '<br>Encadrement stagiaires  proratis&eacute; : '.$stagiaire;
        echo '<br>Concertation medecins  proratis&eacute; : '.$dureeReunionMG;
        echo '<br>Echanges infirmieres  proratis&eacute; : '.$tps_reunion_infirmiere;
        echo '<br>Non attribue  proratis&eacute; : '.$non_atribue;
        echo '<br>Temps total d&eacute;clar&eacute;  proratis&eacute; : '.$tps_passe_cabinet;

    }


    // cumul de chaque semaine
    $dureeHebdoTT = $dureeHebdoTT + $dureeHebdo;
    $tempsPrepaBilanConsultationTT = $tempsPrepaBilanConsultationTT + $tempsPrepaBilanConsultation;
    $dev_asaleeTT = $dev_asaleeTT + $dev_asalee;
    $gestion_dossiersTT = $gestion_dossiersTT + $gestion_dossiers;
    $autoformationTT = $autoformationTT + $autoformation;
    $formationTT = $formationTT + $formation;
    $stagiaireTT = $stagiaireTT + $stagiaire;
    $dureeReunionMGTT = $dureeReunionMGTT + $dureeReunionMG;
    $tps_reunion_infirmiereTT = $tps_reunion_infirmiereTT + $tps_reunion_infirmiere;
    $non_atribueTT = $non_atribueTT + $non_atribue;
    $tps_passe_cabinetTT = $tps_passe_cabinetTT + $tps_passe_cabinet;


    echo '<p>&nbsp;</p>';

    #          if($value['non_atribue'] != '') echo "<br># cabinet: ".$value['cabinet']." / date : ".$value['date'].'=> non attrib = '.$value['non_atribue'];

    #echo '<br>'.$key.' ResultTemps : non attrib = '.$value['non_atribue'];

#            $non_attribue_periode += $value['non_atribue'];




} // fin de la boucle $resultTemps

echo '<h3>AU TOTAL SUR LA PERIODE en Heures<h3>';
echo '<p style="color:red">Duree hebdo totale (consultations) : '.$dureeHebdoTT;
echo '<br>Preparation dossier sur la semaine : '.round($tempsPrepaBilanConsultationTT);
echo '<br>Contribution aux actions developpement asalee : '.round($dev_asaleeTT);
echo '<br>Gestion sur dossiers patients : '.round($gestion_dossiersTT);
echo '<br>Autoformation : '.round($autoformationTT);
echo '<br>Formation : '.round($formationTT);
echo '<br>Encadrement stagiaires : '.round($stagiaireTT);
echo '<br>Concertation medecins : '.round($dureeReunionMGTT);
echo '<br>Echanges infirmieres : '.round($tps_reunion_infirmiereTT);
echo '<br>Non attribue : '.round($non_atribueTT);
echo '<br>Temps pass&eacute; dans cabinet  : '.round($tps_passe_cabinetTT);
echo '</p>';



// si le total du non attribu� est n�gatif, alors on le met � ZERO et on r�percute
// la valeur absolue au temps consultation
if($non_atribueTT < 0){
    // on reintegre le temps dans dureeHebdo et dans temps passe cabinet
    #$dureeHebdoTT = $dureeHebdoTT+abs($non_atribueTT);
    $tps_passe_cabinetTT = $tps_passe_cabinetTT+abs($non_atribueTT);

    $non_atribueTT = 0;
    echo '<h3>Non attribue negatif donc :</h3>
            dureeHebdoTT => '.$dureeHebdoTT.'<br>
            temps_passe_cabinet => '.$tps_passe_cabinetTT.'
            ';
}


echo '<h3>AU TOTAL SUR LA PERIODE en Jours<h3>';
echo '<p style="color:green">Duree hebdo totale (consultations) : '.convertiJours($dureeHebdoTT);
echo '<br>Preparation dossier sur la semaine : '.convertiJours($tempsPrepaBilanConsultationTT);
echo '<br>Contribution aux actions developpement asalee : '.convertiJours($dev_asaleeTT);
echo '<br>Gestion sur dossiers patients : '.convertiJours($gestion_dossiersTT);
echo '<br>Autoformation : '.convertiJours($autoformationTT);
echo '<br>Formation : '.convertiJours($formationTT);
echo '<br>Encadrement stagiaires : '.convertiJours($stagiaireTT);
echo '<br>Concertation medecins : '.convertiJours($dureeReunionMGTT);
echo '<br>Echanges infirmieres : '.convertiJours($tps_reunion_infirmiereTT);
echo '<br>Non attribue : '.convertiJours($non_atribueTT);
echo '<br>Temps pass� dans cabinet  : '.convertiJours($tps_passe_cabinetTT);
echo '</p>';


// ICI au 25/09 on est OK sur le temps pass�.
// ####################################### //


// ON REPREND D'ICI


/*
          //???????
          // Calcul du temps non attribu� car datas dans suivi_hebdo + dans saisie inf pour le calcul du temps consulation
          $non_attribue = 0;
          #var_dump($resultTemps);exit;
#$resultTemps@@
          foreach ($resultTemps as $key => $value) 
          {
          // echo '<pre style="background-color:#CCC;">';
          // var_dump($value);
          // echo '</pre>';
            if($value['tps_passe_cabinet'] != null)
            {
              if($key == 0)
              {
                $coef_diff = $diff_start;
              }
              else if(($key == sizeof($resultTemps) - 1) && (intval(substr($value['date'], 8, 2)) + 7 > $nb_jour_month))
              {
                $coef_diff = $diff_end;
              }
              else
              {
                $coef_diff = 7;
              }
              $temp_consult_semaine = 0;

              #var_dump($TpsConsultation);exit;
              #var_dump($saisieInfirmiere);
              echo 'NBRE : '.count($saisieInfirmiere);
              foreach ($saisieInfirmiere as $value_saisie) // �valuations infirmi�res
              {
               echo '<br>'.$value_saisie['date'].' // '.$value_saisie['duree']; 

                // modif du 21/09/2016 @ rv
                // �a c'est pour calculer le temps de pr�paration de dossiers, doit �tre identique au suivi habdo temps pass� cot� infirmiere  
                if($value_saisie['type_consultation']!='' && strpos($value_saisie['type_consultation'], ',') === false){
                  #$countTypeConsultation[$saisie['type_consultation']] = $countTypeConsultation[$saisie['type_consultation']]+1;
                  $TpsConsultation[$value_saisie['type_consultation']] = $TpsConsultation[$value_saisie['type_consultation']]+$value_saisie['duree'];
                }
                else {
                  $countTypeConsultation['autres'] = $countTypeConsultation['autres']+1;
                  $TpsConsultation['autres'] = $TpsConsultation['autres']+$value_saisie['duree'];
                }



                /*
                #echo '<br>Temps consultation semaine : ';
                //echo "<br>#(".$value_saisie['date'].' >= '.$value['date'].') && ('.$value_saisie['date'].' < '.date('Y-m-d', strtotime('+1 week', mktime(0, 0, 0, substr($value['date'], 5, 2), substr($value['date'], 8, 2), substr($value['date'], 0, 4)))).')';
                if($value_saisie['cabinet'] == $value['cabinet'] && ($value_saisie['date'] >= $value['date']) && ($value_saisie['date'] < date('Y-m-d', strtotime('+1 week', mktime(0, 0, 0, substr($value['date'], 5, 2), substr($value['date'], 8, 2), substr($value['date'], 0, 4))))))
                {

                  
                  $prepa_dossier = 0;
                  switch ($value_saisie['type_consultation']) 
                  {
                    case 'rcva': $prepa_dossier = $value_saisie['duree'] * 0.25; break;
                    case 'suivi_diab': $prepa_dossier = $value_saisie['duree'] * 0.25; break;
                    case 'dep_diab': $prepa_dossier = $value_saisie['duree'] * 0.25; break;
                    case 'bpco': $prepa_dossier = $value_saisie['duree'] * 0.2; break;
                    case 'cognitif': $prepa_dossier = $value_saisie['duree'] * 0.1; break;
                    case 'uterus': $prepa_dossier = $value_saisie['duree'] * 0.2; break;  
                    case 'sein': $prepa_dossier = $value_saisie['duree'] * 0.2; break;  
                    case 'colon': $prepa_dossier = $value_saisie['duree'] * 0.2; break;  
                    case 'autres': $prepa_dossier = $value_saisie['duree'] * 0.2; break;  
                  }
                  #echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;preparation: ".$prepa_dossier. "(duree ".$value_saisie['duree']." - type_consult(".$value_saisie['type_consultation']."))";
                  $temp_consult_semaine += $value_saisie['duree'] + $prepa_dossier;
                  #echo '<br>tcs = '.$value_saisie['date'].' '.$value_saisie['duree'] + $prepa_dossier;
                }
                


              }

  
              
            }
            

          }


*/


//exit();

echo '<p style="color:red">NON ATTRIBUE FINAL '.$non_attribue.'</p>';

/*echo '<pre style="background-color:#CCC;">';
var_dump(sizeof($saisieInfirmiere));
echo '</pre>';*/

//var_dump($aJourconsult);

// PREPARATION BILAN DES CONSULTATIONS est calcul&eacute; en appliquant des taux forfaitaires par rapport au temps de consultation.
// a priori ne sert plus
// remarque du 27/09
#          $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) + ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['uterus']*0.2) + ($TpsConsultation['colon']*0.2) + ($TpsConsultation['sein']*0.2) + ($TpsConsultation['autres']*0.2));
#          $SuiviHebdomadaireTempsPasse->formation = $formation;
#          $SuiviHebdomadaireTempsPasse->non_attribue = $non_attribue;
#          $SuiviHebdomadaireTempsPasse->tps_contact_tel_patient = $contribution;
#          $SuiviHebdomadaireTempsPasse->info_asalee = $dossier + $tempsPrepaBilanConsultation;
#          $total_temps += $SuiviHebdomadaireTempsPasse->formation + $SuiviHebdomadaireTempsPasse->tps_contact_tel_patient + $SuiviHebdomadaireTempsPasse->info_asalee + $non_attribue;


$total_temps += $consultation;

echo 'consultation : '.$consultation.'<br>';
//echo'<hr>';
//var_dump ($nb_new_mois);

// Activit&eacute; : liste_exams
$SuiviReunionMedecin = new SuiviReunionMedecin();
$SuiviReunionMedecin = $SuiviReunionMedecinMapper->getObjectsByCabinetsBetweenDate($str_cabinets, $date_start, $date_end);
foreach ($SuiviReunionMedecin as $key => $value)
{
    $coordination += $value['duree'];
}
$total_temps += $coordination;
echo 'coordination : '.$coordination.'<br>';
$SuiviHebdomadaireTempsPasse->tps_reunion_medecin = $coordination;

$objExams = new stdClass();
$objExams->consultation = $consultation;
$objExams->total = $total_temps;
$objExams->nb_jour_consult = sizeof($aJourconsult);
$objExams->nb_patient = sizeof($aPatient);
$objExams->protocoles = $aProtocole;

// calcul muti-protocole : calcul doublon dans $aPatientProtocole
// echo '<pre style="background-color:#CCC;">';
// var_dump(count(array_unique($aPatientProtocole)));
// echo '</pre>';
// exit();
//
echo '<p>';
echo 'TOTAL TEMPS : '.$total_temps;
#var_dump($objExams);exit;

#var_dump($SuiviHebdomadaireTempsPasse);exit;

#var_dump($aPatientProtocole);exit;
$objExams->nb_multiprotocole = count(array_unique($aPatientProtocole));//sizeof(array_unique($aPatientProtocole));
$objExams->nb_new = sizeof($aNewPatientMois); //$nb_new_mois;


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

// echo "<pre>aProtocole: ";
// var_dump($aProtocole);
// echo "</pre>";



// +++++++++++++++++++++++++++++++++++++++++++++

$tempsConsultation = $dureeHebdoTT+$tempsPrepaBilanConsultationTT;
$formationGroupee = $formationTT+$autoformationTT+$tps_reunion_infirmiereTT;
// temps pass� total dans le cabinet recalcul� car on tombe jamais sur le mm r�sultat
// a cause des d�clarations incompletes des infirmi�res
$tps_passe_cabinetTT = $tempsConsultation+$gestion_dossiersTT+$dureeReunionMGTT+$formationGroupee+$dev_asaleeTT+$non_atribueTT;
$traite_tout = TRUE;
if(convertiJours($tps_passe_cabinetTT) == '0.0') {
    $traite_tout = false;
}

#### EVOLUTION DES PATOLOGIES #########
# @reprendre en fonctions
#######################################


/////////////////////////////////////////////////////////////////
// Calcul &eacute;volution tension
if($traite_tout) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $regions, $liste_reg;


    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND actif='oui' ".
        "and dossier.cabinet=account.cabinet ".
        "AND account.cabinet IN ('".$str_cabinets."') ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";

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
            if($id_prec!=$id){//Nouveau dossier=> 1�re consult
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
// echo '<br><br>liste des consultations par patient';
// echo '<br>$consult:';
// echo '<pre>'; var_dump($consult); echo '</pre>';



//echo '<br><br>liste des tensions par patient en RCVA';
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


//echo '<br><br>liste des tensions par patient en suivi diab�te';
//Liste des tensions par patient en suivi diab�te
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
//echo '<br>$liste_tension:';
#echo '<pre>'; var_dump($consult); echo '</pre>';exit;

    $denominateur[1] = 0;
    $denominateur[2] = 0;
    $denominateur[3] = 0;
    $denominateur[4] = 0;
    $numerateur[1] = 0;
    $numerateur[2] = 0;
    $numerateur[3] = 0;
    $numerateur[4] = 0;
    $array_temp = array();
//$id = '76286';
    foreach($consult as $id => $tab_consult){

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
            // si la premi�re valeur du tableau est une tension, on peut regarder
            //
            if(strpos($new_normal_array[0], '_atension') !== false && intval($new_assoc_array[$new_normal_array[0]]["TaSys"]) > 140){
                for($i=0; $i < sizeof($new_normal_array); $i++){
                    if(strpos($new_normal_array[$i], '_atension') !== false){

                    }
                    elseif(strpos($new_normal_array[$i], '_consult') !== false){
                        $cpt_consult++;
                        // si c'est une consultation, on regarde si une valeur de tension juste apr�s
                        //
                        if(strpos($new_normal_array[$i + 1], '_atension') !== false){
                            //echo "<br />## I: ".$i;
                            // ok valeur de tension juste apr�s une consult => on regarde la valeur si > 140
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
                    if($cpt_consult > 3) break;  // si plus de 4 consultation, on sort, mais attention la derni�re est la 5i�me, c'est juste pour ranger la prochaine tension
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
    echo "<hr />";
    echo '<pre>'; var_dump($change_taux); echo '</pre>';

#exit;

// fin evolution tension
//////////////////////////////////////////////////


// debut HB1Ac
////////////////////////////////////////////////// 1 �re CONSULTATION ///////////////////////////////////

    $req1="SELECT account.cabinet, nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
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
                    if($hba_suiv!=0){
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
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la consult
                        $hba_prec=$ResHBA;
                        $deltaj_prec=$deltaj;
                    }
                    else{//Un HBA apr�s la consult => on regarde s'il a d�j� �t� enregistr�
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

////////////////////////////////////////////////// 2em CONSULTATION ///////////////////////////////////

    $req2="SELECT account.cabinet, nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";

    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req");


    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res2)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $avantsup7_2M[$cab]=0;
        $nb_dossierssup7_2M[$cab]=0;
        $apressup7_2M[$cab]=0;


        $avantinf7_2M[$cab]=0;
        $nb_dossiersinf7_2M[$cab]=0;
        $apresinf7_2M[$cab]=0;


//   $tpat[$cab] = $pat;
    }
    $req2= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, ".
        "DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and ".
        "liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$end_year.'-'.$end_month.'-31'."' and ".

        "dossier.cabinet IN ('".$str_cabinets."') and type_exam='HBA1c' ".
        "and suivi_diabete.dossier_id=liste_exam.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";

    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res2)){
        if(isset($nb_dossierssup7_2M[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($hba_suiv!=0)&&($nb_consult==2)){
                        if($hba_prec>7){
                            $nb_dossierssup7_2M[$cabinet_prec]=$nb_dossierssup7_2M[$cabinet_prec]+1;
                            $avantsup7_2M[$cabinet_prec]=$avantsup7_2M[$cabinet_prec]+$hba_prec;
                            $apressup7_2M[$cabinet_prec]=$apressup7_2M[$cabinet_prec]+$hba_suiv;

                        }
                        else{
                            $nb_dossiersinf7_2M[$cabinet_prec]=$nb_dossiersinf7_2M[$cabinet_prec]+1;
                            $avantinf7_2M[$cabinet_prec]=$avantinf7_2M[$cabinet_prec]+$hba_prec;
                            $apresinf7_2M[$cabinet_prec]=$apresinf7_2M[$cabinet_prec]+$hba_suiv;

                        }
                    }
                }
                $nb_consult=1;
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $hba_prec=$ResHBA;
                $hba_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult==1){
                            $hba_prec=$ResHBA;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la consult => on regarde s'il a d�j� �t� enregistr�
                        if($hba_suiv==0){
                            if($nb_consult==2){
                                $hba_suiv=$ResHBA;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult==1){ //C'est la deuxi�me consult
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }

                }
            }
        }
        else{
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $hba_prec=$ResHBA;
            $hba_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
            $nb_consult=1;
        }
    }


////////////////////////////////////////////////// 3em CONSULTATION ///////////////////////////////////

    $req3="SELECT account.cabinet, nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");

    if (mysql_num_rows($res3)==0) {
        //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    while(list($cab, $pat, $ville, ) = mysql_fetch_row($res3)) {
        $tcabinet[] = $cab;
        $avantsup7_3M[$cab]=0;
        $nb_dossierssup7_3M[$cab]=0;
        $apressup7_3M[$cab]=0;


        $avantinf7_3M[$cab]=0;
        $nb_dossiersinf7_3M[$cab]=0;
        $apresinf7_3M[$cab]=0;

    }

    $req3= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, ".
        "DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$end_year.'-'.$end_month.'-31'."' and ".
        "dossier.cabinet IN ('".$str_cabinets."') and type_exam='HBA1c' ".
        "and suivi_diabete.dossier_id=liste_exam.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";
    $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res3)){

        if(isset($nb_dossierssup7_3M[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($hba_suiv!=0)&&($nb_consult==3)){
                        if($hba_prec>7){

                            $nb_dossierssup7_3M[$cabinet_prec]=$nb_dossierssup7_3M[$cabinet_prec]+1;
                            $avantsup7_3M[$cabinet_prec]=$avantsup7_3M[$cabinet_prec]+$hba_prec;
                            $apressup7_3M[$cabinet_prec]=$apressup7_3M[$cabinet_prec]+$hba_suiv;

                        }
                        else{

                            $nb_dossiersinf7_3M[$cabinet_prec]=$nb_dossiersinf7_3M[$cabinet_prec]+1;
                            $avantinf7_3M[$cabinet_prec]=$avantinf7_3M[$cabinet_prec]+$hba_prec;
                            $apresinf7_3M[$cabinet_prec]=$apresinf7_3M[$cabinet_prec]+$hba_suiv;

                        }
                    }
                }
                $nb_consult=1;
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $hba_prec=$ResHBA;
                $hba_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult==1){
                            $hba_prec=$ResHBA;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la 3�me consult => on regarde s'il a d�j� �t� enregistr�
                        if($hba_suiv==0){
                            if($nb_consult==3){
                                $hba_suiv=$ResHBA;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult<3){ //C'est la deuxi�me consult ou 3eme
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }
                    else{ //On est sur une consultation suivante
                        if(($hba_suiv!=0)&&($nb_consult==3)){
                            $nb_consult++;
                            if($hba_prec>7){

                                $nb_dossierssup7_3M[$cabinet_prec]=$nb_dossierssup7_3M[$cabinet_prec]+1;
                                $avantsup7_3M[$cabinet_prec]=$avantsup7_3M[$cabinet_prec]+$hba_prec;
                                $apressup7_3M[$cabinet_prec]=$apressup7_3M[$cabinet_prec]+$hba_suiv;

                            }
                            else{

                                $nb_dossiersinf7_3M[$cabinet_prec]=$nb_dossiersinf7_3M[$cabinet_prec]+1;
                                $avantinf7_3M[$cabinet_prec]=$avantinf7_3M[$cabinet_prec]+$hba_prec;
                                $apresinf7_3M[$cabinet_prec]=$apresinf7_3M[$cabinet_prec]+$hba_suiv;

                            }
                        }
                    }
                }
            }
        }
        else{
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $hba_prec=$ResHBA;
            $hba_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
            $nb_consult=1;
        }
    }


////////////////////////////////////////////////// 4em CONSULTATION ///////////////////////////////////

    $req4="SELECT account.cabinet, nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res4=mysql_query($req4) or die("erreur SQL:".mysql_error()."<br>$req4");

    if (mysql_num_rows($res4)==0) {
        //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res4)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $avantsup7_4M[$cab]=0;
        $nb_dossierssup7_4M[$cab]=0;
        $apressup7_4M[$cab]=0;

        $avantinf7_4M[$cab]=0;
        $nb_dossiersinf7_4M[$cab]=0;
        $apresinf7_4M[$cab]=0;



    }

    $req4= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, ".
        "DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$end_year.'-'.$end_month.'-31'."' and ".
        "dossier.cabinet IN ('".$str_cabinets."') and type_exam='HBA1c' ".
        "and suivi_diabete.dossier_id=liste_exam.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";
    $res4=mysql_query($req4) or die("erreur SQL:".mysql_error()."<br>$req4");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res4)){
        if(isset($nb_dossierssup7_4M[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($hba_suiv!=0)&&($nb_consult==4)){
                        if($hba_prec>7){
                            $nb_dossierssup7_4M[$cabinet_prec]=$nb_dossierssup7_4M[$cabinet_prec]+1;
                            $avantsup7_4M[$cabinet_prec]=$avantsup7_4M[$cabinet_prec]+$hba_prec;
                            $apressup7_4M[$cabinet_prec]=$apressup7_4M[$cabinet_prec]+$hba_suiv;

                        }
                        else{
                            $nb_dossiersinf7_4M[$cabinet_prec]=$nb_dossiersinf7_4M[$cabinet_prec]+1;
                            $avantinf7_4M[$cabinet_prec]=$avantinf7_4M[$cabinet_prec]+$hba_prec;
                            $apresinf7_4M[$cabinet_prec]=$apresinf7_4M[$cabinet_prec]+$hba_suiv;

                        }
                    }
                }
                $nb_consult=1;
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $hba_prec=$ResHBA;
                $hba_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult==1){
                            $hba_prec=$ResHBA;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la 3�me consult => on regarde s'il a d�j� �t� enregistr�
                        if($hba_suiv==0){
                            if($nb_consult==4){
                                $hba_suiv=$ResHBA;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult<4){ //C'est la deuxi�me consult ou 3eme
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }
                    else{ //On est sur une consultation suivante
                        if(($hba_suiv!=0)&&($nb_consult==4)){
                            $nb_consult++;
                            if($hba_prec>7){
                                $nb_dossierssup7_4M[$cabinet_prec]=$nb_dossierssup7_4M[$cabinet_prec]+1;
                                $avantsup7_4M[$cabinet_prec]=$avantsup7_4M[$cabinet_prec]+$hba_prec;
                                $apressup7_4M[$cabinet_prec]=$apressup7_4M[$cabinet_prec]+$hba_suiv;

                            }
                            else{
                                $nb_dossiersinf7_4M[$cabinet_prec]=$nb_dossiersinf7_4M[$cabinet_prec]+1;
                                $avantinf7_4M[$cabinet_prec]=$avantinf7_4M[$cabinet_prec]+$hba_prec;
                                $apresinf7_4M[$cabinet_prec]=$apresinf7_4M[$cabinet_prec]+$hba_suiv;

                            }
                        }
                    }
                }
            }
        }
        else{
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $hba_prec=$ResHBA;
            $hba_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
            $nb_consult=1;
        }
    }

////////////////////////////////////////////////// 5em CONSULTATION ///////////////////////////////////

    $req5="SELECT account.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res5=mysql_query($req5) or die("erreur SQL:".mysql_error()."<br>$req5");

    if (mysql_num_rows($res5)==0) {
        // exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }


    while(list($cab, $pat, $ville) = mysql_fetch_row($res5)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $avantsup7_5M[$cab]=0;
        $nb_dossierssup7_5M[$cab]=0;
        $apressup7_5M[$cab]=0;

        $avantinf7_5M[$cab]=0;
        $nb_dossiersinf7_5M[$cab]=0;
        $apresinf7_5M[$cab]=0;

    }
    $req5= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, ".
        "DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$end_year.'-'.$end_month.'-31'."' and ".
        "dossier.cabinet IN ('".$str_cabinets."') and type_exam='HBA1c' ".
        "and suivi_diabete.dossier_id=liste_exam.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";
    $res5=mysql_query($req5) or die("erreur SQL:".mysql_error()."<br>$req5");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res5)){
        if(isset($nb_dossierssup7_5M[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($hba_suiv!=0)&&($nb_consult==5)){
                        if($hba_prec>7){
                            $nb_dossierssup7_5M[$cabinet_prec]=$nb_dossierssup7_5M[$cabinet_prec]+1;
                            $avantsup7_5M[$cabinet_prec]=$avantsup7_5M[$cabinet_prec]+$hba_prec;
                            $apressup7_5M[$cabinet_prec]=$apressup7_5M[$cabinet_prec]+$hba_suiv;

                        }
                        else{
                            $nb_dossiersinf7_5M[$cabinet_prec]=$nb_dossiersinf7_5M[$cabinet_prec]+1;
                            $avantinf7_5M[$cabinet_prec]=$avantinf7_5M[$cabinet_prec]+$hba_prec;
                            $apresinf7_5M[$cabinet_prec]=$apresinf7_5M[$cabinet_prec]+$hba_suiv;

                        }
                    }
                }
                $nb_consult=1;
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $hba_prec=$ResHBA;
                $hba_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult==1){
                            $hba_prec=$ResHBA;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la 3�me consult => on regarde s'il a d�j� �t� enregistr�
                        if($hba_suiv==0){
                            if($nb_consult==5){
                                $hba_suiv=$ResHBA;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult<5){ //C'est la deuxi�me consult ou 3eme
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }
                    else{ //On est sur une consultation suivante
                        if(($hba_suiv!=0)&&($nb_consult==5)){
                            $nb_consult++;
                            if($hba_prec>7){
                                $nb_dossierssup7_5M[$cabinet_prec]=$nb_dossierssup7_5M[$cabinet_prec]+1;
                                $avantsup7_5M[$cabinet_prec]=$avantsup7_5M[$cabinet_prec]+$hba_prec;
                                $apressup7_5M[$cabinet_prec]=$apressup7_5M[$cabinet_prec]+$hba_suiv;

                            }
                            else{
                                $nb_dossiersinf7_5M[$cabinet_prec]=$nb_dossiersinf7_5M[$cabinet_prec]+1;
                                $avantinf7_5M[$cabinet_prec]=$avantinf7_5M[$cabinet_prec]+$hba_prec;
                                $apresinf7_5M[$cabinet_prec]=$apresinf7_5M[$cabinet_prec]+$hba_suiv;

                            }
                        }
                    }
                }
            }
        }
        else{
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $hba_prec=$ResHBA;
            $hba_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
            $nb_consult=1;
        }
    }

////////////////////////////////////////////////// 6em CONSULTATION ///////////////////////////////////////


    $req6="SELECT account.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";

    $res6=mysql_query($req6) or die("erreur SQL:".mysql_error()."<br>$req6");

    if (mysql_num_rows($res6)==0) {
        //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res6)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $avantsup7_6M[$cab]=0;
        $nb_dossierssup7_6M[$cab]=0;
        $apressup7_6M[$cab]=0;

        $avantinf7_6M[$cab]=0;
        $nb_dossiersinf7_6M[$cab]=0;
        $apresinf7_6M[$cab]=0;

    }

    $req6= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, ".
        "DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$end_year.'-'.$end_month.'-31'."' and ".
        "dossier.cabinet IN ('".$str_cabinets."') and type_exam='HBA1c' ".
        "and suivi_diabete.dossier_id=liste_exam.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";
    $res6=mysql_query($req6) or die("erreur SQL:".mysql_error()."<br>$req6");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res6)){
        if(isset($nb_dossierssup7_6M[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($hba_suiv!=0)&&($nb_consult==6)){
                        if($hba_prec>7){
                            $nb_dossierssup7_6M[$cabinet_prec]=$nb_dossierssup7_6M[$cabinet_prec]+1;
                            $avantsup7_6M[$cabinet_prec]=$avantsup7_6M[$cabinet_prec]+$hba_prec;
                            $apressup7_6M[$cabinet_prec]=$apressup7_6M[$cabinet_prec]+$hba_suiv;
                        }
                        else{
                            $nb_dossiersinf7_6M[$cabinet_prec]=$nb_dossiersinf7_6M[$cabinet_prec]+1;
                            $avantinf7_6M[$cabinet_prec]=$avantinf7_6M[$cabinet_prec]+$hba_prec;
                            $apresinf7_6M[$cabinet_prec]=$apresinf7_6M[$cabinet_prec]+$hba_suiv;
                        }
                    }
                }
                $nb_consult=1;
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $hba_prec=$ResHBA;
                $hba_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult==1){
                            $hba_prec=$ResHBA;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la 3�me consult => on regarde s'il a d�j� �t� enregistr�
                        if($hba_suiv==0){
                            if($nb_consult==6){
                                $hba_suiv=$ResHBA;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult<6){ //C'est la deuxi�me consult ou 3eme
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }
                    else{ //On est sur une consultation suivante
                        if(($hba_suiv!=0)&&($nb_consult==6)){
                            $nb_consult++;
                            if($hba_prec>7){
                                $nb_dossierssup7_6M[$cabinet_prec]=$nb_dossierssup7_6M[$cabinet_prec]+1;
                                $avantsup7_6M[$cabinet_prec]=$avantsup7_6M[$cabinet_prec]+$hba_prec;
                                $apressup7_6M[$cabinet_prec]=$apressup7_6M[$cabinet_prec]+$hba_suiv;
                            }
                            else{
                                $nb_dossiersinf7_6M[$cabinet_prec]=$nb_dossiersinf7_6M[$cabinet_prec]+1;
                                $avantinf7_6M[$cabinet_prec]=$avantinf7_6M[$cabinet_prec]+$hba_prec;
                                $apresinf7_6M[$cabinet_prec]=$apresinf7_6M[$cabinet_prec]+$hba_suiv;
                            }
                        }
                    }
                }
            }
        }
        else{
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $hba_prec=$ResHBA;
            $hba_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
            $nb_consult=1;
        }
    }


// fin HBA1c
////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////
// debut LDL
////////////////////////////////////////////////// 1 �re CONSULTATION ///////////////////////////////////
    $req="SELECT account.cabinet, nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $avantsup13[$cab]=0;
        $nb_dossierssup13[$cab]=0;
        $apressup13[$cab]=0;

        $avantinf13[$cab]=0;
        $nb_dossiersinf13[$cab]=0;
        $apresinf13[$cab]=0;

    }


    $req= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, liste_exam, evaluation_infirmier, suivi_diabete ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and cabinet IN ('".$str_cabinets."') and date_exam > '2009-01-01' AND date_exam < '".$end_year.'-'.$end_month.'_31'."'  and ".
        "type_exam='LDL' and suivi_diabete.dossier_id=dossier.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dLDL, $LDL, $date_consult, $deltaj)=mysql_fetch_row($res)){
        if(isset($nb_dossierssup13[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if($LDL_suiv!=0){
                        if($LDL_prec>1.3){
                            $nb_dossierssup13[$cabinet_prec]=$nb_dossierssup13[$cabinet_prec]+1;
                            $avantsup13[$cabinet_prec]=$avantsup13[$cabinet_prec]+$LDL_prec;
                            $apressup13[$cabinet_prec]=$apressup13[$cabinet_prec]+$LDL_suiv;

                        }
                        else{
                            $nb_dossiersinf13[$cabinet_prec]=$nb_dossiersinf13[$cabinet_prec]+1;
                            $avantinf13[$cabinet_prec]=$avantinf13[$cabinet_prec]+$LDL_prec;
                            $apresinf13[$cabinet_prec]=$apresinf13[$cabinet_prec]+$LDL_suiv;

                        }
                    }
                }
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $LDL_prec=$LDL;
                $LDL_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la consult
                        $LDL_prec=$LDL;
                        $deltaj_prec=$deltaj;
                    }
                    else{//Un HBA apr�s la consult => on regarde s'il a d�j� �t� enregistr�
                        if($LDL_suiv==0){
                            $LDL_suiv=$LDL;
                            $deltaj_suiv=$deltaj;
                        }
                    }
                }
                else{
                    if($LDL_suiv!=0){
                        if($LDL_prec>1.3){
                            $nb_dossierssup13[$cabinet_prec]=$nb_dossierssup13[$cabinet_prec]+1;
                            $avantsup13[$cabinet_prec]=$avantsup13[$cabinet_prec]+$LDL_prec;
                            $apressup13[$cabinet_prec]=$apressup13[$cabinet_prec]+$LDL_suiv;
                        }
                        else{
                            $nb_dossiersinf13[$cabinet_prec]=$nb_dossiersinf13[$cabinet_prec]+1;
                            $avantinf13[$cabinet_prec]=$avantinf13[$cabinet_prec]+$LDL_prec;
                            $apresinf13[$cabinet_prec]=$apresinf13[$cabinet_prec]+$LDL_suiv;

                        }
                    }
                    $LDL_prec=$LDL;
                    $LDL_suiv=0;
                    $date_consult_prec=$date_consult;
                }
            }
        }
        else{
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $LDL_prec=$LDL;
            $LDL_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
        }
    }
////////////////////////////////////////////////// 2 �me CONSULTATION ///////////////////////////////////

    $req2="SELECT account.cabinet, nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

    if (mysql_num_rows($res2)==0) {
        //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res2)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $avantsup13_2M[$cab]=0;
        $nb_dossierssup13_2M[$cab]=0;
        $apressup13_2M[$cab]=0;


        $avantinf13_2M[$cab]=0;
        $nb_dossiersinf13_2M[$cab]=0;
        $apresinf13_2M[$cab]=0;


    }

    $req2= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, ".
        "DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and date_exam > '2009-01-01' AND date_exam < '".$end_year.'-'.$end_month.'-31'."'  and ".

        "dossier.cabinet in ('".$str_cabinets."') and type_exam='LDL' ".
        "and suivi_diabete.dossier_id=liste_exam.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";
    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dLDL, $LDL, $date_consult, $deltaj)=mysql_fetch_row($res2)){
        if(isset($nb_dossierssup13_2M[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($LDL_suiv!=0)&&($nb_consult==2)){
                        if($LDL_prec>13){
                            $nb_dossierssup13_2M[$cabinet_prec]=$nb_dossierssup13_2M[$cabinet_prec]+1;
                            $avantsup13_2M[$cabinet_prec]=$avantsup13_2M[$cabinet_prec]+$LDL_prec;
                            $apressup13_2M[$cabinet_prec]=$apressup13_2M[$cabinet_prec]+$LDL_suiv;

                        }
                        else{
                            $nb_dossiersinf13_2M[$cabinet_prec]=$nb_dossiersinf13_2M[$cabinet_prec]+1;
                            $avantinf13_2M[$cabinet_prec]=$avantinf13_2M[$cabinet_prec]+$LDL_prec;
                            $apresinf13_2M[$cabinet_prec]=$apresinf13_2M[$cabinet_prec]+$LDL_suiv;

                        }
                    }
                }
                $nb_consult=1;
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $LDL_prec=$LDL;
                $LDL_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult==1){
                            $LDL_prec=$LDL;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la consult => on regarde s'il a d�j� �t� enregistr�
                        if($LDL_suiv==0){
                            if($nb_consult==2){
                                $LDL_suiv=$LDL;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult==1){ //C'est la deuxi�me consult
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }
                    else{ //On est sur une consultation suivante
                        if(($LDL_suiv!=0)&&($nb_consult==2)){
                            $nb_consult++;
                            if($LDL_prec>1.3){
                                $nb_dossierssup13_2M[$cabinet_prec]=$nb_dossierssup13_2M[$cabinet_prec]+1;
                                $avantsup13_2M[$cabinet_prec]=$avantsup13_2M[$cabinet_prec]+$LDL_prec;
                                $apressup13_2M[$cabinet_prec]=$apressup13_2M[$cabinet_prec]+$LDL_suiv;

                            }
                            else{
                                $nb_dossiersinf13_2M[$cabinet_prec]=$nb_dossiersinf13_2M[$cabinet_prec]+1;
                                $avantinf13_2M[$cabinet_prec]=$avantinf13_2M[$cabinet_prec]+$LDL_prec;
                                $apresinf13_2M[$cabinet_prec]=$apresinf13_2M[$cabinet_prec]+$LDL_suiv;

                            }
                        }
                    }
                }
            }
        }
        else{
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $LDL_prec=$LDL;
            $LDL_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
            $nb_consult=1;
        }
    }
////////////////////////////////////////////////// 3 �me CONSULTATION ///////////////////////////////////

    $req3="SELECT account.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "AND account.cabinet IN ('".$str_cabinets."') ".     // ICI MODIF 11/02/14
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";

    $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");

    if (mysql_num_rows($res3)==0) {
        //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res3)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $avantsup13_3M[$cab]=0;
        $nb_dossierssup13_3M[$cab]=0;
        $apressup13_3M[$cab]=0;


        $avantinf13_3M[$cab]=0;
        $nb_dossiersinf13_3M[$cab]=0;
        $apresinf13_3M[$cab]=0;

    }
    $req3= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
        "evaluation_infirmier.date as date_consult, DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
        "FROM dossier, evaluation_infirmier, liste_exam, suivi_diabete ".
        "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and date_exam > '2009-01-01' AND date_exam < '".$end_year.'-'.$end_month.'-31'."'  and ".
        "dossier.cabinet in ('".$str_cabinets."') and type_exam='LDL' ".
        "and suivi_diabete.dossier_id=dossier.id ".
        "ORDER BY cabinet, dossier.id, date_consult, date_exam";
    $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");

    $id_prec="";
    $cabinet_prec="";

    while(list($cabinet, $dossier_id, $dLDL, $LDL, $date_consult, $deltaj)=mysql_fetch_row($res3)){
        if(isset($nb_dossierssup13_3M[$cabinet_prec])){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($LDL_suiv!=0)&&($nb_consult==3)){
                        if($LDL_prec>1.3){
                            $nb_dossierssup13_3M[$cabinet_prec]=$nb_dossierssup13_3M[$cabinet_prec]+1;
                            $avantsup13_3M[$cabinet_prec]=$avantsup13_3M[$cabinet_prec]+$LDL_prec;
                            $apressup13_3M[$cabinet_prec]=$apressup13_3M[$cabinet_prec]+$LDL_suiv;

                        }
                        else{
                            $nb_dossiersinf13_3M[$cabinet_prec]=$nb_dossiersinf13_3M[$cabinet_prec]+1;
                            $avantinf13_3M[$cabinet_prec]=$avantinf13_3M[$cabinet_prec]+$LDL_prec;
                            $apresinf13_3M[$cabinet_prec]=$apresinf13_3M[$cabinet_prec]+$LDL_suiv;

                        }
                    }
                }
                $nb_consult=1;
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $LDL_prec=$LDL;
                $LDL_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult<3){
                            $LDL_prec=$LDL;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la consult => on regarde s'il a d�j� �t� enregistr�
                        if($LDL_suiv==0){
                            if($nb_consult==3){
                                $LDL_suiv=$LDL;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult<3){ //C'est la deuxi�me consult
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }
                    else{ //On est sur une consultation suivante
                        if(($LDL_suiv!=0)&&($nb_consult==3)){
                            $nb_consult++;
                            if($LDL_prec>1.3){
                                $nb_dossierssup13_3M[$cabinet_prec]=$nb_dossierssup13_3M[$cabinet_prec]+1;
                                $avantsup13_3M[$cabinet_prec]=$avantsup13_3M[$cabinet_prec]+$LDL_prec;
                                $apressup13_3M[$cabinet_prec]=$apressup13_3M[$cabinet_prec]+$LDL_suiv;

                            }
                            else{
                                $nb_dossiersinf13_3M[$cabinet_prec]=$nb_dossiersinf13_3M[$cabinet_prec]+1;
                                $avantinf13_3M[$cabinet_prec]=$avantinf13_3M[$cabinet_prec]+$LDL_prec;
                                $apresinf13_3M[$cabinet_prec]=$apresinf13_3M[$cabinet_prec]+$LDL_suiv;

                            }
                        }
                    }
                }
            }
        }
        else{
            $nb_consult=1;
            $date_consult_prec=$date_consult;
            $cabinet_prec=$cabinet;
            $LDL_prec=$LDL;
            $LDL_suiv=0;
            $id_prec=$dossier_id;
            $deltaj_prec=$deltaj;
        }
    }


// fin LDL
///////////////////////////////////////////////////////
} // fin $traite_tout
//////////////////////////////////////////////////////
// EFR et troubles cognitifs
//if($traite_tout) {
$query = "select COUNT(DISTINCT(evaluation_infirmier.id)) as cogni_unique_patient from evaluation_infirmier,dossier where cabinet IN ('".$str_cabinets."') and evaluation_infirmier.id = dossier.id AND type_consultation like '%cognitif%' AND dnaiss < subdate( NOW(), INTERVAL 75 YEAR) and date <= '".$end_year.'-'.$end_month.'-31'."'";
$result = mysql_query($query);
$allCogni = mysql_fetch_array($result);

$query =  "select COUNT(DISTINCT(evaluation_infirmier.id)) as spiro_unique_patient from evaluation_infirmier,dossier where cabinet IN ('".$str_cabinets."') and evaluation_infirmier.id = dossier.id AND (spirometre='1' OR spirometre_seul='1') and date <= '".$end_year.'-'.$end_month.'-31'."'";
$result = mysql_query($query);
$allSpiro = mysql_fetch_array($result);
// }
// else {
//   $allCogni = array('cogni_unique_patient' => 0);
//   $allSpiro = array("spiro_unique_patient" => 0);
// }
// fin EFR et troubles cognitifs
//////////////////////////////////////////////////////



//////////////////////////////////////////////////////
// Liste des medecins pour l'ent�te

if($type_tdb == "cabinet"){
    $query = "select nom from medecin where cabinet='".$current_cabinet."' AND recordstatus='0'";
    $result = mysql_query($query);
    $strMedecins = "Dr. ";
    while($tab = mysql_fetch_array($result)) {
        $strMedecins .= $tab['nom'].", ";
    }
    $strMedecins = substr($strMedecins, 0, strlen($strMedecins) - 2);
}

echo '<br />### today INFO-ASALEE: ' .getPropertyValue("SuiviHebdomadaireTempsPasse:info_asalee");

// r�cup�ration des infirmi�res sur la fiche contact et non plus le champs contact de la table account
// maj rv le 18mai2016
// on utilise les WS d'Elie
$listeInf = '';
$infirmieresDuCabinet = GetLoginsByCab($current_cabinet, $status);
#var_dump($infirmieresDuCabinet);

foreach($infirmieresDuCabinet as $key=>$inf){
    $listeInf .=$inf['prenom'].' '.$inf['nom'].', ';
}
$listeInf = substr(utf8_decode($listeInf), 0, -2);
#echo $listeInf;


if($non_attribue <= 0){
    // non attribu� ne peux pas etre negatif on le r�affecte au temps total, et on le passe � 0
    $objExams->total = $objExams->total+abs($non_attribue);
    $non_attribue = 0;
}

echo '<h3>'.$total_temps.'</h3>';

$tab_csv["0"] = ($type_tdb == "cabinet") ? $current_cabinet : $region;
$tab_csv["1"] = ($type_tdb == "cabinet") ? $strMedecins : '';
#$tab_csv["2"] = ($type_tdb == "cabinet") ? stripAccents(getPropertyValue("FicheCabinet:contact")) : ''; // remplac� par la liste des inf pr�sentent dans le cabinet dans le r�f�rentiel
$tab_csv["2"] = ($type_tdb == "cabinet") ? stripAccents($listeInf) : '';
$tab_csv["3"] = ($type_tdb == "cabinet") ? stripAccents(getPropertyValue("FicheCabinet:nom_cab")).' - '.stripAccents(getPropertyValue("FicheCabinet:region")) : $region;

$periode_deb = $begin_month.'/'.$begin_year;
$periode_fin = $end_month.'/'.$end_year;
if($periode_deb==$periode_fin){
    $periode = $periode_deb;
    $record_dash = true; // on enregistre les r�sultats dans dashbord_results
}
else{
    $periode = $begin_month.'/'.$begin_year.' - '.$end_month.'/'.$end_year;
    $record_dash = false; // on enregistre pas les r�sultats dans dashbord_results
}

#var_dump($objExams);exit;

$tempsConsultation = $dureeHebdoTT+$tempsPrepaBilanConsultationTT;
$formationGroupee = $formationTT+$autoformationTT+$tps_reunion_infirmiereTT;
// temps pass� total dans le cabinet recalcul� car on tombe jamais sur le mm r�sultat
// a cause des d�clarations incompletes des infirmi�res
$tps_passe_cabinetTT = $tempsConsultation+$gestion_dossiersTT+$dureeReunionMGTT+$formationGroupee+$dev_asaleeTT+$non_atribueTT;


$tab_csv["4"] = $periode;
$tab_csv["5"] = convertiJours($tempsConsultation);
$tab_csv["6"] = round(($tempsConsultation / $tps_passe_cabinetTT) * 100, 2).' %';
$tab_csv["7"] = convertiJours($gestion_dossiersTT);
$tab_csv["8"] = round(($gestion_dossiersTT / $tps_passe_cabinetTT) * 100, 2).' %';
$tab_csv["9"] = convertiJours($dureeReunionMGTT);
$tab_csv["10"] = round(($dureeReunionMGTT / $tps_passe_cabinetTT) * 100, 2).' %';
$tab_csv["11"] = convertiJours($formationGroupee);
$tab_csv["12"] = round(($formationGroupee / $tps_passe_cabinetTT) * 100, 2).' %';
$tab_csv["13"] = convertiJours($dev_asaleeTT);
$tab_csv["14"] = round(($dev_asaleeTT / $tps_passe_cabinetTT) * 100, 2).' %';
$tab_csv["15"] = convertiJours($non_atribueTT);
$tab_csv["16"] = round(($non_atribueTT / $tps_passe_cabinetTT) * 100, 2).' %';


$tab_csv["17"] = convertiJours($tps_passe_cabinetTT);
$tab_csv["18"] = '100 %';

$nbre_jours_retenus = convertiJours($tempsConsultation+$gestion_dossiersTT+$dureeReunionMGTT+$non_atribueTT);
$tab_csv["19"] = $nbre_jours_retenus;
$tab_csv["20"] = $nb_saisie_inf_periode;
$tab_csv["21"] = sprintf("%.2f", $nb_saisie_inf_periode / $nbre_jours_retenus);
$tab_csv["22"] = sprintf("%.2f", round((($nb_saisie_inf_periode / $nbre_jours_retenus) / $const_objectif_consult_jour) * 100, 2)).' %';
$tab_csv["23"] = ($examsDerogatoire['spiro'] + $examsDerogatoire['cogn'] + $examsDerogatoire['ecg'] + $examsDerogatoire['pied'] + $examsDerogatoire['monofil'] + $examsDerogatoire['autre']);
$tab_csv["24"] = $examsDerogatoire['spiro'];
$tab_csv["25"] = $examsDerogatoire['cogn'];
$tab_csv["26"] = $examsDerogatoire['ecg'];
$tab_csv["27"] = $examsDerogatoire['pied'];
$tab_csv["28"] = $examsDerogatoire['monofil'];
$tab_csv["29"] = $examsDerogatoire['autre'];
$tab_csv["30"] = $nb_exam_saisis['nb'];
$tab_csv["31"] = $nb_exam_realises['nb'];
$autre_type_consult = sizeof($objExams->protocoles['autres']) + sizeof($objExams->protocoles['hemocult']);
$tab_csv["32"] = $autre_type_consult + sizeof($objExams->protocoles['dep_diab']) + sizeof($objExams->protocoles['suivi_diab']) + sizeof($objExams->protocoles['rcva']) + sizeof($objExams->protocoles['cognitif']) + sizeof($objExams->protocoles['bpco']) + sizeof($objExams->protocoles['sevrage_tabac']);//$objExams->nb_patient;
$tab_csv["33"] = sizeof($objExams->protocoles['dep_diab']);
$tab_csv["34"] = sizeof($objExams->protocoles['suivi_diab']);
$tab_csv["35"] = sizeof($objExams->protocoles['rcva']) + sizeof($objExams->protocoles['automesure']);
$tab_csv["36"] = sizeof($objExams->protocoles['cognitif']);
$tab_csv["37"] = sizeof($objExams->protocoles['bpco']);
$tab_csv["38"] = sizeof($objExams->protocoles['uterus']) + sizeof($objExams->protocoles['colon']) + sizeof($objExams->protocoles['sein']);  // pierre
$tab_csv["84"] = sizeof($objExams->protocoles['sevrage_tabac']); // AJOUT HERVE janv 2017
$tab_csv["39"] = $autre_type_consult;
$tab_csv["40"] = $objExams->nb_multiprotocole;
$tab_csv["41"] = $objExams->nb_new;
$tab_csv["42"] = sprintf("%.2f", ($objExams->nb_new / $objExams->nb_patient) * 100).' %';

if($traite_tout) {
    // Evolution HBA1C

    $avanttot1=array_sum($avantsup7);
    $aprestot1=array_sum($apressup7);
    $nb_dossierstot1=array_sum($nb_dossierssup7);

    $avanttot2=array_sum($avantsup7_2M);
    $aprestot2=array_sum($apressup7_2M);
    $nb_dossierstot2=array_sum($nb_dossierssup7_2M);

    $avanttot3=array_sum($avantsup7_3M);
    $aprestot3=array_sum($apressup7_3M);
    $nb_dossierstot3=array_sum($nb_dossierssup7_3M);

    $avanttot4=array_sum($avantsup7_4M);
    $aprestot4=array_sum($apressup7_4M);
    $nb_dossierstot4=array_sum($nb_dossierssup7_4M);

    $avanttot5=array_sum($avantsup7_5M);
    $aprestot5=array_sum($apressup7_5M);
    $nb_dossierstot5=array_sum($nb_dossierssup7_5M);

    $avanttot6=array_sum($avantsup7_6M);
    $aprestot6=array_sum($apressup7_6M);
    $nb_dossierstot6=array_sum($nb_dossierssup7_6M);

    $prem_sup_av = round($avanttot1/$nb_dossierstot1, 2);
    $second_sup_av = round($avanttot2/$nb_dossierstot2, 2);
    $three_sup_av = round($avanttot3/$nb_dossierstot3, 2);
    $four_sup_av =  round($avanttot4/$nb_dossierstot4, 2);
    $five_sup_av =  round($avanttot5/$nb_dossierstot5, 2);
    $six_sup_av =  round($avanttot6/$nb_dossierstot6, 2);

    $prem_sup_ap = round($aprestot1/$nb_dossierstot1, 2);
    $second_sup_ap = round($aprestot2/$nb_dossierstot2, 2);
    $three_sup_ap = round($aprestot3/$nb_dossierstot3, 2);
    $four_sup_ap = round($aprestot4/$nb_dossierstot4, 2);
    $five_sup_ap = round($aprestot5/$nb_dossierstot5, 2);
    $six_sup_ap = round($aprestot6/$nb_dossierstot6, 2);

    $tab_csv["43"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_av);;
    $tab_csv["44"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_ap);
    $tab_csv["45"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", round((($prem_sup_ap/$prem_sup_av)*100)-100, 2)).' %';
    $tab_csv["46"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_av);;
    $tab_csv["47"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_ap);
    $tab_csv["48"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", round((($second_sup_ap/$second_sup_av)*100)-100, 2)).' %';
    $tab_csv["49"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_av);;
    $tab_csv["50"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_ap);
    $tab_csv["51"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", round((($three_sup_ap/$three_sup_av)*100)-100, 2)).' %';
    $tab_csv["52"] = ($nb_dossierstot4 < 6) ? '-' : sprintf("%.2f", $four_sup_av);;
    $tab_csv["53"] = ($nb_dossierstot4 < 6) ? '-' : sprintf("%.2f", $four_sup_ap);
    $tab_csv["54"] = ($nb_dossierstot4 < 6) ? '-' : sprintf("%.2f", round((($four_sup_ap/$four_sup_av)*100)-100, 2)).' %';
    $tab_csv["55"] = ($nb_dossierstot5 < 6) ? '-' : sprintf("%.2f", $five_sup_av);;
    $tab_csv["56"] = ($nb_dossierstot5 < 6) ? '-' : sprintf("%.2f", $five_sup_ap);
    $tab_csv["57"] = ($nb_dossierstot5 < 6) ? '-' : sprintf("%.2f", round((($five_sup_ap/$five_sup_av)*100)-100, 2)).' %';
    $tab_csv["58"] = ($nb_dossierstot6 < 6) ? '-' : sprintf("%.2f", $six_sup_av);;
    $tab_csv["59"] = ($nb_dossierstot6 < 6) ? '-' : sprintf("%.2f", $six_sup_ap);
    $tab_csv["60"] = ($nb_dossierstot6 < 6) ? '-' : sprintf("%.2f", round((($six_sup_ap/$six_sup_av)*100)-100, 2)).' %';

    // fin Evolution HBA1C

    // Evolution LDL

    $avanttot1=array_sum($avantsup13);
    $aprestot1=array_sum($apressup13);
    $nb_dossierstot1=array_sum($nb_dossierssup13);

    $avanttot2=array_sum($avantsup13_2M);
    $aprestot2=array_sum($apressup13_2M);
    $nb_dossierstot2=array_sum($nb_dossierssup13_2M);

    $avanttot3=array_sum($avantsup13_3M);
    $aprestot3=array_sum($apressup13_3M);
    $nb_dossierstot3=array_sum($nb_dossierssup13_3M);

    $prem_sup_av = round($avanttot1/$nb_dossierstot1, 2);
    $second_sup_av = round($avanttot2/$nb_dossierstot2, 2);
    $three_sup_av = round($avanttot3/$nb_dossierstot3, 2);
    $prem_sup_ap = round($aprestot1/$nb_dossierstot1, 2);
    $second_sup_ap = round($aprestot2/$nb_dossierstot2, 2);
    $three_sup_ap = round($aprestot3/$nb_dossierstot3, 2);

    $tab_csv["61"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_av);
    $tab_csv["62"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_ap);
    $tab_csv["63"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", round((($prem_sup_ap/$prem_sup_av)*100)-100, 2)).' %';
    $tab_csv["64"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_av);
    $tab_csv["65"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_ap);
    $tab_csv["66"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", round((($second_sup_ap/$second_sup_av)*100)-100, 2)).' %';
    $tab_csv["67"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_av);
    $tab_csv["68"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_ap);
    $tab_csv["69"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", round((($three_sup_ap/$three_sup_av)*100)-100, 2)).' %';

    // fin Evolution LDL


    // Evolution tension
    $tab_csv["70"] = ($denominateur[1] > 5) ? round(($numerateur[1] / $denominateur[1]) * 100).' %' : '-';
    $tab_csv["71"] = ($denominateur[2] > 5) ? round(($numerateur[2] / $denominateur[2]) * 100).' %' : '-';
    $tab_csv["72"] = ($denominateur[3] > 5) ? round(($numerateur[3] / $denominateur[3]) * 100).' %' : '-';
    $tab_csv["73"] = ($denominateur[4] > 5) ? round(($numerateur[4] / $denominateur[4]) * 100).' %' : '-';

    // fin Evolution tension
}
else {
    $tab_csv["43"] = "nc";
    $tab_csv["44"] = "nc";
    $tab_csv["45"] = "nc";
    $tab_csv["46"] = "nc";
    $tab_csv["47"] = "nc";
    $tab_csv["48"] = "nc";
    $tab_csv["49"] = "nc";
    $tab_csv["50"] = "nc";
    $tab_csv["51"] = "nc";
    $tab_csv["52"] = "nc";
    $tab_csv["53"] = "nc";
    $tab_csv["54"] = "nc";
    $tab_csv["55"] = "nc";
    $tab_csv["56"] = "nc";
    $tab_csv["57"] = "nc";
    $tab_csv["58"] = "nc";
    $tab_csv["59"] = "nc";
    $tab_csv["60"] = "nc";
    $tab_csv["61"] = "nc";
    $tab_csv["62"] = "nc";
    $tab_csv["63"] = "nc";
    $tab_csv["64"] = "nc";
    $tab_csv["65"] = "nc";
    $tab_csv["66"] = "nc";
    $tab_csv["67"] = "nc";
    $tab_csv["68"] = "nc";
    $tab_csv["69"] = "nc";
    $tab_csv["70"] = "nc";
    $tab_csv["71"] = "nc";
    $tab_csv["72"] = "nc";
    $tab_csv["73"] = "nc";
}


$tab_csv["74"] = $allSpiro["spiro_unique_patient"];
$tab_csv["75"] = sprintf("%.2f", ($allSpiro["spiro_unique_patient"] / (0.25 * 0.72 * $cpt_patient)) * 100).' %';
$tab_csv["76"] = $allCogni['cogni_unique_patient'];
$tab_csv["77"] = sprintf("%.2f", ($allCogni['cogni_unique_patient'] / $cpt_cogni) * 100).' %';
$tab_csv["78"] = $cpt_patient;
$tab_csv["79"] = $cpt_diab2;
$tab_csv["80"] = $cpt_hta;
$tab_csv["81"] = round((0.25 * 0.72 * $cpt_patient), 0);
$tab_csv["82"] = $cpt_cogni;
$tab_csv["83"] = md5($current_cabinet).'_'.$current_cabinet;




#var_dump($tab_csv);
#echo '<p>';
#var_dump($tab_csv);exit;



// Ecriture dans fichier csv

// entete
$tab_entete = array();

if(!file_exists($config->files_path.'/dashboard/csv/'.$filename.'.csv'))
{
    // activit�
    $tab_entete["0"] = "Id cabinet";
    $tab_entete["1"] = "Nom cabinet";
    $tab_entete["2"] = "Infirmier(es)";
    $tab_entete["3"] = "Localisation";
    $tab_entete["4"] = "Mois concerne";
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

    // Analyse activit�
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
    $tab_entete["84"] = "Patient sevrage tabac"; // herve ajout sevrage tabac
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
}


#exit;


$fp = fopen($config->files_path.'/dashboard/csv/'.$filename.'.csv', 'a+');
echo 'hello file is open';
if(sizeof($tab_entete) != 0) {
    fputcsv($fp, $tab_entete, ";");
    echo 'hello we wrote something in the file ';
}
fputcsv($fp, $tab_csv, ";");
echo 'hello we wrote something in the file AGAIN';
fclose($fp);
echo 'hello we close the file';
// fin ecriture csv


// affiche de la dur�e du script de la page pour optimisation des calculs
$tspfin = strtotime(date('Y-m-d H:i:s'));
$timing = $tspfin-$tspdeb;
if($timing > 120){$timing = $timing/60; echo 'temps �coul� : '.$timing. 'minutes';}
else { echo 'temps �coul� : '.round($timing,4).' secondes';}



//sleep(60);
echo "<br>ok fin : ".$filename;
if($type_tdb == "cabinet"){



    if(true){
        Dashboard::record($tab_csv);
    }


    /* Table tampon pour r�cup le cabinet � traiter */
    $q = "UPDATE account SET tdb_export=NOW() WHERE cabinet='".$str_cabinets."'";
    $r = mysql_query($q);
    mysql_close();

    echo '<script>window.location.reload()</script>';
}
error_log("hello fin",0);
//} // fin while boucle cabinet
?>

