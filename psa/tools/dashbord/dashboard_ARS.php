<?php




 // set_time_limit(1000);
 // $tspdeb = strtotime(date('Y-m-d H:i:s'));
 // ini_set("memory_limit","512M");
 //  //require_once("../../bean/FicheCabinet.php");
 //  require_once("../../bean/dashboard.php");
 //  require_once("../../bean/SuiviHebdomadaireTempsPasse.php");
 //  //require_once("../../bean/EvaluationInfirmier.php");
 //  require_once("../../bean/SuiviReunionMedecin.php");
  
 //  /* persistence object */
 //  require_once("../../persistence/FicheCabinetMapper.php");
 //  require_once("../../persistence/SuiviHebdomadaireTempsPasseMapper.php");
 //  require_once("../../persistence/EvaluationInfirmierMapper.php");echo "ok5";
 //  require_once("../../persistence/SuiviReunionMedecinMapper.php");

   require_once("../../persistence/ConnectionFactory.php");
 //  //require_once("../../tools/date.php");
 //  require_once("../../bean/beanparser/htmltags.php");
 //  //require_once("../../view/jsgenerator/jsgenerator.php");
 //  require_once("../../view/common/vars.php");


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

if(isset($_POST['region'])) {
  $region = $_POST['region'];
  $step = 2;
  $q = "SELECT * FROM account WHERE region='".addslashes(strip_tags($_POST['region']))."'";
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

<table style="width:350px">
  <form method="POST" action="dashboard_ARS_process.php">
    <tr>
      <td>date d&eacute;but:</td>
      <td>&nbsp;</td>
      <td><?php echo date('d/m/Y', strtotime($date_start)) ?></td>
    </tr>
    <tr>
      <td>date fin:</td>
      <td>&nbsp;</td>
      <td><?php echo date('d/m/Y', strtotime($date_end)) ?></td>
    </tr>
    <tr>
      <td>R&eacute;gion:</td>
      <td>&nbsp;</td>
      <td>
          <select name="region">
          <?php while($tab = mysql_fetch_array($liste_region)): ?>  
            <option value="<?php echo ($tab[0]) ?>" <?php echo ($region == ($tab[0])) ? 'selected' : "" ?>><?php echo ($tab[0]) ?></option>
          <?php endwhile ?>
          </select>
      </td>
    </tr>
    <?php if($step >= 2): ?>
      <!-- <tr>
        <td>Cabinet</td>
        <td>&nbsp;</td>
        <td>
          <select name="cabinet">
          <?php //while($tab = mysql_fetch_array($liste_cabinet)): ?>  
            <option value="<?php //echo $tab[0] ?>" <?php echo ($cabinet == $tab[0]) ? 'selected' : "" ?>><?php echo $tab[0] ?></option>
          <?php //endwhile ?>
          </select>
        </td>
      </tr> -->
    <?php endif ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="ok"></td>
    </tr>
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
    
  ?>
  <br />
  <table style="width:90%; border:solid 1px #666;" cellpadding="10">
    <tr>
      <td align="center">ASALEE : travail en &eacute;quipe infirmi&egrave;r(e)s d&eacute;l&eacute;gu&eacute;(e)s &agrave; la sant&eacute; populationnelle & m&eacute;decins g&eacute;n&eacute;ralistes pour l'am&eacute;lioration de la qualit&eacute; des soins et l'allocation optimis&eacute;e de la disponibilit&eacute; des "professionnels de sant&eacute;" sur le territoire concern&eacute;</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td align="right">PC N&deg; 54-0000000008</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td align="center"><h3>&Eacute;valuation du <?php echo date('d/m/Y', strtotime($date_start)) ?> au <?php echo date('d/m/Y', strtotime($date_end)) ?></h3></td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <table>
          <tr valign="top">
            <td width="70%">
              <b><u>Equipe</u></b> : <?php echo $cabinet['nom_complet'] ?>
              <br />
              Docteur <?php echo utf8_encode($strMedecins) ?>, <?php echo $listeInf ?>
              <br />
              <b><u>Ville</u></b> : <?php echo $cabinet['ville'] ?>
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
            <td><textarea></textarea></td>
          </tr>
          <tr><td>&nbsp;</td></tr>
          <tr>
            <td>Difficult&eacute;s rencontr&eacute;es :</td>
            <td><textarea></textarea></td>
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
            <td><?php echo $periode_cpt_depistage_diab ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi du diab&egrave;te</td>
            <td><?php echo $periode_cpt_suiv_diab ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi des risques Cardio Vasculaires (RCVA)</td>
            <td><?php echo $periode_cpt_rcva ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi de patients &agrave; risque tabagiques BPCO</td>
            <td><?php echo ($periode_cpt_bpco + $periode_cpt_tabac) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- rep&eacute;rage des troubles cognitifs</td>
            <td><?php echo $periode_cpt_cognitif ?></td>
          </tr>
          <tr><td colspan="3">&nbsp;</td></tr>
          <tr>
            <td colspan="3">Nombre total et par type de pathologies de patients inclus dans le protocole :</td>
          </tr>
          <tr>
            <td width="5%">&nbsp;</td>
            <td width="30%">- d&eacute;pistage du diab&egrave;te</td>
            <td><?php echo count($total_cpt["dep_diab"]) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi du diab&egrave;te</td>
            <td><?php echo count($total_cpt["suivi_diab"]) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi des risques Cardio Vasculaires (RCVA)</td>
            <td><?php echo (count($total_cpt['rcva']) + count($total_cpt['automesure'])) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- suivi de patients &agrave; risque tabagiques BPCO</td>
            <td><?php echo (count($total_cpt['bpco']) + count($total_cpt['sevrage_tabac'])) ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>- rep&eacute;rage des troubles cognitifs</td>
            <td><?php echo count($total_cpt['cognitif']) ?></td>
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
            <td><?php echo ($periode_cpt_depistage_diab / 0) * 100 ?>%</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Diab&egrave;te</td>
            <td><?php echo ($periode_cpt_suiv_diab / 0) * 100 ?>%</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>RCVA</td>
            <td><?php echo round(($periode_cpt_rcva / $cabinet['total_HTA']) * 100, 2) ?>%</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Troubles cognitifs</td>
            <td><?php echo round(($periode_cpt_cognitif / $cabinet['total_cogni']) * 100, 2) ?>%</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>BPCO</td>
            <td><?php echo round(($periode_cpt_bpco / (0.25 * 0.72 * $cpt_patient)) * 100, 2) ?>%</td>
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
            <td colspan="5" class="indicateur_head">INDICATEURS Gestion des Risques</td>
          </tr>
          <tr class="indicateur_tbody">
            <td width="20%">Libell&eacute;</td>
            <td width="5%">R&eacute;sultats</td>
            <td width="25%">Analyse des causes</td>
            <td width="25%">Actions d’am&eacute;lioration men&eacute;e ou en cours</td>
            <td width="25%">Commentaires</td>
          </tr>
          <tr>
            <td>Taux global d'&eacute;v&eacute;nements ind&eacute;sirables</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
            <td><textarea></textarea></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Taux par type d'&eacute;v&eacute;nements ind&eacute;sirables</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
            <td><textarea></textarea></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Taux global d'&eacute;v&eacute;nements ind&eacute;sirables graves</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
            <td><textarea></textarea></td>
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
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Taux satifaction des d&eacute;l&eacute;gants</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Taux satisfaction des d&eacute;l&eacute;gu&eacute;s</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Taux de refus des patients d'entrer dans le protocole</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>Taux de sortie du protocole des patients ayant accept&eacute; de rentrer dans le PC</td>
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
            <td colspan="4" class="indicateur_head">INDICATEURS Qualité et Sécurité</td>
          </tr>
          <tr class="indicateur_tbody">
            <td colspan="2">Libell&eacute;</td>
            <td>R&eacute;sultats</td>
            <td>Commentaires</td>
          </tr>
          <tr>
            <td rowspan="3" width="30%">Taux de patients présentant une HbA1C par catégorie</td>
            <td>< à 6,5</td>
            <td><?php echo round(($cpt_hba1c_inf / count($temp_retenu)) * 100) ?> %</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>entre 6,5 et 8</td>
            <td><?php echo round(($cpt_hba1c_middle / count($temp_retenu)) * 100) ?> %</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>> à 8</td>
            <td><?php echo round(($cpt_hba1c_sup / count($temp_retenu)) * 100) ?> %</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients présentant une HbA1C normalisée à 1 an du début du suivi du délégué</td>
            <td>en attente ??</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td rowspan="4" width="30%">Taux de patients présentant une tension par catégorie / taux de patients présentant une tension < à 140/90 dans les patients RCVA vus</td>
            <td>taux patient >14/9 avant 1ere cs et <14/9 après 1ere cs</td>
            <td><?php echo round($periode_cpt_tension['1_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>taux  patient >14/9 avant 1ere cs et <14/9 après 2 cs</td>
            <td><?php echo round($periode_cpt_tension['2_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>taux  patient >14/9 avant 1ere cs et <14/9 après 3 cs</td>
            <td><?php echo round($periode_cpt_tension['3_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td>taux  patient >14/9 avant 1ere cs et <14/9 après 4 cs</td>
            <td><?php echo round($periode_cpt_tension['4_consult'] / 12) . ' %' ?></td>
            <td><textarea></textarea></td>
          </tr>

          <tr>
            <td colspan="2">Taux de patients présentant un RCV absolu par catégorie selon équation de Framingham/ nombre de patients pour lequel le risque peut être calculé</td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients ayant arrêté de fumer</td>
            <td><?php echo $nb_arret_tabac['nb_arret_tabac'] ?></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Suivi VEMS/CV   borne haute et borne basse à 3 mois/ nb de spirométrie par patient unique </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients dépistés positifs pour les troubles cognitifs  </td>
            <td>en attente ??</td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients adressés en consultation gérontologique  </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux de patients pour lesquels le diagnostic de troubles cognitifs est non confirmé en cs gérontologique  </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Nombre de réunion d'analyse des EI par an </td>
            <td><input type="text"></td>
            <td><textarea></textarea></td>
          </tr>
          <tr>
            <td colspan="2">Taux d'alertes du délégué au délégant pertinentes (évaluée par le délégant) </td>
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