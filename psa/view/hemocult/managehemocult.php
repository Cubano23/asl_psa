<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php global $account;?>
<?php global $dossier; ?>
<?php global $Hemocult; ?>

<script type="text/javascript" >
<?php
	validateDate();
	compareDates();
	dateInRange();
	validateNumeroDossier();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
	$js->dateInRange("Hemocult:date","Date du dépistage");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php"); ?>" method="post" name="manage">
<?php hiddenControler("HemocultControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>

Ce formulaire permet à tout instant de collecter des données utiles au protocole hémoccult.<br><br>
Il s'appuie sur les données les plus récentes du patient (résultats d'examens)<br><br>
	
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>Cabinet</td>
      <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Numero de dossier</td>
      <td>&nbsp;<?php text("size='10'","dossier:numero"); ?></td>
    </tr>
	<tr>
      <td>Date du dépistage</td>
      <td>&nbsp;<?php text("size='10' onkeyup='formate_date(this)'","Hemocult:date"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'>
	  	<?php customSubmit("value='Valider'",ACTION_NEW,"",""); ?>
		<?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),""); ?>
		<?php customSubmit("value='Liste'",ACTION_LIST,"",""); ?>
	  </td>	  
      <td><!-- <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/> --></td>
    </tr>
  </table>
</form>

