<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>

<script type="text/javascript" >
<?php
	validateNumeroDossier();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Numéro de Dossier");
	$js->endCheckFunction();	
?>
</script>

<body>
<form action="<?php echo ("$path/controler/ActionControler.php"); ?>" method="post" name="manage">
<?php hiddenControler("DossierControler"); ?>
<?php hiddenAction(ACTION_CONSULT_EVT); ?>
<?php hiddenParamN($param->param3,3); ?>  
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>Cabinet</td>
      <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Numéro de dossier</td>
      <td>&nbsp;<?php text("","dossier:numero"); ?></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
	  	<?php customSubmit("value='Consulter les événements'",ACTION_CONSULT_EVT,"","","validateInput"); ?>
	  </td>	  
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
