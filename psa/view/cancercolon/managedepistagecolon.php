<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $depistageCancerColon ?>

<script type="text/javascript" >
<?php
	compareDates();
	validateDate();
	dateInRange();
	validateNumeroDossier();
	$js = new JSValidation();	
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
	$js->dateInRange("depistageCancerColon:date","Date du dépistage");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("DepistageCancerColonControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
  
 Ce formulaire permet à tout instant de collecter des données utiles au protocole dépistage du cancer du colon.<br><br>
Il s'appuie sur les données les plus récentes du patient (résultats d'examens, antécédents)<br><br>
 <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>Cabinet</td>
      <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Numéro de dossier</td>
      <td>&nbsp;<?php text("size='10'","dossier:numero"); ?></td>
    </tr>
	<tr>
      <td>Date du dépistage</td>
      <td>&nbsp;<?php text("size='10' onkeyup='formate_date(this)'","depistageCancerColon:date"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'>
	  	<?php customSubmit("value='Valider'",ACTION_NEW,"","","validateInput"); ?>
		<?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),"","validateInput"); ?>
		<?php customSubmit("value='Liste'",ACTION_LIST,"",""); ?>
		
	  </td>	  
      <td>	  
	    <!-- <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/> -->
	</td>
    </tr>
  </table>

</form>
