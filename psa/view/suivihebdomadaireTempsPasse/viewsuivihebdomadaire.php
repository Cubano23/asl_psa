<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>

<?php 
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;
?>

<?php global $account; ?>
<?php global $param; ?>
<?php global $saisieInfirmiere; ?>
<?php global $SuiviHebdomadaireTempsPasse; ?>
<?php global $evaluationInfirmier;?>
<?php global $evaluationInfirmier;?>
<?php global $SuiviReunionMedecin;?>

<?php



//  CONSULTATIONS  les valeurs sont récuperées de la table evaluation_infirmier en fonction du cabinet et de la date
    #echo '<pre>';var_dump($saisieInfirmiere);echo '</pre>';
    #if($saisieInfirmiere!=""){

    // Vérification pour voir si ne somme pas dans une saisie vide
    if($saisieInfirmiere!=""){
        $total = count($saisieInfirmiere);
    }
    else{
        $total = 0;
    }


    $countTypeConsultation = $TpsConsultation = $consultDomicile = $consultTel = $consultCol = $tpsConsultDomicile = $tpsConsultTel = $tpsConsultCol =array();
    $tpsConsultationRetenu = 0;
    // NB le temp de consultation retenu ($tpsConsultationRetenu) permet de distinguer 
    // le temps de consultations collectives, ainsi pour certains calcules on se base 
    // pas sur le temps passé total, mais sur le temps de consultation retenu (exple temps non attribué)

    $uuid_checked = array(); // empilement des consultations collectives déjà enregistrées.

    $dureeInfConsultCol = array(); // total des durées de consultation collectives au vue de l'infirmière
    $dureeInfAutresConsul = array(); // total des durées des cosultaion de type autre au vue de l'infirmière
    $compteur = 0;

    foreach ($saisieInfirmiere as $saisie) {

      if($saisie['type_consultation']!='' && strpos($saisie['type_consultation'], ',') === false){
        $countTypeConsultation[$saisie['type_consultation']] = $countTypeConsultation[$saisie['type_consultation']]+1;
        $TpsConsultation[$saisie['type_consultation']] = $TpsConsultation[$saisie['type_consultation']]+$saisie['duree'];
      }
      else {
        $countTypeConsultation['autres'] = $countTypeConsultation['autres']+1;
        $TpsConsultation['autres'] = $TpsConsultation['autres']+$saisie['duree'];
      }

      if($saisie['consult_domicile']!='' && $saisie['consult_domicile']!='0'){
        $consultDomicile[$saisie['consult_domicile']] = $consultDomicile[$saisie['consult_domicile']]+1;
        $tpsConsultDomicile[$saisie['consult_domicile']] = $tpsConsultDomicile[$saisie['consult_domicile']]+$saisie['duree'];
      }

      elseif($saisie['consult_tel']!='' && $saisie['consult_tel']!='0'){
        $consultTel[$saisie['consult_tel']] = $consultTel[$saisie['consult_tel']]+1;
        $tpsConsultTel[$saisie['consult_tel']] = $tpsConsultTel[$saisie['consult_tel']]+$saisie['duree'];
      }

      elseif($saisie['consult_collective']!='' && $saisie['consult_collective']!='0'){
        $consultCol[$saisie['consult_collective']] = $consultCol[$saisie['consult_collective']]+1;

        // on dédoublonne les consultation collectives, on ne les comptabilise qu'une seule fois grace au uuid_collectif en base
        if(!in_array($saisie['uuid_collectif'],$uuid_checked) || $saisie['uuid_collectif']!='NULL'){
          array_push($uuid_checked,$saisie['uuid_collectif']);

          $dureeInfConsultCol[$saisie['uuid_collectif']] = $saisie['duree'];

          $tpsConsultCol[$saisie['consult_collective']] = $tpsConsultCol[$saisie['consult_collective']]+$saisie['duree'];
        }

      }

      // Comptabilisation des durées de consultation qui ne sont ni de domicile ni téléphoniques ni collectives
      //if($saisie['consult_domicile'] == null && $saisie['consult_tel'] == null && $saisie['consult_collective'] == null){
      else{
          $dureeInfAutresConsul[$compteur] = $saisie['duree'];
          $compteur++;
      }


      if(!in_array($saisie['uuid_collectif'],$uuid_retenu_checked)){
        array_push($uuid_retenu_checked,$saisie['uuid_collectif']);
        // et on ajoute la durée au temps de consltationretenu
        $tpsConsultationRetenu = $tpsConsultationRetenu+$saisie['duree'];
      }


    }
    $TpsReunionMedecin = array();

#var_dump($SuiviReunionMedecin);

    foreach ($SuiviReunionMedecin as $reunionMedecin){
      $TpsReunionMedecin[$reunionMedecin['reunionmedecin']] = $TpsReunionMedecin[$reunionMedecin['reunionmedecin']]+$reunionMedecin['duree'];
    }

    // PREPARATION BILAN DES CONSULTATIONS est calculé en appliquant des taux forfaitaires par rapport au temps de consultation.
    //                                      Ces taux sont différenciés pour les différents protocoles avec les coefficients suivant :
                          //Suivi diabète à> taux 0,25
                          //Dépistage du Diabète type 2 à > taux 0,25
                          //Suivi du patient RCVA à > taux 0,25
                          //Repérage BPCO tabagique à > taux 0,2
                          //Repérage trouble cognitif à > taux 0,1
                          //Hémoccult à > taux 0
                          //Dépistage cancer du sein à > taux 0
                          //Dépistage cancer du colon à > taux 0
                          //Dépistage cancer de l'utérus à > taux 0
                          //Dépistage cancer du sein à > taux 0
                          //Autres0,2
                          //Automesure -> 0,10
                          // sevrage_tabagique => 0,2

    $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) +
    ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['autres']*0.2) + ($TpsConsultation['automesure']*0.1) + ($TpsConsultation['sevrage_tabac']*0.2));
?>
 <table border='1'  cellpadding='0'>
  <tr>
     <td><b>Cabinet: </b></td>
     <td><?php echo($account->cabinet) ;?></td>
   </tr>
  <tr>
     <td><b>Semaine commençant le Lundi: </b></td>
     <td><?php echo($SuiviHebdomadaireTempsPasse->date); ?> </td>
   </tr>
</table>
<br>
<p>Le suivi hebdomadaire du temps passé vous permet de voir la répartition en minutes du temps passé par types d'activités.<br/><br/>

Ce suivi calcule automatiquement la durée de chaque type de consultation à partir des durées de consultations que vous avez déclarées dans les différents comptes-rendus de consultation. Est également calculé automatiquement un temps forfaitaire de préparation et de bilan des consultations.<br/><br/>

Il vous appartient de compléter les autres rubriques concernant par exemple la gestion des dossiers patients, l'auto formation, etc.</p>
<br>
<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
  <?php hiddenControler("SuiviHebdomadaireTempsPasseControler"); ?>
  <?php hiddenAction(""); ?>
  <?php hiddenParam1(""); ?>
  <?php hidden("","SuiviHebdomadaireTempsPasse:cabinet"); ?>
  <?php hidden("","SuiviHebdomadaireTempsPasse:date");?>

  <?php 
  $infirmieres = GetLoginsByCab($_SESSION['cabinet'], $status);
  $tempsInfirmieres = SuiviHebdomadaireTempsPasseInfirmieres::getRecordsByCabinet($account->cabinet,$SuiviHebdomadaireTempsPasse->date);
  

  $tpsInf = array();
  foreach($tempsInfirmieres as $value){
    $tpsInf[$value['infirmiere']] = $value['duree'];
  }
  #var_dump($SuiviHebdomadaireTempsPasse);

  ?>
  <table border="1" cellpadding='3'>
  <tr>
    <td><b>Infirmière(s)</b></td>
    <td><b>Temps passé en heures</b></td>
  </tr>

  <?php 
    $infirmieres = GetLoginsByCab($_SESSION['cabinet'], $status);

    foreach($infirmieres as $key => $inf){ ?>
     <tr>
      <td><?php echo utf8_decode($inf['prenom'].' '.$inf['nom']); ?></td>
      <td align="center">
        <?php echo $tpsInf[$inf['login']];?>
        
      </select>
      </td>
    </tr>
  <?php } ?>


   <tr>
      <td><b>Nombre d'heures' travaillées <br/>dans la semaine dans ce cabinet</b></td>
      <td align="center">
        <input type="hidden" name="tps_passe_cabinet" id="tps_passe_cabinet" value="<?php echo ($SuiviHebdomadaireTempsPasse->tps_passe_cabinet / 60);?>" disabled><div id="totalTempsPasseCabinet" style="font-size:18px"><?php echo ($SuiviHebdomadaireTempsPasse->tps_passe_cabinet / 60);?>h</div>

       
      </td>
    </tr>
</table>

<table border="1" cellpadding='3'>
    <tr>
      <th colspan="2" align="center">Tâche</th>
      <th>Nombre</th>
      <th>Durée<br>
        (minutes)</th>
    </tr>

    <tr>
      <td rowspan="14" width="185"><b>Consultations<br />(Temps d'exposition des patients aux professionnels de santé)</b></td>

        <td width="410">Suivi diabète</td>
         <?php if($countTypeConsultation['suivi_diab']){?>
          <td align="right"><?php echo $countTypeConsultation['suivi_diab'];?></td>
          <td align="right"><?php echo $TpsConsultation['suivi_diab'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>

    <tr>
        <td>Dépistage du Diabète type 2</td>
        <?php if($countTypeConsultation['dep_diab']){?>
          <td align="right"><?php echo $countTypeConsultation['dep_diab'];?></td>
          <td align="right"><?php echo $TpsConsultation['dep_diab'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php }?>
    </tr>

    <tr>
        <td>Suivi du patient RCVA</td>
        <?php if($countTypeConsultation['rcva']){?>
          <td align="right"><?php echo $countTypeConsultation['rcva'];?></td>
          <td align="right"><?php echo $TpsConsultation['rcva'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php }?>
    </tr>

    <tr>
        <td>Repérage BPCO tabagique</td>
        <?php if($countTypeConsultation['bpco']){?>
          <td align="right"><?php echo $countTypeConsultation['bpco'];?></td>
          <td align="right"><?php echo $TpsConsultation['bpco'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>

  <tr>
      <td>Sevrage tabagique</td>
      <?php if($countTypeConsultation['sevrage_tabac']){?>
        <td align="right"><?php echo $countTypeConsultation['sevrage_tabac'];?></td>
        <td align="right"><?php echo $TpsConsultation['sevrage_tabac'];?></td>
      <?php } else { ?>
        <td></td>
        <td></td>
     <?php } ?>
  </tr>

    <tr>

        <td>Repérage trouble cognitif</td>
        <?php if($countTypeConsultation['cognitif']){?>
          <td align="right"><?php echo $countTypeConsultation['cognitif'];?></td>
          <td align="right"><?php echo $TpsConsultation['cognitif'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>
    <tr>
        <td>Hémoccult</td>
        <?php if($countTypeConsultation['hemocult']){?>
          <td align="right"><?php echo $countTypeConsultation['hemocult'];?></td>
          <td align="right"><?php echo $TpsConsultation['hemocult'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>
    <tr>
        <td>Dépistage Cancer du sein</td>
        <?php if( isset($countTypeConsultation['sein']) && $countTypeConsultation['sein']!='' ){?>
          <td align="right"><?php echo $countTypeConsultation['sein'];?></td>
          <td align="right"><?php echo $TpsConsultation['sein'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>
        <tr>
        <td>Dépistage Cancer du colon</td>
          <?php if( isset($countTypeConsultation['colon']) && $countTypeConsultation['colon']!='' ){?>
          <td align="right"><?php echo $countTypeConsultation['colon'];?></td>
          <td align="right"><?php echo $TpsConsultation['colon'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>
    <tr>
        <td>Dépistage Cancer de l'utérus</td>
         <?php if( isset($countTypeConsultation['uterus']) && $countTypeConsultation['uterus']!='' ){?>
          <td align="right"><?php echo $countTypeConsultation['uterus'];?></td>
          <td align="right"><?php echo $TpsConsultation['uterus'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>
    <tr>
        <td>Surpoids</td>
        <?php if( isset($countTypeConsultation['surpoids']) && $countTypeConsultation['surpoids']!='' ){?>
            <td align="right"><?php echo $countTypeConsultation['surpoids'];?></td>
            <td align="right"><?php echo $TpsConsultation['surpoids'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>
    <tr>
        <td>Automesure</td>
         <?php if( isset($countTypeConsultation['automesure']) && $countTypeConsultation['automesure']!='' ){?>
          <td align="right"><?php echo $countTypeConsultation['automesure'];?></td>
          <td align="right"><?php echo $TpsConsultation['automesure'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
       <?php } ?>
    </tr>
    <tr>
        <td>Autres</td>
         <?php if( isset($countTypeConsultation['autres']) && $countTypeConsultation['autres']!='' ){?>
          <td align="right"><?php echo $countTypeConsultation['autres'];?></td>
          <td align="right"><?php echo $TpsConsultation['autres'];?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
          <?php } ?>
    </tr>
    <tr>
        <td>Total</td>
          <?php
          #if($countTypeConsultation){ ?>
          <td align="right" id="total_nb_consult"> <?php echo $total;?></td>
          <td align="right" id="total_tps_consult"> <?php echo array_sum($TpsConsultation);?></td>
          <?php# } else { ?>
          <!-- <td></td>
          <td></td> -->
          <?php# } ?>
    </tr>
    <tr>
      <td rowspan="5" width="185"><b>Temps infirmière passé en consultations</b></td>
      <td>Total du temps infirmière passé en consultations<br>(comptabilise le temps passé réel en prenant compte des consultations collectives)</td>
        <td align="right"><?php echo $total;?></td>
        <td align="right" id="total_tps_consult_retenu"> <?php echo (array_sum($tpsConsultDomicile) + array_sum($tpsConsultTel) + array_sum($dureeInfConsultCol) + array_sum($dureeInfAutresConsul)); ?></td>

    </tr>
    <tr>
      <td>dont consultations &agrave; domiciles</td>
        <?php


    if($consultDomicile!='0' && $consultDomicile!=NULL ){?>
        <td align="right"><?php echo array_sum($consultDomicile);?></td>
        <td align="right"><?php echo array_sum($tpsConsultDomicile);?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
          <?php } ?> 
      </tr>
    <tr>
      <td>dont consultations téléphoniques</td>
        <?php if($consultTel){?>
        <td align="right"><?php echo array_sum($consultTel);?></td>
        <td align="right"><?php echo array_sum($tpsConsultTel);?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
          <?php } ?>
    </tr>
    <tr>
      <td>dont consultations collectives<!--  <br>(Temps infirmière passé en consultations collectives) --></td>
        <?php if($consultCol){?>
        <td align="right"><?php echo array_sum($consultCol);?></td>
        <td align="right"><?php echo array_sum($dureeInfConsultCol); ?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
          <?php } ?>
    </tr>
    <tr>
        <td>dont autres</td>
        <td align="right"><?php echo ($total - (array_sum($consultDomicile) + array_sum($consultTel) + array_sum($consultCol)));?></td>
        <td align="right"><?php echo array_sum($dureeInfAutresConsul);?></td>
    </tr>

     


<?php #} ?>

     <tr>
      <td colspan=2><b>Préparation/bilan des consultations</b></td>
      <td>&nbsp;</td>
      <td align="right"><?php echo round($tempsPrepaBilanConsultation);?></td>
    </tr>

    <tr>
      <td colspan=2>
          <b>Contribution aux actions de développement d&acute;Asalée : </b><br/>Développement de nouveaux protocoles, communication,...
          <br>
          <?php if ($SuiviHebdomadaireTempsPasse->precision_contribution_dev_asalee != null) echo "<em>(Précision : $SuiviHebdomadaireTempsPasse->precision_contribution_dev_asalee)</em>"?>
      </td>
      <td align="right"><?php #echo round($SuiviHebdomadaireTempsPasse->nb_contact_tel_patient);?></td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->tps_contact_tel_patient);?></td>
    </tr>

    <tr>
      <td colspan="2"><b>Gestion sur dossier patient</b><br/>
      Mise à jour des dossiers ASALEE sur PSA : saisie, intégration<br/>
      Travail sur dossiers médicaux sur le logiciel de gestion du cabinet : alerte, enrichissement de données
      </td>
      <td>&nbsp;</td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->info_asalee);?></td>
    </tr>

    <tr>
      <td colspan=2><b>Auto-formation</b> (recherches internet, lecture d'articles...)</td>
      <td>&nbsp;</td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->autoformation);?></td>
    </tr>
    <tr>
      <td colspan=2><b>Formation suivie</b></td>
      <td>&nbsp;</td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->formation);?></td>
    </tr>
    <tr>
      <td colspan=2><b>Encadrement de stagiaires</b></td>
      <td>&nbsp;</td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->stagiaires);?></td>
    </tr>
    <tr>
      <td colspan=2><b>Concertation avec les médecins : Nombre de réunions et temps total passé</b></td>
      <td align="right"><?php if(empty($SuiviReunionMedecin)){echo '0';}else{echo count($SuiviReunionMedecin);}?></td>
      <?php
      // 
      $tempsRC = 0;
      foreach ($SuiviReunionMedecin as $reuM){
        $tempsAdd = count(explode(",",$reuM['infirmiere']))* $reuM['duree'];
        $tempsRC = $tempsRC + $tempsAdd;
      }

      ?>

      <td align="right"><?php echo $tempsRC;?></td>
    </tr>
    <tr>
      <td colspan=2><b>Echanges avec d'autres infirmi&egrave;res : Nombre de réunions et temps total passé</b></td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->nb_reunion_infirmiere);?></td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->tps_reunion_infirmiere);?></td>

    </tr>

    <tr>
      <td colspan=2><b>Autres et/ou Non attribué</b></td>
      <td>&nbsp;</td>
      <td align="right"><?php echo round($SuiviHebdomadaireTempsPasse->non_atribue);?></td>
    </tr>

    <tr>
      <td colspan='3'><b>Total</b><div style="float:right;">équivalence en heures :  &nbsp;&nbsp;<?php echo $SuiviHebdomadaireTempsPasse->tps_passe_cabinet/60 ;?></div></td>
      <td align="right"><b><?php echo $SuiviHebdomadaireTempsPasse->tps_passe_cabinet;?></b></td>
    </tr>

      <td colspan='4'>&nbsp; </td>
    </tr>
    <tr>
      <td colspan='4' align='center'>
          <?php #customSubmitWithAlert("value='Supprimer cette évaluation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette évaluation?"); ?>
          <?php customSubmit("value='Compléter ou modifier cette évaluation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
      </td>
    </tr>


  </table>
</form>

</body></html>


