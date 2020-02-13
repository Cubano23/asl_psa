<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $TroubleCognitif ?>
<?php global $EvaluationInfirmier; ?>
<?php global $param ?>

<script type="text/javascript" >
<?php
	validateNumeroDossier();
	validateDate();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateDate("TroubleCognitif:date","Date du suivi");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="get" name="aForm"> 
  <?php hiddenControler("TroubleCognitifControler"); ?>
  <?php hiddenAction(ACTION_NEW); ?> 
  <?php hidden("","dossier:numero"); ?>
  <?php hidden("","EvaluationInfirmier:id"); ?>
 
Ce formulaire permet à tout instant de faire passer un test de dépistage des troubles cognitifs à un patient.<br><br>
Il s'appuie sur les réponses données lors du test.<br><br>
  <b>Suivi du jour:
  <table border=0 cellspacing=14> 
    <tr> 
      <td>date du suivi:</td> 
      <td><?php text(" onkeyup='formate_date(this)'","TroubleCognitif:date"); ?></td>
    </tr> 
 
    <tr> 
      <td></td> 
      <td><table border=0> 
          <tr> 
            <td><font color='blue'><b>MMSE</b></font></td>
            <td><?php checkBox("","TroubleCognitif:suivi_type","mmse"); ?></td>
          </tr> 
          <tr>
            <td><font color='green'><b>GDS</b></font></td>
            <td><?php checkBox("","TroubleCognitif:suivi_type","gds"); ?></td>
          </tr>
          <tr>
            <td><font color='#FF00FF'><b>IADL</b></font></td>
            <td><?php checkBox("","TroubleCognitif:suivi_type","iadl"); ?></td>
          </tr>
          <tr>
            <td><font color='brown'><b>Horloge</b></font></td>
            <td><?php checkBox("","TroubleCognitif:suivi_type","horl"); ?></td>
          </tr> 
         <tr>
            <td><font color='orange'><b>5 mots de Dubois</b></font></td>
            <td><?php checkBox("","TroubleCognitif:suivi_type","dubois"); ?></td>
          </tr> 
         <tr>
            <td><font color='black'><b>EDF</b></font></td>
            <td><?php checkBox("","TroubleCognitif:suivi_type","edf"); ?></td>
          </tr> 
         <tr>
            <td><font color='purple'><b>NPI</b></font></td>
            <td><?php checkBox("","TroubleCognitif:suivi_type","npi"); ?></td>
          </tr> 
        </table></td> 
    </tr> 
  </table>
  <input type='button' onclick="validateInput()" value='Suivant'> 
</form> 
