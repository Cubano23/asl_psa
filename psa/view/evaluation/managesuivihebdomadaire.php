
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php /*global $dossier */?>
<?php /*global $param */?>
<?php global $SuiviHebdomadaire; ?>

<script type="text/javascript" >
<?php
	validateDate();
	compareDates();
	dateInRange();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->dateInRange("SuiviHebdomadaire:date","Date du dépistage");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("SuiviHebdomadaireControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>

  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%">Cabinet</td>
      <td width="60%"><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
	<tr>
      <td>Date de l'évaluation </td>	  
      <td><?php text("","SuiviHebdomadaire:date"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
	  	<?php customSubmit("value='Créer'",ACTION_NEW,"","","validateInput"); ?>
		<?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),"","validateInput"); ?>
		<?php customSubmit("value='Liste'",ACTION_LIST,"",""); ?>
	  </td>	  
<?php //      <td><input type="button" value="Créer ou modifier un dossier"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE) /*,array("Dossier:dossier:numero"=>$dossier->numero));*/ ?><?/*','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')"/></td>
*/ ?>   </tr>
  </table>
</form>

