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
}
if(isset($_POST['cabinet'])) {
  $step = 3;
  $region = $_POST['region'];
  $current_cabinet = $_POST['cabinet'];
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
}



?>
<html>
  <head>
    <style type="text/css">
      .xls_reference {
        width: 25px;
        height: 25px;
        text-align: center;
        line-height: 25px;
        background-color: red;
        color: white;
        margin-right: 10px;
        display: inline-block;
      }
      body { font-family: Arial, Helvetica; }
      textarea { width: 100%; height: 70px; }
      tr { vertical-align: top; }
      table { width: 100%; }
      .indicateur_head { background-color: yellow; }
      .indicateur_tbody { background-color: #AAAAAA; color: #FFFFFF; text-align: center; }
      .sidebar { width: 65px; line-height: 100px; position:fixed; right: 0; top:300px; text-align: center;}
      .sidebar .fa { color: #FFFFFF; width: 55px; height: 55px; margin: auto; line-height: 55px; margin-top: 10px; background-color: #920049; cursor:pointer;  }
    </style>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  </head>
  <body>

<table style="width:100%">
  <form method="POST" action="ars.php">
    <!-- <tr>
      <td>date d&eacute;but:</td>
      <td>&nbsp;</td>
      <td><?php echo date('d/m/Y', strtotime($date_start)) ?></td>
    </tr>
    <tr>
      <td>date fin:</td>
      <td>&nbsp;</td>
      <td><?php echo date('d/m/Y', strtotime($date_end)) ?></td>
    </tr> -->
    <!-- <tr>
      <td>R&eacute;gion:</td>
      <td>&nbsp;</td>
      <td>
          <select name="region">
          <?php //while($tab = mysql_fetch_array($liste_region)): ?>  
            <option value="<?php //echo ($tab[0]) ?>" <?php //echo ($region == ($tab[0])) ? 'selected' : "" ?>><?php //echo ($tab[0]) ?></option>
          <?php //endwhile ?>
          </select>
      </td>
    </tr> -->
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
    <?php if($step >= 2): ?>
      <tr>
        <td>Cabinet :</td>
        <td>&nbsp;</td>
        <td>
          <select name="cabinet">
            <option>-- s&eacute;lectionnez --</option>
          <?php while($tab = mysql_fetch_array($liste_cabinet)): ?>  
            <option value="<?php echo $tab[0] ?>" <?php echo ($cabinet["cabinet"] == $tab[0]) ? 'selected' : "" ?>><?php echo $tab['region'].' : '.$tab[0] ?></option>
          <?php endwhile ?>
          </select> <input type="submit" value="ok"> 
          <input type="hidden" name="region" value="<?php echo $region ?>">
        </td>
      </tr>
    <?php endif ?>
    <!-- <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="ok"></td>
    </tr> -->
  </form>
</table>


<?php if($step == 3): ?>
  <div class="sidebar">
    <div class="fa fa-save fa-2x"></div>
    <div class="fa fa-print fa-2x"></div>
  </div>
<?php endif ?>

<?php if($step == 3): ?>
  <?php
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
    
  ?>
  <br />
  <table style="width:90%; border:solid 1px #666;" cellpadding="10">
    <tr>
      <td align="center">ASALEE : travail en &eacute;quipe infirmi&egrave;r(e)s d&eacute;l&eacute;gu&eacute;(e)s &agrave; la sant&eacute; populationnelle & m&eacute;decins g&eacute;n&eacute;ralistes pour l'am&eacute;lioration de la qualit&eacute; des soins et l'allocation optimis&eacute;e de la disponibilit&eacute; des "professionnels de sant&eacute;" sur le territoire concern&eacute;</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td align="right">PC N&deg; 54-0000000008</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td align="center"><h3><span class="xls_reference">D</span>&Eacute;valuation du <?php echo date('d/m/Y', strtotime($date_start)) ?> au <?php echo date('d/m/Y', strtotime($date_end)) ?></h3></td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table>
          <tr valign="top">
            <td width="70%">
              <b><u>Equipe</u></b> : <span class="xls_reference">E</span><?php echo $cabinet['nom_complet'] ?>
              <br />
              Docteur <span class="xls_reference">F</span><?php echo utf8_encode($strMedecins) ?>,<br />
              <span class="xls_reference">G</span><?php echo $listeInf ?>
              <br />
              <b><u>Ville</u></b> : <span class="xls_reference">H</span><?php echo $cabinet['ville'] ?>
            </td>
            <td align="right">N&deg; Equipe dans COOP PS : -</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table>
          <tr>
            <td width="35%">Points positifs</td>
            <td><span class="xls_reference">I</span><textarea>> Nombreuses demandes spontan&eacute;es &eacute;manant des m&eacute;decins comme des infirmi&egrave;res
> L'activit&eacute; des infirmi&egrave;res se traduit par un gain de temps de 20' par consultation de fond (patient diab&eacute;tique d&eacute;s&eacute;quilibr&eacute;) et de 7' pour les consultations de suivi. </textarea></td>
          </tr>
          <tr><td>&nbsp;</td></tr>
          <tr>
            <td>Difficult&eacute;s rencontr&eacute;es :</td>
            <td><span class="xls_reference">J</span><textarea>> Il arrive que les d&eacute;l&eacute;gu&eacute;es ne disposent pas de bureaux fixes et sont donc amen&eacute;e &agrave; changer de bureaux en fonction des disponibilit&eacute;s du jour 
> Rendez-vous non honor&eacute; par les patients qui freine l'activit&eacute; des infirmi&egrave;res</textarea></td>
          </tr>
          <tr><td>&nbsp;</td></tr>
          <tr>
            <td>Actions am&eacute;lioration men&eacute;es ou envisag&eacute;es :</td>
            <td><textarea></textarea></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table cellpadding="2">
          <tr>
            <td colspan="3">Nombre total et par types de pathologie de patients ayant b&eacute;n&eacute;fici&eacute; d'un :</td>
          </tr>
          <tr>
            <td width="1%">&nbsp;</td>
            <td width="40%">- d&eacute;pistage du diab&egrave;te</td>
            <td><span class="xls_reference">K</span><?php echo count($total_cpt_beneficie["dep_diab"]) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi du diab&egrave;te</td>
            <td><span class="xls_reference">L</span><?php echo count($total_cpt_beneficie["suivi_diab"]) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi des risques Cardio Vasculaires (RCVA)</td>
            <td><span class="xls_reference">M</span><?php echo (count($total_cpt_beneficie['rcva']) + count($total_cpt_beneficie['automesure'])) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi de patients &agrave; risque tabagiques BPCO</td>
            <td><span class="xls_reference">N</span><?php echo (count($total_cpt_beneficie['bpco']) + count($total_cpt_beneficie['sevrage_tabac'])) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- rep&eacute;rage des troubles cognitifs</td>
            <td><span class="xls_reference">O</span><?php echo count($total_cpt_beneficie['cognitif']) ?></td>
          </tr>
          <tr><td colspan="3">&nbsp;</td></tr>
          <tr>
            <td colspan="3">Nombre total et par type de pathologies de patients inclus dans le protocole :</td>
          </tr>
          <tr>
            <td width="5%">&nbsp;</td>
            <td width="30%">- d&eacute;pistage du diab&egrave;te</td>
            <td><span class="xls_reference">P</span><?php echo count($total_cpt["dep_diab"]) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi du diab&egrave;te</td>
            <td><span class="xls_reference">Q</span><?php echo count($total_cpt["suivi_diab"]) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi des risques Cardio Vasculaires (RCVA)</td>
            <td><span class="xls_reference">R</span><?php echo (count($total_cpt['rcva']) + count($total_cpt['automesure'])) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi de patients &agrave; risque tabagiques BPCO</td>
            <td><span class="xls_reference">S</span><?php echo (count($total_cpt['bpco']) + count($total_cpt['sevrage_tabac'])) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- rep&eacute;rage des troubles cognitifs</td>
            <td><span class="xls_reference">T</span><?php echo count($total_cpt['cognitif']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table cellpadding="5">
          <tr>
            <td colspan="4" class="indicateur_head">INDICATEURS Activit&eacute;</td>
          </tr>
          <tr class="indicateur_tbody">
            <td colspan="2">Libell&eacute;</td>
            <td>R&eacute;sultats</td>
            <td>Commentaires</td>
          </tr>
          <tr>
            <td rowspan="5" width="30%">Taux de patients vus par rapport au nombre de patients &eacute;ligibles</td>
            <td>D&eacute;pistage Diab&egrave;te</td>
            <td><span class="xls_reference">U</span><?php echo ($periode_cpt_depistage_diab / 0) * 100 ?>%</td>
            <td rowspan="5"><span class="xls_reference">Z</span><textarea>Bien que variable suivant l'historique des pratiques du cabinet, les patients diab&eacute;tiques et &agrave; risque cardiovasculaire commencent &agrave; &ecirc;tre vus au d&eacute;marrage, suivi des patients &eacute;ligibles aux protocoles  BPCO et troubles cognitifs</textarea></td>
          </tr>
          <tr>
            <td>Diab&egrave;te</td>
            <td><span class="xls_reference">V</span><?php echo ($periode_cpt_suiv_diab / 0) * 100 ?>%</td>
          </tr>
          <tr>
            <td>RCVA</td>
            <td><span class="xls_reference">W</span><?php echo round(($periode_cpt_rcva / $cabinet['total_HTA']) * 100, 2) ?>%</td>
          </tr>
          <tr>
            <td>BPCO</td>
            <td><span class="xls_reference">X</span><?php echo round(($periode_cpt_bpco / (0.25 * 0.72 * $cpt_patient)) * 100, 2) ?>%</td>
          </tr>
          <tr>
            <td>Troubles cognitifs</td>
            <td><span class="xls_reference">Y</span><?php echo round(($periode_cpt_cognitif / $cabinet['total_cogni']) * 100, 2) ?>%</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table cellpadding="5">
          <tr>
            <td colspan="5" class="indicateur_head">INDICATEURS Gestion des Risques</td>
          </tr>
          <tr class="indicateur_tbody">
            <td width="20%">Libell&eacute;</td>
            <td width="5%">R&eacute;sultats</td>
            <td width="25%">Analyse des causes</td>
            <td width="25%">Actions d'am&eacute;lioration men&eacute;e ou en cours</td>
            <td width="25%">Commentaires</td>
          </tr>
          <tr>
            <td>Taux global d'&eacute;v&eacute;nements ind&eacute;sirables</td>
            <td><input type="text"></td>
            <td rowspan="3" colspan="3"><span class="xls_reference">AA</span><textarea>Il n'y a pas eu d'&eacute;v&eacute;nements ind&eacute;sirables li&eacute;s au protocole de coop&eacute;ration.</textarea></td>
            <!-- <td><textarea></textarea></td>
            <td><textarea></textarea></td> -->
          </tr>
          <tr>
            <td>Taux par type d'&eacute;v&eacute;nements ind&eacute;sirables</td>
            <td><input type="text"></td>
            <!-- <td><textarea></textarea></td>
            <td><textarea></textarea></td>
            <td><textarea></textarea></td> -->
          </tr>
          <tr>
            <td>Taux global d'&eacute;v&eacute;nements ind&eacute;sirables graves</td>
            <td><input type="text"></td>
            <!-- <td><textarea></textarea></td>
            <td><textarea></textarea></td>
            <td><textarea></textarea></td> -->
          </tr>
        </table>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table cellpadding="5">
          <tr>
            <td colspan="5" class="indicateur_head">INDICATEURS Satisfaction</td>
          </tr>
          <tr class="indicateur_tbody">
            <td width="50%">Libell&eacute;</td>
            <td width="5%">R&eacute;sultats</td>
            <td>Commentaires</td>
          </tr>
          <tr>
            <td>Taux satisfaction patients ayant accept&eacute; de rentrer dans le Protocole </td>
            <td><input type="text"></td>
            <td><span class="xls_reference">AB</span><textarea>Les enqu&ecirc;tes de satisfaction patient montrent une tr&egrave;s bonne adh&eacute;sion aux &eacute;changes avec les d&eacute;l&eacute;gu&eacute;es avec une notation moyenne de 18.2/20 des 13 assertions jontes en annexe     </textarea></td>
          </tr>
          <tr>
            <td>Taux satifaction des d&eacute;l&eacute;gants</td>
            <td><input type="text"></td>
            <td><span class="xls_reference">AC</span><textarea>> Sur l'ensemble de m&eacute;decins adh&eacute;rent, moins d'une dizaine de cas ont donn&eacute; lieu &agrave; un arr&ecirc;t ou un changement de d&eacute;l&eacute;gu&eacute;e 
> Le % de MG ayant arr&ecirc;t&eacute; Asal&eacute;e depuis qu'il existe des statistiques est de l'ordre de 6,5% (13/198)
> La formalisation des r&eacute;unions de concertation d&eacute;l&eacute;guant/d&eacute;l&eacute;gu&eacute; permet de r&eacute;soudre la quasi-totalit&eacute; des difficult&eacute;s qui pourrait apparaitre.</textarea></td>
          </tr>
          <tr>
            <td>Taux satisfaction des d&eacute;l&eacute;gu&eacute;s</td>
            <td><input type="text"></td>
            <td><span class="xls_reference">AD</span><textarea>> De m&ecirc;me, sur l'ensemble des infirmi&egrave;res, moins d'une dizaine de cas ont donn&eacute; lieu &agrave; des arr&ecirc;ts ou des changements de cabinet     </textarea></td>
          </tr>
          <tr>
            <td>Taux de refus des patients d'entrer dans le protocole</td>
            <td><input type="text"></td>
            <td><span class="xls_reference">AE</span><textarea>> Il n'y a pas de refus "franc" identifi&eacute;, en revanche, il peut arriver qu'un patient n'honore pas un RV      </textarea></td>
          </tr>
          <tr>
            <td>Taux de sortie du protocole des patients ayant accept&eacute; de rentrer dans le PC</td>
            <td><input type="text"></td>
            <td><span class="xls_reference">AF</span><textarea>> Il n'y a pas de sortie "franche" identifi&eacute;, en revanche, il peut arriver qu'un patient n'honore pas un RV     </textarea></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table cellpadding="5">
          <tr>
            <td colspan="5" class="indicateur_head">INDICATEURS Organisation</td>
          </tr>
          <tr class="indicateur_tbody">
            <td width="50%">Libell&eacute;</td>
            <td width="5%">R&eacute;sultats</td>
            <td>Commentaires</td>
          </tr>
          <tr>
            <td>Nb de PS d&eacute;l&eacute;gu&eacute;s/d&eacute;l&eacute;gants ayant adh&eacute;r&eacute; au PC sur nb potentiellement concern&eacute;s dans le cabinet m&eacute;dical </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>temps gagn&eacute; par le d&eacute;l&eacute;gant dans le cadre du PC ( en nombre de cs, et en temps absolu)  </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
        </table>
      </td>
    </tr>

    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table cellpadding="5">
          <tr>
            <td colspan="4" class="indicateur_head">INDICATEURS Qualit&eacute; et S&eacute;curit&eacute;</td>
          </tr>
          <tr class="indicateur_tbody">
            <td colspan="2">Libell&eacute;</td>
            <td>R&eacute;sultats</td>
            <td>Commentaires</td>
          </tr>
          <tr>
            <td rowspan="3" width="30%">Taux de patients pr&eacute;sentant une HbA1C par cat&eacute;gorie</td>
            <td>< &agrave; 6,5</td>
            <td><span class="xls_reference">AG</span><?php echo round(($cpt_hba1c_inf / count($temp_retenu)) * 100) ?> %</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>entre 6,5 et 8</td>
            <td><span class="xls_reference">AH</span><?php echo round(($cpt_hba1c_middle / count($temp_retenu)) * 100) ?> %</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>> &agrave; 8</td>
            <td><span class="xls_reference">AI</span><?php echo round(($cpt_hba1c_sup / count($temp_retenu)) * 100) ?> %</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients pr&eacute;sentant une HbA1C normalis&eacute;e &agrave; 1 an du d&eacute;but du suivi du d&eacute;l&eacute;gu&eacute;</td>
            <td>-</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td rowspan="4" width="30%">Taux de patients pr&eacute;sentant une tension par cat&eacute;gorie / taux de patients pr&eacute;sentant une tension < &agrave; 140/90 dans les patients RCVA vus</td>
            <td>taux patient >14/9 avant 1ere cs et <14/9 apr&egrave;s 1ere cs</td>
            <td><span class="xls_reference">AJ</span><?php echo round($periode_cpt_tension['1_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>taux  patient >14/9 avant 1ere cs et <14/9 apr&egrave;s 2 cs</td>
            <td><span class="xls_reference">AK</span><?php echo round($periode_cpt_tension['2_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>taux  patient >14/9 avant 1ere cs et <14/9 apr&egrave;s 3 cs</td>
            <td><span class="xls_reference">AL</span><?php echo round($periode_cpt_tension['3_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>taux  patient >14/9 avant 1ere cs et <14/9 apr&egrave;s 4 cs</td>
            <td><span class="xls_reference">AM</span><?php echo round($periode_cpt_tension['4_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>

          <tr>
            <td colspan="2">Taux de patients pr&eacute;sentant un RCV absolu par cat&eacute;gorie selon &eacute;quation de Framingham/ nombre de patients pour lequel le risque peut &ecirc;tre calcul&eacute;</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients ayant arr&ecirc;t&eacute; de fumer</td>
            <td><span class="xls_reference">AN</span><?php echo $nb_arret_tabac['nb_arret_tabac'] ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Suivi VEMS/CV   borne haute et borne basse &agrave; 3 mois/ nb de spirom&eacute;trie par patient unique </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients d&eacute;pist&eacute;s positifs pour les troubles cognitifs  </td>
            <td>-</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients adress&eacute;s en consultation g&eacute;rontologique  </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients pour lesquels le diagnostic de troubles cognitifs est non confirm&eacute; en cs g&eacute;rontologique  </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Nombre de r&eacute;union d'analyse des EI par an </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux d'alertes du d&eacute;l&eacute;gu&eacute; au d&eacute;l&eacute;gant pertinentes (&eacute;valu&eacute;e par le d&eacute;l&eacute;gant) </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<?php endif ?> 
</body>
</html>