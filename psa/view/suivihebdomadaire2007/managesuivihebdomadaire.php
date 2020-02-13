
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $SuiviHebdomadaire2007; ?>

<script type="text/javascript" >
<?php
	validateDate();
	compareDates();
	dateInRange();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->dateInRange("SuiviHebdomadaire2007:date","Date du suivi");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("SuiviHebdomadaire2007Controler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>

  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%">Cabinet</td>
      <td width="60%">&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
	<tr>
      <td>Date du suivi </td>	  
      <td>&nbsp;<?php text("size='10'","SuiviHebdomadaire2007:date"); ?></td>
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
<?php //      <td><input type="button" value="Créer ou modifier un dossier"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE) /*,array("Dossier:dossier:numero"=>$dossier->numero));*/ ?><?/*','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')"/></td>
*/ ?>   </tr>
  </table>
</form>

