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
  <?php hiddenAction(""); ?> 
  <?php hiddenParam1(""); ?>
  <?php hiddenParamN("",2); ?>

Ce formulaire permet à tout instant de faire passer un test de dépistage des troubles cognitifs à un patient.<br><br>
Il s'appuie sur les réponses données lors du test.<br><br>

<table border="0">
      <tr>
        <td>Cabinet</td>
        <td><?php typePropertyValue("account:cabinet"); ?></td>
      </tr>
      <tr>
        <td>N&deg; de dossier:</td>
        <td><?php text("","dossier:numero"); ?></td>
      </tr>  
	  
      <tr>
        <td colspan="2">
			<?php customSubmit("value='Chercher'",ACTION_LIST,array(PARAM_ANY),"","validateInput"); ?>		
			<?php customSubmit("value='Liste'",ACTION_LIST,array(PARAM_LIST_BY_CABINET),""); ?>
			
        </td>
      </tr>
  </table>
</form>

