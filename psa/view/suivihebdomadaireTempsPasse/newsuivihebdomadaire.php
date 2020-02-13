<?php require_once("bean/beanparser/htmltags.php");?>
<?php require_once("view/common/vars.php");?>
<?php require_once("view/jsgenerator/jsgenerator.php");?>

<?php 
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;
?>


<?php global $account; ?>
<?php global $param; ?>
<?php global $saisieInfirmiere; ?>
<?php global $SuiviHebdomadaireTempsPasse; ?>
<?php global $evaluationInfirmier; ?>
<?php global $SuiviReunionMedecin; ?>
<?php
//  CONSULTATIONS  les valeurs sont r&eacute;cuper&eacute;es de la table evaluation_infirmier en fonction du cabinet et de la date
    if($saisieInfirmiere!=""){
    $total = count($saisieInfirmiere);
    }
    else{
    $total = 0;
    }

    $countTypeConsultation = $TpsConsultation = $consultDomicile = $consultTel = $consultCol = $tpsConsultDomicile = $tpsConsultTel = $tpsConsultCol = array();
    $tpsConsultationRetenu = 0;
    // NB le temp de consultation retenu ($tpsConsultationRetenu) permet de distinguer 
    // le temps de consultations collectives, ainsi pour certains calcules on se base 
    // pas sur le temps passé total, mais sur le temps de consultation retenu (exple temps non attribué)

    $uuid_checked = array(); // empilement des consultations collectives déjà comptabilises. //$uuid_checked = $uuid_retenu_checked = array();

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
          if(!in_array($saisie['uuid_collectif'],$uuid_checked) || $saisie['uuid_collectif']!='NULL'){ //if(!in_array($saisie['uuid_collectif'],$uuid_checked)){
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

      if(!in_array($saisie['uuid_collectif'],$uuid_retenu_checked)){ // || $saisie['uuid_collectif']==''
        array_push($uuid_retenu_checked,$saisie['uuid_collectif']);
        // et on ajoute la durée au temps de consltationretenu
        $tpsConsultationRetenu = $tpsConsultationRetenu+$saisie['duree'];
      }


    }

    $TpsReunionMedecin = array();

#var_dump($uuid_retenu_checked);


    foreach ($SuiviReunionMedecin as $reunionMedecin){
      $TpsReunionMedecin[$reunionMedecin['reunionmedecin']] = $TpsReunionMedecin[$reunionMedecin['reunionmedecin']]+$reunionMedecin['duree'];
    }


    // PREPARATION BILAN DES CONSULTATIONS est calcul&eacute; en appliquant des taux forfaitaires par rapport au temps de consultation.
    //                                      Ces taux sont diff&eacute;renci&eacute;s pour les diff&eacute;rents protocoles avec les coefficients suivant :
                          //Suivi diabète à > taux 0,25
                          //D&eacute;pistage du Diabète type 2 à > taux 0,25
                          //Suivi du patient RCVA à > taux 0,25
                          //Rep&eacute;rage BPCO tabagique à > taux 0,2
                          //Rep&eacute;rage trouble cognitif à  > taux 0,1
                          //H&eacute;moccult à >  taux 0
                          //D&eacute;pistage cancer du sein à > taux 0
                          //D&eacute;pistage cancer du colon à > taux 0
                          //D&eacute;pistage cancer de l'ut&eacute;rus à > taux 0
                          //D&eacute;pistage cancer du sein à > taux 0
                          //Autres0,2
                          //Automesure -> 0,10
                          // sevrage_tabagique => 0,2

    $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) +
    ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['autres']*0.2) + ($TpsConsultation['automesure']*0.1) + ($TpsConsultation['sevrage_tabac']*0.2));
?>
<pre><?php if(isset($_GET['debug'])) var_dump($SuiviHebdomadaireTempsPasse) ?></pre>
 <table border='1'  cellpadding='0'>
  <tr>
     <td><b>Cabinet: </b></td>
     <td><?php echo($account->cabinet) ?></td>
  </tr>
  <tr>
     <td><b>Semaine commençant le Lundi: </b></td>
     <td><?php echo($SuiviHebdomadaireTempsPasse->date); ?> </td>
   </tr>
</table>
<br>
<p>1- La première information à rentrer est celle du nombre d'heures travaillées dans ce cabinet pour la semaine considérée.<br>
Ce temps sera immédiatement converti en minutes dans la case "Total" en bas du formulaire.<br><br>

Si vous avez été en congé, de quelque nature qu'il soit, ne pas compter ces heures de congés dans cette déclaration.<br><br>

2- Remplir en minutes les temps passés aux différentes activités qui ne sont pas pré-remplies.<br><br>

La différence entre le temps total à renseigner et le temps attribué, calculé dans la zone 'Autres et/ou Non attribué permets de vérifier que votre déclaration est complète.<br>
Ce temps doit être supérieur ou égal à zéro une fois le formulaire renseigné. Dans la mesure du possible, tout le temps doit être attribué et donc la rubrique 'Autres et/ou Non attribué à zéro'<br><br>

3- Cliquer sur le bouton valider la saisie, pour enregistrer votre évaluation.</p>
<br>

<?php #var_dump($SuiviHebdomadaireTempsPasse);?>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
  <?php hiddenControler("SuiviHebdomadaireTempsPasseControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hidden("","SuiviHebdomadaireTempsPasse:cabinet");?>
  <?php hidden("","SuiviHebdomadaireTempsPasse:date");?>

  <?php 
  $infirmieres = GetLoginsByCab($_SESSION['cabinet'], $status);
  #var_dump($infirmieres);
  ?>

<p><?php if($errors){echo $errors;}?></p>
<table border="1" cellpadding='3'>
   
  <tr>
      <td><b>Infirmière(s)</b></td>
      <td><b>Temps passé en heures</b></td>
    </tr>

    <?php 
    $infirmieres = GetLoginsByCab($_SESSION['cabinet'], $status);
    $tempsInfirmieres = SuiviHebdomadaireTempsPasseInfirmieres::getRecordsByCabinet($account->cabinet,$SuiviHebdomadaireTempsPasse->date);
  
    $tpsInf = array();$dureeTotale=0;
    foreach($tempsInfirmieres as $value){
      $tpsInf[$value['infirmiere']] = $value['duree'];
      $dureeTotale = $dureeTotale+$value['duree'];
    }
    
    $timeArray = array("0.5","1","1.5","2","2.5");

    foreach($infirmieres as $inf){ ?>
     <tr>
      <td><?php echo utf8_decode($inf['prenom'].' '.$inf['nom']); ?></td>
      <td align="center"><select name="tpsInf_<?php echo $inf['login'];?>" class="rv" onChange="javascript:changeTempsPasse();">
        <option value="0">Sélectionner</option>
        <!--<option value="0" <?php if($tpsInf[$inf['login']]=='0'){$selected= " selected";}?>>0</option>-->
        <?php 
        
        
        $i=0.5;$max=50;
        while($i < $max){
          
          if($tpsInf[$inf['login']]==$i){ $selected= "selected"; } else {$selected='';}
          echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>
          ';
          $i = $i+0.5;
        }
        ?>
        
      </select>
      </td>
    </tr>
  <?php } ?>



   <tr>
      <td><b>Nombre d'heures travaill&eacute;es <br/>dans la semaine dans ce cabinet</b></td>
      <td align="center">
        <input type="hidden" name="suiviHebdomadaireTempsPasse:SuiviHebdomadaireTempsPasse:tps_passe_cabinet" id="tps_passe_cabinet" value="<?php echo $SuiviHebdomadaireTempsPasse->tps_passe_cabinet;?>"><div id="totalTempsPasseCabinet" style="font-size:18px"><?php echo $dureeTotale;?> h</div>
      </td>
    </tr>
</table>

<div>&nbsp;</div>

<table border="1" cellpadding='3'>
  <tr>
    <th colspan="2" align="center">Tâche</th>
    <th>Nombre</th>
    <th>Dur&eacute;e<br>
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
      <td>D&eacute;pistage du Diabète type 2</td>
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
      <td>Rep&eacute;rage BPCO tabagique</td>
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

      <td>Rep&eacute;rage trouble cognitif</td>
      <?php if($countTypeConsultation['cognitif']){?>
        <td align="right"><?php echo $countTypeConsultation['cognitif'];?></td>
        <td align="right"><?php echo $TpsConsultation['cognitif'];?></td>
      <?php } else { ?>
        <td></td>
        <td></td>
     <?php } ?>
  </tr>
  <tr>
      <td>H&eacute;moccult</td>
      <?php if($countTypeConsultation['hemocult']){?>
        <td align="right"><?php echo $countTypeConsultation['hemocult'];?></td>
        <td align="right"><?php echo $TpsConsultation['hemocult'];?></td>
      <?php } else { ?>
        <td></td>
        <td></td>
     <?php } ?>
  </tr>
  <tr>
      <td>D&eacute;pistage Cancer du sein</td>
      <?php if( isset($countTypeConsultation['sein']) && $countTypeConsultation['sein']!='' ){?>
        <td align="right"><?php echo $countTypeConsultation['sein'];?></td>
        <td align="right"><?php echo $TpsConsultation['sein'];?></td>
      <?php } else { ?>
        <td></td>
        <td></td>
     <?php } ?>
  </tr>
  <tr>
      <td>D&eacute;pistage Cancer du colon</td>
        <?php if( isset($countTypeConsultation['colon']) && $countTypeConsultation['colon']!='' ){?>
        <td align="right"><?php echo $countTypeConsultation['colon'];?></td>
        <td align="right"><?php echo $TpsConsultation['colon'];?></td>
      <?php } else { ?>
        <td></td>
        <td></td>
     <?php } ?>
  </tr>
  <tr>
      <td>D&eacute;pistage Cancer de l'ut&eacute;rus</td>
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
        if(isset($countTypeConsultation) && $countTypeConsultation!=''){ ?>
        <td align="right"> <?php echo $total;?></td>
        <td align="right" id="total_tps_consult"> <?php echo array_sum($TpsConsultation);?></td>
        <?php } else { ?>
        <td></td>
        <td id="total_tps_consult"></td>
        <?php } ?>
  </tr>
  <tr>
      <td rowspan="5" width="185"><b>Temps infirmière passé en consultations</b></td>
      <td>Total du temps infirmière passé en consultations<br>(comptabilise le temps passé réel en prenant compte des consultations collectives)</td>
      <td align="right"><?php echo $total;?></td>
      <td align="right" id="total_tps_consult_retenu"> <?php echo (array_sum($tpsConsultDomicile) + array_sum($tpsConsultTel) + array_sum($dureeInfConsultCol) + array_sum($dureeInfAutresConsul)); ?></td>
  </tr>
  <tr>
    <td>dont consultations &agrave; domiciles</td>
      <?php if($consultDomicile){?>
      <td align="right"><?php echo array_sum($consultDomicile);?></td>
      <td align="right"><?php echo array_sum($tpsConsultDomicile);?></td>
      <?php } else { ?>
        <td></td>
        <td></td>
        <?php } ?>
  </tr>
  <tr>
      <td>dont consultations t&eacute;l&eacute;phoniques</td>
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

    


<?php# }?>



  <tr>
    <td colspan=2><b>Pr&eacute;paration/bilan des consultations</b></td>
    <td>&nbsp;</td>
    <td id="tps_consult_prepa_bilan" align="right"><?php echo round($tempsPrepaBilanConsultation);?></td>
  </tr>

  <tr>
    <td colspan=2>
        <b>Contribution aux actions de d&eacute;veloppement d&acute;Asal&eacute;e :</b> <br/>D&eacute;veloppement de nouveaux protocoles, communication,...
        <br>
        <em>Vous pouvez préciser : </em>
        <?php text("value='" . $SuiviHebdomadaireTempsPasse->precision_contribution_dev_asalee .  "'  size='65' maxlength='3000' style='text-align:left;background:orange;'","SuiviHebdomadaireTempsPasse:precision_contribution_dev_asalee");?>
    </td>
    <td><?php #text("size='6' style='text-align:right;background:orange;'  maxlength='6' ","SuiviHebdomadaireTempsPasse:nb_contact_tel_patient");?></td>
    <td><?php text("id='tps_contact_tel_patient' size='6'  style='text-align:right;background:orange;'  maxlength='6' onKeyUp='calcule_non_attrib()' value='".$SuiviHebdomadaireTempsPasse->tps_contact_tel_patient."'","SuiviHebdomadaireTempsPasse:tps_contact_tel_patient");?></td>
  </tr>

<!--   <tr>
    <td rowspan=2><b>Gestion sur dossier patient</b></td>
    <td>Mise &agrave; jour des dossiers ASALEE sur PSA : saisie, int&eacute;gration</td>
    <td>&nbsp;</td>
    <td><?php text("id='info_asalee' size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:info_asalee");?></td>
  </tr>
  <tr>
    <td>Travail sur dossiers m&eacute;dicaux sur le logiciel de gestion du cabinet : alerte, enrichissement de donn&eacute;es</td>
    <td>&nbsp;</td>
    <td><?php text("id='info_dossiermed' size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:info_dossiermed");?></td>
  </tr>  -->

  <tr>
    <td colspan="2"><b>Gestion sur dossier patient</b><br/>
    Mise à jour des dossiers ASALEE sur PSA : saisie, int&eacute;gration<br/>
    Travail sur dossiers m&eacute;dicaux sur le logiciel de gestion du cabinet : alerte, enrichissement de donn&eacute;es
    </td>
    <td>&nbsp;</td>
    <td><?php text("id='info_asalee' value='".$SuiviHebdomadaireTempsPasse->info_asalee."' size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:info_asalee");?></td>
  </tr>

  <tr>
    <td colspan=2><b>Auto-formation</b> (recherches internet, lecture d'articles...)</td>
    <td>&nbsp;</td>
    <td><?php text("id='autoformation' value='".$SuiviHebdomadaireTempsPasse->autoformation."'  size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:autoformation");?></td>
  </tr>
  <tr>
    <td colspan=2><b>Formation suivie</b></td>
    <td>&nbsp;</td>
    <td><?php text("id='formation' value='".$SuiviHebdomadaireTempsPasse->formation."'  size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:formation");?></td>
  </tr>
  <tr>
    <td colspan=2><b>Encadrement de stagiaires</b></td>
    <td>&nbsp;</td>
    <td><?php text("id='stagiaires' value='".$SuiviHebdomadaireTempsPasse->stagiaires."'  size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:stagiaires");?></td>
  </tr>
  <tr>
    <td colspan=2><b>Concertation avec les m&eacute;decins : Nombre de r&eacute;unions et temps total pass&eacute;</b></td>
    <td style='text-align:right;'><?php if(empty($SuiviReunionMedecin)){echo '0';}else{echo count($SuiviReunionMedecin);}?></td>

    <?php
      // 
      $tempsRC = 0;
      foreach ($SuiviReunionMedecin as $reuM){
        $tempsAdd = count(explode(",",$reuM['infirmiere']))* $reuM['duree'];
        $tempsRC = $tempsRC + $tempsAdd;
      }

      ?>
    <td id='tps_reunion_medecin' style='text-align:right;'><?php echo $tempsRC;?> <?php #echo array_sum($TpsReunionMedecin);?></td>
   <!--  <td><?php text("size='6' maxlength='6' style='text-align:right;background:orange;' ","SuiviHebdomadaireTempsPasse:nb_reunion_medecin");?></td>
    <td><?php text("id='tps_reunion_medecin' size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:tps_reunion_medecin");?></td> -->
  </tr>
  <tr>
    <td colspan=2><b>Echanges avec d'autres infirmi&egrave;res : Nombre de r&eacute;unions et temps total pass&eacute;</b></td>
    <td><?php text("size='6' maxlength='6' style='text-align:right;background:orange;'","SuiviHebdomadaireTempsPasse:nb_reunion_infirmiere");?></td>
    <td><?php text("id='tps_reunion_infirmiere' value='".$SuiviHebdomadaireTempsPasse->tps_reunion_infirmiere."'  size='6' maxlength='6' style='text-align:right;background:orange;' onKeyUp='calcule_non_attrib()'","SuiviHebdomadaireTempsPasse:tps_reunion_infirmiere");?></td>

  </tr>

  <tr>
    <td colspan=2><b>Autres et/ou Non attribu&eacute;</b></td>
    <td>&nbsp;</td>
     <td align="right">
      <input id="val_non_att" type="hidden" name="suiviHebdomadaireTempsPasse:SuiviHebdomadaireTempsPasse:non_atribue" value="<?php echo $SuiviHebdomadaireTempsPasse->non_atribue;?>"/>
      <span id="non_atribue"><?php echo $SuiviHebdomadaireTempsPasse->non_atribue;?></span>
     </td>
  </tr>

  <tr>
    <td colspan='3'><b>Total</b><div style="float:right;">équivalence en heures : &nbsp;&nbsp;<div style="float:right;" id="equiv_heure"><?php echo $SuiviHebdomadaireTempsPasse->tps_passe_cabinet/60 ;?></div></div></td>
    <td align="right" id="ttt"><div id="tttrv"><?php echo $SuiviHebdomadaireTempsPasse->tps_passe_cabinet;?></div></td>
  </tr>

  <tr>
    <td colspan='4'>&nbsp;</td>
  </tr>

  <tr>
    <td colspan="4" align='center'> <input type='submit' value='Valider la saisie'>
      <input type='reset' value='Recommencer'> </td>
  </tr>
</table>
</form>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script language="JavaScript" type="text/javascript">

  // var $ = function(id) {
  //   return document.getElementById(id);
  // }
  
  calcule_non_attrib();

  var totalCabinet2 = 0;
  function changeTempsPasse(){
      var totalCabinet2 = 0;
      $('.rv').each(function(){
        //alert(parseInt($(this).children('option:selected').val()))
        if($(this).children('option:selected').val() != NaN){
          totalCabinet2 = parseFloat(totalCabinet2) + parseFloat($(this).children('option:selected').val());
        }

      });
      
      //alert(parseFloat(totalCabinet2));
      $('#tps_passe_cabinet').val(totalCabinet2*60);
      $('#totalTempsPasseCabinet').html(totalCabinet2+' h');
      $('#ttt').html(totalCabinet2*60);

      $('#equiv_heure').html(totalCabinet2);
      //alert(totalCabinet2);
      
      calcule_non_attrib();

    };

  
 
  function calcule_non_attrib(){

    /*
    $('#tps_contact_tel_patient').value = $('#tps_contact_tel_patient').value.replace(",",".");
    $('info_asalee').value = $('info_asalee').value.replace(",",".");
    $('autoformation').value = $('autoformation').value.replace(",",".");
    $('formation').value = $('formation').value.replace(",",".");
    $('stagiaires').value = $('stagiaires').value.replace(",",".");
    // $('tps_reunion_medecin').value = $('tps_reunion_medecin').innerHTML;
    $('tps_reunion_infirmiere').value = $('tps_reunion_infirmiere').value.replace(",",".");
*/

    // if($('total_tps_consult').innerHTML == null)
    // $('total_tps_consult').innerHTML = O;
    

    if(parseInt($('#total_tps_consult_retenu').html()) != NaN){
      var tpc = parseInt($('#total_tps_consult_retenu').html());
    } else {var tpc = 0;}

    if(parseInt($('#tps_consult_prepa_bilan').html()) != NaN){
      var tcpb = parseInt($('#tps_consult_prepa_bilan').html());
    } else {var tcpb = 0;}


    var val_tctp = $('#tps_contact_tel_patient').val();
    //alert(val_tctp);
    if(val_tctp != ''){
      var tctp = parseInt(val_tctp);
      //var tctp = parseInt($('#tps_contact_tel_patient').val());
    } else {var tctp = 0;}

    var val_ia = $('#info_asalee').val();
    if(val_ia != ''){
      var ia = parseInt(val_ia);
    } else {var ia = 0; }

    var val_af = $('#autoformation').val();
    if(val_af != ''){
      var af = parseInt(val_af);
    } else {var af = 0; }

    var val_ff = $('#formation').val();
    if(val_ff != ''){
      var ff = parseInt(val_ff);
    } else {var ff = 0; }
    
    var val_stg = $('#stagiaires').val();
    if(val_stg != ''){
      var stg = parseInt(val_stg);
    } else {var stg = 0; }

    if(parseInt($('#tps_reunion_medecin').html()) != NaN){
      var trm = parseInt($('#tps_reunion_medecin').html());
    } else {var trm = 0; }

    var val_tri = $('#tps_reunion_infirmiere').val();
    if(val_tri != ''){
      var tri = parseInt(val_tri);
    } else {var tri = 0; }

    //console.log(tri);
    var tttt = 0;
    
    /*
    var tttt =
    parseInt($('#total_tps_consult').html()) +
    parseInt($('#tps_consult_prepa_bilan').html()) +
    parseInt($('#tps_contact_tel_patient').val()) +
    parseInt($('#info_asalee').val()) +
    parseInt($('#autoformation').val()) +
    parseInt($('#formation').val()) +
    parseInt($('#stagiaires').val()) +
    parseInt($('#tps_reunion_medecin').html()) +
    parseInt($('#tps_reunion_infirmiere').val());
    */
    
    //alert(tpc+'-'+tcpb+'-'+tctp+'-'+ia+'-'+af+'-'+ff+'-'+stg+'-'+trm+'-'+tri);
    var tttt = tpc+tcpb+tctp+ia+af+ff+stg+trm+tri;
    //alert(tttt);    
    tttt =  Math.round(tttt);

    //console.log(tpc+'@'+tcpb+'@'+tctp+'@'+ia+'@'+af+'@'+ff+'@'+stg+'@'+trm+'@'+tri);
    //console.log(parseInt(tttt));
    var ttt = $('#tps_passe_cabinet').val();
    //console.log(val_tri);
     
     $('#non_atribue').html(parseInt(ttt - tttt));  
     $('#val_non_att').val(parseInt(ttt - tttt));


  }
  //calcule_non_attrib();
 
</script>


</body></html>

