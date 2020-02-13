<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $evaluationMedecin ?>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("EvaluationInfirmierControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","evaluationMedecin:date");?>
<?php hidden("","dossier:numero");?>
<table border='0' width='100%' align='center'> 
  <tr> 
    <td valign='top'><?php require("view/common/dossierresume.php");?></td> 
    <td> <table border='1' cellpadding='3'> 
        <tr> 
          <th>Question</th> 
          <th>Réponse(s)</th> 
        </tr> 
        <tr> 
          <td colspan=2><b>Evaluation par le patient</b></td> 
        </tr> 
        <tr> 
          <td valign='top'>Satisfaction</td> 
          <td><?php echo($satisfaction[getPropertyValue("evaluationMedecin:degre_satisfaction")]); ?></td> 
        </tr> 
        <tr> 
          <td valign='top'>Durée et fréquence des consultations</td> 
          <td><?php typePropertyValue("evaluationMedecin:question_pat"); ?></td> 
        </tr> 
        <tr> 
          <td valign='top'>Satisfaction des patients</td> 
          <td><?php typePropertyValue("evaluationMedecin:evol_recours_med"); ?></td> 
        </tr> 
      </table> 
      <br></td> 
  </tr> 
  <tr> 
    <td>
        <?php customSubmitWithAlert("value='Supprimer cette &eacute;valuation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r&eacute;ellement supprimer cette &eacute;valuation?"); ?> 
	</td> 
    <td> 
        <?php customSubmit("value='Modifier cette &eacute;valuation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?> 
    </td> 
  </tr> 
</table> 
</form>

