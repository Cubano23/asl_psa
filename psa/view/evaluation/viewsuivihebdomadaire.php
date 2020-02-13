<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php global $account; ?>
<?php global $param; ?>

<?php global $SuiviHebdomadaire;  ?>
 <table border='1'  cellpadding='0'> 
  <tr> 
     <td>Cabinet: </td> 
     <td><?php echo($account->cabinet) ?></td> 
   </tr> 
  <tr> 
     <td>Semaine: </td> 
     <td><?php echo($SuiviHebdomadaire->date); ?> </td>
   </tr> 
</table> 
<br> 
<br> 
<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage"> 
 <?php hiddenControler("SuiviHebdomadaireControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
  <?php hidden("", "SuiviHebdomadaire:cabinet"); ?>
  <?php hidden("","SuiviHebdomadaire:date");?>
  <table border="1" cellpadding='3'> 
    <tr> 
      <th>tâche</th> 
      <th>durée<br> 
        (heures)</th> 
      <th>nombre</th> 
    </tr> 
    <tr> 
      <td colspan=2><b>Dépistage</b></td> 
    </tr> 
    <tr> 
      <td>Travail sur base de données</td> 
      <td> <?php echo $SuiviHebdomadaire->travail_base_h;?></td>
    </tr> 
    <tr> 
      <td>Consultations individuelles</td> 
      <td><?php echo $SuiviHebdomadaire->consult_indiv_h?></td>
      <td><?php echo $SuiviHebdomadaire->consult_indiv_n;?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td colspan=2><b>Prévention</b></td> 
    </tr> 
    <tr> 
      <td colspan=2>Consultation d'éducation à la santé</td> 
    </tr> 
    <tr> 
      <td>diabète</td> 
      <td><?php echo $SuiviHebdomadaire->prevention_diabete_h;?></td>
    </tr> 
    <tr> 
      <td>autres</td> 
      <td><?php echo $SuiviHebdomadaire->prevention_autre_h;?></td>
    </tr> 
    <tr> 
      <td>si autres, lesquelles</td> 
      <td colspan=2><?php echo $SuiviHebdomadaire->prevention_autre_note;?></td>
    </tr> 
    <tr> 
      <td colspan=2>Séances collectives</td> 
    </tr> 
    <tr> 
      <td>diabète</td> 
      <td><?php echo $SuiviHebdomadaire->seance_diabete_h;?></td>
    </tr> 
    <tr> 
      <td>autres</td> 
      <td><?php echo $SuiviHebdomadaire->seance_autre_h;?></td>
    </tr> 
    <tr> 
      <td>si autres, lesquelles</td> 
      <td colspan=2><?php echo $SuiviHebdomadaire->seance_autre_note;?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td>Suivi de l'armoire à pharmacie</td> 
      <td><?php echo $SuiviHebdomadaire->suivi_armoire_h;?></td>
      <td><?php echo $SuiviHebdomadaire->suivi_armoire_n;?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td colspan=2><b>Aide au fonctionnement du cabinet</b></td> 
    </tr> 
    <tr> 
      <td>Téléphone</td> 
      <td><?php echo $SuiviHebdomadaire->aide_telephone;?></td>
    </tr> 
    <tr> 
      <td>Préparation du matériel</td> 
      <td><?php echo $SuiviHebdomadaire->aide_prep_matos;?></td>
    </tr> 
    <tr> 
      <td>Examens complémentaires<br> 
        <font size="-1">(ECG, automesure TA, autres)</font></td> 
      <td valign='top'><?php echo $SuiviHebdomadaire->aide_examen_compl;?></td>
    </tr> 
    <tr> 
      <td>Soins</td> 
      <td><?php echo $SuiviHebdomadaire->aide_soins;?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td>Formation </td> 
      <td><?php echo $SuiviHebdomadaire->aide_formation;?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td>Autres</td> 
      <td><?php echo $SuiviHebdomadaire->aide_autre;?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
      <td>____</td> 
    </tr> 
    <tr> 
      <td>Total </td> 
      <td><?php $total = $SuiviHebdomadaire->travail_base_h + $SuiviHebdomadaire->consult_indiv_h + $SuiviHebdomadaire->prevention_diabete_h +
      			$SuiviHebdomadaire->prevention_autre_h + $SuiviHebdomadaire->seance_diabete_h + $SuiviHebdomadaire->seance_autre_h
      			+ $SuiviHebdomadaire->suivi_armoire_h + $SuiviHebdomadaire->aide_telephone + $SuiviHebdomadaire->aide_prep_matos +
      			$SuiviHebdomadaire->aide_examen_compl + $SuiviHebdomadaire->aide_soins + $SuiviHebdomadaire->aide_formation + $SuiviHebdomadaire->aide_autre;
      			
      		echo $total; ?>
      	&nbsp;</td> 
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
  <tr> 
    <td> 
        <?php customSubmitWithAlert("value='Supprimer cette évaluation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r&eacute;ellement supprimer cette évaluation?"); ?> 
    </td>
    <td>
        <?php customSubmit("value='Modifier cette &eacute;valuation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
    </td> 
  </tr> 
  </table> 
</form> 

</body></html>
