<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $TroubleCognitif ?>
<?php global $param ?>


<script type="text/javascript" >
<?php
	validateNumeroDossier();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
  <?php hiddenControler("TroubleCognitifControler"); ?>
  <?php hiddenAction(ACTION_MANAGE); ?> 
  <?php hiddenParam1(PARAM_CREATE); ?> 
Ce formulaire permet à tout instant de faire passer un test de dépistage des troubles cognitifs à un patient.<br><br>
Il s'appuie sur les réponses données lors du test.<br><br>
  <table border="0"> 
    <tr> 
      <td>Cabinet</td> 
      <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td> 
    </tr> 
    <tr> 
      <td>Numéro de dossier</td> 
      <td>&nbsp;<?php text("size='10'","dossier:numero"); ?></td> 
    </tr> 
    <tr> 
      <td colspan="2"> <input type="button"  onClick="validateInput()" name="valider" value="Valider">
      <!-- <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/> --> </td> 
    </tr> 
  </table> 
</form> 

