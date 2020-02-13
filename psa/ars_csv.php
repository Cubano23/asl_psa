<?php

require_once("persistence/ConnectionFactory.php");

// recup des WS pour le refrentiel infirmi&egrave;res
// connexion aux WS
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

mysql_connect($hostname_mysql, $username_mysql, $password_mysql) or die("Impossible de se connecter au SGBD");
mysql_select_db($database_mysql) or die("Impossible de se connecter &agrave; la base");

$liste_region = mysql_query("SELECT distinct(region) FROM account");

$step = 1;
$date_end = date('Y-m-31', strtotime('-1 month'));
$date_start = date('Y-m-31', strtotime('-13 month'));
//$_POST['region'] = "Aquitaine";
if(isset($_POST['region'])) {
  $region = $_POST['region'];
  $step = 2;
  switch ($region) {
    case 'Occitanie': $str_search = "region='Occitanie'"; break;
    case 'Nouvelle Aquitaine': $str_search = "region='Nouvelle-Aquitaine'"; break;
    case 'Ile-de-France': $str_search = "region='Ile-de-France'"; break;
  }
  $q = "SELECT * FROM account WHERE " . $str_search . " ORDER BY region, cabinet";
  $liste_cabinet = mysql_query($q);

  $tab_entete = array("cabinet", "date debut", "date fin", "equipe", "docteur", "infirmière", "ville", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN");
  $fp = fopen('ars_' . $region . '.csv', 'a+');
  fputcsv($fp, $tab_entete, ";"); 

  $cpt = 0;

  while($tab_cabinet = mysql_fetch_assoc($liste_cabinet)) {

    //echo "<pre>"; var_dump($tab_cabinet); exit();
    $current_cabinet = $tab_cabinet['cabinet'];
    $q = "SELECT * FROM dashboard_results WHERE cabinet='".addslashes(strip_tags($current_cabinet))."' AND date_periode >='".$date_start."' AND date_periode <= '".$date_end."'";
    $liste_dashboard = mysql_query($q);

    $q = "SELECT * FROM account WHERE cabinet='".addslashes(strip_tags($current_cabinet))."'";
    $cabinet = mysql_fetch_assoc(mysql_query($q));
    // get docteur et infirimieres

    $listeInf = '';
    $infirmieresDuCabinet = GetLoginsByCab($cabinet['cabinet'], $status);
    #var_dump($infirmieresDuCabinet);

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



    // calcul des "Nombre total et par types de pathologie de patients ayant b&eacute;n&eacute;fici&eacute;"
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

    // 20/11/2017 : retravaille pierre pour K/L/M/N/O
    // calcul des "Nombre total et par type de pathologies de patients ayant bénéficié d'un "
    $q = "SELECT e.id, e.type_consultation FROM evaluation_infirmier AS e INNER JOIN dossier AS d ON e.id=d.id WHERE cabinet='" . $current_cabinet . "' AND e.date >='".$date_start."' AND e.date <= '".$date_end."'";
    $liste_evaluation = mysql_query($q);
    $total_cpt_beneficie = array(
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
        if(!in_array($tab['id'], $total_cpt_beneficie[$liste_type[$i]])) {
          array_push($total_cpt_beneficie[$liste_type[$i]], $tab['id']);
        }
      }
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

    // calcul "Taux de patients ayant arr&ecirc;t&eacute; de fumer"
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

    
    $tab_csv = array($current_cabinet, date('d/m/Y', strtotime($date_start)), date('d/m/Y', strtotime($date_end)), $cabinet['nom_complet'], $strMedecins, $listeInf, $cabinet['ville'], count($total_cpt_beneficie["dep_diab"]), count($total_cpt_beneficie["suivi_diab"]), (count($total_cpt_beneficie['rcva']) + count($total_cpt_beneficie['automesure'])), (count($total_cpt_beneficie['bpco']) + count($total_cpt_beneficie['sevrage_tabac'])), count($total_cpt_beneficie['cognitif']), count($total_cpt["dep_diab"]), count($total_cpt["suivi_diab"]), (count($total_cpt['rcva']) + count($total_cpt['automesure'])), (count($total_cpt['bpco']) + count($total_cpt['sevrage_tabac'])), count($total_cpt['cognitif']), (($periode_cpt_depistage_diab / 0) * 100 . '%'), (($periode_cpt_suiv_diab / 0) * 100 . '%'), (round(($periode_cpt_rcva / $cabinet['total_HTA']) * 100, 2) . '%'), (round(($periode_cpt_bpco / (0.25 * 0.72 * $cpt_patient)) * 100, 2) . '%'), (round(($periode_cpt_cognitif / $cabinet['total_cogni']) * 100, 2) . '%'), (round(($cpt_hba1c_inf / count($temp_retenu)) * 100) . '%'), (round(($cpt_hba1c_middle / count($temp_retenu)) * 100) . '%'), (round(($cpt_hba1c_sup / count($temp_retenu)) * 100) . '%'), (round($periode_cpt_tension['1_consult'] / 12) . '%'), (round($periode_cpt_tension['2_consult'] / 12) . '%'), (round($periode_cpt_tension['3_consult'] / 12) . '%'), (round($periode_cpt_tension['4_consult'] / 12) . '%'), $nb_arret_tabac['nb_arret_tabac']);
    fputcsv($fp, $tab_csv, ";");
    echo "<br />cabinet ok: " . $current_cabinet;
  }
  fclose($fp);
}




?>


<?php if($step == 1): ?>
<table style="width:100%">
  <form method="POST" action="">
    <tr>
      <td width="100">R&eacute;gion:</td>
      <td width="10">&nbsp;</td>
      <td>
          <?php if($step == 1): ?>
            <select name="region">
              <option>-- s&eacute;lectionnez --</option>
              <option value="Nouvelle Aquitaine" <?php echo ($region == "Nouvelle Aquitaine") ? 'selected' : "" ?>>Nouvelle Aquitaine</option>
              <option value="Occitanie" <?php echo ($region == "Occitanie") ? 'selected' : "" ?>>Occitanie</option>
              <option value="Ile-de-France" <?php echo ($region == "Ile-de-France") ? 'selected' : "" ?>>Ile-de-France</option>
            </select> &nbsp; <?php if($step == 1): ?><input type="submit" value="ok"><?php endif ?>
          <?php else: ?>
            <?php echo $region ?>&nbsp;<a href="" style="color: #CCC;font-size: 11px;text-decoration: none;margin-left: 35px;">x r&eacute;initialiser</a>
          <?php endif ?>
      </td>
    </tr>
  </form>
</table>
<?php endif ?>
</body>
</html>