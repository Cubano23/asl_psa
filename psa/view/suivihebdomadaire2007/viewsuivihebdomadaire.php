<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php global $account; ?>
<?php global $param; ?>

<?php global $SuiviHebdomadaire2007; ?>
 <table border='1'  cellpadding='0'>
  <tr>
     <td><b>Cabinet: </b></td>
     <td><?php echo($account->cabinet) ?></td>
   </tr>
  <tr>
     <td><b>Semaine: </b></td>
     <td><?php echo($SuiviHebdomadaire2007->date); ?> </td>
   </tr>
</table>
<br>
<br>
<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
  <?php hiddenControler("SuiviHebdomadaire2007Controler"); ?>
  <?php hiddenAction(""); ?>
  <?php hiddenParam1(""); ?>
  <?php hidden("", "SuiviHebdomadaire2007:cabinet"); ?>
  <?php hidden("","SuiviHebdomadaire2007:date");?>
  <table border="1" cellpadding='3'>
    <tr>
      <th colspan="2">Tâche</th>
      <th>Nombre</th>
      <th>Durée<br>
        (heures)</th>
    </tr>
    <tr>
      <td rowspan=2><b>Travail informatique</b></td>
      <td>Asalée</td>
	      <td>&nbsp;</td>
    		  <td><?php echo $SuiviHebdomadaire2007->info_asalee;?></td>
    </tr>
    <tr>
      <td>Dossiers Médicaux</td>
	      <td>&nbsp;</td>
    		  <td><?php echo $SuiviHebdomadaire2007->info_dossiermed;?></td>
    </tr>
    <tr>
      <td rowspan='7'><b>Consultations</b></td>
	      <td>Suivi de diabète</td>
		      <td><?php echo $SuiviHebdomadaire2007->nb_consult_suividiab;?></td>
		      <td><?php echo $SuiviHebdomadaire2007->tps_consult_suividiab;?></td>
    </tr>

    <tr>
	      <td>Dépistage diabète</td>
		      <td><?php echo $SuiviHebdomadaire2007->nb_consult_depdiab;?></td>
		      <td><?php echo $SuiviHebdomadaire2007->tps_consult_depdiab;?></td>
    </tr>
    <tr>
	      <td>Dépistage Cancer</td>
		      <td><?php echo $SuiviHebdomadaire2007->nb_consult_depcancer;?></td>
		      <td><?php echo $SuiviHebdomadaire2007->tps_consult_depcancer;?></td>
    </tr>
    <tr>
	      <td>Tests mémoire</td>
		      <td><?php echo $SuiviHebdomadaire2007->nb_consult_memoire;?></td>
		      <td><?php echo $SuiviHebdomadaire2007->tps_consult_memoire;?></td>
    </tr>
    <tr>
	      <td>Automesure TA</td>
		      <td><?php echo $SuiviHebdomadaire2007->nb_consult_autota;?></td>
		      <td><?php echo $SuiviHebdomadaire2007->tps_consult_autota;?></td>
    </tr>
    <tr>
	      <td>HTA</td>
		      <td><?php echo $SuiviHebdomadaire2007->nb_consult_hta;?></td>
		      <td><?php echo $SuiviHebdomadaire2007->tps_consult_hta;?></td>
    </tr>
    <tr>
	      <td>Autres</td>
		      <td><?php echo $SuiviHebdomadaire2007->nb_consult_autre;?></td>
		      <td><?php echo $SuiviHebdomadaire2007->tps_consult_autre;?></td>
    </tr>
    <tr>
      <td colspan=2><b>ECG</b></td>
      <td>&nbsp;</td>
      <td><?php echo $SuiviHebdomadaire2007->ecg;?></td>
    </tr>
    <tr>
      <td colspan=2><b>Auto-formation</b> (recherches internet, lecture d'articles...)</td>
      <td>&nbsp;</td>
      <td><?php echo $SuiviHebdomadaire2007->autoformation;?></td>
    </tr>
    <tr>
      <td colspan=2><b>Formation</b></td>
      <td>&nbsp;</td>
      <td><?php echo $SuiviHebdomadaire2007->formation;?></td>
    </tr>
    <tr>
      <td colspan=2><b>Encadrement de stagiaires</b></td>
      <td>&nbsp;</td>
      <td><?php echo $SuiviHebdomadaire2007->stagiaires;?></td>
    </tr>
    <tr>
      <td colspan=2><b>Réunion</b></td>
      <td>&nbsp;</td>
      <td><?php echo $SuiviHebdomadaire2007->reunion;?></td>
    </tr>
    <tr>
      <td colspan=2><b>Téléphone</b></td>
      <td>&nbsp;</td>
      <td><?php echo $SuiviHebdomadaire2007->telephone;?></td>
    </tr>
    <tr>
      <td colspan=2><b>Autres</b></td>
      <td>&nbsp;</td>
      <td><?php echo $SuiviHebdomadaire2007->autre;?></td>
    </tr>
    <tr>
      <td colspan='3'>&nbsp; </td>
      <td>____</td>
    </tr>
    <tr>
      <td colspan='3'><b>Total </b></td>
      <td><?php $total = $SuiviHebdomadaire2007->info_asalee + $SuiviHebdomadaire2007->info_dossiermed +
	  					 $SuiviHebdomadaire2007->tps_consult_suividiab + $SuiviHebdomadaire2007->tps_consult_depdiab +
						 $SuiviHebdomadaire2007->tps_consult_depcancer + $SuiviHebdomadaire2007->tps_consult_memoire +
						 $SuiviHebdomadaire2007->tps_consult_autota + $SuiviHebdomadaire2007->tps_consult_hta +
						 $SuiviHebdomadaire2007->tps_consult_autre + $SuiviHebdomadaire2007->ecg +
						 $SuiviHebdomadaire2007->autoformation + $SuiviHebdomadaire2007->formation +
						 $SuiviHebdomadaire2007->stagiaires + $SuiviHebdomadaire2007->reunion +
						 $SuiviHebdomadaire2007->telephone + $SuiviHebdomadaire2007->autre;

      		echo $total; ?>
      	&nbsp;</td>
    </tr>
    <tr>
      <td colspan='4'>&nbsp; </td>
    </tr>
  <tr>
    <td colspan='4' align='center'>
        <?php customSubmitWithAlert("value='Supprimer cette évaluation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r&eacute;ellement supprimer cette évaluation?"); ?>
        <?php customSubmit("value='Modifier cette &eacute;valuation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
    </td>
  </tr>
  </table>
</form>

</body></html>

