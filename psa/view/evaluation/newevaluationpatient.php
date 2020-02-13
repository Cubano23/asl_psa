<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $evaluationPatient ?>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("EvaluationPatientControler"); ?>
<?php hiddenAction(ACTION_SAVE); ?>
<?php hidden("","evaluationPatient:date");?>
<?php hidden("","dossier:numero");?>

<?php require("view/common/dossierresume.php");?>

<table border="1" cellpadding='3'>
<tr>
  <td>Degré de satisfaction:</td>
  <td><?php selectv("","evaluationPatient:degre_satisfaction",$satisfaction); ?></td>
</tr>
<tr>
  <td valign='top'>Question posée par le patient:</td>
  <td><?php textArea("","evaluationPatient:question_pat"); ?></td>
</tr>
<tr>  
  <td valign='top'>Evolution du recours aux médecins:</td>
  <td><?php textArea("","evaluationPatient:evol_recours_med"); ?></td>
</tr>
<tr>  
  <td colspan="2" align='center'>
    <input type='submit' value='Valider la saisie'> 
    <input type='reset' value='Recommencer'>
   </td>
</tr>
</table>
</form>

