<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>

<?php global $account;?>

<?php global $evaluationMedecin ?>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("EvaluationMedecinControler"); ?>
<?php hiddenAction(ACTION_SAVE); ?>
<?php hidden("","evaluationMedecin:date");?>
<?php hidden("","evaluationMedecin:name");?>


	
  <table border="1" cellpadding='3'>
  <tr>
      <td colspan="2">Evaluation de Dr <?php typePropertyValue("evaluationMedecin:name");?> au <?php typePropertyValue("evaluationMedecin:date") ?> </td>      
    </tr>
    <tr>
      <td>Degré de satisfaction:</td>
      <td><?php selectv("","evaluationMedecin:degre_satisfaction",$satisfaction); ?></td>
    </tr>
    <tr>
      <td valign='top'>Durée et fréquence des consultations :</td>
      <td><?php textArea("","evaluationMedecin:duree_freq_consult"); ?></td>
    </tr>
    <tr>
      <td valign='top'>Satisfaction des patients :</td>
      <td><?php textArea("","evaluationMedecin:satisfaction_pat"); ?></td>
    </tr>
    <tr>
      <td colspan="2" align='center'><input type='submit' value='Valider la saisie'>
        <input type='reset' value='Recommencer'>
      </td>
    </tr>
  </table>
</form>

