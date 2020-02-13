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
      <td>Degr� de satisfaction:</td>
      <td><?php selectv("","evaluationInfirmier:degre_satisfaction",$satisfaction); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points positifs :
      <div style="font-size:9px">
  Besoins du patient pris en compte<br>
  Objectifs pr�vus atteints<br>
  Objectifs  non  pr�vus atteints<br>
  Outil(s), support (s), m�thodes  utilis�s </div></td>
      <td  width='70%'><?php textArea("","evaluationInfirmier:points_positifs"); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points � am�liorer :
         <div style="font-size:9px">
    Besoins du patient non pris en compte<br>
  Objectifs pr�vus non  atteints<br>
  Objectifs per�us � atteindre<br>
  M�thodes envisag�es prochaine s�ance</div>
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

