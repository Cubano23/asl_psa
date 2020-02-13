
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $SuiviReunionMedecin; ?>

<script type="text/javascript" >
<?php
	validateDate();
	compareDates();
	dateInRange();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->dateInRange("SuiviReunionMedecin:date","Date du suivi");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("SuiviReunionMedecinControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>

  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%">Cabinet</td>
      <td width="60%">&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Date de réunion</td>
      <td>&nbsp;<?php text("size='10'","SuiviReunionMedecin:date"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'>
	  	<?php customSubmit("value='Créer'",ACTION_NEW,"","","validateInput"); ?>
		  <?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),"","validateInput"); ?>
		  <?php customSubmit("value='Liste'",ACTION_LIST,"",""); ?>
	  </td>	  

    </tr>
  </table>
</form>
