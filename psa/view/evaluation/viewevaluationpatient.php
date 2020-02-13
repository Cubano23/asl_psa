<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>
<?php global $evaluationPatient ?>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("EvaluationPatientControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","evaluationPatient:date");?>
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
          <td colspan=2><b>Evaluation Patient</b></td>
        </tr>
        <tr>
          <td valign='top'>Satisfaction</td>
          <td>&nbsp;<?php echo($satisfaction[getPropertyValue("evaluationPatient:degre_satisfaction")]); ?></td>
        </tr>
        <tr>
          <td valign='top'>Question posée par le patient</td>
          <td>&nbsp;<?php typePropertyValue("evaluationPatient:question_pat"); ?></td>
        </tr>
        <tr>
          <td valign='top'>Evolution du recours aux médecins</td>
          <td>&nbsp;<?php typePropertyValue("evaluationPatient:evol_recours_med"); ?></td>
        </tr>
      </table> 
      <br></td> 
  </tr> 
  <tr> 
    <td>
        <?php customSubmitWithAlert("value='Supprimer cette évaluation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r&eacute;ellement supprimer cette évaluation?"); ?> 
    </td> 
    <td> 
      <?php customSubmit("value='Modifier cette évaluation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
</td> 
  </tr> 
</table> 
  </form>

