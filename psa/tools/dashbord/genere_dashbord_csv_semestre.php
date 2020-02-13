<?php

  require_once("../../bean/FicheCabinet.php");
  require_once("../../bean/SuiviHebdomadaireTempsPasse.php");
  require_once("../../bean/EvaluationInfirmier.php");
  require_once("../../bean/SuiviReunionMedecin.php");
  
  /* persistence object */
  require_once("../../persistence/FicheCabinetMapper.php");
  require_once("../../persistence/SuiviHebdomadaireTempsPasseMapper.php");
  require_once("../../persistence/EvaluationInfirmierMapper.php");
  require_once("../../persistence/SuiviReunionMedecinMapper.php");

  require_once("../../persistence/ConnectionFactory.php");
  require_once("../../tools/date.php");
  require_once("../../bean/beanparser/htmltags.php");
  require_once("../../view/jsgenerator/jsgenerator.php");
  require_once("../../view/common/vars.php");


$serveur = 'localhost';
/*
$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
*/
$idDB = 'informed';
$mdpDB = 'no11iugX';
$DB = 'informed3';



mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");

function stripAccents($text)
{
    $text=str_replace("é", "e", utf8_decode($text));
    $text=str_replace("è", "e", utf8_decode($text));
    $text=str_replace("ë", "e", utf8_decode($text));
    $text=str_replace("ê", "e", utf8_decode($text));
    $text=str_replace("à", "a", utf8_decode($text));
    $text=str_replace("ä", "a", utf8_decode($text));
    $text=str_replace("â", "a", utf8_decode($text));
    $text=str_replace("ÿ", "y", utf8_decode($text));
    $text=str_replace("ô", "o", utf8_decode($text));
    $text=str_replace("ö", "o", utf8_decode($text));
    $text=str_replace("'", "-", utf8_decode($text));
    $text=str_replace("ç", "c", utf8_decode($text));
    
    return $text;
}
function date_diff2($date1, $date2)  
{ 
 $s = $date2-$date1; 
 $d = intval($s/86400)+1;   
 return $d; 
}

if(date('d') >= 7)
{
  $month_to_traite = "-1 month";
  $month_debut = "6 month";
}else{
  $month_to_traite = "-2 month";
  $month_debut = "-7 month";
}
$current_year = "2014";//date("Y",strtotime($month_to_traite)); //'2013';
$current_month = "11";//date("m",strtotime($month_to_traite)); //'03';

$start_year = "2014";//date("Y",strtotime($month_debut)); //'2013';
$start_month = "06";//date("m",strtotime($month_debut)); //'03';


global $date_start; $date_start = $start_year.'-'.$start_month.'-01';
global $date_end; $date_end = $current_year.'-'.$current_month.'-31';


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
$first_lundi_final = strtotime('2014-05-26');
$last_lundi_final = strtotime('2014-12-01');
$diff_start = (7 - date_diff2($first_lundi_final, mktime(0, 0, 0, $start_month, 1, $start_year)) + 1);
$diff_end = ((date_diff2($last_lundi_final, mktime(0, 0, 0, $current_month, ($nb_jour_month), $current_year))));


$cpt = 0;


/* Table tampon pour récup le cabinet à traiter */
$q = "SELECT * FROM temp_dashboard WHERE is_ok=0 LIMIT 1";
$r = mysql_query($q);
$curr = mysql_fetch_array($r);

if(mysql_num_rows($r) == 0)
{
  echo '<br>@@ final @@';
  //mail('pierre.dufour@touaregs.com', 'cron dashboard', 'cron ok');
  exit();
}


//$q = "SELECT * FROM account WHERE cabinet LIKE 'Le%'";
//$q = "SELECT * FROM account WHERE infirmiere!='' and region!='' ORDER BY cabinet";
$q = "SELECT * FROM account WHERE cabinet ='".$curr["cabinet"]."'";
$res_cabinet = mysql_query($q);
$total_cabinet = mysql_num_rows($res_cabinet);


while($tab_cabinet = mysql_fetch_array($res_cabinet))
{
  
  
  $cpt++;

  $current_cabinet = $tab_cabinet['cabinet'];

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
  $const_demi_jour = 420; // en minutes car nous avons en dur&eacute;e minute dans suivi hebdo du temps
  $const_objectif_consult_jour = 6;
  $total_temps = 0;

          $FicheCabinet=New FicheCabinet();
          $FicheCabinet->cabinet = $current_cabinet;
          $FicheCabinet->region = $account->region;
          $FicheCabinet->infirmiere = $account->infirmiere;
          $resultCabinet = $FicheCabinetMapper->findObject($FicheCabinet->beforeSerialisation($account));
          $FicheCabinet = $resultCabinet->afterDeserialisation($account);

          // Activit&eacute; : suivi temps hebdo 
          $SuiviHebdomadaireTempsPasse = new SuiviHebdomadaireTempsPasse();
          //$SuiviHebdomadaireTempsPasse->date= '2013-05-20';
          $SuiviHebdomadaireTempsPasse->info_asalee = 0;
          $SuiviHebdomadaireTempsPasse->cabinet = $current_cabinet;
          
          //echo "<br>select * from suivi_hebdo_temps_passe where cabinet=".$current_cabinet." AND date >= '".date('Y-m-d', $first_lundi_final)."' AND date < '".date('Y-m-d', strtotime('+1 week', $last_lundi_final))."' ORDER BY date ASC";
          //exit();
          $resultTemps = $SuiviHebdomadaireTempsPasseMapper->getObjectsByCabinetBetweenDates($current_cabinet, date('Y-m-d', $first_lundi_final), date('Y-m-d', strtotime('+1 week', $last_lundi_final)));
          $formation = 0;
          $contribution = 0;
          $dossier = 0;
          $coordination = 0;
          $non_attribue = 0;
          $temps_total_suivi_temps = 0;

          foreach ($resultTemps as $key => $value) 
          {
            //echo '<pre>';
            if($key == 0)
            {
              //echo '<br>#'.$key.' : first => prorata : '.$diff_start;
              $coef_diff = $diff_start;
            }
            else if(($key == sizeof($resultTemps) - 1) && (intval(substr($value['date'], 8, 2)) + 7 > cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year)))
            {
              //echo '<br>#'.$key.' : last => prorata : '.$diff_end;
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
            if($value['info_asalee'] != null)
              $dossier += ((($value['info_asalee']) / 7) * $coef_diff);
            if($value['tps_reunion_infirmiere'] != null)
              $formation += (($value['tps_reunion_infirmiere']) / 7) * $coef_diff;
            
          }
          
          
          // Activit&eacute; : liste_exams 
          $evaluationInfirmier = new EvaluationInfirmier();
          $evaluationInfirmier->date = dateToMysqlDate($SuiviHebdomadaireTempsPasse->date);
          $saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetBetweenDate($current_cabinet, date('Y-m-d', $first_lundi_final), date('Y-m-d', strtotime('+1 week', $last_lundi_final)));
          $consultation = 0;
          $aJourconsult = array();
          $aPatient = array();
          $aProtocole = array('dep_diab'=>array(), 'suivi_diab'=>array(), 'rcva'=>array(), 'cognitif'=>array(), 'bpco'=>array(), 'automesure'=>array(), 'autres'=>array(), 'uterus'=>array(), 'sein'=>array(), 'hemocult'=>array(), 'colon'=>array());
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

          foreach ($saisieInfirmiere as $key => $value) 
          {
            //echo "<br>".$value['date'].' >= '.$date_start.' /// '.$value['date'].' <= '.$date_end;
            if(($value['date'] >= $date_start) && ($value['date'] <= $date_end))
            {
                  // echo '<pre style="background-color:#CCC;">';
                  // var_dump($value);
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
              $aTypeConsult = explode(',', $value['type_consultation']);
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
              if(($value['dcreat'] >= $date_start) && (!in_array($value['numero'], $aNewPatientMois)))  // nb new patient dans le mois
                array_push($aNewPatientMois, $value['numero']);
                //$nb_new_mois++;
              
              
            
              $TpsConsultation[$value['type_consultation']] = $TpsConsultation[$value['type_consultation']]+intval($value['duree']);

              $examsDerogatoire['spiro'] += ($value['spirometre'] != NULL) ? $value['spirometre'] : 0;//((($value['spirometre_seul'] != NULL) ? $value['spirometre_seul'] : 0) + (($value['spirometre'] != NULL) ? $value['spirometre'] : 0));
              $examsDerogatoire['cogn'] += $value['t_cognitif'];
              $examsDerogatoire['ecg'] += ($value['ecg'] != NULL) ? $value['ecg'] : 0;//((($value['ecg_seul'] != NULL) ? $value['ecg_seul'] : 0) + (($value['ecg'] != NULL) ? $value['ecg'] : 0));
              $examsDerogatoire['pied'] += $value['exapied'];
              $examsDerogatoire['monofil'] += $value['monofil'];
              $examsDerogatoire['autre'] += $value['hba'];
            }
          }
//var_dump($TpsConsultation);

          // Calcul du temps non attribué car datas dans suivi_hebdo + dans saisie inf pour le calcul du temps consulation
          $non_attribue = 0;
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
              else if(($key == sizeof($resultTemps) - 1) && (intval(substr($value['date'], 8, 2)) + 7 > cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year)))
              {
                $coef_diff = $diff_end;
              }
              else
              {
                $coef_diff = 7;
              }
              $temp_consult_semaine = 0;
              foreach ($saisieInfirmiere as $value_saisie) 
              {
// echo '<pre style="background-color:#999;">';
// var_dump($value_saisie);
// echo '</pre>';                
                //echo "<br>#(".$value_saisie['date'].' >= '.$value['date'].') && ('.$value_saisie['date'].' < '.date('Y-m-d', strtotime('+1 week', mktime(0, 0, 0, substr($value['date'], 5, 2), substr($value['date'], 8, 2), substr($value['date'], 0, 4)))).')';
                if(($value_saisie['date'] >= $value['date']) && ($value_saisie['date'] < date('Y-m-d', strtotime('+1 week', mktime(0, 0, 0, substr($value['date'], 5, 2), substr($value['date'], 8, 2), substr($value['date'], 0, 4))))))
                {
                  //echo "<br>@@@@@ ok";
                  $prepa_dossier = 0;
                  switch ($value_saisie['type_consultation']) 
                  {
                    case 'rcva': $prepa_dossier = $value_saisie['duree'] * 0.25; break;
                    case 'suivi_diab': $prepa_dossier = $value_saisie['duree'] * 0.25; break;
                    case 'dep_diab': $prepa_dossier = $value_saisie['duree'] * 0.25; break;
                    case 'bpco': $prepa_dossier = $value_saisie['duree'] * 0.2; break;
                    case 'cognitif': $prepa_dossier = $value_saisie['duree'] * 0.1; break;
                    case 'autres': $prepa_dossier = $value_saisie['duree'] * 0.2; break;  
                  }
                  $temp_consult_semaine += $value_saisie['duree'] + $prepa_dossier;
                }
              }
              $non_attribue_temp = $value['tps_passe_cabinet'] - ($temp_consult_semaine + $value['formation'] + $value['autoformation'] + $value['stagiaires'] + $value['tps_contact_tel_patient'] + $value['info_asalee'] + $value['tps_reunion_infirmiere'] + $value['tps_reunion_medecin']);
              echo "<br>@@@@@ non_attrib_temp: ".$non_attribue;
              $non_attribue += ($non_attribue_temp / 7) * $coef_diff;
            }
          }
//exit();
          /*echo '<pre style="background-color:#CCC;">';
          var_dump(sizeof($saisieInfirmiere));
          echo '</pre>';*/

          //var_dump($aJourconsult);
          
          // PREPARATION BILAN DES CONSULTATIONS est calcul&eacute; en appliquant des taux forfaitaires par rapport au temps de consultation. 
          $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) + ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['autres']*0.2));
          $SuiviHebdomadaireTempsPasse->formation = $formation;
          $SuiviHebdomadaireTempsPasse->non_attribue = $non_attribue;
          $SuiviHebdomadaireTempsPasse->tps_contact_tel_patient = $contribution;
          $SuiviHebdomadaireTempsPasse->info_asalee = $dossier + $tempsPrepaBilanConsultation;
          $total_temps += $SuiviHebdomadaireTempsPasse->formation + $SuiviHebdomadaireTempsPasse->tps_contact_tel_patient + $SuiviHebdomadaireTempsPasse->info_asalee + $non_attribue;


          $total_temps += $consultation;
          //echo'<hr>';
          //var_dump ($nb_new_mois);

          // Activit&eacute; : liste_exams 
          $SuiviReunionMedecin = new SuiviReunionMedecin();
          $SuiviReunionMedecin = $SuiviReunionMedecinMapper->getObjectsByCabinetBetweenDate($current_cabinet, $date_start, $date_end);
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

          // calcul muti-protocole : calcul doublon dans $aPatientProtocole
// echo '<pre style="background-color:#CCC;">';
// var_dump(count(array_unique($aPatientProtocole)));
// echo '</pre>';
// exit();
          $objExams->nb_multiprotocole = count(array_unique($aPatientProtocole));//sizeof(array_unique($aPatientProtocole));
          $objExams->nb_new = sizeof($aNewPatientMois); //$nb_new_mois;


          // Nb exams saisis ou int&eacute;gr&eacute;s / nb exams r&eacute;alis&eacute;s
          // R&eacute;alis&eacute;s : 
          $req = "SELECT count(*) as nb FROM liste_exam as e INNER JOIN dossier as d ON e.id=d.id WHERE d.cabinet='".$current_cabinet."' AND e.date_exam BETWEEN '".$date_start."' AND '".$date_end."'";
          $res=mysql_query($req);
          $nb_exam_realises = mysql_fetch_assoc($res);
          // Saisis ou int&eacute;gr&eacute;s : 
          $req = "SELECT count(*) as nb FROM liste_exam as e INNER JOIN dossier as d ON e.id=d.id WHERE d.cabinet='".$current_cabinet."' AND e.dmaj BETWEEN '".$date_start."' AND '".$date_end."'";
          $res=mysql_query($req);
          $nb_exam_saisis = mysql_fetch_assoc($res);


/////////////////////////////////////////////////////////////////
// Calcul &eacute;volution tension
 

global $dossierssup140;
global $change;

$req="SELECT dossier.cabinet, count(*), nom_cab, region ".
     "FROM dossier, account ".
     "WHERE infirmiere!='' and region!='' ".
     "AND actif='oui' ".
     "and dossier.cabinet=account.cabinet ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14
     "GROUP BY nom_cab ".
     "ORDER BY nom_cab, numero ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

if (mysql_num_rows($res)==0) {
  //exit ("<p align='center'>Aucun cabinet n'est actif</p>");
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
   
//   $tpat[$cab] = $pat;
}

sort($liste_reg);

//Liste des consults par patient
$req="SELECT cabinet, dossier.id, date ".
     "FROM evaluation_infirmier, dossier ".
     "WHERE actif='oui' ".
     "AND evaluation_infirmier.id=dossier.id ".
     "AND cabinet='".$current_cabinet."' ".
     "ORDER BY cabinet, id, date ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$id_prec="";

while(list($cabinet, $id, $date)=mysql_fetch_row($res)){
  if(isset($regions[$cabinet])){
    if($id_prec!=$id){//Nouveau dossier=> 1&egrave;re consult
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
     "and date_exam > '2009-01-01' AND date_exam < '".$date_end."' ".
     "and cabinet='".$current_cabinet."'".
     "GROUP BY cabinet, dossier.id, date_exam ";
     "ORDER BY cabinet, dossier.id, date_exam ";

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

//Liste des tensions par patient en suivi diab&egrave;te
$req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
     "FROM suivi_diabete, dossier, liste_exam ".
     "WHERE actif='oui' ".
     "AND dossier_id=dossier.id and dossier.id=liste_exam.id ".
     "and date_exam > '2009-01-01' AND date_exam < '".$date_end."' ".
     "and type_exam='systole' ".
     "and cabinet='".$current_cabinet."'".
     "GROUP BY cabinet, dossier.id, date_exam ";
     "ORDER BY cabinet, dossier.id, date_exam ";

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



foreach($liste_tension as $id => $tab){
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
    }
  }

// fin evolution tension
//////////////////////////////////////////////////


// debut HB1Ac
////////////////////////////////////////////////// 1 ère CONSULTATION ///////////////////////////////////

$req1="SELECT account.cabinet, nom_cab ".
     "FROM dossier, account ".
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14
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
    "dossier.cabinet='$current_cabinet' and ".
    "type_exam='HBA1c' and suivi_diabete.dossier_id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$date_start."' ".
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

////////////////////////////////////////////////// 2em CONSULTATION ///////////////////////////////////

$req2="SELECT account.cabinet, nom_cab ".
     "FROM dossier, account ".
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14     
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
    "liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$date_start."' and ".

    "dossier.cabinet ='$current_cabinet' and type_exam='HBA1c' ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la 1ère consult
          if($nb_consult==1){
            $hba_prec=$ResHBA;
            $deltaj_prec=$deltaj;
          }
        }
        else{//Un HBA après la consult => on regarde s'il a déjà été enregistré
          if($hba_suiv==0){
            if($nb_consult==2){
              $hba_suiv=$ResHBA;
              $deltaj_suiv=$deltaj;
            }
          }
        }
      }
      else{ //On est sur une nieme consult de ce dossier
        if($nb_consult==1){ //C'est la deuxième consult
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
     "WHERE region!='' and infirmiere!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14     
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
    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$date_start."' and ".
    "dossier.cabinet='$current_cabinet' and type_exam='HBA1c' ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la 1ère consult
          if($nb_consult==1){
            $hba_prec=$ResHBA;
            $deltaj_prec=$deltaj;
          }
        }
        else{//Un HBA après la 3ème consult => on regarde s'il a déjà été enregistré
          if($hba_suiv==0){
            if($nb_consult==3){
              $hba_suiv=$ResHBA;
              $deltaj_suiv=$deltaj;
            }
          }
        }
      }
      else{ //On est sur une nieme consult de ce dossier
        if($nb_consult<3){ //C'est la deuxième consult ou 3eme
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
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14
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
    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$date_start."' and ".
    "dossier.cabinet='$current_cabinet' and type_exam='HBA1c' ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la 1ère consult
          if($nb_consult==1){
            $hba_prec=$ResHBA;
            $deltaj_prec=$deltaj;
          }
        }
        else{//Un HBA après la 3ème consult => on regarde s'il a déjà été enregistré
          if($hba_suiv==0){
            if($nb_consult==4){
              $hba_suiv=$ResHBA;
              $deltaj_suiv=$deltaj;
            }
          }
        }
      }
      else{ //On est sur une nieme consult de ce dossier
        if($nb_consult<4){ //C'est la deuxième consult ou 3eme
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
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14     
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
    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$date_start."' and ".
    "dossier.cabinet='$current_cabinet' and type_exam='HBA1c' ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la 1ère consult
          if($nb_consult==1){
            $hba_prec=$ResHBA;
            $deltaj_prec=$deltaj;
          }
        }
        else{//Un HBA après la 3ème consult => on regarde s'il a déjà été enregistré
          if($hba_suiv==0){
            if($nb_consult==5){
              $hba_suiv=$ResHBA;
              $deltaj_suiv=$deltaj;
            }
          }
        }
      }
      else{ //On est sur une nieme consult de ce dossier
        if($nb_consult<5){ //C'est la deuxième consult ou 3eme
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
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14     
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
    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2009-01-01' AND liste_exam.date_exam < '".$date_start."' and ".
    "dossier.cabinet='$current_cabinet' and type_exam='HBA1c' ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la 1ère consult
          if($nb_consult==1){
            $hba_prec=$ResHBA;
            $deltaj_prec=$deltaj;
          }
        }
        else{//Un HBA après la 3ème consult => on regarde s'il a déjà été enregistré
          if($hba_suiv==0){
            if($nb_consult==6){
              $hba_suiv=$ResHBA;
              $deltaj_suiv=$deltaj;
            }
          }
        }
      }
      else{ //On est sur une nieme consult de ce dossier
        if($nb_consult<6){ //C'est la deuxième consult ou 3eme
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
////////////////////////////////////////////////// 1 ère CONSULTATION ///////////////////////////////////
$req="SELECT account.cabinet, nom_cab ".
     "FROM dossier, account ".
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14     
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
    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and cabinet='$current_cabinet' and date_exam > '2009-01-01' AND date_exam < '".$date_start."'  and ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la consult
          $LDL_prec=$LDL;
          $deltaj_prec=$deltaj;
        }
        else{//Un HBA après la consult => on regarde s'il a déjà été enregistré
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
////////////////////////////////////////////////// 2 ème CONSULTATION ///////////////////////////////////

$req2="SELECT account.cabinet, nom_cab ".
     "FROM dossier, account ".
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14     
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
    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and date_exam > '2009-01-01' AND date_exam < '".$date_start."'  and ".

    "dossier.cabinet='$current_cabinet' and type_exam='LDL' ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la 1ère consult
          if($nb_consult==1){
            $LDL_prec=$LDL;
            $deltaj_prec=$deltaj;
          }
        }
        else{//Un HBA après la consult => on regarde s'il a déjà été enregistré
          if($LDL_suiv==0){
            if($nb_consult==2){
              $LDL_suiv=$LDL;
              $deltaj_suiv=$deltaj;
            }
          }
        }
      }
      else{ //On est sur une nieme consult de ce dossier
        if($nb_consult==1){ //C'est la deuxième consult
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
////////////////////////////////////////////////// 3 ème CONSULTATION ///////////////////////////////////

$req3="SELECT account.cabinet, count(*), nom_cab ".
     "FROM dossier, account ".
     "WHERE infirmiere!='' and region!='' ".
     "AND dossier.cabinet=account.cabinet ".
     "AND actif='oui' ".
     "AND account.cabinet='".$current_cabinet."' ".     // ICI MODIF 11/02/14     
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
    "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and date_exam > '2009-01-01' AND date_exam < '".$date_start."'  and ".
    "dossier.cabinet='$current_cabinet' and type_exam='LDL' ".
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
    else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
      if($date_consult_prec==$date_consult){
        if($deltaj<0){//Le HBA est avant la 1ère consult
          if($nb_consult<3){
            $LDL_prec=$LDL;
            $deltaj_prec=$deltaj;
          }
        }
        else{//Un HBA après la consult => on regarde s'il a déjà été enregistré
          if($LDL_suiv==0){
            if($nb_consult==3){
              $LDL_suiv=$LDL;
              $deltaj_suiv=$deltaj;
            }
          }
        }
      }
      else{ //On est sur une nieme consult de ce dossier
        if($nb_consult<3){ //C'est la deuxième consult
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

//////////////////////////////////////////////////////
// EFR et troubles cognitifs

$query = "select COUNT(DISTINCT(evaluation_infirmier.id)) as cogni_unique_patient from evaluation_infirmier,dossier where cabinet='".$current_cabinet."' and evaluation_infirmier.id = dossier.id AND type_consultation like '%cognitif%' AND dnaiss < subdate( NOW(), INTERVAL 75 YEAR)";
$result = mysql_query($query);
$allCogni = mysql_fetch_array($result);

$query =  "select COUNT(DISTINCT(evaluation_infirmier.id)) as spiro_unique_patient from evaluation_infirmier,dossier where cabinet='".$current_cabinet."' and evaluation_infirmier.id = dossier.id AND (spirometre='1' OR spirometre_seul='1')";
$result = mysql_query($query);
$allSpiro = mysql_fetch_array($result);

// fin EFR et troubles cognitifs
//////////////////////////////////////////////////////


//////////////////////////////////////////////////////
// Liste des medecins pour l'entête

$query = "select nom from medecin where cabinet='".$current_cabinet."'";
$result = mysql_query($query);
$strMedecins = "Drs. ";
while($tab = mysql_fetch_array($result)) {
  $strMedecins .= $tab['nom'].", ";
}
$strMedecins = substr($strMedecins, 0, strlen($strMedecins) - 2);



$aMonth = array ('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'); 

$tab_csv["0"] = $current_cabinet;
$tab_csv["1"] = getPropertyValue("FicheCabinet:nom_complet");
$tab_csv["2"] = stripAccents(getPropertyValue("FicheCabinet:contact"));
$tab_csv["3"] = stripAccents(getPropertyValue("FicheCabinet:ville")).' - '.stripAccents(getPropertyValue("FicheCabinet:region"));
$tab_csv["4"] = ucfirst($aMonth[$current_month - 1]).' '.$current_year;
$tab_csv["5"] = sprintf("%.1f", round($objExams->consultation / $const_demi_jour, 1));
$tab_csv["6"] = sprintf("%.2f", round(($objExams->consultation / $objExams->total) * 100, 0)).' %';
$tab_csv["7"] = sprintf("%.1f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:info_asalee") / $const_demi_jour), 1));
$tab_csv["8"] = sprintf("%.2f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:info_asalee") / $objExams->total) * 100, 0)).' %';
$tab_csv["9"] = sprintf("%.1f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_reunion_medecin") / $const_demi_jour), 1));
$tab_csv["10"] = sprintf("%.2f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_reunion_medecin") / $objExams->total) * 100, 0)).' %';
$tab_csv["11"] = sprintf("%.1f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:formation") / $const_demi_jour), 1));
$tab_csv["12"] = sprintf("%.2f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:formation") / $objExams->total) * 100, 0)).' %';
$tab_csv["13"] = sprintf("%.1f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_contact_tel_patient") / $const_demi_jour), 1));
$tab_csv["14"] = sprintf("%.2f", round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_contact_tel_patient") / $objExams->total) * 100, 0)).' %';
$tab_csv["15"] = sprintf("%.1f", round($non_attribue/ $const_demi_jour, 1));
$tab_csv["16"] = sprintf("%.2f", round(($non_attribue / $objExams->total) * 100, 0)).' %';
$tab_csv["17"] = sprintf("%.1f", round($objExams->total / $const_demi_jour, 1));
$tab_csv["18"] = sprintf("%.2f", 100).' %';

$jour_activite = (getPropertyValue("SuiviHebdomadaireTempsPasse:info_asalee") + getPropertyValue("SuiviHebdomadaireTempsPasse:tps_reunion_medecin") + $objExams->consultation + $non_attribue) / ($const_demi_jour);
$tab_csv["19"] = sprintf("%.2f", $jour_activite);
$tab_csv["20"] = $nb_saisie_inf_periode;
$tab_csv["21"] = sprintf("%.2f", $nb_saisie_inf_periode / $jour_activite);
$tab_csv["22"] = sprintf("%.2f", round((($nb_saisie_inf_periode / $jour_activite) / $const_objectif_consult_jour) * 100, 2)).' %';
$tab_csv["23"] = ($examsDerogatoire['spiro'] + $examsDerogatoire['cogn'] + $examsDerogatoire['ecg'] + $examsDerogatoire['pied'] + $examsDerogatoire['monofil'] + $examsDerogatoire['autre']);
$tab_csv["24"] = $examsDerogatoire['spiro'];
$tab_csv["25"] = $examsDerogatoire['cogn'];
$tab_csv["26"] = $examsDerogatoire['ecg'];
$tab_csv["27"] = $examsDerogatoire['pied'];
$tab_csv["28"] = $examsDerogatoire['monofil'];
$tab_csv["29"] = $examsDerogatoire['autre'];
$tab_csv["30"] = $nb_exam_saisis['nb'];
$tab_csv["31"] = $nb_exam_realises['nb'];
$autre_type_consult = sizeof($objExams->protocoles['autres']) + sizeof($objExams->protocoles['automesure']) + sizeof($objExams->protocoles['colon']) + sizeof($objExams->protocoles['sein']) + sizeof($objExams->protocoles['hemocult']) + sizeof($objExams->protocoles['uterus']);
//$autre_type_consult = $objExams->nb_patient - (sizeof($objExams->protocoles['dep_diab']) + sizeof($objExams->protocoles['suivi_diab']) + sizeof($objExams->protocoles['rcva']) + sizeof($objExams->protocoles['cognitif']) + sizeof($objExams->protocoles['bpco']));
$tab_csv["32"] = $autre_type_consult + sizeof($objExams->protocoles['dep_diab']) + sizeof($objExams->protocoles['suivi_diab']) + sizeof($objExams->protocoles['rcva']) + sizeof($objExams->protocoles['cognitif']) + sizeof($objExams->protocoles['bpco']);//$objExams->nb_patient;
$tab_csv["33"] = sizeof($objExams->protocoles['dep_diab']);
$tab_csv["34"] = sizeof($objExams->protocoles['suivi_diab']);
$tab_csv["35"] = sizeof($objExams->protocoles['rcva']);
$tab_csv["36"] = sizeof($objExams->protocoles['cognitif']);
$tab_csv["37"] = sizeof($objExams->protocoles['bpco']);
$tab_csv["38"] = $autre_type_consult;
$tab_csv["39"] = $objExams->nb_multiprotocole;
$tab_csv["40"] = $objExams->nb_new;
$tab_csv["41"] = sprintf("%.2f", ($objExams->nb_new / $objExams->nb_patient) * 100).' %';

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

  $tab_csv["42"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_av);;
  $tab_csv["43"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_ap);
  $tab_csv["44"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", round((($prem_sup_ap/$prem_sup_av)*100)-100, 2)).' %';
  $tab_csv["45"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_av);;
  $tab_csv["46"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_ap);
  $tab_csv["47"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", round((($second_sup_ap/$second_sup_av)*100)-100, 2)).' %';
  $tab_csv["48"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_av);;
  $tab_csv["49"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_ap);
  $tab_csv["50"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", round((($three_sup_ap/$three_sup_av)*100)-100, 2)).' %';
  $tab_csv["51"] = ($nb_dossierstot4 < 6) ? '-' : sprintf("%.2f", $four_sup_av);;
  $tab_csv["52"] = ($nb_dossierstot4 < 6) ? '-' : sprintf("%.2f", $four_sup_ap);
  $tab_csv["53"] = ($nb_dossierstot4 < 6) ? '-' : sprintf("%.2f", round((($four_sup_ap/$four_sup_av)*100)-100, 2)).' %';
  $tab_csv["54"] = ($nb_dossierstot5 < 6) ? '-' : sprintf("%.2f", $five_sup_av);;
  $tab_csv["55"] = ($nb_dossierstot5 < 6) ? '-' : sprintf("%.2f", $five_sup_ap);
  $tab_csv["56"] = ($nb_dossierstot5 < 6) ? '-' : sprintf("%.2f", round((($five_sup_ap/$five_sup_av)*100)-100, 2)).' %';
  $tab_csv["57"] = ($nb_dossierstot6 < 6) ? '-' : sprintf("%.2f", $six_sup_av);;
  $tab_csv["58"] = ($nb_dossierstot6 < 6) ? '-' : sprintf("%.2f", $six_sup_ap);
  $tab_csv["59"] = ($nb_dossierstot6 < 6) ? '-' : sprintf("%.2f", round((($six_sup_ap/$six_sup_av)*100)-100, 2)).' %';

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

  $tab_csv["60"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_av);
  $tab_csv["61"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", $prem_sup_ap);
  $tab_csv["62"] = ($nb_dossierstot1 < 6) ? '-' : sprintf("%.2f", round((($prem_sup_ap/$prem_sup_av)*100)-100, 2)).' %';
  $tab_csv["63"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_av);
  $tab_csv["64"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", $second_sup_ap);
  $tab_csv["65"] = ($nb_dossierstot2 < 6) ? '-' : sprintf("%.2f", round((($second_sup_ap/$second_sup_av)*100)-100, 2)).' %';
  $tab_csv["66"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_av);
  $tab_csv["67"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", $three_sup_ap);
  $tab_csv["68"] = ($nb_dossierstot3 < 6) ? '-' : sprintf("%.2f", round((($three_sup_ap/$three_sup_av)*100)-100, 2)).' %';


// fin Evolution LDL

// Evolution tension
if($dossierssup140[$current_cabinet][1] != "XX")
{
  $cpt_tab = 69;
  for($i=1; $i<=4; $i++)
  {
    $tab_csv[$cpt_tab] = ($dossierssup140[$current_cabinet][$i] < 6) ? "-" : round($change[$current_cabinet][$i]/$dossierssup140[$current_cabinet][$i]*100).' %';
    $cpt_tab++;
  }
}
else
{
  $cpt_tab = 69;
  for($i=1; $i<=4; $i++)
  {
    $tab_csv[$cpt_tab] = '-';
    $cpt_tab++;
  }
}
// fin Evolution tension

$tab_csv["73"] = $allSpiro["spiro_unique_patient"];
$tab_csv["74"] = sprintf("%.2f", ($allSpiro["spiro_unique_patient"] / (0.25 * 0.72 * $tab_cabinet['total_pat'])) * 100).' %';
$tab_csv["75"] = $allCogni['cogni_unique_patient'];
$tab_csv["76"] = sprintf("%.2f", ($allCogni['cogni_unique_patient'] / $tab_cabinet['total_cogni']) * 100).' %';
$tab_csv["77"] = $tab_cabinet['total_pat'];
$tab_csv["78"] = $tab_cabinet['total_diab2'];
$tab_csv["79"] = $tab_cabinet['total_HTA'];
$tab_csv["80"] = round((0.25 * 0.72 * $tab_cabinet['total_pat']), 0);
$tab_csv["81"] = $tab_cabinet['total_cogni']; 




  // Ecriture dans fichier csv
  
  // entete
$tab_entete = array();
if(!file_exists('./csv/'.$current_year.'_'.$current_month.'_semestre.csv'))
{
  // activité
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
  $tab_entete["38"] = "Patient autres types";
  $tab_entete["39"] = "Patient multiprotocole";
  $tab_entete["40"] = "Nb patients du mois";
  $tab_entete["41"] = "% nouveaux patients";

  // hba1c
  $tab_entete["42"] = "1ere HBA1c avant";
  $tab_entete["43"] = "1ere HBA1c apres";
  $tab_entete["44"] = "% 1ere HBA1c";
  $tab_entete["45"] = "2eme HBA1c avant";
  $tab_entete["46"] = "2eme HBA1c apres";
  $tab_entete["47"] = "% 2eme HBA1c";
  $tab_entete["48"] = "3eme HBA1c avant";
  $tab_entete["49"] = "3eme HBA1c apres";
  $tab_entete["50"] = "% 3eme HBA1c";
  $tab_entete["51"] = "4eme HBA1c avant";
  $tab_entete["52"] = "4eme HBA1c apres";
  $tab_entete["53"] = "% 4eme HBA1c";
  $tab_entete["54"] = "5eme HBA1c avant";
  $tab_entete["55"] = "5eme HBA1c apres";
  $tab_entete["56"] = "% 5eme HBA1c";
  $tab_entete["57"] = "6eme HBA1c avant";
  $tab_entete["58"] = "6eme HBA1c apres";
  $tab_entete["59"] = "% 6eme HBA1c";
  
  // ldl
  $tab_entete["60"] = "1ere LDL avant";
  $tab_entete["61"] = "1ere LDL apres";
  $tab_entete["62"] = "% 1ere LDL";
  $tab_entete["63"] = "2eme LDL avant";
  $tab_entete["64"] = "2eme LDL apres";
  $tab_entete["65"] = "% 2eme LDL";
  $tab_entete["66"] = "3eme LDL avant";
  $tab_entete["67"] = "3eme LDL apres";
  $tab_entete["68"] = "% 3eme LDL";

// Evolution tension
  $tab_entete["69"] = "tension 1ere consult";
  $tab_entete["70"] = "tension 2eme consult";
  $tab_entete["71"] = "tension 3eme consult";
  $tab_entete["72"] = "tension 4eme consult";
  
  $tab_entete["73"] = "Nb sipro/patient unique";
  $tab_entete["74"] = "% EFR";
  $tab_entete["75"] = "Nb depistage cognitif";
  $tab_entete["76"] = "% test cognitif";
  $tab_entete["77"] = "Nb patient total";
  $tab_entete["78"] = "Nb patient diab type 2";
  $tab_entete["79"] = "Nb patient risque cardio";
  $tab_entete["80"] = "Nb patient BPCO";
  $tab_entete["81"] = "Nb patient cognitif";
}




  $fp = fopen('./csv/'.$current_year.'_'.$current_month.'_semestre.csv', 'a+');
  if(sizeof($tab_entete) != 0)
    fputcsv($fp, $tab_entete, ";");  

  fputcsv($fp, $tab_csv, ";");
  fclose($fp);
  // fin ecriture csv

/* Table tampon pour récup le cabinet à traiter */
$q = "UPDATE temp_dashboard SET is_ok=1, updated_at=NOW() WHERE id='".$curr['id']."'";
$r = mysql_query($q);
mysql_close();

//sleep(60);
echo "<br>ok fin : ".$current_cabinet;
echo '<script>window.location.reload()</script>';

} // fin while boucle cabinet
?>

