<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account; ?>
<?php global $dossier; ?>
<?php global $TroubleCognitif; ?>
<?php global $param; ?>
<?php global $outDateReference; ?>

<script type="text/javascript" >
<?php
	validateInteger();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateInteger("outDateReference:period","délai");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
  <?php hiddenControler("TroubleCognitifControler"); ?>
  <?php hiddenAction(ACTION_LIST); ?> 
  <?php hiddenParam1(PARAM_OUTDATED); ?>
  <br> 
  <table border="0"> 
      <tr> 
        <td>Cabinet:</td> 
        <td><?php typePropertyValue("account:cabinet"); ?></td> 
      </tr> 
      <tr> 
        <td>délai:</td>
        <td><?php text("","outDateReference:period");?>mois</td> 
      <tr>       
      <tr> 
        <td colspan="2"><input type="button"  onClick="validateInput()" name="Valider" value="Valider"> </td> 
      </tr> 
  </table> 
</form> 
<br> 
