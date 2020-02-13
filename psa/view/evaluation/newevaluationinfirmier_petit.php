<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $evaluationInfirmier ?>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("EvaluationInfirmierControler"); ?>
<?php hiddenAction(ACTION_SAVE); ?>
<?php hidden("","evaluationInfirmier:date");?>
<?php hidden("","dossier:numero");?>

  <?php require("view/common/dossierresume.php");?>
	
  <table border="1" cellpadding='3'>
    <tr>
      <td>Degré de satisfaction:</td>
      <td><?php selectv("","evaluationInfirmier:degre_satisfaction",$satisfaction); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points positifs :
      <div style="font-size:9px">
  Besoins du patient pris en compte<br>
  Objectifs prévus atteints<br>
  Objectifs  non  prévus atteints<br>
  Outil(s), support (s), méthodes  utilisés </div></td>
      <td  width='70%'><?php textArea("","evaluationInfirmier:points_positifs"); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points à améliorer :
         <div style="font-size:9px">
    Besoins du patient non pris en compte<br>
  Objectifs prévus non  atteints<br>
  Objectifs perçus à atteindre<br>
  Méthodes envisagées prochaine séance</div>
      </td>
      <td  width='70%'><?php textArea("","evaluationInfirmier:points_ameliorations"); ?></td>
    </tr>
    <tr>
      <td colspan="2" align='center'><input type='submit' value='Valider la saisie'>
        <input type='reset' value='Recommencer'>
      </td>
    </tr>
  </table>
</form>

