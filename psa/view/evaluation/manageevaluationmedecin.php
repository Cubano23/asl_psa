
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>
<?php global $currentObjectName;?>

<script type="text/javascript" >
<?php
	
	validateDate();
	compareDates();
	dateInRange();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");	
	$js->dateInRange("$currentObjectName:date","Date du dépistage");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler(""); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>

  
  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%">Cabinet</td>
      <td width="60%"><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Nom du medecin </td>
      <td><?php text("","$currentObjectName:name"); ?></td>
    </tr>
	<tr>
      <td>Date de l'évaluation </td>	  
      <td><?php text("","$currentObjectName:date"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
	  	<?php customSubmit("value='Créer'",ACTION_NEW,"","$param->controler","validateInput"); ?>
	  </td>	  
      <td>&nbsp;</td>
    </tr>
  </table>
</form>

